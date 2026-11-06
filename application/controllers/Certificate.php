<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends CI_Controller
{
    private $cfg;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('certificate_model', 'certs');
        $this->load->model('certificate_log_model', 'logs');
        $this->config->load('certificate', TRUE);
        $this->cfg = $this->config->item('certificate');
        date_default_timezone_set($this->cfg['timezone']);
    }

    /** GET /certificate */
    public function index()
    {
        // Allow the main website to embed this page.
        header_remove('X-Frame-Options');
        $this->output->set_header("Content-Security-Policy: frame-ancestors 'self' " . $this->cfg['frame_ancestors']);

        $this->load->view('certificate/index', array('year' => $this->certs->current_year()));
    }

    /** POST /certificate/lookup  (AJAX, JSON) */
    public function lookup()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(array('ok' => false, 'code' => 'method'), 405);
        }

        $nric = preg_replace('/\D/', '', (string) $this->input->post('nric'));
        $ip   = $this->input->ip_address();
        $ua   = (string) $this->input->user_agent();
        $year = $this->certs->current_year();

        if (strlen($nric) !== 12) {
            return $this->json(array('ok' => false, 'code' => 'invalid',
                'zh' => '請輸入完整 12 位數字。', 'en' => 'Please enter all 12 digits.'), 422);
        }

        if ($this->logs->recent_misses($ip, $this->cfg['miss_window']) >= $this->cfg['max_misses']) {
            return $this->json(array('ok' => false, 'code' => 'throttled',
                'zh' => '查詢次數過多，請稍後再試。', 'en' => 'Too many attempts. Please try again later.'), 429);
        }

        $rows = $this->certs->find_by_nric($nric, $year);
        $this->logs->record('search', $year, $nric, !empty($rows), NULL, $ip, $ua);

        if (empty($rows)) {
            return $this->json(array('ok' => false, 'code' => 'not_found',
                'zh' => '找不到此身份證號碼的獎狀，請檢查號碼是否正確。',
                'en' => 'No certificate found for this NRIC. Please check the number.'));
        }

        $certs = array();
        foreach ($rows as $r) {
            $is_award = $r['cert_type'] === 'award';
            $drive_id = $this->drive_id($r['pdf_url']);
            $level_en = ($is_award && isset($this->cfg['levels'][$r['award_level']])) ? $this->cfg['levels'][$r['award_level']] : NULL;
            $certs[] = array(
                'zh'       => $is_award ? '入圍獎狀' : '參與獎狀',
                'en'       => $is_award ? 'Award Certificate' : 'Participation Certificate',
                'level_zh' => $is_award ? $r['award_level'] : NULL,
                'level_en' => $level_en,
                'preview'  => $drive_id ? 'https://drive.google.com/file/d/' . $drive_id . '/preview' : NULL,
                'download' => site_url('certificate/download/' . $this->make_token((int) $r['id'])),
            );
        }

        return $this->json(array(
            'ok'       => true,
            'name'     => $rows[0]['name'],
            'group_zh' => $rows[0]['group_code'] . '組',
            'group_en' => 'Group ' . $rows[0]['group_code'],
            'certs'    => $certs,
        ));
    }

    /** GET /certificate/download/{token} */
    public function download($token = '')
    {
        $id = $this->verify_token((string) $token);
        if ($id === NULL) {
            $this->output->set_status_header(403)->set_output('連結已過期，請重新查詢。 / This link has expired. Please search again.');
            return;
        }

        $row      = $this->certs->find($id);
        $drive_id = $row ? $this->drive_id($row['pdf_url']) : NULL;
        if (!$row || !$drive_id) {
            show_404();
        }

        // Log first, so every download (including the Drive fallback) is counted.
        $this->logs->record('download', $row['year'], $row['nric'], TRUE, $id,
            $this->input->ip_address(), (string) $this->input->user_agent());

        $pdf = $this->fetch_pdf($drive_id);
        if ($pdf === NULL) {
            redirect('https://drive.google.com/file/d/' . $drive_id . '/view');
            return;
        }

        $label = $row['cert_type'] === 'award' ? '入圍獎狀' : '參與獎狀';
        $name  = preg_replace('/[\\\\\/:*?"<>|\x00-\x1f]/u', '', $row['name']);
        $file  = '華光杯' . $row['year'] . '_' . $row['group_code'] . '組_' . $name . '_' . $label . '.pdf';

        $this->output
            ->set_content_type('application/pdf')
            ->set_header('Content-Disposition: attachment; filename="certificate.pdf"; filename*=UTF-8\'\'' . rawurlencode($file))
            ->set_header('Cache-Control: private, max-age=0')
            ->set_output($pdf);
    }

    // ---------------------------------------------------------------

    private function json($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    private function drive_id($url)
    {
        if ($url && preg_match('~(?:/d/|[?&]id=)([A-Za-z0-9_-]{20,})~', $url, $m)) {
            return $m[1];
        }

        return NULL;
    }

    private function secret()
    {
        $s = $this->cfg['secret'] !== '' ? $this->cfg['secret'] : (string) $this->config->item('encryption_key');
        if ($s === '') {
            show_error('Set $config[\'secret\'] in application/config/certificate.php (or the encryption_key in config.php).', 500);
        }

        return $s;
    }

    private function make_token($id)
    {
        $exp = time() + $this->cfg['token_ttl'];

        return $id . '.' . $exp . '.' . hash_hmac('sha256', $id . '|' . $exp, $this->secret());
    }

    private function verify_token($token)
    {
        $p = explode('.', $token);
        if (count($p) !== 3 || !ctype_digit($p[0]) || !ctype_digit($p[1]) || (int) $p[1] < time()) {
            return NULL;
        }

        return hash_equals(hash_hmac('sha256', $p[0] . '|' . $p[1], $this->secret()), $p[2]) ? (int) $p[0] : NULL;
    }

    /** Fetch a PDF from Drive once, then serve it from application/cache/certificates/. */
    private function fetch_pdf($drive_id)
    {
        $dir = APPPATH . 'cache/certificates/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, TRUE);
        }
        $file = $dir . $drive_id . '.pdf';
        if (is_file($file) && filesize($file) > 0) {
            return file_get_contents($file);
        }

        $urls = array(
            'https://drive.usercontent.google.com/download?id=' . $drive_id . '&export=download&confirm=t',
            'https://drive.google.com/uc?export=download&id=' . $drive_id,
        );
        foreach ($urls as $u) {
            $ch = curl_init($u);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_USERAGENT      => 'Mozilla/5.0',
            ));
            $body = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($body !== FALSE && $code === 200 && strpos($body, '%PDF') === 0) {
                @file_put_contents($file, $body);

                return $body;
            }
        }

        return NULL;
    }
}

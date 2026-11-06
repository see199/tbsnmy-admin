<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Staff report: who has / hasn't searched or downloaded. */
class Certificate_report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_staff();
        $this->load->database();
        $this->load->helper(array('url', 'html'));
        $this->load->model('certificate_model', 'certs');
        $this->config->load('certificate', TRUE);
        date_default_timezone_set($this->config->item('timezone', 'certificate'));
    }

    /**
     * IMPORTANT: replace the body with YOUR existing staff-login check, e.g.
     *   if (!$this->session->userdata('staff_logged_in')) { redirect('login'); }
     * Until you do, this page refuses everyone (it shows NRICs).
     */
    private function require_staff()
    {
        //show_error('Add your staff login check in Certificate_report::require_staff() first.', 403);
    }

    public function index()
    {
        $year   = (int) ($this->input->get('year') ?: $this->certs->current_year());
        $status = (string) $this->input->get('status');
        $group  = in_array($this->input->get('group'), array('A', 'B'), TRUE) ? $this->input->get('group') : '';

        $rows = $this->rows($year, $status, $group);

        if ($this->input->get('export')) {
            return $this->csv($rows, $year);
        }

        $misses = $this->db->query(
            "SELECT nric, COUNT(*) AS tries, MAX(created_at) AS last_try
               FROM certificate_logs
              WHERE event_type='search' AND found=0 AND year=?
              GROUP BY nric ORDER BY tries DESC, last_try DESC LIMIT 50",
            array($year)
        )->result_array();

        $this->load->view('certificate/report', compact('rows', 'misses', 'year', 'status', 'group'));
    }

    private function rows($year, $status, $group)
    {
        switch ($status) {
            case 'never':       $having = 'HAVING searches = 0'; break;
            case 'no_download': $having = 'HAVING searches > 0 AND award_dl + part_dl = 0'; break;
            default:            $having = '';
        }
        $binds     = array($year);
        $group_sql = '';
        if ($group !== '') {
            $group_sql = 'AND c.group_code = ?';
            $binds[]   = $group;
        }

        $sql = "SELECT c.nric, MAX(c.name) AS name, MAX(c.group_code) AS group_code,
                  MAX(CASE WHEN c.cert_type='award' THEN c.award_level END) AS award_level,
                  (SELECT COUNT(*) FROM certificate_logs s
                    WHERE s.event_type='search' AND s.found=1 AND s.nric=c.nric AND s.year=c.year) AS searches,
                  (SELECT MAX(s.created_at) FROM certificate_logs s
                    WHERE s.event_type='search' AND s.found=1 AND s.nric=c.nric AND s.year=c.year) AS last_search,
                  (SELECT COUNT(*) FROM certificate_logs d JOIN certificates x ON x.id=d.certificate_id
                    WHERE d.event_type='download' AND x.nric=c.nric AND x.year=c.year AND x.cert_type='award') AS award_dl,
                  (SELECT COUNT(*) FROM certificate_logs d JOIN certificates x ON x.id=d.certificate_id
                    WHERE d.event_type='download' AND x.nric=c.nric AND x.year=c.year AND x.cert_type='participation') AS part_dl
                FROM certificates c
               WHERE c.year = ? $group_sql
               GROUP BY c.nric, c.year
               $having
               ORDER BY group_code, name";

        return $this->db->query($sql, $binds)->result_array();
    }

    private function csv($rows, $year)
    {
        $h = fopen('php://temp', 'r+');
        fputcsv($h, array('組別', '姓名', 'NRIC', '獎項', '查詢次數', '最後查詢', '入圍獎狀下載次數', '參與獎狀下載次數'));
        foreach ($rows as $r) {
            fputcsv($h, array($r['group_code'], $r['name'], '="' . $r['nric'] . '"', $r['award_level'],
                $r['searches'], $r['last_search'], $r['award_level'] ? $r['award_dl'] : '', $r['part_dl']));
        }
        rewind($h);
        $csv = stream_get_contents($h);
        fclose($h);

        $this->output
            ->set_content_type('text/csv', 'utf-8')
            ->set_header('Content-Disposition: attachment; filename="certificate-report-' . $year . '.csv"')
            ->set_output("\xEF\xBB\xBF" . $csv);
    }
}

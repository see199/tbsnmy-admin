<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Election extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form', 'election_helper'));
        $this->load->model('election_model');
        $this->load->model('contact_model');
    }

    public function form($token = '') {
        if (empty($token)) {
            $this->load_error_view('無效的連結！請確認您的網址是否正確。');
            return;
        }

        $submission = $this->election_model->get_submission_by_token($token);
        if (!$submission) {
            $this->load_error_view('查無此申報連結！可能該連結已失效。');
            return;
        }

        if ($submission['status'] === 'approved') {
            $this->load_error_view('此屆改選名單已核准並生效！無法再次編輯。若有修改需求，請聯絡馬密總秘書處。');
            return;
        }

        // Check if there is already a submitted draft, prefill from it if status is 'submitted'
        $prefilled_members = array();
        $prefilled_contact = array();
        $ajk_session = $submission['ajk_session'];

        if ($submission['status'] === 'submitted' && !empty($submission['submitted_data'])) {
            $draft = json_decode($submission['submitted_data'], true);
            $ajk_session = isset($draft['ajk_session']) ? $draft['ajk_session'] : $submission['ajk_session'];
            $prefilled_members = isset($draft['members']) ? $draft['members'] : array();
            $prefilled_contact = isset($draft['contact_person']) ? $draft['contact_person'] : array();
        } else {
            // Load current board members of the chapter as prefilled template
            $current_ajk = $this->contact_model->get_chapter_ajk($submission['chapter_id']);
            $current_ajk = $this->order_committee_members($current_ajk);
            
            foreach ($current_ajk as $c) {
                // Prefill active board positions (exclude normal members)
                if ($c['position'] !== '會員') {
                    $prefilled_members[] = array(
                        'position'     => $c['position'],
                        'name_chinese' => $c['name_chinese'],
                        'name_dharma'  => $c['name_dharma'],
                        'name_malay'   => $c['name_malay'],
                        'nric'         => $c['nric'],
                        'phone_mobile' => $c['phone_mobile'],
                        'email'        => $c['email'],
                        'address'      => isset($c['address']) ? $c['address'] : ''
                    );
                }
            }
            
            // Prefill contact person from existing chapter data
            $prefilled_contact = array(
                'name'  => isset($submission['current_contact_person']) ? $submission['current_contact_person'] : '',
                'phone' => isset($submission['current_phone']) ? $submission['current_phone'] : '',
                'email' => !empty($submission['current_contact_email']) ? $submission['current_contact_email'] : (isset($submission['current_email']) ? $submission['current_email'] : '')
            );
        }

        $data = array(
            'submission'  => $submission,
            'ajk_session' => $ajk_session,
            'members'     => $prefilled_members,
            'contact'     => $prefilled_contact,
            'token'       => $token
        );

        $this->load->view('election/form_view', $data);
    }

    public function submit($token = '') {
        if (empty($token)) {
            show_error('無效的請求！');
        }

        $submission = $this->election_model->get_submission_by_token($token);
        if (!$submission || $submission['status'] === 'approved') {
            show_error('此改選連結已失效或已核准。');
        }

        $post = $this->input->post();
        
        $ajk_session = trim($post['ajk_session']);
        $members_post = isset($post['members']) ? $post['members'] : array();

        $cleaned_members = array();

        foreach ($members_post as $m) {
            if (empty($m['nric']) && empty($m['name_chinese'])) {
                continue; // Skip completely empty rows
            }

            // Detect and read custom position
            $position = $m['position'];
            if ($position === '其他') {
                $position = isset($m['position_custom']) ? trim($m['position_custom']) : '理事';
            }

            // 1. Chinese Character Translation (Simplified to Traditional)
            $name_chinese = translate_simplified_to_traditional(trim($m['name_chinese']));
            $position     = translate_simplified_to_traditional(trim($position));

            // Auto-generate Dharma Name if empty
            $name_dharma = '';
            if (isset($m['name_dharma']) && trim($m['name_dharma']) !== '') {
                $name_dharma = translate_simplified_to_traditional(trim($m['name_dharma']));
            } else {
                $len = mb_strlen($name_chinese, 'UTF-8');
                if ($len >= 2) {
                    $name_dharma = '蓮花' . mb_substr($name_chinese, -2, 2, 'UTF-8');
                } else {
                    $name_dharma = '蓮花' . $name_chinese;
                }
            }

            $address = '';
            if (isset($m['address'])) {
                $address = translate_simplified_to_traditional(trim($m['address']));
            }

            // 2. Normalizing NRIC & Phone numbers
            $nric = format_malaysian_nric(trim($m['nric']));
            $phone_mobile = format_malaysian_phone(trim($m['phone_mobile']));

            $cleaned_members[] = array(
                'position'     => $position,
                'name_chinese' => $name_chinese,
                'name_dharma'  => $name_dharma,
                'name_malay'   => strtoupper(trim($m['name_malay'])), // malay/english names in uppercase
                'nric'         => $nric,
                'phone_mobile' => $phone_mobile,
                'email'        => isset($m['email']) ? strtolower(trim($m['email'])) : '',
                'address'      => $address
            );
        }

        // Contact mapping details
        $contact_name  = translate_simplified_to_traditional(trim($post['contact_name']));
        $contact_phone = trim($post['contact_phone']);
        $contact_email = strtolower(trim($post['contact_email']));

        $submitted_data = array(
            'ajk_session'    => $ajk_session,
            'members'        => $cleaned_members,
            'contact_person' => array(
                'name'  => $contact_name,
                'phone' => $contact_phone,
                'email' => $contact_email
            )
        );

        $this->election_model->update_submission($submission['id'], array(
            'status'         => 'submitted',
            'submitted_data' => json_encode($submitted_data),
            'submitted_at'   => date('Y-m-d H:i:s')
        ));

        redirect('election/success');
    }

    public function success() {
        $this->load->view('election/success_view');
    }

    public function ajax_get_contact() {
        $token = $this->input->post('token');
        if (empty($token) || !$this->election_model->get_submission_by_token($token)) {
            echo json_encode(array());
            return;
        }

        $nric = format_malaysian_nric($this->input->post('nric'));
        $contact = $this->contact_model->get_contact_by_nric($nric, true);
        
        // Fetch address field safely directly from tbs_contact to avoid join issues
        if ($contact && isset($contact['contact_id'])) {
            $details = $this->contact_model->db->select('address')->where('contact_id', $contact['contact_id'])->get('tbs_contact')->row_array();
            $contact['address'] = isset($details['address']) ? $details['address'] : '';
        }
        
        echo json_encode($contact);
    }

    private function load_error_view($message) {
        $data['message'] = $message;
        $this->load->view('election/error_view', $data);
    }

    private function order_committee_members($chapter_members) {
        $position_order = ["堂主","顧問","永久顧問","法律顧問","法務顧問","行政顧問","常住","主席","總會長","署理主席","署理總會長","副主席","副總會長","總秘書","秘書","副總秘書","副秘書","總財政","財政","副總財政","副財政","總務","副總務","公關"];
        $last_position = ["理事","中央理事","委員","稽查師","查賬","員工"];
        
        usort($chapter_members, function($a, $b) use ($position_order, $last_position) {
            $a_pos = array_search($a['position'], $position_order);
            $b_pos = array_search($b['position'], $position_order);
            $a_last = array_search($a['position'], $last_position);
            $b_last = array_search($b['position'], $last_position);
            
            if ($a_pos !== false && $b_pos !== false) {
                return $a_pos - $b_pos;
            }
            if ($a_pos !== false) return -1;
            if ($b_pos !== false) return 1;
            
            if ($a_last !== false && $b_last !== false) {
                return $a_last - $b_last;
            }
            if ($a_last !== false) return 1;
            if ($b_last !== false) return -1;
            
            return 0;
        });
        
        return $chapter_members;
    }
}

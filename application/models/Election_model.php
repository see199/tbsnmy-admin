<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Election_model extends CI_Model {

    public $db;

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('local', TRUE);
        $this->load->model('contact_model');
        $this->load->model('backend_model');
    }

    public function get_all_submissions() {
        $this->db->select('s.*, c.name_chinese as chapter_name, c.name_malay as chapter_name_malay, c.url_name as chapter_url, c.membership_id, c.tb_id')
                 ->from('tbs_chapter_election_submission s')
                 ->join('tbs_chapter c', 's.chapter_id = c.chapter_id', 'left')
                 ->order_by('s.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_submission_by_token($token) {
        $this->db->select('s.*, c.name_chinese as chapter_name, c.name_malay as chapter_name_malay, c.url_name as chapter_url, c.membership_id, c.tb_id, c.contact_person as current_contact_person, c.phone as current_phone, c.email as current_email, c.contact_email as current_contact_email')
                 ->from('tbs_chapter_election_submission s')
                 ->join('tbs_chapter c', 's.chapter_id = c.chapter_id', 'left')
                 ->where('s.token', $token);
        $res = $this->db->get()->result_array();
        return $res ? $res[0] : null;
    }

    public function get_submission_by_id($id) {
        $this->db->select('s.*, c.name_chinese as chapter_name, c.name_malay as chapter_name_malay, c.url_name as chapter_url, c.membership_id, c.tb_id, c.contact_person as current_contact_person, c.phone as current_phone, c.email as current_email, c.contact_email as current_contact_email')
                 ->from('tbs_chapter_election_submission s')
                 ->join('tbs_chapter c', 's.chapter_id = c.chapter_id', 'left')
                 ->where('s.id', $id);
        $res = $this->db->get()->result_array();
        return $res ? $res[0] : null;
    }

    public function get_approved_submissions_by_chapter($chapter_id) {
        $this->db->select('*')
                 ->from('tbs_chapter_election_submission')
                 ->where('chapter_id', $chapter_id)
                 ->where('status', 'approved')
                 ->order_by('approved_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function create_submission($chapter_id, $ajk_session, $token) {
        $data = array(
            'chapter_id'  => $chapter_id,
            'ajk_session' => $ajk_session,
            'token'       => $token,
            'status'      => 'pending',
            'created_at'  => date('Y-m-d H:i:s')
        );
        $this->db->insert('tbs_chapter_election_submission', $data);
        return $this->db->insert_id();
    }

    public function update_submission($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('tbs_chapter_election_submission', $data);
    }

    public function delete_submission($id) {
        $this->db->where('id', $id);
        return $this->db->delete('tbs_chapter_election_submission');
    }

    /**
     * Approves an election submission and merges it into chapters, contacts, and chapter members.
     * $data contains: ajk_session, members (array of board members), contact_person (array with name, phone, email)
     */
    public function approve_submission($id, $data) {
        $submission = $this->get_submission_by_id($id);
        if (!$submission) return false;

        $chapter_id = $submission['chapter_id'];
        $ajk_session = $data['ajk_session'];
        $members = isset($data['members']) ? $data['members'] : array();
        
        $new_contact_ids = array();

        // 1. Process each board member
        foreach ($members as $m) {
            if (empty($m['nric'])) continue;

            $nric = $m['nric'];
            $name_chinese = isset($m['name_chinese']) ? $m['name_chinese'] : '';
            $name_dharma  = isset($m['name_dharma']) ? $m['name_dharma'] : '';
            $name_malay   = isset($m['name_malay']) ? $m['name_malay'] : '';
            $phone_mobile = isset($m['phone_mobile']) ? $m['phone_mobile'] : '';
            $email        = isset($m['email']) ? $m['email'] : '';
            $address      = isset($m['address']) ? $m['address'] : '';
            $position     = isset($m['position']) ? $m['position'] : '理事';

            // Check if contact already exists
            $existing_contact = $this->contact_model->get_contact_by_nric($nric, true);

            if ($existing_contact) {
                $contact_id = $existing_contact['contact_id'];
                
                // Update existing contact
                $this->contact_model->update_contact(array(
                    'contact_id'   => $contact_id,
                    'name_chinese' => $name_chinese,
                    'name_dharma'  => $name_dharma,
                    'name_malay'   => $name_malay,
                    'phone_mobile' => $phone_mobile,
                    'email'        => $email,
                    'address'      => $address,
                    'nric'         => $nric
                ));
            } else {
                // Insert new contact
                $res = $this->contact_model->add_contact(array(
                    'contact_id'   => '',
                    'name_chinese' => $name_chinese,
                    'name_dharma'  => $name_dharma,
                    'name_malay'   => $name_malay,
                    'phone_mobile' => $phone_mobile,
                    'email'        => $email,
                    'address'      => $address,
                    'nric'         => $nric
                ));
                
                if (isset($res['status']) && $res['status'] === 'duplicate' && isset($res['data']['contact_id'])) {
                    $contact_id = $res['data']['contact_id'];
                    
                    // Since it existed but nric wasn't matched initially, update it
                    $this->contact_model->update_contact(array(
                        'contact_id'   => $contact_id,
                        'name_chinese' => $name_chinese,
                        'name_dharma'  => $name_dharma,
                        'name_malay'   => $name_malay,
                        'phone_mobile' => $phone_mobile,
                        'email'        => $email,
                        'address'      => $address,
                        'nric'         => $nric
                    ));
                } else {
                    $contact_id = isset($res['contact_id']) ? $res['contact_id'] : '';
                }
            }

            if (!$contact_id) continue;

            $new_contact_ids[] = $contact_id;

            // Map/link contact to chapter in tbs_chapter_member
            $this->db->select('cm_id')
                     ->from('tbs_chapter_member')
                     ->where('chapter_id', $chapter_id)
                     ->where('contact_id', $contact_id);
            $existing_member = $this->db->get()->row_array();

            $cm_id = $existing_member ? $existing_member['cm_id'] : '';
            
            $this->contact_model->replace_chapter_member(array(
                'chapter_id' => $chapter_id,
                'contact_id' => $contact_id,
                'position'   => $position
            ), $cm_id);
        }

        // 2. Demote old board members not in the new board list to "會員"
        $this->db->select('cm_id, contact_id, position')
                 ->from('tbs_chapter_member')
                 ->where('chapter_id', $chapter_id)
                 ->where('position <>', '會員');
        $old_board = $this->db->get()->result_array();

        foreach ($old_board as $ob) {
            if (!in_array($ob['contact_id'], $new_contact_ids)) {
                $this->contact_model->replace_chapter_member(array(
                    'chapter_id' => $chapter_id,
                    'contact_id' => $ob['contact_id'],
                    'position'   => '會員'
                ), $ob['cm_id']);
            }
        }

        // 3. Update Dojo Official Contact Details
        $chapter_update = array(
            'ajk_session'    => $ajk_session,
            'contact_person' => isset($data['contact_person']['name']) ? $data['contact_person']['name'] : '',
            'phone'          => isset($data['contact_person']['phone']) ? $data['contact_person']['phone'] : '',
            'email'          => isset($data['contact_person']['email']) ? $data['contact_person']['email'] : '',
            'contact_email'  => isset($data['contact_person']['email']) ? $data['contact_person']['email'] : ''
        );
        $this->backend_model->update_chapter($chapter_update, $submission['chapter_url']);

        // 4. Archive final approved submission data and update status
        $this->update_submission($id, array(
            'status'         => 'approved',
            'submitted_data' => json_encode($data),
            'approved_at'    => date('Y-m-d H:i:s')
        ));

        return true;
    }
}

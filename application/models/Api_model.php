<?php

class api_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function get_event_list($chapter_name = ''){

		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT te.*, CONCAT(tm.name,tm.position) as master_name, tc.name_chinese as chapter_name FROM tbs_event as te 
		LEFT JOIN tbs_master as tm ON te.master_1 = tm.master_id
		LEFT JOIN tbs_chapter as tc ON te.chapter_url = tc.url_name
		WHERE te.end_date >= CURDATE()
		";

		if($chapter_name) $query .= " AND te.chapter_url = '$chapter_name'";
		$query .= "ORDER BY start_date";
        $i = $this->db->query($query);
        return ($i->num_rows() > 0) ? $i->result() : array();
	}

	public function get_chapter_details($url_name = ''){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_chapter WHERE url_name = '$url_name'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		if(count($res)) return json_encode($res[0]);
		
	}

	public function get_chapter_details_by_id($chapter_id = ''){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_chapter WHERE chapter_id = $chapter_id";
		$i = $this->db->query($query);
		$res = $i->result_array();
		if(count($res)) return $res[0];
		
	}

	public function get_all_chapter_details(){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_chapter";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return json_encode($res);
	}

	public function get_all_chapter_details_active(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('name_chinese,name_malay,CONCAT(address," ",postcode," ",city,", ",state) as full_address, contact_person, phone, fax, email, website, fb_page')
			->from('tbs_chapter')
			->where('status','A')
			->where('name_malay <>','')
			->where('name_malay <>','Pertubuhan Pengurusan Pusat Jagaan Warga Emas Harmopeace,Perak')
			->order_by('state,chapter_id');
		$i = $this->db->get();
		$res = $i->result_array();
		return json_encode($res);
	}

	public function get_all_chapter_details_active_by_state($state){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('name_chinese,name_malay,url_name,CONCAT(address," ",postcode," ",city,", ",state) as full_address, contact_person, phone, fax, email, website, fb_page')
			->from('tbs_chapter')
			->where('status','A')
			->where('state',urldecode($state))
			->where('name_malay <>','')
			->where('name_malay <>','Pertubuhan Pengurusan Pusat Jagaan Warga Emas Harmopeace,Perak')
			->order_by('state,chapter_id');
		$i = $this->db->get();
		$res = $i->result_array();
		return json_encode($res);
	}

	public function get_chapter_meeting_list(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT membership_id,chapter_id,name_chinese, meeting_id, meeting_pinyin, meeting_fpinyin, state FROM tbs_chapter WHERE meeting_pinyin <> '' AND meeting_id <> 'GA01' ORDER BY membership_id";
		$i = $this->db->query($query);
        return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_fb_list(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT chapter_id, fb_page, name_chinese FROM tbs_chapter WHERE fb_page IS NOT NULL AND fb_page <> ''";
		$i = $this->db->query($query);
        return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_member_meeting_list(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('membership_id, member_id, c.name_dharma as name_chinese, meeting_id, meeting_pinyin, meeting_fpinyin, m.status')
				->from('tbs_member m')
				->join('tbs_contact c','c.contact_id = m.member_id', 'left')
				->where('m.status','A')
				->order_by('membership_id');
		$i = $this->db->get();
        return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_all_chapter_coordinate(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT * FROM tbs_chapter WHERE coordinate IS NOT NULL AND coordinate <> ''";
		$i = $this->db->query($query);
        return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function check_verified_user($email){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('email',$email);
		$res = $this->db->get('verified_users');
		return $res->result_array();
	}

	public function update_verified_user($user){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('email',$user['email']);
		$this->db->update('verified_users',$user);
		
	}

	public function insert_verified_user($users){
		$this->db = $this->load->database('local', TRUE);
		$this->db->truncate('verified_users');
		$this->db->insert_batch('verified_users',$users);
		
	}

	public function get_all_verified_user(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->from('verified_users')
				->order_by('verified_date');
		$res = $this->db->get();
		return $res->result_array();
	}

}
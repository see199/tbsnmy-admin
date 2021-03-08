<?php
class Agm_model extends CI_Model {

	public $db;

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('local', TRUE);
	}

	public function get_chapter_list(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('name_chinese,membership_id,chapter_id')
			->where('membership_id <>','')
			->order_by('membership_id');

		$res = $this->db->get('tbs_chapter');
		return $res->result_array();
	}

	public function get_chapter_member_list(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('cm.* , c.name_chinese')
				->where('c.name_chinese <>','')
				->from('tbs_chapter_member cm')
				->join('tbs_contact c','c.contact_id = cm.contact_id', 'left');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function get_agm_attendance($year){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('year',$year);
		$res = $this->db->get('tbs_agm_attendance');
		return $res->result_array();
	}

	public function replace_agm_attendance($primary,$value){
		$this->db = $this->load->database('local', TRUE);

		$counter = 0;
		foreach($primary as $k => $v){
			$this->db->where($k,$v);
		}
		$this->db->from('tbs_agm_attendance');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$this->db = $this->load->database('local', TRUE);
			foreach($primary as $k => $v){
				$this->db->where($k,$v);
			}
			$this->db->update('tbs_agm_attendance',$value);

		}else{
			$this->db = $this->load->database('local', TRUE);
			$this->db->insert('tbs_agm_attendance',array_merge($primary,$value));
		}
	}
}
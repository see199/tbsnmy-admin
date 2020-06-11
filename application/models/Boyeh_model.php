<?php
class Boyeh_model extends CI_Model {

	public function __construct()
	{
		$this->db = $this->load->database('boyeh', TRUE);
	}

	public function insert_attendance($data){
		$this->db->insert('attendance',$data);
	}

	public function get_attendance($where) {

		$this->db->select('*');
		$this->db->order_by('id, datetime');
		foreach($where as $k => $v) $this->db->where($k,$v);
        $query = $this->db->get('attendance')->result_array();
        return $query;
	}


}
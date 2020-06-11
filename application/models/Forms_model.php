<?php

class Forms_model extends CI_Model {

	public $db;

	public function __construct(){
		parent::__construct();
		$this->db = $this->load->database('local', TRUE);
	}

	public function insert_form($table, $data){
		$this->db->insert('tbs_form_'.$table, $data);
		return ($this->db->affected_rows() != 1) ? false : $this->db->insert_id();;

	}

	public function update_form($table,$data,$id){

		$this->db->where('id_form_'.$table, $id);
		$this->db->update('tbs_form_'.$table, $data);
		return ($this->db->affected_rows() != 1) ? false : true;
	}

	public function list_form($table, $filter_data, $where){

		$this->db->where($where);
		$this->db->order_by($filter_data['order_by']);
		$this->db->limit($filter_data['l2'], $filter_data['l1']);
		$res = $this->db->get('tbs_form_'.$table);
		return $res->result_array();
	}

	public function count_total_form($table,$where){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('COUNT(*) AS count');
		$this->db->where($where);
		$query = $this->db->get('tbs_form_'.$table);

		$result = "0";
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$result = $row['count'];
		}
		return $result;
	}

	public function get_single_form($table,$id) {

		$res = $this->db->get_where('tbs_form_'.$table, array('id_form_'.$table => $id));

		$res = $res->result_array();
		return $res[0];
	}

	public function get_subform_list($fk_form_normal) {

		$res = $this->db->get_where('tbs_form_normal_list', array('fk_form_normal' => $fk_form_normal));
		return $res->result_array();
	}

}

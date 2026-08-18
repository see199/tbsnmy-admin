<?php

class Wforms_model extends CI_Model {

	public $db;

	public function __construct(){
		parent::__construct();
		$this->db = $this->load->database('local', TRUE);
	}

	public function search_wenxuan_duplicate($data){
		$this->db->where(array(
			'wenxuan_name' => $data['wenxuan_name'],
			'wenxuan_contact' => $data['wenxuan_contact'],
		));
		$res = $this->db->get('tbs_wenxuan_subscriber');
		return $res->result_array();
	}

	public function replace_wenxuan_subscriber($wenxuan_subscriber){
		$this->db = $this->load->database('local', TRUE);
		
		if (!empty($wenxuan_subscriber['wenxuan_id'])) {
			$id = $wenxuan_subscriber['wenxuan_id'];
			$this->db->where('wenxuan_id', $id);
			$this->db->update('tbs_wenxuan_subscriber', $wenxuan_subscriber);
			return $id;
		} else {
			unset($wenxuan_subscriber['wenxuan_id']);
			$this->db->insert('tbs_wenxuan_subscriber', $wenxuan_subscriber);
			return $this->db->insert_id();
		}
	}

	public function search_wenxuan_year_duplicate($wenxuan_id,$package_id){
		$this->db->where(array(
			'wenxuan_id' => $wenxuan_id,
			'package_id' => $package_id,
		));
		$res = $this->db->get('tbs_wenxuan_subscriber_year');
		return $res->result_array();
	}

	public function insert_wenxuan_year($data){
		$this->db->insert('tbs_wenxuan_subscriber_year', $data);
		return $this->db->insert_id();
	}

	public function update_subscriber_year($id, $data){
		$this->db->where('id', $id);
		return $this->db->update('tbs_wenxuan_subscriber_year', $data);
	}

	public function update_subscriber_year_by_md5($md5_id, $data){
		$this->db->where('md5_id', $md5_id);
		return $this->db->update('tbs_wenxuan_subscriber_year', $data);
	}

	public function update_subscriber_year_by_xendit_id($xendit_invoice_id, $data){
		$this->db->where('xendit_invoice_id', $xendit_invoice_id);
		return $this->db->update('tbs_wenxuan_subscriber_year', $data);
	}

	public function get_subscriber_year_by_xendit_id($xendit_invoice_id){
		$this->db->where('xendit_invoice_id', $xendit_invoice_id);
		$res = $this->db->get('tbs_wenxuan_subscriber_year');
		$result = $res->result_array();
		return !empty($result) ? $result[0] : NULL;
	}

	public function get_this_year_packages($year = NULL){
		if ($year) {
			$this->db->where('year', $year);
		}
		$this->db->where('package_name !=', '百元功德主');
		$this->db->order_by('year', 'DESC');
		$res = $this->db->get('tbs_wenxuan_subscriber_package');
		return $res->result_array();
	}

	public function get_latest_package_year(){
		$this->db->select('year');
		$this->db->order_by('year', 'DESC');
		$this->db->limit(1);
		$res = $this->db->get('tbs_wenxuan_subscriber_package');
		$row = $res->row_array();
		return !empty($row['year']) ? $row['year'] : date('Y');
	}

	public function get_subscriber_year_by_md5($md5_id){
		$this->db->where('md5_id', $md5_id);
		if (is_numeric($md5_id)) {
			$this->db->or_where('id', $md5_id);
		}
		$res = $this->db->get('tbs_wenxuan_subscriber_year');
		$result = $res->result_array();
		return !empty($result) ? $result[0] : NULL;
	}

	public function get_package_by_id($package_id){
		$this->db->where('package_id', $package_id);
		$res = $this->db->get('tbs_wenxuan_subscriber_package');
		$result = $res->result_array();
		return !empty($result) ? $result[0] : NULL;
	}

	public function get_subscriber_by_id($wenxuan_id){
		$this->db->where('wenxuan_id', $wenxuan_id);
		$res = $this->db->get('tbs_wenxuan_subscriber');
		$result = $res->result_array();
		return !empty($result) ? $result[0] : NULL;
	}

}

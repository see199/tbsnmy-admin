<?php
class Dizang_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('local', TRUE);
	}

	public function get_hash_password($password)
	{
		return $password;
	}

	public function check_login($email){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_dizang_user WHERE email = '$email'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function update_login($user_id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('user_id',$user_id);
		$this->db->update('tbs_dizang_user',array('last_login' => date('Y-m-d H:i:s')));
	}

	public function get_dizang_details($ids,$order='date'){
		$this->db = $this->load->database('local', TRUE);
		
		$res = $this->db->where_in('dizang_id', $ids)->from('tbs_dizang')->order_by($order)->get();
		return $res->result_array();
	}

	public function update_details($dizang){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('dizang_id',$dizang['dizang_id']);
		$this->db->update('tbs_dizang',$dizang);
	}

	public function add_details($dizang){
		$this->db = $this->load->database('local', TRUE);

		$this->db->insert('tbs_dizang',$dizang);
		return array("status" => "success", "dizang_id" => $this->db->insert_id());
	}

	
}
<?php
class Dharma_model extends CI_Model {

	public function __construct()
	{
		//$this->load->database();
	}

	public function get_hash_password($password)
	{
		return $password;
	}

	public function check_login($email){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_dharma_user WHERE email = '$email'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function update_login($user_id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('user_id',$user_id);
		$this->db->update('tbs_dharma_user',array('last_login' => date('Y-m-d H:i:s')));
	}

	
}
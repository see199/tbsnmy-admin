<?php
class Backend_model extends CI_Model {

	public function __construct()
	{
		//$this->load->database();
	}

	public function get_hash_password($password)
	{
		return $password;
	}

	public function get_all_chapter_url($allowed){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT url_name, name_chinese FROM tbs_chapter WHERE url_name <> '' AND url_name IS NOT NULL";
		if($allowed[0] != 'all'){
			$query .= ' AND url_name IN("'.join('","',$allowed).'")';
		}
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_all_chapter_id(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT chapter_id, name_chinese FROM tbs_chapter WHERE status IN('A','1','2')";
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_all_chapter_email(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT chapter_id, email, contact_email, name_chinese, state, url_name FROM tbs_chapter WHERE status IN('A','1','2') order by state";
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_all_chapter_contact(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT chapter_id, contact_person, phone, name_chinese, state, url_name FROM tbs_chapter WHERE status IN('A','1','2') order by state";
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_chapter_details($url_name = ''){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_chapter WHERE url_name = '$url_name'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res[0];
	}

	public function update_chapter($chapter,$url_name){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('url_name',$url_name);
		$this->db->update('tbs_chapter',$chapter);
		
	}

	public function get_all_member_email(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('m.membership_id, c.contact_id, c.name_dharma, email, c.phone_mobile')
				->where('m.status','A')
				->from('tbs_member m')
				->join('tbs_contact c','c.contact_id = m.member_id', 'left')
				->order_by('m.membership_id');
		$res = $this->db->get();
		return ($res->num_rows() > 0) ? $res->result_array() : array();
	}

	public function check_login($email){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_api_user WHERE email = '$email'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function update_login($user_id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('user_id',$user_id);
		$this->db->update('tbs_api_user',array('last_login' => date('Y-m-d H:i:s')));
	}

	public function log_activity($user_id,$activity){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('user_id',$user_id);
		$this->db->insert('tbs_api_user_log',array(
			'user_id'  => $user_id,
			'create_date' => date('Y-m-d H:i:s'),
			'activity' => $activity,
		));
	}

	public function get_all_user($filter_data){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_api_user ORDER BY ".$filter_data['order_by']." LIMIT " . $filter_data['l1'] . ", " . $filter_data['l2'];
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function count_total_user(){
		$this->db = $this->load->database('local', TRUE);

		$sql_query = "SELECT COUNT(*) AS count FROM tbs_api_user";
		
		$query = $this->db->query($sql_query);
        
        $result = "0";
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $result = $row['count'];
        }
        
        return $result;
	}

	public function get_user_details($user_id){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_api_user WHERE user_id = '$user_id'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function get_latest_activity($user_id){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_api_user_log WHERE user_id = '$user_id' ORDER BY create_date DESC LIMIT 1";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function get_user_activity($filter_data){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_api_user_log WHERE user_id = '".$filter_data['user_id']."' ORDER BY ".$filter_data['order_by']." LIMIT " . $filter_data['l1'] . ", " . $filter_data['l2'];
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function count_total_activity($user_id){
		$this->db = $this->load->database('local', TRUE);

		$sql_query = "SELECT COUNT(*) AS count FROM tbs_api_user_log WHERE user_id = '$user_id'";
		
		$query = $this->db->query($sql_query);
        
        $result = "0";
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $result = $row['count'];
        }
        
        return $result;
	}

	public function get_all_event($filter_data){
		$this->db = $this->load->database('local', TRUE);

		$where = ' chapter_url = "' . $filter_data['chapter_url'] . '" ';
		if($filter_data['show_all'] != '1') $where .= ' AND end_date >= NOW() ';

		$query = "SELECT * FROM tbs_event WHERE $where ORDER BY ".$filter_data['order_by']." LIMIT " . $filter_data['l1'] . ", " . $filter_data['l2'];

		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function count_total_event($filter_data){
		$this->db = $this->load->database('local', TRUE);

		$where = ' chapter_url = "' . $filter_data['chapter_url'] . '" ';
		if($filter_data['show_all'] != '1') $where .= ' AND end_date >= NOW() ';

		$sql_query = "SELECT COUNT(*) AS count FROM tbs_event WHERE $where ";
		
		$query = $this->db->query($sql_query);
        
        $result = "0";
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $result = $row['count'];
        }
        
        return $result;
	}

	public function get_master_name($master_id){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT * FROM tbs_master WHERE master_id = $master_id";
		$i = $this->db->query($query);
		$res = $i->result_array();
		if(!$res) echo $query;
		return $res[0];
	}

	public function get_all_master(){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT CONCAT(name,position) AS master_name, master_id FROM tbs_master WHERE position = '金剛上師'";
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function get_event($event_id){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT te.*, CONCAT(tm.name,tm.position) AS master_name FROM tbs_event te LEFT JOIN tbs_master tm ON te.master_1 = tm.master_id WHERE event_id = $event_id";
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function update_event($event){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('event_id',$event['event_id']);
		$this->db->update('tbs_event',$event);
	}

	public function insert_event($event){
		$this->db = $this->load->database('local', TRUE);
		$this->db->insert('tbs_event',$event);
		return $this->db->insert_id();
	}

	public function get_monthly_event($date){
		$this->db = $this->load->database('local', TRUE);
		$query = "SELECT te.*, CONCAT(tm.name,tm.position) AS master_name, tc.name_chinese as chapter_name FROM tbs_event te LEFT JOIN tbs_master tm ON te.master_1 = tm.master_id LEFT JOIN tbs_chapter tc ON te.chapter_url = tc.url_name WHERE te.start_date BETWEEN '".$date['start']."' AND '".$date['end']."' ORDER BY te.start_date";
		$i = $this->db->query($query);
		return ($i->num_rows() > 0) ? $i->result_array() : array();
	}
}
<?php
class Contact_model extends CI_Model {

	public $db;

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('local', TRUE);
	}

	public function get_contact_list($filter_data, $where){

		foreach($where as $k => $v)
		$this->db->or_like($k,$v);
		$this->db->order_by($filter_data['order_by']);
		$this->db->limit($filter_data['l2'], $filter_data['l1']);
		$res = $this->db->get('tbs_contact');
		return $res->result_array();
	}

	public function count_total_contact($where){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('COUNT(*) AS count');
		foreach($where as $k => $v)
		$this->db->or_like($k,$v);
		$query = $this->db->get('tbs_contact');

		$result = "0";
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$result = $row['count'];
		}
		return $result;
	}

	public function get_dharma_contact_list($filter_data, $where){

		$this->db->where('dharma_position',$where['dharma_position'])
				->group_start();
		foreach($where as $k => $v){
			if($k != 'dharma_position') $this->db->or_like($k,$v);
		}
		$this->db->group_end()
				->order_by($filter_data['order_by'])
				->limit($filter_data['l2'], $filter_data['l1'])
				->from('tbs_dharma_staff ds')
				->join('tbs_contact c', 'ds.contact_id = c.contact_id', 'left');
		$res = $this->db->get();
		write_file('application/logs/ttt.txt',print_r($this->db,1));
		return $res->result_array();
	}

	public function count_total_dharma_contact($where){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('COUNT(*) AS count');
		$this->db->where('dharma_position',$where['dharma_position'])
				->group_start();
		foreach($where as $k => $v){
			if($k != 'dharma_position') $this->db->or_like($k,$v);
		}
		$this->db->group_end()
				->from('tbs_dharma_staff ds')
				->join('tbs_contact c', 'ds.contact_id = c.contact_id', 'left');
		$query = $this->db->get();


		$result = "0";
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$result = $row['count'];
		}
		return $result;
	}

	public function get_contact_details($id = ''){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT tbs_contact.*, tbs_dharma_staff.chapter_id, tbs_dharma_staff.dharma_position, tbs_dharma_staff.event, tbs_dharma_staff.status FROM tbs_contact LEFT JOIN tbs_dharma_staff ON tbs_contact.contact_id = tbs_dharma_staff.contact_id WHERE tbs_contact.contact_id = '$id'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res[0];
	}

	public function get_contact_chapter($id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('cm.* , c.name_chinese, c.ajk_session, c.url_name')
				->where('cm.contact_id',$id)
				->from('tbs_chapter_member cm')
				->join('tbs_chapter c','c.chapter_id = cm.chapter_id', 'left');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function get_contact_member($id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('m.*')
				->where('m.member_id',$id)
				->from('tbs_member m')
				->join('tbs_contact c','c.contact_id = m.member_id', 'left');
		$res = $this->db->get();

		if($res->result_array()) return $res->result_array()[0];
		else return array();
	}

	public function get_chapter_ajk($id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('cm.* , c.*')
				->where('cm.chapter_id',$id)
				->where('cm.position <>','會員')
				->from('tbs_chapter_member cm')
				->join('tbs_contact c','c.contact_id = cm.contact_id', 'left');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function get_chapter_vip_ajk_email($id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('cm.* , c.email, c.name_dharma')
				->where('cm.chapter_id',$id)
				->where('c.email IS NOT NULL')
				->where('c.email <> ""')
				->where_in('cm.position',array('主席','署理主席','副主席','秘書','總務','副秘書','副總務','堂主'))
				->from('tbs_chapter_member cm')
				->join('tbs_contact c','c.contact_id = cm.contact_id', 'left');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function update_contact($contact){
		$this->db = $this->load->database('local', TRUE);

		// Null NRIC if empty
		if(!$contact['nric']) unset($contact['nric']);

		$this->db->where('contact_id',$contact['contact_id']);
		$this->db->update('tbs_contact',$contact);
		
	}

	public function add_contact($contact){
		$this->db = $this->load->database('local', TRUE);

		$counter = 0;
		foreach(array('nric','phone_mobile','email') as $v){
			if(isset($contact[$v]) && $contact[$v] != ''){
				$this->db->or_like($v,$contact[$v]);
			}else $counter++;
		}
		$this->db->from('tbs_contact');
		$query = $this->db->get();

		if ($query->num_rows() > 0 && $counter != 3) {
			$result = $query->row_array();

			return array("status" => "duplicate", "data" => $result);
		}else{
			$this->db = $this->load->database('local', TRUE);

			// Null NRIC if empty
			if(!$contact['nric']) unset($contact['nric']);

			$this->db->insert('tbs_contact',$contact);
			return array("status" => "success", "contact_id" => $this->db->insert_id());
		}


	}

	public function replace_dharma_info($dharma,$contact_id){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_dharma_staff',array_merge(array('contact_id' => $contact_id),$dharma));
	}

	public function replace_chapter_member($chapter_member,$cmid){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_chapter_member',array_merge(array('cm_id' => $cmid),$chapter_member));
	}

	public function delete_chapter_member($cmid){
		$this->db = $this->load->database('local', TRUE);
		$this->db->delete('tbs_chapter_member', array('cm_id' => $cmid));

	}

	public function replace_contact_member($contact_member,$member_id){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_member',array_merge(array('member_id' => $member_id),$contact_member));
	}

	public function get_dharma_insurance(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('di.* , c.contact_id, c.name_chinese, c.name_malay, c.nric, c.dob, c.name_dharma')
				->where_in('ds.dharma_position',array('SS','FS'))
				->where_in('ds.status',array('Normal','Rest'))
				->where_not_in('ds.contact_id',array(1,9,457)) // -- remove 1:高上師,9:羅上師,457:楊上師
				->from('tbs_dharma_staff ds')
				->join('tbs_contact c','c.contact_id = ds.contact_id', 'left')
				->join('tbs_dharma_insurance di','c.contact_id = di.contact_id', 'left')
				//->order_by('ds.dharma_position, c.name_malay');
				->order_by('c.name_malay');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function get_dharma_insurance_by_id($contact_id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('di.* , c.contact_id, c.name_chinese, c.name_malay, c.nric, c.dob, c.name_dharma')
				->where('ds.contact_id',$contact_id)
				->from('tbs_dharma_staff ds')
				->join('tbs_contact c','c.contact_id = ds.contact_id', 'left')
				->join('tbs_dharma_insurance di','c.contact_id = di.contact_id', 'left');

		$res = $this->db->get();
		if($res->result_array()) return $res->result_array()[0];
		else return array();
	}

	public function replace_dharma_insurance($insurance,$contact_id){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_dharma_insurance',array_merge(array('contact_id' => $contact_id),$insurance));

		return ($this->db->affected_rows() != 1) ? false : true;
	}

	////////////////////////////////////

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
<?php
class Wenxuan_model extends CI_Model {

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

		$query = "SELECT * FROM tbs_wenxuan_user WHERE email = '$email'";
		$i = $this->db->query($query);
		$res = $i->result_array();
		return $res;
	}

	public function update_login($user_id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('user_id',$user_id);
		$this->db->update('tbs_wenxuan_user',array('last_login' => date('Y-m-d H:i:s')));
	}

	public function get_list_chapter(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('s.*, c.name_chinese, c.membership_id, c.chapter_id as cid, CONCAT(c.address,", ",c.postcode," ",c.city,", ",c.state) AS mishu_address, c.mailing_address as mishu_mailing_address, c.contact_person as mishu_contact')
			->from('tbs_chapter c')
			->join('tbs_wenxuan_subscriber s','c.chapter_id = s.chapter_id', 'left')
			->where('s.type','chapter')
			->or_where('s.type IS NULL')
			->where_in('c.status',array('A','P','1'))
			->where_not_in('c.chapter_id',array(1,115,126)) // Ingore: 1般若，115其他，126大德
			->order_by('c.membership_id');
		$i = $this->db->get();
		return $i->result_array();
	}

	public function replace_subscriber($subscriber,$wenxuan_id,$type){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_wenxuan_subscriber',array_merge(array('wenxuan_id' => $wenxuan_id, 'type' => $type),$subscriber));

		return ($this->db->affected_rows() != 1) ? false : true;
	}

	public function get_list_contact(){
		$this->db = $this->load->database('local', TRUE);

		// Get All Contact
		$this->db->select('s.*')
			->from('tbs_wenxuan_subscriber s')
			->where('s.type','contact');
		$i = $this->db->get();
		foreach($i->result_array() as $r){
			$res[$r['wenxuan_id']] = $r;
		}

		// Get Previous Year
		$this->db->select('y.*, p.year')
			->from('tbs_wenxuan_subscriber_year y')
			->join('tbs_wenxuan_subscriber_package p','y.package_id = p.package_id', 'left')
			->where('p.year >=',date('Y') - 1);
		$i = $this->db->get();
		foreach($i->result_array() as $r){
			$res[$r['wenxuan_id']]['package'][$r['year']] = $r;
		}

		return $res;
	}

	public function replace_package_year($packages,$wenxuan_id){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_wenxuan_subscriber_year',array_merge(array('wenxuan_id' => $wenxuan_id),$packages));

		return ($this->db->affected_rows() != 1) ? false : true;
	}

	public function get_package(){
		$this->db = $this->load->database('local', TRUE);

		// Get All Contact
		$this->db->select('p.*')
			->from('tbs_wenxuan_subscriber_package p')
			->order_by('p.year DESC, p.package_name ASC');
		$i = $this->db->get();
		foreach($i->result_array() as $r){
			$res[$r['package_id']] = $r;
		}
		return $res;
	}

	public function replace_package($packages,$package_id){
		$this->db = $this->load->database('local', TRUE);
		$this->db->replace('tbs_wenxuan_subscriber_package',array_merge(array('package_id' => $package_id),$packages));

		return ($this->db->affected_rows() != 1) ? false : true;
	}

	public function snapshot_subscriber(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('COUNT(*) as total_subscriber, SUM(gmbook) as gmbook, SUM(tbnews_free) as tbnews_free, SUM(tbnews_paid) as tbnews_paid, SUM(randeng_free) as randeng_free, SUM(randeng_paid) as randeng_paid, MAX(update_date) as update_date, type')
			->from('tbs_wenxuan_subscriber')
			->group_by('type');
		$i = $this->db->get();
		return $i->result_array();
	}

	public function snapshot_package(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('COUNT(y.package_id) as total_subscriber, p.year, p.package_name, p.package_amount')
			->from('tbs_wenxuan_subscriber_year y')
			->join('tbs_wenxuan_subscriber_package p','p.package_id = y.package_id','left')
			->group_by('y.package_id')
			->order_by('p.year DESC, p.package_name ASC');
		$i = $this->db->get();
		return $i->result_array();
	}

	public function search_chapter($name_chinese){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT name_chinese, chapter_id FROM tbs_chapter WHERE name_chinese like '%$name_chinese%'";
		//echo $query;
		$i = $this->db->query($query);
		$res = $i->result_array();
		if(count($res)) return $res[0];
	}

	public function search_contact($name){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT wenxuan_id FROM tbs_wenxuan_subscriber WHERE wenxuan_name like '$name'";
		//echo $query;
		$i = $this->db->query($query);
		$res = $i->result_array();
		if(count($res)) return $res[0];
	}
}
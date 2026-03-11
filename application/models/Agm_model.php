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

		$this->db->select('name_chinese,membership_id,chapter_id,status')
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

	public function get_member_meeting_list(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->select('membership_id, member_id, c.name_dharma as name_chinese, name_malay, nric, email')
				->from('tbs_member m')
				->join('tbs_contact c','c.contact_id = m.member_id', 'left')
				->where('m.status','A')
				->order_by('membership_id');
		$i = $this->db->get();
        return ($i->num_rows() > 0) ? $i->result_array() : array();
	}

	public function add_registrant($primary,$value){
		$this->db = $this->load->database('local', TRUE);

		$counter = 0;
		foreach($primary as $k => $v){
			$this->db->where($k,$v);
		}
		$this->db->from('tbs_agm_zoom_reg');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$this->db = $this->load->database('local', TRUE);
			foreach($primary as $k => $v){
				$this->db->where($k,$v);
			}
			$this->db->update('tbs_agm_zoom_reg',$value);

		}else{
			$this->db = $this->load->database('local', TRUE);
			$this->db->insert('tbs_agm_zoom_reg',array_merge($primary,$value));
		}
	}

	public function del_registrant($email){
		$this->db = $this->load->database('local', TRUE);
		$this->db->delete('tbs_agm_zoom_reg', array('email' => $email));
	}

	public function del_all_registrant(){
		$this->db = $this->load->database('local', TRUE);
		$this->db->truncate('tbs_agm_zoom_reg');
	}

	public function reset_login_date(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->update('tbs_agm_zoom_reg',array(
			'login_time' => "0000-00-00 00:00:00"
		));
	}

	public function login_zoom($nric){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('nric',$nric);
		$this->db->update('tbs_agm_zoom_reg',array(
			'login_time' => date('Y-m-d H:i:s')
		));
	}

	public function login_on_site($nric){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('nric',$nric);
		$this->db->update('tbs_agm_zoom_reg',array(
			'login_time' => date('Y-m-d H:i:s'),
			'zoom_link' => '現場出席'
		));
	}

	public function update_voted($nric){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('nric',$nric);
		$this->db->update('tbs_agm_zoom_reg',array(
			'voted' => 1
		));
	}

	public function check_duplicate_email_registrant($email){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('email',$email);
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();
	}

	public function count_same_chapter($chapter_id){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('chapter_id',$chapter_id)
				->order_by('reg_date');
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();
	}

	public function get_registrant($nric){
		$this->db = $this->load->database('local', TRUE);
		$result = $this->db->get_where('tbs_agm_zoom_reg', array('nric' => $nric))->row_array();

		return $result;
	}

	public function get_registrant_link($nric){
		$this->db = $this->load->database('local', TRUE);
		$this->db->select('zoom_link')
				->where('nric',$nric);
		$res = $this->db->get('tbs_agm_zoom_reg');
		if ($res->num_rows() > 0) {
			return $res->result_array()[0];
		}else return array("zoom_link" => '');
	}

	public function list_zoom_registrant(){
		$this->db = $this->load->database('local', TRUE);
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();
	}

	public function list_login_zoom_registrant(){
		$this->db = $this->load->database('local', TRUE);
		$this->db->where('login_time <>','0000-00-00 00:00:00')
				->where('membership_id <>','列席');
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();
	}

	public function list_login_zoom_registrant_all(){
		$this->db = $this->load->database('local', TRUE);
		$this->db->where('login_time <>','0000-00-00 00:00:00');
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();
	}

	public function list_zoom_registrant_nonvote(){
		$this->db = $this->load->database('local', TRUE);

		$this->db->where('membership_id','列席');
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();
	}

	public function api_add_zoom_registrant($meeting_id,$user){

		$access_token = $this->get_zoom_access_token();
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.zoom.us/v2/meetings/".$meeting_id."/registrants",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($user),
			CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$access_token,
				"content-type: application/json"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return array("error"=>$err);
		} else {
		  return json_decode($response,1);
		}

	}

	public function api_del_zoom_registrant($meeting_id,$registrant_id){

		$access_token = $this->get_zoom_access_token();
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.zoom.us/v2/meetings/".$meeting_id."/registrants/".$registrant_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "DELETE",
			CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$access_token
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return array("error"=>$err);
		} else {
		  return json_decode($response,1);
		}
	}

    private function get_zoom_access_token(){

    	// JWT Depreciated, Change to use Server-to-Server OAuth
        /*
        POST https://zoom.us/oauth/token?grant_type=account_credentials&account_id={accountId}
        HTTP/1.1
        Host: zoom.us
        Authorization: Basic Base64Encoder(clientId:clientSecret)
        */
        $setting = json_decode(read_file('application/logs/agm_setting.txt'),1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://zoom.us/oauth/token?grant_type=account_credentials&account_id=".$setting['account_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(array(
                'grant_type' => 'account_credentials',
                'account_id' => $setting['account_id'],
            )),
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".base64_encode($setting['client_id'].":".$setting['client_secret']),
                "Accept: application/json",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $access_token = json_decode($response)->access_token;
        return($access_token);
    }

	// Temporary Unused
	private function api_get_zoom_registrant_link($meeting_id, $page){

		// Generate at: https://marketplace.zoom.us/develop/apps/hpkMCtQGQa2t0MAGvIutEA/credentials
		// Token expired on 2021-06-27 23:59
		$access_token = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6Ikw4NWFOandIUkhtVkJZaU55VTh4NWciLCJleHAiOjE2MzMzMzE0MDAsImlhdCI6MTYyNjMyOTcwNn0.BC2a76bcYCFoMYt2y_aEV4Dszw_vIcmOZLXlALJpAXY";

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.zoom.us/v2/meetings/".$meeting_id."/registrants?page_number=".$page."&page_size=300&status=approved",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$access_token
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo "<pre>";print_r(json_decode($response,1));
		}
	}

	public function count_onsite_attendance(){
		$this->db = $this->load->database('local', TRUE);
		$this->db->select('membership_id, chapter_id, contact_id, COUNT(*) as total')
				->where('login_time <>','0000-00-00 00:00:00')
				->where('membership_id <>','列席')
				->where('zoom_link','現場出席')
				->group_by('membership_id');
		$res = $this->db->get('tbs_agm_zoom_reg');
		return $res->result_array();		
	}
}
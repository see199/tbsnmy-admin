<?php

class Event extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper(array('url','file','common','form'));
		$this->config->load('siteinfo', TRUE);
	}

	public function index($id=0){
        
        @$this->msg = ($this->msg) ? $this->msg : "";

        // Show error if no ID is entered
        if(!$id){
            echo "Error ID";
            exit;
        }

        // Get Event Details
        $this->db = $this->load->database('local', TRUE);
        $event = $this->db
                ->where('event_id', $id)
                ->from('zwh_event')
                ->get()
                ->result_array()[0];
        //print_pre($event);

        // Load view
        $this->load->view('event/'.$event['event_view'], array(
            'event' => $event,
            'chapter_country' => $this->chapter_country(),
            'master_country'  => $this->master_country(),
            'msg' => $this->msg,
        ));
	}


    public function ajax_get_master_by_country(){
        $country = $this->input->post('country');

        // Get Master by Country
        $this->db = $this->load->database('local', TRUE);
        $list = $this->db
                ->where('country', $country)
                ->from('zwh_master')
                ->get()
                ->result_array();

        $options = array(array('value' => '', 'text' => ''));
        foreach($list as $master){
            $options[] = array('value' => $master['master_id'], 'text' => $master['name'].$master['position']);
        }
        echo json_encode($options);
    }

    public function ajax_get_chapter_by_country(){
        $country = $this->input->post('country');

        // Get Master by Country
        $this->db = $this->load->database('local', TRUE);
        $list = $this->db
                ->where('country', $country)
                ->from('zwh_chapter')
                ->get()
                ->result_array();

        $options = array(array('value' => '', 'text' => ''));
        foreach($list as $chapter){
            $options[] = array('value' => $chapter['chapter_id'], 'text' => $chapter['name']);
        }
        echo json_encode($options);
    }

    public function register($id=0){

        $event_reg = $this->input->post();

        // Rename Master Position by substr() and Rename 教授師
        @$event_reg['master_position'] = ($event_reg['master_name']) ? mb_substr($event_reg['master_name'],-2) : "";
        if($event_reg['master_position'] == '授師') $event_reg['master_position'] = '教授師';

        //Unique Key Checking
        $unique = array('event_id','master_id','chapter_id','event_type','event_date');
        foreach($unique as $u)
            if(@!isset($event_reg[$u])) $event_reg[$u] = '0';

        // Check duplicates for Unique
        $this->db = $this->load->database('local', TRUE);
        $q = $this->db;
        foreach($unique as $u) $q->where($u,$event_reg[$u]);
        $res = $q->get('zwh_event_reg');

        //print_pre($event_reg);

        // Insert DB if no duplicates
        if ($res->num_rows() == 0) {
            // Insert the record
            $this->db->insert('zwh_event_reg',$event_reg);
            $reg_id = $this->db->insert_id();
            $this->msg = "成功登入!";
        }else{
            $this->msg = "Error: 無法重複輸入！";
        }
        
        // Load Index View
        $this->index($id);

    }

    private function master_country(){

        return array(
            '',
            '澳洲',
            '巴西',
            '加拿大',
            '香港',
            '印尼',
            '日本',
            '馬來西亞',
            '荷蘭',
            '新加坡',
            '台灣',
            '英國',
            '美國',
        );
    }

    private function chapter_country(){

        return array(
            '',
            '澳洲',
            '巴西',
            '汶萊',
            '加拿大',
            '多明尼加共和國',
            '法國',
            '德國',
            '香港',
            '印尼',
            '愛爾蘭',
            '日本',
            '馬來西亞',
            '荷蘭',
            '巴拿馬',
            '波多黎各',
            '新加坡',
            '西班牙',
            '瑞典',
            '台灣',
            '泰國',
            '英國',
            '美國',
            '越南',
        );
    }

    public function stats($id=0){

        // Show error if no ID is entered
        if(!$id){
            echo "Error ID";
            exit;
        }

        // Get Event Details
        $this->db = $this->load->database('local', TRUE);
        $event = $this->db
                ->where('event_id', $id)
                ->from('zwh_event')
                ->get()
                ->result_array()[0];
        //print_pre($event);

        // Stats Calculation
        $this->db = $this->load->database('local', TRUE);
        $stats = $this->db
                ->where('event_id', $id)
                ->from('zwh_event_reg')
                ->get()
                ->result_array();

        //print_pre($stats);
        // Load view
        $this->load->view('event/'.$event['stats_view'], array(
            'event' => $event,
            'stats' => $stats,
            'chapter_country' => $this->chapter_country(),
            'master_country'  => $this->master_country(),
        ));


    }


}



?>
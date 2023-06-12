<?php

class Event extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper(array('url','file','common','form'));
		$this->config->load('siteinfo', TRUE);
	}

	public function index($id=0,$lang=''){
        
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
        $this->load->view('event/'.$event['event_view'].$lang, array(
            'event' => $event,
            'chapter_country' => $this->chapter_country($lang),
            'master_country'  => $this->master_country($lang),
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
        $country = $this->get_chinese_country($this->input->post('country'));



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

    public function ajax_get_chapter_timezone(){

        $chapter_id = $this->input->post('chapter_id');

        /* -- 時區 與 國家 對比
        ID  時區     國家
        1   2       馬來西亞 / 新加坡 / 汶萊
        2   2       台灣
        3   2       香港
        4   4       泰國 / 越南 / 柬埔寨
        5   4       印尼（西部）
        6   2       印尼（中部）
        7   3       印尼（東部）
        8   3       日本
        9   5       關島
        10  5       澳洲（東南部）
        11  4       澳洲（聖誕島）
        12  2       澳洲（西部）
        13  6       紐西蘭
        14  7       巴西（聖保羅）/（福塔雷薩）
        15  8       美國（西岸)/加拿大（BC省）
        16  9       美國（山地時區）/加拿大（AB省）
        17  10      美國（中部時區）
        18  11      美國（東岸時區）/加拿大（ON/QC省）/巴拿馬
        19  11      波多黎各/多米尼加共和國
        20  12      夏威夷
        21  13      英國/葡萄牙
        22  14      歐洲-1：瑞典/挪威/德國/瑞士/法國/西班牙/意大利
        23  15      歐洲-2：芬蘭/希臘
        24  14      南非
        */

        $this->db = $this->load->database('local', TRUE);
        $timezone = $this->db
                ->select('timezone')
                ->where('chapter_id', $chapter_id)
                ->from('zwh_chapter')
                ->get()
                ->result_array()[0]['timezone'];
        echo $timezone;


    }

    public function register($id=0,$lang=''){

        $event_reg = $this->input->post();
        @$this->msg = ($this->msg) ? $this->msg : "";

        if(@$event_reg['chapter_country'] == '不在名單內'){
            $event_reg['chapter_name'] = $event_reg['chapter_name_other'];
            $event_reg['chapter_country'] = '道場不在名單內';
        }
        unset($event_reg['chapter_name_other']);


        if(is_array($event_reg['master_name'])){
            foreach(array_filter($event_reg['master_name']) as $i => $name){
                $new_event_reg = $event_reg;
                $new_event_reg['event_counter'] = 1;
                $new_event_reg['event_date'] = $event_reg['event_date'][$i];
                $new_event_reg['master_position'] = $event_reg['master_position'][$i];
                $new_event_reg['master_name'] = $event_reg['master_name'][$i]."(".$new_event_reg['master_position'].")";
                $this->msg .= '<br />'.$new_event_reg['master_name'].'('.$new_event_reg['event_date'].'): '.$this->insert_event_reg($new_event_reg);

            }
        }else
            $this->msg = $this->insert_event_reg($event_reg);
        
        // Load Index View
        $this->index($id,$lang);

    }

    private function insert_event_reg($event_reg){

        // Rename Master Position by substr() and Rename 教授師
        if(!isset($event_reg['master_position'])){
            $event_reg['master_position'] = mb_substr($event_reg['master_name'],-2);
            if($event_reg['master_position'] == '授師') $event_reg['master_position'] = '教授師';
        }

        // Set multiple_event_date to json format
        if(@$event_reg['event_date_multiple'])
            @$event_reg['event_date_multiple'] = json_encode($event_reg['event_date_multiple']);

        //Unique Key Checking
        $unique = array('event_id','master_id','master_name','chapter_id','event_type','event_date','event_date_multiple');
        foreach($unique as $u){
            if(@!isset($event_reg[$u]) && $u != 'event_date') $event_reg[$u] = '0';
            if(@!isset($event_reg[$u]) && $u == 'event_date') $event_reg[$u] = '0000-00-00';
        }

        // Chapter's Country Multi-language
        $event_reg['chapter_country'] = (isset($event_reg['chapter_country'])) ? $this->get_chinese_country($event_reg['chapter_country']) : "";

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
            if($event_reg['event_id'] == 3){
                $this->sendmail(array(
                    'email' => $event_reg['chapter_email'],
                    'title' => '成功登記線上法會',
                    'text'  => '您好，<br><br>您已成功登記線上法會，感恩護持！<br> 請注意收取zoom密碼。<br/><br/>文宣處合十',
                ));
                return "登記完成，感恩護持！ Success Registration!";
            }
            else
                return "成功登入!";
        }else{
            return "Error: 無法重複輸入！";
        }

    }

    private function master_country($lang){

        return array(
            '',
            '澳洲',
            '巴西',
            '加拿大',
            '柬埔寨',
            '香港',
            '印尼',
            '日本',
            '馬來西亞',
            '荷蘭',
            '新加坡',
            '台灣',
            '英國',
            '美國',
            '其他',
        );
    }

    private function get_chinese_country($country){
        $countries = array(
            '澳洲' => '澳洲',
            '巴西' => '巴西',
            '汶萊' => '汶萊',
            '加拿大' => '加拿大',
            '多明尼加共和國' => '多明尼加共和國',
            '法國' => '法國',
            '德國' => '德國',
            '香港' => '香港',
            '印尼' => '印尼',
            '愛爾蘭' => '愛爾蘭',
            '日本' => '日本',
            '馬來西亞' => '馬來西亞',
            '荷蘭' => '荷蘭',
            '紐西蘭' => '紐西蘭',
            '巴拿馬' => '巴拿馬',
            '波多黎各' => '波多黎各',
            '新加坡' => '新加坡',
            '西班牙' => '西班牙',
            '瑞典' => '瑞典',
            '台灣' => '台灣',
            '泰國' => '泰國',
            '英國' => '英國',
            '美國' => '美國',
            '越南' => '越南',
            '不在名單內' => '不在名單內',
            'Australia' => '澳洲',
            'Brazil' => '巴西',
            'Brunei' => '汶萊',
            'Canada' => '加拿大',
            'Dominican Republic' => '多明尼加共和國',
            'France' => '法國',
            'German' => '德國',
            'Hong Kong' => '香港',
            'Indonesia' => '印尼',
            'Ireland' => '愛爾蘭',
            'Japan' => '日本',
            'Malaysia' => '馬來西亞',
            'Netherland' => '荷蘭',
            'New Zealand' => '紐西蘭',
            'Panama' => '巴拿馬',
            'Puerto Rico' => '波多黎各',
            'Singapura' => '新加坡',
            'Spain' => '西班牙',
            'Sweden' => '瑞典',
            'Taiwan' => '台灣',
            'Thailand' => '泰國',
            'United Kingdom' => '英國',
            'United States' => '美國',
            'Vietnam' => '越南',
            'Other' => '不在名單內',
        );
        return $countries[$country];
    }

    private function chapter_country($lang){

        if($lang == 'en'){
            return array(
                '',
                'Australia',
                'Brazil',
                'Brunei',
                'Canada',
                'Dominican Republic',
                'France',
                'German',
                'Hong Kong',
                'Indonesia',
                'Ireland',
                'Japan',
                'Malaysia',
                'Netherland',
                'New Zealand',
                'Panama',
                'Puerto Rico',
                'Singapura',
                'Spain',
                'Sweden',
                'Taiwan',
                'Thailand',
                'United Kingdom',
                'United States',
                'Vietnam',
                'Other'
            );
        } else if ($lang == 'id'){
            return array(
                '',
                'Indonesia',
            );
        }

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
            '紐西蘭',
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
            '不在名單內',
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
            'chapter_country' => $this->chapter_country(''),
            'master_country'  => $this->master_country(''),
        ));


    }

    public function test(){
        $this->sendmail(array(
            'email' => 'see199@gmail.com',
            'title' => '成功登記線上法會',
            'text'  => '您好，<br><br>您已成功登記線上法會，感恩護持！<br> 請注意收取zoom密碼。<br/><br/>文宣處合十',
        ));
    }

    public function sendmail($data){
        echo 1;
        $this->load->library('email');
        
        $this->email->initialize(array(
            'protocol' => 'mail',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ));

        $this->email->from('wenxuan@tbsn.org', '宗委會文宣處');
        $this->email->to($data['email']);
        $this->email->subject($data['title']);
        $this->email->message($data['text']);
        echo $this->email->send();

    }


}



?>
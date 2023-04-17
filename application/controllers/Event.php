<?php

class Event extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper(array('url','file','common','form'));
		$this->config->load('siteinfo', TRUE);
	}

	public function index($id=0){
        
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
        ));
	}

    public function ajax_get_master_by_country(){
        $selectedValue = $_GET['selectedValue'];
        $options = array();
        if ($selectedValue === '加拿大') {
            $options = array(
                array('value' => 'a', 'text' => 'A'),
                array('value' => 'b', 'text' => 'B')
            );
        } else{
            $options = array(
                array('value' => 'x', 'text' => 'X'),
                array('value' => 'y', 'text' => 'Y')
            );
        }
    echo json_encode($options);
    }

    private function master_country(){

        return array(
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
            '澳洲',
            '巴西',
            '汶萊',
            '柬埔寨',
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
            '新西蘭',
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

    public function sql(){
/**
 *  zwh_master
CREATE TABLE `zwh_master` (
 `master_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(10) NOT NULL,
 `position` enum('上師','仁波切','教授師','法師','講師','助教') NOT NULL,
 `country` varchar(10) NOT NULL,
 PRIMARY KEY (`master_id`),
 KEY `country` (`country`),
 KEY `position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 * zwh_event
CREATE TABLE `zwh_event` (
 `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `title` varchar(200) NOT NULL,
 `description` text NOT NULL,
 `banner_url` varchar(200) NOT NULL,
 `date_start` date NOT NULL,
 `date_end` date NOT NULL,
 `event_view` varchar(50) NOT NULL,
 `stats_view` varchar(50) NOT NULL,
 PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 * 
 * zwh_chapter
CREATE TABLE `zwh_chapter` (
 `chapter_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(20) NOT NULL,
 `country` varchar(10) NOT NULL,
 PRIMARY KEY (`chapter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 * 
 * zwh_event_reg
CREATE TABLE `zwh_event_reg` (
 `reg_id` int(11) NOT NULL AUTO_INCREMENT,
 `event_id` int(11) NOT NULL,
 `master_id` int(11) NOT NULL,
 `master_country` varchar(10) NOT NULL,
 `master_chapter` varchar(50) NOT NULL,
 `master_position` varchar(10) NOT NULL,
 `master_name` varchar(10) NOT NULL,
 `chapter_id` int(11) NOT NULL,
 `chapter_name` varchar(20) NOT NULL,
 `chapter_country` int(10) NOT NULL,
 `event_type` varchar(10) NOT NULL,
 `event_date` date NOT NULL,
 `create_date` date NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`reg_id`),
 KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
**/


    }

	public function member(){
        $this->load->view('agm/meeting_view',array(
        	'chapter'    => $this->api_model->get_member_meeting_list(),
        	'type'       => 'member',
        	'attendance' => $this->read_attendance($this->config->item('file_attendance')),
        ));
    }

    public function stats(){
    	$this->load->view('agm/stats_view',array());
    }

    public function ajax_stats(){
        
        // 現場記錄
        $data = $this->read_attendance($this->config->item('file_attendance'));
        $chapter = $chapter_member = $member = 0;
        foreach($data as $type => $v){
            foreach($v as $id => $total){
                if((int)$total > 0){
                    $$type++;
                    if($type == 'chapter') $chapter_member += $total;
                }
            }
        }

        // Zoom 登入
        $this->update_online_attendance();
        $data_online = $this->read_attendance($this->config->item('file_attendance_online'));
        $online_chapter = $online_chapter_member = $online_member = 0;
        foreach($data_online as $type => $v){
            foreach($v as $id => $total){
                if((int)$total > 0){
                   
                    // Comparing with 現場
                    if($type == 'member'){
                        if(@!isset($data['member'][$id]) || @$data['member'][$id] == 0){
                            $online_member++;
                        }
                    }else{

                        // 如果現場 + Zoom >= 3人，Zoom 人數 = 3 - 現場人數
                        // 如果不是的話， Zoom 人數直接加上去
                        // Cater for: 現場沒人 / 現場全部出席 / 部分現場 + 部分Zoom
                        if(!isset($data['chapter'][$id])) $data['chapter'][$id] = 0;
                        $to_add = (($data['chapter'][$id] + $total) >= 3) ? 3-$data['chapter'][$id] : $total;
                        $online_chapter_member += $to_add;

                        // 如果沒有現場者，才加入道場數額
                        if(!isset($data['chapter'][$id]) || $data['chapter'][$id] == 0)
                        $online_chapter += 1;
                    }
                }
            }
        }

        echo json_encode(array(
            'total_chapter' => $chapter,
            'total_member'  => $member,
            'total_chapter_member' => $chapter_member,

            'total_online_chapter' => $online_chapter,
            'total_online_member'  => $online_member,
            'total_online_chapter_member' => $online_chapter_member,

            'total_total_chapter' => $chapter + $online_chapter,
            'total_total_member'  => $member + $online_member,
            'total_total_chapter_member' => $chapter_member + $online_chapter_member,
        ));
    }

    public function update_attendance(){
        $file = $this->config->item('file_attendance');
    	$attendance = $this->read_attendance($file);
    	$attendance[$this->input->post('type')][$this->input->post('id')] = $this->input->post('status');

    	$txt = '';
    	foreach($attendance as $type => $v){
    		foreach($v as $id => $total){
    			$txt .= $type."\t".$id."\t".$total."\n";
    		}
    	}
    	write_file($file,$txt);
    }

    private function read_attendance($file){
        $attendance = array();
    	$data = explode("\n",read_file($file));
    	foreach($data as $v){
    		if($v){
	    		list($type,$id,$total) = explode("\t",$v);
	    		if($type && $id) $attendance[$type][$id] = $total;
	    	}
    	}
    	return $attendance;
    }

    public function update_online_attendance(){
        
        $this->load->model('agm_model');
        $list = $this->agm_model->list_login_zoom_registrant();

        $sorted_list = array();
        foreach($list as $l){

            // 團體會員
            if($l['membership_id'] > 5000){
                @$sorted_list['chapter'][$l['chapter_id']] += 1;
            }
            // 個人會員
            elseif($l['membership_id'] > 1000){
                @$sorted_list['member'][$l['contact_id']] += 1;
            }
        }

        // Write to Log file
        $txt = '';
        foreach($sorted_list as $type => $v){
            foreach($v as $id => $total){
                $txt .= $type."\t".$id."\t".$total."\n";
            }
        }
        write_file($this->config->item('file_attendance_online'),$txt);
    }


    // Registration for Zoom
    public function register($msg_code='',$msg=''){

        // Sorting by state
        $chapter_by_state = array();
        $states = array(
            "" => " - 請選擇 | Please select - ",
            '1' => 'Johor',
            '2' => 'Kedah',
            '3' => 'Kelantan',
            '4' => 'Melaka',
            '5' => 'Negeri Sembilan',
            '6' => 'Pahang',
            '7' => 'Penang',
            '8' => 'Perak',
            '9' => 'Perlis',
            '10' => 'Sabah',
            '11' => 'Sarawak',
            '12' => 'Selangor',
            '13' => 'Terengganu',
            '14' => 'W.Persekutan',
        );
        foreach($states as $k => $v){
            $chapter_by_state[$k][""] = " - 請選擇 | Please select - ";
        }

        foreach($this->api_model->get_chapter_meeting_list() as $chapter){
            $chapter_by_state[array_keys($states,$chapter['state'])[0]][$chapter['chapter_id']] = $chapter['name_chinese'];
            //$states[$chapter['state']] = $chapter['state'];
        }

        $this->load->view('agm/register_view',array(
            'chapter_by_state' => $chapter_by_state,
            'states'           => $states,
            'msg_code'         => $msg_code,
            'msg'              => $msg,
            'setting'          => json_decode(read_file('application/logs/agm_setting.txt'),1),
        ));
    }

    // Registration for Zoom
    public function register2(){ $this->register_personal();}
    public function register_personal($msg_code='',$msg=''){

        $this->load->view('agm/register_personal_view',array(
            'msg_code'         => $msg_code,
            'msg'              => $msg,
            'setting'          => json_decode(read_file('application/logs/agm_setting.txt'),1),
        ));
    }

    public function get_contact_by_nric($nric){
        $this->load->model('contact_model');
        $contact = $this->contact_model->get_contact_by_nric($nric);

        //Only show 1 email
        @$contact['email'] = explode(",",$contact['email'])[0];

        echo json_encode($contact);
    }

    public function get_contact_by_nric_agm_personal($nric){
        $this->load->model('contact_model');
        $contact = $this->contact_model->get_contact_by_nric_agm_personal($nric);

        //Only show 1 email
        @$contact['email'] = explode(",",$contact['email'])[0];

        echo json_encode($contact);
    }

    public function zoom_login(){

        $post = $this->input->post();
        if(count($post)){
            $this->load->model('agm_model');
            $res = $this->agm_model->get_registrant_link($post['nric']);
            if($res['zoom_link']){
                $this->agm_model->login_zoom($post['nric']);
                header('Location: '.$res['zoom_link']);
            }
            else{
                $this->load->view('agm/login_view',array('error' => 'user_not_found','setting' => json_decode(read_file('application/logs/agm_setting.txt'),1),));
            }

        }else{
            $this->load->view('agm/login_view',array('setting' => json_decode(read_file('application/logs/agm_setting.txt'),1),));
        }

        
    }

    public function add_registrant(){
        $this->load->model('agm_model');

        $post    = $this->input->post();
        $chapter = $this->api_model->get_chapter_details_by_id($post['chapter_id']);

        // Check if Email exists or not, if exists, display error
        $duplicate_emails = $this->agm_model->check_duplicate_email_registrant($post['email']);
        if(count($duplicate_emails) > 0){
            if($duplicate_emails[0]['nric'] != $post['nric']){
                        
                list($e1,$e2) = explode('@',$post['email']);
                $new_email1 = $e1.'+1@'.$e2;
                $new_email2 = $e1.'+2@'.$e2;

                $this->register('error',"Error: 此電郵已登記！請使用其他電郵。<br />建議修改： ".$post['email']." 改成 $new_email1 或 $new_email2");
    
                return;
            }
        }

        // Limit 3 Registrant -- Become 列席
        $count_same_chapter = $this->agm_model->count_same_chapter($post['chapter_id']);
        if(count($count_same_chapter) >= 3){
            $chapter['membership_id'] = '列席';
        }

        // Submit to Zoom API
        $post['first_name'] = $chapter['membership_id'] . '-' . preg_replace(array('/真佛宗/','/同修會/','/雷藏寺/','/堂/','/（籌委會）/'),'',$chapter['name_chinese']) .'-';

        // If choose ZOOM only call ZOOM API
        
        if($post['online']){
            $setting = json_decode(read_file('application/logs/agm_setting.txt'),1);
            $registrant = $this->agm_model->api_add_zoom_registrant($setting['zoom_id'],array(
                "email"      => $post['email'],
                "first_name" => $post['first_name'],
                "last_name"  => $post['name_chinese'],
            ),$setting['access_token']);

            // If Zoom return empty
            if(!isset($registrant['registrant_id'])){
                $err_code = "TB01";
                $err_msg  = "EMPTY_RESPONSE";
                if($registrant['code']) $err_code = $registrant['code'];
                if($registrant['message']) $err_msg = $registrant['message'];
                $this->register('error',"暫時無法登記，ZOOM 暫無回應，請稍後再試。 Failed to register due to empty response from ZOOM! Please try again later. Error code: $err_code : $err_msg");
                return ;
            }

        }else{
            $registrant = array(
                'registrant_id' => '',
                'join_url' => '現場出席',
            );
        }

        // if Error, Display ERROR to contact admin
        if(isset($registrant['code']) && $post['online']){

            $this->register('error',"無法登記！請聯絡馬密總秘書處。 Failed to register! Please contact secretary. Error Message: " . $registrant['code'].":".$registrant['message']);

            return ;
        }


        $registrant_primary = array(
            'nric'         => $post['nric'],
        );
        $registrant_value = array(
            'chapter_id'   => $post['chapter_id'],
            'contact_id'   => $post['contact_id'],
            'name_chinese' => $post['name_chinese'],
            'name_malay'   => $post['name_malay'],
            'membership_id'=> $chapter['membership_id'],
            'phone_mobile' => $post['phone_mobile'],
            'position'     => $post['position'],
            'email'        => $post['email'],
            'first_name'   => $post['first_name'],
            'last_name'    => $post['name_chinese'],
            'registrant_id'=> @$registrant['registrant_id'],
            'zoom_link'    => @$registrant['join_url'],
        );
        $this->agm_model->add_registrant($registrant_primary,$registrant_value);

        $this->register('success_reg',"成功登記！Registration Success!");
        return;
    }

    public function add_registrant_personal(){
        $this->load->model('agm_model');
        $this->load->model('contact_model');

        $post    = $this->input->post();
        $contact = $this->contact_model->get_contact_member($post['contact_id']);

        // Check if Email exists or not, if exists, display error
        $duplicate_emails = $this->agm_model->check_duplicate_email_registrant($post['email']);
        if(count($duplicate_emails) > 0){
            if($duplicate_emails[0]['nric'] != $post['nric']){
                        
                list($e1,$e2) = explode('@',$post['email']);
                $new_email1 = $e1.'+1@'.$e2;
                $new_email2 = $e1.'+2@'.$e2;

                $this->register_personal('error',"Error: 此電郵已登記！請使用其他電郵。<br />建議修改： ".$post['email']." 改成 $new_email1 或 $new_email2");
    
                return;
            }
        }

        // 非個人會員
        if(!isset($contact['membership_id'])){

            $this->register_personal('error',"無法登記！請確保您是個人會員（弘法人員或連續2屆的中央理事）。 Failed to register! Please make sure you are Personal Membership (Dharma Personal or AJK for 2 session. <br /><a href='".base_url('agm/register')."'>點擊登記道場代表 Click to Register as Group</a>");

            return ;
        }

        // Submit to Zoom API
        $post['first_name'] = $contact['membership_id'] .'-';

        // If choose ZOOM only call ZOOM API
        
        if($post['online']){
            $setting = json_decode(read_file('application/logs/agm_setting.txt'),1);
            $registrant = $this->agm_model->api_add_zoom_registrant($setting['zoom_id'],array(
                "email"      => $post['email'],
                "first_name" => $post['first_name'],
                "last_name"  => $post['name_chinese'],
            ),$setting['access_token']);

            // If Zoom return empty
            if(!isset($registrant['registrant_id'])){
                $err_code = "TB01";
                $err_msg  = "EMPTY_RESPONSE";
                if(isset($registrant['code'])) $err_code = $registrant['code'];
                if(isset($registrant['message'])) $err_msg = $registrant['message'];
                $this->register('error',"暫時無法登記，ZOOM 暫無回應，請稍後再試。 Failed to register due to empty response from ZOOM! Please try again later. Error code: $err_code : $err_msg");
                return ;
            }
        }else{
            $registrant = array(
                'registrant_id' => '',
                'join_url' => '現場出席',
            );
        }

        // if Error, Display ERROR to contact admin
        if(isset($registrant['code']) && $post['online']){

            $this->register_personal('error',"無法登記！請聯絡馬密總秘書處。 Failed to register! Please contact secretary. Error Message: " . $registrant['code'].":".$registrant['message']);

            return ;
        }


        $registrant_primary = array(
            'nric'         => $post['nric'],
        );
        $registrant_value = array(
            'chapter_id'   => "",
            'contact_id'   => $post['contact_id'],
            'name_chinese' => $post['name_chinese'],
            'name_malay'   => $post['name_malay'],
            'membership_id'=> $contact['membership_id'],
            'phone_mobile' => $post['phone_mobile'],
            'position'     => "",
            'email'        => $post['email'],
            'first_name'   => $post['first_name'],
            'last_name'    => $post['name_chinese'],
            'registrant_id'=> @$registrant['registrant_id'],
            'zoom_link'    => @$registrant['join_url'],
        );
        $this->agm_model->add_registrant($registrant_primary,$registrant_value);

        $this->register_personal('success_reg',"成功登記！Registration Success!");
        return;
    }

    public function vote() {
        // Load form helper to set form validation rules
        $this->load->library('form_validation');
        $this->load->model('agm_model');
        
        // Load AGM Settings
        $data['setting'] = json_decode(read_file('application/logs/agm_setting.txt'),1);
        $data['setting']['gform'] = '1FAIpQLSdj9KEURkmamwtFYT6Kn9CJZStVNbR8MKRmlDLKW6NVD405AA';

        // Check if form has been submitted
        if(count($this->input->post())){

            // Set form validation rules
            $this->form_validation->set_rules('nric', 'NRIC', 'required');

            // Check if form validation passed
            if ($this->form_validation->run() == FALSE) {
                // If form validation failed, reload the view with error message
                $data['error'] = 'Please enter your NRIC.';
                $this->load->view('agm/vote_form', $data);
            } else {
                // If form validation passed, check if NRIC exists in database
                $nric = $this->input->post('nric');
                $result = $this->agm_model->get_registrant($nric);

                if (empty($result)) {
                    // If NRIC is not found, reload the view with error message
                    $data['error'] = 'Sorry, you are not allowed to vote.';
                } elseif ($result['voted'] == 1) {
                    // If user has already voted, reload the view with error message
                    $data['error'] = 'Sorry, you have already voted.';
                } else {
                    // If NRIC is found and user has not voted, load Google form
                    $google_form_url = 'https://docs.google.com/forms/d/e/'.$data['setting']['gform'].'/viewform';
                    $data['google_form_url'] = $google_form_url;

                    // Update voted field in database
                    //$this->agm_model->update_voted($nric);

                }
            }
        } 

        $this->load->view('agm/vote_form', $data);

        
    }

}



?>
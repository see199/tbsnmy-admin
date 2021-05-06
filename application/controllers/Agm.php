<?php

class AGM extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper(array('url','file','common','form'));
		$this->config->load('siteinfo', TRUE);
	}

	public function index(){
		$this->chapter();
	}

    public function chapter(){
        $this->load->view('agm/meeting_view',array(
        	'chapter'    => $this->api_model->get_chapter_meeting_list(),
        	'type'       => 'chapter',
        	'attendance' => $this->read_attendance(),
        ));
    }

	public function member(){
        $this->load->view('agm/meeting_view',array(
        	'chapter'    => $this->api_model->get_member_meeting_list(),
        	'type'       => 'member',
        	'attendance' => $this->read_attendance(),
        ));
    }

    public function stats(){
    	$data = $this->read_attendance();

    	$chapter = $chapter_member = $member = 0;
    	foreach($data as $type => $v){
    		foreach($v as $id => $total){
    			if((int)$total > 0){
    				$$type++;
    				if($type == 'chapter') $chapter_member += $total;
    			}
    		}
    	}
    	$this->load->view('agm/stats_view',array(
        	'total_chapter' => $chapter,
        	'total_member'  => $member,
        	'total_chapter_member' => $chapter_member,
        ));
    }

    public function update_attendance(){
    	$attendance = $this->read_attendance();
    	$attendance[$this->input->post('type')][$this->input->post('id')] = $this->input->post('status');

    	$txt = '';
    	foreach($attendance as $type => $v){
    		foreach($v as $id => $total){
    			$txt .= $type."\t".$id."\t".$total."\n";
    		}
    	}
    	write_file($this->config->item('file_attendance'),$txt);
    }

    private function read_attendance(){
    	$data = explode("\n",read_file($this->config->item('file_attendance')));
    	foreach($data as $v){
    		if($v){
	    		list($type,$id,$total) = explode("\t",$v);
	    		if($type && $id) $attendance[$type][$id] = $total;
	    	}
    	}
    	return $attendance;
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
        ));
    }

    public function get_contact_by_nric($nric){
        $this->load->model('contact_model');
        $contact = $this->contact_model->get_contact_by_nric($nric);

        echo json_encode($contact);
    }

    public function zoom_login(){

        $post = $this->input->post();
        if(count($post)){
            $this->load->model('agm_model');
            $res = $this->agm_model->get_registrant_link($post['nric']);
            if($res['zoom_link']) header('Location: '.$res['zoom_link']);
            else{
                $this->load->view('agm/login_view',array('error' => 'user_not_found'));
            }

        }else{
            $this->load->view('agm/login_view',array());
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

        $registrant = $this->agm_model->api_add_zoom_registrant("89065666966",array(
            "email"      => $post['email'],
            "first_name" => $post['first_name'],
            "last_name"  => $post['name_chinese'],
        ));

        // if Error, Display ERROR to contact admin
        if(isset($registrant['code'])){

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
            'position'     => $post['position'],
            'email'        => $post['email'],
            'first_name'   => $post['first_name'],
            'last_name'    => $post['name_chinese'],
            'registrant_id'=> $registrant['registrant_id'],
            'zoom_link'    => $registrant['join_url'],
        );
        $this->agm_model->add_registrant($registrant_primary,$registrant_value);

        $this->register('success_reg',"成功登記！Registration Success!");
        return;
    }

}



?>
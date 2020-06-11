<?php

class AGM extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper(array('url','file'));
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

}



?>
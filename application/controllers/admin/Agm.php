<?php
class Agm extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('contact', 'english');
		$this->load->config('tbsparam');
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));
		$this->load->model('agm_model');

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){
		$this->list();
	}

	public function year($year){
		$data = $this->data;

		// Get all AJK
		$chapter_ajk = array();
		foreach($this->agm_model->get_chapter_member_list() as $cm){
			$chapter_ajk[$cm['chapter_id']]["id_".$cm['cm_id']] = $cm['name_chinese'] . " (".$cm['position'].")";
		}

		// Get Year Attendance
		$agm_attendance = array();
		foreach($this->agm_model->get_agm_attendance($year) as $aa){
			$agm_attendance[$aa['chapter_id']] = $aa;
		}

		// Count Total
		$total = array(
			'chapter' => 0,
			'chapter_member' => 0,
		);
		foreach($agm_attendance as $aa){

			if($aa['cm_id_1'] || $aa['cm_id_2'] || $aa['cm_id_3']){
				@$total['chapter'] += 1;

				@$total['chapter_member'] += ($aa['cm_id_1']) ? 1 : 0;
				@$total['chapter_member'] += ($aa['cm_id_2']) ? 1 : 0;
				@$total['chapter_member'] += ($aa['cm_id_3']) ? 1 : 0;
			}
		}
		
		// Place AJK into chapter
		$chapter_list = array();
		foreach($this->agm_model->get_chapter_list($year) as $chapter){
			$chapter['ajk'] = @array_merge(array(""=>"-N/A-"),$chapter_ajk[$chapter['chapter_id']]);
			$chapter['agm'] = @$agm_attendance[$chapter['chapter_id']];
			$chapter_list[$chapter['chapter_id']] = $chapter;
		}

		// Years 
		$years = array();
		for($i = 0; $i< 5; $i++){
			$y = date('Y') - $i;
			$years[$y] = $y;
		}

		$data = $this->data;
		$data['chapter'] = $chapter_list;
		$data['total']   = $total;
		$data['year']    = $year;
		$data['years']   = $years;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/agm/year_view',$data);
		$this->load->view('admin/footer');

	}

	public function list(){

		$data = $this->data;

		$years = array();
		$total = array(
			'chapter' => array(),
			'chapter_member' => array(),
		);

		// Get Year Attendance
		$agm_attendance = array();
		for($i = 0; $i< 5; $i++){
			$year = date('Y') - $i;
			foreach($this->agm_model->get_agm_attendance($year) as $aa){

				$aa['total'] = 0;
				$aa['total'] += ($aa['cm_id_1']) ? 1 : 0;
				$aa['total'] += ($aa['cm_id_2']) ? 1 : 0;
				$aa['total'] += ($aa['cm_id_3']) ? 1 : 0;
				$agm_attendance[$aa['chapter_id']][$year] = $aa;
			}
			$years[$year] = $year;
			$total['chapter'][$year] = 0;
			$total['chapter_member'][$year] = 0;
		}

		
		// Count Total
		foreach($agm_attendance as $chapter_id => $d){
			foreach($d as $year => $aa){

				if($aa['cm_id_1'] || $aa['cm_id_2'] || $aa['cm_id_3']){
					@$total['chapter'][$year] += 1;

					@$total['chapter_member'][$year] += ($aa['cm_id_1']) ? 1 : 0;
					@$total['chapter_member'][$year] += ($aa['cm_id_2']) ? 1 : 0;
					@$total['chapter_member'][$year] += ($aa['cm_id_3']) ? 1 : 0;
				}
			}
		}
		
		// Place AJK into chapter
		$chapter_list = array();
		foreach($this->agm_model->get_chapter_list($year) as $chapter){
			$chapter['agm'] = @$agm_attendance[$chapter['chapter_id']];
			$chapter_list[$chapter['chapter_id']] = $chapter;
		}

		$data = $this->data;
		$data['chapter'] = $chapter_list;
		$data['total']   = $total;
		$data['years']   = $years;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/agm/list_view',$data);
		$this->load->view('admin/footer');
	}

	public function replace_agm_attendance(){

		list(,$cmid) = explode("d_",$this->input->post('value'));
		$primary = array(
			'year' => $this->input->post('year'),
			'chapter_id' => $this->input->post('chapter_id'),
		);
		$value = array(
			"cm_id_".$this->input->post('cm_id') => $cmid,
		);
		$this->agm_model->replace_agm_attendance($primary,$value);
		
	}

	public function list_zoom_registrant(){

		$registrant_by_chapter = array();
		$list = $this->agm_model->list_zoom_registrant();
		foreach($list as $registrant){
			$registrant_by_chapter[$registrant['chapter_id']][] = $registrant;
		}

		$chapter_list = array();
		$total = array('chapter' => 0, 'chapter_member' => 0, 'chapter_member_offline' => 0);
		foreach($this->agm_model->get_chapter_list(date('Y')) as $chapter){
			$chapter_list[$chapter['chapter_id']] = $chapter;

			if(isset($registrant_by_chapter[$chapter['chapter_id']])){
				$registrant = $registrant_by_chapter[$chapter['chapter_id']];
				$chapter_list[$chapter['chapter_id']]['registrant'] = $registrant;
				@$total['chapter'] += 1;

				foreach($registrant as $r){
					if($r['membership_id'] != '列席'){
						if($r['zoom_link'] == '現場出席') @$total['chapter_member_offline'] += 1;
						else @$total['chapter_member'] += 1;
					}
				}
				unset($registrant);
			}
		}

		$data = $this->data;
		$data['chapter'] = $chapter_list;
		$data['total']   = $total;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/agm/list_zoom_registrant_view',$data);
		$this->load->view('admin/footer');

	}

	public function list_zoom_registrant_personal(){

		$list = $this->agm_model->list_zoom_registrant();
		$registrant = array();
		$total = array('online' => 0, 'offline' => 0);
		foreach($list as $l){
			if($l['membership_id'] < 3000 && $l['membership_id'] > 1000){
				$registrant[$l['nric']] = $l;
				if($l['zoom_link'] == '現場出席') $total['offline'] += 1;
				else  $total['online'] += 1;
			}
		}

		$members = $this->agm_model->get_member_meeting_list();
		foreach($members as $k => $m){
			if(isset($registrant[$m['nric']])){
				$members[$k]['registrant'] = $registrant[$m['nric']];
			}else{
				$members[$k]['registrant'] = array(
					'first_name' => $m['membership_id'].'-',
					'last_name'  => $m['name_chinese'],
					'email'      => $m['email'],
					'zoom_link'  => '',
					'registrant_id' => '',
					'reg_date'   => '-未登記-',
				);
			}
		}

		$data = $this->data;
		$data['members'] = $members;
		$data['total'] = $total;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/agm/list_zoom_registrant_personal_view',$data);
		$this->load->view('admin/footer');
	}

	public function list_zoom_registrant_nonvote(){

		$list = $this->agm_model->list_zoom_registrant_nonvote();
		foreach($list as $l){
			if($l['membership_id'] < 3000 && $l['membership_id'] > 1000)
				$registrant[$l['nric']] = $l;
		}

		$members = $this->agm_model->get_member_meeting_list();
		foreach($members as $k => $m){
			if(isset($registrant[$m['nric']])){
				$members[$k]['registrant'] = $registrant[$m['nric']];
			}else{
				$members[$k]['registrant'] = array(
					'first_name' => $m['membership_id'].'-',
					'last_name'  => $m['name_chinese'],
					'email'      => $m['email'],
					'zoom_link'  => '',
					'registrant_id' => '',
					'reg_date'   => '-未登記-',
				);
			}
		}

		$data = $this->data;
		$data['list'] = $list;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/agm/list_zoom_registrant_nonvote_view',$data);
		$this->load->view('admin/footer');
	}

	public function ajax_add_registrant(){

        $post = $this->input->post();

        // Submit to Zoom API
        $registrant = $this->agm_model->api_add_zoom_registrant("89065666966",array(
            "email"      => $post['email'],
            "first_name" => $post['first_name'],
            "last_name"  => $post['last_name'],
        ));

        // if Error, Display ERROR to contact admin
        if(isset($registrant['code'])){
        	echo json_encode(array(
        		'success' => 0,
        		'msg'     => "Error ".$registrant['code'].":".$registrant['message'],
        	));
            return ;
        }


        $registrant_primary = array(
            'email'         => $post['email'],
        );
        $registrant_value = array(
            'first_name'   => $post['first_name'],
            'last_name'    => $post['last_name'],
            'registrant_id'=> $registrant['registrant_id'],
            'zoom_link'    => $registrant['join_url'],
        );
        $this->agm_model->add_registrant($registrant_primary,$registrant_value);

        echo json_encode(array(
        	'success' => 1,
        	'msg'     => 'Success updated!',
        	'zoom_link' => $registrant['join_url'],
        ));
        return;
    }

    public function ajax_add_registrant_personal(){

        $post = $this->input->post();

        // Submit to Zoom API
        $registrant = $this->agm_model->api_add_zoom_registrant("89065666966",array(
            "email"      => $post['email'],
            "first_name" => $post['first_name'],
            "last_name"  => $post['last_name'],
        ));

        // if Error, Display ERROR to contact admin
        if(isset($registrant['code'])){
        	echo json_encode(array(
        		'success' => 0,
        		'msg'     => "Error ".$registrant['code'].":".$registrant['message'],
        	));
            return ;
        }


        $registrant_primary = array(
            'email'         => $post['email'],
        );
        $registrant_value = array(
            'first_name'   => $post['first_name'],
            'last_name'    => $post['last_name'],
            'registrant_id'=> $registrant['registrant_id'],
            'zoom_link'    => $registrant['join_url'],
            'nric'         => $post['nric'],
            'contact_id'   => $post['contact_id'],
            'name_chinese' => $post['name_chinese'],
            'name_malay'   => $post['name_malay'],
            'membership_id'=> $post['membership_id'],
        );
        if(@isset($post['position'])) $registrant_value['position'] = $post['position'];
        $this->agm_model->add_registrant($registrant_primary,$registrant_value);

        echo json_encode(array(
        	'success' => 1,
        	'msg'     => 'Success updated!',
        	'zoom_link' => $registrant['join_url'],
        ));
        return;
    }

    public function ajax_del_registrant(){

        $post = $this->input->post();

        // Submit to Zoom API
        $registrant = $this->agm_model->api_del_zoom_registrant("89065666966",$post['registrant_id']);

        // if Error, Display ERROR to contact admin
        if(isset($registrant['code'])){
        	echo json_encode(array(
        		'success' => 0,
        		'msg'     => "Error ".$registrant['code'].":".$registrant['message'],
        	));
            return ;
        }

        $this->agm_model->del_registrant($post['email']);
        echo json_encode(array(
        	'success' => 1,
        	'msg'     => 'Contact deleted! Refresh to reflect.',
        ));
        return;
    }

	
}

?>
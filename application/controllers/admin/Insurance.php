<?php
class Insurance extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('contact', 'english');
		$this->load->config('tbsparam');
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));
		$this->load->model('contact_model');

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){

		$data = $this->data;
		$data['insurance'] = $this->contact_model->get_dharma_insurance();
		$data['stats']     = $this->cal_stats($data['insurance']);
		
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/insurance/index_view',$data);
		$this->load->view('admin/footer');
	}

	private function cal_stats($data){

		$return['age_group'] = array(
			'16-21' => 0,
			'22-29' => 0,
			'30-39' => 0,
			'40-49' => 0,
			'50-54' => 0,
			'55-59' => 0,
			'60-64' => 0,
			'65-69' => 0,
			'70-74' => 0,
			'75-79' => 0,
		);

		foreach($data as $k => $v){
			
			// Process Age Group
			if($v['dob'] != '0000-00-00'){
				$age = date_diff(date_create($v['dob']),date_create('today'))->y;

				if($age <= 15) @$return['age_group']['<15'] += 1;
				elseif($age >= 16 && $age <= 21) @$return['age_group']['16-21'] += 1;
				elseif($age >= 22 && $age <= 29) @$return['age_group']['22-29'] += 1;
				elseif($age >= 30 && $age <= 39) @$return['age_group']['30-39'] += 1;
				elseif($age >= 40 && $age <= 49) @$return['age_group']['40-49'] += 1;
				elseif($age >= 50 && $age <= 54) @$return['age_group']['50-54'] += 1;
				elseif($age >= 55 && $age <= 59) @$return['age_group']['55-59'] += 1;
				elseif($age >= 60 && $age <= 64) @$return['age_group']['60-64'] += 1;
				elseif($age >= 65 && $age <= 69) @$return['age_group']['65-69'] += 1;
				elseif($age >= 70 && $age <= 74) @$return['age_group']['70-74'] += 1;
				elseif($age >= 75 && $age <= 79) @$return['age_group']['75-79'] += 1;
				elseif($age >= 80) @$return['age_group']['>80'] += 1;
			}

			// Calculate Additional Price per type
			if($v['a_max_price'] > 0){
				@$return['amax_total'][$v['a_max']]['amount'] += $v['a_max_price'];
				@$return['amax_total'][$v['a_max']]['pax'] += 1;
			}

			// Calculate Total Group Medical & PA
			if($v['group_medical'] > 0) @$return['group_medical'] += 1;
			if($v['group_pa'] > 0) @$return['group_pa'] += 1;
		}
		return $return;
	}

	public function ajax_get_view($contact_id){

		$data['contact'] = $this->contact_model->get_dharma_insurance_by_id($contact_id);

		//$this->load->view('admin/insurance/index_edit_view',$data);
		$html = $this->load->view('admin/insurance/index_edit_view',$data, TRUE);

		$data = array('html' => $html);
		echo json_encode($data);
	}

	public function ajax_post(){
		
		$post_data = $this->input->post();

		$success = $this->contact_model->replace_dharma_insurance($post_data,$post_data['contact_id']);
		$msg     = $this->alert_box('success','保險資料成功更新!');
		$data    = array('html' => $msg);
		echo json_encode($data);
	}

	private function alert_box($type, $msg) {
		switch($type) {
			case 'success':
			$icon = 'glyphicon-ok';
			break;

			case 'danger':
			$icon = 'glyphicon-remove';
			break;

			default:
			return "ERROR: INVALID ALERT TYPE!";
		}

		return '<div class="alert alert-' . $type . ' text-center"><i style="font-size:50px;" class="glyphicon ' . $icon . '" aria-hidden="true"></i><br /><br />' . $msg . '</div>';
	}

}

?>
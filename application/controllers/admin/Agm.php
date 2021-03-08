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

	
}

?>
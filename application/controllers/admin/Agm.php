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
		$this->year(date('Y'));
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
		
		// Place AJK into chapter
		$chapter_list = array();
		foreach($this->agm_model->get_chapter_list($year) as $chapter){
			$chapter['ajk'] = @array_merge(array(""=>"-N/A-"),$chapter_ajk[$chapter['chapter_id']]);
			$chapter['agm'] = @$agm_attendance[$chapter['chapter_id']];
			$chapter_list[$chapter['chapter_id']] = $chapter;
		}

		$data = $this->data;
		$data['chapter'] = $chapter_list;
		$data['year']    = $year;
		$data['years']   = array(
			"2021" => "2021",
			"2020" => "2020",
			"2019" => "2019",
		);

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/agm/year_view',$data);
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
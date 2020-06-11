<?php
class Process_Img extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend_model');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
	}

	public function index()
	{
		$data = $this->data;
		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/process_img_view', $data);
		$this->load->view('admin/footer', $data);
	}

	public function rename_img() {
		$new_name = $this->get_location($this->input->post('new_name'));
		rename('../images/'.$this->input->post('ori_name'),'../images/stories/'.$new_name);
		echo "Done! images/stories/".$new_name;
	}

	public function generate_thumb(){
		echo 'img/350x350/'.$this->get_location($this->input->post('new_name'));
	}

	public function get_location($filename){
		list($type,$date,$temple) = explode("_",$filename);

		if(in_array($type,array('fahui'))){
			$loc = 'upcoming-activities/';
		}else{
			$loc = 'news/';
		}

		$array_date = str_split($date,2);

		return $loc.$array_date[0].$array_date[1].'/'.(int)$array_date[2].'/'.$filename;


	}


}

?>
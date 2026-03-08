<?php
class Zwhsearch extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		$this->data = load_view_data($this->session);
	}

	public function index(){
		$data = $this->data;
        $data['menu_title'] = "宗務網站搜尋工具";
		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/zwh_search_view',$data);
		$this->load->view('admin/footer');
	}
}
?>

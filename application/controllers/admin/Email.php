<?php
class Email extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('chapter', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model(array('backend_model','contact_model'));

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){
		echo 1;
	}

	public function chapter(){
		$data = $this->data;
		$chapters = $this->backend_model->get_all_chapter_email();

		$list = array();

		foreach($chapters as $c){
			$list[$c['state']][$c['chapter_id']] = $c;

			// Contact List
			$vip_ajk_email = $this->contact_model->get_chapter_vip_ajk_email($c['chapter_id']);
			$list[$c['state']][$c['chapter_id']]['vip'] = $vip_ajk_email;
		}
		//print_pre($list);

		$data['email'] = $list;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/email/chapter_view',$data);
		$this->load->view('admin/footer');
	}

	public function member(){
		$data = $this->data;
		$members = $this->backend_model->get_all_member_email();

		$data['email'] = $members;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/email/member_view',$data);
		$this->load->view('admin/footer');


	}


	


}

?>
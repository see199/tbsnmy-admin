<?php
class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('contact', 'english');
		$this->load->config('tbsparam');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('api_model');

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){
		$this->list();
	}

	public function verify_list(){
		$data = $this->data;
		$data['users'] = array('verified' => array(), 'non' => array());

		$list = $this->api_model->get_all_verified_user();
		foreach($list as $user){
			$data['users'][(isset($user['verified_date']))?"verified":"non"][] = $user;
		}

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/api/verify_list_view',$data);
		$this->load->view('admin/footer');
	}

	public function verify_setting(){
		$data = $this->data;

		$email_list = $this->input->post('list');
		if(isset($email_list)){
			$email_list = array_unique(explode("\r\n",$this->input->post('list')));

			foreach($email_list as $k => $email){
				$users[] = array(
					'email'    => $email,
					'lucky_no' => str_pad($k+1000, 4, "0", STR_PAD_LEFT),
				);
			}

			$this->api_model->insert_verified_user($users);
		}

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/api/verify_setting_view',$data);
		$this->load->view('admin/footer');
	}
	
}

?>
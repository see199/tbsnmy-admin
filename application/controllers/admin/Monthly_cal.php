<?php
class Monthly_Cal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('monthly_cal', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
	}

	public function index(){
		$data = $this->data;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/monthly_cal_index_view',$data);
		$this->load->view('admin/footer');
	}
	public function upload(){

		// Directory Creation
		$path = $this->config->item('monthly_cal_location').$this->session->userdata('chapter_used').'/';
		if(!file_exists($path)) mkdir($path,0755,true);

		$config['allowed_types'] = 'jpg';
        $config['upload_path']   = $path;
        $config['file_name']     = $this->input->post('year').$this->input->post('month').'.jpg';
        $config['overwrite']     = TRUE;

        $this->load->library('upload',$config);
        
        if($this->upload->do_upload('file1')){
        	$this->backend_model->log_activity($this->session->userdata('user_id'),'upload_monthly_calendar: '.$path.$config['file_name'].' - SUCCESS');
        	echo alert_success(lang('success_upload'));
        }else{
            $errors = $this->upload->display_errors();
            $this->backend_model->log_activity($this->session->userdata('user_id'),'upload_monthly_calendar: '.$path.$config['file_name'].' - ERR: '.$errors);
            echo alert_danger($errors);
        }

	}



	


}

?>
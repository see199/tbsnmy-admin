<?php
class Tbnews extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('tbnews', 'english');
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
		$this->load->view('admin/tbnews_index_view',$data);
		$this->load->view('admin/footer');
	}
	public function upload(){

		$upload_loc = $this->config->item('tbnews_upload_location');

		if(isset($_FILES['file1'])){

			$file = $_FILES['file1'];

			if(preg_match('/WTBN([0-9]+).pdf/',$file['name'],$match) || preg_match('/WTBN([0-9]+).jpg/',$file['name'],$match)){
				$issue = $match[1];
				$datetime = calculate_tbsnews_date($issue);
				$target = $upload_loc . date('Y',$datetime) . '/'.$file['name'];
				
				if(file_exists($target)) unlink($target);
				$status = move_uploaded_file($file['tmp_name'], $target);
				
				if($status){
				    echo alert_success(lang('success_upload')) . '<textarea style="width:475px;" onclick="this.select()">' . $this->tbsnews_success_msg($issue,$datetime) . '</textarea>';
				}else{
				    echo alert_danger("Error Upload: " . $status.print_r($file,1));
				}

				

			}else{
				echo alert_danger(lang('err_incorrect_filename'));
			}
		} else {
			echo alert_danger(lang('err_no_file_uploaded'));
		}

		
	}
	
	public function load_success_text(){
		$issue = $this->input->post('issue');
		if(!$issue) die(alert_danger(lang('err_incorrect_filename')));
		$datetime = calculate_tbsnews_date($issue);
		echo alert_success(lang('success_upload')) . '<textarea style="width:475px;" onclick="this.select()">' . $this->tbsnews_success_msg($issue,$datetime) . '</textarea>';

	}

	private function tbsnews_success_msg($issue,$datetime){
		$date = date('Y年m月d日',$datetime);
		$year = date('Y',$datetime);
		return sprintf(lang('fb_msg'),$issue,$issue,$date,$issue,$issue);
	}



	


}

?>
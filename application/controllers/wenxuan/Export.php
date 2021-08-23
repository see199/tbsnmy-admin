<?php
class Export extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));
		$this->load->model('wenxuan_model');

		if(!$this->session->userdata('access_token_wx') || !$this->session->userdata('email')) redirect('wenxuan/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_common_view_data($this->session);
	}

	public function index(){
		$this->chapter();
	}

	public function chapter(){
		$data = $this->data;

		$export_list = array();

		$list = $this->wenxuan_model->get_list_chapter();
		foreach($list as $k => $v){

			if($v['tbnews_free'] > 0 || $v['tbnews_paid'] > 0 || $v['randeng_free'] > 0 || $v['randeng_paid'] > 0){
				$export_list[$k] = array(
					'TBN x ' .($v['tbnews_free'] + $v['tbnews_paid']).' 份    RDM x '.($v['randeng_free'] + $v['randeng_paid']) .' 本',
					'Attn: '.$v['wenxuan_name'],
					$v['wenxuan_address1'],
					$v['wenxuan_address2'],
					$v['wenxuan_postcode'].' '.$v['wenxuan_city'].', '.$v['wenxuan_state'].', '.$v['wenxuan_country'],
				);
			}
		}
		
		$data['list']  = $export_list;
		//echo "<pre>";print_r($data);
		$this->print_csv($export_list,"Chapter");

	}

	public function contact(){
		$data = $this->data;

		$export_list = array();

		$list = $this->wenxuan_model->get_list_contact();
		foreach($list as $k => $v){

			if($v['tbnews_free'] > 0 || $v['tbnews_paid'] > 0 || $v['randeng_free'] > 0 || $v['randeng_paid'] > 0){
				$export_list[$k] = array(
					'TBN x ' .($v['tbnews_free'] + $v['tbnews_paid']).' 份    RDM x '.($v['randeng_free'] + $v['randeng_paid']) .' 本',
					'Attn: '.$v['wenxuan_name'],
					$v['wenxuan_address1'],
					$v['wenxuan_address2'],
					$v['wenxuan_postcode'].' '.$v['wenxuan_city'].', '.$v['wenxuan_state'].', '.$v['wenxuan_country'],
				);
			}
		}

		$data['list']  = $export_list;
		//echo "<pre>";print_r($data);
		$this->print_csv($export_list,"Personal");
	}

	public function contact_email(){
		$data = $this->data;

		$export_list = array();

		$list = $this->wenxuan_model->get_list_contact();
		foreach($list as $k => $v){
			if($v['wenxuan_email'] && filter_var($v['wenxuan_email'], FILTER_VALIDATE_EMAIL))
				$export_list[$v['wenxuan_email']] = array(
					$v['wenxuan_name'],
					$v['wenxuan_email'],
				);
		}

		$data['list']  = $export_list;
		//echo "<pre>";print_r($export_list);
		$this->print_csv($export_list,"Personal_Email");

	}

	private function print_csv($data,$filename){

		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"".$filename.".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");

		$handle = fopen('php://output', 'w');
		foreach ($data as $data) {
			fputcsv($handle, $data);
		}
		fclose($handle);
		exit;
	}

}

?>
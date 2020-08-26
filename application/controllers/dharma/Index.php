<?php
class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('contact', 'english');
		$this->load->config('tbsparam');
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));
		$this->load->model('contact_model');

		if(!$this->session->userdata('access_token_dharma') || !$this->session->userdata('email')) redirect('dharma/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_common_view_data($this->session);
	}

	public function index(){
		$this->dharma('ss');
	}

	public function dharma($dtype){

		$data = $this->data;
		$data['dtype'] = $dtype;

		$this->list_column = array("name_dharma","name_chinese","name_malay","nric","phone_mobile","email","contact_id");

		if ($this->input->is_ajax_request()) {
			$this->get_data_dharma_contact($dtype);
		} else {
			$data = $this->data;
			$data['dtype'] = $dtype;
			$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

			$this->load->view('dharma/header', $data);
			$this->load->view('dharma/navigation', $data);
			$this->load->view('dharma/dharma_list_contact_view',$data);
			$this->load->view('dharma/footer');
		}
	}

	private function get_data_dharma_contact($dtype){

		$data  = array();
		$where = array(
			"dharma_position" => strtoupper($dtype),
			"name_chinese" => $this->input->post('search')['value'],
			"name_malay" => $this->input->post('search')['value'],
			"name_dharma" => $this->input->post('search')['value'],
			"nric" => $this->input->post('search')['value'],
			"phone_mobile" => $this->input->post('search')['value'],
		);
		$filter_data = $this->get_filter_data(array(),'contact_id');

		$forms = $this->contact_model->get_dharma_contact_list($filter_data,$where);
		foreach($forms as $k => $v){

			$data_to_be_added = array(
				"name_dharma" => $v['name_dharma'],
				"name_chinese" => $v['name_chinese'],
				"name_malay" => $v['name_malay'],
				"nric" => $v['nric'],
				"phone_mobile" => $v['phone_mobile'],
				"email" => $v['email'],
				"contact_id" => '<a href="'.base_url('dharma/index/details/'.$v['contact_id']).'">View</a>',
			);

			$data[] = $data_to_be_added;
		}
		//write_file('application/logs/ttt.txt',print_r($dtype,1));

		$result = array(
			'draw' => $this->input->post('draw'),
			'recordsTotal' => $filter_data['l2'],
			'recordsFiltered' => $this->contact_model->count_total_dharma_contact($where),
			'iTotalRecords' => $this->contact_model->count_total_dharma_contact($where),
			'data' => $data,
		);
		echo json_encode($result);
	}

	private function get_filter_data($filter_list=array(), $default_order='') {
        $result = array();

        foreach($filter_list as $name){
            if ($this->input->post($name) != '') {
                $result[$name] = $this->input->post($name);
            }
        }
        
        // Query Limit Default Value
        $result['l1'] = ($this->input->post('start')) ? $this->input->post('start') : '0';
        $result['l2'] = ($this->input->post('length')) ? $this->input->post('length') : '10';

        // Default Ordering
        $order = $this->input->post('order');
        $result['order_by'] = (isset($order[0])) ? $this->list_column[$order[0]['column']] . ' ' . $order[0]['dir'] : $default_order;

        return $result;
    }

	public function details($id){
		$data = $this->data;
		$this->load->config('countries');
		$this->load->model('backend_model');
		$contact = $this->contact_model->get_contact_details($id);

		// Chapter List
		$temp = $this->backend_model->get_all_chapter_id();
		$data['chapter_list'] = array("0" => '-N/A-');
		foreach($temp as $v) $data['chapter_list'][$v['chapter_id']] = $v['name_chinese'];

		// DOB Setting
		if($contact['nric'] <> '' && ($contact['dob'] == '' || $contact['dob'] == '0000-00-00')){
			$temp = str_split($contact['nric'],2);
			$year_split = str_split(date('Y'),2);
			if((int) $year_split[0].$temp[0] > (int)date('Y'))
				$year = ($year_split[0] - 1) . $temp[0];
			else
				$year = $year_split[0] . $temp[0];

			$contact['dob'] = $year .'-'. $temp[1] .'-'. $temp[2];
		}

		// Default Value
		if(!isset($contact['country'])) $contact['country'] = 'MY';

		$data['contact'] = $contact;

		$this->load->view('dharma/header', $data);
		$this->load->view('dharma/navigation', $data);
		$this->load->view('dharma/contact_details_view',$data);
		$this->load->view('dharma/footer');
	}

	public function update(){
		$contact = $this->input->post('contact');
		$dharma  = $this->input->post('dharma');

		$this->contact_model->update_contact($contact);
		if($dharma['dharma_position']) $this->contact_model->replace_dharma_info($dharma,$contact['contact_id']);

		redirect('dharma/index/details/'.$contact['contact_id'],'refresh');
	}
}

?>
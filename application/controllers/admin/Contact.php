<?php
class Contact extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('contact', 'english');
		$this->load->config('tbsparam');
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));
		$this->load->model('contact_model');

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){
		$this->list();
	}

	public function list(){
		$data = $this->data;

		$this->list_column = array("name_chinese","name_malay","name_dharma","nric","phone_mobile","email","contact_id");

		if ($this->input->is_ajax_request()) {
			$this->get_data_all_contact();
		} else {
			$data = $this->data;
			$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/contact/list_contact_view',$data);
			$this->load->view('admin/footer');
		}

	}

	private function get_data_all_contact(){

		$data  = array();
		$where = array(
			"name_chinese" => $this->input->post('search')['value'],
			"name_malay" => $this->input->post('search')['value'],
			"name_dharma" => $this->input->post('search')['value'],
			"nric" => $this->input->post('search')['value'],
			"phone_mobile" => $this->input->post('search')['value'],
		);
		$filter_data = $this->get_filter_data(array(),'contact_id');

		$forms = $this->contact_model->get_contact_list($filter_data,$where);
		foreach($forms as $k => $v){

			$data_to_be_added = array(
				"name_chinese" => $v['name_chinese'],
				"name_malay" => $v['name_malay'],
				"name_dharma" => $v['name_dharma'],
				"nric" => $v['nric'],
				"phone_mobile" => $v['phone_mobile'],
				"email" => $v['email'],
				"contact_id" => '<a href="'.base_url('admin/contact/details/'.$v['contact_id']).'">View</a>',
			);

			$data[] = $data_to_be_added;
		}
		//write_file('application/logs/ttt.txt',print_r($_POST,1));

		$result = array(
			'draw' => $this->input->post('draw'),
			'recordsTotal' => $filter_data['l2'],
			'recordsFiltered' => $this->contact_model->count_total_contact($where),
			'iTotalRecords' => $this->contact_model->count_total_contact($where),
			'data' => $data,
		);
		echo json_encode($result);
	}

	public function dharma($dtype){

		$data = $this->data;
		$data['dtype'] = $dtype;

		$this->list_column = array("name_chinese","name_malay","name_dharma","nric","phone_mobile","email","contact_id");

		if ($this->input->is_ajax_request()) {
			$this->get_data_dharma_contact($dtype);
		} else {
			$data = $this->data;
			$data['dtype'] = $dtype;
			$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/contact/dharma_list_contact_view',$data);
			$this->load->view('admin/footer');
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
				"name_chinese" => $v['name_chinese'],
				"name_malay" => $v['name_malay'],
				"name_dharma" => $v['name_dharma'],
				"nric" => $v['nric'],
				"phone_mobile" => $v['phone_mobile'],
				"email" => $v['email'],
				"contact_id" => '<a href="'.base_url('admin/contact/details/'.$v['contact_id']).'">View</a>',
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
		$chapter_member = $this->contact_model->get_contact_chapter($id);
		$contact_member = $this->contact_model->get_contact_member($id);

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
		$data['chapter_member'] = $chapter_member;
		$data['contact_member'] = $contact_member;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/contact/contact_details_view',$data);
		$this->load->view('admin/footer');
	}

	public function add_contact(){
		$data = $this->data;
		$this->load->config('countries');
		
		// Default Value
		$contact['country'] = 'MY';

		$data['contact'] = $contact;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/contact/add_contact_view',$data);
		$this->load->view('admin/footer');
	}

	public function update(){
		$contact = $this->input->post('contact');
		$dharma  = $this->input->post('dharma');
		$chapter_member = $this->input->post('chapter_member');
		$contact_member = $this->input->post('contact_member');

		$this->contact_model->update_contact($contact);
		//print_pre($dharma);
		if($dharma['dharma_position']) $this->contact_model->replace_dharma_info($dharma,$contact['contact_id']);
		if($contact_member['membership_id']) $this->contact_model->replace_contact_member($contact_member,$contact['contact_id']);

		//Chapter Member
		foreach($chapter_member as $cmid => $c){
			if(isset($c['delete'])) $this->contact_model->delete_chapter_member($cmid);
			else{
				if($c['chapter_id']) $this->contact_model->replace_chapter_member($c,$cmid);
			}
		}

		redirect('admin/contact/details/'.$contact['contact_id'],'refresh');
	}

	public function add(){
		$contact = $this->input->post('contact');
		$res = $this->contact_model->add_contact($contact);

		if($res['status'] == 'duplicate'){
			$data = $this->data;
			$data['contact'] = $res['data'];
			$this->load->view('admin/header', $data);
			$this->load->view('admin/contact/duplicate_contact_view',$data);
		}else{
			if($res['contact_id'])
				redirect('admin/contact/details/'.$res['contact_id'],'refresh');
		}
	}


	


}

?>
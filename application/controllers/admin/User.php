<?php
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('user', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('backend_model');
		$this->list_column = array();

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index() {
		$this->list_column = array("email","chapter","last_login","activity");

        if ($this->input->is_ajax_request()) {
            $this->get_data_all_user();
        } else {
        	$data = $this->data;
        	$data['total_data']  = $this->backend_model->count_total_user();
        	$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

            $this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/user_index_view',$data);
			$this->load->view('admin/footer');
        }
    }

	private function get_data_all_user(){
		$data = array();
		$filter_data = $this->get_filter_data(array(),'last_login');

		$users = $this->backend_model->get_all_user($filter_data);
		foreach($users as $k => $v){
			$chap = json_decode($v['chapter'],1);
			$users[$k]['chapter'] = join(',',$chap);

			$res = $this->backend_model->get_latest_activity($v['user_id']);

			$data[] = array(
				'email' => '<a href="'.base_url('admin/user/activity/'.$v['user_id']).'">'.$v['email'] .'</a>',
				'chapter' => join(',',$chap),
				'last_login' => $v['last_login'],
				'activity' => (sizeof($res) > 0) ? $res[0]['create_date'] . ': '. $res[0]['activity'] : '-N/A-',
			);
		}

		$result = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $filter_data['l2'],
            'recordsFiltered' => $this->backend_model->count_total_user(),
            'iTotalRecords' => $this->backend_model->count_total_user(),
            'data' => $data,
        );
        echo json_encode($result);
	}

	public function activity($user_id){
		$this->list_column = array("activity","create_date");

        if ($this->input->is_ajax_request()) {
            $this->get_data_user_activity();
        } else {
        	$data = $this->data;
        	$data['total_data']  = $this->backend_model->count_total_activity($user_id);
        	$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

        	$user = $this->backend_model->get_user_details($user_id);
			$data['user_email'] = $user[0]['email'];
			$data['user_id'] = $user_id;

            $this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/user_activity_view',$data);
			$this->load->view('admin/footer');
        }
	}

	private function get_data_user_activity(){
		$data = array();
		$filter_data = $this->get_filter_data(array('user_id'),'create_date DESC');

		$activity = $this->backend_model->get_user_activity($filter_data);
		foreach($activity as $v){
			$data[] = array(
				'activity'    => $v['activity'],
				'create_date' => $v['create_date'],
			);
		}

		$result = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $filter_data['l2'],
            'recordsFiltered' => $this->backend_model->count_total_activity($filter_data['user_id']),
            'iTotalRecords' => $this->backend_model->count_total_activity($filter_data['user_id']),
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
}

?>
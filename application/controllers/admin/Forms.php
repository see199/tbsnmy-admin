<?php
class Forms extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('user', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('backend_model');
		$this->load->model('forms_model');
		$this->list_column = array();

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function sangha() {
    	$this->list_form('sangha');
    }

    public function blessing() {
    	$this->list_form('blessing');
    }

    public function bardo() {
    	$this->list_form('bardo');
    }

    public function bardo_normal() {
    	$this->list_form('bardo_normal');
    }

    public function blessing_normal() {
    	$this->list_form('blessing_normal');
    }

	private function list_form($type) {

		if(in_array($type,array('sangha','blessing_normal','bardo_normal')))
			$this->list_column = array("created_at","name","amount","payer_email","payment_status","txn_id","printed");
		else
			$this->list_column = array("created_at","name","payer_email","payment_status","txn_id","printed");

        if ($this->input->is_ajax_request()) {
            $this->get_data_all_form($type);
        } else {
        	$data = $this->data;
        	$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

            $this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/forms/form_list_'.$type.'_view',$data);
			$this->load->view('admin/footer');
        }
    }

	private function get_data_all_form($type){

		$data  = array();
		$where = array();
		$filter_data = $this->get_filter_data(array(),'created_at DESC');

		if(in_array($type,array('blessing_normal','bardo_normal'))) {
			list($form_type, $table) = explode('_',$type);
			$table = 'normal';
			$where = array('form_type' => $form_type);
		} else {
			$table = $type;
		}

		$forms = $this->forms_model->list_form($table, $filter_data,$where);
		foreach($forms as $k => $v){

			$data_to_be_added = array(
				"created_at" => $v['created_at'],
				"amount" => ($table == 'normal') ? $v['total_amount'] : $v['amount'],
				"payer_email" => $v['payer_email'],
				"payment_status" => $v['payment_status'],
				"txn_id" => $v['txn_id'],
				"printed" => '<a target="print" href="'.base_url('admin/forms/print_form/'.$type.'/'.$v['id_form_'.$table]).'">Print Form</a> Is Printend? ' . $v['printed'],
			);

			if($type == 'sangha') {
				$data_to_be_added['name'] = $v['name'];
			} else {
				$data_to_be_added['name'] = $v['contact_name'];
			}

			if(in_array($type,array('bardo','blessing'))) {
				unset($data_to_be_added['amount']);
			}

			$data[] = $data_to_be_added;
		}

		$result = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $filter_data['l2'],
            'recordsFiltered' => $this->forms_model->count_total_form($table,$where),
            'iTotalRecords' => $this->forms_model->count_total_form($table,$where),
            'data' => $data,
        );
        echo json_encode($result);
	}

	public function print_form($type,$id){

		$this->view_form($type,$id,1);
	}

	public function view_form($type,$id,$printed=0) {

		
		$list = array();
		if(in_array($type,array('blessing_normal','bardo_normal'))) {
			list($form_type, $table) = explode('_',$type);
			$table = 'normal';
			$where = array('form_type' => $form_type);
			$list = $this->forms_model->get_subform_list($id);

		} else {
			$table = $type;
		}

		if($printed) $this->forms_model->update_form($table,array('printed' => 1),$id);
		$form = $this->forms_model->get_single_form($table,$id);
		
		if($type != 'sangha') {

			if(in_array($type,array('blessing','bardo')))
				$form['list'] = json_decode($form['list'],1);
			else
				$form['list'] = $list;
		}

		$this->load->view('admin/forms/print_form_'.$type.'_view', array('form' => $form));
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
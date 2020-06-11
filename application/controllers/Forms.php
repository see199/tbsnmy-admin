<?php
class Forms extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('form_model');
		$this->load->helper(array('url','file'));
		$this->load->library('session');
	}

	public function index(){

		$form = array(
			'sangha'   => array(
				'title' => '供僧大会',
				'desc'  => '【供僧】中的『供』是布施供养的意思。可以是供养食物、器皿、日常用品、僧袍等等。『僧』则是指僧伽（Sangha）、僧人、出家人的意思，也包括了我们熟悉的和尚、喇嘛。',
				'type'  => '供僧',
			),
			'blessing' => array(
				'title' => '法会祈福报名',
				'desc'  => '法会祈福报名或主祈者報名。主祈者可向主壇上師敬獻哈達以表敬意、包含護摩曼陀羅木一支',
				'type'  => '祈福',
			),
			'bardo'    => array(
				'title' => '法会超度报名',
				'desc'  => '法会超度报名或主祈者報名。主祈者可向主壇上師敬獻哈達以表敬意、包含護摩曼陀羅木一支',
				'type'  => '超度',
			),
		);

		$data = array(
			'form' => $form,
			'success_message' => $this->session->flashdata('success_message'),
		);

		// Load View
		$this->load->view('forms/base_header');
		$this->load->view('forms/index_view',$data);
		$this->load->view('forms/base_footer');
	}

	public function bardo(){

		$this->load_form(array(
			'item_name' => '2016 Main Bardo',
			'type' => 'bardo',
		));
	}

	public function blessing(){

		$this->load_form(array(
			'item_name' => '2016 Main Blessing',
			'type' => 'blessing',
		));
	}

	public function sangha(){

		$this->load_form(array(
			'item_name' => '2016 Sangha Offering',
			'type' => 'sangha',
		));
	}

	public function blessing_normal() {

		$this->load_form(array(
			'item_name' => '2016 Normal Blessing',
			'type' => 'blessing_normal',
		));
	}

	public function bardo_normal() {

		$this->load_form(array(
			'item_name' => '2016 Normal Bardo',
			'type' => 'bardo_normal',
		));
	}

	private function load_form($form_data) {

		$paypal = array(
			'business'  => 'info@tbsn.my',
			'item_name' => $form_data['item_name'],
			'return'    => base_url('forms/process_payment/'.$form_data['type']),
		);

		$data = array(
			'paypal'       => $paypal,
			'paypal_url'   => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
			'process_form' => base_url('forms/process_form/'.$form_data['type']),
			'type'         => $form_data['type'],
		);

		// Load View
		$this->load->view('forms/base_header');
		$this->load->view('forms/'.$form_data['type'].'_view',$data);
		$this->load->view('forms/base_footer');
	}

	public function paypal($type,$id,$amount) {

		switch($type){
			case 'sangha': $item_name = '2016 Sangha Offering'; break;
			case 'blessing_normal': $item_name = '2016 Normal Blessing'; break;
			case 'bardo_normal': $item_name = '2016 Normal Bardo'; break;
		}
		$paypal = array(
			'business'  => 'info@tbsn.my',
			'item_name' => $item_name,
			'return'    => base_url('forms/process_payment/'.$type.'/'.$id),
			'custom'    => $type.'|'.$id,
			'amount'    => $amount,
		);

		$data = array(
			'paypal'       => $paypal,
			'paypal_url'   => 'https://www.paypal.com/cgi-bin/webscr',
			'process_form' => base_url('forms/process_form/'.$type),
			'type'         => $type,
		);

		// Load View
		$this->load->view('forms/base_header');
		$this->load->view('forms/paypal_view',$data);
		$this->load->view('forms/base_footer');
	}

	public function process_form($type){
		$post_data = $this->input->post();

		$insert_data = array(
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'payer_email' => $post_data['payer_email'],
		);

		if($type == 'sangha') {
			$insert_col = array('name','amount','wish','contact_name','contact_address','contact_phone','attend');
			foreach($insert_col as $c){
				$insert_data[$c] = $post_data[$c];
			}
		}
		else if($type == 'bardo' || $type == 'blessing'){

			foreach($post_data['name'] as $k => $v) {
				if(!empty($post_data['name'][$k])) {
					$list[] = array(
						'name'    => $post_data['name'][$k],
						'address' => $post_data['address'][$k],
						'wish'    => $post_data['wish'][$k],
					);
				}
			}

			$insert_data['contact_name'] = $post_data['contact_name'];
			$insert_data['amount']       = '1000'; // Fixed at RM 1000
			$insert_data['list']         = json_encode($list);
		}
		else {

			list($type,) = explode('_',$type);

			$insert_col = array('contact_name','contact_address','contact_phone','total_donation','total_amount');
			if($type == 'blessing'){
				$insert_col[] = 'total_wood';
				$insert_col[] = 'total_card';
			}else{
				$insert_col[] = 'total_ship';
				$insert_col[] = 'total_lotus';
				$insert_col[] = 'total_paper';
			}
			foreach($insert_col as $c){
				$insert_data[$c] = $post_data[$c];
			}
			$insert_data['form_type'] = $type;
			$this->load->model('forms_model');
			$id_form = $this->forms_model->insert_form('normal', $insert_data);

			// Insert into sub table
			foreach($post_data['name'] as $k => $v) {
				if(!empty($post_data['name'][$k])) {
					$insert_data_sub = array(
						'name'    => $post_data['name'][$k],
						'address' => $post_data['address'][$k],
						'wish'    => $this->check_empty($post_data,'wish',$k),
						'count_card' => $this->check_empty($post_data,'count_card',$k),
						'count_wood' => $this->check_empty($post_data,'count_wood',$k),
						'count_ship' => $this->check_empty($post_data,'count_ship',$k),
						'count_lotus' => $this->check_empty($post_data,'count_lotus',$k),
						'count_paper' => $this->check_empty($post_data,'count_paper',$k),
						'fk_form_normal' => $id_form,
					);
					$id_form_list = $this->forms_model->insert_form('normal_list', $insert_data_sub);
				}
			}

			echo json_encode(array('status'=>'success','id_form' => $id_form));
			exit;
		}

		$this->load->model('forms_model');
		$id_form = $this->forms_model->insert_form($type, $insert_data);

		echo json_encode(array('status'=>'success','id_form' => $id_form));
	}

	private function check_empty($post_data,$col,$key){

		if(isset($post_data[$col]))
			if($post_data[$col][$key] > 0)
				return $post_data[$col][$key];

		return ($col == 'wish') ? '' : '0';
	}

	public function process_payment($type, $id_form){

		$post_data = $this->input->post();

		if(!empty($post_data)){
			$update_data = array(
				'updated_at' => date('Y-m-d H:i:s'),
				'json_log'   => json_encode($post_data),
			);
			$update_col = array('txn_type','pending_reason','payment_type','payer_status','verify_sign','payer_email','txn_id','payer_id','payment_status');
			foreach($update_col as $c){
				$update_data[$c] = $post_data[$c];
			}
		} else {
			$get_data = $this->input->get();
			$update_data = array(
				'updated_at' => date('Y-m-d H:i:s'),
				'json_log'   => json_encode($get_data),
				'payment_status' => $get_data['st'],
				'txn_id' => $get_data['tx'],
			);
		}

		$this->load->model('forms_model');

		if($type == 'blessing_normal' || $type == 'bardo_normal'){
			$type = 'normal';
		}

		$status = $this->forms_model->update_form($type, $update_data, $id_form);

		$this->session->set_flashdata('success_message',true);
		redirect('forms');

	}

	public function return_success(){
		$this->session->set_flashdata('success_message',true);
		redirect('forms');
	}

	public function process_ipn(){

		$date = date('Y-m-d');
		$post = $this->input->post();
		write_file('application/logs/'.$date.'_ipn.log',"\r\n".json_encode($post),'a');

		// Try to process
		if(!empty($post['custom'])){
			list($type,$id_form) = explode('|',$post['custom']);
			if($type && $id_form){
				
				// Preparing Column
				$update_data = array(
					'updated_at' => date('Y-m-d H:i:s'),
					'json_log'   => json_encode($post),
				);
				$update_col = array('txn_type','payment_type','payer_status','verify_sign','payer_email','txn_id','payer_id','payment_status');
				foreach($update_col as $c){
					if(!empty($post[$c]))
					$update_data[$c] = $post[$c];
				}

				// Update Status
				$this->load->model('forms_model');
				if($type == 'blessing_normal' || $type == 'bardo_normal'){
					$type = 'normal';
				}

				$this->load->model('forms_model');
				$status = $this->forms_model->update_form($type, $update_data, $id_form);
			}
		}
	}
}

?>
<?php
class Form extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','file','common_helper'));
		$this->load->model('wforms_model');
		$this->load->library('xendit_lib');
	}

	public function index($year = NULL){
		if (empty($year)) {
			$year = $this->wforms_model->get_latest_package_year();
		}
		
		$data = array(
			'year'     => $year,
			'packages' => $this->wforms_model->get_this_year_packages($year),
		);

		// Load View
		$this->load->view('general-forms/base_header');
		$this->load->view('general-forms/base_menu');
		$this->load->view('general-forms/wenxuan_index_view', $data);
		$this->load->view('general-forms/base_footer');
	}

	public function add_subscriber(){
		$post = $this->input->post();

		if (empty($post['wenxuan_name']) || empty($post['wenxuan_contact']) || empty($post['payment'])) {
			echo json_encode(array('Status' => 0, 'Msg' => '必要欄位不能空白！ (Required fields missing)'));
			return;
		}

		// Register for Blessing Contact
		$register_blessing = array();
		if (isset($post['name']) && is_array($post['name'])) {
			foreach($post['name'] as $k => $name){
				if(!empty($name)) {
					$register_blessing[] = array(
						'name'    => $name,
						'age'     => isset($post['age'][$k]) ? $post['age'][$k] : '',
						'address' => isset($post['address'][$k]) ? $post['address'][$k] : '',
						'wish'    => isset($post['wish'][$k]) ? $post['wish'][$k] : '',
					);
				}
			}
		}

		// Set subscriber value
		$wenxuan_subscriber_new_data = array(
			'wenxuan_id'            => '',
			'wenxuan_name'          => $post['wenxuan_name'],
			'wenxuan_name_receiver' => isset($post['wenxuan_name_receiver']) ? $post['wenxuan_name_receiver'] : '',
			'wenxuan_contact'       => $post['wenxuan_contact'],
			'wenxuan_email'         => isset($post['wenxuan_email']) ? $post['wenxuan_email'] : '',
			'wenxuan_address1'      => isset($post['wenxuan_address1']) ? $post['wenxuan_address1'] : '',
			'wenxuan_address2'      => isset($post['wenxuan_address2']) ? $post['wenxuan_address2'] : '',
			'wenxuan_city'          => isset($post['wenxuan_city']) ? $post['wenxuan_city'] : '',
			'wenxuan_postcode'      => isset($post['wenxuan_postcode']) ? $post['wenxuan_postcode'] : '',
			'wenxuan_state'         => isset($post['wenxuan_state']) ? $post['wenxuan_state'] : '',
			'wenxuan_country'       => isset($post['wenxuan_country']) ? $post['wenxuan_country'] : 'Malaysia',
			'remarks'               => "New register from website at ".date('Y-m-d H:i:s'),
			'update_date'           => date('Y-m-d H:i:s'),
			'update_by'             => $post['wenxuan_name'],
		);

		// Search for duplicate -- by name & phone
		$wenxuan_subscriber = $this->wforms_model->search_wenxuan_duplicate($post);
		if(sizeof($wenxuan_subscriber) > 0){
			$wenxuan_subscriber_new_data['wenxuan_id'] = $wenxuan_subscriber[0]['wenxuan_id'];
			$wenxuan_subscriber_new_data['remarks']    = $wenxuan_subscriber[0]['remarks']."\nRegister from website at ".date('Y-m-d H:i:s');
		}

		// Save/Replace to DB
		$wenxuan_id = $this->wforms_model->replace_wenxuan_subscriber($wenxuan_subscriber_new_data);

		list($package_id, $fullpayment_str) = explode("_", $post['payment']);
		$fullpayment  = ($fullpayment_str == 'full') ? 1 : 0;
		$self_pickup   = isset($post['self_pickup']) ? $post['self_pickup'] : 0;
		$package_info = $this->wforms_model->get_package_by_id($package_id);

		$wenxuan_subscriber_year = array(
			'wenxuan_id'        => $wenxuan_id,
			'package_id'        => $package_id,
			'fullpayment'       => $fullpayment,
			'self_pickup'       => $self_pickup,
			'status'            => 0,
			'payment_status'    => 'PENDING',
			'gift_taken'        => 0,
			'remarks'           => date('Y-m-d H:i:s') .' - 系統報名',
			'md5_id'            => '',
			'register_blessing' => json_encode($register_blessing),
			'start_date'        => date('Y-m-d'),
			'end_date'          => date('Y-m-d', strtotime('+1 year')),
			'source'            => 'web',
		);

		// Insert subscription record to get unique ID
		$sub_id = $this->wforms_model->insert_wenxuan_year($wenxuan_subscriber_year);
		
		// Create unique md5_id incorporating primary key, timestamp, and random token
		$unique_md5 = md5($sub_id . '_' . $wenxuan_id . '_' . $package_id . '_' . time() . '_' . uniqid());
		
		// FULL PAYMENT VIA XENDIT
		if ($fullpayment == 1) {
			$external_id  = 'WENXUAN-' . $unique_md5;
			$amount       = $package_info['package_amount'];
			$payer_email  = !empty($post['wenxuan_email']) ? $post['wenxuan_email'] : 'info@tbsn.my';
			$description  = "大馬文宣功德主 - " . $package_info['package_name'] . " (RM " . $amount . ")";
			$success_url  = base_url('wenxuan/receipt/' . $unique_md5);
			$failure_url  = base_url('wenxuan/receive_note/' . $unique_md5);
			$cust_name    = $post['wenxuan_name'];

			$xendit_res = $this->xendit_lib->create_invoice($external_id, $amount, $payer_email, $description, $success_url, $failure_url, $cust_name);

			if ($xendit_res['success']) {
				$this->wforms_model->update_subscriber_year($sub_id, array(
					'md5_id'             => $unique_md5,
					'payment_method'     => 'xendit',
					'xendit_invoice_id'  => $xendit_res['invoice_id'],
					'xendit_invoice_url' => $xendit_res['invoice_url'],
					'payment_status'     => 'PENDING',
				));

				echo json_encode(array(
					'Status'       => 1,
					'payment_type' => 'xendit',
					'invoice_url'  => $xendit_res['invoice_url'],
					'Msg'          => '正在前往 Xendit 付款頁面...'
				));
				return;
			} else {
				// Xendit API failed, fallback to manual mode with warning
				$this->wforms_model->update_subscriber_year($sub_id, array(
					'md5_id'         => $unique_md5,
					'payment_method' => 'manual',
					'payment_status' => 'PENDING',
					'remarks'        => date('Y-m-d H:i:s') . ' - Xendit error: ' . $xendit_res['error'],
				));

				echo json_encode(array(
					'Status'       => 1,
					'payment_type' => 'manual',
					'URL'          => base_url('wenxuan/receive_note/' . $unique_md5),
					'Msg'          => 'Xendit Gateway Notice: ' . $xendit_res['error'] . '。請手動轉賬。'
				));
				return;
			}
		} 
		// INSTALLMENT (MANUAL PAYMENT)
		else {
			$this->wforms_model->update_subscriber_year($sub_id, array(
				'md5_id'         => $unique_md5,
				'payment_method' => 'installment',
				'payment_status' => 'PENDING',
			));

			$this->send_subscriber_email($unique_md5, 'registration_received');

			echo json_encode(array(
				'Status'       => 1,
				'payment_type' => 'manual',
				'URL'          => base_url('wenxuan/receive_note/' . $unique_md5),
				'Msg'          => 'Receipt Code: ' . $unique_md5
			));
			return;
		}
	}

	/**
	 * Xendit Webhook Callback Endpoint
	 */
	public function xendit_callback(){
		$headers = $this->input->request_headers();
		$callback_token = isset($headers['x-callback-token']) ? $headers['x-callback-token'] : (isset($headers['X-Callback-Token']) ? $headers['X-Callback-Token'] : '');

		if (!$this->xendit_lib->verify_callback_token($callback_token)) {
			show_error('Invalid Token', 403);
			return;
		}

		$raw_input = file_get_contents('php://input');
		$payload = json_decode($raw_input, TRUE);

		if (empty($payload)) {
			echo json_encode(array('status' => 'empty_payload'));
			return;
		}

		// Log callback payload
		write_file('application/logs/' . date('Y-m-d') . '_xendit_callback.log', "\r\n" . date('H:i:s') . ' ' . $raw_input, 'a');

		$invoice_id  = isset($payload['id']) ? $payload['id'] : '';
		$external_id = isset($payload['external_id']) ? $payload['external_id'] : '';
		$status      = isset($payload['status']) ? strtoupper($payload['status']) : '';
		$pay_method  = isset($payload['payment_method']) ? $payload['payment_method'] : 'Xendit';

		$md5_id = str_replace('WENXUAN-', '', $external_id);

		$subscription = NULL;
		if (!empty($invoice_id)) {
			$subscription = $this->wforms_model->get_subscriber_year_by_xendit_id($invoice_id);
		}
		if (empty($subscription) && !empty($md5_id)) {
			$subscription = $this->wforms_model->get_subscriber_year_by_md5($md5_id);
		}

		if ($subscription) {
			if ($status === 'PAID' || $status === 'SETTLED') {
				$update_data = array(
					'status'         => 1,
					'payment_status' => 'PAID',
					'payment_method' => $pay_method,
					'paid_at'        => date('Y-m-d H:i:s'),
					'remarks'        => $subscription['remarks'] . "\n" . date('Y-m-d H:i:s') . ' - Xendit Payment Confirmed',
				);

				$this->wforms_model->update_subscriber_year_by_md5($subscription['md5_id'], $update_data);
				$this->send_subscriber_email($subscription['md5_id'], 'payment_success');
			} else if ($status === 'EXPIRED') {
				$this->wforms_model->update_subscriber_year_by_md5($subscription['md5_id'], array(
					'payment_status' => 'EXPIRED',
				));
			}
		}

		header('Content-Type: application/json');
		echo json_encode(array('status' => 'success'));
	}

	public function receive_note($id){
		$package = $this->wforms_model->get_subscriber_year_by_md5($id);
		if (empty($package)) {
			show_404();
			return;
		}

		// Auto-sync Xendit payment status if still pending
		$this->sync_xendit_status($package);

		$package['details']    = $this->wforms_model->get_package_by_id($package['package_id']);
		$package['subscriber'] = $this->wforms_model->get_subscriber_by_id($package['wenxuan_id']);

		// Load View
		$this->load->view('general-forms/base_header');
		$this->load->view('general-forms/wenxuan_receive_note_view', $package);
		$this->load->view('general-forms/base_footer');
	}

	public function receipt($id){
		$package = $this->wforms_model->get_subscriber_year_by_md5($id);
		if (empty($package)) {
			show_404();
			return;
		}

		// Auto-sync Xendit payment status if still pending
		$this->sync_xendit_status($package);

		$package['details']    = $this->wforms_model->get_package_by_id($package['package_id']);
		$package['subscriber'] = $this->wforms_model->get_subscriber_by_id($package['wenxuan_id']);

		// Load View
		$this->load->view('general-forms/base_header');
		$this->load->view('general-forms/wenxuan_receipt_view', $package);
		$this->load->view('general-forms/base_footer');
	}

	public function viewform($id){
		$package = $this->wforms_model->get_subscriber_year_by_md5($id);
		if (empty($package)) {
			show_404();
			return;
		}

		// Auto-sync Xendit payment status if still pending
		$this->sync_xendit_status($package);

		$package['register_blessing'] = json_decode($package['register_blessing'], 1);
		$data['package']    = $package;
		$data['subscriber'] = $this->wforms_model->get_subscriber_by_id($package['wenxuan_id']);
		$data['packages']   = $this->wforms_model->get_package_by_id($package['package_id']);

		// Load View
		$this->load->view('general-forms/base_header');
		$this->load->view('general-forms/wenxuan_viewform_view', $data);
		$this->load->view('general-forms/base_footer');
	}

	/**
	 * Helper: Sync Xendit payment status directly with Xendit API
	 */
	private function sync_xendit_status(&$package){
		if (!empty($package) && $package['payment_status'] !== 'PAID' && !empty($package['xendit_invoice_id'])) {
			$xendit_res = $this->xendit_lib->get_invoice($package['xendit_invoice_id']);
			if ($xendit_res['success']) {
				$status = strtoupper($xendit_res['status']);
				if ($status === 'PAID' || $status === 'SETTLED') {
					$pay_method = isset($xendit_res['data']['payment_method']) ? $xendit_res['data']['payment_method'] : 'Xendit';
					$update_data = array(
						'status'         => 1,
						'payment_status' => 'PAID',
						'payment_method' => $pay_method,
						'paid_at'        => date('Y-m-d H:i:s'),
						'remarks'        => $package['remarks'] . "\n" . date('Y-m-d H:i:s') . ' - Xendit Payment Confirmed (Sync)',
					);
					$this->wforms_model->update_subscriber_year_by_md5($package['md5_id'], $update_data);
					$this->send_subscriber_email($package['md5_id'], 'payment_success');

					// Refresh package data
					$package = $this->wforms_model->get_subscriber_year_by_md5($package['md5_id']);
				} else if ($status === 'EXPIRED') {
					$this->wforms_model->update_subscriber_year_by_md5($package['md5_id'], array('payment_status' => 'EXPIRED'));
					$package['payment_status'] = 'EXPIRED';
				}
			}
		}
	}

	/**
	 * Send confirmation email helper
	 */
	private function send_subscriber_email($md5_id, $type = 'payment_success'){
		$package = $this->wforms_model->get_subscriber_year_by_md5($md5_id);
		if (empty($package)) return;

		$subscriber = $this->wforms_model->get_subscriber_by_id($package['wenxuan_id']);
		$details    = $this->wforms_model->get_package_by_id($package['package_id']);

		if (empty($subscriber['wenxuan_email'])) return;

		$this->config->load('params', TRUE);
		$params = $this->config->item('params');
		$email_config = isset($params['email_wenxuan']) ? $params['email_wenxuan'] : (isset($params['email']) ? $params['email'] : NULL);

		if ($email_config) {
			$this->load->library('email', $email_config);
			$this->email->set_newline("\r\n");
			$this->email->from($email_config['smtp_user'], '大馬真佛宗文宣部');
			$this->email->to($subscriber['wenxuan_email']);

			if ($type === 'payment_success') {
				$this->email->subject('【大馬真佛宗】文宣功德主報名與付款成功通知');
				$body = "親愛的 " . $subscriber['wenxuan_name'] . " 蓮花同門/大德：<br/><br/>" .
				        "感謝您的發心護持！我們已成功收到您的文宣功德主報名款項。<br/>" .
				        "<b>報名方案：</b>" . $details['year'] . "年 " . $details['package_name'] . " (RM " . $details['package_amount'] . ")<br/>" .
				        "<b>收據連結：</b> <a href='" . base_url('wenxuan/receipt/' . $md5_id) . "'>" . base_url('wenxuan/receipt/' . $md5_id) . "</a><br/><br/>" .
				        "祝願 蓮生活佛加持 福慧增長 萬事如意！<br/><br/>馬密總文宣部 敬上";
			} else {
				$this->email->subject('【大馬真佛宗】文宣功德主報名成功通知 (分期付款/手動匯款)');
				$body = "親愛的 " . $subscriber['wenxuan_name'] . " 蓮花同門/大德：<br/><br/>" .
				        "感謝您的報名！我們已收到您的文宣功德主報名資料。<br/>" .
				        "<b>報名方案：</b>" . $details['year'] . "年 " . $details['package_name'] . " (RM " . $details['package_amount'] . ") - 分期付款/手動轉賬<br/>" .
				        "請將匯款收據連同報名收據電郵至 info@tbsn.my 。<br/>" .
				        "<b>報名收據：</b> <a href='" . base_url('wenxuan/receive_note/' . $md5_id) . "'>" . base_url('wenxuan/receive_note/' . $md5_id) . "</a><br/><br/>" .
				        "馬密總文宣部 敬上";
			}

			$this->email->message($body);
			@$this->email->send();
		}
	}
}
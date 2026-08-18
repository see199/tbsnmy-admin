<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xendit_lib {

	protected $CI;
	private $secret_key;
	private $callback_token;
	private $api_url = 'https://api.xendit.co/v2/invoices';

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->config->load('wenxuan/xendit', TRUE, TRUE);

		$xendit_config = $this->CI->config->item('wenxuan/xendit');
		$this->secret_key     = isset($xendit_config['xendit_secret_key']) ? trim($xendit_config['xendit_secret_key']) : '';
		$this->callback_token = isset($xendit_config['xendit_callback_token']) ? trim($xendit_config['xendit_callback_token']) : '';
	}

	/**
	 * Create a Xendit Invoice
	 */
	public function create_invoice($external_id, $amount, $payer_email, $description, $success_url, $failure_url, $customer_name = '') {
		$payload = array(
			'external_id'          => $external_id,
			'amount'               => (float) $amount,
			'payer_email'          => $payer_email,
			'description'          => $description,
			'currency'             => 'MYR',
			'success_redirect_url' => $success_url,
			'failure_redirect_url' => $failure_url,
		);

		if (!empty($customer_name)) {
			$payload['customer'] = array(
				'given_names' => $customer_name,
				'email'       => $payer_email,
			);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Basic ' . base64_encode($this->secret_key . ':')
		));

		$response  = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error     = curl_error($ch);
		curl_close($ch);

		// Log request & response for debugging
		$log_msg = date('Y-m-d H:i:s') . " [XENDIT INVOICE REQUEST]\n" . json_encode($payload, JSON_PRETTY_PRINT) . 
		           "\n[HTTP $http_code RESPONSE]\n" . $response . "\n----------------------------------------\n";
		@write_file('application/logs/' . date('Y-m-d') . '_xendit_api.log', $log_msg, 'a');

		if ($error) {
			return array(
				'success' => FALSE,
				'error'   => 'cURL Error: ' . $error
			);
		}

		$res_data = json_decode($response, TRUE);

		if ($http_code >= 200 && $http_code < 300 && isset($res_data['invoice_url'])) {
			return array(
				'success'     => TRUE,
				'invoice_id'  => $res_data['id'],
				'invoice_url' => $res_data['invoice_url'],
				'status'      => $res_data['status'],
				'data'        => $res_data
			);
		}

		$error_msg = isset($res_data['message']) ? $res_data['message'] : 'Failed to create Xendit invoice';
		return array(
			'success' => FALSE,
			'error'   => $error_msg,
			'data'    => $res_data
		);
	}

	/**
	 * Get / Query Invoice Status from Xendit API
	 */
	public function get_invoice($invoice_id) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url . '/' . $invoice_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic ' . base64_encode($this->secret_key . ':')
		));

		$response  = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$res_data = json_decode($response, TRUE);
		if ($http_code >= 200 && $http_code < 300 && isset($res_data['status'])) {
			return array(
				'success' => TRUE,
				'status'  => strtoupper($res_data['status']),
				'data'    => $res_data
			);
		}

		return array(
			'success' => FALSE,
			'error'   => isset($res_data['message']) ? $res_data['message'] : 'Failed to fetch invoice'
		);
	}

	/**
	 * Verify Webhook Callback Token
	 */
	public function verify_callback_token($header_token) {
		if (empty($this->callback_token)) {
			return TRUE;
		}
		return hash_equals($this->callback_token, (string) $header_token);
	}
}

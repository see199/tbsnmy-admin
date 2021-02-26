<?php
class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ask_model');
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));

		// Session Checking
		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('ask/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_common_view_data($this->session);

		$this->spreadsheetId = "1dK_PKgTZXch-wPtYBL6BvPYkLOZf8vCl1vgEItlc1fQ";
	}

	public function index()
	{

		$data = $this->data;
		$gsheet_data = $this->process_gsheet_data($this->read_gsheet());

		// Get Template
		foreach($gsheet_data as $status => $gdata){
			$gsheet_data[$status]['table-tpl'] = $this->load->view('ask/tpl-table-view',array('mydata' => $gdata), TRUE);
		}

		$data['gsheet'] = $gsheet_data;
		$data['gstatus'] = array(
			"新"   => "全新輸入",
			"宗委" => "待宗委會選",
			"落選" => "落選 - 未處理",
			"待答" => "落選 - 安排人員代答",
			"待回" => "落選 - 已批准代答，待回復",
			"師尊" => "待師尊回答",
			"完成" => "師尊已回答 / 已電郵代答",
			"不理" => "落選 - 不理",
			"重複" => "重複呈交",
		);

		/*echo "<pre>";
		print_r($gsheet_data);
		echo "</pre>";*/

		$data['title'] = 'News archive';

		$this->load->view('ask/header', $data);
		$this->load->view('ask/navigation', $data);
		$this->load->view('ask/pg_index', $data);
		$this->load->view('ask/footer');
	}

	private function read_gsheet_test(){
		return json_decode(read_file(APPPATH.'/logs/test.json'));
	}

	private function load_google_client(){
		require_once APPPATH . "libraries/GVendor/autoload.php";
		$client = new \Google_Client();
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		$client->setAuthConfig(APPPATH . '/config/google-sheets-credentials.json');

		return $client;
	}

	private function read_gsheet(){

		$client  = $this->load_google_client();
		$service = new Google_Service_Sheets($client);
		$get_range = "Form Responses 1!A2:K";
		
		//Request to get data from spreadsheet.
		return $service->spreadsheets_values->get($this->spreadsheetId, $get_range)->getValues();
	}

	private function process_gsheet_data($array){

		$processed_data = array();
		foreach($array as $k => $row){
			
			$row[8] = isset($row[8]) ? $row[8] : '新';

			$processed_data[$row[8]][$k] = array(
				'date'      => @$row[0],
				'name'      => @$row[1],
				'id'        => @$row[2],
				'question'  => @$row[3],
				'country'   => @$row[4],
				'contact'   => @$row[5],
				'agree'     => @$row[6],
				'x2'        => @$row[7],
				'status'    => @$row[8],
				'answer_by' => @$row[9],
				'answer'    => @$row[10],
				'row'       => $k + 2,
			);
			
		}
		return $processed_data;
	}

	public function update_status($id,$status){

		$status_column = "I";
		$options = array('valueInputOption' => 'RAW');

		$client  = $this->load_google_client();
		$service = new Google_Service_Sheets($client);
		$body    = new Google_Service_Sheets_ValueRange(['values' => [[urldecode($status)]]]);
		$result  = $service->spreadsheets_values->update($this->spreadsheetId, $status_column.$id.':'.$status_column.$id, $body, $options);

		if($result->updatedCells > 0) echo 1;
	}

	public function update_answer($id){

		$status_column = "K";
		$options = array('valueInputOption' => 'RAW');

		$client  = $this->load_google_client();
		$service = new Google_Service_Sheets($client);
		$body    = new Google_Service_Sheets_ValueRange(['values' => [[$this->input->post('answer')]]]);
		$result  = $service->spreadsheets_values->update($this->spreadsheetId, $status_column.$id.':'.$status_column.$id, $body, $options);

		if($result->updatedCells > 0) echo 1;
	}




}

?>
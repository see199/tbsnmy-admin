<?php
class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('wenxuan_model');
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));

		// Session Checking
		if(!$this->session->userdata('access_token_wx') || !$this->session->userdata('email')) redirect('wenxuan/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_common_view_data($this->session);
	}

	public function index()
	{

		$data = $this->data;
		$data['title'] = 'News archive';
		$data['wenxuan_snapshot'] = json_decode(read_file($this->config->item('file_wenxuan_snapshot')),1);

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/pg_index', $data);
		$this->load->view('wenxuan/footer');
	}

	public function export_db(){

		$this->db = $this->load->database('local', TRUE);
		$this->load->dbutil();
		
		$filename = 'backup-'.date('YmdHis').'.sql';

		$prefs = array(
			'tables'        => array('tbs_chapter','tbs_contact','tbs_dharma_staff','tbs_chapter_member','tbs_member','tbs_dharma_insurance'),   // Array of tables to backup.
			'ignore'        => array(),                     // List of tables to omit from the backup
			'format'        => 'txt',                       // gzip, zip, txt
			'filename'      => $filename, // File name - NEEDED ONLY WITH ZIP FILES
			'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
			'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
			'newline'       => "\n"                         // Newline character used in backup file
		);

		$backup = $this->dbutil->backup($prefs);

		// For write file
		//$this->load->helper('file');
		//write_file('application/logs/'.$filename, $backup);

		// For download
		$this->load->helper('download');
		force_download($filename, $backup);
	}

	public function import_db(){
		$data = $this->data;
		$file = '';

		@$upload = $_FILES['userfile'];
		if(!$upload['error']){
			$file = $upload['tmp_name'];
		}

		if (file_exists($file)){
			$this->db = $this->load->database('local', TRUE);

			$lines = file($file);
			$statement = '';
			foreach ($lines as $line){
				$statement .= $line;
				if (substr(trim($line), -1) === ';'){
					$this->db->simple_query($statement);
					$statement = '';
				}
			}
		}

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/import_db_view', $data);
		$this->load->view('wenxuan/footer', $data);
	}



}

?>
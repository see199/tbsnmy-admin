<?php
class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend_model');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));

		// Session Checking
		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
	}

	public function index()
	{
		// Only "all" (Super Admin) can view statistics dashboard
		$allowed = json_decode($this->session->userdata('chapter'), 1);
		if (empty($allowed) || $allowed[0] != 'all') {
			redirect('admin/chapter', 'refresh');
		}

		$data = $this->data;
		
		// Load tbsparam configuration
		$this->config->load('tbsparam', TRUE);
		$tbs_config = $this->config->item('tbs', 'tbsparam');
		if (empty($tbs_config)) {
			$tbs_config = $this->config->item('tbs');
		}

		$cache_file = APPPATH . 'logs/dashboard_stats.json';
		$stats = null;
		$force_refresh = false;

		if (file_exists($cache_file)) {
			$cached_data = json_decode(file_get_contents($cache_file), TRUE);
			if ($cached_data && isset($cached_data['last_updated']) && isset($cached_data['data'])) {
				$last_updated = strtotime($cached_data['last_updated']);
				$today_day = (int)date('j');
				
				// Auto-refresh threshold calculation (1st & 15th of the month)
				if ($today_day >= 15) {
					$threshold = strtotime(date('Y-m-15 00:00:00'));
				} else {
					$threshold = strtotime(date('Y-m-01 00:00:00'));
				}

				if ($last_updated < $threshold) {
					$force_refresh = true;
				} else {
					$stats = $cached_data['data'];
					$data['last_updated'] = $cached_data['last_updated'];
				}
			} else {
				$force_refresh = true;
			}
		} else {
			$force_refresh = true;
		}

		if ($force_refresh) {
			$stats = $this->_rebuild_stats_cache($cache_file);
			$data['last_updated'] = date('Y-m-d H:i:s');
		}

		$data['stats'] = $stats;
		$data['tbs_config'] = $tbs_config;
		$data['title'] = 'Dashboard';

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/pg_index', $data);
		$this->load->view('admin/footer');
	}

	public function refresh_stats()
	{
		// Only "all" (Super Admin) can refresh stats
		$allowed = json_decode($this->session->userdata('chapter'), 1);
		if (empty($allowed) || $allowed[0] != 'all') {
			redirect('admin/chapter', 'refresh');
		}

		$cache_file = APPPATH . 'logs/dashboard_stats.json';
		$this->_rebuild_stats_cache($cache_file);

		$this->session->set_flashdata('success_message', 'Dashboard statistics refreshed successfully.');
		redirect('admin/index', 'refresh');
	}

	private function _rebuild_stats_cache($file_path)
	{
		$stats = $this->backend_model->calculate_dashboard_stats();
		$cache_content = array(
			'last_updated' => date('Y-m-d H:i:s'),
			'data' => $stats
		);
		// Ensure parent folder exists
		$dir = dirname($file_path);
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		file_put_contents($file_path, json_encode($cache_content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		return $stats;
	}

	public function load_joomla($raw_url){
		$data = $this->data;
		$data['url'] = urldecode(base64_decode($raw_url));
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/load_joomla', $data);
		$this->load->view('admin/footer', $data);
	}

	public function update_default_chapter($chapter_used,$chapter_page=""){
		// Add to Session
        $this->session->set_userdata(array(
            'chapter_used' => $chapter_used,
        ));
        /*if($chapter_page == "chapter_page")
        	redirect(base_url("admin/chapter"),'refresh');
        else
        	redirect($this->input->server('HTTP_REFERER'),'refresh');
        */
        // All redirect to chapter page
        redirect(base_url("admin/chapter"),'refresh');
	}

	public function export_db(){

		$this->db = $this->load->database('local', TRUE);
		$this->load->dbutil();
		
		$filename = 'backup-'.date('YmdHis').'.sql';

		$prefs = array(
			'tables'        => array(
				'tbs_agm_attendance',
				'tbs_agm_zoom_reg',
				'tbs_chapter',
				'tbs_chapter_contact',
				'tbs_chapter_member',
				'tbs_contact',
				'tbs_dharma_staff',
				'tbs_dizang',
				'tbs_dizang_user',
				'tbs_member',
				'tbs_dharma_insurance',
				'tbs_wenxuan_subscriber',
				'tbs_wenxuan_subscriber_package',
				'tbs_wenxuan_subscriber_year',
				'tbs_wenxuan_user'
			),   // Array of tables to backup.
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
		if(isset($upload['error']) && !$upload['error']){
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

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/import_db_view', $data);
		$this->load->view('admin/footer', $data);
	}



}

?>
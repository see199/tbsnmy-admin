<?php
class Newsletter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('event', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('backend_model');
		$this->config->load('siteinfo', TRUE);

		//if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		// $this->data = load_view_data($this->session);
		// $this->url_name = $this->session->userdata('chapter_used');
	}

	public function index() {
		$this->generate_newsletter(date('Ym',strtotime("+1 month")));
	}

	public function current(){
		$this->generate_newsletter(date('Ym'));
	}

	public function month($year,$month){
		$this->generate_newsletter($year.$month);
	}

	private function generate_newsletter($date){

		list($this->year,$month) = str_split($date,4);
		$data['year'] = $this->year;
		$this->month = $data['month'] = (int) $month; // 08 become 8
		$this->next_yearmonth = date('Y-m-05',strtotime($this->year.'-'.$this->month.'-01') + 31*24*3600);
		//$this->next_month = $month + 1;
		$this->prev_month = $data['prev_month'] = $month - 1;

		// Get Monthly Cal & Monthly Event for Boyeh
		$data['boyeh'] = $this->get_boyeh_events($date);

		// Get Event
		$data['event'] = $this->get_monthly_event();

		// Get TBS News For Past Month
		$data['tbsnews'] = $this->get_tbsnews();
		$data['tbsnews_date'] = ($this->prev_month == 0) ? ($this->year - 1) . '年12月' : $this->year . '年' . $this->prev_month . '月';

		// Get Qing Liang Image

		//print_r($data);
		$this->load->view('admin/newsletter_template', $data);
	}

	private function get_boyeh_events($date){

		// Get Monthly Cal For Boyeh
		$cal_img = $this->config->item('monthly_cal_location').'boyeh/'.$date.'.jpg';
		$data['cal'] = (file_exists($cal_img)) ? $this->generate_img_url($cal_img) : '';

		// Get Monthly Event For Boyeh
		$path = $this->config->item('event_img_location') . $this->year . '/' . $this->month . '/';
		$event_img = glob($path.'fahui*boyeh.jpg');
		if(sizeof($event_img) > 0){
			foreach($event_img as $img) $data['event'][] = $this->generate_img_url($img);
		}else
			$data['event'] = '';

		return $data;
	}

	private function get_monthly_event(){

		$newsletter_img_path = '/images/stories/upcoming-activities/';

		// Get Event
		$events = $this->backend_model->get_monthly_event(array(
			'start' => $this->year.'-'.$this->month.'-01',
			'end'   => $this->next_yearmonth,//date('Y-m-d',strtotime("-1 DAY ".$this->year.'-'.$this->next_month.'-06')),
		));
		foreach($events as $e){
			$e['event_url'] = $this->config->item('event_url') . $e['event_id'] . '.html';
			$e['chapter_domain'] = $this->config->item('chapter_url') . $e['chapter_url'];
			if($e['remarks']){
				$image = preg_match("@[0-9]+/[0-9]+/(?P<type>\w+).jpg@",$e['remarks'],$match);
				$e['resize_img'] = $this->generate_img_url($this->config->item('event_img_location') . $match[0] ,'thumb');
				$data['with_img'][$match[0]] = $e;
			}
			$data['all_event'][] = $e;
		}
		return $data;
	}

	private function get_tbsnews(){

		$year  = $this->year;
		$month = $this->prev_month;
		if($month == 0){
			$year -= 1;
			$month = 12;
		}

		// General Config
		$one_week_sec = 604800;
		$first_issue  = strtotime('2015-01-01') - ($one_week_sec * 1037); // Issue on 2015-01-01 is 1037

		// File Location
		$location     = $this->config->item('tbnews_upload_location') . $year;
		$url_location = $this->generate_img_url($location);

		// Time for latest Thursday
		$time = ($year == date('Y')) ? time() : strtotime($year.'-12-31');
		$last_thursday = strtotime("last thursday", $time); // Issue On Every Thursday

		// Start From Last Thursday Until First of This Year, Loop every 1 week
		$data = array();
		for($i = $last_thursday; $i >= strtotime($year.'-01-01'); $i -= $one_week_sec){
			if((int)date('m',$i) == $month){
				$issue = round(($i - $first_issue) / $one_week_sec);
				// Check if PDF file exists
				if(file_exists($location.'/WTBN'.$issue.'.pdf')){
					$data[$issue] = array(
						'date'  => date('Y年m月d日',$i),
						'link'  => $url_location.'/WTBN'.$issue.'.pdf',
					);
				}
			}
			if((int)date('m',$i) < $month) break;
		}
		return $data;
	}

	private function generate_img_url($img, $thumb = ''){
		if(!$thumb)
			return $this->config->item('site_url','siteinfo') . substr($img,2);
		else
			return $this->config->item('cimage_url') . preg_replace($this->config->item('replace_source'),$this->config->item('replace_result'),$img);
	}
}

?>
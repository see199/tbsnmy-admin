<?php
class Chapter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('chapter', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('backend_model');

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){
		$data = $this->data;

		$chapter = $this->backend_model->get_chapter_details($this->url_name);

		// AJK
		$this->load->model('contact_model');
		$chapter_member = $this->contact_model->get_chapter_ajk($chapter['chapter_id']);
		// AJK Sorting
		{
			$ordering = array("堂主","顧問","永久顧問","法律顧問","法務顧問","行政顧問","常住","主席","總會長","署理主席","署理總會長","副主席","副總會長","總秘書","秘書","副總秘書","副秘書","總財政","財政","副總財政","副財政","總務","副總務","公關");
			$exists_position = $sorted_chapter_member = $normal_ajk = array();

			foreach($chapter_member as $k => $c){
				$exists_position[$c['position']][$c['contact_id']] = $k;
				$chapter_member[$k] = $this->bday_cal($c);
			}
			foreach($ordering as $o){
				if(isset($exists_position[$o])){
					foreach($exists_position[$o] as $new_k){
						$sorted_chapter_member[] = $chapter_member[$new_k];
						unset($chapter_member[$new_k]);
					}
				}
			}
			foreach($chapter_member as $c) if(in_array($c['position'],array("理事","中央理事","委員"))) $normal_ajk[] = $c; else $sorted_chapter_member[] = $c;
			$chapter_member = array_merge($sorted_chapter_member,$normal_ajk);
		}

		// Website Setting
		$web = json_decode($chapter['website'],1);
		$chapter['weburl'] = $web['link'];
		$chapter['minisite'] = json_decode($chapter['minisite'],1);

		$default_bgimg = 'asset/img/bg_default.jpg';
		$chapter_bgimg = 'asset/img/bg_'.$chapter['url_name'].'.jpg';
		$chapter['bgimgurl'] = (file_exists($chapter_bgimg)) ? $chapter_bgimg : $default_bgimg;

		$rand = rand(2,24);
        $rand = ($rand > 9) ? $rand : '0'.$rand;
		$default_bannerimg = '../images/banners/master'.$rand.'.jpg';
		$chapter_bannerimg = 'asset/img/banner_'.$chapter['url_name'].'.jpg';
		$chapter['bannerimgurl'] = (file_exists($chapter_bannerimg)) ? $chapter_bannerimg : $default_bannerimg;

		$data['chapter'] = $chapter;
		$data['chapter_member'] = $chapter_member;
		$data['form_state'] = form_dropdown('chapter[state]', array(
			'Johor' => 'Johor',
			'Kedah' => 'Kedah',
			'Kelantan' => 'Kelantan',
			'Melaka' => 'Melaka',
			'Negeri Sembilan' => 'Negeri Sembilan',
			'Pahang' => 'Pahang',
			'Penang' => 'Penang',
			'Perak' => 'Perak',
			'Perlis' => 'Perlis',
			'Sabah' => 'Sabah',
			'Sarawak' => 'Sarawak',
			'Selangor' => 'Selangor',
			'Terengganu' => 'Terengganu',
			'W.Persekutan' => 'W.Persekutan',
		), $chapter['state'], "class='form-control col-xs-8'");

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/chapter_index_view',$data);
		$this->load->view('admin/footer');
	}

	private function bday_cal($d){
		if($this->session->userdata('email') == 'see199@gmail.com'){
			$cal  = 1+9;
			$cal2 = 0;
			$cal3 = 0;
			if(!$d['nric']){
				$d['bday_cal'] = '';
				return $d;
			}
			foreach(str_split(explode("-",$d['nric'])[0]) as $i){
				$cal += $i;
			}
			if($cal > 9){
				foreach(str_split($cal) as $i){
					$cal2 += $i;
				}
				if($cal2 > 9){
					foreach(str_split($cal2) as $i){
						$cal3 += $i;
					}
					$cal2 = $cal2."|".$cal3;
				}
			}
			$d['bday_cal'] = "<br />".$d['nric']."(".$cal."|".$cal2.")";
		}else{
			$d['bday_cal'] = "<br />".$d['nric'];
		}
		return $d;
	}

	public function update(){
		$chapter = $this->input->post('chapter');
		
		$chapter['website'] = json_encode(array(
			'label' => $chapter['weburl'],
			'link'  => $chapter['weburl'],
		));
		unset($chapter['weburl']);

		$chapter['minisite'] = json_encode($chapter['minisite']);

		if(isset($_FILES['bgimg'])){
			$file = $_FILES['bgimg'];
			$target = 'asset/img/bg_'.$this->url_name.'.jpg';
			if(!$file['error']){
				if(file_exists($target)) unlink($target);
				move_uploaded_file($file['tmp_name'],$target);
			}
		}

		if(isset($_FILES['bannerimg'])){
			$file = $_FILES['bannerimg'];
			$target = 'asset/img/banner_'.$this->url_name.'.jpg';
			if(!$file['error']){
				if(file_exists($target)) unlink($target);
				move_uploaded_file($file['tmp_name'],$target);
			}
		}

		$this->backend_model->update_chapter($chapter,$this->url_name);
		$this->backend_model->log_activity($this->session->userdata('user_id'),'update_chapter: '.$this->url_name);

		redirect('admin/chapter','refresh');
	}

	public function list_contact(){
		$data = $this->data;

		$chapters = $this->backend_model->get_all_chapter_contact();

		$list = $sorted_list = array();

		foreach($chapters as $c){
			$list[$c['state']][$c['chapter_id']] = $c;
		}
		//print_pre($list);
		$state_list = array('Sabah','Sarawak','Perlis','Kedah','Penang','Perak','Selangor','W.Persekutan','Melaka','Negeri Sembilan','Johor','Kelantan','Terengganu','Pahang');
		foreach($state_list as $v) $sorted_list[$v] = $list[$v];

		$data['contact'] = $sorted_list;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/chapter_list_contact_view',$data);
		$this->load->view('admin/footer');
	}


	


}

?>
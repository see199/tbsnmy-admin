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
		$chapter_member = $this->order_committee_members($chapter_member);
		foreach($chapter_member as $k => $v) $chapter_member[$k] = $this->bday_cal($v);
		
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

		//NAS Location
		$chapter['nas_location'] = $this->get_nas_location($chapter);

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

	private function get_nas_location($chapter){

		switch($this->session->userdata('email')) {
			case 'see199@gmail.com':
				$nas_location = 'C:\Users\boyet\Synology';
				break;
			case 'tandy@tbsn.my':
				$nas_location = 'C:\Users\chong\SYNOLOGY';
				break;
			default:
				$nas_location = '';
		}

		// Check for empty TB_ID or MizongMemberID
		$tb_id         = $chapter['tb_id'] ? $chapter['tb_id'] : 'C00000';
		$membership_id = $chapter['membership_id'] ? $chapter['membership_id'] : '0000';

		// Determine the folder name based on chapter status
		switch ($chapter['status']) {
		    case 'A':
		        $nas_folder = '11-道場 Active';
		        break;
		    case 'I':
		        $nas_folder = '13-道場 Inactive';
		        break;
		    default:
		        $nas_folder = '12-道場 New';
		}

		// Remove 真佛宗 in name_chinese
		$name = str_replace('真佛宗', '', $chapter['name_chinese']);

		// Create the folder name
		$folder_name = ($chapter['status'] === 'A' || $chapter['status'] === 'I') 
		               ? "{$tb_id}-{$membership_id}-{$name}" 
		               : $name;

		// Set the nas_location
		return "{$nas_location}\\秘書\\41-道場\\{$nas_folder}\\{$folder_name}";
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
				if(is_numeric($i)) $cal += $i;
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
			$d['bday_cal'] = "<br />".$d['nric']." (".$cal."|".$cal2.")";
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
		foreach($state_list as $v) $sorted_list[$v] = isset($list[$v]) ? $list[$v] : array();

		$data['contact'] = $sorted_list;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/chapter_list_contact_view',$data);
		$this->load->view('admin/footer');
	}

	public function list_all(){
		$data = $this->data;

		$this->load->model('api_model');
		$this->load->config('tbsparam');

		$chapters = json_decode($this->api_model->get_all_chapter_details(),1);

		$list = $sorted_list = array();

		foreach($chapters as $c){
			//echo '<br />-'.$c['state']. ' - '.$c['chapter_id'];
			$list[$c['state']][$c['chapter_id']] = $c;
		}
		$state_list = array('Sabah','Sarawak','Perlis','Kedah','Penang','Perak','Selangor','W.Persekutan','Melaka','Negeri Sembilan','Johor','Kelantan','Terengganu','Pahang');
		foreach($state_list as $v) $sorted_list[$v] = $list[$v];

		$data['contact'] = $sorted_list;
		$data['chapter_status'] = $this->config->item('chapter_status');
		echo $this->config->item('chapter_status');;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/chapter_list_all_view',$data);
		$this->load->view('admin/footer');
	}

	public function list_ajk(){
		$data = $this->data;

		$chapter = $this->backend_model->get_chapter_details($this->url_name);

		// AJK
		$this->load->model('contact_model');
		$chapter_member = $this->contact_model->get_chapter_ajk($chapter['chapter_id']);

		// Sort AJK
		$chapter_member = $this->order_committee_members($chapter_member);
		//print_pre($chapter_member);

		$data['chapter'] = $chapter;
		$data['chapter_member'] = $chapter_member;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/chapter_list_ajk_view',$data);
		$this->load->view('admin/footer');
	}

	private function order_committee_members($chapter_members) {
        $position_order = ["堂主","顧問","永久顧問","法律顧問","法務顧問","行政顧問","常住","主席","總會長","署理主席","署理總會長","副主席","副總會長","總秘書","秘書","副總秘書","副秘書","總財政","財政","副總財政","副財政","總務","副總務","公關"];
        $last_position = ["理事","中央理事","委員","稽查師","查賬","員工"];
        
        usort($chapter_members, function($a, $b) use ($position_order, $last_position) {
            $a_pos = array_search($a['position'], $position_order);
            $b_pos = array_search($b['position'], $position_order);
            $a_last = array_search($a['position'], $last_position);
            $b_last = array_search($b['position'], $last_position);
            
            // If both positions are in the main order list
            if ($a_pos !== false && $b_pos !== false) {
                return $a_pos - $b_pos;
            }
            
            // If one is in main order and the other is not
            if ($a_pos !== false) return -1;
            if ($b_pos !== false) return 1;
            
            // If both are in last position order
            if ($a_last !== false && $b_last !== false) {
                return $a_last - $b_last;
            }
            
            // If one is in last position and the other is not
            if ($a_last !== false) return 1;
            if ($b_last !== false) return -1;
            
            // Any other positions stay in the middle
            return 0;
        });
        
        return $chapter_members;
    }



	public function update_ajk_list(){

		$this->load->model('contact_model');

		//print_pre($this->input->post());
		$contact_list = $this->input->post('contact');
		$chapter_id   = $this->input->post('chapter_id');
		$contact_new  = $this->input->post('contact_new');

		// Update Current AJK
		foreach($contact_list as $contact_id => $c){

			// Update Contact
			$this->contact_model->update_contact([
				'contact_id'   => $contact_id,
				'name_chinese' => $c['name_chinese'],
				'name_dharma'  => $c['name_dharma'],
				'name_malay'   => $c['name_malay'],
				'phone_mobile' => $c['phone_mobile'],
				'email'        => $c['email'],
				'nric'         => '', // Don't Update NRIC
			]);

			// Update Chapter Member
			$this->contact_model->replace_chapter_member([
				'chapter_id' => $chapter_id,
				'contact_id' => $contact_id,
				'position'   => isset($c['delete']) ? "會員" : $c['position'],
			],$c['cm_id']);
		}

		// Add New AJK
		foreach($contact_new as $c){
			
			// Only Add AJK with NRIC
			if($c['nric']){

				// If Contact already exists, just update
				if($c['contact_id']){
					$contact_id = $c['contact_id'];
					$this->contact_model->update_contact([
						'contact_id'   => $c['contact_id'],
						'name_chinese' => $c['name_chinese'],
						'name_dharma'  => $c['name_dharma'],
						'name_malay'   => $c['name_malay'],
						'phone_mobile' => $c['phone_mobile'],
						'email'        => $c['email'],
						'nric'         => $c['nric'],
					]);
				} else {

					// Create New Contact
					$res = $this->contact_model->add_contact([
						'contact_id'   => $c['contact_id'],
						'name_chinese' => $c['name_chinese'],
						'name_dharma'  => $c['name_dharma'],
						'name_malay'   => $c['name_malay'],
						'phone_mobile' => $c['phone_mobile'],
						'email'        => $c['email'],
						'nric'         => $c['nric'],
					]);
					$contact_id = $res['contact_id'];
				}

				// Update if Chapter ID is same -- For 會員 to 理事
				// If not, just add new ChapterMember
				$cm_id = ($c['chapter_id'] == $chapter_id) ? $c['cm_id'] : '';

				$this->contact_model->replace_chapter_member([
					'chapter_id' => $chapter_id,
					'contact_id' => $contact_id,
					'position'   => $c['position'],
				],$cm_id);

			}
		}

		redirect('admin/chapter/list_ajk','refresh');

	}

	public function ajax_get_contact(){
		$this->load->model('contact_model');
		$contact = $this->contact_model->get_contact_by_nric($this->input->post('nric'),true);
		echo json_encode($contact);
	}


	


}

?>
<?php

class Api extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper(array('url','language','file','common_helper','form'));
		$this->lang->load('api', 'english');
		$this->config->load('siteinfo', TRUE);
		$this->data['meta'] = array();
	}

	public function index(){

		redirect('admin/login');

	}

	public function yigong($from=1,$to=999){
		$data = $this->data;

		$p_Filepath = 'application/logs/res.csv';

		$col = array(
			'rdate','cname','ename','dname','sex','dob','job','refdate','refchap','contact','email','address','drive','skill','tshirt','exp','req','team'
		);
		$yigong = array();
		$check_duplicate = array();

		$i = 0;
		$file = fopen($p_Filepath, 'r');
		while($row=fgetcsv($file)){

			if($i >= $from && $i <= $to){
				foreach($col as $k => $v) {
					$temp[$v] = $row[$k];
				}

				// Check for duplicate & age > 19
				$check_key = $temp['ename'].'-'.$temp['email'];
				if(!isset($check_duplicate[$check_key]) && strtotime($temp['dob']) < strtotime('1999-01-01')) {

					$check_duplicate[$check_key] = $check_key;

					// Setting - skill
					$skill_list = array(
						'/基本電腦運用 Basic Computer Operation/',
						'/電腦設計 Digital Design \(PS\, AI\)/',
						'/網絡宣傳 Online Marketing/',
						'/撰寫稿件 Writing Article/',
						'/CPR 急救/',
						'/售賣組 Retail/',
						'/接待組 Reception/',
						'/文集推廣組 Books Promotion/',
					);
					$skill_ary = array();
					foreach($skill_list as $k => $v) $skill_ary['s'.($k+1)] = preg_match($v,$temp['skill']);
					$temp['skill_ary'] = $skill_ary;

					$temp_skill = preg_replace($skill_list,'',$temp['skill']);
					$skill = explode(',',$temp_skill);
					foreach($skill as $k => $v){
						if(trim($v) != '') $temp['skill_oth'][] = trim($v);
					}


					// Setting - Exp
					$exp_list = array(
						'/報名組 Registration/',
						'/佈置組 Decoration/',
						'/交通組 Transportation/',
						'/保安組 Security/',
						'/清潔組 Cleaning/',
						'/皈依組 Refuge Registration/',
						'/焚化組 Burning Mantra Paper/',
						'/售賣組 Retail/',
						'/接待組 Reception/',
						'/文集推廣組 Books Promotion/',
					);
					$exp_ary = array();
					foreach($exp_list as $k => $v) $exp_ary['s'.($k+1)] = preg_match($v,$temp['exp']);
					$temp['exp_ary'] = $exp_ary;

					$temp_exp = preg_replace($exp_list,'',$temp['exp']);
					$exp = explode(',',$temp_exp);
					foreach($exp as $k => $v){
						if(trim($v) != '') $temp['exp_oth'][] = trim($v);
					}

					// Setting
					$dob = explode('/',$temp['dob']);
					$temp['dob'] = date('d M Y',strtotime($dob[2].'-'.$dob[0].'-'.$dob[1]));

					$yigong[] = $temp;
					unset($temp);
				}
			}
			$i++;
			//echo "<br />ROW: $i <br />";
		}
		fclose($file);//print_pre($skill2);

		$data['yigong'] = $yigong;

		$this->load->view('api/base_header', $data);
		$this->load->view('api/yigong', $data);
		//print_pre($yigong);


	}

	public function event_list(){
		$data = $this->data;
		$db_data = $this->api_model->get_event_list($this->uri->segment(3));
		$data['event'] = $db_data;

		$data['site_url'] = $this->config->item('site_url','siteinfo');

		$this->load->view('api/base_header', $data);
		$this->load->view('api/event_list', $data);
		$this->load->view('api/base_footer');
	}

	public function monthly_calendar($temple = 'boyeh'){
		$data = $this->data;
		$location = $this->config->item('monthly_cal_location');//'/images/monthly';
		$file_location = $this->config->item('site_loc','siteinfo') . $location;
		$data['url_location'] = $this->config->item('site_url','siteinfo') . $location;

		$data['temple'] = $temple;
        $date_array     = array(date('Ym'),date('Ym',mktime(0, 0, 0, date('m')+1, 1, date('Y'))),date('Ym',mktime(0, 0, 0, date('m')+2, 1, date('Y'))));

        $display = $list_menu = array();
        $dh = opendir($file_location);
        while(false !== ($filename = readdir($dh))){
        	if(is_dir($file_location.'/'.$filename) && !in_array($filename,array('.','..'))) $chapter_list[$filename] = '';
        }

        foreach($chapter_list as $tk => $tv){
            foreach($date_array as $date){
            	if(file_exists($file_location.'/'.$tk.'/'.$date.'.jpg')){
                
                    if($tk == $temple)
                        $display[$date] = $date;
                    $chapter = json_decode($this->api_model->get_chapter_details($tk));
                    $list_menu[$tk] = preg_replace(array('/真佛宗/','/馬來西亞/'),"",$chapter->name_chinese);
                }
            }
        }
        $data['display']   = $display;
        $data['list_menu'] = $list_menu;
        
        $this->load->view('api/base_header', $data);
        $this->load->view('api/monthly_calendar',$data);
        $this->load->view('api/base_footer');
	}
	
	public function tbsnews_stats($year = ''){
		if(!$year) $year = date('Y');

		$data = array('view','download');
		$res  = array(
			'year' => $year,
			'view'     => array('total' => 0),
			'download' => array('total' => 0),
		);


		foreach($data as $type){
			foreach(explode("\n",read_file("application/logs/tbsnews_".$type."_".$year.".log")) as $row){

				if($row){
					list($issue,$date) = explode("\t",$row);
					list($y1,$y2,$mm,$dd,,,) = str_split($date,2);

					@$res[$type]['total'] += 1;
					@$res[$type]['details'][(int)$mm]['total'] += 1;
					@$res[$type]['details'][(int)$mm]['details'][(int)$dd] += 1;
					@$res['issue'][$issue]['total'] += 1;
					if(!isset($res['issue'][$issue]['date']))
						@$res['issue'][$issue]['date'] = date('Y-m-d',calculate_tbsnews_date($issue));
				}
			}
		}
		krsort($res['issue']);
		$this->load->view('api/tbsnews_stats',$res);
		
	}

	public function viewtbsnews($issue,$download=false){
		$data = $this->data;
		
		// Logging for statistics
		$type = ($download) ? "_download_" : "_view_";
		write_file("application/logs/tbsnews".$type.date('Y').'.log', $issue."\t".date('YmdHis')."\n", "a");
		
		// General Config
		$first_issue  = $this->cal_first_issue();
		$one_week_sec = 604800;
		$limit_check  = 7;
		$datetime = $first_issue + $one_week_sec * $issue;

		$data['year'] = $year = date('Y',$datetime);
		$data['date'] = $date = date('Y年m月d日',$datetime);

		// File Location
		$location = '/asset/img/tbsnews/'.$year;
		$file_location = $this->config->item('site_loc','siteinfo') . $location;
		$url_location  = $this->config->item('gcs_url','siteinfo') . '/tbs-news/'.$year;
		$data['pdf'] = $url_location.'/WTBN'.$issue.'.pdf';

		if($download){
			$pdf_file = $file_location.'/WTBN'.$issue.'.pdf';
			header('Content-Type: application/pdf');
			header('Content-disposition: attachment;filename=WTBN'.$issue.'.pdf');
			readfile($pdf_file);
			exit;
		}

		//Previous & Next Issue
		$data['previous_issue'] = $data['next_issue'] = '';
		for($i=1;$i<=$limit_check;$i++){
			$previous_issue = $issue - $i;
			if(!$data['previous_issue'] && file_exists($file_location.'/WTBN'.$previous_issue.'.pdf')){
				$data['previous_issue'] = $previous_issue;
			}
			$next_issue = $issue + $i;
			if(!$data['next_issue'] && file_exists($file_location.'/WTBN'.$next_issue.'.pdf')){
				$data['next_issue'] = $next_issue;
			}
			if($data['next_issue'] && $data['previous_issue']) break;
		}

		// Load images file - for mobile that can't view PDF
		$image_page = 0;
		$data['images'] = array();
		do{
			$image_page++;
			$image_url = "https://storage.googleapis.com/tbs-news/".$year."/WTBN".$issue."_page-".sprintf("%04d", $image_page).".jpg";
			@$a = file_get_contents($image_url);
			if($a) $data['images'][] = $image_url;
		}while($a);

		$data['issue'] = $issue;
		$meta_image_url = "https://storage.googleapis.com/tbs-news/".$year."/WTBN".$issue.".jpg";
		$data['meta_fb'] = array(
			'og:title'       => sprintf(lang('viewtbsnews_fb_like_title'),$issue),
			'og:site_name'   => lang('viewtbsnews_fb_like_site_name'),
			'og:description' => sprintf(lang('viewtbsnews_fb_like_description'),$issue,$issue,$date,$issue),
			//'og:image'       => file_exists($file_location.'/WTBN'.$issue.'.jpg') ? $url_location.'/WTBN'.$issue.'.jpg' : lang('viewtbsnews_fb_like_image'),
			'og:image'       => @file_get_contents($meta_image_url) ? $meta_image_url : lang('viewtbsnews_fb_like_image'),
			'og:image:width'  => '1080',
			'og:image:height' => '567',
		);

		$this->load->view('api/viewtbsnews',$data);
	}

	private function cal_first_issue(){
		$issue = 1037;
		$date  = strtotime('2015-01-01');
		$one_week_sec = 604800;

		return $date - ($one_week_sec * $issue);
	}

	public function tbsnews(){
	    
		$data = $this->data;

		// General Config
		$first_issue  = $this->cal_first_issue(); // server: 792910800; // local: 792892800;
		$one_week_sec = 604800;
		$data['year'] = $year = ($this->uri->segment(2)) ? $this->uri->segment(2) : date('Y');
		//$data['year'] = $year = ($this->uri->segment(3)) ? $this->uri->segment(3) : date('Y');

		// File Location
		$location = '/asset/img/tbsnews/'.$year;
		$file_location = $this->config->item('site_loc','siteinfo') . $location;
		$url_location  = $this->config->item('gcs_url','siteinfo') . '/tbs-news/'.$year;

		// Time for latest Thursday
		$time = ($year == date('Y')) ? time() : strtotime($year.'-12-31');
		
		$last_thursday = strtotime("last thursday", $time); // Issue On Every Thursday

		// Start From Last Thursday Until First of This Year, Loop every 1 week
		$data['tbsnews'] = array();
		for($i = $last_thursday; $i >= strtotime($year.'-01-01'); $i -= $one_week_sec){
			$issue = round(($i - $first_issue) / $one_week_sec);
			
			// Check if PDF file exists
			if(file_exists($file_location.'/WTBN'.$issue.'.pdf')){
				$data['tbsnews'][$issue] = array(
					'date' => date('Y年m月d日',$i),
					'link' => $url_location.'/WTBN'.$issue.'.pdf',
				);
			}else{

				// if file not exists, check google console & create fake file
				@$a = file_get_contents("https://storage.googleapis.com/tbs-news/".$year."/WTBN".$issue.".pdf");
				if($a){
					file_put_contents($file_location.'/WTBN'.$issue.'.pdf',"");
					$data['tbsnews'][$issue] = array(
						'date' => date('Y年m月d日',$i),
						'link' => $url_location.'/WTBN'.$issue.'.pdf',
					);
				}
			}
		}

		// Page Navigation
		$data['pagenavi'] = array(
			'previous' => ($year > 2013) ? $year - 1 : '',
			'next'     => ($year == date('Y')) ? '' : $year + 1,
			'url'      =>  'http://news.tbsn.my/year',
		);

		// Load View
		$this->load->view('api/base_header', $data);
		$this->load->view('api/tbsnews',$data);
		$this->load->view('api/base_footer');

	}

	public function fb_like(){
		$data = $this->data;
		$data['meta_fb'] = array(
			'og:title'       => lang('fb_like_title'),
			'og:site_name'   => lang('fb_like_site_name'),
			'og:description' => lang('fb_like_description'),
			'og:image'       => lang('fb_like_image'),
		);


		$data['pages'] = $this->api_model->get_fb_list();

		$data['pages2'] = array(
			array(
				'name_chinese' => '請佛住世全國供僧大會',
				'fb_page'      => 'https://www.facebook.com/offering2sangha',
			),
			array(
				'name_chinese' => '馬密總真佛影映網站',
				'fb_page'      => 'https://www.facebook.com/pages/馬密總真佛影映網站/1458216997782511',
			),
			array(
				'name_chinese' => '馬來西亞華光功德會',
				'fb_page'      => 'https://www.facebook.com/lotuslight.org.my',
			),
			array(
				'name_chinese' => '馬來西亞真佛宗華光功德會(霹雳州分会)',
				'fb_page'      => 'https://www.facebook.com/CFZLotusLightPerakMalaysia',
			),
			array(
				'name_chinese' => '華光功德會 - 柔南分會',
				'fb_page'      => 'https://www.facebook.com/pages/%E8%8F%AF%E5%85%89%E5%8A%9F%E5%BE%B7%E6%9C%83-%E6%9F%94%E5%8D%97%E5%88%86%E6%9C%83/850682531622987',
			),
			array(
				'name_chinese' => '古來華光功德會',
				'fb_page'      => 'https://www.facebook.com/lotuslightkulaijaya',
			),
			array(
				'name_chinese' => '华光功德会(东海岸分会)',
				'fb_page'      => 'https://www.facebook.com/pages/Lotus-Light-Charity-East-Coast-Of-Malaysia-%E5%8D%8E%E5%85%89%E5%8A%9F%E5%BE%B7%E4%BC%9A-%E4%B8%9C%E6%B5%B7%E5%B2%B8%E5%88%86%E4%BC%9A-/156649647678966',
			),
			array(
				'name_chinese' => '真佛宗大善堂(马六甲华光功德会)',
				'fb_page'      => 'https://www.facebook.com/pages/%E7%9C%9F%E4%BD%9B%E5%AE%97%E5%A4%A7%E5%96%84%E5%A0%82-%E9%A9%AC%E5%85%AD%E7%94%B2%E5%8D%8E%E5%85%89%E5%8A%9F%E5%BE%B7%E4%BC%9A/157139211011297',
			),
			array(
				'name_chinese' => '沙巴亞庇本覺華光功德會',
				'fb_page'      => 'https://www.facebook.com/pages/%E6%B2%99%E5%B7%B4%E4%BA%9E%E5%BA%87%E6%9C%AC%E8%A6%BA%E8%8F%AF%E5%85%89%E5%8A%9F%E5%BE%B7%E6%9C%83/1459548364356832',
			),
			array(
				'name_chinese' => '亞庇華光功德會',
				'fb_page'      => 'https://www.facebook.com/pages/Lotus-Light-Charity-Society-Kota-Kinabalu-%E4%BA%9E%E5%BA%87%E8%8F%AF%E5%85%89%E5%8A%9F%E5%BE%B7%E6%9C%83/261906703833872',
			),
			array(
				'name_chinese' => '真佛宗平和養老院',
				'fb_page'      => 'https://www.facebook.com/tbspinghe',
			),
			array(
				'name_chinese' => '真佛宗祥安養老院',
				'fb_page'      => 'https://www.facebook.com/tbsxiangan',
				//'fb_page'      => 'https://www.facebook.com/pages/%E7%9C%9F%E4%BD%9B%E5%AE%97%E7%A5%A5%E5%AE%89%E9%A4%8A%E8%80%81%E9%99%A2/146498252040865',
			),
			array(
				'name_chinese' => '马密总霹雳华光青年组',
				'fb_page'      => 'https://www.facebook.com/lotuslight.pkyouth.my',
			),
			array(
				'name_chinese' => '般若青年團',
				'fb_page'      => 'https://www.facebook.com/tbs.boyeh.youth',
			),
			array(
				'name_chinese' => '法雷青年团',
				'fb_page'      => 'https://www.facebook.com/FaLeiYouth',
			),
//			array(
//				'name_chinese' => '',
//				'fb_page'      => '',
//			),
		);
		$this->load->view('api/fb_list',$data);

	}

	public function czodiac(){
		$data = $this->data;
		$this->load->view('api/czodiac');
	}

    public function member_meeting_list(){
    	$data = $this->data;
        $data['chapter'] = $this->api_model->get_member_meeting_list();
        $this->load->view('meeting_view',$data);
    }

    public function qnaEmail($email){

    	$email = urldecode($email);

    	$this->load->library('email');
    	$this->email->from('info@tbsn.org','互動就是力量');

    	$this->email->to($email);
    	$this->email->subject('RE:互動就是力量');
    	$this->email->message('阿彌陀佛，您的提問已收到，工作人員在處理中。');
    	$this->email->send();
    }

    public function sendList(){

    	$array = array(
    	);

    	foreach($array as $lucky_no => $email) $this->eventemail($email,$lucky_no);
    }

    public function eventemail($email,$lucky_no){

    	$email = urldecode($email);

    	$this->load->library('email');
    	$this->email->from('wenxuan@tbsn.org','宗委會文宣處');

    	$this->email->to($email);
    	$this->email->subject('【2022年真佛宗全球道場薈供主題壇城佈置比賽Zoom頒獎禮】邀請函');
    	$this->email->message('阿彌陀佛，

感謝您參與【2022年真佛宗全球道場薈供主題壇城佈置比賽】的投票！宗委會文宣處至誠邀請您來參與台灣時間2022年6月25日晚上8點【Zoom頒獎禮】，我們將在當晚揭曉得獎者，同時也會進行TBSN投票會員抽獎哦！

歡迎您出席，可先在這鏈接 https://bit.ly/3tVaOnX 註冊，然後您的郵箱將會收到Zoom鏈接，到時候可以直接登錄！同時也歡迎將這鏈接分享給其他想出席的同門。

為了方便抽獎，您的抽獎號碼為 【 '.$lucky_no.' 】號，您可先記住這些號碼，然後在頒獎禮上期待中獎！21份【大吉祥天女普及版宣影布畫作】將在當晚抽獎。

期待您的出席！

感恩
宗委會文宣處合十

Amitabha,

You are cordially invited to join the Awards and Lucky Draw Ceremony for the Feast Offering Themed Shrine Decoration Contest via Zoom on 2022-6-25 8pm (Taiwan Time).

Kindly register at https://bit.ly/3tVaOnX  to obtain the zoom link for the ceremony, you are welcome to share the link to other True Buddha Disciples who are interested in joining the event.

Your lucky number for the Lucky Draw Ceremony is [ '.$lucky_no.'  ] .');
    	$this->email->send();
    	echo "\r\nSent: $email";
    }

    public function unicode(){

    	$txt = $this->input->post('txt');
    	$str = "";

    	$list = mb_str_split($txt);
    	foreach(mb_str_split($txt) as $chr){
    		$str .= "{U+".dechex(mb_ord($chr, "UTF-8"))."}";
    	}

    	echo "<form action='unicode' method='post'><table><tr><td><textarea rows=5 cols=30 name=txt>{$txt}</textarea></td><td><textarea rows=5 cols=30>{$str}</textarea></td></tr><tr><td colspan=2><input type=submit></td></tr></table></form>";
    }

    private function pppp($postdata){

    	$ch =  curl_init();
    	curl_setopt( $ch, CURLOPT_URL, 'https://ch.tbsn.org/control/vote_item/add_post' );
    	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    	curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata);
    	curl_setopt( $ch, CURLOPT_COOKIE,  '_ga=GA1.2.877555084.1626956499; _fbp=fb.1.1626956499275.2053884064; __stripe_mid=09837832-b112-445d-9bd4-dda9932bb2d32ccd03; _fbc=fb.1.1645275145279.IwAR1y7eJ-tXwfwqFRKsBHWLBrnsQXFnMtOTozA-8cW3EiAIIZ4f__nnzkQGk; _gid=GA1.2.710024376.1655192308; ci_session=f35f131885d4a3dd92442f3320176db730ac4b5b');

    	$result = curl_exec( $ch );
    	curl_close( $ch );

    	//print_r($result);
    }

    private function hitme(){
    	$me = array(
    		array("096-新加坡獅城雷藏寺"	,"新加坡 <a href='https://ch.tbsn.org/page/index.html?id=121' target='_d'> 更多圖片及影片</a>","新加坡獅城雷藏寺","34197"),
    		array("099-印尼本願雷藏寺"	,"印尼 <a href='https://ch.tbsn.org/page/index.html?id=122' target='_d'> 更多圖片及影片</a>","印尼本願雷藏寺","34202"),
    	);

    	foreach($me as $list => $data){
    		$hitdata = array(
    			'vote_id' => '2',
    			'title' => $data[0],
    			'active' => '1',
    			'place' => $data[1],
    			'memo' => $data[2],
    			'nums' => '',
    			'youtube' => '',
    			'img_id' => $data[3],
    		);
    		echo "\r\nPosting ".$data[0];
    		$this->pppp($hitdata);

    	}
    }

    public function verify(){
    	$data = $this->data;
    	$data['updated'] = false;

    	$post_data = $this->input->post();

    	// Check if email exists
    	$exists = false;
    	if(isset($post_data['email']))
    		$exists = $this->api_model->check_verified_user($post_data['email']);

    	if($exists){
    		$post_data['verified_date'] = date('Y-m-d H:i:s');
    		$this->api_model->update_verified_user($post_data);
    		$data['updated'] = true;
    	}

    	$this->load->view('api/base_header', $data);
		$this->load->view('api/verify_view', $data);
		$this->load->view('api/base_footer');
    }



}



?>
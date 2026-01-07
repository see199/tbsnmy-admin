<?php
class Lists extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->library(array('session'));
		$this->load->model('wenxuan_model');

		if(!$this->session->userdata('access_token_wx') || !$this->session->userdata('email')) redirect('wenxuan/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_common_view_data($this->session);
	}

	public function index(){
		$this->chapter();
	}

	public function chapter(){
		$data = $this->data;

		$col = array('tbnews_free','tbnews_paid','randeng_free','randeng_paid','gmbook');
		foreach($col as $col_v) $total[$col_v] = 0;

		$list = $this->wenxuan_model->get_list_chapter();
		foreach($list as $k => $v){

			foreach($col as $col_v){
				if($v[$col_v]) $total[$col_v] += $v[$col_v];
				else $list[$k][$col_v] = '-';
			}
		}
		$data['list']  = $list;
		$data['total'] = $total;

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/lists_chapter_view',$data);
		$this->load->view('wenxuan/footer');

	}

	public function contact(){
		$data = $this->data;

		$col = array('tbnews_free','tbnews_paid','randeng_free','randeng_paid');
		foreach($col as $col_v) $total[$col_v] = 0;

		$list = $this->wenxuan_model->get_list_contact();
		foreach($list as $k => $v){

			foreach($col as $col_v){
				if($v[$col_v]) $total[$col_v] += $v[$col_v];
				else $list[$k][$col_v] = '-';
			}
		}
		$data['list']  = $list;
		$data['total'] = $total;
		$data['package'] = $this->wenxuan_model->get_package();

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/lists_contact_view',$data);
		$this->load->view('wenxuan/footer');

	}

	public function subscriber($year=''){
		if(!$year) $year = date('Y');

		$data = $this->data;
		$gift_sent = $payment_done = 0;
		$total = $sorted_list = $sorting = $stats = array();

		$list = $this->wenxuan_model->get_subscriber_list($year);
		//echo'<pre>';print_r($list);
		foreach($list as $wenxuan_id => $subscriber){
			$package_id   = $subscriber['package'][$year]['package_id'];
			$package_date = $subscriber['package'][$year]['create_date'];
			@$total[$package_id] += 1;
			$gift_sent += $subscriber['package'][$year]['gift_taken'];
			//$payment_done_temp = ($subscriber['package'][$year]['status'] + $subscriber['package'][$year]['fullpayment'] > 0) ? 1 : 0;
			$payment_done_temp = $subscriber['package'][$year]['status'];
			$payment_done += $payment_done_temp;
			$list[$wenxuan_id]['package'][$year]['payment_done'] = $payment_done_temp;
			$sorting[$package_date][$wenxuan_id] = $wenxuan_id;

			@$stats['raw_data'][substr($package_date, 0, 7)][$package_id] += 1;
			@$stats['packages'][$package_id] = $package_id;
		}
		ksort($total);
		ksort($sorting);

		// Statistic Sorting
		ksort($stats['raw_data']);
		foreach($stats['raw_data'] as $date => $package){
			@$stats['date'][] = $date;
			foreach($stats['packages'] as $package_id)
				@$stats['package_id'][$package_id][] = ($package[$package_id]) ? $package[$package_id] : 0;
		}
		ksort($stats['package_id']);
		//print_pre($stats);
		//print_pre($list);


		//Sort Users based on package create date instead of user create date
		foreach($sorting as $create_date => $subscribers){
			foreach($subscribers as $wenxuan_id => $s){
				$sorted_list[$wenxuan_id] = $list[$wenxuan_id];
			}
		}

		$data['list']  = $sorted_list;//$list;
		$data['total'] = $total;
		$data['year']  = $year;
		$data['stats'] = $stats;
		$data['gift_sent']    = $gift_sent;
		$data['payment_done'] = $payment_done;
		$data['package']      = $this->wenxuan_model->get_package();
		$data['form_url']     = $this->config->item('url_wenxuan_form').'/viewform/';

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/lists_subscriber_view',$data);
		$this->load->view('wenxuan/footer');
	}

	public function package(){
		$data = $this->data;

		$data['package'] = $this->wenxuan_model->get_package();

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/lists_package_view',$data);
		$this->load->view('wenxuan/footer');

	}

	public function ajax_chapter_update(){
		$post_data = $this->input->post();
		$post_data['update_by'] = $this->session->userdata('email');
		$this->wenxuan_model->replace_subscriber($post_data,$post_data['wenxuan_id'],'chapter');
		echo 1;
		$this->snapshot_list();
	}

	public function ajax_contact_update(){
		$post_data = $this->input->post();
		$subscriber_data['update_by'] = $this->session->userdata('email');

		foreach($post_data as $k => $v){
			if (strpos($k,"package_") === FALSE) {
				$subscriber_data[$k] = $v;
			}else{
				list(,$package_id,$new_k) = explode("_", $k,3);

				if($v == 'true') $v = 1;
				elseif($v == 'false') $v = 0;
				$package_data[$package_id][$new_k] = $v;
			}
		}
		$this->wenxuan_model->replace_subscriber($subscriber_data,$post_data['wenxuan_id'],'contact');

		if($post_data['wenxuan_id']){
			foreach($package_data as $pid => $new_v){
				$new_v['md5_id'] = md5($post_data['wenxuan_id'].$new_v['package_id']);
				if($new_v['package_id']) $this->wenxuan_model->replace_package_year($new_v,$post_data['wenxuan_id']);
			}
		}
		echo 1;
		$this->snapshot_list();
	}

	public function ajax_generate_package_tbody(){
		$post_data = $this->input->post();
		$html = $this->load->view('wenxuan/ajax_generate_package_tbody_view',array(
			'sp'      => isset($post_data['package']) ? $post_data['package'] : array(),
			'package' => $this->wenxuan_model->get_package(),
			'wenxuan_contact' => $post_data['wenxuan_contact'],
			'receipt_url' => $this->config->item('url_wenxuan_form').'	/receipt/',
			'form_url' => $this->config->item('url_wenxuan_form').'	/viewform/',
		), TRUE);
		echo json_encode(array('html' => $html));
	}

	public function ajax_package_update(){
		$post_data = $this->input->post();
		$this->wenxuan_model->replace_package($post_data,$post_data['package_id']);
		echo 1;
	}

	public function ajax_delete_contact(){
		$post_data = $this->input->post();
		$this->wenxuan_model->delete_contact($post_data['wenxuan_id']);
		$this->snapshot_list();
		echo 1;
	}

	public function snapshot_list(){
		
		$subscriber = $this->wenxuan_model->snapshot_subscriber();
		
		foreach($subscriber as $s){
			$report['subscriber'][$s['type']] = $s;
		}

		$subscriber = $this->wenxuan_model->snapshot_package();
		foreach($subscriber as $s){
			$report['package'][$s['year']][$s['package_name']] = $s;
		}

		//echo '<pre>';print_r($report);
		write_file($this->config->item('file_wenxuan_snapshot'),json_encode($report));
	}

	private function process_csv(){

		echo '<pre>';
		if (($handle = fopen('./application/logs/tbnewslist.csv', "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				
				if(sizeof($data) != 10){
					list($n,$t) = explode(",",$data[0]);
					$sub = array(
						'wenxuan_name'     => $n,
						'type'             => $t,
						'wenxuan_contact'  => $data[1],
						'wenxuan_address1' => $data[2],
						'wenxuan_city'     => $data[3],
						'wenxuan_postcode' => $data[4],
						'wenxuan_state'    => $data[5],
						'wenxuan_country'  => 'Malaysia',
						'tbnews_free'      => $data[6],
						'tbnews_paid'      => $data[7],
						'remarks'          => $data[8],
					);
					echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
				}else{
					$sub = array(
						'wenxuan_name'     => $data[0],
						'type'             => $data[1],
						'wenxuan_contact'  => $data[2],
						'wenxuan_address1' => $data[3],
						'wenxuan_city'     => $data[4],
						'wenxuan_postcode' => $data[5],
						'wenxuan_state'    => $data[6],
						'wenxuan_country'  => 'Malaysia',
						'tbnews_free'      => $data[7],
						'tbnews_paid'      => $data[8],
						'remarks'          => $data[9],
					);
				}
				print_r($sub);


				//INSERT & EXIT IF CANNOT FIND CHAPTER
				$res = "";//$this->wenxuan_model->search_chapter($sub['wenxuan_name']);
				$res = $this->wenxuan_model->search_contact($sub['wenxuan_name']);
				if($res){
					$sub['wenxuan_id'] = $res['wenxuan_id'];
					$this->wenxuan_model->replace_subscriber($sub,$res['wenxuan_id'],$sub['type']);
					echo "FOUND!!";
				}else{
					$this->wenxuan_model->replace_subscriber($sub,'',$sub['type']);
				}
				
			}
			fclose($handle);
		}
	}

	private function process_remarks(){

		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT wenxuan_id,remarks FROM tbs_wenxuan_subscriber WHERE remarks like '2019-3 2020-2'";
		//echo $query;
		$i = $this->db->query($query);
		$res = $i->result_array();
		
		echo "<pre>";
		print_r($res);
//exit;
		foreach($res as $r){
			echo ",".$r['wenxuan_id'];
			$array=array(
				'wenxuan_id' => $r['wenxuan_id'],
				'package_id' => '9',
			);
			$this->db = $this->load->database('local', TRUE);
			$this->db->replace('tbs_wenxuan_subscriber_year',array_merge($array,$array));

			$array=array(
				'wenxuan_id' => $r['wenxuan_id'],
				'package_id' => '5',
			);

			$this->db = $this->load->database('local', TRUE);
			$this->db->replace('tbs_wenxuan_subscriber_year',array_merge($array,$array));
		}
	}

	public function update_md5(){
		$this->db = $this->load->database('local', TRUE);

		$query = "SELECT wenxuan_id,package_id FROM tbs_wenxuan_subscriber_year WHERE md5_id = ''";
		//echo $query;
		$i = $this->db->query($query);
		$res = $i->result_array();
		
		echo "<pre>";
		print_r($res);
		//$md5_id = md5($wenxuan_id.$package_id);
		foreach($res as $r){
			$this->db
				->where('wenxuan_id',$r['wenxuan_id'])
				->where('package_id',$r['package_id'])
				->update('tbs_wenxuan_subscriber_year',array(
					'md5_id' => md5($r['wenxuan_id'].$r['package_id'])
				));
		}

	}

	public function export_csv_unsent_package($year=''){
		if(!$year) $year = date('Y');

		$data  = $this->data;
		$package_data = array();

		$list = $this->wenxuan_model->get_subscriber_list($year);
		//echo'<pre>';print_r($list);

		// Preset Value
		$header_args = array('No','Category','Parcel Content','Parcel Value','Weight','Pickup Date','Sender Name','Sender Contact','Sender Address','Sender Postcode','Sender City','Receiver Name','Receiver Contact','Receiver Address','Receiver Postcode','Receiver City');
		$preset_data = array('Fashion And Accessories','Gift and Wallet','100','1',date('Y-m-d',strtotime('+1 day')),'PABT Chen Foh Chong Malaysia','\'0333749399','1A, Jalan Perawas, Lebuh Setaka, Taman Chi Liung','41200','Klang');


		$counter = 0;
		foreach($list as $wenxuan_id => $subscriber){

			$package = $subscriber['package'][$year];

			// Only sent package that full payment is made & gift not taken
			if(!$package['gift_taken'] && $package['status'] == 1){
				//print_pre($package);

				$counter++;
				$package_data[] = array_merge(array($counter),$preset_data,array(
					$package['wenxuan_name_receiver'],
					$package['wenxuan_contact'],
					$package['wenxuan_address1'].' '.$package['wenxuan_address2'],
					$package['wenxuan_postcode'],
					$package['wenxuan_city'],
				));
			}
		}

		//print_pre($package_data);
		$this->generate_csv($header_args,$package_data);
	}

	public function export_csv_blessing($year=''){
		if(!$year) $year = date('Y');

		$data  = $this->data;
		$blessing_data = array();

		$list = $this->wenxuan_model->get_subscriber_list($year);
		//echo'<pre>';print_r($list);

		// Preset Value
		$header_args = array('姓名','年齡','地址','祈願');

		foreach($list as $wenxuan_id => $subscriber){
			$package = $subscriber['package'][$year];
			foreach(json_decode($package['register_blessing'],1) as $v){
				$blessing_data[] = $v;
			}
		}

		//print_pre($blessing_data);
		$this->generate_csv($header_args,$blessing_data,'blessing_list');
	}

	private function generate_csv($header_args,$data,$filename='csv_export'){

		ob_start();
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$filename.'.csv');
		ob_end_clean();

		$output = fopen( 'php://output', 'w' );
		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv( $output, $header_args );

		foreach( $data as $data_item ){
			fputcsv( $output, $data_item );
		}

		fclose( $output );
		exit;

	}

	public function stats_tbsnews($year = ''){
		$data = $this->data;

		if(!$year) $year = date('Y');

		$stat = array('view'/*,'download'*/);
		$res  = array(
			'year' => $year,
			'view'     => array('total' => 0),
			'download' => array('total' => 0),
		);


		foreach($stat as $type){
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
		$data = array_merge($data,$res);

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/tbsnews_stats',$data);
		$this->load->view('wenxuan/footer');
		
	}

	public function stats_analytics(){
		$data = $this->data;

		$this->load->view('wenxuan/header', $data);
		$this->load->view('wenxuan/navigation', $data);
		$this->load->view('wenxuan/stats_analytics_view',$data);
		$this->load->view('wenxuan/footer');
	}

}

?>
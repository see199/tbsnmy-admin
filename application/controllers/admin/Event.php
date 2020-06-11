<?php
class Event extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('event', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('backend_model');
		$this->list_column = array("start_date","end_date","master_name","event_name");

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index() {
		
		if ($this->input->is_ajax_request()) {
            $this->get_data_all_event();
        } else {
        	$data = $this->data;
        	$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);
        	$data['chapter_url'] = $this->url_name;

            $this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/event_index_view',$data);
			$this->load->view('admin/footer');
        }
    }

	private function get_data_all_event(){
		$data = array();
		$filter_data = $this->get_filter_data(array('chapter_url','show_all'),'end_date');

		$event = $this->backend_model->get_all_event($filter_data);
		foreach($event as $k => $v){
			if($v['master_1']){
				$master = $this->backend_model->get_master_name($v['master_1']);

				$date = $v['start_date'];
				if($v['end_date']) $date .= ' - ' . $v['end_date'];

				$data[] = array(
					'start_date'  => date('Y-m-d',strtotime($v['start_date'])),
					'end_date'    => date('Y-m-d',strtotime($v['end_date'])),
					'master_name' => $master['name'] . $master['position'] ,
					'event_name'  => '<a href="'.base_url('admin/event/detail/'.$v['event_id']).'" >' . $v['name'] . '</a>',
				);
			}
		}

		$result = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $filter_data['l2'],
            'recordsFiltered' => $this->backend_model->count_total_event($filter_data),
            'iTotalRecords' => $this->backend_model->count_total_event($filter_data),
            'data' => $data,
        );
        echo json_encode($result);
	}

	public function detail($event_id=0){
		$data = $this->data;

		$res = $this->backend_model->get_all_master();
		foreach($res as $v){
			$master[$v['master_id']] = $v['master_name'];
		}
		$js = 'id="select_master" class="form-control"';
		
		// Edit Event
		if($event_id){
			$image_path = 'images/stories/upcoming-activities/';

			$res = $this->backend_model->get_event($event_id);

			// Auto change chapter
			if($res['0']['chapter_url'] != $this->url_name){
				redirect('admin/index/update_default_chapter/'.$res['0']['chapter_url'],'refresh');
				exit;
			}
			
			$event = $res['0'];
			$image = preg_match("@[0-9]+/[0-9]+/(?P<type>\w+).jpg@",$event['remarks'],$match);

			$event['start_date'] = substr($event['start_date'],0,10);
			$event['end_date'] = substr($event['end_date'],0,10);
			
			$event['image_location'] = (sizeof($match) > 0) ? $image_path . $match[0] : '';
			$event['image'] = (file_exists('../'.$event['image_location']) && is_file('../'.$event['image_location'])) ? '<img src="/' . $event['image_location'] . '" height="150px" />' : '';

			$res = $this->backend_model->get_chapter_details($this->url_name);
			$event['chapter_name'] = $res['name_chinese'];

			$data['form_master'] = form_dropdown('event[master_1]', $master, $event['master_1'], $js);
			$data['event'] = $event;
			$data['action'] = 'update';

		}
		// Add New Event
		else{

			$data['form_master'] = form_dropdown('event[master_1]', $master, '', $js);
			$data['event'] = array();
			$data['action'] = 'insert';
		}

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/event_detail_view',$data);
		$this->load->view('admin/footer');
	}

	public function update(){

		$event = $this->input->post('event');

		$res = $this->process_image($event,$_FILES['image']);
		$event['chapter_url'] = $this->url_name;
		unset($event['image_location']);

		$event['remarks'] = $res['remarks'];

		$this->backend_model->update_event($event);
		$this->backend_model->log_activity($this->session->userdata('user_id'),'update_event: ID <a href="'.base_url('admin/event/detail/'.$event['event_id']).'">'.$event['event_id'].'</a>('.$this->url_name.')');
		redirect('admin/event/detail/'.$event['event_id'],'refresh');
	}

	public function insert(){

		$event = $this->input->post('event');

		$res = $this->process_image($event,$_FILES['image']);
		$event['chapter_url'] = $this->url_name;
		unset($event['image_location']);

		$event['remarks'] = $res['remarks'];

		$event_id = $this->backend_model->insert_event($event);
		$this->backend_model->log_activity($this->session->userdata('user_id'),'insert_event: ID <a href="'.base_url('admin/event/detail/'.$event_id).'">'.$event_id.'</a>('.$this->url_name.')');
		redirect('admin/event/detail/'.$event_id,'refresh');
	}

	private function process_image($event, $file){

		// Setting Image
		$max_size = 1000;
		$quality  = 80;
		list($eyear, $emonth, $eday) = explode('-',$event['end_date']);
		$image_path = '../images/stories/upcoming-activities/';
		$image_location = $image_path.$eyear.'/'.(int)$emonth.'/fahui_'.$eyear.$emonth.$eday.'_'.$this->url_name.'.jpg';
		$return = array();

		// Create Folder If Not Exists
		if(!file_exists($image_path.$eyear)) mkdir($image_path.$eyear,0655,true);
		if(!file_exists($image_path.$eyear.'/'.(int)$emonth)) mkdir($image_path.$eyear.'/'.(int)$emonth,0655,true);

		$default_img = '../'.$event['image_location'];

		// If New Image Uploaded
		if(!$file['error'] && $file['type'] == 'image/jpeg'){
			if(file_exists($default_img) && is_file($default_img)) unlink($default_img);

			list($w,$h,) = getimagesize($file['tmp_name']);
			$check_max = ($w > $h) ? $w : $h; // Resize from Max (Height or Width)

			// Resize if > Max Size
			if($check_max > $max_size){
				if($w == $check_max){
					$new_w = $max_size;
					$new_h = abs($h * $max_size / $w);
				}else{
					$new_h = $max_size;
					$new_w = abs($w * $max_size / $h);
				}
				$src_image = ImageCreateFromJpeg($file['tmp_name']);
				$new_image = ImageCreateTrueColor($new_w,$new_h);
				imagecopyresized($new_image,$src_image,0,0,0,0,$new_w,$new_h,$w,$h);
				ImageJpeg($new_image,$image_location,$quality);
				ImageDestroy($new_image);
				ImageDestroy($src_image);

			}else{
				move_uploaded_file($file['tmp_name'],$image_location);
			}

		}
		// If Changes in Date & No New Image Uploaded
		elseif($default_img && $default_img != $image_location && file_exists($default_img) && is_file($default_img)){
			rename($default_img, $image_location);
		}

		$return['remarks'] = (file_exists($image_location)) ? '<p><img src="'.substr($image_location,3).'" alt="'.$event['name'].'" style="display: block; margin-left: auto; margin-right: auto;" /></p>' : '';

		return $return;
		
	}


	private function get_filter_data($filter_list=array(), $default_order='') {
        $result = array();

        foreach($filter_list as $name){
            if ($this->input->post($name) != '') {
                $result[$name] = $this->input->post($name);
            }
        }
        
        // Query Limit Default Value
        $result['l1'] = ($this->input->post('start')) ? $this->input->post('start') : '0';
        $result['l2'] = ($this->input->post('length')) ? $this->input->post('length') : '10';

        // Default Ordering
        $order = $this->input->post('order');
        $result['order_by'] = (isset($order[0])) ? $this->list_column[$order[0]['column']] . ' ' . $order[0]['dir'] : $default_order;

        return $result;
    }
}

?>
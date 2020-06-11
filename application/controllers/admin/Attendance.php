<?php
class Attendance extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('language','form','url','common_helper','file'));
		$this->load->model('boyeh_model');
	}

	public function index() {

		$data = array(
			'data' => $this->load_data(),
			'start_date' => ($this->input->post('start_date')) ? $this->input->post('start_date') : date('Y-m-01'),
			'end_date'   => ($this->input->post('end_date')) ? $this->input->post('end_date') : date('Y-m-d'),
		);

		$this->load->view('admin/header', $data);
		$this->load->view('admin/boyeh/index_view', $data);
		$this->load->view('admin/footer');
	}

	public function update_log(){

		$list = explode("\r\n",read_file('application/logs/attendance.txt'));
		
		foreach($list as $k => $v){
			if($k){
				$d = explode("\t",$v);
				if($v){
					$data = array(
						'id'   => trim(rtrim($d[1])),
						'name' => trim(rtrim($d[2])) . ' ' . trim(rtrim($d[3])),
						'type' => trim(rtrim($d[8])),
						'datetime' => trim(rtrim($d[0])),
					);
					$this->boyeh_model->insert_attendance($data);
				}

			}
		}

		redirect('admin/attendance');
	}

	public function load_data() {

		$where = array(
			'datetime >=' => ($this->input->post('start_date')) ? $this->input->post('start_date') : date('Y-m-01'),
			'datetime <=' => ($this->input->post('end_date')) ? $this->input->post('end_date') : date('Y-m-d'),
		);

		$data = $this->boyeh_model->get_attendance($where);
		$data = $this->sorting($data);
		$data = $this->filter_late_comer($data);

		return $data;
	}

	private function sorting($data){

		$sorted_data = array();

		foreach($data as $v) {
			$date = substr($v['datetime'],0,10);
			$time = substr($v['datetime'],11,8);

			$sorted_data[$v['id']]['name'] = $v['name'];

			if($v['type'] == 'Check-In')
				$sorted_data[$v['id']]['data'][$date]['in'] = $time;
			else
				$sorted_data[$v['id']]['data'][$date]['out'] = $time;
		}
		return $sorted_data;
	}

	private function filter_late_comer($data) {

		foreach($data as $id => $d){
			foreach($d['data'] as $date => $type){
				if(isset($type['in'])){

					if(isset($type['out'])) {
						$data[$id]['data'][$date]['hrs'] = round((strtotime($date.' '.$type['out']) - strtotime($date.' '.$type['in']))/3600,2);
					}

					if(!isset($data[$id]['data'][$date]['hrs'])) $data[$id]['data'][$date]['hrs'] = 0;

					if((int)substr($type['in'],0,2).substr($type['in'],3,2) < 1015) {
						unset($data[$id]['data'][$date]);
					}

				}else {
					unset($data[$id]['data'][$date]);
				}
			}
		}
		
		return $data;
	}
}

?>
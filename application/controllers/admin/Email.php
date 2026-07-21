<?php
class Email extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('chapter', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model(array('backend_model','contact_model'));

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index(){
		echo 1;
	}

	public function chapter(){
		$data = $this->data;
		$chapters = $this->backend_model->get_all_chapter_email();

		$list = array();

		foreach($chapters as $c){
			$list[$c['state']][$c['chapter_id']] = $c;

			// Contact List
			$vip_ajk_email = $this->contact_model->get_chapter_vip_ajk_email($c['chapter_id']);
			$list[$c['state']][$c['chapter_id']]['vip'] = $vip_ajk_email;
		}
		//print_pre($list);

		$data['email'] = $list;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/email/chapter_view',$data);
		$this->load->view('admin/footer');
	}

	public function member(){
		$data = $this->data;
		$members = $this->backend_model->get_all_member_email();

		$data['email'] = $members;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/email/member_view',$data);
		$this->load->view('admin/footer');
	}

	public function export_member_excel(){
		$members = $this->backend_model->get_active_members();

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Set column header titles
		$headers = [
			'A1' => 'No. 會員編號 / ID',
			'B1' => 'Nama',
			'C1' => '姓名',
			'D1' => '法號',
			'E1' => 'NRIC',
			'F1' => 'Contact'
		];

		foreach ($headers as $cell => $val) {
			$sheet->setCellValue($cell, $val);
		}

		// Style header row
		$sheet->getStyle('A1:F1')->getFont()->setBold(true);
		$sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('D9EDF7');

		$rowNum = 2;
		foreach ($members as $m) {
			$sheet->setCellValue("A{$rowNum}", $m['membership_id']);
			$sheet->setCellValue("B{$rowNum}", $m['name_malay']);
			$sheet->setCellValue("C{$rowNum}", $m['name_chinese']);
			$sheet->setCellValue("D{$rowNum}", $m['name_dharma']);
			$sheet->setCellValueExplicit("E{$rowNum}", (string)$m['nric'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
			$sheet->setCellValueExplicit("F{$rowNum}", (string)$m['phone_mobile'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
			$rowNum++;
		}

		foreach (range('A', 'F') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		if (ob_get_level() > 0) {
			ob_end_clean();
		}

		$filename = 'Active_Members_' . date('Ymd') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

}

?>
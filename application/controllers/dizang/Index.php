<?php
/*
 * Created by :
 * Seeyi
 * Copyright © 2021 TBSN.my
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Index extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dizang_model');
        $this->load->helper(array('language','form','url','common_helper','file'));
        $this->load->library(array('session'));

        // Session Checking
        if(!$this->session->userdata('access_token_dz') || !$this->session->userdata('email')) redirect('dizang/login','refresh');

        // Drop Down Box For Chapter
        $this->data = load_common_view_data($this->session);

        $this->data['deceased_type'] = array(
            "亡" => "亡者",
            "祖" => "門堂上歷代祖先",
            "冤" => "冤親債主、纏身靈",
            "水" => "水子靈",
            "壽" => "（壽）",
            "土" => "土地公",
            "拿" => "拿督公",
        );
    }

    public function index() {

        $data = $this->data;
        $data['date_from'] = date('Y-m-d',strtotime("-2 week"));
        $data['date_to']   = date('Y-m-d');

        $this->load->view('dizang/header', $data);
        $this->load->view('dizang/navigation', $data);
        $this->load->view('dizang/pg_index');
        $this->load->view('dizang/footer');
        
    }

    public function ajax_get_list(){

        $this->load->library('Datatable',array('model' => 'datatable/dizang_dt', 'rowIdCol' => 'dizang_id'));
        $this->datatable->setPreResultCallback(
            function(&$json) {

                $rows =& $json['data'];
                $CI   =& get_instance();

                foreach($rows as &$r) {
                    /*$html = $CI->load->view('manage/plants_status_index_link_view', $r, TRUE);*/

                    $r['deceased_type'] = $this->data['deceased_type'][$r['deceased_type']];

                    $r['$']['viewmodal'] = '<input type="checkbox" name="print['.$r['dizang_id'].']"> | <a href="javascript:void(0)" onclick="load_box('.$r['dizang_id'].')" data-toggle="modal" data-target="#myModal">詳情</a>';
                }
            }
        );
        $json = $this->datatable->datatableJson();

        $this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Cache-Control: no-store, no-cache");
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function print(){

        if(!@$this->input->post('print')){
            echo "沒有選擇到任何一個，因此無法顯示！";
            return;
        }

        $ids = array();
        foreach($this->input->post('print') as $id => $val) $ids[] = $id;

        $list = $this->dizang_model->get_dizang_details($ids);
        $data = $this->data;
        $data['list'] = $list;

        switch($this->input->post('action')){
            case '儀軌':
                $this->load->view('dizang/print_yigui',$data);
                break;
            case '備錄':
                $this->load->view('dizang/print_beilu',$data);
                break;
            case '牌位':
                $this->load->view('dizang/print_paiwei',$data);
                break;
            case '地址':
                $this->load->view('dizang/print_address',$data);
                break;
        }
    }

    public function ajax_detail(){
       $res = $this->dizang_model->get_dizang_details(array($this->input->post('dizang_id')));
        echo json_encode($res[0]);

    }

    public function ajax_update(){

        $post = $this->input->post();

        // Insert or Update
        $process = ($post['dizang_id']) ? "update_details" : "add_details";

        // Remove empty field
        foreach($post as $k => $v) if($v == "null" || $v == "") unset($post[$k]);

        $this->dizang_model->$process($post);
        
        echo json_encode(array("success" => 1));
    }

}


/* End of file login.php */
/* Location: ./application/controllers/dizang/login.php */?>
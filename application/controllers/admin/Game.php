<?php
/*
 * Created by :
 * Seeyi
 * Copyright © 2021 TBSN.my
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Game extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('language','form','url','common_helper','file'));
        $this->load->library(array('session'));

        if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

        // Drop Down Box For Chapter
        $this->data = load_view_data($this->session);
        $this->url_name = $this->session->userdata('chapter_used');
    }

    public function index() {

        $data = $this->data;

        $this->load->view('admin/header', $data);
        $this->load->view('admin/navigation', $data);
        $this->load->view('admin/game_index_view',$data);
        $this->load->view('admin/footer');
        
    }

    public function ajax_get_list(){

        $this->load->library('Datatable',array('model' => 'datatable/game_dt', 'rowIdCol' => 'id'));
        $this->datatable->setPreResultCallback(
            function(&$json) {
                $rows =& $json['data'];
                $CI   =& get_instance();
            }
        );
        $json = $this->datatable->datatableJson();

        $this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Cache-Control: no-store, no-cache");
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

}


/* End of file game.php */
/* Location: ./application/controllers/admin/game.php */?>
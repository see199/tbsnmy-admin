<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chapter extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('api_model');
        $this->config->load('siteinfo', TRUE);

        $chapter_url = ($this->uri->segment(3) == "") ? $this->uri->segment(1) : $this->uri->segment(3);
        $chapter = $this->get_chapter_details($chapter_url);
        $default_bgimg = 'asset/img/bg_default.jpg';
        $chapter_bgimg = (isset($chapter['url_name'])) ? 'asset/img/bg_'.$chapter['url_name'].'.jpg' : $default_bgimg;
        $chapter['bgimgurl'] = (file_exists($chapter_bgimg)) ? $chapter_bgimg : $default_bgimg;
        $this->chapter = $chapter;
    }

    public function get_all(){
        echo $this->api_model->get_all_chapter_details();
    }

    public function get_active($code=''){
        if($code != '38f777481dd9a8c854acbf5e2394ef2b'){
            echo "Invalid access!";
            return;
        }

        echo $this->api_model->get_all_chapter_details_active();
    }

    public function get_active_by_state($state="Selangor"){
        echo $this->api_model->get_all_chapter_details_active_by_state($state);
    }

    public function index(){
        $data['chapter'] = $this->chapter;
        $data['chapter']['encoded_address'] = preg_replace('/\s/','+',$data['chapter']['address']);
        $data['chapter']['website'] = json_decode($data['chapter']['website'],1);

        $this->load->view('chapter/header_view',$data);
        $this->load->view('chapter/index_view',$data);
        $this->load->view('chapter/footer_view',$data);

    }

    private function get_chapter_details($url_name){
        
        $response = $this->api_model->get_chapter_details($url_name);
        $chapter = json_decode($response,1);

        if(isset($chapter['dharma_staff']))
        preg_replace('@,@',', ',$chapter['dharma_staff']);

        return $chapter;
    }
}

/* End of file chapter.php */
/* Location: ./application/controllers/chapter.php */
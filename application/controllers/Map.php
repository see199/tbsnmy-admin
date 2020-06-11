<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('api_model');
    }

    public function index(){
        $res = $this->api_model->get_all_chapter_coordinate();

        foreach($res as $v){
            list($latitude,$longitude) = explode(",",$v['coordinate']);
            $chapter[] = array($v['name_chinese'].'<br /><a href="https://www.google.com.my/maps/dir//'.$v['coordinate'].'/@'.$v['coordinate'].',13z/data=!3m1!4b1?hl=en">Direct</a>',$latitude,$longitude);
        }

        $data['data'] = json_encode($chapter);
        $this->load->view('chapter/all_map_view',$data);
    }
}

/* End of file chapter.php */
/* Location: ./application/controllers/chapter.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('api_model');
        $this->config->load('siteinfo', TRUE);
    }

    public function index(){

        $data = array();

        $questions = array(
            array('no' => 1,'img' => 'B1.jpg','json' => json_encode(array(
                'Q' => '請問 Q1',
                'A1' => 'A. for Q1',
                'A2' => 'B. for Q1 ',
                'A3' => 'C. for Q1 ',
                'AC' => 'A'
            ))),
            array('no' => 2,'img' => 'B2.jpg','json' => json_encode(array(
                'Q' => '請問 Q2',
                'A1' => 'A. for Q2',
                'A2' => 'B. for Q2 ',
                'A3' => 'C. for Q2 ',
                'AC' => 'A'
            ))),
            /*array('no' => 3,'img' => 'B3.jpg','json' => json_encode(array(
                'Q' => '請問 Q3',
                'A1' => 'A. for Q3',
                'A2' => 'B. for Q3 ',
                'A3' => 'C. for Q3 ',
                'AC' => 'A'
            ))),*/
            array('no' => 4,'img' => 'B4.jpg','json' => json_encode(array(
                'Q' => '請問 Q4',
                'A1' => 'A. for Q4',
                'A2' => 'B. for Q4 ',
                'A3' => 'C. for Q4 ',
                'AC' => 'A'
            ))),
            array('no' => 5,'img' => 'B5.jpg','json' => json_encode(array(
                'Q' => '請問 Q5',
                'A1' => 'A. for Q5',
                'A2' => 'B. for Q5 ',
                'A3' => 'C. for Q5 ',
                'AC' => 'A'
            ))),
            /*array('no' => 6,'img' => 'B6.jpg','json' => json_encode(array(
                'Q' => '請問 Q6',
                'A1' => 'A. for Q6',
                'A2' => 'B. for Q6 ',
                'A3' => 'C. for Q6 ',
                'AC' => 'A'
            ))),*/
            array('no' => 7,'img' => 'B7.jpg','json' => json_encode(array(
                'Q' => '請問 Q7',
                'A1' => 'A. for Q7',
                'A2' => 'B. for Q7 ',
                'A3' => 'C. for Q7 ',
                'AC' => 'A'
            ))),
            array('no' => 8,'img' => 'B8.jpg','json' => json_encode(array(
                'Q' => '請問 Q8',
                'A1' => 'A. for Q8',
                'A2' => 'B. for Q8 ',
                'A3' => 'C. for Q8 ',
                'AC' => 'A'
            ))),
            /*array('no' => 9,'img' => 'B9.jpg','json' => json_encode(array(
                'Q' => '請問 Q9',
                'A1' => 'A. for Q9',
                'A2' => 'B. for Q9 ',
                'A3' => 'C. for Q9 ',
                'AC' => 'A'
            ))),*/
        );

        $i = 0;
        $row = 2; // Display how many per rows
        foreach($questions as $k => $v){
            if($k%$row == 0) $i++;
            $data['game'][$i][] = $v;
        }

        $this->load->view('game/header_view',$data);
        $this->load->view('game/index_view',$data);
        $this->load->view('game/footer_view',$data);

    }

    public function submit(){
        $this->db = $this->load->database('local', TRUE);

        $game = $this->input->post();
        $this->db->insert('tbs_game',$game);
        echo $this->db->insert_id();
    }
}

/* End of file game.php */
/* Location: ./application/controllers/game.php */
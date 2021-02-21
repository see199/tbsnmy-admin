<?php
/*
 * Created by :
 * Seeyi | wong.seeyi@zalora.com.my
 * Copyright © 2015 Zalora IT Malaysia
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Controller {
    private $user;
    public function __construct() {
        parent:: __construct();

        $this->load->helper(array('form', 'common_helper', 'url','email','language'));
        $this->load->model('backend_model');
    }

    public function index() {

        require_once APPPATH . "libraries/GVendor/autoload.php";

        $client = new Google_Client();
        $client->setAuthConfig(APPPATH . '/config/google-webservice-credential.json');
        $client->setRedirectUri($this->config->item('redirect_uri'));
        $client->addScope("https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");


        // Send Client Request
        $objOAuthService = new Google_Service_Oauth2($client);

        
        // Add Access Token to Session
        if ($this->input->get('code')) {
            echo $this->config->item('redirect_uri');
            $client->authenticate($this->input->get('code'));
            $this->session->set_userdata('access_token', $client->getAccessToken());
            header('Location: ' . filter_var($this->config->item('redirect_uri'), FILTER_SANITIZE_URL));
        }

        // Set Access Token to make Request
        if ($this->session->userdata('access_token')) {
            $client->setAccessToken($this->session->userdata('access_token'));

        }else {
            $authUrl = $client->createAuthUrl();
        }
        
        

        // Get User Data from Google and store them in $data
        if ($client->getAccessToken()) {

            if ($client->isAccessTokenExpired()) {
                $this->logout();
            }

            $userData = $objOAuthService->userinfo->get();
            $data['userData'] = $userData;
            $this->session->set_userdata('access_token', $client->getAccessToken());

            // Checking for limited emails
            $res = $this->backend_model->check_login($userData->email);
            if(sizeof($res) == 0){
                echo "<script>alert('You are not allowed to login');
                window.location='".site_url()."admin/login/logout';
                </script>";
            }
            else{

                $this->backend_model->update_login($res[0]['user_id']);
                //$this->backend_model->log_activity($res[0]['user_id'],'login');

                // Set Chapter Default
                $chapter = json_decode($res[0]['chapter'],1);
                if($chapter[0] == 'all') $chapter_used = 'boyeh';
                else $chapter_used = $chapter[0];

                // Add to Session
                $this->session->set_userdata(array(
                    'name'  => $userData->given_name . ' ' . $userData->family_name,
                    'email' => $userData->email,
                    'chapter' => json_encode($chapter),
                    'chapter_used' => $chapter_used,
                    'avatar'  => $userData->picture,
                    'user_id' => $res[0]['user_id'],
                ));
                redirect('admin/index','refresh');
            }
        } else {
            $authUrl = $client->createAuthUrl();
            $data['authUrl'] = $authUrl;
            $this->load->view('admin/login_view', $data);
        }
    }

    // Unset session and logout
    public function logout() {
        //$this->backend_model->log_activity($this->session->userdata('user_id'),'logout');
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('avatar');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('chapter');
        $this->session->unset_userdata('chapter_used');
        $this->session->sess_destroy();
        redirect(base_url().'admin/login');
    }

}


/* End of file login.php */
/* Location: ./application/controllers/agent/login.php */?>
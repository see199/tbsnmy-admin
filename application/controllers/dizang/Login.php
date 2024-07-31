<?php
/*
 * Created by :
 * Seeyi
 * Copyright © 2021 TBSN.my
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Controller {
    private $user;
    public function __construct() {
        parent:: __construct();

        $this->load->helper(array('form', 'common_helper', 'url','email','language'));
        $this->load->model('dizang_model');
    }

    public function index() {

        require_once APPPATH . "libraries/GVendor/autoload.php";

        $client = new Google_Client();
        $client->setAuthConfig(APPPATH . '/config/google-webservice-credential.json');
        $client->setRedirectUri($this->config->item('redirect_uri_dizang'));
        $client->addScope("https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");


        // Send Client Request
        $objOAuthService = new Google_Service_Oauth2($client);

        
        // Add Access Token to Session
        if ($this->input->get('code')) {
            $client->authenticate($this->input->get('code'));
            $this->session->set_userdata('access_token_dz', $client->getAccessToken());
            header('Location: ' . filter_var($this->config->item('redirect_uri_dizang'), FILTER_SANITIZE_URL));
        }

        // Set Access Token to make Request
        if ($this->session->userdata('access_token_dz')) {
            $client->setAccessToken($this->session->userdata('access_token_dz'));

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
            $this->session->set_userdata('access_token_dz', $client->getAccessToken());

            // Checking for limited emails
            $res = $this->dizang_model->check_login($userData->email);
            if(sizeof($res) == 0){
                echo "<script>alert('You are not allowed to login');
                window.location='".site_url()."/dizang/login/logout';
                </script>";
            }
            else{

                $this->dizang_model->update_login($res[0]['user_id']);

                // Add to Session
                $this->session->set_userdata(array(
                    'name'  => $userData->given_name . ' ' . $userData->family_name,
                    'email' => $userData->email,
                    'avatar'  => $userData->picture,
                    'user_id' => $res[0]['user_id'],
                ));
                redirect('dizang/index','refresh');
            }
        } else {
            $authUrl = $client->createAuthUrl();
            $data['authUrl'] = $authUrl;
            $this->load->view('dizang/login_view', $data);
        }
    }

    // Unset session and logout
    public function logout() {
        $this->session->unset_userdata('access_token_dz');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('avatar');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('chapter');
        $this->session->unset_userdata('chapter_used');
        $this->session->sess_destroy();
        redirect(base_url().'dizang/login');
    }

}


/* End of file login.php */
/* Location: ./application/controllers/dizang/login.php */?>
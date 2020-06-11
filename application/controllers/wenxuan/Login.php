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
        $this->load->model('wenxuan_model');
    }

    public function index() {

        // Send Client Request
        $client = $this->create_google_client();
        $objOAuthService = new Google_Service_Oauth2($client);

        
        // Add Access Token to Session
        if ($this->input->get('code')) {
            $resp = $client->authenticate($this->input->get('code'));
            $this->session->set_userdata('access_token_wx', $client->getAccessToken());
            header('Location: ' . filter_var($this->config->item('redirect_uri_wenxuan'), FILTER_SANITIZE_URL));
        }

        // Set Access Token to make Request
        if ($this->session->userdata('access_token_wx')) {
            $client->setAccessToken($this->session->userdata('access_token_wx'));

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
            $this->session->set_userdata('access_token_wx', $client->getAccessToken());

            // Checking for limited emails
            $res = $this->wenxuan_model->check_login($userData->email);
            if(sizeof($res) == 0){
                echo "<script>alert('You are not allowed to login');
                window.location='".site_url()."wenxuan/login/logout';
                </script>";
            }
            else{

                $this->wenxuan_model->update_login($res[0]['user_id']);

                // Add to Session
                $this->session->set_userdata(array(
                    'name'  => $userData->given_name . ' ' . $userData->family_name,
                    'email' => $userData->email,
                    'avatar'  => $userData->picture,
                    'user_id' => $res[0]['user_id'],
                ));
                redirect('wenxuan/index','refresh');
            }
        } else {
            $authUrl = $client->createAuthUrl();
            $data['authUrl'] = $authUrl;
            $this->load->view('wenxuan/login_view', $data);
        }
    }

    private function create_google_client(){

        require_once APPPATH . "libraries/Google/autoload.php";
        include_once APPPATH . "libraries/Google/Client.php";
        include_once APPPATH . "libraries/Google/Service/Oauth2.php";

        // Create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId($this->config->item('client_id'));
        $client->setClientSecret($this->config->item('client_secret'));
        $client->setRedirectUri($this->config->item('redirect_uri_wenxuan'));
        $client->setDeveloperKey($this->config->item('simple_api_key'));
        $client->addScope("https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");

        return $client;
    }

    // Unset session and logout
    public function logout() {
        $this->session->unset_userdata('access_token_wx');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('avatar');
        $this->session->unset_userdata('name');
        $this->session->sess_destroy();
        redirect(base_url().'wenxuan/login');
    }

}


/* End of file login.php */
/* Location: ./application/controllers/agent/login.php */?>
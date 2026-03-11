<?php
class Gforms extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contact_model');
		$this->load->library('session');
		$this->load->helper(array('form', 'common_helper', 'url','email','language'));
	}

	public function index($form_id="") {

        require_once APPPATH . "libraries/GVendor/autoload.php";

        if($form_id != ""){
        	$this->session->set_userdata('form_id', $form_id);
        }

        $client = new Google_Client();
        $client->setAuthConfig(APPPATH . '/config/google-webservice-credential.json');
        $client->setRedirectUri($this->config->item('redirect_uri_gforms'));
        $client->addScope("https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");


        // Send Client Request
        $objOAuthService = new Google_Service_Oauth2($client);

        
        // Add Access Token to Session
        if ($this->input->get('code')) {
            $client->authenticate($this->input->get('code'));
            $this->session->set_userdata('access_token_gform', $client->getAccessToken());
            header('Location: ' . filter_var($this->config->item('redirect_uri_gforms'), FILTER_SANITIZE_URL));
        }

        // Set Access Token to make Request
        if ($this->session->userdata('access_token_gform')) {
            $client->setAccessToken($this->session->userdata('access_token_gform'));
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
            $this->session->set_userdata('access_token_gform', $client->getAccessToken());

            // Query Database for existing users
            $res = $this->contact_model->get_contact_by_email($userData->email);
            if(sizeof($res) == 0){
                //Redirect to un-prefilled GForm
                $this->forms($this->session->userdata('form_id'),false,array());
            }
            else{
            	//If found, prefilled GForm
            	$this->forms($this->session->userdata('form_id'),true,$res);
                
            }
        } else {

        	// Auto Login
            //$authUrl = $client->createAuthUrl();
            //$data['authUrl'] = $authUrl;
            //$this->load->view('gforms', $data);
            redirect($authUrl,'refresh');
        }
    }

    private function forms($form_id,$prefilled=false,$user_data=array()){

    	switch ($form_id) {
    		//「真佛道場卓越計畫」說明會
    		case '1FAIpQLScnF8LMmuvBkMwhtVf028by2FolOPcCJ23PYsRlQdL52CSioQ':
    			$url = "https://docs.google.com/forms/d/e/$form_id/viewform";
    			if($prefilled)
    				$url .= "?usp=pp_url&entry.317713988=".urlencode($user_data['chapter_name'])."&entry.1685206990=".urlencode($user_data['name_chinese'])."&entry.759580696=".urlencode($user_data['position'])."&entry.809719715=".urlencode($user_data['phone_mobile'])."";

    			//echo $url;
    			redirect($url);
    			break;
    		
    		default:
    			echo "Google Form Not Found!";
    			break;
    	}
    }



    // Unset session and logout
    public function logout() {
        $this->session->unset_userdata('access_token_gform');
        $this->session->sess_destroy();
        //redirect(base_url().'gforms');
    }

	
}

?>
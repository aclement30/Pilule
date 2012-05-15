<?php
class mFacebook extends CI_Model {

	public function __construct() {
		parent::__construct();

		$config = array(
						'appId'  => '102086416558659',
						'secret' => 'f30950e26499eb50e468887c79edfbfe',
						'fileUpload' => true, // Indicates if the CURL based @ syntax for file uploads is enabled.
						);
		
		$this->load->library('Facebook', $config);
		
		$user = $this->facebook->getUser();
 
        // We may or may not have this data based on whether the user is logged in.
        //
        // If we have a $user id here, it means we know the user is logged into
        // Facebook, but we don't know if the access token is valid. An access
        // token is invalid if the user logged out of Facebook.
        $profile = null;
        if($user)
        {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $profile = $this->facebook->api('/me?fields=id,name,link,email');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }
 
        $fb_data = array(
                        'me' => $profile,
                        'uid' => $user,
                        'loginUrl' => $this->facebook->getLoginUrl(),
                        'logoutUrl' => $this->facebook->getLogoutUrl(),
                    );
 
        $this->session->set_userdata('fb_data', $fb_data);
	}
	
	function getAuthUrl ($redirect_url) {
		return ($this->facebook->getLoginUrl(array(
												   'redirect_uri'	=>	site_url()."cfacebook/s_auth/u/".base64_encode($redirect_url)
											 )));
	}
	
	function isAuthenticated () {
		if ($this->facebook->getUser() == 0) {
			return (false);
		} else {
			return (true);
		}
	}
	
	function getUserData () {
		$user = $this->facebook->getUser();

		// We may or may not have this data based on whether the user is logged in.
		//
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.
		$profile = null;
		
		if ($user) {
			try {
			    // Proceed knowing you have a logged in user who's authenticated.
				$profile = $this->facebook->api('/me?fields=id,name');
				
				return ($profile);
			} catch (FacebookApiException $e) {
				error_log($e);
			    $user = null;
				
				return (false);
			}
		} else {
			return (false);
		}
	}
	
	function getFriends () {
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$friends = $this->facebook->api('/me/friends?limit=5000');
			
			return ($friends);
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
			
			return (false);
		}
	}
	
	function getFriendlists () {
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$friends = $this->facebook->api('/me/friendlists?limit=5000');
			
			return ($friends);
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
			
			return (false);
		}
	}
}
?>
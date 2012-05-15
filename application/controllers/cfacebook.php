<?php

class cFacebook extends CI_Controller {
	var $appId = '102086416558659';
	var $appSecret = 'f30950e26499eb50e468887c79edfbfe';
	
	function cFacebook() {
		parent::__construct();
		
		// Chargement des modèles
		$this->load->model('mFacebook');
		$this->load->model('mUser');
	}
	
	function auth () {
		$params = $this->uri->ruri_to_assoc(3);
		
		if ($this->mFacebook->isAuthenticated()) {
			// Redirection à l'URL demandé
			redirect (base64_decode($params['u']));
		} else {
			// Redirection à la page d'authentification Facebook
			redirect($this->mFacebook->getAuthUrl(base64_decode($params['u'])));
		}
	}
	
	function s_auth () {
		$error = $this->input->get('error');
		
		$fb_data = $this->session->userdata('fb_data');
		if ($error == '' and $fb_data['uid'] == 0 and isset($fb_data['loginUrl'])) {
			redirect(urldecode($fb_data['loginUrl']));
		}
		
		if ($error == '') {
			$params = $this->uri->ruri_to_assoc(3);
			redirect (base64_decode($params['u']));
		} else {
			$params = $this->uri->ruri_to_assoc(3);
			redirect (base64_decode($params['u'])."?fbauth=error");
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
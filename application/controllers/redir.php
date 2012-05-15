<?php
class Redir extends CI_Controller {
	var $mobile = 0;
	var $user;
	var $capsuleOffline = 0;
	var $encryption_key = "?!&#!@(?#&H#!@?&*#H!@&#*!@G?BGDAJBFSFD?!?%#%!&HG1yt2632512bFI?&12SF%b2fs5mhqs5f23sb!8-nh|IM";
	
	function Redir() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Auto-connexion, si spécifié dans l'URL
		$uri = str_replace(">>", "#!", $this->input->get('uri'));
		
		redirect($uri, 'location', 301);
	}
}
<?php

class Support extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;
	
	function Support() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
		
		// Chargement des modèles
		$this->load->model('mUser');
		
		// Chargement des librairies
		$this->load->library('lfetch');
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}

	function terms () {
        $data = array(
            'section'           =>  'support',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
        );

        // Chargement de l'entête
        if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);

        // Chargement de la page
        $this->load->view('support/terms', $data);

        // Chargement du bas de page
        if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
		
	function privacy () {
        $data = array(
            'section'           =>  'support',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
        );

        // Chargement de l'entête
        if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);

        // Chargement de la page
        $this->load->view('support/privacy', $data);

        // Chargement du bas de page
        if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function faq () {
        $data = array(
            'section'           =>  'support',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
        );

        // Chargement de l'entête
        if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);

        // Chargement de la page
        $this->load->view('support/faq', $data);

        // Chargement du bas de page
        if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function phishingEmail () {
		redirect('/welcome/', 'location', 301);
	}
	
	function contact () {
        $data = array(
            'section'           =>  'support',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
        );

        // Chargement de l'entête
        if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);

        // Chargement de la page
        $this->load->view('support/contact', $data);

        // Chargement du bas de page
        if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function w_reportBug () {
		$data = array();
		if (isset($_SESSION['cap_iduser'])) $data['user'] = $this->user;
		
		$this->load->view('support/w-report-bug', $data);
	}
	
	function s_reportBug () {
		$idul = strtolower($this->input->post('idul'));
		$email = strtolower($this->input->post('email'));
		$url = strtolower($this->input->post('url'));
		$type = $this->input->post('type');
		$description = strip_tags($this->input->post('description'));
		
		// Transfert de la copie-écran, s'il y a lieu
		$config['upload_path'] = './temp/';
		$config['allowed_types'] = 'gif|jpg|png|tiff|bmp';
		$config['max_size']	= '10240';
		$config['encrypt_name'] = true;
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload('printscreen')) {
			
		} else {
			$filedata = $this->upload->data();
			$filename = $filedata['file_name'];
		}
		
		$this->lfetch->host_name = 'alexandreclement.com';
		$this->lfetch->request_method="POST";
		$this->lfetch->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'idul'	=>	$idul,
			  'email'	=>	$email,
			  'url'		=>	$url,
			  'type'	=>	$type,
			  'description'	=>	$description,
			  'filename'	=>	$filename
			  );
		$arguments["RequestURI"] = "/pilulerest/sendbugreport/";
		
		$error=$this->lfetch->SendRequest($arguments);
		if ($error!="") {
			error_log(__FILE__." : ligne ".__LINE__." | ".$error);
			return ('server-connection');
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__FILE__." : ligne ".__LINE__." | ".$error);
			return ('server-connection');
		}
		
		$error = $this->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
														
		$this->lfetch->Close();

		// Envoi du message
		if (trim($response)=="OK") {
			?><script language="javascript">top.$.modal.close();top.resultMessage("Nous avons bien reçu le rapport du problème. Merci de participer à l'amélioration de Pilule !");</script><?php
		} else {
			?><script language="javascript">top.errorMessage("Une erreur inconnue est survenue durant la transmission du problème.");</script><?php
		}
	}
}
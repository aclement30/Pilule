<?php

class Dashboard extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Dashboard() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Vérification de la connexion
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') redirect('login');
		
		// Vérification que l'utilisateur soit administrateur
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser']!='alcle8') {
			?>document.location.hash='#!/dashboard/';<?php
 			die();
		}
		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) $this->user = $this->mUser->getUser($_SESSION['cap_iduser']);
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function index() {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
				
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/dashboard', $data, true)));
		
		echo "setPageInfo('admin/dashboard');setPageContent(\"".addslashes($content)."\");";
	}
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
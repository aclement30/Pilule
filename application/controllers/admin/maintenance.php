<?php

class Maintenance extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Maintenance() {
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
		
		if (file_exists('./temp-maintenance.htaccess')) {
			$data['maintenance'] = 1;
		} else {
			$data['maintenance'] = 0;
		}
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/maintenance/status', $data, true)));
		
		echo "setPageInfo('admin/maintenance');setPageContent(\"".addslashes($content)."\");";
	}
	
	function s_blockSite() {
		if (!file_exists('./temp-maintenance.htaccess')) {
			// Copie du fichier de maintenance
			$content = file_get_contents('maintenance.htaccess');
			
			// Remplacement des valeurs
			$content = str_replace("{ip}", str_replace(".", "\.", $_SERVER['REMOTE_ADDR']), $content);
			
			// Copie du fichier .htaccess actuel
			rename('./.htaccess', './temp-maintenance.htaccess');
			
			// Création du fichier .htaccess de maintenance
			file_put_contents('.htaccess', $content);
			
			?>statusBlockSite(1);<?php
			return (true);
		} else {
			?>statusBlockSite(1);<?php
			return (true);
		}
	}
	
	function s_unblockSite() {
		if (file_exists('./temp-maintenance.htaccess')) {
			// Suppression du fichier .htaccess de maintenance
			unlink('.htaccess');
			
			// Restauration du fichier .htaccess antérieur
			rename('./temp-maintenance.htaccess', './.htaccess');
			
			?>statusUnblockSite(1);<?php
			return (true);
		} else {
			?>statusUnblockSite(1);<?php
			return (true);
		}
	}
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
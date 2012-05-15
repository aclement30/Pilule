<?php

class Users extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Users() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		$this->load->library('lfetch');
		$this->load->library('lmobile');
		
		$this->load->helper('date');
		
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Vérification de la connexion
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') redirect('login');
		
		// Vérification que l'utilisateur soit administrateur
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser']!='alcle8') redirect('welcome');
 		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) $this->user = $this->mUser->getUser($_SESSION['cap_iduser']);
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function index() {
		$data = array();
		$data['section'] = 'admin';
		$data['page'] = 'list';
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
		
		if (!isset($_SESSION['cap_datacheck'])) redirect('welcome/s_logout');
		
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		$data['users'] = $this->mUser->getUsers();
		
		// Chargement de la page
		$this->load->view('admin/users/list', $data);
		
		// Chargement du menu
		$this->load->view('admin/users/m-menu', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function s_removeUser () {
		$idul = strtolower($this->input->post('idul'));
		
		$error = 0;
		
		if (($idul=='') && $error==0) {
			// Renvoi d'une réponse négative
			?>errorMessage('Identifiant IDUL vide...');<?php
			$error = 1;
		}
		
		$user = $this->mUser->getUser($idul);
		
		if ($user==array() and $error==0) {
			// Renvoi d'une réponse négative
			?>errorMessage('Impossible de trouver l\'utilisateur.');<?php
			$error = 1;
		}
	
		if ($error==0) {
			if ($this->mUser->removeUser($idul)) {
				?>statusRemove(1, '<?php echo $idul; ?>');<?php
			} else {
				?>statusRemove(2);<?php
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
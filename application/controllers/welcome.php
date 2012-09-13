<?php

class Welcome extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;
	
	function Welcome() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
		
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lfetch');
		$this->load->library('lcrypt');
		
		// Chargement des modèles
		$this->load->model('mCourses');
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Vérification de la connexion
		if ((!$this->mUser->isAuthenticated()) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login' and $this->uri->segment(2)!='s_authFB') {
			$_SESSION['login_redirect'] = $this->uri->uri_string();
			redirect('login');
		}

		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}

	function s_getAvailableModules () {
		$data['user'] = $this->user;
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		$modules = $this->mUser->getModules();
		if ($modules == array()) {
			// Chargement des modules par défaut
			$data['modules'] = $this->mUsers->getDashboardModules();
		} else {
			$data['modules'] = array();
			foreach ($modules as $module) {
				$module2 = $this->mUsers->getDashboardModule($module['module']);
				
				unset($module['order']);
				unset($module['idul']);
				
				$data['modules'][] = array_merge($module, $module2);
			}
		}
		
		$data['dashboard_modules'] = $this->mUsers->getDashboardModules(false);
		
		$content = str_replace("\n", "", str_replace("\r", "", $this->load->view('welcome/available-modules', $data, true)));
		
		?>$('#available-modules').html("<?php echo addslashes($content); ?>");$('#available-modules').slideDown();dashboardObj.unlockModules();stopLoading();$('#edit-dashboard-link').hide();<?php
	}
	
	function s_saveDashboard() {
		$modules = explode(',', substr(urldecode($this->input->post('modules')), 1));
		
		$this->mUser->updateModules($modules);
	}
	
	function s_addRegistrationModule() {
		$user = $this->user;
		if ($user['registration'] == 1) {
			$modules = $this->mUser->getModules();
			
			$modules_list = array();
			$found = 0;
			foreach ($modules as $module) {
				$modules_list[] = 'box-'.$module['module'];
				if ($module['module'] == 'registration') {
					$found = 1;
				}
			}
			
			if ($found == 0) $modules_list[] = 'box-registration';
			
			$this->mUser->updateModules($modules_list);
			
			ob_start();
			
			$module = $this->mUsers->getDashboardModule('registration');
			
			?><li id="box-<?php echo $module['id']; ?>" class="module" style="display: none;" onMouseOver="javascript:mouseOver('<?php echo $module['id']; ?>', 1);" onMouseOut="javascript:mouseOver('<?php echo $module['id']; ?>', 2);">
<a href="<?php echo $module['url']; ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link"><img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" /></a>
<div class="title"><a href='<?php if (substr($module['url'], 0, 4)!='http') echo site_url(); echo $module['url']; ?>'><?php echo $module['description']; ?></a></div></li><?php

			$content = str_replace("\n", "", str_replace("\r", "", ob_get_clean()));
			
			?>$('#modules').append('<?php echo addslashes($content); ?>');stopLoading();$('#box-registration').fadeIn();<?php
		}
	}
	
	
	function index() {
		$data = array(
            'section'       =>  'welcome',
            'user'          =>  $this->mUser->info(),
            'mobile_browser'=>  $this->mobile
        );

		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);

		if ($this->session->userdata('capsule_offline') == 'yes') {
			$data['capsule_offline'] = 1;
		}

		// Chargement de la page
		$this->load->view('empty', $data);

		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function dashboard () {
        $data = array(
            'section'           =>  'welcome',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false
        );

		$modules = $this->mUser->getModules();
		if ($modules == array()) {
			// Chargement des modules par défaut
			$data['modules'] = $this->mUsers->getDashboardModules();
		} else {
			// Chargement des modules de l'utilisateur
			$data['modules'] = array();
			foreach ($modules as $module) {
				
				$module2 = $this->mUsers->getDashboardModule($module['module']);
				
				unset($module['order']);
				unset($module['idul']);
				
				$data['modules'][] = array_merge($module, $module2);
			}
			
			$data['custom_dashboard'] = 1;
		}

		// Affichage de la page
        respond(array(
            'title'     =>  'Tableau de bord',
            'content'   =>  $this->load->view('welcome/dashboard', $data, true),
            'breadcrumb'=>  array(
                array(
                    'url'   =>  '#!/dashboard',
                    'title' =>  'Tableau de bord'
                )
            ),
            /*'buttons'       =>  array(
                array(
                    'action'=>  "app.dashboard.unlockModules();",
                    'type'  =>  'edit',
                    'title' =>  '<i class="icon-pencil"></i>',
                    'tip'   =>  'Modifier le tableau de bord'
                )
            )*/
        ));
	}

	function setAutologonCookie() {
		$this->mUser->setParam('autologon', 'yes');
		$this->load->view('welcome/set-autologon-cookie', array());
		
		$this->lcrypt->Key = $this->encryption_key;
		$encrypted_credentials = $this->lcrypt->encrypt(serialize(array('idul'=>$_SESSION['cap_iduser'], 'password'=>$_SESSION['cap_password'])));
		$this->mUser->setParam('ulaval-credentials', $encrypted_credentials);
	}
	
	function s_authFB () {
		$fb_data = $this->session->userdata('fb_data');
		if (isset($fb_data['uid']) and $fb_data['uid'] != '0' and $fb_data['uid'] != '') {
			$user = $this->mUsers->getUserByFbuid($fb_data['uid']);
			if (is_array($user)) {
				if (isset($_SESSION['cap_iduser'])) unset ($_SESSION['cap_iduser']);
				if (isset($_SESSION['cap_password'])) unset ($_SESSION['cap_password']);
				if (isset($_SESSION['cap_offline'])) unset ($_SESSION['cap_offline']);
				$_SESSION['cap_offline'] = 'no';
				
				// Décryptage des identifiants de connexion
				$this->lcrypt->Key = $this->encryption_key;
				$credentials = unserialize($this->lcrypt->decrypt($this->mUser->getParam('ulaval-credentials', $user['idul'])));

				$idul = $credentials['idul'];
				$password = $credentials['password'];
				
				$response = $this->lcapsule->login($idul, $password);
				if ($response == 'server-unavailable') {
					$response = $this->lcapsule->loginWebCT($idul, $password);
					$_SESSION['cap_offline'] = 'yes';
				}
				
				if ($response == 'server-connection') {
					// Seconde tentative de connexion
					$response = $this->lcapsule->login($idul, $password);
					
					if ($response == 'server-connection') {
						$response = $this->lcapsule->loginWebCT($idul, $password);
						$_SESSION['cap_offline'] = 'yes';
					}
				}
				if ($response=='success') {
					// Enregistrement de l'IDUL/mot de passe de l'utilisateur dans la session
					$_SESSION['cap_iduser'] = $credentials['idul'];
					$_SESSION['cap_password'] = $credentials['password'];
		
					// Enregistrement de la dernière visite
					$this->mUser->registerLogin();
					
					$this->mHistory->save('login');
					
					?><script language="javascript">top.successLogin();</script><?php
				} else {
					switch ($response) {
						case 'credentials':
							// Ajout d'une erreur
							$this->mErrors->addError('login', 'credentials', $idul);
							
							// Renvoi d'une réponse négative
							?><script language="javascript">top.errorLogin('Erreur de connexion ! Les identifiants de connexion sont erronés.');</script><?php
						break;
						case 'server-connection':
							// Ajout d'une erreur
							$this->mErrors->addError('login', 'server-connection', $idul);
							
							// Renvoi d'une réponse négative
							?><script language="javascript">top.errorLogin('Erreur de connexion ! Impossible de contacter le serveur de Capsule.');</script><?php
						break;
					}
				}
			} else {
				?><script language="javascript">top.errorLogin('Erreur de connexion ! Ce compte Facebook n\'est associé à aucun IDUL.');</script><?php
			}
		} else {
			?><script language="javascript">top.errorLogin('Erreur de connexion ! Problème d\'authentification via Facebook.');</script><?php
		}
	}

	// Changement de l'affichage (mobile/web)
	function s_changeDisplay() {
		$mode = $this->uri->segment(3);

        $this->session->set_userdata('display_mode', $mode);

		// Redirection à la page d'accueil
		redirect( site_url() . '/#!/dashboard' );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
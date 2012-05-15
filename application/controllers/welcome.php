<?php

class Welcome extends CI_Controller {
	var $mobile = 0;
	var $user;
	var $encryption_key = "?!&#!@(?#&H#!@?&*#H!@&#*!@G?BGDAJBFSFD?!?%#%!&HG1yt2632512bFI?&12SF%b2fs5mhqs5f23sb!8-nh|IM";
	
	function Welcome() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		$_SESSION['usebots'] = 0;
		
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lfetch');
		$this->load->library('lcrypt');
		
		// Chargement des modèles
		$this->load->model('mCourses');
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Vérification de la connexion
		if ((!isset($_SESSION['cap_iduser'])) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login' and $this->uri->segment(2)!='s_authFB' and $this->uri->segment(2)!='s_acceptterms') {
			$_SESSION['login_redirect'] = $this->uri->uri_string();
			redirect('login');
		}
				
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) {
			$this->user = $this->mUser->info();
			$this->user['password'] = $_SESSION['cap_password'];
		}
	}
	
	function setadmincookie() {
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser']=='alcle8') {
			setcookie('pilule-super-admin-iCNxbUaON58e', 'alcle8', time()+3600*24*365);
			echo 'SET COOKIE : YES';
		} else {
			echo 'SET COOKIE : ERROR!';
		}
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
		$data = array();
		$data['section'] = 'welcome';
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
		
		//error_log(print_r($_SESSION, true));
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees,summary');
		
		if ($cache!=array()) {
			$data['fees'] = unserialize($cache['value']);
		}
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		// Vérification du cookie pour l'affichage de la notice WebCT
		if (isset($_COOKIE['pilule-webct-tooltip']) and $_COOKIE['pilule-webct-tooltip']!='2') {
			$data['display_tooltip'] = 1;
			setcookie('pilule-webct-tooltip', '2', time()+3600*24*365);
		} elseif (!isset($_COOKIE['pilule-webct-tooltip'])) {
			$data['display_tooltip'] = 1;
			setcookie('pilule-webct-tooltip', '1', time()+3600*24*365);
		} else {
			$data['display_tooltip'] = 0;
		}
		
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
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$data['cap_datacheck'] = $_SESSION['cap_datacheck'];
		
		// Chargement de la page
		if ($this->mobile!=1) $this->load->view('welcome/dashboard', $data); else $this->load->view('welcome/m-dashboard', $data);
		
		$this->load->view('content-footer', $data);

		// Chargement du menu
		if ($this->mobile!=1) $this->load->view('welcome/m-menu', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function dashboard () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
		
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees,summary');
		
		if ($cache!=array()) {
			$data['fees'] = unserialize($cache['value']);
		}
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		// Vérification du cookie pour l'affichage de la notice WebCT
		if (isset($_COOKIE['pilule-webct-tooltip']) and $_COOKIE['pilule-webct-tooltip']!='2') {
			$data['display_tooltip'] = 1;
			setcookie('pilule-webct-tooltip', '2', time()+3600*24*365);
		} elseif (!isset($_COOKIE['pilule-webct-tooltip'])) {
			$data['display_tooltip'] = 1;
			setcookie('pilule-webct-tooltip', '1', time()+3600*24*365);
		} else {
			$data['display_tooltip'] = 0;
		}
		
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
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$data['cap_datacheck'] = $_SESSION['cap_datacheck'];
		
		// Chargement de la page
		if ($this->mobile!=1) $content = str_replace("\r", '', str_replace("\n", '', $this->load->view('welcome/dashboard', $data, true))); else $content = str_replace("\r", '', str_replace("\n", '', $this->load->view('welcome/m-dashboard', $data, true)));
		
		echo "setPageInfo('welcome/dashboard');setPageContent(\"".addslashes($content)."\");";
	}
	
	function getMenu() {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees,summary');
		
		if ($cache!=array()) {
			$data['fees'] = unserialize($cache['value']);
		}
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$data['cap_datacheck'] = $_SESSION['cap_datacheck'];
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('welcome/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
	}
	
	function setAutologonCookie() {
		$this->mUser->setParam('autologon', 'yes');
		$this->load->view('welcome/set-autologon-cookie', array());
		
		$this->lcrypt->Key = $this->encryption_key;
		$encrypted_credentials = $this->lcrypt->encrypt(serialize(array('idul'=>$_SESSION['cap_iduser'], 'password'=>$_SESSION['cap_password'])));
		$this->mUser->setParam('ulaval-credentials', $encrypted_credentials);
	}
	
	function login () {
		$data = array();
		$data['section'] = 'login';
		$data['mobile_browser'] = $this->mobile;
		if (isset($_SESSION['login-error'])) {
			$data['login_error'] = $_SESSION['login-error'];
			unset($_SESSION['login-error']);
		}
		
		if (!isset($_COOKIE['pilule-demo-tooltip-2'])) {
			$data['display_tooltip'] = 1;
			setcookie('pilule-demo-tooltip-2', '1', time()+3600*24*365);
		} else {
			$data['display_tooltip'] = 0;
			//setcookie('pilule-demo-tooltip-2', '1', time()-3600*24*365);
		}
		
		if (isset($_SESSION['login_redirect']) and $_SESSION['login_redirect'] != 'welcome') {
			$data['redirect_url'] = $_SESSION['login_redirect'];
			unset($_SESSION['login_redirect']);
		} elseif (isset($_SESSION['login_redirect']) and $_SESSION['login_redirect'] == 'welcome') {
			unset($_SESSION['login_redirect']);
		}
		
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		// Chargement de la page
		if ($this->mobile!=1) $this->load->view('welcome/login', $data); else $this->load->view('welcome/m-login', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
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
	
	function s_login() {
		$idul = strtolower($this->input->post('idul'));
		$password = $this->input->post('password');
		
		/*
		$loadData = $this->input->post('loaddata');
		
		if ($loadData == 'no' && $idul == 'alcle8') {
			$_SESSION['load-data'] = false;
		} else {
			$_SESSION['load-data'] = true;
		}
		
		if ($this->capsuleOffline == 1) $_SESSION['load-data'] = false;
		*/
		
		if (isset($_SESSION['cap_iduser'])) unset ($_SESSION['cap_iduser']);
		if (isset($_SESSION['cap_password'])) unset ($_SESSION['cap_password']);
		if (isset($_SESSION['cap_offline'])) unset ($_SESSION['cap_offline']);
		$_SESSION['cap_offline'] = 'no';
		
		if (($idul == 'demo' and $password == 'demo')) {
			$response = 'success';
		} else {
			$response = $this->lcapsule->login($idul, $password);
		}
		
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
			$_SESSION['cap_iduser'] = $idul;
			$_SESSION['cap_password'] = $password;

			// Enregistrement de la dernière visite
			$this->mUser->registerLogin();
			
			$this->mHistory->save('login');
			
			if ($this->mobile == 1) {
				$dataCheck = 1;
				
				// Vérification des données enregistrées
				if ($this->mUser->getStudies() != array()) {
					$cache = $this->mCache->getCache('data|studies,details');
					
					if ($cache == array()) {
						$dataCheck = 0;
					}
				}
				
				$cache = $this->mCache->getCache('data|studies,report');
				
				if ($cache == array()) {
					$dataCheck = 0;
				}
				
				$cache = $this->mCache->getCache('data|schedule,semesters');
				
				if ($cache == array()) {
					$dataCheck = 0;
				}
				
				$cache = $this->mCache->getCache('data|fees,semesters');
				
				if ($cache == array()) {
					$dataCheck = 0;
				}
				
				if ($dataCheck == 0) {
					// Renvoi d'une erreur
					?>errorLogin('Vous devez d\'abord vous connecter sur le site normal une première fois avant de pouvoir accéder au site mobile.');<?php
					
					return (true);
				}
			}
			
			// Renvoi d'une réponse positive
			?>successLogin();<?php
		} else {
			switch ($response) {
				case 'credentials':
					// Ajout d'une erreur
					$this->mErrors->addError('login', 'credentials', $idul);
					
					// Renvoi d'une réponse négative
					?>errorLogin('Erreur de connexion ! Veuillez vérifier les identifiants de connexion...');<?php
				break;
				case 'server-connection':
					// Ajout d'une erreur
					$this->mErrors->addError('login', 'server-connection', $idul);
					
					// Renvoi d'une réponse négative
					?>errorLogin('Erreur de connexion ! Impossible de contacter le serveur de Capsule...');<?php
				break;
			}
		}
	}
	
	function s_checkSecurity () {
		$password = $this->input->post('password');
		
		if (isset($_SESSION['temp_iduser']) and $_SESSION['temp_iduser']=='alcle8') {
			$security_password = $this->mUser->getParam('security-password', $_SESSION['temp_iduser']);
			
			if (md5($password)==$security_password) {
				// Transfert des infos de l'utilisateur dans les variables de session
				$_SESSION['cap_iduser'] = $_SESSION['temp_iduser'];
				$_SESSION['cap_password'] = $_SESSION['temp_password'];
				unset($_SESSION['temp_iduser']);
				unset($_SESSION['temp_password']);
				
				// Enregistrement de la dernière visite
				$this->mUser->registerLogin();
				
				$this->mHistory->save('login');
				$this->mHistory->save('security-check');
				
				// Renvoi d'une réponse positive
				?>successLogin(1);<?php
			} else {
				// Suppression des variables temporaires
				unset($_SESSION['temp_iduser']);
				unset($_SESSION['temp_password']);
					
				// Enregistrement de l'erreur
				$this->mErrors->addError('login', 'security-check : fail');
				
				if (isset($_SESSION['cap_user'])) unset($_SESSION['cap_user']);
				
				// Renvoi d'une erreur
				?>errorLogin('Échec de la vérification de sécurité.');setTimeout("document.location='<?php echo site_url(); ?>login/'", 1500);<?php
			}
		} else {
			// Renvoi d'une erreur inconnue
			?>errorLogin('Une erreur inconnue est survenue durant la vérification de sécurité...');setTimeout("document.location='<?php echo site_url(); ?>login/'", 1000);<?php
		}
	}
	
	// Changement de l'affichage (mobile/web)
	function s_changeDisplay() {
		$mode = $this->uri->segment(3);
		
		$_SESSION['display_mode'] = $mode;
		
		// Redirection à la page d'accueil
		redirect('welcome/');
	}
	
	// Recherche des données de l'utilisateur sur Capsule
	function s_getUserData () {
		ob_start();
		
		// Augmentation de la limitation de mémoire
		ini_set('memory_limit', '200M');
		
		$datalist = array(
						  'data|studies,summary',
						  'data|studies,details',
						  'data|studies,report',
						  'data|schedule,semesters',
						  'data|fees,summary',
						  'data|holds'
						  );
		
		$current_semester = '201201';
				
		// Vérification que l'utilisateur soit connecté
		if (!isset($_SESSION['cap_iduser'])) {
			$this->mErrors->addError('loading-data', 'iduser not set');
			
			?>errorLogin('Erreur lors du chargement des données (ERR<?php echo (__LINE__); ?>) !');<?php
			die();
		}
		
		if ($_SESSION['cap_iduser'] == 'demo') {
			//sleep(10);
			$_SESSION['cap_datacheck'] = 1;
			?>resultLoading(1);<?php
			die();
		}
		
		// Vérification de l'existence des données en cache
		$cache = $this->mCache->getCache('data|studies,summary');
		
		if ($cache!=array()) {
			// Passage à l'étape suivante
			$_SESSION['cap_datacheck'] = 1;
			
			?>resultLoading(1);<?php
			die();
		} elseif (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$_SESSION['cap_datacheck'] = 2;
			?>resultLoading(1);<?php
			die();
		}
		
		$error = 0;
		$dataErrors = array();
		$noStudentInfo = 0;
		
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser'] != 'demo') $this->mCache->deleteUserCache($_SESSION['cap_iduser']);
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser'] != 'demo') $this->mUser->deleteCourses();
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser'] != 'demo') $this->mUser->deleteStudies();
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser'] != 'demo') $this->mUser->deleteClasses();
		
		foreach ($datalist as $item) {
			switch ($item) {
				case 'data|studies,summary':
					// Vérification de l'existence des données
					$studies = $this->mUser->getStudies();
					
					if ($studies==array()) {
						// Chargement du programme d'études
						$studies = $this->lcapsule->getStudies($current_semester);
						
						if ($studies == 'no-info') {
							$noStudentInfo = 0;
						}
						
						if (is_array($studies)) {
							if ($studies['program']=='Programme pré-Banner') {
								// Erreur : programme inexistant (ex : employés Ulaval)
								?>errorLogin('Votre programme d\'études ne peut pas être analysé par Pilule (err: programme pré-Banner).');<?php
								return (false);
							}
							
							if ($studies['program']=='Études libres premier cycle') {
								// Erreur : programme inexistant (ex : employés Ulaval)
								?>errorLogin('Votre programme d\'études ne peut pas être analysé par Pilule (err: études libres).');<?php
								return (false);
							}
							
							// Enregistrement des données
							if (!$this->mUser->setStudies($studies)) {
								// Enregistrement de l'erreur
								$this->mErrors->addError('loading-data', 'studies-summary : setUserStudies');
								
								$error = 1;
								$dataErrors[] = $item;
								
								// Mise en cache des données
								$this->mCache->addCache('data|studies,summary', $studies['rawdata'], '1');
							} else {
								// Mise en cache des données
								$this->mCache->addCache('data|studies,summary', $studies['data']);
							}
						} elseif ($noStudentInfo == 0) {
							$this->mErrors->addError('loading-data', 'studies-summary : parsing error');
							
							$error = 1;
							$dataErrors[] = $item;
						}
					} elseif ($studies['program']=='Programme pré-Banner') {
						// Erreur : programme inexistant (ex : employés Ulaval)
						?>errorLogin('Votre programme d\'études ne peut pas être analysé par Pilule (err: programme pré-Banner).');<?php
						return (false);
					} elseif ($studies['program']=='Études libres premier cycle') {
						// Erreur : programme inexistant (ex : employés Ulaval)
						?>errorLogin('Votre programme d\'études ne peut pas être analysé par Pilule (err: études libres).');<?php
						return (false);
					}
				break;
				case 'data|studies,details':
					// Vérification de l'existence des données
					$studies = $this->mUser->getStudies();
					
					// Chargement du rapport de cheminement
					if ($studies!=array() and $studies['code_permanent']!='') {
						$fetchDetails1 = false;
						$response = $this->lcapsule->getStudiesDetails($current_semester, false);
					} else {
						$fetchDetails1 = true;
						$response = $this->lcapsule->getStudiesDetails($current_semester);
					}
					
					if (is_array($response)) {
						if ($fetchDetails1) {
							// Enregistrement des données
							if (!$this->mUser->setStudies($response['studies'])) {
								// Enregistrement de l'erreur
								$this->mErrors->addError('loading-data', 'studies-details : setUserStudies');
								
								$error = 1;
								$dataErrors[] = $item;
								
								// Mise en cache des données
								$this->mCache->addCache('data|studies,details,1', $response['data']['details1'], '1');
							} else {
								// Mise en cache des données
								$this->mCache->addCache('data|studies,details,1', $response['data']['details1']);
							}
						}
						
						// Mise en cache des données
						$this->mCache->addCache('data|studies,details,2', $response['data']['details2']);
						$this->mCache->addCache('data|studies,details,3', $response['data']['details3']);
						$this->mCache->addCache('data|studies,details', $response['details']);
					} else {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'studies-details : parsing error');
						
						$error = 1;
						$dataErrors[] = $item;
					}
				break;
				case 'data|studies,report':
					// Chargement du relevé de notes
					$response = $this->lcapsule->getReport();
					
					if (is_array($response)) {
						// Enregistrement des données
						if (!$this->mUser->setStudies($response['studies'])) {
							// Enregistrement de l'erreur
							$this->mErrors->addError('loading-data', 'studies-report : setUserStudies');
							
							$error = 1;
							$dataErrors[] = $item;
							
							// Mise en cache des données
							$this->mCache->addCache('data|studies,report,rawdata', $response['rawdata'], '1');
						} else {
							// Mise en cache des données
							$this->mCache->addCache('data|studies,report', $response['report']);
						}
					} else {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'studies-report : parsing error');
						
						$error = 1;
						$dataErrors[] = $item;
					}
				break;
				case 'data|schedule,semesters':
					// Chargement des horaires de cours
					$result = $this->lcapsule->getSchedule();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'schedule-semesters : parsing error');
						
						$error = 1;
						$dataErrors[] = $item;
					}
				break;
				case 'data|fees,summary':
					// Chargement des détails des frais de scolarité
					$result = $this->lcapsule->getFeesSummary();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'fees : parsing error');
						
						$error = 1;
						$dataErrors[] = $item;
					}
				break;
				case 'data|holds':
					// Vérification des blocages
					$this->lcapsule->checkHolds();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'check-holds : parsing error');
						
						$error = 1;
						$dataErrors[] = $item;
					}
				break;
			}
			
			// Test de la connexion à Capsule
			$this->lcapsule->testConnection();
		}
		
		$error = 0;
		
		if ($dataErrors!=array()) {
			// Test de la connexion à Capsule
			$this->lcapsule->testConnection();
		}
		
		// Second essai pour les problèmes de chargement
		foreach ($dataErrors as $item) {
			switch ($item) {
				case 'data|studies,summary':
					// Vérification de l'existence des données
					$studies = $this->mUser->getStudies();
					
					if ($studies==array()) {
						// Chargement du programme d'études
						$studies = $this->lcapsule->getStudies($current_semester);
						
						if ($studies['program']=='Programme pré-Banner') {
							// Erreur : programme inexistant (ex : employés Ulaval)
							?>errorLogin('Votre programme d\'études ne peut pas être analysé par Pilule (err: programme pré-Banner).');<?php
							return (false);
						}
						
						if (is_array($studies)) {
							// Enregistrement des données
							if (!$this->mUser->setStudies($studies)) {
								// Enregistrement de l'erreur
								$this->mErrors->addError('loading-data', 'studies-summary (2) : setUserStudies');
								
								$error = 1;
								
								// Mise en cache des données
								$this->mCache->addCache('data|studies,summary', $studies['rawdata'], '1');
							} else {
								// Mise en cache des données
								$this->mCache->addCache('data|studies,summary', $studies['data']);
							}
						} else {
							$this->mErrors->addError('loading-data', 'studies-summary (2) : parsing error');
							
							$error = 1;
						}
					} elseif ($studies['program']=='Programme pré-Banner') {
						// Erreur : programme inexistant (ex : employés Ulaval)
						?>errorLogin('Votre programme d\'études ne peut pas être analysé par Pilule (err: programme pré-Banner).');<?php
						return (false);
					}
				break;
				case 'data|studies,details':
					// Vérification de l'existence des données
					$studies = $this->mUser->getStudies();
					
					// Chargement du rapport de cheminement
					if ($studies!=array() and $studies['code_permanent']!='') {
						$fetchDetails1 = false;
						$response = $this->lcapsule->getStudiesDetails($current_semester, false);
					} else {
						$fetchDetails1 = true;
						$response = $this->lcapsule->getStudiesDetails($current_semester);
					}
					
					if (is_array($response)) {
						if ($fetchDetails1) {
							// Enregistrement des données
							if (!$this->mUser->setStudies($response['studies'])) {
								// Enregistrement de l'erreur
								$this->mErrors->addError('loading-data', 'studies-details (2) : setUserStudies');
								
								$error = 1;
								
								// Mise en cache des données
								$this->mCache->addCache('data|studies,details,1', $response['data']['details1'], '1');
							} else {
								// Mise en cache des données
								$this->mCache->addCache('data|studies,details,1', $response['data']['details1']);
							}
						}
						
						// Mise en cache des données
						$this->mCache->addCache('data|studies,details,2', $response['data']['details2']);
						$this->mCache->addCache('data|studies,details,3', $response['data']['details3']);
						$this->mCache->addCache('data|studies,details', $response['details']);
					} else {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'studies-details (2) : parsing error');
						
						$error = 1;
					}
				break;
				case 'data|studies,report':
					// Chargement du relevé de notes
					$response = $this->lcapsule->getReport();
					
					if (is_array($response)) {
						// Enregistrement des données
						if (!$this->mUser->setStudies($response['studies'])) {
							// Enregistrement de l'erreur
							$this->mErrors->addError('loading-data', 'studies-report (2) : setUserStudies');
							
							$error = 1;
							
							// Mise en cache des données
							$this->mCache->addCache('data|studies,report,rawdata', $response['rawdata'], '1');
						} else {
							// Mise en cache des données
							$this->mCache->addCache('data|studies,report', $response['report']);
						}
					} else {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'studies-report (2) : parsing error');
						
						$error = 1;
					}
				break;
				case 'data|schedule,semesters':
					// Chargement des horaires de cours
					$result = $this->lcapsule->getSchedule();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'schedule-semesters (2) : parsing error');
						
						$error = 1;
					}
				break;
				case 'data|fees,summary':
					// Chargement des détails des frais de scolarité
					$result = $this->lcapsule->getFeesSummary();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'fees (2) : parsing error');
						
						$error = 1;
					}
				break;
				case 'data|holds':
					// Vérification des blocages
					$this->lcapsule->checkHolds();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('loading-data', 'check-holds (2) : parsing error');
						
						$error = 1;
					}
				break;
			}
			
			// Test de la connexion à Capsule
			$this->lcapsule->testConnection();
		}
		
		// Enregistrement du programme dans la fiche de l'utilisateur
		$studies = $this->mUser->getStudies();
		$this->mUser->editUser(array(
									 'idul'		=>	$_SESSION['cap_iduser'],
									 'program'	=>	$studies['program'],
									 'faculty'	=>	$studies['faculty']
									 ));
		
		ob_clean();

		$this->mHistory->save('loading-data');
		
		if ($error==0) {
			// Renvoi d'un résultat positif
			$_SESSION['cap_datacheck'] = 1;
			?>resultLoading(1);<?php
		} else {
			// Renvoi d'une erreur
			if ($_SESSION['cap_iduser']=='alcle8') {
				?>askLoadData();requestTimeout();<?php
			} else {
				?>requestTimeout();<?php
			}
		}
	}
	
	// Vérification des données disponibles
	function s_checkData () {
		$studies = $this->mUser->getStudies();
		
		if ($studies == array()) {
			?>requestTimeout();<?php
			return (false);
		}
		
		$dataList = array(
						  'data|studies,details',
						  'data|studies,report',
						  'data|schedule',
						  'data|fees'
						  );
		
		$errors = 0;
		
		foreach ($dataList as $item) {
			switch ($item) {
				case 'data|studies,summary':
					$cache = $this->mCache->getCache('data|studies,details');
					
					if ($cache == array()) $errors++;
				break;
				case 'data|studies,report':
					$cache = $this->mCache->getCache('data|studies,report');
					
					if ($cache == array()) $errors++;
				break;
				case 'data|studies,report':
					$cache = $this->mCache->getCache('data|studies,report');
					
					if ($cache == array()) $errors++;
				break;
				case 'data|schedule':
					$cache = $this->mCache->getCache('data|schedule,semesters');
		
					if ($cache == array()) $errors++;
				break;
				case 'data|fees':
					$cache = $this->mCache->getCache('data|fees,semesters');
		
					if ($cache == array()) $errors++;
				break;
			}
		}
		
		if ($errors>5) {
			?>requestTimeout();<?php
			return (false);
		} else {
			$_SESSION['loading-errors'] = 1;
			$_SESSION['cap_datacheck'] = 1;
			?>resultLoading(1);<?php
			return (true);
		}
	}
	
	function s_logout () {
		$this->mHistory->save('logout');
		
		// Suppression des variables de session
		unset($_SESSION['cap_iduser']);
		unset($_SESSION['cap_password']);
		if (isset($_SESSION['cap_user'])) unset($_SESSION['cap_user']);
		if (isset($_SESSION['loading-errors'])) unset($_SESSION['loading-errors']);
		unset($_SESSION['cap_datacheck']);
		unset($_SESSION['idbot']);
		unset($_SESSION['bot']);
		//unset($_SESSION['usebots']);
		
		// Redirection à la page d'accueil
		redirect('welcome/');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
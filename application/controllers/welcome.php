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
            'buttons'       =>  array(
                array(
                    'action'=>  "app.dashboard.unlockModules();",
                    'type'  =>  'edit',
                    'title' =>  '<i class="icon-pencil"></i>',
                    'tip'   =>  'Modifier le tableau de bord'
                )
            )
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
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
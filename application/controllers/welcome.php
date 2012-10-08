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
        $this->load->model('mModules');
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Vérification de la connexion
		if ((!$this->mUser->isAuthenticated()) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login' and $this->uri->segment(2)!='s_authFB') {
			$_SESSION['login_redirect'] = $this->uri->uri_string();
			redirect('login');
		}

		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
		//$this->mobile = 1;
	}

	function dashboard_edit () {
        $data = array(
            'user'              =>  $this->mUser->info(),
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false
        );

        // Recherche des modules de l'utilisateur
        $user_modules = $this->mModules->getUserModules();

        // Extraction de la liste des ID des modules déjà activés
        $data[ 'user_modules' ] = array();
        foreach ( $user_modules as $module ) {
            $data[ 'user_modules' ][] = $module['id'];
        }

        $data[ 'modules' ] = $this->mModules->get();

        respond(array(
            'title'     =>  'Tableau de bord',
            'content'      =>  $this->load->view('welcome/dashboard-edit', $data, true),
            'breadcrumb'=>  array(
                array(
                    'url'   =>  '#!/dashboard',
                    'title' =>  'Tableau de bord'
                )
            ),
            'buttons'       =>  array(
                array(
                    'action'=>  "app.dashboard.save();",
                    'type'  =>  'save',
                    'tip'   =>  'Enregistrer le tableau de bord'
                )
            )
        ));
	}

    function ajax_toggleModule () {
        $params = $this->input->post();

        if ( $params[ 'isEnabled' ] == 1 ) {
            // Ajout du module du tableau de bord
            if ( $this->mModules->addUserModule( $params[ 'id' ] ) ) {
                respond( array(
                    'status'    =>  true,
                    'id'        =>  $params[ 'id' ]
                ));
            } else {
                respond( array(
                    'status'    =>  false,
                    'id'        =>  $params[ 'id' ]
                ));
            }
        } else {
            // Suppression du module du tableau de bord
            if ( $this->mModules->removeUserModule( $params[ 'id' ] ) ) {
                respond( array(
                    'status'    =>  true,
                    'id'        =>  $params[ 'id' ]
                ));
            } else {
                respond( array(
                    'status'    =>  false,
                    'id'        =>  $params[ 'id' ]
                ));
            }
        }
    }

	function index() {
		$data = array(
            'section'           =>  'welcome',
            'user'              =>  $this->mUser->info(),
            'mobile_browser'    =>  $this->mobile,
            'programs'          =>  $this->mStudies->getPrograms(),
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? 'true': 'false',
        );

		// Chargement de l'entête
		$this->load->view('header', $data);

		// Chargement de la page
		$this->load->view('empty', $data);

		// Chargement du bas de page
		$this->load->view('footer', $data);
	}
	
	function dashboard () {
        $data = array(
            'section'           =>  'welcome',
            'user'              =>  $this->mUser->info(),
            'mobile_browser'    =>  $this->mobile,
            'programs'          =>  $this->mStudies->getPrograms(),
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false
        );

        // Recherche des modules de l'utilisateur
        $data[ 'modules' ] = $this->mModules->getUserModules();
		if ( empty( $data[ 'modules' ] ) ) {
			// Chargement des modules par défaut
			$data[ 'modules' ] = $this->mModules->get( array( 'default' => true ) );
		}

		// Affichage de la page
        respond( array(
            'title'     =>  'Tableau de bord',
            'content'   =>  $this->load->view( 'welcome/dashboard', $data, true ),
            'breadcrumb'=>  array(
                array(
                    'url'   =>  '#!/dashboard',
                    'title' =>  'Tableau de bord'
                )
            ),
            'buttons'       =>  array(
                array(
                    'action'=>  "app.dashboard.edit();",
                    'type'  =>  'edit',
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
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
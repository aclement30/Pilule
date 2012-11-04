<?php

class Settings extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;

	function Settings() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
				
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lfetch');
		$this->load->library('lcrypt');
		
		// Chargement des modèles
        $this->load->model('mCourses');
        $this->load->model('mSchedule');
        $this->load->model('mTuitions');
        $this->load->model('mUser');
        $this->load->model('mUsers');
		$this->load->model('mFacebook');

        // Vérification de la connexion
        if ((!$this->mUser->isAuthenticated())) {
            $_SESSION['login_redirect'] = $this->uri->uri_string();
            redirect('login');
        }
				
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function index() {
        $data = array(
            'section'           =>  'fees',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
            'data_storage'      =>  $this->mUser->getParam('data-storage'),
            'autologon'         =>  $this->mUser->getParam('autologon')
        );
        //TODO : Réparer la connexion auto
        $data['autologon'] = 'no';
		if ($data['autologon'] == 'yes') {
			$data['fbuid'] = $this->mUser->getParam('fbuid');
			if ($data['fbuid']) {
				$data['fbname'] = $this->mUser->getParam('fbname');
			}
		}
		
		if ($this->input->get('fbauth') != '') {
			if ($this->input->get('fbauth') == 'error') {
				$data['error_message'] = "L'authentification par Facebook a échouée : annulation par l'utilisateur.";
			} else {
				$data['result_message'] = "Le compte Facebook a été autorisé pour la connexion automatique.";
			}
		}
		
		$data['param']['expiration-delay'] = $this->mUser->getParam('data-expiration-delay');

        $code = '';
		if ($this->session->userdata('fbauth') != '') {
			if ($this->input->get('fbauth') == 'error') {
				$code = <<<EOT
                    errorMessage("L'authentification par Facebook a échouée : annulation par l'utilisateur.");
EOT;
			} else {
                $code = <<<EOT
                    resultMessage("Le compte Facebook a été autorisé pour la connexion automatique.");
EOT;
			}
			$this->session->unset_userdata('fbauth');
		}

        respond(array(
            'title'         =>  'Préférences',
            'content'       =>  $this->load->view('settings/dashboard', $data, true),
            'breadcrumb'=>  array(
                array(
                    'url'   =>  '#!/dashboard',
                    'title' =>  'Tableau de bord'
                ),
                array(
                    'url'   =>  '#!/settings',
                    'title' =>  'Préférences'
                )
            )
        ));
	}

	function s_unlinkAccount () {
		$params = $this->uri->uri_to_assoc(3);
		switch ($params['account']) {
			case 'facebook':
				$this->facebook->destroySession();
				
				$this->mUser->deleteParam('fbuid');
				$this->mUser->deleteParam('fbname');
				$this->mUser->editUser(array('fbuid'=>''));
				
				ob_start();
				
				?><div style="float: left; margin-top: 10px; margin-right: 20px;">Aucun compte autorisé</div>
<div style="float: right;"><a href="javascript:document.location='<?php echo site_url()."cfacebook/auth/u/".base64_encode(site_url()."settings/s_linkaccount/account/facebook"); ?>';" class='icon-button add-icon' style="margin-top: 0px;"><span class='et-icon'><span>Ajouter</span></span></a><div style="clear: both;"></div></div><div style="clear: both;"></div><?php

				$content = str_replace("\n", "", str_replace("\r", "", ob_get_clean()));
				
				?>resultMessage("L'autorisation du compte Facebook a été annulée.");$('#fb-account').html("<?php echo addslashes($content); ?>");<?php
			break;
		}
		
	}
	
	function s_linkAccount () {
		$params = $this->uri->uri_to_assoc(3);
		switch ($params['account']) {
			case 'facebook':
				if ($this->input->get('fbauth') != '') {
					if ($this->input->get('fbauth') == 'error') {
						$_SESSION['fbauth'] = 'error';
						redirect(site_url()."#!/settings/");
					}
				} else {
					$user_fbdata = $this->session->userdata('fb_data');
					
					if ($user_fbdata['uid'] == 0 and isset($user_fbdata['loginUrl'])) {
						redirect(urldecode($user_fbdata['loginUrl']));
					}
					
					$this->mUser->setParam('fbuid', $user_fbdata['uid']);
					$this->mUser->setParam('fbname', $user_fbdata['me']['name']);
					$this->mUser->editUser(array('fbuid'=>$user_fbdata['uid']));
					$_SESSION['fbauth'] = 'success';
					
					redirect(site_url()."#!/settings/");
				}
			break;
		}
		
	}
	
	function ajax_configure () {
		$param = $this->input->post('param');
		
		switch ($param) {
			case 'data-storage':
				$value = $this->input->post('data-storage');
				
				if ($value != 'yes' and $this->mUser->getParam('autologon')) {
					if ($this->mUser->getParam('autologon') == 'yes') {
						// Affichage d'une erreur
						?><script language="javascript">top.app.settings.configureCallback('data-storage', 2, 'La connexion automatique nécessite le stockage des données.');</script><?php
						
						return (true);
					}
				}
				
				if ($value == 'yes') {
					if ($this->mUser->setParam('data-storage', $value)) {
						$this->mCache->lockUserCache();
						$_SESSION['data-storage'] = 'yes';
						?><script language="javascript">top.app.settings.configureCallback('data-storage', 1);</script><?php
					} else {
						?><script language="javascript">top.app.settings.configureCallback('data-storage', 2, 'Impossible d\'enregistrer le paramètre !');</script><?php
					}
				} else {
					if ($this->mUser->setParam('data-storage', 'no')) {
						$this->mCache->unlockUserCache();
						$_SESSION['data-storage'] = 'no';
						?><script language="javascript">top.app.settings.configureCallback('data-storage', 1);</script><?php
					} else {
						?><script language="javascript">top.app.settings.configureCallback('data-storage', 2, 'Impossible d\'enregistrer le paramètre !');</script><?php
					}
				}
			break;
			case 'autologon':
				$value = $this->input->post('autologon');

				if ($value == 'yes') {
					if ($this->mUser->setParam('autologon', $value)) {
						// Stockage de l'IDUL et du NIP
						$this->lcrypt->Key = $this->encryption_key;
						$encrypted_credentials = $this->lcrypt->encrypt(serialize(array('idul'=>$_SESSION['cap_iduser'], 'password'=>$_SESSION['cap_password'])));
						$this->mUser->setParam('ulaval-credentials', $encrypted_credentials);
						
						?><script language="javascript">top.app.settings.configureCallback('autologon', 1);</script><?php
					} else {
						?><script language="javascript">top.app.settings.configureCallback('autologon', 2, 'Impossible d\'enregistrer le paramètre !');</script><?php
					}
				} else {
					if ($this->mUser->setParam('autologon', 'no')) {
						$this->mUser->deleteParam('ulaval-credentials');
						
						?><script language="javascript">top.app.settings.configureCallback('autologon', 1);</script><?php
					} else {
						?><script language="javascript">top.app.settings.configureCallback('autologon', 2, 'Impossible d\'enregistrer le paramètre !');</script><?php
					}
				}
			break;
			case 'expiration-delay':
				$value = $this->input->post('delay');
				
				if ($this->mUser->setParam('data-expiration-delay', $value)) {
					?><script language="javascript">top.app.settings.configureCallback('expiration-delay', 1);</script><?php
				} else {
					?><script language="javascript">top.app.settings.configureCallback('expiration-delay', 2, 'Impossible d\'enregistrer le paramètre !');</script><?php
				}
			break;
		}
	}
	
	function eraseData() {
		// Effacement des données enregistrées en cache
		if ($this->session->userdata('pilule_user') != '' and $this->session->userdata('pilule_user') != 'demo') {
            $this->mStudies->deletePrograms();
            $this->mStudies->deleteProgramSections();
            $this->mStudies->deleteReportSemesters();
            $this->mStudies->deleteReportAdmittedSections();
            $this->mStudies->deleteReports();
            $this->mSchedule->deleteSemesters();
            $this->mTuitions->deleteAccount();
            $this->mTuitions->deleteSemesters();
            $this->mUser->deleteModules();
            $this->mCache->deleteRequests();
            $this->mUsers->removeUser($this->session->userdata('pilule_user'));
        }

		$this->mHistory->save('erase-data');
		
		// Suppression des variables de session
        $this->session->unset_userdata('pilule_user');
        $this->session->unset_userdata('pilule_password');

		// Redirection à la page d'accueil
		return (true);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
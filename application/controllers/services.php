<?php
class Services extends CI_Controller {
	var $mobile = 0;
	var $user;
	var $capsuleOffline = 0;
	var $usebots = 0;
	var $encryption_key = "?!&#!@(?#&H#!@?&*#H!@&#*!@G?BGDAJBFSFD?!?%#%!&HG1yt2632512bFI?&12SF%b2fs5mhqs5f23sb!8-nh|IM";
	
	function Services() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lcrypt');
		$this->load->library('lfetch');
		
		$_SESSION['usebots'] = 0;
		
		// Auto-connexion, si spécifié dans l'URL
		$params = $this->uri->ruri_to_assoc(3);

		if (isset($params['autologon']) and $params['autologon'] == '1') {
			$idul = base64_decode($params['u']);
			$password = base64_decode($params['p']);
			
			if (($idul == 'demo' and $password == 'demo')) {
				$response = 'success';
			} elseif ($this->capsuleOffline != 1) {
				$response = $this->lcapsule->login($idul, $password);
			} elseif ($this->capsuleOffline == 1) {
				$response = $this->lcapsule->loginWebCT($idul, $password);
			}
			
			if ($response=='success') {
				// Enregistrement de l'IDUL/mot de passe de l'utilisateur dans la session
				$_SESSION['cap_iduser'] = $idul;
				$_SESSION['cap_password'] = $password;
			}
		}
		
		// Vérification de la connexion
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') {
			$_SESSION['login_redirect'] = $this->uri->uri_string();
			redirect('login');
		}
		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) {
			$this->user = $this->mUser->info();
			$this->user['password'] = $_SESSION['cap_password'];
		}
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function bus () {
		$this->mHistory->save('redirect-bus');
		
		redirect('http://www.rtcquebec.ca/Tarifs/Programmesdabonnement/LabonneBUSUniversit%C3%A9Laval/tabid/259/Default.aspx');
	}
	
	function wifi () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-wifi');
		
		$this->load->view('services/wifi', $data);
	}
	
	function webct () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-webct');
		
		$this->load->view('services/webct', $data);
	}
	
	function portailcours () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-portailcours');
		
		$this->load->view('services/portailcours', $data);
	}
	
	function pixel () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-pixel');
		
		$this->load->view('services/pixel', $data);
	}
	
	function elluminate () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-elluminate');
		
		$this->load->view('services/elluminate', $data);
	}
	
	function exchange () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-exchange');
		
		$this->load->view('services/exchange', $data);
	}
	
	function capsule () {
		$data['user'] = $this->user;
		
		$this->mHistory->save('redirect-capsule');
		
		$this->load->view('services/capsule', $data);
	}
	
	function fse_intranet () {
		$data['user'] = $this->user;
		$autologon = $this->mUser->getParam('fse-intranet-autologon');
		$data['autologon'] = $autologon;
		$data['credentials'] = array();
		
		if ($autologon == 'yes') {
			$credentials = $this->mUser->getParam('fse-intranet-credentials');
			if ($credentials) {
				$this->lcrypt->Key = $this->encryption_key;
				$data['credentials'] = unserialize($this->lcrypt->decrypt($credentials));
			} else {
				$data['autologon'] = 'no';
			}
			
			$this->mHistory->save('redirect-fse-intranet');
			
			$this->load->view('services/fse-intranet', $data);
		} else {
			$this->mHistory->save('redirect-fse-intranet');
			
			$this->load->view('services/fse-intranet', $data);
		}
	}
	
	function alfresco () {
		$data['user'] = $this->user;
		$autologon = $this->mUser->getParam('alfresco-autologon');
		$data['autologon'] = $autologon;
		$data['credentials'] = array();
		
		if ($autologon == 'yes') {
			$credentials = $this->mUser->getParam('alfresco-credentials');
			if ($credentials) {
				$this->lcrypt->Key = $this->encryption_key;
				$data['credentials'] = unserialize($this->lcrypt->decrypt($credentials));
			} else {
				$data['autologon'] = 'no';
			}
			
			$this->mHistory->save('redirect-alfresco');
			
			$this->load->view('services/alfresco', $data);
		} else {
			$this->mHistory->save('redirect-alfresco');
			
			$this->load->view('services/alfresco', $data);
		}
	}
	
	function med_intranet () {
		$data['user'] = $this->user;
		$autologon = $this->mUser->getParam('med-intranet-autologon');
		$data['autologon'] = $autologon;
		$data['credentials'] = array();
		
		if ($autologon == 'yes') {
			$credentials = $this->mUser->getParam('med-intranet-credentials');
			if ($credentials) {
				$this->lcrypt->Key = $this->encryption_key;
				$data['credentials'] = unserialize($this->lcrypt->decrypt($credentials));
			} else {
				$data['autologon'] = 'no';
			}
			
			$this->mHistory->save('redirect-med-intranet');
			
			$this->load->view('services/med-intranet', $data);
		} else {
			$this->mHistory->save('redirect-med-intranet');
			
			$this->load->view('services/med-intranet', $data);
		}
	}
	
	function s_connect () {
		$service = $this->uri->segment(3);
		
		$autologon = $this->mUser->getParam($service.'-autologon');
		if (!$autologon) {
			?>dashboardObj.askCredentials('<?php echo $service; ?>');<?php
		} elseif ($autologon == 'yes') {
			$credentials = $this->mUser->getParam($service.'-credentials');
			if (!$credentials) {
				?>dashboardObj.askCredentials('<?php echo $service; ?>');<?php
			} else {
				?>window.frames['report-frame'].location='<?php echo site_url(); ?>services/<?php echo str_replace("-", "_", $service); ?>';<?php
			}
		} else {
			?>window.frames['report-frame'].location='<?php echo site_url(); ?>services/<?php echo str_replace("-", "_", $service); ?>';<?php
		}
	}
	
	function askCredentials () {
		$data['service'] = $this->uri->segment(3);
		
		switch ($data['service']) {
			case 'alfresco':
				$data['title'] = 'Connexion à Alfresco';
				$data['username'] = 'Nom d\'utilisateur';
				$data['password'] = 'Mot de passe';
				$data['service_name'] = 'Alfresco';
			break;
			case 'med-intranet':
				$data['title'] = 'Connexion à Intranet - Faculté de Médecine';
				$data['username'] = 'Nom d\'usager';
				$data['username_value'] = $this->user['idul'];
				$data['password'] = 'Mot de passe';
				$data['service_name'] = 'Intranet MED';
				$data['service_url'] = 'med_intranet';
			break;
			case 'fse-intranet':
				$data['title'] = 'Connexion à Intranet - Faculté d\'éducation';
				$data['username'] = 'Identifiant';
				$data['username_value'] = $this->user['idul'];
				$data['password'] = 'Mot de passe';
				$data['service_name'] = 'Intranet FSE';
				$data['service_url'] = 'fse_intranet';
			break;
		}
		
		// Chargement de la page d'aide
		$this->load->view('services/w-ask-credentials', $data);
	}
	
	function s_tryLogin() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$service = $this->input->post('service');
		
		switch ($service) {
			case 'alfresco':
				$url="/share/page/site-index";
				$this->lfetch->host_port = 8080;
				$this->lfetch->host_name = "ged01.bibl.ulaval.ca";
				
				$arguments['HostName'] = "ged01.bibl.ulaval.ca";
				$arguments["RequestURI"] = $url;
				
				$error=$this->lfetch->Open($arguments);
				if ($error!="") {
					if ($error=='0 could not connect to the host "'.$arguments['HostName'].'"') {
						sleep(1);
						
						$error=$this->lfetch->Open($arguments);
						if ($error!="") {
							if ($error=='0 could not connect to the host "'.$arguments['HostName'].'"') {
								error_log(__FILE__." : ligne ".__LINE__." | ".$error);
								return ('server-connection');
							}
						}
					}
				}
				
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
							
				$this->lfetch->Close();
							
				$this->lfetch->request_method="POST";
				$this->lfetch->Open($arguments);
				
				// Envoi du formulaire
				$arguments["PostValues"] = array(
					  'username'	=>	$username,
					  'password'	=>	$password,
					  'success'		=>	'/share/page/site-index',
					  'failure'		=>	'/share/page/type/login?error=true'
					  );
				$arguments["RequestURI"] = "/share/page/dologin";
				
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
				
				$error = 0;
				foreach ($headers as $key => $line) {
					if ($key == 'location') {
						if (trim($line) == 'http://ged01.bibl.ulaval.ca/share/page/type/login?error=true') {
							$error = 1;
							break;
						}
					}
				}
				
				if ($error == 1) {
					?>dashboardObj.loginServiceCallback(2);<?php
				} else {
					$this->mUser->setParam('alfresco-autologon', 'yes');
					
					//$this->lcrypt->Mode = $this->lCrypt::MODE_HEX;
					$this->lcrypt->Key = $this->encryption_key;
					$encrypted_credentials = $this->lcrypt->encrypt(serialize(array('username'=>$username,'password'=>$password)));
					
					$this->mUser->setParam('alfresco-credentials', $encrypted_credentials);
					?>dashboardObj.loginServiceCallback(1);<?php
				}
			break;
			case 'fse-intranet':
				$url="/index.php";
				$this->lfetch->protocol = "https";
				$this->lfetch->host_name = "www.intranet.fse.ulaval.ca";

				$arguments['HostName'] = "www.intranet.fse.ulaval.ca";
				$arguments["RequestURI"] = $url;
				
				$error=$this->lfetch->Open($arguments);
				if ($error!="") {
					if ($error=='0 could not connect to the host "'.$arguments['HostName'].'"') {
						sleep(1);
						
						$error=$this->lfetch->Open($arguments);
						if ($error!="") {
							if ($error=='0 could not connect to the host "'.$arguments['HostName'].'"') {
								error_log(__FILE__." : ligne ".__LINE__." | ".$error);
								return ('server-connection');
							}
						}
					}
				}
				
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
							
				$this->lfetch->Close();
							
				$this->lfetch->request_method="POST";
				$this->lfetch->Open($arguments);

				// Envoi du formulaire
				$arguments["PostValues"] = array(
					  'idul'		=>	$username,
					  'user_pass'	=>	$password,
					  'pageencours'	=>	"/index.php",
					  'pageencours_get'	=>	""
					  );
				$arguments["RequestURI"] = "/check_login.php";
				
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
				
				$error = 0;
				if (strpos($response, "Votre nom d'utilisateur ou votre mot de passe n'est pas valide.")>1) {
					$error = 1;
				}
				
				if ($error == 1) {
					?>dashboardObj.loginServiceCallback(2);<?php
				} else {
					$this->mUser->setParam('med-intranet-autologon', 'yes');
					
					//$this->lcrypt->Mode = $this->lCrypt::MODE_HEX;
					$this->lcrypt->Key = $this->encryption_key;
					$encrypted_credentials = $this->lcrypt->encrypt(serialize(array('username'=>$username,'password'=>$password)));
					
					$this->mUser->setParam('med-intranet-credentials', $encrypted_credentials);
					?>dashboardObj.loginServiceCallback(1);<?php
				}
			break;
			case 'med-intranet':
				$url="/intranet/usagers/identification.asp";
				$this->lfetch->protocol = "https";
				$this->lfetch->host_name = "intranet.fmed.ulaval.ca";

				$arguments['HostName'] = "intranet.fmed.ulaval.ca";
				$arguments["RequestURI"] = $url;
				
				$error=$this->lfetch->Open($arguments);
				if ($error!="") {
					if ($error=='0 could not connect to the host "'.$arguments['HostName'].'"') {
						sleep(1);
						
						$error=$this->lfetch->Open($arguments);
						if ($error!="") {
							if ($error=='0 could not connect to the host "'.$arguments['HostName'].'"') {
								error_log(__FILE__." : ligne ".__LINE__." | ".$error);
								return ('server-connection');
							}
						}
					}
				}
				
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
							
				$this->lfetch->Close();
							
				$this->lfetch->request_method="POST";
				$this->lfetch->Open($arguments);
				
				// Envoi du formulaire
				$arguments["PostValues"] = array(
					  'NomUsager'	=>	$username,
					  'MotDePasse'	=>	$password
					  );
				$arguments["RequestURI"] = "/intranet/usagers/identification.asp";
				
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
				
				$error = 0;
				if (strpos($response, "tres d'identification sont incorrects,")>1) {
					$error = 1;
				}
				
				if ($error == 1) {
					?>dashboardObj.loginServiceCallback(2);<?php
				} else {
					$this->mUser->setParam('med-intranet-autologon', 'yes');
					
					//$this->lcrypt->Mode = $this->lCrypt::MODE_HEX;
					$this->lcrypt->Key = $this->encryption_key;
					$encrypted_credentials = $this->lcrypt->encrypt(serialize(array('username'=>$username,'password'=>$password)));
					
					$this->mUser->setParam('med-intranet-credentials', $encrypted_credentials);
					?>dashboardObj.loginServiceCallback(1);<?php
				}
			break;
		}
	}
	
	function s_skipAutoLogon () {
		$params = $this->uri->uri_to_assoc(3);
		
		if ($params['autologin'] == 0) {
			$this->mUser->setParam($params['service'].'-autologon', 'no');
		} else {
			$this->mUser->setParam($params['service'].'-autologon', 'yes');
		}
		
		?>window.document.location='<?php echo site_url(); ?>services/<?php echo str_replace("-", "_", $params['service']); ?>';<?php
	}
}
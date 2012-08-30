<?php

class lCapsule {
	public $CI;
	private $debug = 0;
	
	function __construct() {
		$this->CI =& get_instance();
	}
	
	// Connexion à Capsule
	public function login ($idul, $password) {
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->debug = $this->debug;
			
			$_SESSION['referer'] = '';
			
			$url="/pls/etprod7/twbkwbis.P_WWWLogin";
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = $url;
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				if ($error=='0 could not connect to the host "capsuleweb.ulaval.ca"') {
					sleep(1);
					
					$error=$this->CI->lfetch->Open($arguments);
					if ($error!="") {
						if ($error=='0 could not connect to the host "capsuleweb.ulaval.ca"') {
							error_log(__FILE__." : ligne ".__LINE__." | ".$error);
							return ('server-connection');
						}
					}
				}
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				$this->CI->lfetch->Close();
				return ('server-connection');
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				$this->CI->lfetch->Close();
				return ('server-connection');
			}
						
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
			
			// Vérification de la disponibilité du formulaire de connexion
			if (strpos($response, '<INPUT TYPE="text" NAME="sid" SIZE="10" MAXLENGTH="8" ID="UserID" >')<1) {
				$this->CI->lfetch->Close();
				return('server-unavailable');
			}
			
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->request_method="POST";
			$this->CI->lfetch->Open($arguments);
			
			// Envoi du formulaire
			$arguments["PostValues"] = array(
				  'sid'	=>	$idul,
				  'PIN'	=>	$password
				  );
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_ValLogin";
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				$this->CI->lfetch->Close();
				return ('server-connection');
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				$this->CI->lfetch->Close();
				return ('server-connection');
			}
			
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
															
			$this->CI->lfetch->Close();
			
			if (strpos($response, "IDUL ou le NIP sont invalides.")>1) {
				return ('credentials');
			} elseif (strpos($body, "<meta http-equiv=\"refresh\" content=\"0;url=/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_MainMnu&amp;msg=WELCOME")>1) {
				$this->CI->lfetch->SaveCookies($cookies);
				
				$_SESSION['cookies'] = $cookies;
				
				// Vérification de l'existence de l'utilisateur
				if ($this->CI->mUsers->userExists($idul)===false) {
					$name = substr($body, strpos($body, "<meta http-equiv=\"refresh\" content=\"0;url=/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_MainMnu&amp;msg=WELCOME"));
					$name = substr($name, strpos($name, "WELCOME+")+8);
					$name = utf8_encode(urldecode(substr($name, 0, strpos($name, "+bienvenue")-1)));
					
					// Enregistrement de l'utilisateur
					$user = array(
								  'idul'	=>	$idul,
								  'name'	=>	$name,
								  'terms'	=>	'1'
								  );
					
					$this->CI->mUsers->addUser($user);
				}
				
				return ('success');
			} else {
				//error_log($response);
				return ('server-connection');
			}
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('robotLogin');
		
			// Création de la requête
			$request = array($idul, $password);
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$response['data'] = unserialize(base64_decode($response['data']));
					
					$_SESSION['cap_user'] = $response['data'];
					
					return (true);
				} else {
					return (false);
				}
			}
		}
	}
	
	// Vérification de l'authentification par WebCT
	public function loginWebCT ($idul, $password) {
		$this->CI->lfetch->debug = $this->debug;
		
		$_SESSION['referer'] = '';
		
		$url="/webct/ticket/ticketLogin?action=print_login&request_uri=/webct/homearea/homearea%3F";
		$this->CI->lfetch->protocol="https";
		
		$arguments['HostName'] = "www.webct.ulaval.ca";
		$arguments["RequestURI"] = $url;
		
		$error=$this->CI->lfetch->Open($arguments);
		if ($error!="") {
			if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
				sleep(1);
				
				$error=$this->CI->lfetch->Open($arguments);
				if ($error!="") {
					if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
						error_log(__FILE__." : ligne ".__LINE__." | ".$error);
						return ('server-connection');
					}
				}
			}
		}
		
		$error=$this->CI->lfetch->SendRequest($arguments);
		if ($error!="") {
			error_log(__FILE__." : ligne ".__LINE__." | ".$error);
			return ('server-connection');
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__FILE__." : ligne ".__LINE__." | ".$error);
			return ('server-connection');
		}
																				
		$this->CI->lfetch->Close();
					
		$this->CI->lfetch->request_method="POST";
		$this->CI->lfetch->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'WebCT_ID'		=>	$idul,
			  'Password'		=>	$password,
			  'request_uri'		=>	'/webct/homearea/homearea?',
			  'action'			=>	'webform_user'
			  );
		$arguments["RequestURI"] = "/webct/ticket/ticketLogin";
		
		$error=$this->CI->lfetch->SendRequest($arguments);
		if ($error!="") {
			error_log(__FILE__." : ligne ".__LINE__." | ".$error);
			return ('server-connection');
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__FILE__." : ligne ".__LINE__." | ".$error);
			return ('server-connection');
		}
		
		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
														
		$this->CI->lfetch->Close();
				
		if (strpos($response, "Erreur: Les informations entrées sont incorrectes.")>1) {
			return ('credentials');
		} elseif (strpos($body, "successful login")>1) {
			$url="/webct/homearea/homearea?";
			$this->CI->lfetch->request_method="GET";
			
			$arguments["RequestURI"] = $url;
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
					sleep(1);
					
					$error=$this->CI->lfetch->Open($arguments);
					if ($error!="") {
						if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
							error_log(__FILE__." : ligne ".__LINE__." | ".$error);
							return ('server-connection');
						}
					}
				}
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return ('server-connection');
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return ('server-connection');
			}
			
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
		
			$this->CI->lfetch->Close();
			$this->CI->lfetch->SaveCookies($cookies);
			
			$_SESSION['cookies'] = $cookies;
			
			// Vérification de l'existence de l'utilisateur
			if ($this->CI->mUsers->userExists($idul)===false) {
				$name = substr($body, strpos($body, "<b>Bienvenue,"), 500);
				$name = substr($name, strpos($name, ",")+1);
				$name = trim((urldecode(substr($name, 0, strpos($name, "</b>")))));
				
				// Enregistrement de l'utilisateur
				$user = array(
							  'idul'	=>	$idul,
							  'name'	=>	$name,
							  'terms'	=>	'1'
							  );
				
				$this->CI->mUsers->addUser($user);
			}
			
			return ('success');
		} else {
			//error_log($response);
			return ('server-connection');
		}
	}
	
	// Test de la connexion
	public function testConnection () {		
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
			
			if ($_SESSION['referer']=='') {
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			} else {
				$this->CI->lfetch->referer = $_SESSION['referer'];
			}
					
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			// Vérification de la réponse
			$found = 0;
			for (Reset($headers),$header=0;$header<count($headers);Next($headers),$header++)
			{
				$header_name=Key($headers);
				if ($header_name=='set-cookie' and preg_match("#SESSID\=;#", $headers[$header_name])) {
					$found = 1;
				}
			}					
			
			$this->CI->lfetch->Close();
			
			if ($found==1 || $error!='') {
				// Reconnexion au serveur
				$this->login($_SESSION['cap_iduser'], $_SESSION['cap_password']);
			}
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('robotTestConnection');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])));
			$this->CI->xmlrpc->request($request);

			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				error_log(__LINE__." | ".print_r($_SESSION['cap_user'], true));
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				error_log(__LINE__." | ".print_r($_SESSION['cap_user'], true));
				return (true);
			}
		}
	}
	
	// Vérification des blocages
	public function checkHolds () {
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
			
			if ($_SESSION['referer']=='') {
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			} else {
				$this->CI->lfetch->referer = $_SESSION['referer'];
			}
			
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu";
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$this->CI->lfetch->Close();
					
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu';
			$arguments["RequestURI"] = "/pls/etprod7/bwskoacc.P_ViewHold";
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			// Extraction du code source du résultat											
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$data = utf8_encode(html_entity_decode($body));
			
			$this->CI->lfetch->Close();
			
			if (!$this->checkPage($data)) return (false);
				
			if (strpos($data, "Type de blocage")>1) {
				$content = substr($data, strpos($data, "Processus touché"));
				$content = substr($content, strpos($content, "<TR>"));
				$content = substr($content, 0, strpos($content, "</TABLE>"));
				$content = explode("<TR>", $content);
				
				$number = 0;
				$holds = array();
				
				foreach ($content as $line) {
					$hold = array();
					$title = "";
					if ($number!=0) {
						$line2 = explode("</TD>", $line);
						
						$hold['type'] = trim(strip_tags($line2[0]));
						$hold['date_start'] = trim(str_replace("/", "", strip_tags($line2[1])));
						$hold['date_end'] = trim(str_replace("/", "", strip_tags($line2[2])));
						if ($hold['date_end']=='20991231') $hold['date_end'] = '';
						$hold['amount'] = str_replace("&nbsp;", "", trim(strip_tags($line2[3])));
						$hold['reason'] = str_replace("&nbsp;", "", trim(strip_tags($line2[4])));
						$hold['resp'] = str_replace("&nbsp;", "", trim(strip_tags($line2[5])));
						$hold['actions'] = str_replace("\n", ", ", trim(strip_tags($line2[6])));
						
						if ($hold['date_start'] != '') $holds[] = $hold;
					}
					$number++;
				}
				
				// Mise en cache des données des blocages
				$this->CI->mCache->addCache('data|holds', $holds);
			
				return ($holds);
			} else {
				return (array());
			}
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('robotCheckHolds');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])));
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$holds = unserialize(base64_decode($response['data']));
					
					// Mise en cache des données des blocages
					$this->CI->mCache->addCache('data|holds', $holds);
				
					return ($holds);
				} else {
					return (false);
				}
			}
		}
	}
	
	// Sommaire du dossier étudiant
	public function getStudies ($semester) {
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
			
			if ($_SESSION['referer']=='') {
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			} else {
				$this->CI->lfetch->referer = $_SESSION['referer'];
			}
			
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/bwskgstu.P_StuInfo";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
						
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->request_method="POST";
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskgstu.P_StuInfo';
			$this->CI->lfetch->Open($arguments);
			
			// Envoi du formulaire
			$arguments["PostValues"] = array(
				  'term_in'	=>	$semester
				  );
			$arguments["RequestURI"] = "/pls/etprod7/bwskgstu.P_StuInfo";
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
			
			$this->CI->lfetch->Close();
			
			if (!$this->checkPage($response)) return (false);
				
			$_SESSION['referer'] = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskgstu.P_StuInfo';
			
			//$response = str_replace("<br />\nS ", "", $response);
			
			if (strpos($response, "Il n'existe pas d'informations étudiantes disponibles")>1) {
				$this->CI->lfetch->SaveCookies($cookies);
				
				$_SESSION['cookies'] = $cookies;
				
				return ('no-info');
			}
			
			if (strpos($response, "tudes en cours")>1) {
				$this->CI->lfetch->SaveCookies($cookies);
				
				$_SESSION['cookies'] = $cookies;
							
				$content = substr($response, strpos($response, "<DIV class=\"pagebodydiv\">"));
				$content = substr($content, strpos($content, "<TABLE"));
				$content = substr($content, strpos($content, "<TR>"));
				$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
				$content = substr($content, 0, strrpos($content, "</TABLE>"));
				
				// Tri des données
				$studies = array();
				
				$studies['data'] = $content;
				$studies['rawdata'] = $response;
				
				$sections = explode("<TR>", $content);
				
				foreach ($sections as $section) {
					$section = explode("</TH>", $section);
					if (count($section)==2) {
						$name = trim(str_replace(":", "", strip_tags($section[0])));
						$value = trim(strip_tags($section[1]));
						
						switch ($name) {
							case 'Inscrit pour la session':
								$studies['registered'] = $value;
							break;
							case 'Première session de fréquentationdans le cycle d\'études':
								$studies['first_sem'] = $value;
							break;
							case 'Dernière session de fréquentationdans le cycle d\'études':
								$studies['last_sem'] = $value;
							break;
							case 'Statut':
								$studies['status'] = $value;
							break;
							case 'Programme actuel':
								$studies['diploma'] = $value;
							break;
							case 'Cycle':
								$studies['cycle'] = $value;
							break;
							case 'Programme':
								$studies['program'] = $value;
							break;
							case 'Session d\'admission':
								$studies['adm_semester'] = $value;
							break;
							case 'Type d\'admission':
								$studies['adm_type'] = $value;
							break;
							case 'Faculté':
								$studies['faculty'] = $value;
							break;
							case 'Majeure':
								$studies['major'] = $value;
							break;
							case 'Mineure':
								$studies['minor'] = $value;
							break;
							case 'Concentration de majeure':
								if (!array_key_exists('concentrations', $studies)) $studies['concentrations'] = array();
								$studies['concentrations'][] = $value;
							break;
						}
					}
				}
				
				$diploma = substr($content, strpos($content, "Programme actuel"));
				$diploma = substr($diploma, strpos($diploma, "dddefault")+11);
				$diploma = substr($diploma, 0, strpos($diploma, "</TD>"));
				$studies['diploma'] = $diploma;
				
				return ($studies);
			} else {
				// Enregistrement de la réponse dans le cache
				$this->CI->mCache->addCache('data|studies,summary', $response, 1);
				
				return (false);
			}
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('studiesGetStudies');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])), $semester);
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$studies = unserialize(base64_decode($response['data']));
				
					return ($studies);
				} else {
					return (false);
				}
			}
		}
	}
	
	// Rapport de cheminement
	public function getStudiesDetails ($semester, $fetchDetails1 = true) {
		$this->CI->mUser->deleteCourses();

		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
	
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu";
	
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu';
			$arguments["RequestURI"] = "/pls/etprod7/bwcksmmt.P_DispPrevEval";
		
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}	
			
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->request_method="POST";
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwcksmmt.P_DispPrevEval';
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$arguments["PostValues"] = array(
				  'term_in'	=>	$semester
				  );
			$arguments["RequestURI"] = "/pls/etprod7/bwcksmmt.P_DispPrevEval";
	
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
			
			if (!$this->checkPage($response)) return (false);
				
			$this->CI->lfetch->Close();
			unset($arguments["PostValues"]);
			$this->CI->lfetch->request_method = "GET";
			
			// Recherche du lien vers le dernier rapport de cheminement
			if (strpos($response, "Rapports précédents")>1) {
				$link = substr($response, strpos($response, '/pls/etprod7/bwckcapp.P_DispEvalViewOption?request_no'), 500);
				$link = substr($link, 0, strpos($link, "\""));
			} else {
				error_log('Impossible de trouver un rapport');
				error_log($response);
				return (false);
			}
			
			if ($link!='') {
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwcksmmt.P_DispPrevEval';
				$arguments["RequestURI"] = $link;
	
				$error=$this->CI->lfetch->Open($arguments);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
	
				$error=$this->CI->lfetch->SendRequest($arguments);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
				
				$this->CI->lfetch->Close();
			} else {
				return (false);
			}
			
			$this->CI->lfetch->request_method="POST";
			
			$id = substr($link, strpos($link, "_no=")+4);
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca'.$link;
			
			if ($fetchDetails1) {
				// Attestation de cheminement
				
				$arguments["RequestURI"] = "/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption";
				
				$error=$this->CI->lfetch->Open($arguments);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
				
				$arguments["PostValues"] = array(
					  'request_no'		=>	$id,
					  'program_summary'	=>	'1'
					  );
		
				$error=$this->CI->lfetch->SendRequest($arguments);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
				
				$error=$this->CI->lfetch->ReadReplyHeaders($headers);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
				
				$error = $this->CI->lfetch->ReadWholeReplyBody($body);
				$response = utf8_encode(html_entity_decode($body));
				$details1 = $response;
				
				$this->CI->lfetch->Close();
				
				if (!$this->checkPage($details1)) return (false);
				
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption';
			}
			
			// Rapport détaillé
			
			$arguments["RequestURI"] = "/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$arguments["PostValues"] = array(
				  'request_no'		=>	$id,
				  'program_summary'	=>	'3'
				  );
	
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
			$details2 = $response;
			
			$this->CI->lfetch->Close();
			
			if (!$this->checkPage($details2)) return (false);
			
			// Infos supplémentaires
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption';
			$arguments["RequestURI"] = "/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$arguments["PostValues"] = array(
				  'request_no'		=>	$id,
				  'program_summary'	=>	'2'
				  );
	
			$error = $this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
			$details3 = $response;
			
			$this->CI->lfetch->Close();
			
			if (!$this->checkPage($details3)) return (false);
			
			$_SESSION['referer'] = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption';
			
			// Sélection des informations dans les rapports
			if ($fetchDetails1) {
				$content = substr($details1, strpos($details1, "<DIV class=\"pagebodydiv\">"));
				$content = substr($content, strpos($content, "<TABLE"));
				$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
				$content = substr($content, 0, strrpos($content, "<A"));
				$content = str_replace("<br />", "", $content);
				$details1 = str_replace(":</TH>", "</TH>", $content);
			} else {
				$details1 = '';
			}
			
			$content = substr($details2, strpos($details2, "<DIV class=\"pagebodydiv\">"));
			$content = substr($content, strpos($content, "<TABLE"));
			$content = substr($content, strpos($content, "</DIV"));
			$content = substr($content, strpos($content, "fieldmediumtext"));
			$content = '<TABLE>'.trim(substr($content, strpos($content, "</TR")+5));
			$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
			$content = substr($content, 0, strrpos($content, "<BR>"));
			$content = str_replace("<br />", "", $content);
			$details2 = str_replace(":</TH>", "</TH>", $content);
			
			$content = substr($details3, strpos($details3, "<DIV class=\"pagebodydiv\">"));
			$content = substr($content, strpos($content, "<H3"));
			$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
			$content = substr($content, 0, strrpos($content, "<BR>"));
			$content = str_replace("<br />", "", $content);
			$details3 = str_replace(":</TH>", "</TH>", $content);
			
			// Tri des données
			$studies = array();
			
			if ($fetchDetails1) {				
				$code_permanent = substr($details1, strpos($details1, "Code permanent"));
				$code_permanent = substr($code_permanent, strpos($code_permanent, "dddefault")+11);
				$code_permanent = strip_tags(substr($code_permanent, 0, strpos($code_permanent, "</TD>")));
				$studies['code_permanent'] = trim(str_replace(" ", "", $code_permanent));
				
				$date_attestation = substr($details1, strpos($details1, ">Date de l'attestation"));
				$date_attestation = substr($date_attestation, strpos($date_attestation, "dddefault")+11);
				$date_attestation = strip_tags(substr($date_attestation, 0, strpos($date_attestation, "</TD>")));
				$studies['date_attestation'] = $date_attestation;
				
				$date_diplome = substr($details1, strpos($details1, "Date d'obtention"));
				$date_diplome = substr($date_diplome, strpos($date_diplome, "<TD"));
				$date_diplome = strip_tags(substr($date_diplome, 0, strpos($date_diplome, "</TD>")));
				$studies['date_diplome'] = trim(str_replace("&nbsp;", "", $date_diplome));
	
				$session_repertoire = substr($details1, strpos($details1, ">Session de répertoire"));
				$session_repertoire = substr($session_repertoire, strpos($session_repertoire, "dddefault")+11);
				$session_repertoire = strip_tags(substr($session_repertoire, 0, strpos($session_repertoire, "</TD>")));
				$studies['session_repertoire'] = $session_repertoire;
				
				$session_evaluation = substr($details1, strpos($details1, ">Session d'évaluation"));
				$session_evaluation = substr($session_evaluation, strpos($session_evaluation, "dddefault")+11);
				$session_evaluation = strip_tags(substr($session_evaluation, 0, strpos($session_evaluation, "</TD>")));
				$studies['session_evaluation'] = $session_evaluation;
				
				$requirements = substr($details1, strpos($details1, ">Total exigé"));
				$requirements = substr($requirements, strpos($requirements, "dddefault")+11);
				$requirements = trim(strip_tags(substr($requirements, 0, strpos($requirements, "</TD>"))));
				$studies['requirements'] = $requirements;
				
				$credits_programme = substr($details1, strpos($details1, ">Total exigé"));
				$credits_programme = substr($credits_programme, strpos($credits_programme, "dddefault")+11);
				$credits_programme = substr($credits_programme, strpos($credits_programme, "dddefault")+11);
				$credits_programme = strip_tags(substr($credits_programme, 0, strpos($credits_programme, "</TD>")));
				$studies['credits_program'] = trim(html_entity_decode($credits_programme, ENT_QUOTES, 'utf-8'));
				
				$credits_utilises = substr($details1, strpos($details1, ">Total exigé"));
				$credits_utilises = substr($credits_utilises, strpos($credits_utilises, "dddefault")+11);
				$credits_utilises = substr($credits_utilises, strpos($credits_utilises, "dddefault")+11);
				$credits_utilises = substr($credits_utilises, strpos($credits_utilises, "dddefault")+11);
				$credits_utilises = strip_tags(substr($credits_utilises, 0, strpos($credits_utilises, "</TD>")));
				$studies['credits_used'] = trim(html_entity_decode($credits_utilises, ENT_QUOTES, 'utf-8'));
				
				$cours_programme = substr($details1, strpos($details1, ">Total exigé"));
				$cours_programme = substr($cours_programme, strpos($cours_programme, "dddefault")+11);
				$cours_programme = substr($cours_programme, strpos($cours_programme, "dddefault")+11);
				$cours_programme = substr($cours_programme, strpos($cours_programme, "dddefault")+11);
				$cours_programme = substr($cours_programme, strpos($cours_programme, "dddefault")+11);
				$cours_programme = strip_tags(substr($cours_programme, 0, strpos($cours_programme, "</TD>")));
				$studies['courses_program'] = trim(html_entity_decode($cours_programme, ENT_QUOTES, 'utf-8'));
				
				$cours_utilises = substr($details1, strpos($details1, ">Total exigé"));
				$cours_utilises = substr($cours_utilises, strpos($cours_utilises, "dddefault")+11);
				$cours_utilises = substr($cours_utilises, strpos($cours_utilises, "dddefault")+11);
				$cours_utilises = substr($cours_utilises, strpos($cours_utilises, "dddefault")+11);
				$cours_utilises = substr($cours_utilises, strpos($cours_utilises, "dddefault")+11);
				$cours_utilises = substr($cours_utilises, strpos($cours_utilises, "dddefault")+11);
				$cours_utilises = strip_tags(substr($cours_utilises, 0, strpos($cours_utilises, "</TD>")));
				$studies['courses_used'] = trim(html_entity_decode($cours_utilises, ENT_QUOTES, 'utf-8'));
						
				$credits_reconnus = substr($details1, strpos($details1, ">Reconnaissance d'acquis"));
				$credits_reconnus = substr($credits_reconnus, strpos($credits_reconnus, "dddefault")+11);
				$credits_reconnus = substr($credits_reconnus, strpos($credits_reconnus, "dddefault")+11);
				$credits_reconnus = strip_tags(substr($credits_reconnus, 0, strpos($credits_reconnus, "</TD>")));
				$studies['credits_admitted'] = trim(html_entity_decode($credits_reconnus, ENT_QUOTES, 'utf-8'));
				
				$cours_reconnus = substr($details1, strpos($details1, ">Reconnaissance d'acquis"));
				$cours_reconnus = substr($cours_reconnus, strpos($cours_reconnus, "dddefault")+11);
				$cours_reconnus = substr($cours_reconnus, strpos($cours_reconnus, "dddefault")+11);
				$cours_reconnus = substr($cours_reconnus, strpos($cours_reconnus, "dddefault")+11);
				$cours_reconnus = substr($cours_reconnus, strpos($cours_reconnus, "dddefault")+11);
				$cours_reconnus = strip_tags(substr($cours_reconnus, 0, strpos($cours_reconnus, "</TD>")));
				$studies['courses_admitted'] = trim(html_entity_decode($cours_reconnus, ENT_QUOTES, 'utf-8'));
				
				$moyenne_cheminement = substr($details1, strpos($details1, ">Moyenne de cheminement"));
				$moyenne_cheminement = substr($moyenne_cheminement, strpos($moyenne_cheminement, "dddefault")+11);
				$moyenne_cheminement = substr($moyenne_cheminement, strpos($moyenne_cheminement, "dddefault")+11);
				$moyenne_cheminement = strip_tags(substr($moyenne_cheminement, 0, strpos($moyenne_cheminement, "</TD>")));
				$studies['gpa_overall'] = trim(str_replace(",", ".", trim($moyenne_cheminement)));
				
				$gpa_program = substr($details1, strpos($details1, ">Moyenne de programme"));
				$gpa_program = substr($gpa_program, strpos($gpa_program, ":")+1);
				$gpa_program = str_replace("&nbsp;", "", strip_tags(substr($gpa_program, 0, strpos($gpa_program, "</SPAN>"))));
				$gpa_program = substr($gpa_program, strrpos($gpa_program, " ")+1);
				$studies['gpa_program'] = trim(str_replace(",", ".", str_replace(" ", "", $gpa_program)));
			}
			
			// Sélection des données du rapport de cheminement
			$data = substr($details2, strpos($details2, 'Cette table d\'affichage sert à présenter les conditions')-42);
			//$data = substr($data, strpos($data, "Bloc:</SPAN>")+12);
			$blocs = explode("Bloc:</SPAN>", $data);
			
			$sections = array();
			$number = 0;
			$sections_number = 1;
			foreach ($blocs as $bloc) {
				if ($number!=0) {
					$section = array();
					$title = substr($bloc, strpos($bloc, "fieldmediumtextbold")+20);
					$section['title'] = trim(strip_tags(substr($title, 0, strpos($title, "</SPAN>"))));
					if (strpos($section['title'], " ( ")>-1) {
						$section['credits'] = trim(substr($section['title'], strrpos($section['title'], " ( ")+3));
						$section['credits']	= substr($section['credits'], 0, strpos($section['credits'], ","));													   
						$section['title'] = trim(substr($section['title'], 0, strrpos($section['title'], " ( ")));
					}
					$courses = array();
					
					$courses_data = substr($bloc, strpos($bloc, "<TH CLASS=\"ddheader\" scope=\"col\" ><SPAN class=fieldsmallboldtext>Source</SPAN></TH>"));
					
					$courses_data = explode("</TR>", $courses_data);
					
					$line_number = 0;
					foreach ($courses_data as $line) {
						if ($line_number!=0 and $line_number!=count($courses_data)) {
							$line = explode("</TD>", $line);
							
							if (count($line)>5 and trim(str_replace("&nbsp;", "", strip_tags($line[(count($line)-2)])))!='') {
								$code = trim(strip_tags(str_replace("&nbsp;", "", $line[(count($line)-7)])))."-".trim(strip_tags(str_replace("&nbsp;", "", $line[(count($line)-6)])));
								$code = str_replace(" ", "", $code);
								
								if ($code=='-') $code = '';
								$semester = trim(strip_tags(str_replace("&nbsp;", "", $line[(count($line)-8)])));
								$note = trim(strip_tags(str_replace("&nbsp;", "", $line[(count($line)-3)])));
								if ($note=='*') $note = '';
								if ($note==' ') $note = '';
								$title = trim(strip_tags($line[(count($line)-5)]));
								$credits = trim(strip_tags($line[(count($line)-4)]));
								
								if ($title!='Total des crédits et moyenne du bloc d\'exigences' and $title!='' and $semester!='' and $code!='') {
									$courses[] = array(
													   'idcourse'	=>	$code,
													   'section'	=>	$section['title'],
													   'title'		=>	$title,
													   'credits'	=>	$credits,
													   'semester'	=>	$semester,
													   'note'		=>	$note
													  );
								}
							}
						}
						
						$line_number++;
					}
					
					foreach ($courses as $course) {
						// Enregistrement du cours
						$this->CI->mUser->addCourse($course);
					}
					
					if (count($courses)>0) {
						$section['number'] = $sections_number;
						$sections[] = $section;
						$sections_number++;
					}
				}
				$number++;
			}
			
			foreach ($sections as $section) {
				// Enregistrement de la section de cours
				$this->CI->mUser->addCoursesSection($section);
			}
			
			// Données brutes
			$data = array(
						  'details1'	=>	$details1,
						  'details2'	=>	$details2,
						  'details3'	=>	$details3
						  );
			
			$details = array();
			$details['other_courses'] = array();
			if (strpos($details3, ">Cours non utilisés")>1) {
				$other_courses = substr($details3, strpos($details3, ">Cours non utilisés"));
				$other_courses = substr($other_courses, strpos($other_courses, "<TR>")+4);
				$other_courses = substr($other_courses, strpos($other_courses, "<TR>")+4);
				$other_courses = substr($other_courses, 0, strpos($other_courses, "</TABLE>"));
				$other_courses = substr($other_courses, 0, strrpos($other_courses, "</TR>"));
				$other_courses = explode("</TR>", $other_courses);
				
				$courses = array();
				foreach ($other_courses as $line) {
					$line = substr($line, 0, strrpos($line, "</TD>"));
					$line = explode("</TD>", $line);
					$code = trim(strip_tags($line[0]))."-".trim(strip_tags($line[1]));
					$title2 = trim(strip_tags($line[2]));
					$semester = trim(strip_tags($line[3]));
					$credits = trim(strip_tags($line[4]));
					$note = trim(strip_tags($line[5]));
					if ($note=='&nbsp;') $note = '';
					
					$courses[] = array(
									   'code'	=>	$code,
									   'title'	=>	$title2,
									   'semester'=>	$semester,
									   'credits'=>	str_replace(",0", "", $credits),
									   'note'	=>	$note
									  );
				}
				
				$details['other_courses'] = $courses;
			}
			
			return (array('studies'=>$studies, 'data'=>$data, 'details'=>$details));
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('studiesGetStudiesDetails');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])), $semester);
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$studies = unserialize(base64_decode($response['data']));
				
					return ($studies);
				} else {
					return (false);
				}
			}
		}
	}
	
	
	// Relevé de notes
	public function getReport () {
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu";
	
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
				
			$headers = array();
			$error = $this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu';
			$arguments["RequestURI"] = "/pls/etprod7/bwskotrn.P_ViewTermTran";
		
			$error = $this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->request_method="POST";
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskotrn.P_ViewTermTran';
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$arguments["PostValues"] = array(
				  'levl'	=>	'1',
				  'tprt'	=>	'WEB'
				  );
			$arguments["RequestURI"] = "/pls/etprod7/bwskotrn.P_ViewTran";
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
			
			$this->CI->lfetch->Close();
			
			if (!$this->checkPage($response)) return (false);
			
			$_SESSION['referer'] = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskotrn.P_ViewTran';
			
			// Sélection des informations
			$content = substr($response, strpos($response, "<DIV class=\"pagebodydiv\">"));
			$content = substr($content, strpos($content, "<TABLE"));
			$content = substr($content, strpos($content, "<P class=\"whitespace1\">"));
			$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
			$content = str_replace("<br />", "", $content);
			$content = str_replace(":</TH>", "</TH>", $content);
			
			// Tri des données
			$report = array();
			$studies = array();
			$data = $content;
			$rawdata = $response;
			
			$birthday = substr($content, strpos($content, "Jour de naissance"));
			$birthday = substr($birthday, strpos($birthday, "dddefault")+11);
			$birthday = str_replace("Û", "û", str_replace("É", "é", strtolower(strip_tags(substr($birthday, 0, strpos($birthday, "</TD>"))))));
			$studies['birthday'] = $birthday;
			
			$da = substr($content, strpos($content, "No de dossier"));
			$da = substr($da, strpos($da, "dddefault")+11);
			$da = strtolower(strip_tags(substr($da, 0, strpos($da, "</TD>"))));
			$studies['da'] = str_replace(" ", "", $da);
			
			$attendance = substr($content, strpos($content, "Fréquentation<"));
			$attendance = substr($attendance, strpos($attendance, "dddefault")+11);
			$attendance = strip_tags(substr($attendance, 0, strpos($attendance, "</TD>")));
			$studies['attendance'] = $attendance;
			
			$moyenne = substr($content, strpos($content, "Reconnaissance des acquis<"));
			$moyenne = substr($moyenne, strpos($moyenne, "Total<"));
			$moyenne = substr($moyenne, strpos($moyenne, "dddefault")+11);
			$moyenne = substr($moyenne, strpos($moyenne, "dddefault")+11);
			$moyenne = substr($moyenne, strpos($moyenne, "dddefault")+11);
			$moyenne = substr($moyenne, strpos($moyenne, "dddefault")+11);
			$moyenne = substr($moyenne, strpos($moyenne, "dddefault")+11);
			$moyenne = substr($moyenne, strpos($moyenne, "dddefault")+11);
			$moyenne = trim(strip_tags(substr($moyenne, 0, strpos($moyenne, "</TD>"))));
			$report['gpa'] = $moyenne;
			
			$semesters = substr($content, strpos($content, "CRÉDITS DE L'UNIVERSITÉ LAVAL"));
			$semesters = substr($content, strpos($content, "fieldOrangetextbold"));
			$semesters = substr($semesters, 0, strpos($semesters, "BILAN DU RELEVÉ"));
			$semesters = explode("fieldOrangetextbold", $semesters);
			
			$report['semesters'] = array();
			foreach ($semesters as $semester) {
				$title = substr($semester, 1, 100);
				$title = substr($title, 0, strpos($title, "</SPAN>"));
				
				if (strpos($semester, "Session<")>1) {
					$total_credits = substr($semester, strpos($semester, "Session<"));
					$total_credits = substr($total_credits, strpos($total_credits, "rightaligntext")+16);
					$total_credits = substr($total_credits, strpos($total_credits, "rightaligntext")+16);
					$total_credits = substr($total_credits, strpos($total_credits, "rightaligntext")+16);
					$total_credits = trim(str_replace(",000", "", substr($total_credits, 0, strpos($total_credits, "</TD>"))));
					
					$total_points = substr($semester, strpos($semester, "Session<"));
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = trim(substr($total_points, 0, strpos($total_points, "</TD>")));
					
					$moyenne = substr($semester, strpos($semester, "Session<"));
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = trim(substr($moyenne, 0, strpos($moyenne, "</TD>")));
				} else {
					$total_credits = substr($semester, strpos($semester, "Session actuelle<"));
					$total_credits = substr($total_credits, strpos($total_credits, "rightaligntext")+16);
					$total_credits = substr($total_credits, strpos($total_credits, "rightaligntext")+16);
					$total_credits = substr($total_credits, strpos($total_credits, "rightaligntext")+16);
					$total_credits = trim(str_replace(",000", "", substr($total_credits, 0, strpos($total_credits, "</TD>"))));
					
					$total_points = substr($semester, strpos($semester, "Session actuelle<"));
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = substr($total_points, strpos($total_points, "rightaligntext")+16);
					$total_points = trim(substr($total_points, 0, strpos($total_points, "</TD>")));
					
					$moyenne = substr($semester, strpos($semester, "Session actuelle<"));
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = substr($moyenne, strpos($moyenne, "rightaligntext")+16);
					$moyenne = trim(substr($moyenne, 0, strpos($moyenne, "</TD>")));
				}
				
				$semester = substr($semester, strpos($semester, "</TR>")+4);
				$semester = substr($semester, strpos($semester, "</TR>")+4);
				$semester = substr($semester, strpos($semester, "<TR>")+4);
				$semester = substr($semester, 0, strpos($semester, "Crédits"));
				$semester = substr($semester, 0, strrpos($semester, "</TR>"));
				$semester = explode("</TR>", $semester);
				
				$courses = array();
				foreach ($semester as $line) {
					$line = substr($line, 0, strrpos($line, "</TD>"));
					$line = explode("</TD>", $line);
					
					if (count($line)>1) {
						$code = trim(strip_tags($line[0]))."-".trim(strip_tags($line[1]));
						$title2 = trim(strip_tags($line[3]));
						$note = trim(strip_tags($line[4]));
						if ($note=='&nbsp;') $note = '';
						$credits = trim(strip_tags($line[5]));
						$points = trim(strip_tags($line[6]));
						if (isset($line[7])) {
							$repeat = trim(strip_tags($line[7]));
							if ($repeat=='&nbsp;') $repeat = '';
						} else $repeat = '';
						
						$courses[] = array(
										   'code'	=>	$code,
										   'title'	=>	$title2,
										   'credits'=>	str_replace(",000", "", $credits),
										   'note'	=>	$note,
										   'points'	=>	$points,
										   'repeat'=>	$repeat
										  );
					}
				}
				
				if ($courses!=array()) {
					$semester2 = array(
									   'title'	=>	$title,
									   'courses'=>	$courses,
									   'gpa'=>	$moyenne,
									   'total_points'=>	$total_points,
									   'total_credits'=>	$total_credits
									   );
					
					$report['semesters'][] = $semester2;
				}
			}
							
			return (array('studies'=>$studies, 'report'=>$report, 'rawdata'=>$rawdata));
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('studiesGetReport');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])));
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$report = unserialize(base64_decode($response['data']));
				
					return ($report);
				} else {
					return (false);
				}
			}
		}
	}
	
	// Horaire de cours
	public function getSchedule ($requested_semester = '') {
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
			
			if ($_SESSION['referer']=='') {
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			} else {
				$this->CI->lfetch->referer = $_SESSION['referer'];
			}
			
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
					
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
			$arguments["RequestURI"] = "/pls/etprod7/bwskfshd.P_CrseSchdDetl";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
						
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$this->CI->lfetch->Close();
			
			if ($requested_semester=='') {
				$suggested_semesters = array(
											 (date('Y')+1)."01"		=>	"Hiver ".(date('Y')+1),
											 date('Y')."09"		=>	"Automne ".date('Y'),
											 date('Y')."05"		=>	"Été ".date('Y'),
											 date('Y')."01"		=>	"Hiver ".date('Y'),
											 (date('Y')-1)."09"		=>	"Automne ".(date('Y')-1),
											 (date('Y')-1)."05"		=>	"Été ".(date('Y')-1),
											 (date('Y')-1)."01"		=>	"Hiver ".(date('Y')-1)
											 );
				
				$semesters = array();
			} else {
				$suggested_semesters = array($requested_semester => '');
			}
			
			foreach ($suggested_semesters as $semester => $name) {
				$this->CI->lfetch->request_method="POST";
				
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfshd.P_CrseSchdDetl';
				$arguments["RequestURI"] = "/pls/etprod7/bwskfshd.P_CrseSchdDetl";
				$this->CI->lfetch->Open($arguments);
				
				// Envoi du formulaire
				$arguments["PostValues"] = array(
												'term_in'	=>	$semester
												);
				
				$error=$this->CI->lfetch->SendRequest($arguments);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
				
				$headers=array();
				$error=$this->CI->lfetch->ReadReplyHeaders($headers);
				if ($error!="") {
					error_log(__FILE__." : ligne ".__LINE__." | ".$error);
					return (false);
				}
				
				$error = $this->CI->lfetch->ReadWholeReplyBody($body);
				$data = utf8_encode(html_entity_decode($body));
				
				$this->CI->lfetch->Close();
				
				if (!$this->checkPage($data)) return (false);
				
				if (strpos($data, "Vous n'êtes pas actuellement inscrit pour la session.")>1) {
				} else {
					// Tri des données
					$schedule = array();
					
					$content = substr($data, strpos($data, "Régime d'études"));
					$content = substr($content, strpos($content, "datadisplaytable"));
					$content = substr($content, 0, strpos($content, "Retour à la page précédente"));
					$content = explode("détails de l'horaire des cours", $content);
					
					$number = 0;
					$courses = array();
					$classes = array();
					$min_hour = 22;
					$max_hour = 0;
					
					foreach ($content as $line) {
						$course = array();
						$title = "";
						if ($number!=0) {
							$title = substr($line, strpos($line, "<CAPTION"));
							$title = trim(strip_tags(substr($title, 0, strpos($title, "</CAPTION>"))));
							$title = explode(" - ", $title);
							
							$course['code'] = trim(str_replace(" ", "-", $title[1]));
							$course['title'] = trim($title[0]);
							$course['letter'] = trim($title[2]);
															
							$nrc = substr($line, strpos($line, "NRC"));
							$nrc = substr($nrc, strpos($nrc, "<TD"));
							$course['nrc'] = trim(strip_tags(substr($nrc, 0, strpos($nrc, "</TD>"))));
							
							$teacher = substr($line, strpos($line, "Professeur:"));
							$teacher = substr($teacher, strpos($teacher, "<TD"));
							$course['teacher'] = trim(strip_tags(substr($teacher, 0, strpos($teacher, "</TD>"))));
							
							$credits = substr($line, strpos($line, "Crédits:"));
							$credits = substr($credits, strpos($credits, "<TD"));
							$course['credits'] = str_replace(",000", "", trim(strip_tags(substr($credits, 0, strpos($credits, "</TD>")))));
							
							$campus = substr($line, strpos($line, "Campus:"));
							$campus = substr($campus, strpos($campus, "<TD"));
							$course['campus'] = trim(strip_tags(substr($campus, 0, strpos($campus, "</TD>"))));
							
							$timetable = substr($line, strpos($line, "Horaires prévus"));
							$timetable = substr($timetable, 0, strpos($timetable, "</TABLE>"));
							$timetable = explode("<TR>", $timetable);
							
							$number2 = 0;
							foreach ($timetable as $line2) {
								$class = array();
								if ($number2>1) {
									$line2 = explode("</TD>", $line2);
									$class['type'] = trim(strip_tags($line2[0]));
									if (trim(strip_tags($line2[1]))!="ACU" and trim(strip_tags($line2[1]))!='') {
										$hours = explode("-", trim(strip_tags($line2[1])));
										$class['hour_start'] = trim($hours[0]);
										$class['hour_end'] = trim($hours[1]);
									}
									if (isset($class['hour_start'])) {
										$class['hour_start'] = str_replace(":30", ".5", str_replace(":00", "", str_replace(":20", ".5", $class['hour_start'])));
										if (strpos($class['hour_start'], ':50')>1) {
											$class['hour_start'] = str_replace(":50", "", $class['hour_start']);
											$class['hour_start']++;
										}
										if ($class['hour_start']<=$min_hour) $min_hour = $class['hour_start']-0.5;
									}
									if (isset($class['hour_end'])) {
										$class['hour_end'] = str_replace(":30", ".5", str_replace(":00", "", str_replace(":20", ".5", $class['hour_end'])));
										if (strpos($class['hour_end'], ':50')>1) {
											$class['hour_end'] = str_replace(":50", "", $class['hour_end']);
											$class['hour_end']++;
										}
										if ($class['hour_end']>=$max_hour) $max_hour = $class['hour_end'];
									}
									$class['day'] = trim(str_replace("&nbsp;", " ", strip_tags($line2[2])));
									$class['local'] = trim(strip_tags($line2[3]));
									$days = explode("-", trim(strip_tags($line2[4])));
									$class['day_start'] = trim(str_replace("/", "", $days[0]));
									$class['day_end'] = trim(str_replace("/", "", $days[1]));
									$class['teacher'] = trim(str_replace("(P)", "", strip_tags($line2[6])));
									
									$class['nrc'] = $course['nrc'];
									$class['idcourse'] = $course['code'];
									$class['semester'] = $semester;
									
									$this->CI->mUser->addClass($class);
									
									$classes[] = $class;
								}
								$number2++;
							}
							
							$courses[] = $course;
							
						}
						$number++;
					}
		
					$schedule['courses'] = $courses;

					$courses_type1 = 0;
					$courses_type2 = 0;
					$days_start = array();
					$days_end = array();
					
					foreach ($classes as $class) {
						if (strtolower($class['type'])!='cours en classe') {
							$courses_type2++;
						} else {
							$days_start[] = $class['day_start'];
							$days_end[] = $class['day_end'];
						}
					}
					
					$array_difference = array_count_values($days_start);
					$array_difference2 = array_count_values($days_end);
					
					$periods_days = array();
					foreach ($array_difference as $day => $number) {
						$periods_days[] = $day;
					}
					foreach ($array_difference2 as $day => $number) {
						$periods_days[] = $day;
					}
					sort($periods_days);
					$periods = array();
					if (count($array_difference)>1) {
						$number = 0;
						$periods_days2 = $periods_days;
						$last_date = '';
						foreach ($periods_days as $date) {
							if ($number == (count($periods_days)-1)) break;
							
							if ($last_date == '') {
								$first_date = $date;
							} else {
								$first_date = date('Ymd', mktime(0, 0, 0, substr($last_date, 4, 2), substr($last_date, 6, 2), substr($last_date, 0, 4))+(3600*24)); 
							}
							
							if ($number == (count($periods_days)-2)) {
								$last_date = $periods_days2[$number+1];
							} else {
								$last_date = date('Ymd', mktime(0, 0, 0, substr($periods_days2[$number+1], 4, 2), substr($periods_days2[$number+1], 6, 2), substr($periods_days2[$number+1], 0, 4))-(3600*24));
							}
							
							// Vérification que des classes sont données pendant la période visée
							$found = 0;
							foreach ($classes as $class) {
								if ($class['day_end']>$first_date and $class['day_start']<=$last_date) $found = 1;
							}
							
							if ($found == 1) {
								$periods[$first_date . '-' . $last_date] = strtolower(currentDate($first_date, 'd M'))." &ndash; ".currentDate($periods_days2[$number+1], "d M Y");
							}
							
							$number++;
						}
					}
					
					// Mise en cache des données de l'horaire
					$this->CI->mCache->addCache('data|schedule['.$semester.']', $schedule);
					
					$semesters[$semester] = array(
												  'title'	=>	$name,
												  'periods'	=>	$periods
												  );
				}
			}
			
			// Enregistrement de la liste des semestres disponibles
			if ($requested_semester=='') $this->CI->mCache->addCache('data|schedule,semesters', $semesters);
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('scheduleGetSchedule');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])), $requested_semester);
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$response = unserialize(base64_decode($response['data']));
					
					foreach ($response['schedule'] as $semester	=> $schedule) {
						$this->CI->mCache->addCache('data|schedule['.$semester.']', $schedule);
					}
					
					if ($requested_semester=='') $this->CI->mCache->addCache('data|schedule,semesters', $response['semesters']);
					
					return (true);
				} else {
					return (false);
				}
			}
		}
	}
	
	public function getFeesSummary ($requested_semester = '') {
		if ($_SESSION['usebots']==0) {
			$this->CI->lfetch->cookies = $_SESSION['cookies'];
			$this->CI->lfetch->debug = $this->debug;
			
			if ($_SESSION['referer']=='') {
				$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
			} else {
				$this->CI->lfetch->referer = $_SESSION['referer'];
			}
			
			$this->CI->lfetch->protocol="https";
			
			$arguments['HostName'] = "capsuleweb.ulaval.ca";
			$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_ARMnu";
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$error=$this->CI->lfetch->SendRequest($arguments);
				
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$this->CI->lfetch->Close();
			
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_ARMnu';
			$arguments["RequestURI"] = "/pls/etprod7/bwskoacc.P_ViewAcct";
			
			$error=$this->CI->lfetch->Open($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
					
			$error=$this->CI->lfetch->SendRequest($arguments);
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			$headers=array();
			$error=$this->CI->lfetch->ReadReplyHeaders($headers);			
			if ($error!="") {
				error_log(__FILE__." : ligne ".__LINE__." | ".$error);
				return (false);
			}
			
			// Extraction du code source du résultat											
			$error = $this->CI->lfetch->ReadWholeReplyBody($body);
			$data = utf8_encode(html_entity_decode($body));
			
			$this->CI->lfetch->Close();
			
			if (!$this->checkPage($data)) return (false);
				
			// Vérification de l'existence des sessions en cache
			$cache = $this->CI->mCache->getCache('data|fees,client_number');
			
			if ($cache!=array()) {
				$client_number = $cache['value'];
			} else {
				$number = substr($data, strpos($data, "Numéro de client:")+5, 500);
				$number = substr($number, strpos($number, "<TD"));
				$number = trim(strip_tags(substr($number, 0, strpos($number, "</TD>"))));
				
				// Mise en cache des données du numéro de client
				$this->CI->mCache->addCache('data|fees,client_number', $number);
				
				$client_number = $number;
			}
			
			$content = substr($data, strpos($data, "Solde du compte:"));
			$content = substr($content, strpos($content, "<SPAN class=fieldOrangetextbold>"));
			$content = substr($content, 0, strpos($content, "</TABLE>"));
			$content = explode("fieldOrangetextbold", $content);
			
			$number = 0;
			$semesters = array();
			
			foreach ($content as $line) {
				$semester = array();
				$title = "";
				if ($number!=0) {
					$semester['name'] = substr($line, strpos($line, ">")+1);
					$semester['name'] = trim(strip_tags(substr($semester['name'], 0, strpos($semester['name'], "</SPAN>"))));
					
					$semester['total_fees'] = substr($line, strpos($line, "Frais de session:"));
					$semester['total_fees'] = substr($semester['total_fees'], strpos($semester['total_fees'], '<p'));
					$semester['total_fees'] = str_replace(" ", "", str_replace("$", "", trim(strip_tags(substr($semester['total_fees'], 0, strpos($semester['total_fees'], '</TD>'))))));
					
					$semester['total_payments'] = substr($line, strpos($line, "paiements de session:"));
					$semester['total_payments'] = substr($semester['total_payments'], strpos($semester['total_payments'], '<p'));
					$semester['total_payments'] = str_replace(" ", "", str_replace("$", "", trim(strip_tags(substr($semester['total_payments'], 0, strpos($semester['total_payments'], '</TD>'))))));
					
					$semester['balance'] = substr($line, strpos($line, "Solde de session:"));
					$semester['balance'] = substr($semester['balance'], strpos($semester['balance'], '<p'));
					$semester['balance'] = str_replace(" ", "", str_replace("$", "", trim(strip_tags(substr($semester['balance'], 0, strpos($semester['balance'], '</TD>'))))));
					
					$details = substr($line, strpos($line, "<TH CLASS=\"ddheader\" scope=\"col\" >Description</TH>"));
					$details = substr($details, strpos($details, "<TR>"));
					$details = substr($details, 0, strpos($details, "<TH CLASS=\"ddlabel\""));
					$details = substr($details, 0, strrpos($details, "</TR>"));
					$details = explode("<TR>", $details);
					
					$number2 = 0;
					foreach ($details as $line2) {
						$fee = array();
						if ($number2>=1) {
							$line2 = explode("</TD>", $line2);
							
							$fee['name'] = trim(strip_tags($line2[0]));
							if (substr($fee['name'], 0, 8)=='Paiement') {
								$fee['type'] = 'payment';
								$fee['amount'] = str_replace(" ", "", str_replace("$", "", trim(strip_tags($line2[2]))));
							} else {
								$fee['type'] = 'fee';
								$fee['amount'] = str_replace(" ", "", str_replace("$", "", trim(strip_tags($line2[1]))));
							}
							
							$semester['fees'][] = $fee;
						}
						$number2++;
					}
					
					// Mise en cache des données du sommaire du semestre
					$semester_date = explode(" ", $semester['name']);
					
					switch (strtolower($semester_date[0])) {
						case 'hiver':
							$semester_name = $semester_date[1]."01";
						break;
						case 'automne':
							$semester_name = $semester_date[1]."09";
						break;
						default:
							$semester_name = $semester_date[1]."05";
						break;
					}
					$this->CI->mCache->addCache('data|fees['.$semester_name.']', $semester);
					
					$semesters[] = $semester;
				}
				$number++;
			}
			
			$balance = substr($data, strpos($data, "Solde du compte:"), 500);
			$balance = substr($balance, strpos($balance, "<TD"));
			$balance = str_replace(" ", "", str_replace("$", "", trim(strip_tags(substr($balance, 0, strpos($balance, "</TD>"))))));
			
			$summary = array(
							 'client_number'	=>	$client_number,
							 'balance'			=>	$balance,
							 'semesters'		=>	$semesters
							 );
			
			// Mise en cache des données de l'état de compte
			$this->CI->mCache->addCache('data|fees,summary', $summary);
			
			// Mise en cache des données de la liste des semestres
			$semesters_list = array();
			foreach ($semesters as $semester) {
				$semester_date = explode(" ", $semester['name']);
				
				switch (strtolower($semester_date[0])) {
					case 'hiver':
						$semester_name = $semester_date[1]."01";
					break;
					case 'automne':
						$semester_name = $semester_date[1]."09";
					break;
					default:
						$semester_name = $semester_date[1]."05";
					break;
				}
				
				$semesters_list[$semester_name] = $semester['name'];
			}
			
			$this->CI->mCache->addCache('data|fees,semesters', $semesters_list);
					
			return($summary);
		} else {
			// Sélection de la méthode
			$this->CI->xmlrpc->method('feesGetSummary');
		
			// Création de la requête
			$request = array($_SESSION['cap_user']['idul'], base64_encode(serialize($_SESSION['bot-session'])), $requested_semester);
			$this->CI->xmlrpc->request($request);
			
			// Envoi de la requête
			if (!$this->CI->xmlrpc->send_request()) {
				// Enregistrement de l'erreur
				error_log(__FILE__." : ligne ".__LINE__." | ".$this->CI->xmlrpc->display_error());
				return (false);
			} else {
				$response = $this->CI->xmlrpc->display_response();
				$_SESSION['bot-session'] = unserialize(base64_decode($response['session']));
				
				if ($response['status']=='data') {
					$response = unserialize(base64_decode($response['data']));
					
					foreach ($response['fees'] as $semester	=> $fees) {
						$this->CI->mCache->addCache('data|fees['.$semester.']', $fees);
					}
					
					$this->CI->mCache->addCache('data|fees,summary', $response['summary']);
					$this->CI->mCache->addCache('data|fees,client_number', $response['client_number']);
					$this->CI->mCache->addCache('data|fees,semesters', $response['semesters']);
					
					return (true);
				} else {
					return (false);
				}
			}
		}
	}
	
	public function registerCourses ($nrc_array, $semester) {
		$this->CI->lfetch->cookies = $_SESSION['cookies'];
		$this->CI->lfetch->debug = $this->debug;
		
		if ($_SESSION['referer']=='') {
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
		} else {
			$this->CI->lfetch->referer = $_SESSION['referer'];
		}
		
		$this->CI->lfetch->protocol="https";
		
		$arguments['HostName'] = "capsuleweb.ulaval.ca";
		$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu";
		
		$error=$this->CI->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->CI->lfetch->Close();
		
		$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->CI->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->CI->lfetch->Close();
		
		$this->CI->lfetch->request_method="POST";
		
		$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->CI->lfetch->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'term_in'				=>	$semester
			  );
		
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->CI->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->CI->lfetch->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		// Analyse de la page
		$data = substr($response, strpos($response, '<TABLE  CLASS="datadisplaytable" SUMMARY="Horaire actuel">')+20);
		$data = substr($data, strpos($data, '</TR>')+5);
		$data = substr($data, 0, strpos($data, '<TABLE  CLASS="datadisplaytable"'));
		$data = explode("<TR>", $data);
		
		$arguments['PostString'] = "term_in=".$semester."&RSTS_IN=DUMMY&assoc_term_in=DUMMY&CRN_IN=DUMMY&start_date_in=DUMMY&end_date_in=DUMMY&SUBJ=DUMMY&CRSE=DUMMY&SEC=DUMMY&LEVL=DUMMY&CRED=DUMMY&GMOD=DUMMY&TITLE=DUMMY&MESG=DUMMY&REG_BTN=DUMMY";
		
		$number = 0;
		foreach ($data as $line) {
			if ($number!=0) {
				$new = array();
				
				$field = substr($line, strpos($line, ' NAME="MESG"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['MESG'] = $field;
				
				$new['RSTS_IN'] = '';
				
				$field = substr($line, strpos($line, ' NAME="assoc_term_in"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['assoc_term_in'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="CRN_IN"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['CRN_IN'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="start_date_in"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['start_date_in'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="end_date_in"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['end_date_in'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="SUBJ"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['SUBJ'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="CRSE"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['CRSE'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="SEC"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['SEC'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="LEVL"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['LEVL'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="CRED"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['CRED'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="GMOD"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['GMOD'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="TITLE"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['TITLE'] = $field;
				
				foreach ($new as $name => $value) {
					$arguments['PostString'] .= "&".$name."=".urlencode($value);
				}
			}
			
			$number++;
		}
		
		// Ajout des nouveaux NRC
		for ($n=1; $n<11; $n++) {
			$arguments['PostString'] .= "&RSTS_IN=RW";
			if (isset($nrc_array[($n-1)])) {
				$arguments['PostString'] .= "&CRN_IN=".$nrc_array[($n-1)];
			} else {
				$arguments['PostString'] .= "&CRN_IN=";
			}
			$arguments['PostString'] .= "&assoc_term_in=";
			$arguments['PostString'] .= "&start_date_in=";
			$arguments['PostString'] .= "&end_date_in=";
		}
		
		$data = substr($response, strpos($response, '<H3>Ajout de sections de cours à la feuille de travail</H3>'));
		$data = substr($data, 0, strpos($data, '<!--  ** START OF twbkwbis.P_CloseDoc **  -->'));
		$data = substr($data, strpos($data, '<INPUT TYPE="hidden" NAME="regs_row"'));
				
		$field = substr($data, strpos($data, ' NAME="regs_row"'), 200);
		$field = substr($field, strpos($field, ' VALUE="')+8);
		$field = substr($field, 0, strpos($field, '"'));
		$arguments['PostString'] .= "&regs_row=".$field;
		
		$field = substr($data, strpos($data, ' NAME="wait_row"'), 200);
		$field = substr($field, strpos($field, ' VALUE="')+8);
		$field = substr($field, 0, strpos($field, '"'));
		$arguments['PostString'] .= "&wait_row=".$field;
		
		$field = substr($data, strpos($data, ' NAME="add_row"'), 200);
		$field = substr($field, strpos($field, ' VALUE="')+8);
		$field = substr($field, 0, strpos($field, '"'));
		$arguments['PostString'] .= "&add_row=".$field;
		
		$arguments['PostString'] .= "&REG_BTN=Soumettre les modifications";
				
		$this->CI->lfetch->Close();
		
		$this->CI->lfetch->request_method="POST";
		$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->CI->lfetch->Open($arguments);
		
		// Envoi du formulaire
		unset($arguments["PostValues"]);
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckcoms.P_Regs";

		$error=$this->CI->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->CI->lfetch->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		if ($this->CI->lfetch->response_status==404) {
			error_log(__LINE__);
			return (false);
		} else {
			// Analyse de la réponse
			$data = substr($response, strpos($response, '<TABLE  CLASS="datadisplaytable" SUMMARY="Horaire actuel">')+20);
			$data = substr($data, strpos($data, '</TR>')+5);
			$data = substr($data, 0, strpos($data, '<TABLE  CLASS="datadisplaytable"'));
			
			$data = explode("<TR>", $data);
			
			$coursesStatus = array();
			$number = 0;
			foreach ($data as $line) {
				if ($number!=0) {
					$field = substr($line, strpos($line, ' NAME="CRN_IN"'), 200);
					$field = substr($field, strpos($field, ' VALUE="')+8);
					$field = substr($field, 0, strpos($field, '"'));
					$nrc = $field;
					
					reset($nrc_array);
					foreach ($nrc_array as $nrc2) {
						if ($nrc==$nrc2) {
							$coursesStatus[] = array(
													 'nrc'			=>	$nrc,
													 'registered'	=>	1
													 );
							break;
						}
					}
				}
				
				$number++;
			}
			
			$data = substr($response, strpos($response, 'Nombre de crédits inscrits'));
			$data = substr($data, 0, strpos($data, '<H3>Ajout de sections de cours à la feuille de travail</H3>'));
			
			if (strpos($data, '<TABLE  CLASS="datadisplaytable" SUMMARY="Cette table de disposition sert à présenter les erreurs d\'inscription.">')>1) {
				// Analyse des erreurs d'inscription
				$data = substr($data, strpos($data, '<TABLE  CLASS="datadisplaytable" SUMMARY="Cette table de disposition sert à présenter les erreurs d\'inscription.">'));
				$data = substr($data, 0, strrpos($data, '</TABLE>'));
				
				$data = explode("<TR>", $data);
			
				$number = 0;
				foreach ($data as $line) {
					if ($number>1) {
						$line = explode("</TD>", $line);
						
						$nrc = trim(strip_tags($line[1]));
						$error_message = trim(strip_tags($line[0]));
						
						reset($nrc_array);
						foreach ($nrc_array as $nrc2) {
							if ($nrc==$nrc2) {
								$coursesStatus[] = array(
														 'nrc'			=>	$nrc,
														 'registered'	=>	0,
														 'error'		=>	$error_message
														 );
								break;
							}
						}
					}
					
					$number++;
				}
			}
			
			return ($coursesStatus);
		}
	}
	
	public function removeCourse ($nrc, $semester) {
		$this->CI->lfetch->cookies = $_SESSION['cookies'];
		$this->CI->lfetch->debug = $this->debug;
		
		if ($_SESSION['referer']=='') {
			$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
		} else {
			$this->CI->lfetch->referer = $_SESSION['referer'];
		}
		
		$this->CI->lfetch->protocol="https";
		
		$arguments['HostName'] = "capsuleweb.ulaval.ca";
		$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu";
		
		$error=$this->CI->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->CI->lfetch->Close();
		
		$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->CI->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->CI->lfetch->Close();
		
		$this->CI->lfetch->request_method="POST";
		
		$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->CI->lfetch->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'term_in'				=>	$semester
			  );
		
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->CI->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->CI->lfetch->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		// Analyse de la page
		$data = substr($response, strpos($response, '<TABLE  CLASS="datadisplaytable" SUMMARY="Horaire actuel">')+20);
		$data = substr($data, strpos($data, '</TR>')+5);
		$data = substr($data, 0, strpos($data, '<TABLE  CLASS="datadisplaytable"'));
		
		$data = explode("<TR>", $data);
		
		$arguments['PostString'] = "term_in=".$semester."&RSTS_IN=DUMMY&assoc_term_in=DUMMY&CRN_IN=DUMMY&start_date_in=DUMMY&end_date_in=DUMMY&SUBJ=DUMMY&CRSE=DUMMY&SEC=DUMMY&LEVL=DUMMY&CRED=DUMMY&GMOD=DUMMY&TITLE=DUMMY&MESG=DUMMY&REG_BTN=DUMMY";
		
		$number = 0;
		foreach ($data as $line) {
			if ($number!=0) {
				$new = array();
				
				$field = substr($line, strpos($line, ' NAME="CRN_IN"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$current_nrc = $field;
				
				$field = substr($line, strpos($line, ' NAME="MESG"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['MESG'] = $field;
				
				if ($current_nrc!=$nrc) {
					$new['RSTS_IN'] = '';
				} else {
					$new['RSTS_IN'] = 'DW';
				}
				
				$field = substr($line, strpos($line, ' NAME="assoc_term_in"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['assoc_term_in'] = $field;
				
				$new['CRN_IN'] = $current_nrc;
				
				$field = substr($line, strpos($line, ' NAME="start_date_in"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['start_date_in'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="end_date_in"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['end_date_in'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="SUBJ"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['SUBJ'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="CRSE"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['CRSE'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="SEC"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['SEC'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="LEVL"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['LEVL'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="CRED"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['CRED'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="GMOD"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['GMOD'] = $field;
				
				$field = substr($line, strpos($line, ' NAME="TITLE"'), 200);
				$field = substr($field, strpos($field, ' VALUE="')+8);
				$field = substr($field, 0, strpos($field, '"'));
				$new['TITLE'] = $field;
				
				foreach ($new as $name => $value) {
					$arguments['PostString'] .= "&".$name."=".urlencode($value);
				}
			}
			
			$number++;
		}
		
		// Ajout des nouveaux NRC
		for ($n=1; $n<11; $n++) {
			$arguments['PostString'] .= "&RSTS_IN=RW";
			$arguments['PostString'] .= "&CRN_IN=";
			$arguments['PostString'] .= "&assoc_term_in=";
			$arguments['PostString'] .= "&start_date_in=";
			$arguments['PostString'] .= "&end_date_in=";
		}
		
		$data = substr($response, strpos($response, '<H3>Ajout de sections de cours à la feuille de travail</H3>'));
		$data = substr($data, 0, strpos($data, '<!--  ** START OF twbkwbis.P_CloseDoc **  -->'));
		$data = substr($data, strpos($data, '<INPUT TYPE="hidden" NAME="regs_row"'));
				
		$field = substr($data, strpos($data, ' NAME="regs_row"'), 200);
		$field = substr($field, strpos($field, ' VALUE="')+8);
		$field = substr($field, 0, strpos($field, '"'));
		$arguments['PostString'] .= "&regs_row=".$field;
		
		$field = substr($data, strpos($data, ' NAME="wait_row"'), 200);
		$field = substr($field, strpos($field, ' VALUE="')+8);
		$field = substr($field, 0, strpos($field, '"'));
		$arguments['PostString'] .= "&wait_row=".$field;
		
		$field = substr($data, strpos($data, ' NAME="add_row"'), 200);
		$field = substr($field, strpos($field, ' VALUE="')+8);
		$field = substr($field, 0, strpos($field, '"'));
		$arguments['PostString'] .= "&add_row=".$field;

		$arguments['PostString'] .= "&REG_BTN=Soumettre les modifications";
				
		$this->CI->lfetch->Close();
		
		$this->CI->lfetch->request_method="POST";
		
		$this->CI->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->CI->lfetch->Open($arguments);
		
		// Envoi du formulaire
		unset($arguments["PostValues"]);
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckcoms.P_Regs";

		$error=$this->CI->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->CI->lfetch->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		if ($this->CI->lfetch->response_status==404) {
			error_log(__LINE__);
			return (false);
		} else {
			// Analyse de la réponse
			$data = substr($response, strpos($response, '<TABLE  CLASS="datadisplaytable" SUMMARY="Horaire actuel">')+20);
			$data = substr($data, strpos($data, '</TR>')+5);
			$data = substr($data, 0, strpos($data, '<TABLE  CLASS="datadisplaytable"'));
			
			$data = explode("<TR>", $data);
			
			$number = 0;
			$found = 0;
			foreach ($data as $line) {
				if ($number!=0) {
					$field = substr($line, strpos($line, ' NAME="CRN_IN"'), 200);
					$field = substr($field, strpos($field, ' VALUE="')+8);
					$field = substr($field, 0, strpos($field, '"'));
					$current_nrc = $field;
					
					if ($current_nrc==$nrc) {
						$found = 1;
						break;
					}
				}
				
				$number++;
			}
			
			if ($found==0) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	public function fetchCourse ($code, $semester) {
		$this->CI->lfetch->cookies = $_SESSION['cookies'];
		$this->CI->lfetch->debug = $this->debug;
		$code = explode("-", strtoupper($code));
		
		$this->CI->lfetch->protocol="https";
		
		$arguments['HostName'] = "capsuleweb.ulaval.ca";
		$arguments["RequestURI"] = "/pls/etprod7/bwckctlg.p_disp_course_detail?cat_term_in=".$semester."&subj_code_in=".$code[0]."&crse_numb_in=".$code[1];
		
		//echo "<H2><LI>Opening connection to:</H2>\n<PRE>",HtmlEntities($arguments["HostName"]),"</PRE>\n";
		//flush();
		$error=$this->CI->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log('Ligne '.__LINE__);
			return (false);
		}
		
		//echo "<H2><LI>Sending request for page:</H2>\n<PRE>";
		//echo HtmlEntities($arguments["RequestURI"]),"\n";
		$error=$this->CI->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log('Ligne '.__LINE__);
			return (false);
		}
			
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne '.__LINE__);
			return (false);
		}

		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		//error_log($response);
		$this->CI->lfetch->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		// Vérification de l'existence du cours
		if (strpos($response, "Aucun cours à afficher")>1 || trim($response)=='') {
			return (false);
		} else {
			// Sélection des informations du cours
			$course = array();
			
			$title = substr($response, strpos($response, "<TD CLASS=\"nttitle\" scope=\"colgroup"));
			$title = substr($title, strpos($title, "-")+2);
			$course['title'] = trim(strip_tags(substr($title, 0, strpos($title, "</TD>"))));
			
			$description = substr($response, strpos($response, "<TD CLASS=\"ntdefault\">"));
			$course['description'] = str_replace("", "'", trim(strip_tags(substr($description, 0, strpos($description, "<BR>")))));
			
			$data = substr($response, strpos($response, "<TD CLASS=\"ntdefault\">"));
			$data = substr($data, strpos($data, "<BR>")+4);
			$data = explode("<BR>", substr($data, 0, strpos($data, "<SPAN")));
			//print_r($data);
			if (strpos($data[0], " OR ")>1) {
				$data[0] = substr($data[0], strpos($data[0], " OR ")+4);
				$course['credits'] = trim(strip_tags(substr($data[0], 0, strpos($data[0], ",")+1)));
			} else {
				$course['credits'] = trim(strip_tags(substr($data[0], 0, strpos($data[0], ",")+1)));
			}
			$course['hours_theory'] = trim(strip_tags(substr($data[1], 0, strpos($data[1], ",")+1)));
			$course['hours_lab'] = trim(strip_tags(substr($data[2], 0, strpos($data[2], ",")+1)));
			$course['hours_other'] = 0;
			if (isset($data[3])) $course['hours_other'] = trim(strip_tags(substr($data[3], 0, strpos($data[3], ",")+1)));
			
			$cycle = substr($response, strpos($response, "<TD CLASS=\"ntdefault\">"));
			$cycle = substr($cycle, strpos($cycle, "Cycle(s): </SPAN>")+17);
			$cycle = trim(strip_tags(substr($cycle, 0, strpos($cycle, "<BR>"))));
			switch ($cycle) {
				case 'Premier cycle':
					$course['cycle'] = 1;
				break;
				case 'Deuxième cycle':
					$course['cycle'] = 2;
				break;
				case 'Troisième cycle':
					$course['cycle'] = 3;
				break;
			}
			
			$faculty = substr($response, strpos($response, "Faculté: ")+9);
			$course['faculty'] = trim(strip_tags(substr($faculty, 0, strpos($faculty, "<BR>"))));
			
			$department = substr($response, strpos($response, "Département: ")+13);
			$course['department'] = trim(strip_tags(substr($department, 0, strpos($department, "<BR>"))));
			
			$restrictions = substr($response, strpos($response, "Restrictions:")+13);
			$course['restrictions'] = trim(strip_tags(substr($restrictions, 0, strpos($restrictions, "<SPAN"))));
			
			if (strpos($response, "Préalables:")>1) {
				$prerequisites = substr($response, strpos($response, "Préalables:")+12);
				$course['prerequisites'] = trim(strip_tags(substr($prerequisites, 0, strpos($prerequisites, "</TD>"))));
			}
			
			$data = substr($response, strpos($response, "<TD CLASS=\"ntdefault\">"));
			$data = substr($data, strpos($data, "<SPAN class=fieldlabeltext>Mode d'enseignement: </SPAN>"));
			$data = substr($data, 0, strpos($data, "<BR>"));
			
			?><div style="border-bottom: 1px dotted silver; padding: 5px; font-family: Helvetica; font-size: 10pt;"><strong><?php echo $code[0]."-".$code[1]; ?>&nbsp;&mdash;&nbsp;<?php echo $course['title']; ?></strong></div><?php

			if (strpos($data, "HREF=")) {
				$course['av'.$semester] = '1';
				$links = array();
				
				// Cours disponible
				if (strpos($data, "</A>, <A")) {
					// 2 modes d'enseignement
					$link = substr($data, strpos($data, "HREF=")+6);
					$link = str_replace("&amp;", "&", substr($link, 0, strpos($link, "\"")));
					$links[] = $link;
					
					$link = substr($data, strpos($data, ">, ")+2);
					$link = substr($link, strpos($link, "HREF=")+6);
					$link = str_replace("&amp;", "&", substr($link, 0, strpos($link, "\"")));
					$links[] = $link;
				} else {
					$link = substr($data, strpos($data, "HREF=")+6);
					$link = str_replace("&amp;", "&", substr($link, 0, strpos($link, "\"")));
					$links[] = $link;
				}
				
				foreach ($links as $link) {
					$this->CI->lfetch->referer = "https://capsuleweb.ulaval.ca/pls/etprod7/bwckctlg.p_disp_course_detail?cat_term_in=".$semester."&subj_code_in=".$code[0]."&crse_numb_in=".$code[1];
				
					$this->CI->lfetch->protocol="https";
					
					$arguments['HostName'] = "capsuleweb.ulaval.ca";
					$arguments["RequestURI"] = $link;
			
					$error=$this->CI->lfetch->Open($arguments);
				
					if ($error!="") {
						error_log('Ligne '.__LINE__);
						return (false);
					}
					
					$error=$this->CI->lfetch->SendRequest($arguments);
				
					if ($error!="") {
						error_log('Ligne '.__LINE__);
						return (false);
					}
						
					$headers=array();
					$error=$this->CI->lfetch->ReadReplyHeaders($headers);
					if ($error!="") {
						error_log('Ligne '.__LINE__);
						return (false);
					}
			
					$error = $this->CI->lfetch->ReadWholeReplyBody($body);
					$response = utf8_encode(html_entity_decode($body));
									
					$this->CI->lfetch->Close();
					
					if (!$this->checkPage($response)) return (false);
					
					unset($arguments["PostValues"]);
					$this->CI->lfetch->request_method = "GET";
					
					$data = substr($response, strpos($response, "<CAPTION class=\"captiontext\">Groupes trouvés</CAPTION>"));
					$data = substr($data, strpos($data, "<TH CLASS=\"ddlabel\" scope=\"row")+20);
					$data = substr($data, 0, strpos($data, "<TD CLASS=\"ntdefault\">"));
					
					$lines = explode("<TH CLASS=\"ddlabel\" scope=\"row", $data);
					
					$classes = array();
					
					// Suppression des classes déjà enregistrées pour ce semestre
					$this->CI->mRegistration->deleteCourseClasses($code[0]."-".$code[1], $semester);
					
					foreach ($lines as $line) {
						$class = array();
						
						$title = substr($line, strpos($line, "<A HREF="));
						$title = trim(strip_tags(substr($title, 0, strpos($title, "</A>"))));
						$title = explode(" - ", $title);
						
						//$course['code'] = trim(str_replace(" ", "-", $title[2]));
						$class['nrc'] = trim($title[1]);
						if (count($title) > 4) {
							$class['nrc'] = trim($title[2]);
						} else {
							$class['nrc'] = trim($title[1]);
						}
						$class['idcourse'] = $code[0]."-".$code[1];
						//$course['title'] = trim($title[0]);
						//$course['letter'] = trim($title[3]);
						
						$notes = substr($line, strpos($line, "<TD CLASS=\"dddefault\">"));
						$class['notes'] = trim(strip_tags(substr($notes, 0, strpos($notes, "<BR>"))));
						
						$campus = substr($line, strpos($line, "Campus: ")+8);
						$class['campus'] = str_replace("", "'", trim(strip_tags(substr($campus, 0, strpos($campus, "<BR>")))));
						
						$timetable = substr($line, strpos($line, "Horaires prévus"));
						$timetable = substr($timetable, 0, strpos($timetable, "</TABLE>"));
						$timetable = explode("<TR>", $timetable);
						
						$number2 = 0;
						$class['timetable'] = array();
						$class['semester'] = $semester;
						
						foreach ($timetable as $line2) {
							$class2 = array();
							if ($number2>1) {
								$line2 = explode("</TD>", $line2);
								$class2['type'] = trim(strip_tags($line2[0]));
								if (trim(strip_tags($line2[1]))!="ACU" and trim(strip_tags($line2[1]))!='') {
									$hours = explode("-", trim(strip_tags($line2[1])));
									$class2['hour_start'] = trim($hours[0]);
									$class2['hour_end'] = trim($hours[1]);
								}
								$class2['day'] = trim(str_replace("&nbsp;", " ", strip_tags($line2[2])));
								$class2['local'] = trim(strip_tags($line2[3]));
								$days = explode("-", trim(strip_tags($line2[4])));
								$class2['day_start'] = trim(str_replace("/", "", $days[0]));
								$class2['day_end'] = trim(str_replace("/", "", $days[1]));
								$class['teacher'] = trim(str_replace("(P)", "", strip_tags($line2[6])));
								
								$class['timetable'][] = $class2;
							}
							$number2++;
						}
						
						$class['timetable'] = serialize($class['timetable']);
						
						?><div style="border-bottom: 1px dotted silver; padding: 5px; padding-left: 40px; font-family: Helvetica; font-size: 10pt;"><?php echo $class['nrc']; ?></div><?php
						
						// Enregistrement de la classe
						$this->CI->mRegistration->addClass($class);
						
						// Actualisation des places disponibles
						$this->updateClassSpots($class['nrc'], $semester);
					}
				}
			} else {
				$course['av'.$semester] = '0';
			}
			
			// Enregistrement du cours
			$course['id'] = $code[0]."-".$code[1];
			$this->CI->mRegistration->addCourse($course);
			
			return (true);
		}
	}
	
	public function updateClassSpots ($nrc, $semester) {
		$this->CI->lfetch->cookies = $_SESSION['cookies'];
		$this->CI->lfetch->debug = $this->debug;
		
		$this->CI->lfetch->protocol="https";
		
		$arguments['HostName'] = "capsuleweb.ulaval.ca";
		$arguments["RequestURI"] = "/pls/etprod7/bwckschd.p_disp_detail_sched?term_in=".$semester."&crn_in=".$nrc;

		$error=$this->CI->lfetch->Open($arguments);
		if ($error!="") {
			error_log(__FILE__ .' | Ligne '.__LINE__);
			return (false);
		}
		
		$error=$this->CI->lfetch->SendRequest($arguments);
		if ($error!="") {
			error_log(__FILE__ .' | Ligne '.__LINE__);
			return (false);
		}
			
		$headers=array();
		$error=$this->CI->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__FILE__ .' | Ligne '.__LINE__);
			return (false);
		}

		$error = $this->CI->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->CI->lfetch->Close();
		
		if (!$this->checkPage($response)) return (false);
		
		// Analyse du contenu de la page
		$data = substr($response, strpos($response, "Places disponibles"));
		$data = substr($data, 0, strpos($data, "</TABLE>"));
		$data = substr($data, strpos($data, '<SPAN class=fieldlabeltext>Places</SPAN>'));
		$data = substr($data, strpos($data, '<TD'));
		$data = explode("</TD>", $data);
		
		$spots = array();
		$spots['total'] = trim(strip_tags($data[0]));
		$spots['registered'] = trim(strip_tags($data[1]));
		$spots['remaining'] = trim(strip_tags($data[2]));
		
		$spots['waiting_total'] = trim(strip_tags(substr($data[3], strpos($data[3], "<TD"))));
		$spots['waiting_registered'] = trim(strip_tags($data[4]));
		$spots['waiting_remaining'] = trim(strip_tags($data[5]));
		
		$spots['nrc'] = $nrc;
		
		if ($this->CI->mCourses->updateClassSpots($spots)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	private function checkPage ($data) {
		if (!strpos($data, "<TITLE>Connexion utilisateur | Capsule | Université Laval</TITLE>")) {
			return (true);
		} else {
			return (false);
		}
	}
}

?>
<?php

class Capsule {
	private $debug = 0;
    private $fetcher;
    private $domparser;
	public $forceReload = false;
    //private $host = "132.203.189.178";
    private $host = "capsuleweb.ulaval.ca";
    
    public $cookies;
    public $referer;
    public $userName;

	public function __construct( $fetcher, $domparser ) {
        $this->fetcher = $fetcher;
        $this->domparser = $domparser;
    }
	
	// Login to Capsule
	public function login ( $idul, $password ) {
        // Define request parameters
        $this->fetcher->set( array(
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'request_method'=>  'GET'
        ));

        // Define request arguments
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  "/pls/etprod7/twbkwbis.P_WWWLogin"
        );

        // Open connection to remote server
        $error = $this->fetcher->Open( $arguments );
        if ( !empty( $error ) ) {
            if ( $error == '0 could not connect to the host "' . $this->host . '"') {
                sleep(1);

                // Second attempt to connect
                $error = $this->fetcher->Open( $arguments );
                if ( !empty( $error ) ) {
                    if ( $error == '0 could not connect to the host "' . $this->host . '"' )
                        return ( 'server-connection' );
                }
            }
        }

        // Send request data to remote server
        $error = $this->fetcher->SendRequest( $arguments );
        if ( !empty( $error ) ) return false;

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = utf8_encode( html_entity_decode( $response ) );

        // Close remote connection
        $this->fetcher->Close();

        // Check if login form is available
        if ( strpos( $response, '<INPUT TYPE="text" NAME="sid" SIZE="10" MAXLENGTH="8" ID="UserID" >' ) < 1 )
            return('server-unavailable');

        // Change request method to POST
        $this->fetcher->request_method = 'POST';

        // Define request arguments
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  '/pls/etprod7/twbkwbis.P_ValLogin',
            'PostValues'    =>  array(
                'sid' =>  $idul,
                'PIN' =>  $password
            )
        );

        // Open connection to remote server
        $this->fetcher->Open( $arguments );

        // Send request data to remote server
        $error = $this->fetcher->SendRequest( $arguments );
        if ( !empty( $error ) ) return false;

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = utf8_encode( html_entity_decode( $response ) );

        // Close remote connection
        $this->fetcher->Close();

        // Check if provided credentials are accepted by Capsule
        if ( preg_match( '/IDUL ou le NIP sont invalides/' , $response ) ) {
            // Connection failed because of wrong credentials
            return ( 'credentials' );
        } elseif ( strpos( $response, "<meta http-equiv=\"refresh\" content=\"0;url=/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_MainMnu&amp;msg=WELCOME" ) > 1 ) {
            $this->fetcher->SaveCookies($cookies);
            $this->cookies = $cookies;

            // Extract user full name from server response
            $this->userName = substr( $response, strpos( $response, "<meta http-equiv=\"refresh\" content=\"0;url=/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_MainMnu&amp;msg=WELCOME" ) );
            $this->userName = substr( $this->userName, strpos( $this->userName, "WELCOME+" ) + 8 );
            $this->userName = urldecode( substr( $this->userName, 0, strpos( $this->userName, "+bienvenue" ) - 1 ) );

            // Connection to Capsule completed with success
            return ( 'success' );
        } else {
            // Unknown error occurred during login
            return ( 'server-connection' );
        }
	}
	
	// Vérification de l'authentification par WebCT
	public function loginWebCT ($idul, $password) {
		$this->fetcher->debug = $this->debug;
		
		$_SESSION['referer'] = '';
		
		$url="/webct/ticket/ticketLogin?action=print_login&request_uri=/webct/homearea/homearea%3F";
		$this->fetcher->protocol="https";
		
		$arguments['HostName'] = "www.webct.ulaval.ca";
		$arguments["RequestURI"] = $url;
		
		$error=$this->fetcher->Open($arguments);
		if ($error!="") {
			if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
				sleep(1);

                // Deuxième essai
				$error=$this->fetcher->Open($arguments);
				if ($error!="") {
					if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
						return ('server-connection');
					}
				}
			}
		}
		
		$error=$this->fetcher->SendRequest($arguments);
		if ($error!="") {
			return ('server-connection');
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			return ('server-connection');
		}
																				
		$this->fetcher->Close();
					
		$this->fetcher->request_method="POST";
		$this->fetcher->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'WebCT_ID'		=>	$idul,
			  'Password'		=>	$password,
			  'request_uri'		=>	'/webct/homearea/homearea?',
			  'action'			=>	'webform_user'
			  );
		$arguments["RequestURI"] = "/webct/ticket/ticketLogin";
		
		$error=$this->fetcher->SendRequest($arguments);
		if ($error!="") {
			return ('server-connection');
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			return ('server-connection');
		}
		
		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
														
		$this->fetcher->Close();
				
		if (strpos($response, "Erreur: Les informations entrées sont incorrectes.")>1) {
            // Enregistrement du résultat de la requête dans la BD pour débug
            $this->CI->mHistory->saveRequestData($idul, 'login-webct-error-credentials', $response, __FILE__." : ligne ".__LINE__." | ".$error);

            return ('credentials');
		} elseif (strpos($body, "successful login")>1) {
			$url="/webct/homearea/homearea?";
			$this->fetcher->request_method="GET";
			
			$arguments["RequestURI"] = $url;
			
			$error=$this->fetcher->Open($arguments);
			if ($error!="") {
				if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
					sleep(1);

                    // Deuxième essai
					$error=$this->fetcher->Open($arguments);
					if ($error!="") {
						if ($error=='0 could not connect to the host "webct.ulaval.ca"') {
							return ('server-connection');
						}
					}
				}
			}
			
			$error=$this->fetcher->SendRequest($arguments);
			if ($error!="") {
				return ('server-connection');
			}
			
			$headers=array();
			$error=$this->fetcher->ReadReplyHeaders($headers);
			if ($error!="") {
				return ('server-connection');
			}
			
			$error = $this->fetcher->ReadWholeReplyBody($body);
			$response = utf8_encode(html_entity_decode($body));
		
			$this->fetcher->Close();
			$this->fetcher->SaveCookies($cookies);
			
			$this->CI->session->set_userdata('cookies', $cookies);
			
			// Vérification de l'existence de l'utilisateur
			if ($this->CI->mUsers->userExists($idul)===false) {
				$name = substr($body, strpos($body, "<b>Bienvenue,"), 500);
				$name = substr($name, strpos($name, ",")+1);
				$name = trim((urldecode(substr($name, 0, strpos($name, "</b>")))));
				
				// Enregistrement de l'utilisateur
				$user = array(
							  'idul'	=>	$idul,
							  'name'	=>	$name
							  );
				
				$this->CI->mUsers->addUser($user);
			}
			
			return ('success');
		} else {
            // Enregistrement du résultat de la requête dans la BD pour débug
            $this->CI->mHistory->saveRequestData($idul, 'login-webct-error-server-connection', $response, __FILE__." : ligne ".__LINE__." | ".$error);

            //error_log($response);
			return ('server-connection');
		}
	}
	
	// Test de la connexion
	public function testConnection () {		
        $this->fetcher->cookies = $this->CI->session->userdata('capsule_cookies');
        $this->fetcher->debug = $this->debug;

        if ($this->CI->session->userdata('capsule_referer') == '') {
            $this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
        } else {
            $this->fetcher->referer = $this->CI->session->userdata('capsule_referer');
        }

        $this->fetcher->protocol="https";

        $arguments['HostName'] = $this->host;
        $arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu";

        $error=$this->fetcher->Open($arguments);
        if ($error!="") {
            return (false);
        }

        $error=$this->fetcher->SendRequest($arguments);
        if ($error!="") {
            return (false);
        }

        $headers=array();
        $error=$this->fetcher->ReadReplyHeaders($headers);
        if ($error!="") {
            return (false);
        }

        // Vérification de la réponse
        $found = 0;
        for (Reset($headers),$header=0;$header<count($headers);Next($headers),$header++)
        {
            $header_name=Key($headers);

            if ($header_name=='set-cookie') {
                if (is_array($headers[$header_name])) {
                    foreach ($headers[$header_name] as $cookie) {
                        if (preg_match("#SESSID\=;#", $cookie)) {
                            $found = 1;
                            break;
                        }
                    }
                } elseif (preg_match("#SESSID\=;#", $headers[$header_name])) {
                    $found = 1;
                }
            }
        }

        $this->fetcher->Close();

        if ($found == 1 || $error != '') {
            // Reconnexion au serveur
            $this->login($this->CI->session->userdata('pilule_user'), $this->CI->session->userdata('pilule_password'));
        }
	}
	
	// Vérification des blocages
	public function checkHolds () {
        $this->fetcher->cookies = $this->CI->session->userdata('capsule_cookies');
        $this->fetcher->debug = $this->debug;

        if ($this->CI->session->userdata('capsule_referer')=='') {
            $this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
        } else {
            $this->fetcher->referer = $this->CI->session->userdata('capsule_referer');
        }

        $this->fetcher->protocol="https";

        $arguments['HostName'] = $this->host;
        $arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu";
        $error=$this->fetcher->Open($arguments);
        if ($error!="") {
            error_log(__FILE__." : ligne ".__LINE__." | ".$error);
            return (false);
        }

        $error=$this->fetcher->SendRequest($arguments);
        if ($error!="") {
            error_log(__FILE__." : ligne ".__LINE__." | ".$error);
            return (false);
        }

        $headers=array();
        $error=$this->fetcher->ReadReplyHeaders($headers);
        if ($error!="") {
            error_log(__FILE__." : ligne ".__LINE__." | ".$error);
            return (false);
        }

        $this->fetcher->Close();

        $this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu';
        $arguments["RequestURI"] = "/pls/etprod7/bwskoacc.P_ViewHold";
        $error=$this->fetcher->Open($arguments);
        if ($error!="") {
            error_log(__FILE__." : ligne ".__LINE__." | ".$error);
            return (false);
        }

        $error=$this->fetcher->SendRequest($arguments);
        if ($error!="") {
            error_log(__FILE__." : ligne ".__LINE__." | ".$error);
            return (false);
        }

        $headers=array();
        $error=$this->fetcher->ReadReplyHeaders($headers);
        if ($error!="") {
            error_log(__FILE__." : ligne ".__LINE__." | ".$error);
            return (false);
        }

        // Extraction du code source du résultat
        $error = $this->fetcher->ReadWholeReplyBody($body);
        $data = utf8_encode(html_entity_decode($body));

        $this->fetcher->Close();

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
	}

	// Sommaire du dossier étudiant
    public function getStudies ($semester) {
        // Définition des paramètres de la requête
        $this->fetcher->set(array(
            'cookies'       =>  $this->CI->session->userdata('capsule_cookies'),
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'referer'       =>  'https://capsuleweb.ulaval.ca/pls/etprod7/bwskgstu.P_StuInfo',
            'request_method'=>  'POST'
        ));

        // Définition des valeurs du formulaire
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  "/pls/etprod7/bwskgstu.P_StuInfo",
            'PostValues'    =>  array('term_in'	=>	$semester)
        );

        // Ouverture de la connexion
        $this->fetcher->Open($arguments);

        // Envoi du formulaire
        $error = $this->fetcher->SendRequest($arguments);
        if (!empty($error)) return (false);

        // Lecture du contenu de la réponse
        $this->fetcher->ReadWholeReplyBody($response);
        $response = utf8_encode(html_entity_decode($response));

        // Fermeture de la connexion
        $this->fetcher->Close();

        // Vérification des données
        if (!$this->checkPage($response)) return (false);

        // Enregistrement des cookies
        $this->fetcher->SaveCookies($cookies);
        $this->CI->session->set_userdata('capsule_cookies', $cookies);

        // Vérification que le dossier de l'étudiant n'est pas vide
        if (strpos($response, "Il n'existe pas d'informations étudiantes disponibles")) return ('no-info');

        // Analyse des données
        if (strpos($response, "tudes en cours")) {
            // Nettoyage du code HTML
            if (function_exists('tidy_repair_string')) {
                $tidy = tidy_parse_string($response);
                $tidy->cleanRepair();
            } else {
                $tidy = $response;
            }

            // Analyse de la structure DOM de la page
            $this->domparser->load($tidy);
            $tables = $this->domparser->find('table.datadisplaytable');

            // Vérification d'une requête similaire
            $md5 = md5(serialize($tables));
            if ($this->CI->mCache->requestExists('studies', $md5)) {
                if (!$this->forceReload) return (true);
            } else {
                $this->CI->mCache->addRequest('studies', $md5);
            }

            $studies = array( 'empty_data' => false );

            // Recherche des données de fréquentation
            $rows = $tables[0]->find('tr');
            foreach ($rows as $row) {
                $name = str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252'));
                if (isset($row->nodes[3])) $value = html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252');
                switch ($name) {
                    case 'Inscrit pour la session':
                        $studies['registered'] = 0;
                        if ($value == 'Oui') $studies['registered'] = 1;
                    break;
                    case 'Statut':
                        $studies['status'] = $value;
                    break;
                    default:
                        if (strpos($name, 'Première session de fréquentation') !== false) {                            $semester = explode(' ', $value);
                            $studies['first_sem'] = convertSemester($value);
                        } elseif (strpos($name, 'Dernière session de fréquentation') !== false) {
                            $studies['last_sem'] = convertSemester($value);
                        }
                        break;
                }
            }

            $programs = array();
            $program = array();

            // Recherche des programmes d'études
            $rows = $tables[1]->find('tr');
            foreach ($rows as $row) {
                $name = trim(str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')));
                if (isset($row->nodes[3])) $value = html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252');
                switch ($name) {
                    case 'Programme actuel':
                        // Si nouveau programme, le programme en cours est ajouté à la liste
                        if ($program != array()) $programs[] = $program;
                        $program = array();
                        break;
                    case 'Cycle':
                        if ($value == 'Premier cycle') $program['cycle'] = 1;
                        elseif ($value == 'Deuxième cycle') $program['cycle'] = 2;
                        elseif ($value == 'Troisième cycle') $program['cycle'] = 3;
                        break;
                    case 'Programme':
                        $program['name'] = $value;
                        break;
                    case 'Session d\'admission':
                        $program['adm_semester'] = convertSemester($value);
                        break;
                    case 'Session de répertoire':                        $semester = explode(' ', $value);
                        $program['session_repertoire'] = convertSemester($value);
                        break;
                    case 'Type d\'admission':
                        $program['adm_type'] = $value;
                        break;
                    case 'Faculté':
                        $program['faculty'] = $value;
                        break;
                    case 'Majeure':
                        $program['major'] = $value;
                        break;
                    case 'Mineure':
                        $program['minor'] = $value;
                        break;
                    case 'Concentration de majeure':
                        if (!array_key_exists('concentrations', $studies)) $program['concentrations'] = array();
                        $program['concentrations'][] = $value;
                        break;
                    default:
                        if (strpos($name, 'Baccalauréat') !== false || strpos($name, 'Maîtrise') !== false || strpos($name, 'Doctorat') !== false || strpos($name, 'Diplôme') !== false) {
                            $program['diploma'] = $name;
                        }
                        break;
                }
            }

            $programs[] = $program;

            // Vérification des programmes
            foreach ($programs as &$program) {
                if ($program['name'] == 'Programme pré-Banner') {
                    $program = array();
                }
            }

            return (array('programs'=>$programs, 'studies'=>$studies));
        } else {
            // Enregistrement du résultat de la requête dans la BD pour débug
            $this->CI->mHistory->saveRequestData($this->CI->session->userdata('pilule_user'), 'get-studies', $response, __FILE__." : ligne ".__LINE__." | ".$error);

            return (false);
        }
	}
	
	// Rapport de cheminement
	public function getStudiesDetails ($semester, $programs) {
        // Définition des paramètres de la requête
        $this->fetcher->set(array(
            'cookies'       =>  $this->CI->session->userdata('capsule_cookies'),
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'referer'       =>  'https://capsuleweb.ulaval.ca/pls/etprod7/bwskgstu.P_StuInfo',
            'request_method'=>  'POST'
        ));

        // Définition des valeurs du formulaire
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  "/pls/etprod7/bwcksmmt.P_DispPrevEval",
            'PostValues'    =>  array(
                'term_in'	=>	$semester
            )
        );

        // Ouverture de la connexion
        $this->fetcher->Open($arguments);

        // Envoi du formulaire
        $error = $this->fetcher->SendRequest($arguments);
        if (!empty($error)) return (false);

        // Lecture du contenu de la réponse
        $this->fetcher->ReadWholeReplyBody($response);
        $response = utf8_encode(html_entity_decode($response));

        // Fermeture de la connexion
        $this->fetcher->Close();

        // Vérification des données
        if (!$this->checkPage($response)) return (false);

        // Nettoyage du code HTML
        if (function_exists('tidy_repair_string')) {
            $tidy = tidy_parse_string($response);
            $tidy->cleanRepair();
        } else {
            $tidy = $response;
        }

        // Analyse de la structure DOM de la page
        $this->domparser->load($tidy);
        $rows = $this->domparser->find('table.dataentrytable tr');

        // Recherche du lien vers le dernier rapport de cheminement pour chaque programme
        foreach ($programs as &$program) {
            foreach ($rows as $row) {
                $name = trim(str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')));
                if ($name == $program['name']) {
                    // Extraction du lien
                    $links = $row->find('a');
                    $program['link'] = $links[0]->attr['href'];
                    break;
                }
            }

            if (isset($program['link']) and (!empty($program['link']))) {
                // Définition des paramètres de la requête
                $this->fetcher->set(array('request_method' => 'POST'));

                // Attestation de cheminement

                // Définition des valeurs du formulaire
                $arguments["RequestURI"] = "/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption";
                $arguments["PostValues"] = array(
                    'request_no'		=>	substr($program['link'], strpos($program['link'], "_no=")+4),
                    'program_summary'	=>	'1'
                );

                // Ouverture de la connexion
                $this->fetcher->Open($arguments);

                // Envoi du formulaire
                $error = $this->fetcher->SendRequest($arguments);
                if (!empty($error)) return (false);

                // Lecture du contenu de la réponse
                $this->fetcher->ReadWholeReplyBody($response);
                $details1 = utf8_encode(html_entity_decode($response, ENT_COMPAT, 'cp1252'));

                // Fermeture de la connexion
                $this->fetcher->Close();

                // Vérification des données
                if (!$this->checkPage($details1)) return (false);

                // Rapport détaillé

                // Sélection des informations dans les rapports
                // Nettoyage du code HTML
                if (function_exists('tidy_repair_string')) {
                    $tidy = tidy_parse_string($details1);
                    $tidy->cleanRepair();
                } else {
                    $tidy = $response;
                }

                // Analyse de la structure DOM de la page
                $this->domparser->load($tidy);
                $tables = $this->domparser->find('table.datadisplaytable');

                // Vérification d'une requête similaire
                $md5 = md5(serialize($tables));
                if ($this->CI->mCache->requestExists('studies-details-program-'.md5($program['name']), $md5)) {
                    $studies = true;
                    //$program = true;
                    if (!$this->forceReload) continue;
                } else {
                    $this->CI->mCache->addRequest('studies-details-program-'.md5($program['name']), $md5);
                }

                // Tri des données
                $studies = array();

                $rows = $tables[0]->find('tr');
                foreach ($rows as $row) {
                    $name = trim(str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')));
                    if (isset($row->nodes[3])) $value = html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252');
                    switch ($name) {
                        case 'Code permanent':
                            $studies['code_permanent'] = trim(str_replace(' ', '', $value));
                            break;
                    }

                    if (count($row->nodes) > 5) {
                        $name = trim(str_replace(':', '', html_entity_decode($row->nodes[5]->text(), ENT_COMPAT, 'cp1252')));
                        if (isset($row->nodes[7])) $value = html_entity_decode($row->nodes[7]->text(), ENT_COMPAT, 'cp1252');
                        switch ($name) {
                            case 'Session d\'évaluation':
                                $program['session_evaluation'] = convertSemester($value);
                                break;
                            case 'Date d\'obtention du diplôme':
                                $program['date_diplome'] = trim(str_replace('/', '', $value));
                                break;
                            case 'Date de l\'attestation':
                                $program['date_attestation'] = trim(str_replace('/', '', $value));
                                break;
                        }
                    }
                }

                $rows = $tables[1]->find('tr');
                foreach ($rows as $row) {
                    $name = str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252'));
                    if (isset($row->nodes[3])) $value = html_entity_decode($row->nodes[3]->text());
                    if (isset($row->nodes[5])) $value2 = html_entity_decode($row->nodes[5]->text());
                    if (isset($row->nodes[7])) $value3 = html_entity_decode($row->nodes[7]->text());
                    if (isset($row->nodes[9])) $value4 = html_entity_decode($row->nodes[9]->text());
                    if (isset($row->nodes[11])) $value5 = html_entity_decode($row->nodes[11]->text());
                    switch ($name) {
                        case 'Total exigé':
                            $program['requirements'] = $value;
                            $program['credits_program'] = (int)$value2;
                            $program['credits_used'] = (int)$value3;
                            $program['courses_program'] = (int)$value4;
                            $program['courses_used'] = (int)$value5;
                            break;
                        case 'Reconnaissance d\'acquis':
                            $program['credits_admitted'] = (int)$value3;
                            $program['courses_admitted'] = (int)$value5;
                            break;
                        case 'Moyenne de cheminement':
                            $program['gpa_overall'] = str_replace(',', '.', $value3);
                            break;
                        default:
                            if (isset($row->nodes[3]) and strpos(html_entity_decode($row->nodes[3]->text()), 'Moyenne de programme') !== false) {
                                $program['gpa_program'] = str_replace(',', '.', trim(str_replace(' ', '', substr(html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252'), strpos(html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252'), ':')+1))));
                            }
                    }
                }

                $sections = array();
                $section = array();
                $section_number = 1;

                for ($i = 2; $i < count ($tables); $i++) {
                    $rows = $tables[$i]->find('tr');
                    foreach ($rows as $row) {
                        $name = str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252'));
                        if ($name == 'Bloc') {
                            // Reset courses fetching
                            $check_courses = false;

                            // Ajout de la section précédente
                            if (!empty($section)) $sections[] = $section;

                            // Add section
                            $section = array();
                            $section['title'] = html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252');
                            $section['title'] = trim(substr($section['title'], 0, strrpos($section['title'], ' - ')));
                            $section['courses'] = array();
                            if (strpos($section['title'], " ( ")>-1) {
                                $section['credits'] = trim(substr($section['title'], strrpos($section['title'], " ( ")+3));
                                $section['credits']	= (int)substr($section['credits'], 0, strpos($section['credits'], ","));
                                $section['title'] = trim(substr($section['title'], 0, strrpos($section['title'], " ( ")));
                            }
                            $section['number'] = $section_number;

                            $section_number++;
                        } elseif ($name == 'Cours') {
                            $courses = array();
                            $check_courses = true;
                        } elseif ($name == 'Cours échoués') {
                            $check_courses = false;

                            // Ajout de la section précédente
                            if (!empty($section)) $sections[] = $section;

                            $section = array('title' => 'Cours échoués', 'number' => $section_number);
                            $section_number++;
                        } elseif (trim($name) != '') {
                            if ($check_courses) {
                                $course = array(
                                    'code'  =>  strtoupper(trim(html_entity_decode($row->nodes[1]->text()) . '-' . html_entity_decode($row->nodes[3]->text()))),
                                    'title'     =>  trim(html_entity_decode($row->nodes[5]->text(), ENT_COMPAT, 'cp1252')),
                                    'semester'  =>  trim(str_replace(' ', '', html_entity_decode($row->nodes[7]->text(), ENT_COMPAT, 'cp1252'))),
                                    'credits'   =>  (int)trim(str_replace('cr.', '', html_entity_decode($row->nodes[9]->text()))),
                                    'note'      =>  trim(str_replace('*', '', html_entity_decode($row->nodes[11]->text()))),
                                );

                                if (!empty($course['semester'])) {
                                    $semester = explode(' ', $course['semester']);
                                    if (isset($semester[1])) {
                                        $course['semester'] = convertSemester($course['semester']);
                                    }
                                }

                                $courses[] = $course;
                            }
                        } else {
                            if ($check_courses) {
                                $section['courses'] = $courses;

                                $courses = array();
                                $check_courses = false;
                            }
                        }
                    }
                }

                // Ajout de la section précédente
                $section['courses'] = $courses;
                if (!empty($section)) $sections[] = $section;

                // Enregistrement des sections de cours
                $program['sections'] = $sections;
            }
        }

        return (array('studies' => $studies, 'programs' => $programs));
	}
	
	
	// Relevé de notes
	public function getReport () {
        // Définition des paramètres de la requête
        $this->fetcher->set(array(
            'cookies'       =>  $this->CI->session->userdata('capsule_cookies'),
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'referer'       =>  'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu',
            'request_method'=>  'POST'
        ));

        // Définition des valeurs du formulaire
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  "/pls/etprod7/bwskotrn.P_ViewTran",
            'PostValues'    =>  array(
                'levl'	=>	'1',
                'tprt'	=>	'WEB'
            )
        );

        // Ouverture de la connexion
        $this->fetcher->Open($arguments);

        // Envoi du formulaire
        $error = $this->fetcher->SendRequest($arguments);
        if (!empty($error)) return (false);

        // Lecture du contenu de la réponse
        $this->fetcher->ReadWholeReplyBody($response);
        $response = (html_entity_decode($response, ENT_COMPAT, 'cp1252'));

        // Fermeture de la connexion
        $this->fetcher->Close();

        // Vérification des données
        if (!$this->checkPage($response)) return (false);

        // Nettoyage du code HTML
        if (function_exists('tidy_repair_string')) {
            $tidy = tidy_parse_string($response);
            $tidy->cleanRepair();
        } else {
            $tidy = $response;
        }

        // Analyse de la structure DOM de la page
        $this->domparser->load($tidy);
        $table = $this->domparser->find('table.datadisplaytable');

        // Vérification d'une requête similaire
        $md5 = md5(serialize($table));
        if ($this->CI->mCache->requestExists('studies-report', $md5)) {
            if (!$this->forceReload) return (true);
        } else {
            $this->CI->mCache->addRequest('studies-report', $md5);
        }

        // Analyse des données
        $student = array();
        $programs = array();
        $report = array();
        $semesters = array();
        $admitted_sections = array();

        $check_programs = false;
        $check_courses = false;
        $check_admitted = false;
        $check_semesters = false;

        $rows = $table[0]->find('tr');
        foreach ($rows as $row) {
            $name = trim(str_replace(':', '', utf8_encode(html_entity_decode($row->nodes[1]->text()))));
            if (isset($row->nodes[3])) $value = html_entity_decode($row->nodes[3]->text());
            if (isset($row->nodes[5])) $value2 = html_entity_decode($row->nodes[5]->text());
            if (isset($row->nodes[7])) $value3 = html_entity_decode($row->nodes[7]->text());
            if (isset($row->nodes[9])) $value4 = html_entity_decode($row->nodes[9]->text());
            if (isset($row->nodes[11])) $value5 = html_entity_decode($row->nodes[11]->text());
            switch ($name) {
                case 'Jour de naissance':
                    $student['birthday'] = str_replace('É', 'é', str_replace('È', 'è', str_replace('Û', 'û', utf8_encode(trim(strtolower($value))))));
                    break;
                case 'No de dossier':
                    $student['da'] = trim(str_replace(' ', '', $value));
                    break;
                case 'Dernier rendement universitaire':
                    break;
                case 'Matière':
                    break;
                case 'Session':
                case 'Session actuelle':
                    if ($check_courses) {
                        $semester['credits_registered'] = (int)trim(html_entity_decode($row->nodes[3]->text()));
                        $semester['credits_done'] = (int)trim(html_entity_decode($row->nodes[7]->text()));
                        $semester['credits_gpa'] = (int)trim(html_entity_decode($row->nodes[9]->text()));
                        $semester['points'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[11]->text())));
                        $semester['gpa'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[13]->text())));
                    }
                    break;
                case 'Cumul':
                    if ($check_courses) {
                        $semester['cumulative_gpa'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[13]->text())));
                        if (!empty($semester)) $semesters[] = $semester;
                        $semester = array();
                        $check_courses = false;
                    }
                    break;
                case 'Observation sur le cycle':
                    $report['notes'] = trim(html_entity_decode($row->nodes[3]->text()));
                    break;
                case 'Université Laval':
                    $report['credits_registered'] = (int)trim(html_entity_decode($row->nodes[3]->text()));
                    $report['credits_done'] = (int)trim(html_entity_decode($row->nodes[7]->text()));
                    $report['credits_gpa'] = (int)trim(html_entity_decode($row->nodes[9]->text()));
                    $report['points'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[11]->text())));
                    $report['ulaval_gpa'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[13]->text())));
                    break;
                case 'Reconnaissance des acquis':
                    $report['credits_admitted'] = (int)trim(html_entity_decode($row->nodes[3]->text()));
                    $report['credits_admitted_done'] = (int)trim(html_entity_decode($row->nodes[7]->text()));
                    $report['credits_admitted_gpa'] = (int)trim(html_entity_decode($row->nodes[9]->text()));
                    $report['credits_admitted_points'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[11]->text())));
                    $report['gpa_admitted'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[13]->text())));
                    break;
                case 'Total':
                    $report['gpa_cycle'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[13]->text())));
                    break;
                default:
                    if ((!empty($name)) and (!$check_programs) and substr($name, 0, 15) == 'PROGRAMME(S) FR') {
                        $check_programs = true;
                        $program = array();
                    } elseif (strpos($name, 'DITS DE L\'UNIVERSIT') !== false) {
                            $check_admitted = false;
                            $check_programs = false;
                            $check_semesters = true;
                            $semester = array();
                    } elseif (strpos($name, 'BILAN DU RELEV') !== false) {
                        $check_admitted = false;
                        $check_programs = false;
                        $check_semesters = false;
                    } elseif ((!empty($name)) and $check_semesters and strlen($name) > 2) {
                        if (count($row->nodes) < 5) {
                            if (strpos($name, 'Totaux de session') !== false) {
                                $check_courses = false;
                            } else {
                                // Ajout du programme précédent
                                if (!empty($semester)) $semesters[] = $semester;

                                $semester = array();

                                $semester['semester'] = convertSemester(trim($name));
                                $semester['courses'] = array();
                                $check_courses = true;
                            }
                        } else {
                            $course = array(
                                'code'      =>  strtoupper(trim(utf8_encode(html_entity_decode($row->nodes[1]->text())) . '-' . html_entity_decode($row->nodes[3]->text()))),
                                'cycle'     =>  (isset($row->nodes[5])) ? (int)trim(html_entity_decode($row->nodes[5]->text())): 0,
                                'title'     =>  (isset($row->nodes[7])) ? trim(utf8_encode(html_entity_decode($row->nodes[7]->text()))): 0,
                                'note'      =>  (isset($row->nodes[9])) ? trim(str_replace('*', '', utf8_encode(html_entity_decode($row->nodes[9]->text())))): 0,
                                'credits'   =>  (isset($row->nodes[11])) ? (int)trim(str_replace('cr.', '', html_entity_decode($row->nodes[11]->text()))): 0,
                                'points'    =>  (isset($row->nodes[13])) ? str_replace(',', '.', trim(html_entity_decode($row->nodes[13]->text()))): 0,
                                'reprise'   =>  (isset($row->nodes[15])) ? trim(utf8_encode(html_entity_decode($row->nodes[15]->text()))): 0,
                            );

                            $semester['courses'][] = $course;
                        }
                    } elseif ((!empty($name)) and $check_programs) {
                        switch($name) {
                            case 'En cheminement':
                                // Ajout du programme précédent
                                if (!empty($program)) $programs[] = $program;

                                $program = array();
                                $program['concentrations'] = array();
                                break;
                            case 'Diplôme obtenu':
                                // Ajout du programme précédent
                                if (!empty($program)) $programs[] = $program;

                                $program = array(
                                    'date_diplome'  =>  trim(str_replace('/', '', $value3)),
                                    'credits'       =>  (int)trim(substr($value4, 0, strpos($value4, ' ')))
                                );
                                $program['concentrations'] = array();
                                break;
                            case 'Programme':
                                $program['full_name'] = utf8_encode(trim($value));
                                break;
                            case 'Fréquentation':
                                $program['attendance'] = utf8_encode(trim($value));
                                break;
                            case 'Concentration':
                                $program['concentrations'][] = utf8_encode(trim($value));
                                break;
                            case 'Majeure':
                                $program['major'] = utf8_encode(trim($value));
                                break;
                            case 'Mineure':
                                $program['minor'] = utf8_encode(trim($value));
                                break;

                            default:
                                if (strpos($name, 'RECONNAISSANCE DES ACQUIS') !== false) {
                                    $check_programs = false;

                                    // Ajout du programme précédent
                                    if (!empty($program)) $programs[] = $program;

                                    $check_admitted = true;
                                    $admitted_section = array();
                                }
                                break;
                        }
                    } elseif ((!empty($name)) and $check_admitted and strlen($name) > 2) {
                        if (($name) != 'Matière') {
                            if (count($row->nodes) < 6) {
                                // Ajout de la section précédente
                                if (!empty($admitted_section)) $admitted_sections[] = $admitted_section;

                                $admitted_section = array(
                                    'period'    =>  utf8_encode($name),
                                    'title'     =>  utf8_encode(trim($value)),
                                    'courses'   =>  array()
                                );
                            } elseif (!empty($name)) {
                                $course = array(
                                    'code'  =>  strtoupper(trim(html_entity_decode($row->nodes[1]->text()) . '-' . html_entity_decode($row->nodes[3]->text()))),
                                    'title'     =>  trim(utf8_encode(html_entity_decode($row->nodes[5]->text()))),
                                    'note'      =>  trim(str_replace('*', '', html_entity_decode($row->nodes[7]->text()))),
                                    'credits'   =>  (int)trim(str_replace('cr.', '', html_entity_decode($row->nodes[9]->text()))),
                                    'points'    =>  (isset($row->nodes[11])) ? str_replace(',', '.', trim(str_replace('*', '', html_entity_decode($row->nodes[11]->text())))): 0,
                                    'reprise'   =>  (isset($row->nodes[13])) ? trim(str_replace('*', '', utf8_encode(html_entity_decode($row->nodes[13]->text())))): 0,
                                );

                                $admitted_section['courses'][] = $course;
                            }
                        }
                    } elseif (strlen($name) < 3 and $check_admitted) {
                        if (isset($row->nodes[3]) and trim(utf8_encode(html_entity_decode($row->nodes[3]->text()))) != 'Crédits obtenus') {
                            $admitted_section['credits_admitted'] = (int)trim(html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252'));
                            $admitted_section['credits_gpa'] = (int)trim(html_entity_decode($row->nodes[5]->text(), ENT_COMPAT, 'cp1252'));
                            $admitted_section['points'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[7]->text(), ENT_COMPAT, 'cp1252')));
                            $admitted_section['gpa'] = str_replace(',', '.', trim(html_entity_decode($row->nodes[9]->text(), ENT_COMPAT, 'cp1252')));

                            $admitted_sections[] = $admitted_section;
                            $admitted_section = array();
                        }
                    }
                    break;
            }
        }

        $report['programs'] = $programs;

        return (array('student'=>$student, 'report'=>$report, 'semesters'=>$semesters, 'admitted_sections'=>$admitted_sections));
	}
	
	// Horaire de cours
	public function getSchedule ($requested_semester = '') {
        // Définition des paramètres de la requête
        $this->fetcher->set(array(
            'cookies'       =>  $this->CI->session->userdata('capsule_cookies'),
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'referer'       =>  'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu',
            'request_method'=>  'POST'
        ));

        if ($requested_semester=='') {
            $suggested_semesters = array(
                                         (date('Y')+1)."01"	=>	"Hiver ".(date('Y')+1),
                                         date('Y')."09"		=>	"Automne ".date('Y'),
                                         date('Y')."05"		=>	"Été ".date('Y'),
                                         date('Y')."01"		=>	"Hiver ".date('Y'),
                                         (date('Y')-1)."09"	=>	"Automne ".(date('Y')-1),
                                         (date('Y')-1)."05"	=>	"Été ".(date('Y')-1),
                                         (date('Y')-1)."01"	=>	"Hiver ".(date('Y')-1)
                                         );

            $semesters = array();
        } else {
            $suggested_semesters = array($requested_semester => '');
        }

        $schedule = array();

        foreach ($suggested_semesters as $semester => $name) {
            // Définition des valeurs du formulaire
            $arguments = array(
                'HostName'      =>  $this->host,
                'RequestURI'    =>  "/pls/etprod7/bwskfshd.P_CrseSchdDetl",
                'PostValues'    =>  array(
                    'term_in'	=>	$semester
                )
            );

            // Ouverture de la connexion
            $this->fetcher->Open($arguments);

            // Envoi du formulaire
            $error = $this->fetcher->SendRequest($arguments);
            if (!empty($error)) return (false);

            // Lecture du contenu de la réponse
            $this->fetcher->ReadWholeReplyBody($response);
            $response = utf8_encode(html_entity_decode($response));

            // Fermeture de la connexion
            $this->fetcher->Close();

            // Vérification des données
            if (!$this->checkPage($response)) return (false);

            if (!strpos($response, "Vous n'êtes pas actuellement inscrit pour la session.")) {
                $schedule[$semester] = array();

                // Nettoyage du code HTML
                if (function_exists('tidy_repair_string')) {
                    $tidy = tidy_parse_string($response);
                    $tidy->cleanRepair();
                } else {
                    $tidy = $response;
                }

                // Analyse de la structure DOM de la page
                $this->domparser->load($tidy);
                $tables = $this->domparser->find('table.datadisplaytable');

                // Vérification d'une requête similaire
                $md5 = md5(serialize($tables));
                if ($this->CI->mCache->requestExists('schedule-' . $semester, $md5)) {
                    $schedule[$semester] = true;
                    if (!$this->forceReload) continue;
                } else {
                    $this->CI->mCache->addRequest('schedule-' . $semester, $md5);
                }

                $courses = array();
                $course = array('classes' => array());

                for ($n = 1; $n < count($tables); $n++) {
                    if (html_entity_decode($tables[$n]->nodes[1]->text(), ENT_COMPAT, 'cp1252') == 'Horaires prévus') {
                        // Recherche des classes prévus à l'horaire
                        $rows = $tables[$n]->find('tr');


                        for ($i = 1; $i < count($rows); $i++) {
                            $row = $rows[$i];
                            $class = array(
                                'type'     =>  trim(html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')),
                                'hours'    =>  explode(' - ', trim(str_replace('ACU', '', html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252')))),
                                'day'      =>  trim(str_replace(' ', '', html_entity_decode($row->nodes[5]->text(), ENT_COMPAT, 'cp1252'))),
                                'location' =>  trim(str_replace('ACU', '', html_entity_decode($row->nodes[7]->text(), ENT_COMPAT, 'cp1252'))),
                                'dates'    =>  explode(' - ', trim(html_entity_decode($row->nodes[9]->text(), ENT_COMPAT, 'cp1252'))),
                                'teaching' =>  trim(html_entity_decode($row->nodes[11]->text(), ENT_COMPAT, 'cp1252')),
                                'teacher'  =>  trim(str_replace('ACU', '', html_entity_decode($row->nodes[13]->text(), ENT_COMPAT, 'cp1252'))),
                                'code'     =>   $course['code']
                            );

                            if (count($class['hours']) == 2) {
                                if (strpos($class['hours'][0], ':50')) {
                                    $class['hours'][0] = substr($class['hours'][0], 0, strpos($class['hours'][0], ':'));
                                    $class['hours'][0]++;
                                }
                                if (strpos($class['hours'][1], ':50')) {
                                    $class['hours'][1] = substr($class['hours'][1], 0, strpos($class['hours'][1], ':'));
                                    $class['hours'][1]++;
                                }

                                $class['hour_start'] = str_replace(':00', '', str_replace(':30', '.5', str_replace(':20', '.5', $class['hours'][0])));
                                $class['hour_end'] = str_replace(':00', '', str_replace(':30', '.5', str_replace(':20', '.5', $class['hours'][1])));
                            } else {
                                $class['hour_start'] = '';
                                $class['hour_end'] = '';
                            }

                            unset($class['hours']);

                            $class['date_start'] = str_replace('/', '', $class['dates'][0]);
                            $class['date_end'] = str_replace('/', '', $class['dates'][1]);
                            unset($class['dates']);

                            if ($class['type'] == 'Plage horaire (grève)' and (empty($class['hour_start']))) $class = array();
                            if (!empty($class)) $course['classes'][] = $class;
                        }

                        // Ajout du cours précédent
                        if (!empty($course)) $courses[] = $course;
                        $course = array();
                    } else {
                        $name = trim(html_entity_decode($tables[$n]->nodes[1]->text(), ENT_COMPAT, 'cp1252'));
                        $name = explode(' - ', $name);
                        $course['title'] = trim($name[0]);
                        $course['code'] = strtoupper(str_replace(' ', '-', trim($name[1])));
                        if (isset($name[2])) $course['section'] = trim($name[2]);

                        // Recherche des infos du cours
                        $rows = $tables[$n]->find('tr');
                        foreach ($rows as $row) {
                            $name = trim(str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')));
                            $value = trim(str_replace(':', '', html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252')));

                            switch ($name) {
                                case 'NRC':
                                    $course['nrc'] = $value;
                                    break;
                                case 'Professeur':
                                    $course['teacher'] = $value;
                                    break;
                                case 'Crédits':
                                    $course['credits'] = (int)$value;
                                    break;
                                case 'Cycle':
                                    if ($value == 'Premier cycle') {
                                        $course['cycle'] = 1;
                                    } elseif ($value == 'Deuxième cycle') {
                                        $course['cycle'] = 2;
                                    } elseif ($value == 'Troisième cycle') {
                                        $course['cycle'] = 3;
                                    }
                                    break;
                                case 'Campus':
                                    $course['campus'] = $value;
                                    break;
                            }
                        }
                    }
                }

                $schedule[$semester]['courses'] = $courses;
            }
        }

        return ($schedule);
	}
	
	public function getFees ($requested_semester = '') {
        // Définition des paramètres de la requête
        $this->fetcher->set(array(
            'cookies'       =>  $this->CI->session->userdata('capsule_cookies'),
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'referer'       =>  'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu'
        ));

        $arguments = array(
            'RequestURI'    =>  "/pls/etprod7/bwskoacc.P_ViewAcct"
        );

        // Ouverture de la connexion
        $this->fetcher->Open($arguments);

        // Envoi du formulaire
        $error = $this->fetcher->SendRequest($arguments);
        if (!empty($error)) return (false);

        // Lecture du contenu de la réponse
        $this->fetcher->ReadWholeReplyBody($response);
        $response = utf8_encode(html_entity_decode($response));

        // Fermeture de la connexion
        $this->fetcher->Close();

        // Vérification des données
        if (!$this->checkPage($response)) return (false);

        // Nettoyage du code HTML
        if (function_exists('tidy_repair_string')) {
            $tidy = tidy_parse_string($response);
            $tidy->cleanRepair();
        } else {
            $tidy = $response;
        }

        // Analyse de la structure DOM de la page
        $this->domparser->load($tidy);
        $tables = $this->domparser->find('table.datadisplaytable');

        // Vérification d'une requête similaire
        $md5 = md5(serialize($tables));
        if ($this->CI->mCache->requestExists('fees', $md5)) {
            if (!$this->forceReload) return (true);
        } else {
            $this->CI->mCache->addRequest('fees', $md5);
        }

        $account = array();

        $rows = $tables[0]->find('tr');
        foreach ($rows as $row) {
            $name = trim(str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')));
            $value = trim(html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252'));
            switch ($name) {
                case 'Numéro de client':
                    $account['account_number'] = $value;
                    break;
                default:
                    if (strpos($name, 'Numéro d\'assuré AELIÉS') !== false) {
                        $account['aelies_number'] = str_replace(' ', '', $value);
                    }
                    break;
            }
        }

        $semesters = array();
        $semester = array();
        $rows = $tables[1]->find('tr');
        foreach ($rows as $row) {
            if (isset($row->nodes[1])) $name = trim(str_replace(':', '', html_entity_decode($row->nodes[1]->text(), ENT_COMPAT, 'cp1252')));
            if (isset($row->nodes[3])) $value = trim(str_replace(' ', '', str_replace(',', '.', html_entity_decode($row->nodes[3]->text(), ENT_COMPAT, 'cp1252'))));

            switch ($name) {
                case 'Description':
                    break;
                case 'Frais de session':
                    $semester['total'] = (float)str_replace('$', '', $value);
                    break;
                case 'Crédits et paiements de session':
                    $semester['payments'] = (float)str_replace('$', '', $value);
                    break;
                case 'Solde de session':
                    $semester['balance'] = (float)str_replace('$', '', $value);

                    // Save last semester and start a new one
                    $semesters[] = $semester;
                    $semester = array();
                    break;
                case 'Solde du compte':
                    $account['balance'] = (float)str_replace('$', '', $value);
                    break;
                default:
                    if (strpos($name, 'Automne ') !== false || strpos($name, 'Été ') !== false || strpos($name, 'Hiver ') !== false) {
                        $semester['semester'] = convertSemester($name);
                    } elseif (str_replace(' ', '', $name) != '') {
                        if (str_replace(' ', '', $value) != '') {
                            $semester['fees'][] = array('name' => $name, 'amount' => (float)str_replace('$', '', $value));
                        }
                    }
            }
        }

        if (!empty($semester)) $semesters[] = $semester;

        return (array('account' => $account, 'semesters' => $semesters));
	}
	
	public function registerCourses ($nrc_array, $semester) {
		$this->fetcher->cookies = $_SESSION['cookies'];
		$this->fetcher->debug = $this->debug;
		
		if ($_SESSION['referer']=='') {
			$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
		} else {
			$this->fetcher->referer = $_SESSION['referer'];
		}
		
		$this->fetcher->protocol="https";
		
		$arguments['HostName'] = $this->host;
		$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu";
		
		$error=$this->fetcher->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->fetcher->Close();
		
		$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->fetcher->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->fetcher->Close();
		
		$this->fetcher->request_method="POST";
		
		$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->fetcher->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'term_in'				=>	$semester
			  );
		
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->fetcher->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->fetcher->Close();
		
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
				
		$this->fetcher->Close();
		
		$this->fetcher->request_method="POST";
		$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->fetcher->Open($arguments);
		
		// Envoi du formulaire
		unset($arguments["PostValues"]);
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckcoms.P_Regs";

		$error=$this->fetcher->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->fetcher->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		if ($this->fetcher->response_status==404) {
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
		$this->fetcher->cookies = $_SESSION['cookies'];
		$this->fetcher->debug = $this->debug;
		
		if ($_SESSION['referer']=='') {
			$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
		} else {
			$this->fetcher->referer = $_SESSION['referer'];
		}
		
		$this->fetcher->protocol="https";
		
		$arguments['HostName'] = $this->host;
		$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu";
		
		$error=$this->fetcher->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->fetcher->Close();
		
		$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->fetcher->Open($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->SendRequest($arguments);
	
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$this->fetcher->Close();
		
		$this->fetcher->request_method="POST";
		
		$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->fetcher->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'term_in'				=>	$semester
			  );
		
		$arguments["RequestURI"] = "/pls/etprod7/bwskfreg.P_AltPin";
		
		$error=$this->fetcher->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->fetcher->Close();
		
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
				
		$this->fetcher->Close();
		
		$this->fetcher->request_method="POST";
		
		$this->fetcher->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin';
		
		$this->fetcher->Open($arguments);
		
		// Envoi du formulaire
		unset($arguments["PostValues"]);
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckcoms.P_Regs";

		$error=$this->fetcher->SendRequest($arguments);
		
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__LINE__);
			return (false);
		}
		
		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->fetcher->Close();
		
		if (!$this->checkPage($response)) return (false);
				
		if ($this->fetcher->response_status==404) {
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
		$this->fetcher->cookies = $_SESSION['cookies'];
		$this->fetcher->debug = $this->debug;
		$code = explode("-", strtoupper($code));
		
		$this->fetcher->protocol="https";
		
		$arguments['HostName'] = $this->host;
		$arguments["RequestURI"] = "/pls/etprod7/bwckctlg.p_disp_course_detail?cat_term_in=".$semester."&subj_code_in=".$code[0]."&crse_numb_in=".$code[1];
		
		//echo "<H2><LI>Opening connection to:</H2>\n<PRE>",HtmlEntities($arguments["HostName"]),"</PRE>\n";
		//flush();
		$error=$this->fetcher->Open($arguments);
	
		if ($error!="") {
			error_log('Ligne '.__LINE__);
			return (false);
		}
		
		//echo "<H2><LI>Sending request for page:</H2>\n<PRE>";
		//echo HtmlEntities($arguments["RequestURI"]),"\n";
		$error=$this->fetcher->SendRequest($arguments);
	
		if ($error!="") {
			error_log('Ligne '.__LINE__);
			return (false);
		}
			
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne '.__LINE__);
			return (false);
		}

		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		//error_log($response);
		$this->fetcher->Close();
		
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
					$this->fetcher->referer = "https://capsuleweb.ulaval.ca/pls/etprod7/bwckctlg.p_disp_course_detail?cat_term_in=".$semester."&subj_code_in=".$code[0]."&crse_numb_in=".$code[1];
				
					$this->fetcher->protocol="https";
					
					$arguments['HostName'] = $this->host;
					$arguments["RequestURI"] = $link;
			
					$error=$this->fetcher->Open($arguments);
				
					if ($error!="") {
						error_log('Ligne '.__LINE__);
						return (false);
					}
					
					$error=$this->fetcher->SendRequest($arguments);
				
					if ($error!="") {
						error_log('Ligne '.__LINE__);
						return (false);
					}
						
					$headers=array();
					$error=$this->fetcher->ReadReplyHeaders($headers);
					if ($error!="") {
						error_log('Ligne '.__LINE__);
						return (false);
					}
			
					$error = $this->fetcher->ReadWholeReplyBody($body);
					$response = utf8_encode(html_entity_decode($body));
									
					$this->fetcher->Close();
					
					if (!$this->checkPage($response)) return (false);
					
					unset($arguments["PostValues"]);
					$this->fetcher->request_method = "GET";
					
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
		$this->fetcher->cookies = $_SESSION['cookies'];
		$this->fetcher->debug = $this->debug;
		
		$this->fetcher->protocol="https";
		
		$arguments['HostName'] = $this->host;
		$arguments["RequestURI"] = "/pls/etprod7/bwckschd.p_disp_detail_sched?term_in=".$semester."&crn_in=".$nrc;

		$error=$this->fetcher->Open($arguments);
		if ($error!="") {
			error_log(__FILE__ .' | Ligne '.__LINE__);
			return (false);
		}
		
		$error=$this->fetcher->SendRequest($arguments);
		if ($error!="") {
			error_log(__FILE__ .' | Ligne '.__LINE__);
			return (false);
		}
			
		$headers=array();
		$error=$this->fetcher->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log(__FILE__ .' | Ligne '.__LINE__);
			return (false);
		}

		$error = $this->fetcher->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		$this->fetcher->Close();
		
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
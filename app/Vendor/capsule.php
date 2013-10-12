<?php

class Capsule {
	public $debug = 0;
    private $fetcher;
    private $domparser;
    public $Cache;
	public $forceReload = false;
    private $host = "132.203.189.178";
    //private $host = "capsuleweb.ulaval.ca";
    
    public $cookies;
    public $referer;
    public $userName;

    // Private vars (used for relogin if server connection is lost)
    private $idul;
    private $password;

	public function __construct( &$fetcher, &$domparser ) {
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
        ) );

        // Define request arguments
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  "/pls/etprod7/twbkwbis.P_WWWLogin"
        );

        // Open connection to remote server
        $error = $this->fetcher->Open( $arguments );
        if ( !empty( $error ) ) {
            if ( $error == '0 could not connect to the host "' . $this->host . '"' ) {
                sleep( 1 );

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
        if ( !empty( $error ) ) return ( 'server-connection' );

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = utf8_encode( html_entity_decode( $response, ENT_COMPAT, 'cp1252' ) );

        // Close remote connection
        $this->fetcher->Close();

        // Check if login form is available
        if ( strpos( $response, '<INPUT TYPE="text" NAME="sid" SIZE="10" MAXLENGTH="8" ID="UserID" >' ) < 1 )
            return( 'server-unavailable' );

        // Submit login form
        $request = $this->_fetchPage( '/pls/etprod7/twbkwbis.P_ValLogin', 'POST', array(
            'sid' =>  $idul,
            'PIN' =>  $password
        ) );

        // Check if provided credentials are accepted by Capsule
        if ( preg_match( '/chec de la connexion/' , $request[ 'response' ] ) ) {
            // Connection failed because of wrong credentials
            return ( 'credentials' );
        } elseif ( strpos( $request[ 'response' ], "bienvenue+dans+Capsule" ) > 1 ) {
            // Save cookies
            $this->fetcher->SaveCookies( $cookies );
            $this->cookies = $cookies;

            // Extract user full name from server response
            $this->userName = substr( $request[ 'response' ], strpos( $request[ 'response' ], "<meta http-equiv=\"refresh\" content=\"0;url=/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_MainMnu&amp;msg=WELCOME" ) );
            $this->userName = substr( $this->userName, strpos( $this->userName, "WELCOME+" ) + 8 );
            $this->userName = urldecode( substr( $this->userName, 0, strpos( $this->userName, "+bienvenue" ) - 1 ) );

            // Save credentials in private vars (if needed for further access)
            $this->idul = $idul;
            $this->password = $password;

            // Connection to Capsule completed with success
            return ( 'success' );
        } else {
            // Unknown error occurred during login
            return ( 'server-connection' );
        }
	}

    // Check the availability of Capsule and Exchange servers
    public function pokeULServers () {
        $capsule = true;
        $exchange = true;

        // Test for Capsule availability
        $request = $this->_fetchPage( '/pls/etprod7/twbkwbis.P_WWWLogin', 'GET', array(), false );

        if ( !$request ) {
            $capsule = false;
        }
        
        // Check if login form is available
        if ( $capsule && strpos( $request[ 'response' ], '<input type="submit" value="Connexion">' ) < 1 ) {
            $capsule = false;
        }

        // Test for Exchange availability
        $this->host = 'exchange.ulaval.ca';
        $request = $this->_fetchPage( '/owa/auth/logon.aspx', 'GET', array(), false );

        if ( !$request ) {
            $exchange = false;
        }
            
        // Check if login form is available
        if ( strpos( $request[ 'response' ], '<input type="submit" class="btn" value="" onclick="clkLgn()">' ) < 1 ) {
            $exchange = false;
        }

        return array( 'capsule' => $capsule, 'exchange' => $exchange );
    }

    // Fallback login function when Capsule is offline
    public function loginExchange ( $idul, $password ) {
        // Define temporary host
        $this->host = 'exchange.ulaval.ca';

        // Define request parameters
        $this->fetcher->set( array(
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'request_method'=>  'GET'
        ) );

        // Define request arguments
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  "/owa/auth/logon.aspx"
        );

        // Open connection to remote server
        $error = $this->fetcher->Open( $arguments );
        if ( !empty( $error ) ) {
            if ( $error == '0 could not connect to the host "' . $this->host . '"' ) {
                sleep( 1 );

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
        if ( !empty( $error ) ) return ( 'server-connection' );

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = html_entity_decode( $response, ENT_COMPAT, 'utf-8' );

        // Close remote connection
        $this->fetcher->Close();

        // Check if login form is available
        if ( strpos( $response, '<label for="username" class="text">IDUL</label><input id="username" name="username" type="text" class="text"/>' ) < 1 )
            return( 'server-unavailable' );

        // Submit login form
        $request = $this->_fetchPage( '/exchweb/bin/auth/owaauth.dll', 'POST', array(
            'destination'   =>  'https://exchange.ulaval.ca/exchange/',
            'flags'         =>  0,
            'forcedownlevel'=>  0,
            'username'      =>  $idul,
            'password'      =>  $password,
            'isUtf8'        =>  1,
            'trusted'       =>  0
        ), false );

        // Check if provided credentials are accepted by Exchange
        if ( preg_match( '/utilisateur ou le mot de passe que vous avez/' , $request[ 'response' ] ) ) {
            // Connection failed because of wrong credentials
            return ( 'credentials' );
        } elseif ( strpos( $request[ 'response' ], "Se déconnecter" ) > 1 ) {
            // Save credentials in private vars (if needed for further access)
            $this->idul = $idul;
            $this->password = $password;

            // Connection to Exchange completed with success
            return ( 'success' );
        } else {
            // Unknown error occurred during login
            return ( 'server-connection' );
        }
    }

	// Test connection to Capsule server
	public function testConnection () {
        $this->cookies = SessionComponent::read( 'Capsule.cookies' );
        if ( empty( $this->idul ) ) {
            $this->idul = SessionComponent::read( 'User.idul' );
            $this->password = SessionComponent::read( 'User.password' );
        }
        $request = $this->_fetchPage( '/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_AdminMnu' );

        // Retry user login if request fails
        if ( !$request || !isset( $request[ 'headers' ] ) )
            $this->login( $this->idul, $this->password );

        // Check if session ID cookie from header response is empty
        $isEmpty = false;

        for ( reset( $request[ 'headers' ] ), $header = 0; $header < count( $request[ 'headers' ] ); next( $request[ 'headers' ] ), $header++ ) {
            $header_name = key( $request[ 'headers' ] );

            if ( $header_name == 'set-cookie' ) {
                if ( is_array( $request[ 'headers' ][ $header_name ] ) ) {
                    foreach ( $request[ 'headers' ][ $header_name ] as $cookie ) {
                        if ( preg_match( "#SESSID\=;#", $cookie ) ) {
                            $isEmpty = true;
                            break;
                        }
                    }
                } elseif ( preg_match( "#SESSID\=;#", $request[ 'headers' ][ $header_name ] ) ) {
                    $isEmpty = true;
                }
            }
        }

        if ( $isEmpty ) {
            // Retry user login
            $this->login( $this->idul, $this->password );
        }

        // Connection is OK
        return true;
	}
	
    /*
	// Vérification des blocages
	public function checkHolds () {
        $this->fetcher->cookies = $this->cookies;
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
    */

	// Get studies summary
    public function getStudies ( $md5Hash, $semester ) {
        $request = $this->_fetchPage( '/pls/etprod7/bwskgstu.P_StuInfo', 'POST', array( 'term_in' => $semester ) );
        
        // Check if student has studies info
        if ( strpos( $request[ 'response' ], "Il n'existe pas d'informations étudiantes disponibles" ) )
            return ( array( 'status' => false ) );
        
        // Parse studies data
        if ( strpos( $request[ 'response' ], "tudes en cours" ) ) {
            // Parse DOM structure from response
            $this->domparser->load( $request[ 'response' ] );
            $tables = $this->domparser->find( 'table.datadisplaytable' );

            // Check if similar data already exists in DB
            if ( md5( serialize( $tables ) ) == $md5Hash ) {
                // Data already exists in DB, if not force to reload, quit
                if ( !$this->forceReload ) return true;
            } else {
                // Update MD5 Hash
                $md5Hash = md5( serialize( $tables ) );
            }

            $userInfo = array( 'empty_data' => false );

            // Find university attendance info
            $rows = $tables[ 0 ]->find( 'tr' );
            foreach ( $rows as $row ) {
                $name = str_replace( ':', '', $row->nodes[ 1 ]->text() );
                if ( isset( $row->nodes[ 3 ] ) ) $value = $row->nodes[ 3 ]->text();
                switch ( $name ) {
                    case 'Inscrit pour la session':
                        $userInfo[ 'registered' ] = false;
                        if ( $value == 'Oui' ) $userInfo[ 'registered' ] = true;
                    break;
                    case 'Statut':
                        $userInfo[ 'status' ] = $value;
                    break;
                    default:
                        if ( strpos( $name, 'Première session de fréquentation' ) !== false ) {
                            $semester = explode( ' ', $value );
                            $userInfo[ 'first_sem' ] = $this->_convertSemester( $value );
                        } elseif ( strpos( $name, 'Dernière session de fréquentation' ) !== false ) {
                            $userInfo[ 'last_sem' ] = $this->_convertSemester( $value );
                        }
                        break;
                }
            }

            $programs = array();
            $program = array();

            // Find study programs
            $rows = $tables[ 1 ]->find( 'tr' );
            foreach ( $rows as $row ) {
                $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
                if ( isset( $row->nodes[ 3 ] ) ) $value = $row->nodes[ 3 ]->text();
                switch ( $name ) {
                    case 'Programme actuel':
                        // If new program, current program data are added to the end of programs list
                        if ( $program != array() ) {
                            $program[ 'concentrations' ] = serialize( $program[ 'concentrations' ] );
                            $program[ 'idul' ] = $this->idul;
                            $programs[] = array( 'Program' => $program );
                        }
                        $program = array();
                        break;
                    case 'Cycle':
                        if ( $value == 'Premier cycle' ) $program[ 'cycle' ] = 1;
                        elseif ( $value == 'Deuxième cycle' ) $program[ 'cycle' ] = 2;
                        elseif ( $value == 'Troisième cycle' ) $program[ 'cycle' ] = 3;
                        break;
                    case 'Programme':
                        $program[ 'name' ] = $value;
                        break;
                    case 'Session d\'admission':
                        $program[ 'adm_semester' ] = $this->_convertSemester( $value );
                        break;
                    case 'Session de répertoire':
                        $semester = explode( ' ', $value );
                        $program[ 'session_repertoire' ] = $this->_convertSemester( $value );
                        break;
                    case 'Type d\'admission':
                        $program[ 'adm_type' ] = $value;
                        break;
                    case 'Faculté':
                        $program[ 'faculty' ] = $value;
                        break;
                    case 'Majeure':
                        $program[ 'major' ] = $value;
                        break;
                    case 'Mineure':
                        $program[ 'minor' ] = $value;
                        break;
                    case 'Concentration de majeure':
                        if ( !array_key_exists( 'concentrations', $program ) ) $program[ 'concentrations' ] = array();
                        $program[ 'concentrations' ][] = $value;
                        break;
                    default:
                        if ( strpos( $name, 'Baccalauréat' ) !== false || strpos( $name, 'Maîtrise' ) !== false || strpos( $name, 'Doctorat' ) !== false || strpos( $name, 'Diplôme' ) !== false ) {
                            $program[ 'diploma' ] = $name;
                        }
                        break;
                }
            }

            $program[ 'concentrations' ] = serialize( $program[ 'concentrations' ] );
            $program[ 'idul' ] = $this->idul;
            $programs[] = array( 'Program' => $program );

            // Check program validity (Pre-Banner programs are removed)
            foreach ( $programs as &$program ) {
                if ( $program[ 'Program' ][ 'name' ] == 'Programme pré-Banner' ) {
                    $program = array();
                }
            }

            return ( array( 'status' => true, 'md5Hash' => $md5Hash, 'programs' => $programs, 'userInfo' => $userInfo ) );
        } else {
            // Enregistrement du résultat de la requête dans la BD pour débug
            //$this->CI->mHistory->saveRequestData($this->CI->session->userdata('pilule_user'), 'get-studies', $response, __FILE__." : ligne ".__LINE__." | ".$error);

            return false;
        }
	}
	
	// Rapport de cheminement
	public function getStudiesDetails ( $md5Hash, $semester, $programs ) {
        $userInfo = array();

        // Get list of Rapport de cheminement
        $request = $this->_fetchPage( '/pls/etprod7/bwcksmmt.P_DispPrevEval', 'POST', array( 'term_in' => $semester ) );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $rows = $this->domparser->find( 'table.dataentrytable tr' );

        // Find a link to the last Rapport de cheminement for each study program
        foreach ( $programs as &$program ) {
            foreach ( $rows as $row ) {
                $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
                if ( $name == $program[ 'Program' ][ 'name' ] ) {
                    // Extract link
                    $links = $row->find( 'a' );
                    $program[ 'Program' ][ 'link' ] = $links[ 0 ]->attr[ 'href' ];
                    break;
                }
            }

            if ( isset( $program[ 'Program' ][ 'link' ] ) and ( !empty( $program[ 'Program' ][ 'link' ] ) ) ) {
                // Fetch Attestation de cheminement
                $request = $this->_fetchPage( '/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption', 'POST', array(
                    'request_no'        =>  substr( $program[ 'Program' ][ 'link' ], strpos( $program[ 'Program' ][ 'link' ], "_no=" ) + 4 ),
                    'program_summary'   =>  '1'
                ) );

                // Parse DOM structure from response
                $this->domparser->load( $request[ 'response' ] );
                $tables = $this->domparser->find( 'table.datadisplaytable' );

                // Check if similar data already exists in DB
                if ( array_key_exists( 'studies-details-program-' . md5( $program[ 'Program' ][ 'name' ] ), $md5Hash ) && md5( serialize( $tables ) ) == $md5Hash[ 'studies-details-program-' . md5( $program[ 'Program' ][ 'name' ] ) ] ) {
                    // Data already exists in DB, if not force to reload, quit
                    if ( !$this->forceReload )
                        continue;
                } else {
                    // Update MD5 Hash
                    $md5Hash[ 'studies-details-program-' . md5( $program[ 'Program' ][ 'name' ] ) ] = md5( serialize( $tables ) );
                }

                // Parse data

                $rows = $tables[ 0 ]->find( 'tr' );
                foreach ( $rows as $row ) {
                    $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
                    if ( isset( $row->nodes[ 3 ] ) ) $value = $row->nodes[ 3 ]->text();
                    switch ( $name ) {
                        case 'Code permanent':
                            $userInfo[ 'code_permanent' ] = trim( str_replace( ' ', '', $value ) );
                            break;
                    }

                    if ( count( $row->nodes ) > 5 ) {
                        $name = trim( str_replace( ':', '', $row->nodes[ 5 ]->text() ) );
                        if ( isset( $row->nodes[ 7 ] ) ) $value = $row->nodes[ 7 ]->text();
                        switch ( $name ) {
                            case 'Session d\'évaluation':
                                $program[ 'Program' ][ 'session_evaluation' ] = $this->_convertSemester( $value );
                                break;
                            case 'Date d\'obtention du diplôme':
                                $program[ 'Program' ][ 'date_diplome' ] = trim( str_replace( '/', '', $value ) );
                                break;
                            case 'Date de l\'attestation':
                                $program[ 'Program' ][ 'date_attestation' ] = trim( str_replace( '/', '', $value ) );
                                break;
                        }
                    }
                }

                $rows = $tables[ 1 ]->find( 'tr' );
                foreach ( $rows as $row ) {
                    $name = str_replace( ':', '', $row->nodes[ 1 ]->text() );
                    if ( isset( $row->nodes[ 3 ] ) ) $value = $row->nodes[ 3 ]->text();
                    if ( isset( $row->nodes[ 5 ] ) ) $value2 = $row->nodes[ 5 ]->text();
                    if ( isset( $row->nodes[ 7 ] ) ) $value3 = $row->nodes[ 7 ]->text();
                    if ( isset( $row->nodes[ 9 ] ) ) $value4 = $row->nodes[ 9 ]->text();
                    if ( isset( $row->nodes[ 11 ] ) ) $value5 = $row->nodes[ 11 ]->text();
                    switch ( $name ) {
                        case 'Total exigé':
                            $program[ 'Program' ][ 'requirements' ] = $value;
                            $program[ 'Program' ][ 'credits_program' ] = ( int )$value2;
                            $program[ 'Program' ][ 'credits_used' ] = ( int )$value3;
                            $program[ 'Program' ][ 'courses_program' ] = ( int )$value4;
                            $program[ 'Program' ][ 'courses_used' ] = ( int )$value5;
                            break;
                        case 'Reconnaissance d\'acquis':
                            $program[ 'Program' ][ 'credits_admitted' ] = ( int )$value3;
                            $program[ 'Program' ][ 'courses_admitted' ] = ( int )$value5;
                            break;
                        case 'Moyenne de cheminement':
                            $program[ 'Program' ][ 'gpa_overall' ] = str_replace( ',', '.', $value3 );
                            break;
                        default:
                            if ( isset( $row->nodes[ 3 ] ) and strpos( $row->nodes[ 3 ]->text(), 'Moyenne de programme' ) !== false ) {
                                $program[ 'Program' ][ 'gpa_program' ] = str_replace( ',', '.', trim( str_replace( ' ', '', substr( $row->nodes[ 3 ]->text(), strpos( $row->nodes[ 3 ]->text(), ':' )+1 ) ) ) );
                            }
                    }
                }

                $sections = array();
                $sectionNumber = 1;
                $section = array(
                    'idul'      => $this->idul,
                    'number'    => $sectionNumber,
                    'program_id'=> $program[ 'Program' ][ 'id' ],
                    'Course'    => array()
                );
                $program[ 'Section' ] = array();

                for ( $i = 2; $i < count ( $tables ); $i++ ) {
                    $rows = $tables[ $i ]->find( 'tr' );
                    foreach ( $rows as $row ) {
                        $name = str_replace( ':', '', $row->nodes[ 1 ]->text() );
                        if ( $name == 'Bloc' ) {
                            // Reset courses fetching
                            $check_courses = false;

                            // Ajout de la section précédente
                            if ( isset( $section[ 'title' ] ) && !empty( $section[ 'title' ] ) )
                                $program[ 'Section' ][] = $section;

                            // Add section
                            $section = array(
                                'idul'      => $this->idul,
                                'number'    => $sectionNumber,
                                'program_id'=> $program[ 'Program' ][ 'id' ],
                                'Course'    => array()
                            );

                            $section[ 'title' ] = $row->nodes[ 3 ]->text();
                            $section[ 'title' ] = trim( substr( $section[ 'title' ], 0, strrpos( $section[ 'title' ], ' - ' ) ) );
                            if ( strpos( $section[ 'title' ], " ( " )>-1 ) {
                                $section[ 'credits' ] = trim( substr( $section[ 'title' ], strrpos( $section[ 'title' ], " ( " )+3 ) );
                                $section[ 'credits' ]	= ( int )substr( $section[ 'credits' ], 0, strpos( $section[ 'credits' ], "," ) );
                                $section[ 'title' ] = trim( substr( $section[ 'title' ], 0, strrpos( $section[ 'title' ], " ( " ) ) );
                            }
                            $sectionNumber++;
                        } elseif ( $name == 'Cours' ) {
                            $courses = array();
                            $check_courses = true;
                        } elseif ( $name == 'Cours échoués' ) {
                            $check_courses = false;

                            // Ajout de la section précédente
                            if ( isset( $section[ 'title' ] ) && !empty( $section[ 'title' ] ) )
                                $program[ 'Section' ][] = $section;

                            // Add section
                            $section = array(
                                'idul'      => $this->idul,
                                'title'     => 'Cours échoués',
                                'number'    => $sectionNumber,
                                'program_id'=> $program[ 'Program' ][ 'id' ],
                                'Course'    => array()
                            );
                            $sectionNumber++;
                        } elseif ( trim( $name ) != '' ) {
                            if ( $check_courses ) {
                                $course = array(
                                    'idul'      => $this->idul,
                                    'program_id'=> $program[ 'Program' ][ 'id' ],
                                    'code'      =>  strtoupper( trim( $row->nodes[ 1 ]->text() . '-' . $row->nodes[ 3 ]->text() ) ),
                                    'title'     =>  trim( $row->nodes[ 5 ]->text() ),
                                    'semester'  =>  trim( str_replace( ' ', '', $row->nodes[ 7 ]->text() ) ),
                                    'credits'   =>  ( int )trim( str_replace( 'cr.', '', $row->nodes[ 9 ]->text() ) ),
                                    'note'      =>  trim( str_replace( '*', '', $row->nodes[ 11 ]->text() ) ),
                                );

                                if ( !empty( $course[ 'semester' ] ) ) {
                                    $semester = explode( ' ', $course[ 'semester' ] );
                                    if ( isset( $semester[ 1 ] ) ) {
                                        $course[ 'semester' ] = $this->_convertSemester( $course[ 'semester' ] );
                                    }
                                }

                                $courses[] = $course;
                            }
                        } else {
                            if ( $check_courses ) {
                                $section[ 'Course' ] = $courses;

                                $courses = array();
                                $check_courses = false;
                            }
                        }
                    }
                }

                // Ajout de la section précédente
                $section[ 'Course' ] = $courses;
                if ( isset( $section[ 'title' ] ) && !empty( $section[ 'title' ] ) )
                    $program[ 'Section' ][] = $section;

                // Remove link field
                unset( $program[ 'Program' ][ 'link' ] );
            }
        }

        return ( array( 'status' => true, 'md5Hash' => $md5Hash, 'userInfo' => $userInfo, 'programs' => $programs ) );
	}
	
    // Rapport de cheminement détaillé
    public function getStudiesCourses ( $md5Hash, $semester, $programs ) {
        $userInfo = array();

        // Get list of Rapport de cheminement
        $request = $this->_fetchPage( '/pls/etprod7/bwcksmmt.P_DispPrevEval', 'POST', array( 'term_in' => $semester ) );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $rows = $this->domparser->find( 'table.dataentrytable tr' );

        // Find a link to the last Rapport de cheminement for each study program
        foreach ( $programs as &$program ) {
            foreach ( $rows as $row ) {
                $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
                if ( $name == $program[ 'Program' ][ 'name' ] ) {
                    // Extract link
                    $links = $row->find( 'a' );
                    $program[ 'Program' ][ 'link' ] = $links[ 0 ]->attr[ 'href' ];
                    break;
                }
            }

            if ( isset( $program[ 'Program' ][ 'link' ] ) and ( !empty( $program[ 'Program' ][ 'link' ] ) ) ) {
                // Fetch Rapport de cheminement détaillé
                $request = $this->_fetchPage( '/pls/etprod7/bwckcapp.P_VerifyDispEvalViewOption', 'POST', array(
                    'request_no'        =>  substr( $program[ 'Program' ][ 'link' ], strpos( $program[ 'Program' ][ 'link' ], "_no=" ) + 4 ),
                    'program_summary'   =>  '3'
                ) );

                // Parse DOM structure from response
                $this->domparser->load( $request[ 'response' ] );
                $tables = $this->domparser->find( 'table.datadisplaytable' );

                // Check if similar data already exists in DB
                if ( array_key_exists( 'studies-courses-program-' . md5( $program[ 'Program' ][ 'name' ] ), $md5Hash ) && md5( serialize( $tables ) ) == $md5Hash[ 'studies-courses-program-' . md5( $program[ 'Program' ][ 'name' ] ) ] ) {
                    // Data already exists in DB, if not force to reload, quit
                    if ( !$this->forceReload )
                        continue;
                } else {
                    // Update MD5 Hash
                    $md5Hash[ 'studies-courses-program-' . md5( $program[ 'Program' ][ 'name' ] ) ] = md5( serialize( $tables ) );
                }

                // Parse data
                for ( $i = 2; $i < count ( $tables ); $i++ ) {
                    $sectionTitle = '';
                    $coursesList = array();

                    // Extract section info
                    $rows = $tables[ $i ]->find( 'tr' );

                    $sectionTitle = $rows[ 0 ]->nodes[ 3 ]->text();
                    $sectionTitle = trim( substr( $sectionTitle, 0, strrpos( $sectionTitle, ' - ' ) ) );
                    if ( strpos( $sectionTitle, " ( " ) > -1 ) {
                        $sectionTitle = trim( substr( $sectionTitle, 0, strrpos( $sectionTitle, " ( " ) ) );
                    }

                    $tableContent = $tables[ $i ]->text();

                    // Find courses ranges
                    preg_match_all( '/(([A-Z]{3})(-)([0-9]{4}))(\s)(à)(\s)(([A-Z]{3})(-)([0-9]{4}))/', $tableContent, $results );
                    if ( !empty( $results[ 0 ] ) ) {
                        for ( $n = 0; $n < count( $results[ 4 ] ); $n++ ) {
                            $firstNumber = $results[ 4 ][ $n ];
                            $lastNumber = $results[ 11 ][ $n ];

                            for ( $y = $firstNumber; $y < ( $lastNumber+1 ); $y++ ) {
                                $coursesList[] = $results[ 2 ][ 0 ] . '-' . $y;
                            }
                        }
                    }
                    
                    // Find courses codes
                    preg_match_all( '/(([A-Z]{3})(-)([0-9]{4}))/', $tableContent, $results );
                    $coursesList = array_merge( $coursesList, $results[ 0 ] );

                    // Find courses codes split in different cells
                    preg_match_all( '/(([A-Z]{3})\s([0-9]{4}))/', $tableContent, $results );
                    foreach ( $results[ 0 ] as &$code ) {
                        $code = str_replace( ' ', '-', $code );
                    }
                    $coursesList = array_merge( $coursesList, $results[ 0 ] );

                    foreach ( $program[ 'Section' ] as &$section ) {
                        if ( $section[ 'title' ] == $sectionTitle ) {
                            // Add courses to section list

                            foreach ( $coursesList as $key => $code ) {
                                // Remove 'Équivalence de crédits'
                                if ( $code == 'EHE-1899' )
                                    continue;

                                // Remove undesirable courses
                                if ( $section[ 'title' ] == 'Connaiss. générale français' && ( $code == 'FRN-1900' || $code == 'FRN-1960' ) )
                                    continue;

                                // Check if course is already in section list
                                $existingCourse = Set::extract( '/Course[code=' . $code . ']', $section );

                                if ( empty( $existingCourse ) ) {
                                    $section[ 'Course' ][] = array(
                                        'idul'      =>  $this->idul,
                                        'program_id'=>  $program[ 'Program' ][ 'id' ],
                                        'code'      =>  $code
                                    );
                                }
                            }
                        }
                    }
                }

                // Remove link field
                unset( $program[ 'Program' ][ 'link' ] );
            }
        }

        return ( array( 'status' => true, 'md5Hash' => $md5Hash, 'programs' => $programs ) );
    }

	// Student report
	public function getReport ( $md5Hash ) {
        // Get list of student report page
        $request = $this->_fetchPage( '/pls/etprod7/bwskotrn.P_ViewTran', 'POST', array( 'levl' => '1', 'tprt' => 'WEB' ) );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $table = $this->domparser->find( 'table.datadisplaytable' );

        // Check if similar data already exists in DB
        if ( md5( serialize( $table ) ) == $md5Hash ) {
            // Data already exists in DB, if not force to reload, quit
            if ( !$this->forceReload ) return true;
        } else {
            // Update MD5 Hash
            $md5Hash = md5( serialize( $table ) );
        }

        // Parse response data
        $userInfo = array();
        $programs = array();
        $report = array( 'idul' => $this->idul );
        $semesters = array();
        $admittedSections = array();

        $check_programs = false;
        $check_courses = false;
        $check_admitted = false;
        $check_semesters = false;

        $rows = $table[ 0 ]->find( 'tr' );
        foreach ( $rows as $row ) {
            $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
            if ( isset( $row->nodes[ 3 ] ) ) $value = $row->nodes[ 3 ]->text();
            if ( isset( $row->nodes[ 5 ] ) ) $value2 = $row->nodes[ 5 ]->text();
            if ( isset( $row->nodes[ 7 ] ) ) $value3 = $row->nodes[ 7 ]->text();
            if ( isset( $row->nodes[ 9 ] ) ) $value4 = $row->nodes[ 9 ]->text();
            if ( isset( $row->nodes[ 11 ] ) ) $value5 = $row->nodes[ 11 ]->text();
            switch ( $name ) {
                case 'Jour de naissance':
                    $userInfo[ 'birthday' ] = str_replace( 'É', 'é', str_replace( 'È', 'è', str_replace( 'Û', 'û', trim( strtolower( $value ) ) ) ) );
                    break;
                case 'No de dossier':
                    $userInfo[ 'da' ] = trim( str_replace( ' ', '', $value ) );
                    break;
                case 'Dernier rendement universitaire':
                    break;
                case 'Matière':
                    break;
                case 'Session':
                case 'Session actuelle':
                    if ( $check_courses ) {
                        $semester[ 'credits_registered' ] = ( int )trim( $row->nodes[ 3 ]->text() );
                        $semester[ 'credits_done' ]       = ( int )trim( $row->nodes[ 7 ]->text() );
                        $semester[ 'credits_gpa' ]        = ( int )trim( $row->nodes[ 9 ]->text() );
                        $semester[ 'points' ]             = str_replace( ',', '.', trim( $row->nodes[ 11 ]->text() ) );
                        $semester[ 'gpa' ]                = str_replace( ',', '.', trim( $row->nodes[ 13 ]->text() ) );
                    }
                    break;
                case 'Cumul':
                    if ( $check_courses ) {
                        $semester[ 'cumulative_gpa' ] = str_replace( ',', '.', trim( $row->nodes[ 13 ]->text() ) );
                        if ( !empty( $semester[ 'semester' ] ) ) $semesters[] = $semester;

                        $semester = array( 'idul' => $this->idul );
                        $check_courses = false;
                    }
                    break;
                case 'Observation sur le cycle':
                    $report[ 'notes' ] = trim( $row->nodes[ 3 ]->text() );
                    break;
                case 'Université Laval':
                    $report[ 'credits_registered' ]   = ( int )trim( $row->nodes[ 3 ]->text() );
                    $report[ 'credits_done' ]         = ( int )trim( $row->nodes[ 7 ]->text() );
                    $report[ 'credits_gpa' ]          = ( int )trim( $row->nodes[ 9 ]->text() );
                    $report[ 'points' ]               = str_replace( ',', '.', trim( $row->nodes[ 11 ]->text() ) );
                    $report[ 'ulaval_gpa' ]           = str_replace( ',', '.', trim( $row->nodes[ 13 ]->text() ) );
                    break;
                case 'Reconnaissance des acquis':
                    $report[ 'credits_admitted' ]           = ( int )trim( $row->nodes[ 3 ]->text() );
                    $report[ 'credits_admitted_done' ]      = ( int )trim( $row->nodes[ 7 ]->text() );
                    $report[ 'credits_admitted_gpa' ]       = ( int )trim( $row->nodes[ 9 ]->text() );
                    $report[ 'credits_admitted_points' ]    = str_replace( ',', '.', trim( $row->nodes[ 11 ]->text() ) );
                    $report[ 'gpa_admitted' ]               = str_replace( ',', '.', trim( $row->nodes[ 13 ]->text() ) );
                    break;
                case 'Total':
                    $report[ 'gpa_cycle' ] = str_replace( ',', '.', trim( $row->nodes[ 13 ]->text() ) );
                    break;
                default:
                    if ( !empty( $name ) && !$check_programs && substr( $name, 0, 15 ) == 'PROGRAMME(S) FR' ) {
                        $check_programs = true;
                        $program = array();
                    } elseif ( strpos( $name, 'DITS DE L\'UNIVERSIT' ) !== false ) {
                        $check_admitted = false;
                        $check_programs = false;
                        $check_semesters = true;
                        $semester = array( 'idul' => $this->idul );
                    } elseif ( strpos( $name, 'BILAN DU RELEV' ) !== false ) {
                        $check_admitted = false;
                        $check_programs = false;
                        $check_semesters = false;
                    } elseif ( !empty( $name ) && $check_semesters && strlen( $name ) > 2 ) {
                        if ( count( $row->nodes ) < 5 ) {
                            if ( strpos( $name, 'Totaux de session' ) !== false ) {
                                $check_courses = false;
                            } else {
                                // Ajout du programme précédent
                                if ( !empty( $semester[ 'semester' ] ) ) $semesters[] = $semester;

                                $semester = array( 'idul' => $this->idul );

                                $semester[ 'semester' ] = $this->_convertSemester( trim( $name ) );
                                $semester[ 'Course' ] = array();
                                $check_courses = true;
                            }
                        } else {
                            $course = array(
                                'idul'      => $this->idul,
                                'code'      =>  strtoupper( trim( $row->nodes[ 1 ]->text() . '-' . $row->nodes[ 3 ]->text() ) ),
                                'cycle'     =>  ( isset( $row->nodes[ 5 ] ) ) ? ( int )trim( $row->nodes[ 5 ]->text() ): 0,
                                'title'     =>  ( isset( $row->nodes[ 7 ] ) ) ? trim( $row->nodes[ 7 ]->text() ): 0,
                                'note'      =>  ( isset( $row->nodes[ 9 ] ) ) ? trim( str_replace( '*', '', $row->nodes[ 9 ]->text() ) ): 0,
                                'credits'   =>  ( isset( $row->nodes[ 11 ] ) ) ? ( int )trim( str_replace( 'cr.', '', $row->nodes[ 11 ]->text() ) ): 0,
                                'points'    =>  ( isset( $row->nodes[ 13 ] ) ) ? str_replace( ',', '.', trim( $row->nodes[ 13 ]->text() ) ): 0,
                                'reprise'   =>  ( isset( $row->nodes[ 15 ] ) ) ? trim( $row->nodes[ 15 ]->text() ): 0,
                            );

                            // Add course to semester's courses
                            $semester[ 'Course' ][] = $course;
                        }
                    } elseif ( ( !empty( $name ) ) and $check_programs ) {
                        switch( $name ) {
                            case 'En cheminement':
                                // Ajout du programme précédent
                                if ( !empty( $program ) ) $programs[] = $program;

                                $program = array();
                                $program[ 'concentrations' ] = array();
                                break;
                            case 'Diplôme obtenu':
                                // Ajout du programme précédent
                                if ( !empty( $program ) ) $programs[] = $program;

                                $program = array(
                                    'date_diplome'  =>  trim( str_replace( '/', '', $value3 ) ),
                                    'credits'       =>  ( int )trim( substr( $value4, 0, strpos( $value4, ' ' ) ) )
                                );
                                $program[ 'concentrations' ] = array();
                                break;
                            case 'Programme':
                                $program[ 'full_name' ] = trim( $value );
                                break;
                            case 'Fréquentation':
                                $program[ 'attendance' ] = trim( $value );
                                break;
                            case 'Concentration':
                                $program[ 'concentrations' ][] = trim( $value );
                                break;
                            case 'Majeure':
                                $program[ 'major' ] = trim( $value );
                                break;
                            case 'Mineure':
                                $program[ 'minor' ] = trim( $value );
                                break;

                            default:
                                if ( strpos( $name, 'RECONNAISSANCE DES ACQUIS' ) !== false ) {
                                    $check_programs = false;

                                    // Ajout du programme précédent
                                    if ( !empty( $program ) ) $programs[] = $program;

                                    $check_admitted = true;
                                    $admittedSection = array( 'idul' => $this->idul );
                                }
                                break;
                        }
                    } elseif ( ( !empty( $name ) ) and $check_admitted and strlen( $name ) > 2 ) {
                        if ( ( $name ) != 'Matière' ) {
                            if ( count( $row->nodes ) < 6 ) {
                                // Ajout de la section précédente
                                if ( !empty( $admittedSection[ 'title' ] ) ) $admittedSections[] = $admittedSection;

                                $admittedSection = array(
                                    'idul'      => $this->idul,
                                    'period'    =>  $name,
                                    'title'     =>  trim( $value ),
                                    'Course'    =>  array()
                                );
                            } elseif ( !empty( $name ) ) {
                                $course = array(
                                    'idul'      => $this->idul,
                                    'code'      =>  strtoupper( trim( $row->nodes[ 1 ]->text() . '-' . $row->nodes[ 3 ]->text() ) ),
                                    'title'     =>  trim( $row->nodes[ 5 ]->text() ),
                                    'note'      =>  trim( str_replace( '*', '', $row->nodes[ 7 ]->text() ) ),
                                    'credits'   =>  ( int )trim( str_replace( 'cr.', '', $row->nodes[ 9 ]->text() ) ),
                                    'points'    =>  ( isset( $row->nodes[ 11 ] ) ) ? str_replace( ',', '.', trim( str_replace( '*', '', $row->nodes[ 11 ]->text() ) ) ): 0,
                                    'reprise'   =>  ( isset( $row->nodes[ 13 ] ) ) ? trim( str_replace( '*', '', $row->nodes[ 13 ]->text() ) ): 0,
                                );

                                $admittedSection[ 'Course' ][] = $course;
                            }
                        }
                    } elseif ( strlen( $name ) < 3 and $check_admitted ) {
                        if ( isset( $row->nodes[ 3 ] ) and trim( $row->nodes[ 3 ]->text() ) != 'Crédits obtenus' ) {
                            $admittedSection[ 'credits_admitted' ]  = ( int )trim( $row->nodes[ 3 ]->text() );
                            $admittedSection[ 'credits_gpa' ]       = ( int )trim( $row->nodes[ 5 ]->text() );
                            $admittedSection[ 'points' ]            = str_replace( ',', '.', trim( $row->nodes[ 7 ]->text() ) );
                            $admittedSection[ 'gpa' ]               = str_replace( ',', '.', trim( $row->nodes[ 9 ]->text() ) );

                            $admittedSections[] = $admittedSection;
                            $admittedSection = array( 'idul' => $this->idul );
                        }
                    }
                    break;
            }
        }

        $report[ 'programs' ] = serialize( $programs );

        return ( array(
            'status'            =>  true,
            'userInfo'          =>  $userInfo,
            'md5Hash'           =>  $md5Hash,
            'report'            =>  array( 'Report' => $report, 'Semester' => $semesters, 'AdmittedSection' => $admittedSections )
        ) );
	}
	
	// Schedule
	public function getSchedule ( $md5Hash, $requestedSemester = '' ) {
        // If no requested semester, try to fetch semesters after and before current date
        if ( $requestedSemester == '' ) {
            $suggestedSemesters = array(
                ( date( 'Y' ) + 1 ) . "01",
                date( 'Y' ) . "09",
                date( 'Y' ) . "05",
                date( 'Y' ) . "01",
                ( date( 'Y' ) - 1 ) . "09",
                ( date( 'Y' ) - 1 ) . "05",
                ( date( 'Y' ) - 1 ) . "01"
            );

            $semesters = array();
        } else {
            $suggestedSemesters = array( $requestedSemester );
        }

        $schedule = array();

        foreach ( $suggestedSemesters as $semester ) {
            // Get list of student report page
            $request = $this->_fetchPage( '/pls/etprod7/bwskfshd.P_CrseSchdDetl', 'POST', array( 'term_in' => $semester ) );

            if ( !strpos( $request[ 'response' ], "Vous n'êtes pas actuellement inscrit pour la session." ) ) {
                // Parse DOM structure from response
                $this->domparser->load( $request[ 'response' ] );
                $tables = $this->domparser->find( 'table.datadisplaytable' );

                // Check if similar data already exists in DB
                if ( array_key_exists( 'schedule-' . $semester, $md5Hash ) && md5( serialize( $tables ) ) == $md5Hash[ 'schedule-' . $semester ] ) {
                    // Data already exists in DB, if not force to reload, quit
                    if ( !$this->forceReload )
                        continue;
                } else {
                    // Update MD5 Hash
                    $md5Hash[ 'schedule-' . $semester ] = md5( serialize( $tables ) );
                }

                $scheduleSemester = array(
                    'Course'    =>  array(),
                    'semester'  =>  $semester
                );
                $course = array( );

                for ( $n = 1; $n < count( $tables ); $n++ ) {
                    if ( $tables[ $n ]->nodes[ 1 ]->text() == 'Horaires prévus' ) {
                        // Find classes in semester schedule
                        $rows = $tables[ $n ]->find( 'tr' );


                        for ( $i = 1; $i < count( $rows ); $i++ ) {
                            $row = $rows[ $i ];
                            $class = array(
                                'type'     =>  trim( $row->nodes[ 1 ]->text() ),
                                'hours'    =>  explode( ' - ', trim( str_replace( 'ACU', '', $row->nodes[ 3 ]->text() ) ) ),
                                'day'      =>  trim( str_replace( ' ', '', $row->nodes[ 5 ]->text() ) ),
                                'location' =>  trim( str_replace( 'ACU', '', $row->nodes[ 7 ]->text() ) ),
                                'dates'    =>  explode( ' - ', trim( $row->nodes[ 9 ]->text() ) ),
                                'teaching' =>  trim( $row->nodes[ 11 ]->text() ),
                                'teacher'  =>  trim( str_replace( 'ACU', '', $row->nodes[ 13 ]->text() ) )
                            );

                            if ( isset( $course[ 'nrc' ] ) && !empty( $course[ 'nrc' ] ) )
                                $class[ 'nrc' ] = $course[ 'nrc' ];
                            
                            // Parse class start/end hours
                            if ( count( $class[ 'hours' ] ) == 2 ) {
                                if ( strpos( $class[ 'hours' ][ 0 ], ':50' ) ) {
                                    $class[ 'hours' ][ 0 ] = substr( $class[ 'hours' ][ 0 ], 0, strpos( $class[ 'hours' ][ 0 ], ':' ) );
                                    $class[ 'hours' ][ 0 ]++;
                                }
                                if ( strpos( $class[ 'hours' ][ 1 ], ':50' ) ) {
                                    $class[ 'hours' ][ 1 ] = substr( $class[ 'hours' ][ 1 ], 0, strpos( $class[ 'hours' ][ 1 ], ':' ) );
                                    $class[ 'hours' ][ 1 ]++;
                                }

                                $class[ 'hour_start' ] = str_replace( ':00', '', str_replace( ':30', '.5', str_replace( ':20', '.5', $class[ 'hours' ][ 0 ] ) ) );
                                $class[ 'hour_end' ] = str_replace( ':00', '', str_replace( ':30', '.5', str_replace( ':20', '.5', $class[ 'hours' ][ 1 ] ) ) );
                            } else {
                                $class[ 'hour_start' ] = '';
                                $class[ 'hour_end' ] = '';
                            }
                            unset( $class[ 'hours' ] );

                            // Parse class start/end dates
                            $class[ 'date_start' ] = str_replace( '/', '', $class[ 'dates' ][ 0 ] );
                            $class[ 'date_end' ] = str_replace( '/', '', $class[ 'dates' ][ 1 ] );
                            unset( $class[ 'dates' ] );

                            // Special line for 2012 student strike
                            if ( $class[ 'type' ] == 'Plage horaire (grève)' and ( empty( $class[ 'hour_start' ] ) ) ) $class = array();

                            // Add class to course
                            if ( !empty( $class ) ) {
                                $class[ 'idul' ] = $this->idul;
                                $course[ 'Class' ][] = $class;
                            }
                        }

                        // Add course to semester schedule
                        if ( !empty( $course ) ) {
                            $course[ 'idul' ] = $this->idul;
                            $course[ 'semester' ] = $semester;
                            $scheduleSemester[ 'Course' ][] = $course;
                        }

                        $course = array();
                    } else {
                        // Parse course name and code
                        $name = trim( $tables[ $n ]->nodes[ 1 ]->text() );
                        $name = explode( ' - ', $name );
                        $course[ 'title' ] = trim( $name[ 0 ] );
                        $course[ 'code' ] = strtoupper( str_replace( ' ', '-', trim( $name[ 1 ] ) ) );
                        if ( isset( $name[ 2 ] ) ) $course[ 'section' ] = trim( $name[ 2 ] );

                        // Find course info
                        $rows = $tables[ $n ]->find( 'tr' );
                        foreach ( $rows as $row ) {
                            $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
                            $value = trim( str_replace( ':', '', $row->nodes[ 3 ]->text() ) );

                            switch ( $name ) {
                                case 'NRC':
                                    $course[ 'nrc' ] = $value;
                                    break;
                                case 'Professeur':
                                    $course[ 'teacher' ] = $value;
                                    break;
                                case 'Crédits':
                                    $course[ 'credits' ] = ( int )$value;
                                    break;
                                case 'Cycle':
                                    if ( $value == 'Premier cycle' ) {
                                        $course[ 'cycle' ] = 1;
                                    } elseif ( $value == 'Deuxième cycle' ) {
                                        $course[ 'cycle' ] = 2;
                                    } elseif ( $value == 'Troisième cycle' ) {
                                        $course[ 'cycle' ] = 3;
                                    }
                                    break;
                                case 'Campus':
                                    $course[ 'campus' ] = $value;
                                    break;
                            }
                        }
                    }
                }

                // Add semester to schedule
                if ( !empty( $scheduleSemester[ 'Course' ] ) ) {
                    $scheduleSemester[ 'idul' ] = $this->idul;
                    $schedule[] = array( 'ScheduleSemester' => $scheduleSemester );
                }
            }
        }

        return ( array(
            'status'    =>  true,
            'md5Hash'   =>  $md5Hash,
            'schedule'  =>  $schedule
        ) );
	}
	
	public function getTuitionFees ( $md5Hash, $requested_semester = '' ) {
        // Fetch PDF summary list
        $request = $this->_fetchPage( '/pls/etprod7/y_bwskfact.p_factures' );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $tables = $this->domparser->find( 'table.datadisplaytable' );

        // Fetch tuition fees summary by semester
        $pdfStatements = array();
        $rows = $tables[ 0 ]->find( 'tr' );
        foreach ( $rows as $index => $row ) {
            if ( $index != 0 ) {
                if ( isset( $row->nodes[ 5 ] ) ) $semesterName = trim( str_replace( ':', '', $row->nodes[ 5 ]->text() ) );
                if ( isset( $row->nodes[ 9 ] ) ) {
                    // Extract link
                    $links = $row->nodes[ 9 ]->find( 'a' );
                    $statementUrl = $links[ 0 ]->attr[ 'href' ];
                }

                $pdfStatements[ $this->_convertSemester( $semesterName ) ] = 'https://capsuleweb.ulaval.ca/pls/etprod7/' . $statementUrl;
            }
        }

        // Get list of tuition fees page
        $request = $this->_fetchPage( '/pls/etprod7/bwskoacc.P_ViewAcct' );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $tables = $this->domparser->find( 'table.datadisplaytable' );

        // Check if similar data already exists in DB
        if ( md5( serialize( $tables ) ) == $md5Hash ) {
            // Data already exists in DB, if not force to reload, quit
            if ( !$this->forceReload ) return true;
        } else {
            // Update MD5 Hash
            $md5Hash = md5( serialize( $tables ) );
        }

        $account = array( 'idul' => $this->idul, 'Semester' => array() );

        // Fetch student tuition account info
        $rows = $tables[ 0 ]->find( 'tr' );
        foreach ( $rows as $row ) {
            $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
            $value = trim( $row->nodes[ 3 ]->text() );
            switch ( $name ) {
                case 'Numéro de client':
                    $account[ 'account_number' ] = $value;
                    break;
                default:
                    if ( strpos( $name, 'Numéro d\'assuré AELIÉS' ) !== false ) {
                        $account[ 'aelies_number' ] = str_replace( ' ', '', $value );
                    }
                    break;
            }
        }

        // Fetch tuition fees summary by semester
        $semester = array();
        $rows = $tables[ 1 ]->find( 'tr' );
        foreach ( $rows as $row ) {
            if ( isset( $row->nodes[ 1 ] ) ) $name = trim( str_replace( ':', '', $row->nodes[ 1 ]->text() ) );
            if ( isset( $row->nodes[ 3 ] ) ) $value = trim( str_replace( ' ', '', str_replace( ',', '.', $row->nodes[ 3 ]->text() ) ) );

            switch ( $name ) {
                case 'Description':
                    break;
                case 'Frais de session':
                    $semester[ 'total' ] = ( float )str_replace( ',', '', str_replace( '$', '', $value ) );
                    break;
                case 'Crédits et paiements de session':
                    $semester[ 'payments' ] = ( float )str_replace( ',', '', str_replace( '$', '', $value ) );
                    break;
                case 'Solde de session':
                    $semester[ 'balance' ] = ( float )str_replace( ',', '', str_replace( '$', '', $value ) );

                    // Save last semester and start a new one
                    if ( !empty( $semester ) ) {
                        $semester[ 'idul' ] = $this->idul;
                        $semester[ 'fees' ] = serialize( $semester[ 'fees' ] );
                        $account[ 'Semester' ][] = $semester;
                    }
                    $semester = array();
                    break;
                case 'Solde du compte':
                    $account[ 'balance' ] = ( float )str_replace( ',', '', str_replace( '$', '', $value ) );
                    break;
                default:
                    if ( strpos( $name, 'Automne ' ) !== false || strpos( $name, 'Été ' ) !== false || strpos( $name, 'Hiver ' ) !== false ) {
                        $semester[ 'semester' ] = $this->_convertSemester( $name );
                        if ( !empty( $pdfStatements[ $semester[ 'semester' ] ] ) ) {
                            $semester[ 'pdf_statement_url' ] = $pdfStatements[ $semester[ 'semester' ] ];
                        }
                    } elseif ( str_replace( ' ', '', $name ) != '' ) {
                        if ( str_replace( ' ', '', $value ) != '' ) {
                            $semester[ 'fees' ][] = array( 'name' => $name, 'amount' => ( float )str_replace( ',', '', str_replace( '$', '', $value ) ) );
                        }
                    }
            }
        }

        if ( !empty( $semester ) ) {
            $semester[ 'idul' ] = $this->idul;
            $semester[ 'fees' ] = serialize( $semester[ 'fees' ] );
            $account[ 'Semester' ][] = $semester;
        }

        return ( array(
            'status'    =>  true,
            'md5Hash'   =>  $md5Hash,
            'tuitions'  =>  array( 'TuitionAccount' => $account )
        ) );
	}
	
	public function registerCourses ( $nrcArray, $semester ) {
        // Fetch registration page
        $request = $this->_fetchPage( '/pls/etprod7/bwskfreg.P_AltPin', 'POST', array( 'term_in' => $semester ) );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $table = $this->domparser->find( 'table.datadisplaytable' );

        // Log registration data
        CakeLog::write( 'registration', '------------------------------------------------------------------' );
        CakeLog::write( 'registration', '1ère requête [ IDUL : ' . $this->idul . ' ]' );
        CakeLog::write( 'registration', $request[ 'response' ] );
        CakeLog::write( 'registration', '------------------------------------------------------------------' );

        // Check for error messages in the page
        if ( preg_match( '/Il vous est impossible\sde vous inscrire dans Capsule, car aucune période/', $request[ 'response' ] ) ) {
            // Return error message
            return 'error:Inscription impossible puisque vous n\'avez pas de période d\'inscription accordée. <br>Veuillez communiquer avec votre direction de programme.';
        } elseif ( preg_match( '/Désolé ce service est présentement\sinaccessible/', $request[ 'response' ] ) ) {
            // Return error message
            return 'error:Erreur lors de l\'inscription : Capsule est hors service. Veuillez réessayer plus tard.';
        } elseif( preg_match( '/Vous pouvez vous\sinscrire durant la période suivante/', $request[ 'response' ] ) ) {
            // Detect start of registration period for this user
            $cells = $table[ 0 ]->find( 'td.dddefault' );
            $initialDate = implode( '-', array_reverse( explode( '/', $cells[ 0 ]->text() ) ) ) . ' à ' . $cells[ 1 ]->text();

            // Return error message
            return 'error:Erreur lors de l\'inscription : votre période d\'inscription commencera le ' . $initialDate;
        }

        $postString = "term_in=".$semester."&RSTS_IN=DUMMY&assoc_term_in=DUMMY&CRN_IN=DUMMY&start_date_in=DUMMY&end_date_in=DUMMY&SUBJ=DUMMY&CRSE=DUMMY&SEC=DUMMY&LEVL=DUMMY&CRED=DUMMY&GMOD=DUMMY&TITLE=DUMMY&MESG=DUMMY&REG_BTN=DUMMY";

        if ( count ( $table ) != 0 ) {
            $inputFields = $table[ 0 ]->find( 'input' );
            // Parse all table input fields
            foreach( $inputFields as $field ) {
                if ( $field->name != '' )  {
                    $postString .= '&' . $field->name . '=' . urlencode( utf8_decode( $field->value ) );

                    if ( $field->name == 'MESG' ) {
                        $postString .= '&RSTS_IN=';
                    }
                }
            }
        }

        // Add NRC of courses to be registered
        for ( $n = 1; $n < 11; $n++ ) {
            $postString .= '&RSTS_IN=RW';
            if ( !empty( $nrcArray[ ( $n - 1 ) ] ) ) {
                $postString .= '&CRN_IN=' . $nrcArray[ ( $n - 1 ) ];
            } else {
                $postString .= '&CRN_IN=';
            }

            $postString .= '&assoc_term_in=&start_date_in=&end_date_in=';
        }

        $form = $this->domparser->find( 'form' );

        if ( empty( $form ) || !is_array( $form ) ) {
            return 'error:Réponse invalide du serveur Capsule';
        }
        
        $inputFields = $form[ 1 ]->find( 'input' );
        // Parse all form input fields
        foreach( $inputFields as $field ) {
            if ( $field->name == 'regs_row' || $field->name == 'wait_row' || $field->name == 'add_row' ) {
                $postString .= '&' . $field->name . '=' . urlencode( utf8_decode( $field->value ) );
            }
        }

        $postString .= '&REG_BTN=' . urlencode( utf8_decode( 'Soumettre les modifications' ) );

        // Submit registration form
        $request = $this->_fetchPage( '/pls/etprod7/bwckcoms.P_Regs', 'POST', array(), true, array( 'PostString' => $postString ) );

        // Log registration data
        CakeLog::write( 'registration', '2e requête [ IDUL : ' . $this->idul . ' ]' );
        CakeLog::write( 'registration', $request[ 'response' ] );
        CakeLog::write( 'registration', '------------------------------------------------------------------' );

        // Check for error messages in the page
        if ( preg_match( '/Une erreur s\'est\sproduite empêchant l\'exécution de votre\sopération/', $request[ 'response' ] ) ) {
            // Return error message
            return 'error:Erreur lors de l\'inscription. Veuillez réessayer.';
        }

        // Check if dates need to be confirmed
        if ( preg_match( '/p_proc_start_date_confirm/', $request[ 'response' ] ) ) {
            // Confirm dates

            // Parse DOM structure from response
            $this->domparser->load( $request[ 'response' ] );
            $forms = $this->domparser->find( 'form' );
            $inputFields = $forms[ 1 ]->find( 'input' );
            $table = $this->domparser->find( 'table.datadisplaytable' );
            $postString = array();
            
            // Parse all form input fields
            foreach( $inputFields as $field ) {
                if ( !empty( $field->name ) ) {
                    $postString[] = $field->name . '=' . urlencode( $field->value );
                }
            }

            $postString = implode( '&', $postString );

            // Submit 2nd of registration form
            $request = $this->_fetchPage( '/pls/etprod7/bwckcoms.p_proc_start_date_confirm', 'POST', array(), true, array( 'PostString' => $postString ) );

            // Log registration data
            CakeLog::write( 'registration', '3e requête [ IDUL : ' . $this->idul . ' ]' );
            CakeLog::write( 'registration', $request[ 'response' ] );
            CakeLog::write( 'registration', '------------------------------------------------------------------' );
        }

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $table = $this->domparser->find( 'table.datadisplaytable' );

        $coursesStatus = array();

        $inputFields = $table[ 0 ]->find( 'input' );
        // Parse all table input fields
        foreach( $inputFields as $field ) {
            if ( $field->name == 'CRN_IN' && in_array( $field->value, $nrcArray ) )  {
                $coursesStatus[ $field->value ] = array(
                    'registered'    =>  true
                );
            }
        }

        // Log registration results
        CakeLog::write( 'registration-success', $this->idul . ' : ' . implode( ', ', $nrcArray ) );

        if ( strpos( $request[ 'response' ], 'Erreur d\'ajout' ) > 1 ) {
            // Parse registration errors
            if ( isset( $table[ 2 ] ) ) {
                $rows = $table[ 2 ]->find( 'tr' );
            } else {
                $rows = $table[ 0 ]->find( 'tr' );
            }

            foreach( $rows as $rowIndex => $row ) {
                if ( $rowIndex != 0 ) {
                    $errorMessage = $row->nodes[ 1 ]->text();
                    $nrc = $row->nodes[ 3 ]->text();
                    if ( in_array( $nrc, $nrcArray ) )  {
                        $coursesStatus[ $nrc ] = array(
                            'registered'   =>  false,
                            'error'        =>  $errorMessage
                        );
                    }
                }
            }
        }

        return $coursesStatus;
	}
	
	public function removeCourse ( $nrc, $semester ) {
        // Fetch registration page
        $request = $this->_fetchPage( '/pls/etprod7/bwskfreg.P_AltPin', 'POST', array( 'term_in' => $semester ) );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $table = $this->domparser->find( 'table.datadisplaytable' );

        $postString = "term_in=".$semester."&RSTS_IN=DUMMY&assoc_term_in=DUMMY&CRN_IN=DUMMY&start_date_in=DUMMY&end_date_in=DUMMY&SUBJ=DUMMY&CRSE=DUMMY&SEC=DUMMY&LEVL=DUMMY&CRED=DUMMY&GMOD=DUMMY&TITLE=DUMMY&MESG=DUMMY&REG_BTN=DUMMY";

        $rows = $table[ 0 ]->find( 'tr' );
        foreach ( $rows as $rowIndex => $row ) {
            if ( $rowIndex != 0 ) {
                $inputFields = $row->find( 'input' );

                foreach( $inputFields as $field ) {
                    $postString .= '&' . $field->name . '=' . urlencode( $field->value );

                    if ( $field->name == 'CRN_IN' ) {
                        if ( $field->value == $nrc ) {
                            $postString .= '&RSTS_IN=DW';
                        } else {
                            $postString .= '&RSTS_IN=';
                        }
                    }
                }
            }
        }
        /*
        $table = $this->domparser->find( 'table.dataentrytable' );
        $inputFields = $table[0]->find( 'input' );

        foreach( $inputFields as $field ) {
            if ( $field->name != '' )  {
                $postString .= '&' . $field->name . '=' . urlencode( $field->value );
            }
        }

        $form = $this->domparser->find( 'form' );
        $inputFields = $form[1]->find( 'input' );

        $postString = '';

        // Parse all form input fields
        foreach( $inputFields as $field ) {
            if ( $field->name != '' )  {
                $postString .= '&' . $field->name . '=' . urlencode( $field->value );

                // If field contains NRC : check if current NRC is the one to be removed
                if ( $field->name == 'CRN_IN' ) {
                    if ( $field->value == $nrc ) {
                        // Add remove course action
                        $postString = substr( $postString, 0, strrpos( $postString, '&' ) ) . '&RSTS_IN=DW' . substr( $postString, strrpos( $postString, '&' ) );
                    } elseif ( $field->value != '' ) {
                        // Do nothing
                        $postString = substr( $postString, 0, strrpos( $postString, '&' ) ) . '&RSTS_IN=' . substr( $postString, strrpos( $postString, '&' ) );
                    }
                }
            }
        }
*/
        for ( $n = 1; $n < 11; $n++ ) {
            $postString .= '&RSTS_IN=RW&CRN_IN=&assoc_term_in=&start_date_in=&end_date_in=';
        }

        $form = $this->domparser->find( 'form' );
        $inputFields = $form[ 1 ]->find( 'input' );
        // Parse all form input fields
        foreach( $inputFields as $field ) {
            if ( $field->name == 'regs_row' || $field->name == 'wait_row' || $field->name == 'add_row' ) {
                $postString .= '&' . $field->name . '=' . urlencode( $field->value );
            }
        }

        $postString .= '&REG_BTN=' . urlencode( 'Soumettre les modifications' );

        // Submit registration form
        $request = $this->_fetchPage( '/pls/etprod7/bwckcoms.P_Regs', 'POST', array(), true, array( 'PostString' => $postString ) );

         // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );

        $form = $this->domparser->find( 'form' );
        $inputFields = $form[ 1 ]->find( 'input' );

         // Parse all form input fields
        foreach( $inputFields as $field ) {
            if ( $field->name == 'CRN_IN' && $field->value == $nrc ) {
                // NRC found in response page content : course has not been removed
                return false;
            }
        }

        return true;
	}
	
	public function fetchCourse ( $code, $semester, $fetchClasses = true ) {
        ini_set( 'memory_limit','50M' );
        $code = explode( '-', strtoupper( $code ) );

        // Fetch course page
        $request = $this->_fetchPage( '/pls/etprod7/bwckctlg.p_disp_course_detail?cat_term_in=' . $semester . '&subj_code_in=' . $code[ 0 ] . '&crse_numb_in=' . $code[ 1 ] );

        if ( !strpos( $request[ 'response' ], "Aucun cours à afficher" ) ) {
            $course = array( 'code' => implode( '-', $code ) );

            // Parse DOM structure from response
            $this->domparser->load( $request[ 'response' ] );
            $tables = $this->domparser->find( 'table.datadisplaytable' );

            // Get course title
            $firstCell = $tables[ 0 ]->find( 'tr td.nttitle' );
            $course[ 'title' ] = trim( strip_tags( substr( $firstCell[ 0 ]->text(), strpos( $firstCell[ 0 ]->text(), " - " ) + 2 ) ) );

            $secondCell = $tables[ 0 ]->find( 'tr td.ntdefault' );
            $parts = explode( '<br>', $secondCell[ 0 ] );

            $checkRestrictions = false;
            $checkPrerequisites = false;

            foreach ( $parts as $index => $part ) {
                if ( $index == 0 ) {
                    $course[ 'description' ] = trim( strip_tags( str_replace( "", "'", $part ) ) );
                } elseif ( $checkRestrictions ) {
                    if ( strpos( $part, '<span' ) ) {
                        $checkRestrictions = false;
                    } else {
                        if ( $course[ 'restrictions' ] == '' ) {
                            $course[ 'restrictions' ] = trim( $part );
                        } else {
                            $course[ 'restrictions' ] .= '<br />' . trim( $part );
                        }
                    }
                } elseif ( $checkPrerequisites ) {
                    if ( trim( $part ) == '' ) {
                        $checkPrerequisites = false;
                    } else {
                        $course[ 'prerequisites' ] = trim( strip_tags( $part ) );
                    }
                } elseif ( strpos( $part, "Crédits" ) ) {
                    if ( strpos( $part, ' OR ' ) ) {
                        $course[ 'credits' ] = substr( $part, strpos( $part, "OR" ) + 3 );
                        $course[ 'credits' ] = ( int )trim( substr( $course[ 'credits' ], 0, strpos( $course[ 'credits' ], "," ) + 1 ) );
                    } else {
                        $course[ 'credits' ] = ( int )trim( substr( $part, 0, strpos( $part, "," ) + 1 ) );
                    }
                } elseif ( strpos( $part, "Heures de cours" ) ) {
                    $course[ 'hours_theory' ] = ( int )trim( substr( $part, 0, strpos( $part, "," ) + 1 ) );
                } elseif ( strpos( $part, "Heures de labo" ) ) {
                    $course[ 'hours_lab' ] = ( int )trim( substr( $part, 0, strpos( $part, "," ) + 1 ) );
                } elseif ( strpos( $part, "Autres heures" ) ) {
                    $course[ 'hours_other' ] = ( int )trim( substr( $part, 0, strpos( $part, "," ) + 1 ) );
                } elseif ( strpos( $part, "Cycle(s)" ) ) {
                    $cycle = trim( substr( $part, strpos( $part, "</span>" ) + 7 ) );
                    switch ( $cycle ) {
                        case 'Premier cycle':
                            $course[ 'cycle' ] = 1;
                        break;
                        case 'Deuxième cycle':
                            $course[ 'cycle' ] = 2;
                        break;
                        case 'Troisième cycle':
                            $course[ 'cycle' ] = 3;
                        break;
                    }
                } elseif ( strpos( $part, "Faculté:" ) ) {
                    $course[ 'faculty' ] = trim( substr( $part, strpos( $part, ":" ) + 1 ) );
                } elseif ( strpos( $part, "Département:" ) ) {
                    $course[ 'department' ] = trim( substr( $part, strpos( $part, ":" ) + 1 ) );
                } elseif ( strpos( $part, "Restrictions:" ) ) {
                    $checkRestrictions = true;

                    $course[ 'restrictions' ] = '';
                } elseif ( strpos( $part, "Préalables:" ) ) {
                    $checkPrerequisites = true;
                }
            }

            if ( $fetchClasses ) {
                $course[ 'Class' ] = array();

                $classes = $this->fetchClasses( $course[ 'code' ], $semester, $request[ 'response' ] );

                if ( $classes && !empty( $classes ) ) {
                    $course[ 'Class' ] += $classes[ 'Class' ];
                    $course[ 'av' . $semester ] = true;
                } else {
                    $course[ 'av' . $semester ] = false;
                }

                $course[ 'checkup_' . $semester ] = time();
            }

            return array( 'UniversityCourse' => $course );
        } else {
            return false;
        }
    }
	
    public function fetchClasses ( $code, $semester, $requestContent = '' ) {
        $code = explode( '-', strtoupper( $code ) );
        $classes = array();

        if ( empty( $request[ 'content' ] ) ) {
            // Fetch course page
            $request = $this->_fetchPage( '/pls/etprod7/bwckctlg.p_disp_course_detail?cat_term_in=' . $semester . '&subj_code_in=' . $code[ 0 ] . '&crse_numb_in=' . $code[ 1 ] );
        } else {
            $request[ 'response' ] = $request[ 'content' ];
        }

        if ( !strpos( $request[ 'response' ], "Aucun cours à afficher" ) ) {
            $part = substr( $request[ 'response' ], strpos( $request[ 'response' ], 'Mode d\'enseignement:' ), 1000 );
            $part = substr( $part, 0, strpos( $part, '<br>' ) );

            if ( strpos( $part, "href=" ) ) {
                $links = array();
                
                $part = str_replace( 'href= "', 'href="', $part );
                if ( strpos( $part, "</a>, <Aa" ) ) {
                    // 2 modes d'enseignement
                    $link = substr( $part, strpos( $part, "/pls/etprod7/" ) );
                    $link = str_replace( "&amp;", "&", substr( $link, 0, strpos( $link, "\"" ) ) );
                    $links[] = $link;
                    
                    $link = substr( $part, strpos( $part, ">, " ) + 2 );
                    $link = substr( $link, strpos( $link, "/pls/etprod7/" ) );
                    $link = str_replace( " &amp;", "&", substr( $link, 0, strpos( $link, "\"" ) ) );
                    $links[] = $link;
                } else {
                    $link = substr( $part, strpos( $part, "/pls/etprod7/" ) );
                    $link = str_replace( "&amp;", "&", substr( $link, 0, strpos( $link, "\"" ) ) );
                    $links[] = $link;
                }

                // Fetch course's classes
                foreach ( $links as $link ) {
                    // Fetch class page
                    $request = $this->_fetchPage( $link );

                    $class = array();

                    // Parse DOM structure from response
                    $this->domparser->load( $request[ 'response' ] );
                    $tables = $this->domparser->find( '.pagebodydiv>table.datadisplaytable' );
                    $timetables = $this->domparser->find( 'table.datadisplaytable tbody tr td.dddefault table.datadisplaytable' );

                    $tableIndex = 0;

                    $rows = $tables[ 0 ]->children( -1 );

                    foreach ( $rows as $i => $row ) {
                        if ( $i == 0 ) continue;

                        $cell = $row->nodes[ 1 ];

                        if ( isset( $cell->attr[ 'class' ] ) && $cell->attr[ 'class' ] == 'ddlabel' ) {
                            // Title cell

                            $class = array( 'semester' => $semester );

                            $title = $cell->text();

                            $title = explode( " - ", $title );

                            // Extract NRC and class section from title
                            $class[ 'nrc' ] = trim( $title[ 1 ] );
                            if ( count( $title ) > 4 ) {
                                $class[ 'nrc' ] = trim( $title[ 2 ] );
                            } else {
                                $class[ 'nrc' ] = trim( $title[ 1 ] );
                            }
                            $class[ 'idcourse' ] = $code[ 0 ] . "-" . $code[ 1 ];
                        } else if ( isset( $cell->attr[ 'class' ] ) && $cell->attr[ 'class' ] == 'dddefault' ) {
                            // Content cell

                            $cell = $row->nodes[ 1 ];
                            $parts = explode( '<br>', $cell );

                            foreach ( $parts as $index => $part ) {
                                if ( $index == 0 ) {
                                    $class[ 'notes' ] = trim( strip_tags( $part ) );
                                } elseif ( strpos( $part, "Campus" ) ) {
                                    $class[ 'campus' ] = trim( substr( $part, strpos( $part, ":" ) + 1 ) );
                                }
                            }

                            $class[ 'timetable' ] = array();

                            // Get corresponding table
                            if ( !isset( $timetables[ $tableIndex ] ) ) continue;
                            $currentTimetable = $timetables[ $tableIndex ];
                            $tableIndex++;

                            $subrows = $currentTimetable->find( 'tr' );

                            foreach ( $subrows as $index => $subrow ) {
                                $subclass = array();

                                if ( $index > 0 ) {
                                    if ( isset( $subrow->nodes[ 1 ] ) ) $subclass[ 'type' ] = trim( $subrow->nodes[ 1 ]->text() );
                                    if ( isset( $subrow->nodes[ 3 ] ) ) {
                                        $hours = trim( strip_tags( $subrow->nodes[ 3 ]->text() ) );
                                        if ( $hours != 'ACU' ) {
                                            $hours = explode( '-', str_replace( " ", "", $hours ) );
                                            $subclass[ 'hour_start' ] = trim( $hours[ 0 ] );
                                            $subclass[ 'hour_end' ] = trim( $hours[ 1 ] );
                                        }
                                    }
                                    if ( isset( $subrow->nodes[ 5 ] ) ) {
                                        $subclass[ 'day' ] = trim( str_replace( "", "'", strtoupper( strip_tags( $subrow->nodes[ 5 ]->text() ) ) ) );
                                    }
                                    if ( isset( $subrow->nodes[ 7 ] ) ) {
                                        $local = trim( strip_tags( $subrow->nodes[ 7 ]->text() ) );
                                        if ( strpos( $local, 'ACU' ) != 0 ) {
                                            $subclass[ 'local' ] = $local;
                                        }
                                    }
                                    if ( isset( $subrow->nodes[ 9 ] ) ) {
                                        $days = explode( '-', trim( strip_tags( $subrow->nodes[ 9 ]->text() ) ) );
                                        $subclass[ 'day_start' ] = trim( str_replace( '/', '', $days[ 0 ] ) );
                                        $subclass[ 'day_end' ] = trim( str_replace( '/', '', $days[ 1 ] ) );
                                    }
                                    if ( isset( $subrow->nodes[ 13 ] ) ) {
                                        $teacher = trim( strip_tags( $subrow->nodes[ 13 ]->text() ) );
                                        if ( strpos( $teacher, 'ACU' ) != 0 ) {
                                            $class[ 'teacher' ] = $teacher;
                                        }
                                    }

                                    $class[ 'timetable' ][] = $subclass;
                                }
                            }

                            $classes[] = $class;
                        }
                    }
                }
            }

            return array( 'Class' => $classes );
        } else {
            return false;
        }
    }

	public function updateClassSpots ( $nrc, $semester ) {
        // Get class page
        $request = $this->_fetchPage( '/pls/etprod7/bwckschd.p_disp_detail_sched?term_in=' . $semester . '&crn_in=' . $nrc );

        // Parse DOM structure from response
        $this->domparser->load( $request[ 'response' ] );
        $tables = $this->domparser->find( '.pagebodydiv>table.datadisplaytable table.datadisplaytable' );

        $cells = $tables[ 0 ]->find( 'tr td.dddefault' );

        $spots = array(
            'nrc'           =>  $nrc,
            'last_update'   =>  time()
        );        

        foreach ( $cells as $index => $cell ) {
            switch ( $index ) {
                case 0:
                    $spots[ 'total' ] = ( int )$cell->text();
                    break;
                case 1:
                    $spots[ 'registered' ] = ( int )$cell->text();
                    break;
                case 2:
                    $spots[ 'remaining' ] = ( int )$cell->text();
                    break;
                case 3:
                    $spots[ 'waiting_total' ] = ( int )$cell->text();
                    break;
                case 4:
                    $spots[ 'waiting_registered' ] = ( int )$cell->text();
                    break;
                case 5:
                    $spots[ 'waiting_remaining' ] = ( int )$cell->text();
                    break;
            }
        }

        return $spots;
	}
	
    public function searchCourses ( $searchRequest ) {
        if ( !empty( $searchRequest[ 'code' ] ) ) {
            $code = strtoupper( trim( str_replace( '-', '', str_replace( ' ', '', $searchRequest[ 'code' ] ) ) ) );

            $postString = (
                'term_in=' . $searchRequest[ 'semester' ] . 
                '&sel_subj=dummy' .
                '&sel_day=dummy' .
                '&sel_schd=dummy' .
                '&sel_insm=dummy' .
                '&sel_camp=dummy' .
                '&sel_levl=dummy' .
                '&sel_sess=dummy' .
                '&sel_instr=dummy' .
                '&sel_ptrm=dummy' .
                '&sel_attr=dummy' .
                '&sel_subj=' . substr( $code, 0, 3 ) .
                '&sel_crse=' . substr( $code, 3, 4 ) .
                '&sel_title=' .
                '&sel_schd=%25' .
                '&sel_from_cred=' .
                '&sel_to_cred=' .
                '&sel_camp=%25' .
                '&sel_levl=%25' .
                '&sel_ptrm=%25' .
                '&sel_instr=%25' .
                '&sel_sess=%25' .
                '&sel_attr=%25' .
                '&begin_hh=0' .
                '&begin_mi=0' .
                '&begin_ap=x' .
                '&end_hh=23' .
                '&end_mi=59' .
                '&end_ap=x'
            );
        } else {
            if ( is_array( $searchRequest[ 'subject' ] ) ) {
                $postString =
                    'term_in=' . $searchRequest[ 'semester' ] . 
                    '&sel_subj=dummy' .
                    '&sel_day=dummy' .
                    '&sel_schd=dummy' .
                    '&sel_insm=dummy' .
                    '&sel_camp=dummy' .
                    '&sel_levl=dummy' .
                    '&sel_sess=dummy' .
                    '&sel_instr=dummy' .
                    '&sel_ptrm=dummy' .
                    '&sel_attr=dummy';

                foreach ( $searchRequest[ 'subject' ] as $subject ) {
                    $postString .= '&sel_subj=' . $subject;
                }

                $postString .=
                    '&sel_crse=' .
                    '&sel_title=' . urlencode( utf8_decode( $searchRequest[ 'keywords' ] ) ) .
                    '&sel_schd=%25' .
                    '&sel_from_cred=' .
                    '&sel_to_cred=' .
                    '&sel_camp=%25' .
                    '&sel_levl=%25' .
                    '&sel_ptrm=%25' .
                    '&sel_instr=%25' .
                    '&sel_sess=%25' .
                    '&sel_attr=%25' .
                    '&begin_hh=0' .
                    '&begin_mi=0' .
                    '&begin_ap=x' .
                    '&end_hh=23' .
                    '&end_mi=59' .
                    '&end_ap=x';
            } else {
                $postString = (
                    'term_in=' . $searchRequest[ 'semester' ] . 
                    '&sel_subj=dummy' .
                    '&sel_day=dummy' .
                    '&sel_schd=dummy' .
                    '&sel_insm=dummy' .
                    '&sel_camp=dummy' .
                    '&sel_levl=dummy' .
                    '&sel_sess=dummy' .
                    '&sel_instr=dummy' .
                    '&sel_ptrm=dummy' .
                    '&sel_attr=dummy' .
                    '&sel_subj=' . strtoupper( $searchRequest[ 'subject' ] ) .
                    '&sel_crse=' .
                    '&sel_title=' . urlencode( utf8_decode( $searchRequest[ 'keywords' ] ) ) .
                    '&sel_schd=%25' .
                    '&sel_from_cred=' .
                    '&sel_to_cred=' .
                    '&sel_camp=%25' .
                    '&sel_levl=%25' .
                    '&sel_ptrm=%25' .
                    '&sel_instr=%25' .
                    '&sel_sess=%25' .
                    '&sel_attr=%25' .
                    '&begin_hh=0' .
                    '&begin_mi=0' .
                    '&begin_ap=x' .
                    '&end_hh=23' .
                    '&end_mi=59' .
                    '&end_ap=x'
                );
            }
        }

        // Fetch search page
        $request = $this->_fetchPage( '/pls/etprod7/bwskfcls.P_GetCrse', 'POST', array(), true, array( 'PostString' => $postString ) );

        // Check if courses have been found
        if ( strpos( $request[ 'response' ], 'Aucun cours ne correspond' ) ) {
            return array();
        } else {
            $courses = array();

            // Parse DOM structure from response
            $this->domparser->load( $request[ 'response' ] );
            $tables = $this->domparser->find( 'table.datadisplaytable' );

            $rows = $tables[ 0 ]->find( 'tr' );
            foreach ( $rows as $rowIndex => $row ) {
                if ( $rowIndex > 1 ) {
                    // Check if course is available
                    if ( count( $row->nodes ) > 5 and strlen( trim( $row->nodes[ 3 ]->text() ) ) > 4 ) {
                        // Add course NRC and code to results list
                        $courses[ trim( $row->nodes[ 3 ]->text() ) ] = strtoupper( trim( $row->nodes[ 5 ]->text() ) ) . '-' . trim( $row->nodes[ 7 ]->text() );
                    }
                }
            }

            return $courses;
        }
    }

    public function fetchPortailCours() {
        $this->host = 'www.portaildescours.ulaval.ca';
        $this->debug = 1;
        //$this->fetcher->follow_redirect = 0;

        // Define request parameters
        $this->fetcher->set( array(
            'debug'             =>  $this->debug,
            'protocol'          =>  'https',
            'request_method'    =>  'GET'
        ) );

        // Define Host name
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  '/login.jsp?_js=true',
        );

        // Open connection to remote server
        $error = $this->fetcher->Open( $arguments );
        if ( !empty( $error ) ) return false;

        // Send request data to remote server
        $error = $this->fetcher->SendRequest( $arguments );
        if ( !empty( $error ) ) return false;

        // Read response headers from remote server
        $error = $this->fetcher->ReadReplyHeaders( $headers );
        if ( !empty( $error ) ) return false;

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = $response;

        // Close remote server connection
        $this->fetcher->Close();

         // Get class page
        //$this->fetcher->SaveCookies( $cookies );
        //$this->fetcher->cookies = $cookies;
        /*
        // Define Host name
        $arguments = array(
            'HostName'      =>  'www.portaildescours.ulaval.ca',
            'RequestURI'    =>  substr( $headers[ 'location' ], strpos( $headers[ 'location' ], ':443/' ) + 4 ),
        );

        // Open connection to remote server
        $error = $this->fetcher->Open( $arguments );
        if ( !empty( $error ) ) return false;

        // Send request data to remote server
        $error = $this->fetcher->SendRequest( $arguments );
        if ( !empty( $error ) ) return false;

        // Read response headers from remote server
        $error = $this->fetcher->ReadReplyHeaders( $headers );
        if ( !empty( $error ) ) return false;

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = $response;

        // Close remote server connection
        $this->fetcher->Close();
        */
        // Parse DOM structure from response
        //$this->domparser->load( $request[ 'response' ] );
        //$tables = $this->domparser->find( '.pagebodydiv>table.datadisplaytable table.datadisplaytable' );
    }

    private function _fetchPage ( $url, $method = 'GET', $postVars = array(), $checkPage = true, $otherArguments = array() ) {
        // Define request parameters
        $this->fetcher->set( array(
            'cookies'       =>  $this->cookies,
            'debug'         =>  $this->debug,
            'protocol'      =>  'https',
            'request_method'=>  $method
        ) );

        // Define Host name
        $arguments = array(
            'HostName'      =>  $this->host,
            'RequestURI'    =>  $url,
        );

        if ( !empty( $postVars ) )
            $arguments[ 'PostValues' ] = $postVars;

        if ( !empty( $otherArguments ) ) {
            $arguments += $otherArguments;
        }

        // Open connection to remote server
        $error = $this->fetcher->Open( $arguments );
        if ( !empty( $error ) ) return false;

        // Send request data to remote server
        $error = $this->fetcher->SendRequest( $arguments );
        if ( !empty( $error ) ) return false;

        // Read response headers from remote server
        $error = $this->fetcher->ReadReplyHeaders( $headers );
        if ( !empty( $error ) ) return false;

        // Read response content from remote server
        $this->fetcher->ReadWholeReplyBody( $response );
        $response = $response;

        // Close remote server connection
        $this->fetcher->Close();

        // Check data integrity
        if ( $checkPage ) {
            if ( strpos( $response, "<TITLE>Connexion utilisateur | Capsule | Université Laval</TITLE>" ) )
                return false;
        }

        // Clean HTML code
        if ( function_exists( 'tidy_repair_string' ) ) {
            $tidy = tidy_parse_string( $response );
            $tidy->cleanRepair();
        } else {
            $tidy = $response;
        }

        // Return request result
        return ( array( 'headers' => $headers, 'response' => utf8_encode( html_entity_decode( $tidy, ENT_COMPAT, 'cp1252' ) ) ) );
    }

	private function checkPage ( $data ) {
		if ( !strpos( $data, "<TITLE>Connexion utilisateur | Capsule | Université Laval</TITLE>" ) ) {
			return ( true );
		} else {
			return ( false );
		}
	}

    private function _convertSemester( $semester, $smallFormat = false ) {
        if ( is_numeric( $semester ) and strlen( $semester ) == 6 ) {
            // Semester format is YYYYMM
            switch ( substr( $semester, 5, 2 ) ) {
                case '09';
                    if ( $smallFormat ) {
                        $semester = 'A-' . substr( $semester, 2, 2 );
                    } else {
                        $semester = 'Automne ' . substr( $semester, 0, 4 );
                    }
                    break;
                case '01';
                    if ( $smallFormat ) {
                        $semester = 'H-' . substr( $semester, 2, 2 );
                    } else {
                        $semester = 'Hiver ' . substr( $semester, 0, 4 );
                    }
                    break;
                case '05';
                    if ( $smallFormat ) {
                        $semester = 'E-' . substr( $semester, 2, 2 );
                    } else {
                        $semester = 'Été ' . substr( $semester, 0, 4 );
                    }
                    break;
            }

            return ( $semester );
        } else {
            // Semester is in text format
            $textSemester = '';
            $semester = explode( ' ', $semester );
            $textSemester = $semester[ 1 ];
            if ( $semester[ 0 ] == 'Automne' ) $textSemester .= '09';
            elseif ( $semester[ 0 ] == 'Hiver' ) $textSemester .= '01';
            elseif ( $semester[ 0 ] == 'Été' ) $textSemester .= '05';

            return ( $textSemester );
        }
    }
}

?>
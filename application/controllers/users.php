<?php

class Users extends CI_Controller {
    var $mobile = 0;
    var $user;
    var $_source;

    function Users() {
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

        // Détection des navigateurs mobiles
        $this->mobile = $this->lmobile->isMobile();

        // Sélection des données de l'utilisateur
        if ($this->session->userdata('pilule_user') != '') {
            $this->user = $this->mUser->info();
          //  $this->user['password'] = $_SESSION['cap_password'];
        }
    }

    function login () {
        $data = array(
            'section'           =>  'login',
            'mobile_browser'    =>  $this->mobile
        );

        // Chargement de la page
        if ($this->mobile!=1) $this->load->view('welcome/login', $data); else $this->load->view('welcome/m-login', $data);
    }

    function ajax_login () {
        $idul = $this->input->get('idul');
        $password = $this->input->get('password');

        $auth_response = false;
        $authenticated = false;
        $capsule_offline = false;

        // Début de la tentative d'authentification de l'utilisateur
        if (($idul == 'demo' and $password == 'demo')) {
            // Authentification automatique pour le compte démo
            $authenticated = true;
        }

        // Première tentative d'authentification
        if ((!$authenticated)) {
            $auth_response = $this->lcapsule->login($idul, $password);
        }

        // Seconde tentative d'authentification
        switch ($auth_response) {
            case 'success':
                // Authentification réussie
                $auth_response = false;
                $authenticated = true;
            break;
            case 'server-unavailable':
                // Deuxième tentative, via WebCT
                $auth_response = $this->lcapsule->loginWebCT($idul, $password);

                if ($auth_response == 'success') $authenticated = true;

                // Capsule semble hors ligne
                $capsule_offline = true;
            break;
            case 'server-connection':
                // Deuxième tentative, via WebCT
                $response = $this->lcapsule->login($idul, $password);

                if ($auth_response == 'success') {
                    $authenticated = true;
                } elseif ($response == 'server-connection') {
                    // Troisième tentative, via WebCT
                    $response = $this->lcapsule->loginWebCT($idul, $password);

                    if ($auth_response == 'success') $authenticated = true;

                    // Capsule semble hors ligne
                    $capsule_offline = true;
                }
            break;
        }

        if ($authenticated) {
            // Enregistrement des identifiants dans la session
            $this->session->set_userdata(array('pilule_user' => $idul, 'pilule_password' => $password));

            // Enregistrement de la dernière date de connexion de l'utilisateur
            $this->mUser->registerLogin();

            // Enregistrement de la connexion dans l'historique
            $this->mHistory->save('login');

            // Vérification de l'existence des données de l'utilisateur
            $data_loaded = true;
            $reload_list = array();
            $data_requests = array('studies-summary', 'studies-details', 'studies-report', 'schedule', 'fees');
            foreach ($data_requests as $request_name) {
                $last_request = $this->mCache->getLastRequest($request_name);
                if (empty($last_request)) {
                    $reload_list[] = $request_name;
                    $data_loaded = false;
                }
            }

            // Renvoi d'un statut de connexion SUCCÈS
            respond(array(
                'status'    =>  true,
                'loading'   =>  (!$data_loaded) ? true: false,
                'reloadList'=>  $reload_list
            ));

            return (true);
        } else {
            switch ($auth_response) {
                case 'credentials':
                    // Ajout d'une erreur
                    $this->mErrors->addError('login', 'credentials', $idul);

                    // Renvoi d'une réponse négative
                    respond(array(
                        'status'    =>  false,
                        'error'     =>  'Erreur : IDUL ou mot de passe erroné.'
                    ));

                    return (false);
                break;
                case 'server-connection':
                    // Ajout d'une erreur
                    $this->mErrors->addError('login', 'server-connection', $idul);

                    // Renvoi d'une réponse négative
                    respond(array(
                        'status'    =>  false,
                        'error'     =>  'Erreur : serveur de Capsule indisponible.'
                    ));

                    return (false);
                break;
                default:
                    // Ajout d'une erreur
                    $this->mErrors->addError('login', $auth_response, $idul);

                    // Renvoi d'une réponse négative
                    respond(array(
                        'status'    =>  false,
                        'error'     =>  'Erreur interne lors de l\'authentification.'
                    ));

                    return (false);
                break;
            }
        }
    }

    function logout () {
        $this->mHistory->save('logout');

        // Suppression des variables de session
        $this->session->unset_userdata('pilule_user');
        $this->session->unset_userdata('pilule_password');

        //unset($_SESSION['cap_iduser']);
        //unset($_SESSION['cap_password']);
        //if (isset($_SESSION['cap_user'])) unset($_SESSION['cap_user']);
        //if (isset($_SESSION['loading-errors'])) unset($_SESSION['loading-errors']);
        //unset($_SESSION['cap_datacheck']);
        //unset($_SESSION['idbot']);
        //unset($_SESSION['bot']);
        //unset($_SESSION['usebots']);

        // Redirection à la page d'accueil
        redirect('welcome/');
    }
}
?>
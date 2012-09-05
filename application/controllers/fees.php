<?php

class Fees extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;

	function Fees() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
		
		// Chargement des modèles
		$this->load->model('mUser');

        // Vérification de la connexion
        if ((!$this->mUser->isAuthenticated())) {
            $_SESSION['login_redirect'] = $this->uri->uri_string();
            redirect('login');
        }
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function index () {
        $data = array(
            'section'           =>  'fees',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            'studies'           =>  $this->mUser->getStudies()
            // Set page specific data
        );

		$this->mHistory->save('fees-summary');
		
        // Vérification de l'existence des sessions en cache
        $cache = $this->mCache->getCache('data|fees,semesters');

        if ($cache!=array()) {
            $semesters = unserialize($cache['value']);

            if ($semesters!=array()) {
                $data['semester'] = key($semesters);
                $this->session->set_userdata('fees_current_semester', $data['semester']);
            }
        }

		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees,summary');
		
		if ($cache!=array()) {
			$data['summary'] = unserialize($cache['value']);
			$data['cache_date'] = $cache['date'];
			$data['cache_time'] = $cache['time'];
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|fees,summary';
			}
			
			if ($data['summary']!=array()) {
			} else {
				// Chargement de la page d'erreur
				$data['title'] = 'État de compte';
				$data['reload_name'] = 'data|fees,summary';
			}
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'État de compte';
			$data['reload_name'] = 'data|fees,summary';
		}

        // Chargement de la page
        respond(array(
            'title'         =>  'État de compte',
            'content'       =>  $this->load->view('fees/summary', $data, true),
            'reloadData'    =>  (isset($data['reload_data'])) ? $data['reload_data']: '',
            'breadcrumb'=>  array(
                array(
                    'url'   =>  '#!/dashboard',
                    'title' =>  'Tableau de bord'
                ),
                array(
                    'url'   =>  '#!/fees',
                    'title' =>  'Frais de scolarité'
                )
            ),
            'buttons'       =>  array(
                array(
                    'action'=>  "app.cache.reloadData('data|fees,summary');",
                    'type'  =>  'refresh'
                )
            )
        ));
	}

	function details () {
        $data = array(
            'section'           =>  'fees',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            'studies'           =>  $this->mUser->getStudies(),
            // Set page specific data
            'semester'          =>  $this->uri->segment(3)
        );
		
		$this->mHistory->save('fees-details');
		
		if ($data['semester']=='') {
			if ($this->session->userdata('fees_current_semester') != '') {
				$data['semester'] = $this->session->userdata('fees_current_semester');
			} else {
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|fees,semesters');
				
				if ($cache!=array()) {
					$semesters = unserialize($cache['value']);
					
					if ($semesters!=array()) {
						$data['semester'] = key($semesters);
						$this->session->set_userdata('fees_current_semester', $data['semester']);
					}
				}
			}
		} else {
            $this->session->set_userdata('fees_current_semester', $data['semester']);
		}
		
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees['.$data['semester'].']');
		
		if ($cache!=array()) {
			$data['fees'] = unserialize($cache['value']);
			$data['cache_date'] = $cache['date'];
			$data['cache_time'] = $cache['time'];
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|fees,summary';
			}
		}
		
		$cache = $this->mCache->getCache('data|fees,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
		}
		
		if (isset($data['fees']) and $data['fees']!=array()) {
			// Chargement de la page
            respond(array(
                'title'         =>  'Relevé par session',
                'content'       =>  $this->load->view('fees/details', $data, true),
                'reloadData'    =>  (isset($data['reload_data'])) ? $data['reload_data']: '',
                'breadcrumb'=>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/fees',
                        'title' =>  'Frais de scolarité'
                    ),
                    array(
                        'url'   =>  '#!/fees/details',
                        'title' =>  'Relevé par session'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData('data|fees,summary');",
                        'type'  =>  'refresh'
                    )
                )
            ));
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Relevé par session';
			$data['reload_name'] = 'data|fees,summary';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
	}
}
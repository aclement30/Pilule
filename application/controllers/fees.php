<?php

class Fees extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;
    var $debug = false;

	function Fees() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
		
		// Chargement des modèles
        $this->load->model('mTuitions');
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
            // Set page specific data
        );

		$this->mHistory->save('fees-summary');

        // Vérification de l'existence des données en cache
        $last_request = $this->mCache->getLastRequest('fees');

        if (empty($last_request)) {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'État de compte',
                'content'       =>  $this->load->view('errors/loading-data', $data, true),
                'reloadData'    =>  'fees',
                'breadcrumb'    =>  array(
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
                        'action'=>  "app.cache.reloadData({name: 'fees', auto: 0});",
                        'type'  =>  'refresh'
                    ),
                    array(
                        'action'=>  "window.print();",
                        'type'  =>  'print'
                    )
                )
            ));

            return (false);
        }

        // Sélection des frais de scolarité pour le semestre sélectionné
        $data['account'] = $this->mTuitions->getAccount();
        $semesters = $this->mTuitions->getSemesters(array('semester' => CURRENT_SEMESTER));

        if (empty($semesters)) {
            // Si aucun sommaire n'existe pour la session en cours, afficher la dernière session disponible
            $semesters = $this->mTuitions->getSemesters();
        }

        if (!empty($semesters)) {
            // Ajout des données de la dernière requête Capsule
            $data['last_request'] = $last_request;

            $data['summary'] = $semesters[0];
            $chart_data = array();

            $tuition_fees = 0;
            foreach ($data['summary']['fees'] as $fee) {
                if (strpos($fee['name'], 'Droits de scolarité') !== false) $tuition_fees = $fee['amount'];

                if (strpos($fee['name'], 'Frais modern. gest. études') !== false) $fee['name'] = 'Capsule';
                if (strpos($fee['name'], 'Droits de scolarité') === false) {
                    $chart_data[] = '{label: \''.addslashes($fee['name']).'\', data: '.round(($fee['amount']/($data['summary']['total']-$tuition_fees)*100)).'}';
                }
            }

            $chart_data = implode(', ', $chart_data);
            $code = <<<EOD
                    var displayChart = function () {
                        var pie = $.plot($(".chart"), [{$chart_data}],{
                            series: {
                                pie: {
                                    show: true,
                                    radius: 3/4,
                                    label: {
                                        show: true,
                                        radius: 3/4,
                                        formatter: function(label, series){
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+Math.round(series.percent)+'%</div>';
                                        },
                                        background: {
                                            opacity: 0.5,
                                            color: '#000'
                                        }
                                    },
                                    innerRadius: 0.2
                                },
                                legend: {
                                    show: false
                                }
                            }
                        });
                    };

                    // Wait until the refresh effect end so the chart is displayed and can be filled
                    setTimeout(displayChart, 100);
EOD;

            // Chargement de la page
            respond(array(
                'title'         =>  'État de compte',
                'content'       =>  $this->load->view('fees/summary', $data, true),
                'code'          =>  $code,
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay) && (!$this->debug)) ? 'fees': false,
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
                        'action'=>  "app.cache.reloadData({name: 'fees', auto: 0});",
                        'type'  =>  'refresh'
                    ),
                    array(
                        'action'=>  "window.print();",
                        'type'  =>  'print'
                    )
                )
            ));
        } else {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'État de compte',
                'content'       =>  $this->load->view('errors/no-data', $data, true),
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'breadcrumb'    =>  array(
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
                        'action'=>  "app.cache.reloadData({name: 'fees', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }
	}

	function details () {
        $data = array(
            'section'           =>  'fees',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
            'semester_date'     =>  ($this->uri->segment(3) !='' ) ? $this->uri->segment(3): CURRENT_SEMESTER
        );

        // Vérification de l'existence des données en cache
        $last_request = $this->mCache->getLastRequest('fees');

        if (empty($last_request)) {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'Relevé par session',
                'content'       =>  $this->load->view('errors/loading-data', $data, true),
                'reloadData'    =>  'fees',
                'breadcrumb'    =>  array(
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
                        'action'=>  "app.cache.reloadData({name: 'fees', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }

        $data['semesters'] = $this->mTuitions->getSemesters();
        $semesters = $this->mTuitions->getSemesters(array('semester' => $data['semester_date']));

        if (!empty($semesters)) $data['semester'] = $semesters[0];
        // Ajout des données de la dernière requête Capsule
        $data['last_request'] = $last_request;

        // Chargement de la page
        respond(array(
            'title'         =>  'Relevé par session',
            'content'       =>  $this->load->view('fees/details', $data, true),
            'timestamp'     =>  time_ago($last_request['timestamp']),
            'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay) && (!$this->debug)) ? 'fees': false,
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
                    'action'=>  "app.cache.reloadData({name: 'fees', auto: 0});",
                    'type'  =>  'refresh'
                ),
                array(
                    'action'=>  "window.print();",
                    'type'  =>  'print'
                )
            )
        ));
	}
}
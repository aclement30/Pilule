<?php

class Studies extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;
	
	function Studies() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
		
		// Augmentation de la limitation de mémoire
		//ini_set('memory_limit', '50M');
		
		// Chargement des modèles
		$this->load->model('mCourses');
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
            'section'           =>  'studies',
            'user'              =>  $this->user,
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
            'programs'           =>  $this->mStudies->getPrograms(),
            'user'              =>  $this->mUser->info()
        );

		$this->mHistory->save('studies-summary');

		// Vérification de l'existence des données en cache
		$last_request = $this->mCache->getLastRequest('studies-summary');

        if (empty($last_request)) {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'Programme d\'études',
                'content'       =>  $this->load->view('errors/loading-data', $data, true),
                'reloadData'    =>  'studies',
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }

        if (!empty($data['programs'])) {
            respond(array(
                'title'         =>  'Programme d\'études',
                'content'       =>  $this->load->view('studies/summary', $data, true),
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay)) ? 'studies': false,
                'breadcrumb'=>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));
        } else {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'Programme d\'études',
                'content'       =>  $this->load->view('errors/no-data', $data, true),
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (true);
        }
	}
	
	function details () {
        $data = array(
            'section'           =>  'studies',
            'user'              =>  $this->user,
            'mobile'            =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
            'programs'          =>  $this->mStudies->getPrograms(),
            'user'              =>  $this->mUser->info(),
        );
		
		$this->mHistory->save('studies-details');

        // Vérification de l'existence des données en cache
        $last_request = $this->mCache->getLastRequest('studies-details');

        if (empty($last_request)) {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'     =>  'Rapport de cheminement',
                'content'   =>  $this->load->view('errors/loading-data', $data, true),
                'reloadData'=>  'studies-details',
                'breadcrumb'=>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    ),
                    array(
                        'url'   =>  '#!/studies/details',
                        'title' =>  'Rapport de cheminement'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies-details', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }

        if (!empty($data['programs'])) {
            // Sélection des données des études
            foreach ($data['programs'] as &$program) {
            // Vérification de l'existence de la page en cache
                $program['sections'] = $this->mStudies->getProgramSections($program['id']);

                foreach ($program['sections'] as &$section) {
                    $section['courses'] = $this->mStudies->getProgramCourses(array('section_id' => $section['id']));
                }

                // Sélection de la moyenne de cohorte
                $program['cohort_gpa'] = $this->mStudies->getCohortAverageGPA($program['name'], $program['session_repertoire']);

                // Sélection des moyennes pour chaque semestre
                $program['gpas'] = $this->mStudies->getSemestersGPA($program['session_repertoire'], $program['session_evaluation']);
            }

            if (!empty($program['gpas'])) {
                $chart_data = array();
                $chart_x_axis = array();
                $smallest = 4.33;
                $highest = 0;

                $n = 0;
                foreach ($program['gpas'] as $semester) {
                    $chart_data[] = '[' . $n . ', ' . $semester['gpa'] . ']';
                    $chart_x_axis[] = '[' . $n . ', \'' . convertSemester($semester['semester'], true) . '\']';

                    if ($semester['gpa'] < $smallest) $smallest = $semester['gpa'];
                    if ($semester['gpa'] > $highest) $highest = $semester['gpa'];

                    $n++;
                }

                $smallest = $smallest - 0.1;
                $highest = $highest + 0.1;
                $chart_data = implode(', ', $chart_data);
                $chart_x_axis = implode(', ', $chart_x_axis);

                $code = <<<EOD
                    var displayChart = function () {
                        var plot = $.plot($(".chart"),
                        [ { data: [{$chart_data}], label: "Moyennes", color: "#134a7f", hoverable: true} ], {
                            series: {
                                lines: { show: true },
                                points: { show: true }
                            },
                            grid: { hoverable: true, clickable: true },
                            yaxis: { min: {$smallest}, max: {$highest} },
                            xaxis: {
                                ticks: [{$chart_x_axis}]
                              },
                            legend: { show: false }
                        });
                        var previousPoint = null;
                        $(".chart").bind("plothover", function (event, pos, item) {

                            if (item) {
                                if (previousPoint != item.datapoint) {
                                    previousPoint = item.datapoint;

                                    $("#tooltip").remove();
                                    var x = item.datapoint[0],
                                        y = item.datapoint[1] - item.datapoint[2];

                                    $(item).tooltip({'title': y + " " + item.series.label});

                                   // showTooltip(item.pageX, item.pageY, y + " " + item.series.label);
                                }
                            } else {
                                $("#tooltip").remove();
                                previousPoint = null;
                            }
                        });
                    };

                    // Wait until the refresh effect end so the chart is displayed and can be filled
                    setTimeout(displayChart, 100);
EOD;
            } else {
                $code = "$('.chart').parent().hide();";
            }

            // Chargement de la page
            respond(array(
                'title'         =>  'Rapport de cheminement',
                'content'       =>  $this->load->view('studies/details', $data, true),
                'code'          =>  $code,
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay)) ? 'studies-details': false,
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    ),
                    array(
                        'url'   =>  '#!/studies/details',
                        'title' =>  'Rapport de cheminement'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies-details', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));
        } else {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'Rapport de cheminement',
                'content'       =>  $this->load->view('errors/no-data', $data, true),
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    ),
                    array(
                        'url'   =>  '#!/studies/details',
                        'title' =>  'Rapport de cheminement'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies-details', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (true);
        }
	}
	
	function report () {
        $data = array(
            'section'           =>  'studies',
            'user'              =>  $this->mUser->info(),
            'mobile'            =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
            'report'            =>  $this->mStudies->getReport(),
            'admitted_sections' =>  $this->mStudies->getReportAdmittedSections(),
            'semesters'         =>  $this->mStudies->getReportSemesters()
        );

		$this->mHistory->save('studies-report');

        // Vérification de l'existence des données en cache
        $last_request = $this->mCache->getLastRequest('studies-report');

        if (empty($last_request)) {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'     =>  'Relevé de notes',
                'content'   =>  $this->load->view('errors/loading-data', $data, true),
                'reloadData'=>  'studies-report',
                'breadcrumb'=>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/studies',
                        'title' =>  'Dossier scolaire'
                    ),
                    array(
                        'url'   =>  '#!/studies/report',
                        'title' =>  'Relevé de notes'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'studies-report', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }

		// Sélection des données des études
        foreach ($data['admitted_sections'] as &$section) {
            $section['courses'] = $this->mStudies->getReportCourses(array('section_id' => $section['id']));
        }
        foreach ($data['semesters'] as &$semester) {
            $semester['courses'] = $this->mStudies->getReportCourses(array('semester_id' => $semester['id']));
        }

        // Chargement de la page
        respond(array(
            'title'         =>  'Relevé de notes',
            'content'       =>  $this->load->view('studies/report', $data, true),
            'timestamp'     =>  time_ago($last_request['timestamp']),
            'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay)) ? 'studies-report': false,
            'breadcrumb'=>  array(
                array(
                    'url'   =>  '#!/dashboard',
                    'title' =>  'Tableau de bord'
                ),
                array(
                    'url'   =>  '#!/studies',
                    'title' =>  'Dossier scolaire'
                ),
                array(
                    'url'   =>  '#!/studies/report',
                    'title' =>  'Relevé de notes'
                )
            ),
            'buttons'       =>  array(
                array(
                    'action'=>  "app.cache.reloadData({name: 'studies-report', auto: 0});",
                    'type'  =>  'refresh'
                )
            )
        ));
    }
}

/* End of file studies.php */
/* Location: ./system/application/controllers/studies.php */
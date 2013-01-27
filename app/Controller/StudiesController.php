<?php
class StudiesController extends AppController {

	public $uses = array( 'StudentProgram', 'StudentProgramSection', 'StudentProgramCourse', 'StudentReport' );

	public $helpers = array( 'Time' );

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index () {
		// Set basic page parameters
		$this->set( 'breadcrumb', array(
            array(
                'url'   =>  'dashboard',
                'title' =>  'Tableau de bord'
            ),
            array(
                'url'   =>  'studies',
                'title' =>  'Dossier scolaire'
            )
        ) );
        $this->set( 'buttons', array(
        	array(
                'action'=>  "app.Cache.reloadData( { name: 'studies', auto: 0 } );",
                'type'  =>  'refresh'
            ),
            array(
                'action'=>  "window.print();",
                'type'  =>  'print'
            )
        ) );
		$this->set( 'title_for_layout', 'Programme d\'études' );
        $this->set( 'sidebar', 'studies' );
		$this->set( 'dataObject', 'studies' );
        $this->setAssets( null, array( '/css/studies.css' ) );

		$programs = $this->StudentProgram->User->find( 'first', array(
			'conditions'	=>	array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
        	'contain'		=>	array( 'Program' ),
        	'fields'		=> 	array( 'User.idul' )
        ) );

		// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'studies-summary' ) ) && ( !empty( $programs ) ) ) {
        	$this->set( 'programs', $programs );
        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
        	if ( !empty( $lastRequest ) )
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

        	// No data exists for this page
        	$this->viewPath = 'Commons';
			$this->render( 'no_data' );

            return (true);
        }
	}

    function details () {
        // Set basic page parameters
        $this->set( 'breadcrumb', array(
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
        ) );
        $this->set( 'buttons', array(
            array(
                'action'=>  "app.Cache.reloadData( { name: 'studies-details', auto: 0 } );",
                'type'  =>  'refresh'
            ),
            array(
                'action'=>  "window.print();",
                'type'  =>  'print'
            )
        ) );
        $this->set( 'title_for_layout', 'Rapport de cheminement' );
        $this->set( 'sidebar', 'studies' );
        $this->set( 'dataObject', 'studies-details' );
        $this->setAssets( null, array( '/css/studies.css' ) );

        $programs = $this->StudentProgram->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Program' ),
            'fields'        =>  array( 'User.idul' )
        ) );

        $sections = $this->StudentProgramSection->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Section' => array( 'Course' => array( 'conditions' => array( 'Course.semester !=' => 'NULL' ) ) ) ),
            'fields'        =>  array( 'User.idul' )
        ) );

        /*
        // Sélection de la moyenne de cohorte
        $program['cohort_gpa'] = $this->mStudies->getCohortAverageGPA($program['name'], $program['session_repertoire']);

        // Sélection des moyennes pour chaque semestre
        $program['gpas'] = $this->mStudies->getSemestersGPA($program['session_repertoire'], $program['session_evaluation']);

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
        */

        // Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'studies-details' ) ) && ( !empty( $sections ) ) ) {
            $this->set( 'programs', $programs );
            $this->set( 'sections', $sections );
            $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
            if ( !empty( $lastRequest ) )
                $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            // No data exists for this page
            $this->viewPath = 'Commons';
            $this->render( 'no_data' );

            return (true);
        }
    }

    function report () {
        // Set basic page parameters
        $this->set( 'breadcrumb', array(
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
        ) );
        $this->set( 'buttons', array(
            array(
                'action'=>  "app.Cache.reloadData( { name: 'studies-report', auto: 0 } );",
                'type'  =>  'refresh'
            ),
            array(
                'action'=>  "window.print();",
                'type'  =>  'print'
            )
        ) );
        $this->set( 'title_for_layout', 'Relevé de notes' );
        $this->set( 'sidebar', 'studies' );
        $this->set( 'dataObject', 'studies-report' );
        $this->setAssets( null, array( '/css/studies.css' ) );
        
        $programs = $this->StudentProgram->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Program' ),
            'fields'        =>  array( 'User.idul' )
        ) );

        $report = $this->StudentReport->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Report' => array( 'Semester' => array( 'Course' ), 'AdmittedSection' => array( 'Course' ) ) ),
            'fields'        =>  array( 'User.idul' )
        ) );
        
        // Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'studies-report' ) ) && ( !empty( $report ) ) ) {
            $this->set( 'programs', $programs );
            $this->set( 'report', $report );
            $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
            if ( !empty( $lastRequest ) )
                $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            // No data exists for this page
            $this->viewPath = 'Commons';
            $this->render( 'no_data' );

            return (true);
        }
    }
}
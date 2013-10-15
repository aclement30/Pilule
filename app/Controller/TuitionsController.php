<?php
class TuitionsController extends AppController {

	public $uses = array( 'StudentTuitionAccount', 'StudentReport' );

	public $helpers = array( 'Time' );

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index () {
		// Set basic page parameters
		$this->set( 'breadcrumb', array(
            array(
                'url'   =>  '/dashboard',
                'title' =>  'Tableau de bord'
            ),
            array(
                'url'   =>  '/tuitions',
                'title' =>  'Frais de scolarité'
            )
        ) );

		$this->set( 'title_for_layout', 'Frais de scolarité' );
        $this->set( 'sidebar', 'tuitions' );
        $this->setAssets( array(
            '/js/tuitions.js',
            '/js/libs/jquery.base64.js'
        ), array( '/css/tuitions.css' ) );
		$this->set( 'dataObject', 'tuition-fees' );

		$tuitions = $this->StudentTuitionAccount->User->find( 'first', array(
			'conditions'	=>	array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'TuitionAccount' => array( 'Semester' ) ),
        	'fields'		=> 	array( 'User.idul' )
        ) );

		// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'tuition-fees' ) ) && ( !empty( $tuitions[ 'TuitionAccount' ][ 'Semester' ] ) ) ) {
            $currentSemester = $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ];

            // Define tuition fees payment deadlines
            if ( substr( $currentSemester[ 'semester' ], 4, 2 ) == '01' ) {
                if ( $currentSemester[ 'semester' ] == 201301 ) {
                    // Exception for H-2013 semester
                    $deadline = array(
                        'long'  =>  '1er mars ' . substr( $currentSemester[ 'semester' ], 0, 4 ),
                        'small' =>  '1<sup>er</sup> mars',
                        'date'  =>  substr( $currentSemester[ 'semester' ], 0, 4 ) . '0301'
                    );
                } else {
                    $deadline = array(
                        'long'  =>  '15 février ' . substr( $currentSemester[ 'semester' ], 0, 4 ),
                        'small' =>  '15 fév.',
                        'date'  =>  substr( $currentSemester[ 'semester' ], 0, 4 ) . '0215'
                    );
                }
            } elseif ( substr( $currentSemester[ 'semester' ], 4, 2 ) == '09' ) {
                $deadline = array(
                    'long'  =>  '15 octobre ' . substr( $currentSemester[ 'semester' ], 0, 4 ),
                    'small' =>  '15 oct.',
                    'date'  =>  substr( $currentSemester[ 'semester' ], 0, 4 ) . '1015'
                );
            } elseif ( substr( $currentSemester[ 'semester' ], 4, 2 ) == '05' ) {
                $deadline = array(
                    'long'  =>  '15 juin ' . substr( $currentSemester[ 'semester' ], 0, 4 ),
                    'small' =>  '15 juin',
                    'date'  =>  substr( $currentSemester[ 'semester' ], 0, 4 ) . '0615'
                );
            }

            // Calculate chart data
            $chartData = array();

            $tuitionFees = 0;
            foreach ( $currentSemester[ 'fees' ] as $fee ) {
                if ( strpos( $fee[ 'name' ], 'Droits de scolarité' ) !== false )
                    $tuitionFees = $fee[ 'amount' ];

                if ( strpos( $fee[ 'name' ], 'Frais modern. gest. études' ) !== false )
                    $fee[ 'name' ] = 'Capsule';

                if ( strpos( $fee[ 'name' ], 'Droits de scolarité' ) === false )
                    $chartData[] = '{label: \'' . addslashes( $fee[ 'name' ] ) . '\', data: ' . round( ( $fee[ 'amount' ] / ( $currentSemester[ 'total' ] - $tuitionFees ) * 100 ) ) . '}';
            }

            $this->set( 'chartData', implode( ', ', $chartData ) );
        	$this->set( 'tuitions', $tuitions );
            $this->set( 'deadline', $deadline );
        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            if ( !empty( $currentSemester[ 'pdf_statement_url' ] ) ) {
                $this->set( 'buttons', array(
                    array(
                        'action'=>  "app.Cache.reloadData( { name: 'tuition-fees', auto: 0 } );",
                        'type'  =>  'refresh'
                    ),
                    /*array(
                        'action'=>  "app.Tuitions.downloadPDF( '" . addslashes( $currentSemester[ 'pdf_statement_url' ] ) . "' );",
                        'type'  =>  'download'
                    ),*/
                    array(
                        'action'=>  "window.print();",
                        'type'  =>  'print'
                    )
                ) );
            } else {
                $this->set( 'buttons', array(
                    array(
                        'action'=>  "app.Cache.reloadData( { name: 'tuition-fees', auto: 0 } );",
                        'type'  =>  'refresh'
                    ),
                    array(
                        'action'=>  "window.print();",
                        'type'  =>  'print'
                    )
                ) );
            }
        } else {
            $this->set( 'buttons', array(
                array(
                    'action'=>  "app.Cache.reloadData( { name: 'tuition-fees', auto: 0 } );",
                    'type'  =>  'refresh'
                )
            ) );

        	if ( !empty( $lastRequest ) )
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

        	// No data exists for this page
        	$this->viewPath = 'Commons';
			$this->render( 'no_data' );

            return ( true );
        }
	}

    function details ( $semester = null ) {
        // Check if a semester has been provided
        if ( empty( $semester ) )
            $semester = CURRENT_SEMESTER;

        // Set basic page parameters
        $this->set( 'breadcrumb', array(
            array(
                'url'   =>  '/dashboard',
                'title' =>  'Tableau de bord'
            ),
            array(
                'url'   =>  '/tuitions',
                'title' =>  'Frais de scolarité'
            ),
            array(
                'url'   =>  '/tuitions/details',
                'title' =>  'Relevé par session'
            )
        ) );

        $this->set( 'title_for_layout', 'Relevé par session' );
        $this->set( 'dataObject', 'tuition-fees' );
        $this->set( 'sidebar', 'tuitions' );
        $this->setAssets( array(
            '/js/tuitions.js',
            '/js/libs/jquery.base64.js'
        ), array( '/css/tuitions.css' ) );

        $tuitions = $this->StudentTuitionAccount->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'TuitionAccount' => array( 'Semester' => array( 'conditions' => array( 'Semester.semester' => $semester ) ) ) ),
            'fields'        =>  array( 'User.idul' )
        ) );

        $semestersList = $this->StudentTuitionAccount->Semester->find( 'list', array(
            'conditions'    =>  array( 'Semester.idul' => $this->Session->read( 'User.idul' ) )
        ) );

        // Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'tuition-fees' ) ) && ( !empty( $tuitions[ 'TuitionAccount' ][ 'Semester' ] ) ) ) {
            $this->set( 'semester', $semester );
            $this->set( 'tuitions', $tuitions );
            $this->set( 'semestersList', $semestersList );
            $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            if ( !empty( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'pdf_statement_url' ] ) ) {
                $this->set( 'buttons', array(
                    array(
                        'action'=>  "app.Cache.reloadData( { name: 'tuition-fees', auto: 0 } );",
                        'type'  =>  'refresh'
                    ),
                    /*array(
                        'action'=>  "app.Tuitions.downloadPDF( '" . addslashes( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'pdf_statement_url' ] ) . "' );",
                        'type'  =>  'download'
                    ),*/
                    array(
                        'action'=>  "window.print();",
                        'type'  =>  'print'
                    )
                ) );
            } else {
                $this->set( 'buttons', array(
                    array(
                        'action'=>  "app.Cache.reloadData( { name: 'tuition-fees', auto: 0 } );",
                        'type'  =>  'refresh'
                    ),
                    array(
                        'action'=>  "window.print();",
                        'type'  =>  'print'
                    )
                ) );
            }
        } else {
            if ( !empty( $lastRequest ) )
                $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            $this->set( 'selectedSemester', $semester );
            if ( !empty( $semestersList ) )
                $this->set( 'semestersList', $semestersList );

            $this->set( 'buttons', array(
                array(
                    'action'=>  "app.Cache.reloadData( { name: 'tuition-fees', auto: 0 } );",
                    'type'  =>  'refresh'
                )
            ) );

            // No data exists for this page
            $this->viewPath = 'Commons';
            $this->render( 'no_data' );

            return ( true );
        }
    }
}
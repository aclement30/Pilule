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

		$this->set( 'title_for_layout', 'Frais de scolarité' );
		
		$tuitions = $this->StudentTuitionAccount->User->find( 'first', array(
			'conditions'	=>	array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'TuitionAccount' => array( 'Semester' ) ),
        	'fields'		=> 	array( 'User.idul' )
        ) );

        $this->setAssets( array(
            '/js/tuitions.js',
            '/js/jquery.flot.min.js',
            '/js/jquery.flot.pie.min.js',
            '/js/jquery.flot.resize.min.js' 
        ) );

		// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'tuition-fees' ) ) && ( !empty( $tuitions[ 'TuitionAccount' ] ) ) ) {
            // Define tuition fees payment deadlines
            if ( substr( CURRENT_SEMESTER, 4, 2 ) == '01' ) {
                $deadline = array(
                    'long'  =>  '15 février ' . substr( CURRENT_SEMESTER, 0, 4 ),
                    'small' =>  '15 fév.',
                    'date'  =>  substr( CURRENT_SEMESTER, 0, 4 ) . '0215'
                );
            } elseif ( substr( CURRENT_SEMESTER, 4, 2 ) == '09' ) {
                $deadline = array(
                    'long'  =>  '15 octobre ' . substr( CURRENT_SEMESTER, 0, 4 ),
                    'small' =>  '15 oct.',
                    'date'  =>  substr( CURRENT_SEMESTER, 0, 4 ) . '1015'
                );
            } elseif ( substr( CURRENT_SEMESTER, 4, 2 ) == '05' ) {
                $deadline = array(
                    'long'  =>  '15 juin ' . substr( CURRENT_SEMESTER, 0, 4 ),
                    'small' =>  '15 juin',
                    'date'  =>  substr( CURRENT_SEMESTER, 0, 4 ) . '0615'
                );
            }

            // Calculate chart data
            $chartData = array();

            $tuitionFees = 0;
            foreach ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['fees'] as $fee ) {
                if ( strpos( $fee[ 'name' ], 'Droits de scolarité' ) !== false )
                    $tuitionFees = $fee[ 'amount' ];

                if ( strpos( $fee[ 'name' ], 'Frais modern. gest. études' ) !== false )
                    $fee[ 'name' ] = 'Capsule';

                if ( strpos( $fee[ 'name' ], 'Droits de scolarité' ) === false )
                    $chartData[] = '{label: \'' . addslashes( $fee[ 'name' ] ) . '\', data: ' . round( ( $fee[ 'amount' ] / ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'total' ] - $tuitionFees ) * 100 ) ) . '}';
            }

            $this->set( 'chartData', implode( ', ', $chartData ) );
        	$this->set( 'tuitions', $tuitions );
            $this->set( 'deadline', $deadline );
        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
        	if ( !empty( $lastRequest ) )
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

        	// No data exists for this page
        	$this->viewPath = 'commons';
			$this->render( 'no_data' );

            return (true);
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

        $this->set( 'title_for_layout', 'Relevé par session' );
        $this->setAssets( array( '/js/tuitions.js' ) );

        $tuitions = $this->StudentTuitionAccount->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'TuitionAccount' => array( 'Semester' => array( 'conditions' => array( 'Semester.semester' => $semester ) ) ) ),
            'fields'        =>  array( 'User.idul' )
        ) );

        $semestersList = $this->StudentTuitionAccount->Semester->find( 'list', array(
            'conditions'    =>  array( 'Semester.idul' => $this->Session->read( 'User.idul' ) )
        ) );

        // Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'tuition-fees' ) ) && ( !empty( $tuitions ) ) ) {
            $this->set( 'semester', $semester );
            $this->set( 'tuitions', $tuitions );
            $this->set( 'semestersList', $semestersList );
            $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
            if ( !empty( $lastRequest ) )
                $this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            // No data exists for this page
            $this->viewPath = 'commons';
            $this->render( 'no_data' );

            return (true);
        }
    }
}
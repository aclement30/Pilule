<?php
class RegistrationController extends AppController {
	public $uses = array( 'StudentScheduleSemester', 'StudentProgramSection' );

	private $registrationSemester = '201201';
	private $currentSemester = '201209';
	private $deadlines = array(
						   '201301'	=> array(
								'registration_start'=>	'20121105',
								'edit_selection'	=>	'20130106', // TODO : to be updated
								'drop_nofee'		=>	'20130123', // TODO : to be updated
								'drop_fee'			=>	'20130319'	// TODO : to be updated
							),
						   '201205'	=> array(
								'registration_start'=>	'20120305',
								'edit_selection'	=>	'20120430',
								'drop_nofee'		=>	'20120918',
								'drop_fee'			=>	'20121113'
							),
						   '201209'	=> array(
								'registration_start'=>	'20120326',
								'edit_selection'	=>	'20120911',
								'drop_nofee'		=>	'20120918',
								'drop_fee'			=>	'20121113'
							)
						   );
	private $registrationSemesters = array( '201209', '201301' );

	public function beforeFilter () {
		parent::beforeFilter();

		// Update current semester, if needed
		if ( date( 'm' ) < 5 ) {
			// Winter semester
			$this->currentSemester = date( 'Y' ) . '01';
		} elseif ( date( 'm' ) < 9 ) {
			// Summer semester
			$this->currentSemester = date( 'Y' ) . '05';
		} else {
			// Autumn semester
			$this->currentSemester = date( 'Y' ) . '09';
		}

		$this->Session->write( 'Registration.semester', $this->registrationSemester );
	}

	public function index ( $semester = null ) {
		// If user selected a different semester, use it for registration
		if ( !empty( $semester ) ) {
			$newSemester = $semester;
			$this->Session->write( 'Registration.semester', $semester );
			$this->registrationSemester = $semester;
		}

		$this->set( 'currentSemester', $this->currentSemester );
		$this->set( 'registrationSemester', $this->registrationSemester );
		$this->set( 'registrationSemesters', $this->registrationSemesters );
		$this->set( 'deadlines', $this->deadlines );

		$registeredCourses = array();
		$selectedCourses = array();

		$schedule = $this->StudentScheduleSemester->find( 'first', array(
			'conditions'	=>	array( 'StudentScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'StudentScheduleSemester.semester' => $this->registrationSemester  ),
        	'contain'		=>	array( 'Course' )
        ) );
		
		if ( !empty( $schedule[ 'Course' ] ) )
			$registeredCourses = $schedule[ 'Course' ];

		$sections = $this->StudentProgramSection->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Section' => array( 'Course' ) ),
            'fields'        =>  array( 'User.idul' )
        ) );

		$this->set( 'sections', $sections );
		$this->set( 'registeredCourses', $registeredCourses );
		$this->set( 'selectedCourses', $selectedCourses );
		/*
		// Get program registration info
		$program = $this->mPrograms->getProgramByName( $data[ 'userPrograms' ][ 0 ][ 'name' ] );
		
		// Get program sections
		$sections = $this->mPrograms->getSections( $program[ 'code' ] );
		
		// Get user selected program sections list
		$data[ 'userSections' ] = $this->mUser->getParam( 'registration-' . $program[ 'code' ] . '-sections' );
		
		if ( ( !$data[ 'userSections' ] ) or $data[ 'userSections' ] == array() ) {
			// Redirect to registration config page
			?>document.location.hash="#!/registration/configure";<?php
		} else {
			$data['userSections'] = explode(",", $data['userSections']);
		}
		
		// Include program plugin
		switch ($program['code']) {
			case 'B-LMO':
				include ('./programs/'.'b-lmo'.'.php');
			break;
		}
		
		// Define available sections
		$data['sections'] = $sections;

		// Get program courses
		$programCourses = $this->mCourses->getProgramCourses( $program['code'] );
		
		// Get student report semesters
        $reportSemesters = $this->mStudies->getReportSemesters();

        // Find courses in student report
		$reportCourses = array();
		if ( !empty( $reportSemesters ) ) {			
			foreach ( $reportSemesters as $semester ) {
				$courses = $this->mStudies->getReportCourses( array( 'semester_id' => $semester['id'] ) );

				foreach ( $courses as $course ) {
					// Define course semester
					$course['semester'] = convertSemester( $semester[ 'semester' ], true );

					// Add course to report
					$reportCourses[ $course[ 'code' ] ] = $course;
				}
			}
		}
		
		// Find courses already registered for registration semester
		$data[ 'registeredCourses' ] = array();

		$scheduleCourses = $this->mSchedule->getCourses( array( 'semester' => $data[ 'registrationSemester' ] ) );
		foreach ( $scheduleCourses as $course ) {
			$data[ 'registeredCourses' ][ $course[ 'code' ] ] = $course;
		}
		
		// Find courses already selected for registration semester
		$cache = $this->mCache->getCache( 'data|selected-courses[' . $data[ 'registrationSemester' ] . ']' );
		$data[ 'selectedCourses' ] = array();
		if ( $cache != array() ) {

			$selectedCourses = unserialize($cache['value']);
			foreach ( $selectedCourses as $course ) {
				$data[ 'selectedCourses' ][ $course[ 'nrc' ] ] = $course;
			}
		}
				
		// Find courses in current semester
		$scheduleCourses = $this->mSchedule->getCourses( array( 'semester' => $data[ 'currentSemester' ] ) );
		foreach ( $scheduleCourses as $course ) {
			$scheduleCourses[ $course[ 'code' ] ] = $course;
		}
		
		$data['programCourses'] = array();

		foreach ( $programCourses as &$course ) {
			// If course is in student report
			if ( isset( $reportCourses[ $course[ 'code' ] ] ) ) {
				$course[ 'note' ] = $reportCourses[ $course[ 'code' ] ][ 'note' ];
				$course['semester'] = $reportCourses[ $course[ 'code' ] ][ 'semester' ];
			}

			// If course is in current semester schedule
			if ( isset( $scheduleCourses[ $course[ 'code' ] ] ) )
				$course[ 'semester' ] = convertSemester( $this->currentSemester, true );
			
			// Define course level
			$course[ 'level' ] = 4;
			if ( isset( $course[ 'note'] ) && !empty( $course['note'] ) ) {
				$course[ 'level' ] = 1;			// Course completed
			} elseif ( isset( $course[ 'semester'] ) && $course[ 'semester '] == $this->currentSemester ) {
				$course[ 'level' ] = 2;			// Course is being attending in this current semester
			} elseif ( $course[ 'av' . $data[ 'registrationSemester' ] ] ) {
				$course[ 'level' ] = 3;			// Course is available for registration
			} elseif ( !$course[ 'av' . $data[ 'registrationSemester' ] ] ) {
				$course[ 'level' ] = 4;			// Course is not available for registration
			}

			if ( isset( $data[ 'programCourses' ][ $course[ 'category' ] ] ) ) {
				$data[ 'programCourses' ][$course[ 'category' ] ][] = $course;
			} else {
				$data[ 'programCourses' ][ $course[ 'category' ] ] = array();
				$data[ 'programCourses' ][ $course[ 'category' ] ][] = $course;
			}
		}

		$data['courses'] = $programCourses;
		
		$this->mHistory->save('registration-courses');
		
		$currentDate = date('Ymd');
		// Chargement de la page d'inscription
		respond(array(
                'title'         =>  'Choix de cours',
                'content'       =>  $this->load->view('registration/courses', $data, true),
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/registration',
                        'title' =>  'Choix de cours'
                    )
                ),
                'code'			=>	<<<EOT
    app.Registration.init({
    	registrationSemester: 	'{$this->registrationSemester}',
    	currentSemester:		'{$this->currentSemester}',
    	currentDate:			'{$currentDate}',
    	deadline_drop_fee:		'{$this->deadlines[$this->registrationSemester]['drop_fee']}',
    	deadline_drop_nofee:	'{$this->deadlines[$this->registrationSemester]['drop_nofee']}',
    	deadline_edit_selection:'{$this->deadlines[$this->registrationSemester]['edit_selection']}'
    });
    
    $('.courses').each(function(index, value) { $(value).find('tr').css('backgroundColor', '#fff'); $(value).find('tr:visible:odd').css('backgroundColor', '#dae6f1'); });
    	*/

    	$this->render( 'limited' );
	}
}

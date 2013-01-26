<?php
class RegistrationController extends AppController {
	public $uses = array( 'User', 'UniversityCourse' );

	public $helpers = array( 'Time' );

	private $registrationSemester = '201301';
	private $currentSemester = CURRENT_SEMESTER;
	private $deadlines = array(
						   '201301'	=> array(
								'registration_start'=>	'20121105',
								'edit_selection'	=>	'20130128', // TODO : to be updated
								'drop_nofee'		=>	'20130223', // TODO : to be updated
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
		$this->set( 'title_for_layout', 'Choix de cours' );

		$registeredCourses = array();
		$selectedCourses = array();

		$schedule = $this->User->ScheduleSemester->find( 'first', array(
			'conditions'	=>	array( 'ScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'ScheduleSemester.semester' => $this->registrationSemester  ),
        	'contain'		=>	array( 'Course' )
        ) );
		
		if ( !empty( $schedule[ 'Course' ] ) )
			$registeredCourses = $schedule[ 'Course' ];

		$sections = $this->User->Section->find( 'all', array(
            'conditions'    =>  array( 'Section.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Course' => array(
            	'conditions'	=> 	array( 'Course.code !=' => 'EHE-1899' )
            ) )
        ) );

		// Get student selected courses for registration semester
		$selectedCourses = $this->User->SelectedCourse->find( 'all', array(
			'conditions'	=>	array(
				'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
				'SelectedCourse.semester'	=>	$this->registrationSemester
			),
			'contain'		=>	array( 'UniversityCourse' )
		) );

		// Extract courses codes
		$coursesCodes = Set::extract( '/Course/code', $sections );
		
		$availableCourses = $this->UniversityCourse->find( 'list', array(
			'conditions'	=>	array( 'UniversityCourse.code' => $coursesCodes, 'UniversityCourse.av' . $this->registrationSemester => true ),
			'fields'		=>	array( 'id', 'code' )
		) );

		if ( !empty( $availableCourses ) )
			$availableCourses = array_values( $availableCourses );

		$this->set( 'sections', $sections );
		$this->set( 'registeredCourses', $registeredCourses );
		$this->set( 'selectedCourses', $selectedCourses );
		$this->set( 'availableCourses', $availableCourses );

    	$this->setAssets( array( '/js/registration.js' ), array( '/css/registration.css' ) );
    	$this->set( 'sidebar', 'registration' );
    	$this->set( 'dataObject', 'studies-courses' );

    	// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'studies-courses' ) ) ) {
        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        	$this->render( 'limited' );
        } else {
        	if ( !empty( $lastRequest ) ) {
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
			} else {
				$this->set( 'timestamp', 1 );
			}

        	// No data exists for this page
        	$this->viewPath = 'commons';
			$this->render( 'searching_courses' );

            return (true);
        }
	}

	public function getCourseInfo ( $code = null ) {
		if ( $this->request->is( 'ajax' ) ) {
			$semester = $this->registrationSemester;

			// Get requested course info
			$course = $this->UniversityCourse->find( 'first', array(
                'conditions'    =>  array( 'UniversityCourse.code' => $code )
            ) );

			$this->set( 'course', $course );
			$this->set( 'semester', $semester );
			
			$this->layout = 'ajax';
			$this->render( 'modals/course_info' );
		}
	}

	public function getAvailableClasses ( $code = null ) {
		//if ( $this->request->is( 'ajax' ) ) {
			$semester = $this->registrationSemester;
			$registeredCourses = array();
			$selectedCourses = array();

			// Get requested course info
			$course = $this->UniversityCourse->find( 'first', array(
                'conditions'    =>  array( 'UniversityCourse.code' => $code ),
                'contain'		=>	array( 'Class' => array( 'Spot' ) )
            ) );

			foreach ( $course[ 'Class' ] as &$class ) {
				if ( empty( $class[ 'Spot' ] ) ) {
					// Update class spots
					$class[ 'Spot' ] = $this->Capsule->updateClassSpots( $class[ 'nrc' ], $semester );

					// Save updated class spots
					$this->UniversityCourse->Class->set( $class );
					$this->UniversityCourse->Class->saveAll( $class );
				} else {
					$lastUpdate = $class[ 'Spot' ][ 'last_update' ];
					$remainingSpots = $class[ 'Spot' ][ 'remaining' ];

					// Check if class spots need to be updated
					if ( $lastUpdate < ( time() - ( 3600 * 24 ) ) || 							// Last update was yesterday or later
						( $remainingSpots < 5 ) || 												// Less than 5 spots remaining
						( $remainingSpots < 10 && $lastUpdate < ( time() - ( 60 * 30 ) ) ) || 	// Less than 10 spots and last update was more than 30 minutes ago
						( $remainingSpots < 20 && $lastUpdate < ( time() - ( 3600 ) ) ) ) {		// Less than 20 spots and last update was more than 1 hour ago
						$spotId = $class[ 'Spot' ][ 'id' ];

						// Update class spots
						$class[ 'Spot' ] = $this->Capsule->updateClassSpots( $class[ 'nrc' ], $semester );
						$class[ 'Spot' ][ 'id' ] = $spotId;

						// Save updated class spots
						$this->UniversityCourse->Class->set( $class );
						$this->UniversityCourse->Class->saveAll( $class );
					}
				}
			}

			// Get student registered courses for registration semester
			$registeredCourses = $this->User->ScheduleSemester->Course->find( 'list', array(
				'conditions'	=>	array(
					'Course.idul' 		=>	$this->Session->read( 'User.idul' ),
					'Course.semester'	=>	$semester
				),
				'fields'	=>	array( 'id', 'nrc' )
			) );
			
			// Get student selected courses for registration semester
			$selectedCourses = $this->User->SelectedCourse->find( 'list', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$semester
				),
				'fields'	=>	array( 'id', 'nrc' )
			) );

			$this->set( 'classes', $course[ 'Class' ] );
			$this->set( 'semester', $semester );
			$this->set( 'registeredCourses', $registeredCourses );
			$this->set( 'selectedCourses', $selectedCourses );

			$this->layout = 'ajax';
			$this->render( 'modals/available_classes' );
		//}
	}

	function selectCourse () {
		if ( $this->request->is( 'ajax' ) ) {
			$nrc = $this->request->data[ 'nrc' ];
			$semester = $this->registrationSemester;
			$replace = null;
			if ( !empty( $this->request->data[ 'replace' ] ) )
				$replace = $this->request->data[ 'replace' ];

			// Get requested course info
			$class = $this->UniversityCourse->Class->find( 'first', array(
                'conditions'    =>  array( 'Class.nrc' => $nrc ),
                'contain'		=>	array( 'UniversityCourse' )
            ) );
			
			// Check if student is already registered to this course
			if ( $this->User->ScheduleSemester->Course->find( 'list', array(
				'conditions'	=>	array(
					'Course.idul' 		=>	$this->Session->read( 'User.idul' ),
					'Course.semester'	=>	$semester,
					'Course.nrc'		=>	$nrc
				) ) ) != array() ) {
				// Error : course already registered
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    	=>  false,
	            		'errorCode'		=>	5
	            	) )
	            ) );
			}

			// Check if student is already registered to a similar course
			if ( $this->User->ScheduleSemester->Course->find( 'list', array(
				'conditions'	=>	array(
					'Course.idul' 		=>	$this->Session->read( 'User.idul' ),
					'Course.semester'	=>	$semester,
					'Course.code'		=>	$class[ 'UniversityCourse' ][ 'code' ]
				) ) ) != array() ) {
				if ( $replace == null ) {
					// Error : similar course already registered
					return new CakeResponse( array(
		            	'body' => json_encode( array(
		            		'status'    	=>  false,
		            		'errorCode'		=>	6,
		            		'nrc'			=>	$nrc
		            	) )
		            ) );
		        }
			}
			
			// Check if student has already selected this course
			if ( $this->User->SelectedCourse->find( 'list', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$semester,
					'SelectedCourse.nrc'	=>	$nrc
				) ) ) != array() ) {
				// Error : course already selected
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    	=>  false,
	            		'errorCode'		=>	3
	            	) )
	            ) );
			}

			// Check if student has already selected a similar course
			if ( $this->User->SelectedCourse->find( 'list', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$semester,
					'SelectedCourse.code'		=>	$class[ 'UniversityCourse' ][ 'code' ]
				) ) ) != array() ) {
				if ( $replace == null ) {
					// Error : similar course already selected
					return new CakeResponse( array(
		            	'body' => json_encode( array(
		            		'status'    	=>  false,
		            		'errorCode'		=>	4,
		            		'nrc'			=>	$nrc
		            	) )
		            ) );
		        } elseif ( $replace ) {
		        	// Delete similar selected course
					$this->User->SelectedCourse->deleteAll( array(
						'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
						'SelectedCourse.semester'	=>	$semester,
						'SelectedCourse.code'		=>	$class[ 'UniversityCourse' ][ 'code' ]
					) );
				}
			}

			// Add selected course to course selection
			$selectedCourse = array( 'SelectedCourse' => array(
				'idul'		=>	$this->Session->read( 'User.idul' ),
				'course_id'	=>	$class[ 'UniversityCourse' ][ 'id' ],
				'code'		=>	$class[ 'UniversityCourse' ][ 'code' ],
				'nrc'		=>	$nrc,
				'semester'	=>	$semester
			) );

			$this->User->SelectedCourse->create();
			$this->User->SelectedCourse->set( $selectedCourse );
			if ( $this->User->SelectedCourse->save( $selectedCourse ) ) {
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    			=>  true,
	            		'nrc'					=>	$nrc
	            	) )
	            ) );
	        } else {
				// Error : unknown error
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    	=>  false,
	            		'errorCode'		=>	2
	            	) )
	            ) );
			}
		}
	}

	function unselectCourse () {
		if ( $this->request->is( 'ajax' ) ) {
			$nrc = $this->request->data[ 'nrc' ];
			$semester = $this->registrationSemester;

			if ( $this->User->SelectedCourse->deleteAll( array( 'SelectedCourse.nrc' => $nrc, 'SelectedCourse.semester' => $semester, 'SelectedCourse.idul' => $this->Session->read( 'User.idul' ) ) ) ) {
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    			=>  true,
	            		'nrc'					=>	$nrc
	            	) )
	            ) );
	        } else {
				// Error : unknown error
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    	=>  false,
	            		'errorCode'		=>	2
	            	) )
	            ) );
			}
		}
	}
}

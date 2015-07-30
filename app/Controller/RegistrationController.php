<?php
class RegistrationController extends AppController {
	public $uses = array( 'CacheRequest', 'CourseSubject', 'User', 'UniversityCourse', 'StudentProgram', 'RegistrationLog' );

	public $components = array( 'Cookie' );

	public $helpers = array( 'Time', 'Text' );

	private $registrationSemester;
	private $currentSemester = CURRENT_SEMESTER;
	private $deadlines = array(
							'201509'    => array(
								'registration_start'=>  '20150330',
								'edit_selection'    =>  '20150908',
								'drop_nofee'        =>  '20150914',
								'drop_fee'          =>  '20151109'
							),
							'201505'    => array(
								'registration_start'=>  '20150309',
								'edit_selection'    =>  '20150501',
								'drop_nofee'        =>  '20150821',
								'drop_fee'          =>  '20150821'
							),
							'201501'	=> array(
								'registration_start'=>	'20141103',
								'edit_selection'	=>	'20150120',
								'drop_nofee'		=>	'20150126',
								'drop_fee'			=>	'20150323'
							),
                            '201409'    => array(
                                'registration_start'=>  '20140331',
                                'edit_selection'    =>  '20140909',
                                'drop_nofee'        =>  '20140916',
                                'drop_fee'          =>  '20141111'
                            ),
                            '201405'    => array(
                                'registration_start'=>  '20140310',
                                'edit_selection'    =>  '20140502',
                                'drop_nofee'        =>  '20140822',
                                'drop_fee'          =>  '20140822'
                            ),
							'201401'	=> array(
								'registration_start'=>	'20131104',
								'edit_selection'	=>	'20140130',
								'drop_nofee'		=>	'20140204',
								'drop_fee'			=>	'20140401'
							),
							'201309'	=> array(
								'registration_start'=>	'20130402',
								'edit_selection'	=>	'20130910',
								'drop_nofee'		=>	'20130917',
								'drop_fee'			=>	'20131112'
							),
							'201305'	=> array(
								'registration_start'=>	'20130311',
								'edit_selection'	=>	'20130430',
								'drop_nofee'		=>	'20130823',
								'drop_fee'			=>	'20130823'
							),
						   '201301'	=> array(
								'registration_start'=>	'20121105',
								'edit_selection'	=>	'20130129',
								'drop_nofee'		=>	'20130204',
								'drop_fee'			=>	'20130401'
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
	
	private $registrationSemesters = array( '201409', '201501' );

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

		if ( $this->Session->read( 'Registration.semester' ) != '' ) {
			$this->registrationSemester = $this->Session->read( 'Registration.semester' );
		} else {
			$this->registrationSemester = '201501';
			$this->Session->write( 'Registration.semester', $this->registrationSemester );
		}

        // If unregistration is still possible for current semester, add it to the registration semesters list
        if ( !in_array( $this->currentSemester, $this->registrationSemesters ) && $this->deadlines[ $this->currentSemester ][ 'drop_fee' ] >= date( 'Ymd' ) ) {
            $this->registrationSemesters[] = $this->currentSemester;
            sort( $this->registrationSemesters );
        }
	}

	public function index ( $semester = null, $programId = null ) {
		// If user selected a different semester, use it for registration
		if ( !empty( $semester ) ) {
			if ( in_array( $semester, $this->registrationSemesters ) ) {
				$newSemester = $semester;
				$this->Session->write( 'Registration.semester', $semester );
				$this->registrationSemester = $semester;
			} else {
				$semester = $this->registrationSemester;
			}
		}

		$this->set( 'currentSemester', $this->currentSemester );
		$this->set( 'registrationSemester', $this->registrationSemester );
		$this->set( 'registrationSemesters', $this->registrationSemesters );
		$this->set( 'deadlines', $this->deadlines );
		$this->set( 'title_for_layout', 'Choix de cours' );
		$this->set( 'buttons', array(
        	array(
                'element'	=>  'registration_dropdowns',
                'type'  	=>  'dropdown',
                'vars'		=>	array( 'semestersList' => $this->registrationSemesters, 'selectedSemester' => $this->registrationSemester )
            ),
            array(
                'action'=>  "app.Cache.reloadData( { name: 'studies-courses', auto: 0, callback: function(){ app.Common.refreshPageContent( false, function(){ $( '.courses-list tbody tr.not-available' ).hide( 'fast' ).promise().done( app.Registration.repaintTableRows ); } ); } } );",
                'type'  =>  'refresh'
            )
        ) );
		$this->set( 'reloadDataCallback', "function(){ app.Common.refreshPageContent( false, function(){ $( '.courses-list tbody tr.not-available' ).hide( 'fast' ).promise().done( app.Registration.repaintTableRows ); } ); }" );
		
		$registeredCourses = array();
		$selectedCourses = array();

		if ( empty( $programId ) ) {
            $program = $this->StudentProgram->User->find( 'first', array(
                'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
                'contain'       =>  array( 'Program' => array( 'limit' => 1, 'order' => 'Program.adm_semester DESC' ) ),
                'fields'        =>  array( 'User.idul' )
            ) );

            $program[ 'Program' ] = array_shift( $program[ 'Program' ] );
        } else {
            $program = $this->StudentProgram->User->find( 'first', array(
                'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
                'contain'       =>  array( 'Program' => array( 'conditions' => array( 'Program.id' => $programId, 'Program.idul' => $this->Session->read( 'User.idul' ) ) ) ),
                'fields'        =>  array( 'User.idul' )
            ) );

            $program[ 'Program' ] = array_shift( $program[ 'Program' ] );
        }

        $programsList = $this->StudentProgram->find( 'list', array(
            'conditions'    =>  array( 'StudentProgram.idul' => $this->Session->read( 'User.idul' ) ),
            'order'         =>  'StudentProgram.adm_semester DESC'
        ) );

        // Retrieve courses already registered by the student
		$schedule = $this->User->ScheduleSemester->find( 'first', array(
			'conditions'	=>	array( 'ScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'ScheduleSemester.semester' => $this->registrationSemester  ),
        	'contain'		=>	array( 'Course' )
        ) );
		
		if ( !empty( $schedule[ 'Course' ] ) )
			$registeredCourses = $schedule[ 'Course' ];

		// Retrieve program sections & courses
		$sections = $this->User->Section->find( 'all', array(
            'conditions'    =>  array( 'Section.idul' => $this->Session->read( 'User.idul' ), 'program_id' => $program[ 'Program' ][ 'id' ] ),
            'contain'       =>  array( 'Course' => array(
            	'conditions'	=> 	array( 'Course.code !=' => 'EHE-1899' )
            ) )
        ) );

		// Extract courses codes from program sections' courses
		$coursesCodes = Set::extract( '/Course/code', $sections );
		
		$availableCourses = $this->UniversityCourse->find( 'list', array(
			'conditions'	=>	array( 'UniversityCourse.code' => $coursesCodes, 'UniversityCourse.av' . $this->registrationSemester => true ),
			'fields'		=>	array( 'id', 'code' )
		) );

		if ( !empty( $availableCourses ) ) {
			$availableCourses = array_values( $availableCourses );
		}

		// Retrieve student selected courses for registration semester
		$selectedCourses = $this->User->SelectedCourse->find( 'all', array(
			'conditions'	=>	array(
				'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
				'SelectedCourse.semester'	=>	$this->registrationSemester
			),
			'contain'		=>	array( 'UniversityCourse' )
		) );

		$this->set( 'programsList', $programsList );
		$this->set( 'program', $program );
		$this->set( 'sections', $sections );
		$this->set( 'registeredCourses', $registeredCourses );
		$this->set( 'selectedCourses', $selectedCourses );
		$this->set( 'availableCourses', $availableCourses );

    	$this->setAssets( array( '/js/registration.js' ), array( '/css/registration.css' ) );
    	$this->set( 'sidebar', 'registration' );
    	$this->set( 'dataObject', 'studies-courses' );

    	// Check if registration help modal need to be displayed (first visit)
    	if ( $this->Cookie->read( 'pilule-registration-help' ) == 'completed' ) {
    		$this->set( 'displayHelpModal', false );
    	} else {
    		$this->set( 'displayHelpModal', true );
    	}

    	// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'studies-courses-' . $this->registrationSemester ) ) ) {
        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
        	if ( !empty( $lastRequest ) ) {
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
			} else {
				$this->set( 'timestamp', 1 );
			}

        	// No data exists for this page
        	$this->viewPath = 'Commons';
			$this->render( 'fetching_courses' );
        }
	}

	public function search () {
		$this->set( 'title_for_layout', 'Recherche de cours' );
		$this->set( 'sidebar', 'registration' );
		$this->set( 'deadlines', $this->deadlines );
		$this->set( 'registrationSemester', $this->registrationSemester );
		$this->set( 'registrationSemesters', $this->registrationSemesters );
		$this->setAssets( array( '/js/registration.js' ), array( '/css/registration.css' ) );
		$validationErrors = '';
		$searchResultsCodes = array();
		$selectedCourses = array();
		$registeredCourses = array();

		// Retrieve student selected courses for registration semester
		$selectedCourses = $this->User->SelectedCourse->find( 'all', array(
			'conditions'	=>	array(
				'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
				'SelectedCourse.semester'	=>	$this->registrationSemester
			),
			'contain'		=>	array( 'UniversityCourse' )
		) );

		// Retrieve courses already registered by the student
		$schedule = $this->User->ScheduleSemester->find( 'first', array(
			'conditions'	=>	array( 'ScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'ScheduleSemester.semester' => $this->registrationSemester  ),
        	'contain'		=>	array( 'Course' )
        ) );
		
		if ( !empty( $schedule[ 'Course' ] ) )
			$registeredCourses = $schedule[ 'Course' ];

		if ( $this->request->is( 'post' ) ) {
			if ( in_array( $this->request->data[ 'Registration' ][ 'semester' ], $this->registrationSemesters ) ) {
				$this->Session->write( 'Registration.semester', $this->request->data[ 'Registration' ][ 'semester' ] );
				$this->registrationSemester = $this->request->data[ 'Registration' ][ 'semester' ];
			}

			// Validate search request
			if ( !empty( $this->request->data[ 'Registration' ][ 'code' ] ) ) {
				$code = strtoupper( trim( str_replace( '-', '', str_replace( ' ', '', $this->request->data[ 'Registration' ][ 'code' ] ) ) ) );
				if ( !preg_match( '/([a-zA-Z]{3})([0-9]{4})/', $code ) ) {
					$validationErrors = 'Le code de cours est invalide (format : XYZ-1234)';
				}
			} elseif ( !empty( $this->request->data[ 'Registration' ][ 'keywords' ] ) ) {
				if ( !empty( $this->request->data[ 'Registration' ][ 'subject' ] ) ) {
					// Try to find the subject
					$subject = $this->CourseSubject->find( 'first', array(
						'conditions'	=>	array( 'CourseSubject.title' => $this->request->data[ 'Registration' ][ 'subject' ] ),
						'fields'		=>	array( 'code' )
					) );

					if ( !empty( $subject ) ) {
						$subject = $subject[ 'CourseSubject' ][ 'code' ];
					} else {
						$validationErrors= 'La matière du cours est introuvable';
					}
				} else {
					// Search in all subjects
					$subject = array_keys( $this->CourseSubject->find( 'list', array(
						'fields'	=>	array( 'code', 'title' )
					) ) );
				}
			} else {
				$validationErrors = 'Veuillez indiquer le code du cours ou un mot-clé pour la recherche.';
			}

			if ( empty( $validationErrors ) ) {
				if ( !empty( $this->request->data[ 'Registration' ][ 'code' ] ) ) {
					// Attempt to find the course in database first
					$searchResults = $this->UniversityCourse->find( 'all', array(
						'conditions'	=>	array( 'UniversityCourse.code' => $this->request->data[ 'Registration' ][ 'code' ], 'UniversityCourse.av' . $this->request->data[ 'Registration' ][ 'semester' ] => true )
					) );
				}

				if ( !empty( $searchResults ) ) {
					// Check if course availability info need to be updated
                    if ( $searchResults[ 0 ][ 'UniversityCourse' ][ 'checkup_' . $this->request->data[ 'Registration' ][ 'semester' ] ] < ( time() - 3600 * 24 * 7 ) ) {
                        // Delete all existing classes for this course
                        $this->UniversityCourse->Class->deleteAll( array( 'Class.course_id' => $searchResults[ 0 ][ 'UniversityCourse' ][ 'id' ], 'Class.semester' => $this->request->data[ 'Registration' ][ 'semester' ] ) );

                        // Update course availability info
                        $classes = $this->Capsule->fetchClasses( $searchResults[ 0 ][ 'UniversityCourse' ][ 'code' ], $this->request->data[ 'Registration' ][ 'semester' ] );

                        if ( !empty( $classes[ 'Class' ] ) ) {
                            // Save newly fetched classes for this course
                            $searchResults[ 0 ][ 'Class' ] = $classes[ 'Class' ];
                            $searchResults[ 0 ][ 'UniversityCourse' ][ 'checkup_' . $this->request->data[ 'Registration' ][ 'semester' ] ] = time();
                            $searchResults[ 0 ][ 'UniversityCourse' ][ 'av' . $this->request->data[ 'Registration' ][ 'semester' ] ] = true;
                            $this->UniversityCourse->set( $searchResults[ 0 ] );
                            $this->UniversityCourse->saveAll( $searchResults[ 0 ] );
                        } else {
                            // Update course availability info
                            $searchResults[ 0 ][ 'UniversityCourse' ][ 'checkup_' . $this->request->data[ 'Registration' ][ 'semester' ] ] = time();
                            $searchResults[ 0 ][ 'UniversityCourse' ][ 'av' . $this->request->data[ 'Registration' ][ 'semester' ] ] = false;
                            $this->UniversityCourse->set( $searchResults[ 0 ] );
                            $this->UniversityCourse->saveAll( $searchResults[ 0 ] );
                        }
                    }
				} else {
					// Test connection to Capsule server
					$this->CapsuleAuth->testConnection();

					$searchRequest = $this->request->data[ 'Registration' ];

					if ( !empty( $searchRequest[ 'keywords' ] ) ) {
						$searchRequest[ 'subject' ] = $subject;
					}

					// Send course search request to Capsule
					$resultCourses = $this->Capsule->searchCourses( $searchRequest );

					if ( !empty( $resultCourses ) ) {
						// Parse found NRC and fetch courses not already existing in database
						foreach ( $resultCourses as $nrc => $code ) {
							// Check if course already exists in database
							$course = $this->UniversityCourse->find( 'first', array(
								'conditions'	=>	array( 'UniversityCourse.code' => $code )
							) );

							if ( empty( $course ) ) {
								// Fetch course info from Capsule
		                        $course = $this->Capsule->fetchCourse( $code, $this->request->data[ 'Registration' ][ 'semester' ] );

		                        if ( !empty( $course[ 'UniversityCourse' ][ 'title' ] ) ) {
									// Save fetched course info
			                        $this->UniversityCourse->create();
			                        $this->UniversityCourse->set( $course );
			                        $this->UniversityCourse->saveAll( $course, array( 'deep' => true ) );
			                    }
							} else {
								// Check if course availability info need to be updated
		                        if ( $course[ 'UniversityCourse' ][ 'checkup_' . $this->request->data[ 'Registration' ][ 'semester' ] ] < ( time() - 3600 * 24 * 7 ) ) {
		                            // Delete all existing classes for this course
		                            $this->UniversityCourse->Class->deleteAll( array( 'Class.course_id' => $course[ 'UniversityCourse' ][ 'id' ], 'Class.semester' => $this->request->data[ 'Registration' ][ 'semester' ] ) );

		                            // Update course availability info
		                            $classes = $this->Capsule->fetchClasses( $course[ 'UniversityCourse' ][ 'code' ], $this->request->data[ 'Registration' ][ 'semester' ] );

		                            if ( !empty( $classes[ 'Class' ] ) ) {
		                                // Save newly fetched classes for this course
		                                $course[ 'Class' ] = $classes[ 'Class' ];
		                                $course[ 'UniversityCourse' ][ 'checkup_' . $this->request->data[ 'Registration' ][ 'semester' ] ] = time();
		                                $course[ 'UniversityCourse' ][ 'av' . $this->request->data[ 'Registration' ][ 'semester' ] ] = true;
		                                $this->UniversityCourse->set( $course );
		                                $this->UniversityCourse->saveAll( $course );
		                            } else {
		                                // Update course availability info
		                                $course[ 'UniversityCourse' ][ 'checkup_' . $this->request->data[ 'Registration' ][ 'semester' ] ] = time();
		                                $course[ 'UniversityCourse' ][ 'av' . $this->request->data[ 'Registration' ][ 'semester' ] ] = false;
		                                $this->UniversityCourse->set( $course );
		                                $this->UniversityCourse->saveAll( $course );
		                            }
		                        }
							}

							$searchResultsCodes[] = $code;
		                }
					}

					$searchResults = $this->UniversityCourse->find( 'all', array(
						'conditions'	=>	array( 'UniversityCourse.code' => $searchResultsCodes, 'UniversityCourse.av' . $this->request->data[ 'Registration' ][ 'semester' ] => true ),
					) );
				}

				$this->set( 'searchResults', $searchResults );
			}
		}

		$coursesSubjects = $this->CourseSubject->find( 'list', array(
			'fields'	=>	array( 'code', 'title' )
		) );

		$this->set( 'registeredCourses', $registeredCourses );
		$this->set( 'selectedCourses', $selectedCourses );
		$this->set( 'coursesSubjects', $coursesSubjects );
		$this->set( 'validationErrors', $validationErrors );
	}

	public function results ( $token = null ) {
		// Get registration result from the session
		$results = $this->Session->read( 'Registration.results.' . $token );

		if ( !empty( $results ) ) {
			$this->set( 'title_for_layout', 'Résultats de l\'inscription' );
			$this->setAssets( array( '/js/registration.js' ), array( '/css/registration.css' ) );
	    	$this->set( 'sidebar', 'registration-results' );
	    	$this->set( 'dataObject', 'studies-courses' );
	    	$this->set( 'results', $results );
	    	$this->set( 'currentSemester', $this->currentSemester );
			$this->set( 'registrationSemester', $this->registrationSemester );
			$this->set( 'registrationSemesters', $this->registrationSemesters );
			$this->set( 'deadlines', $this->deadlines );

			$registeredCourses = array();
			$selectedCourses = array();
			$courses = array();

			$schedule = $this->User->ScheduleSemester->find( 'first', array(
				'conditions'	=>	array( 'ScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'ScheduleSemester.semester' => $this->registrationSemester  ),
	        	'contain'		=>	array( 'Course' )
	        ) );
			
			if ( !empty( $schedule[ 'Course' ] ) )
				$registeredCourses = $schedule[ 'Course' ];

			// Get student selected courses for registration semester
			$selectedCourses = $this->User->SelectedCourse->find( 'all', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$this->registrationSemester
				),
				'contain'		=>	array( 'UniversityCourse' )
			) );

			$this->set( 'registeredCourses', $registeredCourses );
			$this->set( 'selectedCourses', $selectedCourses );

			if ( !empty( $results ) ) {
				$courses = $this->UniversityCourse->Class->find( 'all', array(
					'conditions'	=>	array( 'Class.nrc' => array_keys( $results ) ),
					'contain'		=>	'UniversityCourse'
				) );
				$this->set( 'courses', $courses );
			}
		} else {

		}
	}

	public function getCourseInfo ( $code = null ) {
		if ( $this->request->is( 'ajax' ) ) {
			$semester = $this->registrationSemester;
			$registeredCourses = array();
			$selectedCourses = array();

			// Get requested course info
			$course = $this->UniversityCourse->find( 'first', array(
                'conditions'    =>  array( 'UniversityCourse.code' => $code ),
                'contain'		=>	array( 'Class' => array( 'conditions' => array( 'Class.semester' => $this->registrationSemester ), 'Spot' ) )
            ) );

            if ( !empty( $course ) ) {
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

                $this->set( 'registeredCourses', $registeredCourses );
                $this->set( 'selectedCourses', $selectedCourses );
                $this->set( 'classes', $course[ 'Class' ] );
            }

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
                'contain'		=>	array( 'Class' => array( 'conditions' => array( 'Class.semester' => $this->registrationSemester ), 'Spot' ) )
            ) );

			// Retrieve average response time for getting class spots from Capsule
			$averageResponseTime = $this->CacheRequest->getAverageResponseTime( 'registration-class-spots' );

			// Update HTTP fetcher timeout
			$timeout = ( ( int )$averageResponseTime ) + 10;
			$this->HttpFetcher->timeout = $timeout;

			$averageResponseTime = 0;
			$responseTimes = array();

			foreach ( $course[ 'Class' ] as &$class ) {
				if ( empty( $class[ 'Spot' ] ) ) {
					$startTime = microtime( true );

					// Update class spots
					$class[ 'Spot' ] = $this->Capsule->updateClassSpots( $class[ 'nrc' ], $semester );

					$responseTime[] = microtime( true ) - $startTime;

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

						$startTime = microtime( true );

						// Update class spots
						$class[ 'Spot' ] = $this->Capsule->updateClassSpots( $class[ 'nrc' ], $semester );
						$class[ 'Spot' ][ 'id' ] = $spotId;

						$responseTime[] = microtime( true ) - $startTime;

						// Save updated class spots
						$this->UniversityCourse->Class->set( $class );
						$this->UniversityCourse->Class->saveAll( $class );
					}
				}
			}

			// Save average response time
            if ( count( $responseTimes ) != 0 ) {
                $averageResponseTime = array_sum( $responseTimes ) / count( $responseTimes );
	            $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'registration-class-spots', null, $averageResponseTime );
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

	public function help ( $step = 1 ) {
		if ( $this->request->is( 'ajax' ) ) {
			$this->set( 'step', $step );

			if ( $step == 1 ) {
				// Save cookie
				$this->Cookie->write( 'pilule-registration-help', 'completed', false, '1 year' );
			}

			$this->layout = 'ajax';
			$this->render( 'modals/help' );
		} else {
			$this->set( 'title_for_layout', 'Aide' );
			$this->set( 'sidebar', 'registration' );
			$this->setAssets( array( '/js/registration.js' ), array( '/css/registration.css' ) );
			
			$selectedCourses = array();
			$registeredCourses = array();

			// Retrieve student selected courses for registration semester
			$selectedCourses = $this->User->SelectedCourse->find( 'all', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$this->registrationSemester
				),
				'contain'		=>	array( 'UniversityCourse' )
			) );

			// Retrieve courses already registered by the student
			$schedule = $this->User->ScheduleSemester->find( 'first', array(
				'conditions'	=>	array( 'ScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'ScheduleSemester.semester' => $this->registrationSemester  ),
		    	'contain'		=>	array( 'Course' )
		    ) );
			
			if ( !empty( $schedule[ 'Course' ] ) )
				$registeredCourses = $schedule[ 'Course' ];

			$this->set( 'registeredCourses', $registeredCourses );
			$this->set( 'selectedCourses', $selectedCourses );
			$this->set( 'deadlines', $this->deadlines );
			$this->set( 'registrationSemester', $this->registrationSemester );
			$this->set( 'registrationSemesters', $this->registrationSemesters );
		}
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

	function registerCourses () {
		if ( $this->request->is( 'ajax' ) ) {
			$registrationStartTime = time();

			$semester = $this->registrationSemester;

			// Get student selected courses for registration semester
			$selectedCourses = $this->User->SelectedCourse->find( 'list', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$semester
				),
				'fields'	=>	array( 'id', 'nrc' )
			) );

			if ( empty( $selectedCourses ) ) {

			}

			// Retrieve average response time for login to Capsule
			$averageResponseTime = $this->CacheRequest->getAverageResponseTime( 'login' );

			// Update HTTP fetcher timeout
			$timeout = ( ( int )$averageResponseTime ) + 10;
			$this->HttpFetcher->timeout = $timeout;

			// Test connection to Capsule server
			$this->CapsuleAuth->testConnection();

			// Retrieve average response time for registration with Capsule
			$averageResponseTime = $this->CacheRequest->getAverageResponseTime( 'registration' );

			// Update HTTP fetcher timeout
			$timeout = ( ( int )$averageResponseTime ) + 60;
			$this->HttpFetcher->timeout = $timeout;

			// Log registration attempt
			$this->RegistrationLog->create();
			$this->RegistrationLog->set( array(
				'idul'	=>	$this->Session->read( 'User.idul' ),
				'semester'	=>	$semester,
				'requested_courses'	=>	implode( ',', array_values( $selectedCourses ) )
			) );
			$this->RegistrationLog->save();

			$logId = $this->RegistrationLog->getLastInsertId();

			// Check registration availability
			$registrationAvailability = $this->Capsule->checkRegistrationAvailability( $semester );

			if ( $registrationAvailability[ 'status' ] ) {
				// Log registration availability
				$this->RegistrationLog->updateAll( array( 'is_student_allowed' => true, 'is_service_available' => true, 'is_registration_period' => true, 'has_form' => true ), array( 'id' => $logId ) );

				$startTime = microtime( true );

				// Send course registration request to Capsule
				$registrationResults = $this->Capsule->registerCourses( array_values( $selectedCourses ), $semester, $registrationAvailability[ 'data' ] );

				$responseTime = microtime( true ) - $startTime;

				// Save registration response time
		        $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'registration', null, $responseTime );

				$registrationTotalTime = time() - $registrationStartTime;

				if ( $registrationResults[ 'status' ] ) {
					// Log registration status
					$this->RegistrationLog->updateAll( array( 'status' => true, 'request_time' => $registrationTotalTime ), array( 'id' => $logId ) );

					// Destroy registration log data
					$this->RegistrationLog->Data->deleteAll( array( 'registration_log_id' => $logId ) );

					$this->_reloadSemesterSchedule( $semester );
					
					foreach ( $selectedCourses as $id => $nrc ) {
						if ( isset( $registrationResults[ 'coursesStatus' ][ $nrc ] ) && $registrationResults[ 'coursesStatus' ][ $nrc ][ 'registered' ] ) {
							$this->User->SelectedCourse->delete( $id );

							// Update classes spots
							$this->Capsule->updateClassSpots( $nrc, $semester );
						}
					}
					
					// Save registration results in session
					$token = md5( uniqid() );

					if ( $this->Session->write( 'Registration.results.' . $token, $registrationResults[ 'coursesStatus' ] ) ) {
						return new CakeResponse( array(
			            	'body' => json_encode( array(
			            		'status'    =>  true,
			            		'token'		=>	$token
			            	) )
			            ) );
					} else {
						// Return error message
						return new CakeResponse( array(
		            	'body' => json_encode( array(
		            		'status'    	=>  false,
		            		'errorMessage'	=>	'Erreur lors de l\'inscription. Veuillez réessayer.'
		            	) )
		            ) );
					}
				} else {
					// Log registration status
					$this->RegistrationLog->updateAll( array( 'status' => false, 'request_time' => $registrationTotalTime ), array( 'id' => $logId ) );

					// Log response data
					foreach( $registrationResults[ 'data' ] as $index => $responseData ) {
						$this->RegistrationLog->Data->create();
						$this->RegistrationLog->Data->set( array(
							'registration_log_id'	=>	$logId,
							'idul'					=>	$this->Session->read( 'User.idul' ),
							'data'					=>	$responseData,
							'number'				=>	( $index + 2 )
						) );
						$this->RegistrationLog->Data->save();
					}

					return new CakeResponse( array(
		            	'body' => json_encode( array(
		            		'status'    	=>  false,
		            		'errorMessage'	=>	'Erreur lors de l\'inscription. Veuillez réessayer.'
		            	) )
		            ) );
				}
			} else {
				$registrationTotalTime = time() - $registrationStartTime;

				// Log response data
				$this->RegistrationLog->Data->create();
				$this->RegistrationLog->Data->set( array(
					'registration_log_id'	=>	$logId,
					'idul'					=>	$this->Session->read( 'User.idul' ),
					'data'					=>	$registrationAvailability[ 'data' ],
					'number'				=>	1
				) );
				$this->RegistrationLog->Data->save();

				switch( $registrationAvailability[ 'error' ] ) {
					case 'not-allowed':
						// Log registration availability
						$this->RegistrationLog->updateAll( array( 'is_student_allowed' => false, 'status' => false, 'request_time' => $registrationTotalTime ), array( 'id' => $logId ) );

						// Return error message
						return new CakeResponse( array(
			            	'body' => json_encode( array(
			            		'status'    	=>  false,
			            		'errorMessage'	=>	'Inscription impossible puisque vous n\'avez pas de période d\'inscription accordée. <br>Veuillez communiquer avec votre direction de programme.'
			            	) )
			            ) );
			        break;
			        case 'service-unavailable':
						// Log registration availability
						$this->RegistrationLog->updateAll( array( 'is_service_available' => false, 'status' => false, 'request_time' => $registrationTotalTime ), array( 'id' => $logId ) );

						// Return error message
						return new CakeResponse( array(
			            	'body' => json_encode( array(
			            		'status'    	=>  false,
			            		'errorMessage'	=>	'Erreur lors de l\'inscription : Capsule est hors service. Veuillez réessayer plus tard.'
			            	) )
			            ) );
			        break;
			        case 'out-of-period':
						// Log registration availability
						$this->RegistrationLog->updateAll( array( 'is_registration_period' => false, 'status' => false, 'request_time' => $registrationTotalTime ), array( 'id' => $logId ) );

						// Return error message
						return new CakeResponse( array(
			            	'body' => json_encode( array(
			            		'status'    	=>  false,
			            		'errorMessage'	=>	'Erreur lors de l\'inscription : votre période d\'inscription commencera le ' . $registrationAvailability[ 'initialDate' ]
			            	) )
			            ) );
			        break;
			        case 'no-form':
						// Log registration availability
						$this->RegistrationLog->updateAll( array( 'has_form' => false, 'status' => false, 'request_time' => $registrationTotalTime ), array( 'id' => $logId ) );

						// Return error message
						return new CakeResponse( array(
			            	'body' => json_encode( array(
			            		'status'    	=>  false,
			            		'errorMessage'	=>	'Réponse invalide du serveur Capsule : aucun formulaire.'
			            	) )
			            ) );
			        break;
				}
			}
		}
	}

	function unregisterCourse () {
		if ( $this->request->is( 'ajax' ) ) {
			// Temporarily disable course removal
			return new CakeResponse( array(
            	'body' => json_encode( array(
            		'status'    			=>  false,
            		'nrc'					=>	$nrc
            	) )
            ) );

			$nrc = $this->request->data[ 'nrc' ];
			$semester = $this->registrationSemester;

			// Test connection to Capsule server
			$this->CapsuleAuth->testConnection();

			// Send course removal request to Capsule
			if ( $this->Capsule->removeCourse( $nrc, $semester ) ) {
				// Course removed successfully

				$this->_reloadSemesterSchedule( $semester );

                return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    			=>  true,
	            		'nrc'					=>	$nrc
	            	) )
	            ) );
			} else {
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    			=>  false,
	            		'nrc'					=>	$nrc
	            	) )
	            ) );
			}
		}
	}

	private function _reloadSemesterSchedule ( $semester ) {
		// Get data request from DB
        $request = $this->CacheRequest->find( 'first', array(
            'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name' => 'schedule-' . $semester )
        ) );
        if ( !empty( $request ) ) {
            $md5Hash = array( $request[ 'CacheRequest' ][ 'name' ] => $request[ 'CacheRequest' ][ 'md5' ] );
        } else {
            $md5Hash = array();
        }

		// Reload schedule from Capsule
		$this->Capsule->forceReload = true;
		$result = $this->Capsule->getSchedule( $md5Hash );

		// Similar data have been found in DB (not reloaded)
        if ( !$result ) {
            // Unknown error
            return false;
        } elseif ( $result[ 'status' ] ) {
            // Delete user's schedule saved in DB for the registration semester
            $this->User->ScheduleSemester->deleteAll( array(
                'ScheduleSemester.idul'  	=>  $this->Session->read( 'User.idul' ),
                'ScheduleSemester.semester'	=>	$semester
            ) );

            // Save schedule data
            $this->User->ScheduleSemester->saveAll( $result[ 'schedule' ], array( 'deep' => true ) );

            // Update last data checkup timestamp
            foreach ( $result[ 'md5Hash' ] as $name => $hash ) {
                $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), $name, $hash );
            }

            $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'schedule' );

            return true;
        }
	}
}

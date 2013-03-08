<?php
class RegistrationController extends AppController {
	public $uses = array( 'User', 'UniversityCourse', 'StudentProgram' );

	public $helpers = array( 'Time', 'Text' );

	private $registrationSemester;
	private $currentSemester = CURRENT_SEMESTER;
	private $deadlines = array(
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
	
	private $registrationSemesters = array( '201209', '201301', '201305' );

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
			$this->registrationSemester = '201301';
			$this->Session->write( 'Registration.semester', $this->registrationSemester );
		}
	}

	public function index ( $semester = null, $programId = null ) {
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

    	// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'studies-courses-' . $this->registrationSemester ) ) ) {
        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        	$this->render( 'limited' );
        } else {
        	if ( !empty( $lastRequest ) ) {
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
			} else {
				$this->set( 'timestamp', 1 );
			}

        	// No data exists for this page
        	$this->viewPath = 'Commons';
			$this->render( 'searching_courses' );

            return (true);
        }
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
                'contain'		=>	array( 'Class' => array( 'conditions' => array( 'Class.semester' => $this->registrationSemester ), 'Spot' ) )
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

	function registerCourses () {
		if ( $this->request->is( 'ajax' ) ) {
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

			// Test connection to Capsule server
			$this->CapsuleAuth->testConnection();

			// Send course registration request to Capsule
			$registrationResults = $this->Capsule->registerCourses( array_values( $selectedCourses ), $semester );

			if ( empty( $registrationResults ) || $registrationResults === false ) {
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    =>  false
	            	) )
	            ) );
			} else {
				$this->_reloadSemesterSchedule( $semester );
				
				foreach ( $selectedCourses as $id => $nrc ) {
					if ( isset( $registrationResults[ $nrc ] ) && $registrationResults[ $nrc ][ 'registered' ] ) {
						$this->User->SelectedCourse->delete( $id );

						// Update classes spots
						$this->Capsule->updateClassSpots( $nrc, $semester );
					}
				}
				
				// Save registration results in session
				$token = md5( uniqid() );

				if ( $this->Session->write( 'Registration.results.' . $token, $registrationResults ) ) {
					return new CakeResponse( array(
		            	'body' => json_encode( array(
		            		'status'    =>  true,
		            		'token'		=>	$token
		            	) )
		            ) );
				} else {
					return new CakeResponse( array(
		            	'body' => json_encode( array(
		            		'status'    =>  false
		            	) )
		            ) );
				}
			}
		}
	}

	function unregisterCourse () {
		if ( $this->request->is( 'ajax' ) ) {
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
        if ( !empty( $request )) {
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

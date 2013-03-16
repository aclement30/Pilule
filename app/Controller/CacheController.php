<?php
App::import('Component', 'Auth');
App::import('Vendor', 'HttpFetcher' );
App::import('Vendor', 'Capsule' );
App::import('Vendor', 'domparser' );

class CacheController extends AppController {
	public $name = 'Cache';

	public $uses = array( 'CacheRequest', 'User' );

	public $Capsule;
	public $HttpFetcher;
	public $domparser;
	public $authResponse;

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function fetchData() {
		// Increase memory limit
		ini_set( 'memory_limit', '60M' );

        ob_start();

		$error = false;
		$auto = $this->request->data[ 'auto' ];				    // TODO : rename query to data
		$dataObject = $this->request->data[ 'name' ];			// TODO : rename query to data

        // Force data reload if request has been called by the user (by clicking Reload data button)
        if ( $auto == 0 ) {
            $this->Capsule->forceReload = true;
        }

		// Test connection to Capsule server
		$this->CapsuleAuth->testConnection();

		switch ( $dataObject ) {
            case 'studies-details':
			case 'studies':
            case 'studies-summary':
            	// Get data request from DB
            	$request = $this->CacheRequest->find( 'first', array(
            		'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name' => 'studies-summary' )
            	) );
            	if ( !empty( $request )) {
            		$md5Hash = $request[ 'CacheRequest' ][ 'md5' ];
            	} else {
            		$md5Hash = null;
            	}

				// Load studies programs
				$result = $this->Capsule->getStudies( $md5Hash, CURRENT_SEMESTER );

                if ($result === true) {
                    // Similar data have been found in DB (not reloaded)

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-summary', $result[ 'md5Hash' ] );
                } elseif ( !$result ) {
                    // Unknown error
                    $error = true;
                } elseif ( !$result[ 'status' ] ) {
                    // Delete user's program(s) saved in DB
                    $this->User->Program->deleteAll( array(
                    	'Program.idul'	=>	$this->Session->read( 'User.idul' )
                    ) );

                    // Si l'étudiant n'a aucune information à son dossier, enregistrement dans la BD
                    $this->User->id = $this->Session->read( 'User.idul' );
                    $this->User->saveField( 'empty_data', true );

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-summary' );
                } elseif ( $result[ 'status' ] ) {
                    // Delete user's program(s) saved in DB
                    $this->User->Program->deleteAll( array(
                    	'Program.idul'	=>	$this->Session->read( 'User.idul' )
                    ) );

                    // Save student info
                    $this->User->id = $this->Session->read( 'User.idul' );
                    $this->User->save( array( 'User' => $result[ 'userInfo' ] ) );

					// Save program studies data
					$this->User->Program->saveAll( $result[ 'programs' ] );

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-summary', $result[ 'md5Hash'] );
				}

				// Check if user has programs, if not skip the next part
                $userPrograms = $this->User->Program->find( 'all', array(
                	'conditions'	=>	array( 'Program.idul' => $this->Session->read( 'User.idul' ) )
                ) );

                if ( empty( $userPrograms ) ) break;

                // Get data request from DB
            	$requests = $this->CacheRequest->find( 'all', array(
            		'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name LIKE' => 'studies-details-program-%' )
            	) );
            	if ( !empty( $requests )) {
                    $md5Hash = array();
                    foreach ( $requests as $request ) {
                        $md5Hash[ $request[ 'CacheRequest' ][ 'name' ] ] = $request[ 'CacheRequest' ][ 'md5' ];
                    }
            	} else {
            		$md5Hash = array();
            	}

				// Load Rapport de cheminement
                $result = $this->Capsule->getStudiesDetails( $md5Hash, CURRENT_SEMESTER, $userPrograms );

                if ( $result[ 'status' ] ) {
                    foreach ( $result[ 'md5Hash' ] as $name => $hash ) {
                        // Update last data checkup timestamp
                        $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), $name, $hash );
                    }

                    // Save student info
                    if ( !empty( $result[ 'userInfo' ] ) ) {
                        $this->User->id = $this->Session->read( 'User.idul' );
                        $this->User->save( array( 'User' => $result[ 'userInfo' ] ) );
                    }

                    // Save programs studies data
                    $this->User->Program->saveAll( $result[ 'programs' ], array( 'deep' => true ) );

                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-details' );
                } else {
					// Enregistrement de l'erreur
					//$this->mErrors->addError('reload-data', 'studies-details : parsing error');
					
					$error = true;
				}

                $semester = $this->Session->read( 'Registration.semester' );
                if ( empty( $semester ) ) $semester = CURRENT_SEMESTER;

                // Get data request from DB
                $requests = $this->CacheRequest->find( 'all', array(
                    'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name LIKE' => 'studies-courses-' . $semester . '-program-%' )
                ) );
                if ( !empty( $requests )) {
                    $md5Hash = array();
                    foreach ( $requests as $request ) {
                        $md5Hash[ $request[ 'CacheRequest' ][ 'name' ] ] = $request[ 'CacheRequest' ][ 'md5' ];
                    }
                } else {
                    $md5Hash = array();
                }

                // Check if user has programs, if not skip the next part
                $userPrograms = $this->User->Program->find( 'all', array(
                    'conditions'    =>  array( 'Program.idul' => $this->Session->read( 'User.idul' ) ),
                    'contain'       =>  array( 'Section' => array( 'Course' ) )
                ) );

                if ( empty( $userPrograms ) ) break;

                // Load Rapport de cheminement détaillé
                $result = $this->Capsule->getStudiesCourses( $md5Hash, $semester, $userPrograms );

                if ( $result[ 'status' ] ) {
                    $this->loadModel( 'UniversityCourse' );

                    foreach ( $result[ 'programs' ] as &$program ) {
                        foreach ( $program[ 'Section' ] as &$section ) {
                            foreach ( $section[ 'Course' ] as $courseKey => &$sectionCourse ) {
                                // Find course in Pilule courses database
                                $course = $this->UniversityCourse->find( 'first', array(
                                    'conditions'    =>  array( 'UniversityCourse.code' => $sectionCourse[ 'code' ] )
                                ) );

                                if ( !empty( $course ) ) {
                                    if ( empty( $sectionCourse[ 'title' ] ) ) {
                                        $sectionCourse[ 'title' ] = $course[ 'UniversityCourse' ][ 'title' ];
                                        $sectionCourse[ 'credits' ] = $course[ 'UniversityCourse' ][ 'credits' ];
                                    }
                                } else {
                                    // Fetch course info from Capsule
                                    $course = $this->Capsule->fetchCourse( $sectionCourse[ 'code' ], $semester );

                                    if ( !empty( $course[ 'UniversityCourse' ][ 'title' ] ) ) {
                                        $sectionCourse[ 'title' ] = $course[ 'UniversityCourse' ][ 'title' ];
                                        if ( !empty( $course[ 'UniversityCourse' ][ 'credits' ] ) )
                                            $sectionCourse[ 'credits' ] = $course[ 'UniversityCourse' ][ 'credits' ];

                                        // Save fetched course info
                                        $this->UniversityCourse->create();
                                        $this->UniversityCourse->set( $course );
                                        $this->UniversityCourse->saveAll( $course );
                                    } else {
                                        // Course not found, remove it from student list
                                        unset( $section[ 'Course' ][ $courseKey ] );
                                    }
                                }
                            }
                        }
                    }
                    
                    // Save programs courses data
                    $this->User->Program->saveAll( $result[ 'programs' ], array( 'deep' => true ) );
                }
			break;
            case 'studies-courses':
                $semester = $this->Session->read( 'Registration.semester' );

                // Get data request from DB
                $requests = $this->CacheRequest->find( 'all', array(
                    'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name LIKE' => 'studies-courses-' . $semester . '-program-%' )
                ) );
                if ( !empty( $requests )) {
                    $md5Hash = array();
                    foreach ( $requests as $request ) {
                        $md5Hash[ $request[ 'CacheRequest' ][ 'name' ] ] = $request[ 'CacheRequest' ][ 'md5' ];
                    }
                } else {
                    $md5Hash = array();
                }

                // Check if user has programs, if not skip the next part
                $userPrograms = $this->User->Program->find( 'all', array(
                    'conditions'    =>  array( 'Program.idul' => $this->Session->read( 'User.idul' ) ),
                    'contain'       =>  array( 'Section' => array( 'Course' ) )
                ) );

                if ( empty( $userPrograms ) ) break;

                // Load Rapport de cheminement détaillé
                $result = $this->Capsule->getStudiesCourses( $md5Hash, $semester, $userPrograms );

                if ( $result[ 'status' ] ) {
                    foreach ( $result[ 'md5Hash' ] as $name => $hash ) {
                        // Update last data checkup timestamp
                        $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), $name, $hash );
                    }

                    $this->loadModel( 'UniversityCourse' );

                    foreach ( $result[ 'programs' ] as &$program ) {
                        foreach ( $program[ 'Section' ] as &$section ) {
                            foreach ( $section[ 'Course' ] as $courseKey => &$sectionCourse ) {
                                // Find course in Pilule courses database
                                $course = $this->UniversityCourse->find( 'first', array(
                                    'conditions'    =>  array( 'UniversityCourse.code' => $sectionCourse[ 'code' ] )
                                ) );

                                if ( !empty( $course ) ) {
                                    if ( empty( $sectionCourse[ 'title' ] ) ) {
                                        $sectionCourse[ 'title' ] = $course[ 'UniversityCourse' ][ 'title' ];
                                        $sectionCourse[ 'credits' ] = $course[ 'UniversityCourse' ][ 'credits' ];
                                    }

                                    // Check if course availability info need to be updated
                                    if ( $course[ 'UniversityCourse' ][ 'checkup_' . $semester ] < ( time() - 3600 * 24 * 7 ) ) {
                                        // Delete all existing classes for this course
                                        $this->UniversityCourse->Class->deleteAll( array( 'Class.course_id' => $course[ 'UniversityCourse' ][ 'id' ], 'Class.semester' => $semester ) );

                                        // Update course availability info
                                        $classes = $this->Capsule->fetchClasses( $course[ 'UniversityCourse' ][ 'code' ], $semester );

                                        if ( !empty( $classes[ 'Class' ] ) ) {
                                            // Save newly fetched classes for this course
                                            $course[ 'Class'] = $classes[ 'Class' ];
                                            $course[ 'UniversityCourse' ][ 'checkup_' . $semester ] = time();
                                            $course[ 'UniversityCourse' ][ 'av' . $semester ] = true;
                                            $this->UniversityCourse->set( $course );
                                            $this->UniversityCourse->saveAll( $course );
                                        } else {
                                            // Update course availability info
                                            $course[ 'UniversityCourse' ][ 'checkup_' . $semester ] = time();
                                            $course[ 'UniversityCourse' ][ 'av' . $semester ] = false;
                                            $this->UniversityCourse->set( $course );
                                            $this->UniversityCourse->saveAll( $course );
                                        }
                                    }
                                } else {
                                    // Fetch course info from Capsule
                                    $course = $this->Capsule->fetchCourse( $sectionCourse[ 'code' ], $semester );

                                    if ( !empty( $course[ 'UniversityCourse' ][ 'title' ] ) ) {
                                        $sectionCourse[ 'title' ] = $course[ 'UniversityCourse' ][ 'title' ];
                                        if ( !empty( $course[ 'UniversityCourse' ][ 'credits' ] ) )
                                            $sectionCourse[ 'credits' ] = $course[ 'UniversityCourse' ][ 'credits' ];

                                        // Save fetched course info
                                        $this->UniversityCourse->create();
                                        $this->UniversityCourse->set( $course );
                                        $this->UniversityCourse->saveAll( $course );
                                    } else {
                                        // Course not found, remove it from student list
                                        unset( $section[ 'Course' ][ $courseKey ] );
                                    }
                                }
                            }
                        }
                    }
                    
                    // Save programs courses data
                    $this->User->Program->saveAll( $result[ 'programs' ], array( 'deep' => true ) );
                    
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-courses-' . $semester );
                } else {
                    // Enregistrement de l'erreur
                    //$this->mErrors->addError('reload-data', 'studies-details : parsing error');
                    
                    $error = true;
                }
            break;
			case 'studies-report':
                // Get data request from DB
                $request = $this->CacheRequest->find( 'first', array(
                    'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name' => 'studies-report' )
                ) );
                if ( !empty( $request )) {
                    $md5Hash = $request[ 'CacheRequest' ][ 'md5' ];
                } else {
                    $md5Hash = null;
                }

				// Load report
                $result = $this->Capsule->getReport( $md5Hash );

                if ( $result === true ) {
                	// Similar data have been found in DB (not reloaded)

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-report', $result[ 'md5Hash' ] );
                } elseif ( !$result ) {
                    // Unknown error
                    $error = true;
                } elseif ( $result[ 'status' ] ) {
                    // Delete user's report saved in DB
                    $this->User->Report->deleteAll( array(
                        'Report.idul'  =>  $this->Session->read( 'User.idul' )
                    ) );

                    // Save student info
                    $this->User->id = $this->Session->read( 'User.idul' );
                    $this->User->save( array( 'User' => $result[ 'userInfo' ] ) );

                    // Save report data
                    $this->User->Report->saveAll( $result[ 'report' ], array( 'deep' => true ) );

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'studies-report', $result[ 'md5Hash'] );
                }
			    break;
			case 'schedule':
                // Get data request from DB
                $requests = $this->CacheRequest->find( 'all', array(
                    'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name LIKE' => 'schedule-%' )
                ) );
                if ( !empty( $requests )) {
                    $md5Hash = array();
                    foreach ( $requests as $request ) {
                        $md5Hash[ $request[ 'CacheRequest' ][ 'name' ] ] = $request[ 'CacheRequest' ][ 'md5' ];
                    }
                } else {
                    $md5Hash = array();
                }

				// Loading schedule
				$result = $this->Capsule->getSchedule( $md5Hash );
                
                // Similar data have been found in DB (not reloaded)
                if ( !$result ) {
                    // Unknown error
                    $error = true;
                } elseif ( $result[ 'status' ] ) {
                    // Delete user's schedule saved in DB
                    $this->User->ScheduleSemester->deleteAll( array(
                        'ScheduleSemester.idul'  =>  $this->Session->read( 'User.idul' )
                    ) );

                    // Save schedule data
                    $this->User->ScheduleSemester->saveAll( $result[ 'schedule' ], array( 'deep' => true ) );

                    // Update last data checkup timestamp
                    foreach ( $result[ 'md5Hash' ] as $name => $hash ) {
                        $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), $name, $hash );
                    }

                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'schedule' );
                }
			    break;
			case 'tuition-fees':
                // Get data request from DB
                $request = $this->CacheRequest->find( 'first', array(
                    'conditions' => array( 'CacheRequest.idul' => $this->Session->read( 'User.idul' ), 'CacheRequest.name' => 'tuition-fees' )
                ) );
                if ( !empty( $request )) {
                    $md5Hash = $request[ 'CacheRequest' ][ 'md5' ];
                } else {
                    $md5Hash = null;
                }

				// Loading student tuition fees
				$result = $this->Capsule->getTuitionFees( $md5Hash );

                if ( $result === true ) {
                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'tuition-fees', $result[ 'md5Hash' ] );
                } elseif ( !$result ) {
                    // Unknown error
                    $error = true;
                } elseif ( $result[ 'status' ] ) {
                    // Delete user's tuition fees saved in DB
                    $this->User->TuitionAccount->deleteAll( array(
                        'TuitionAccount.idul'  =>  $this->Session->read( 'User.idul' )
                    ) );

                    // Save tuition fees data
                    $this->User->TuitionAccount->saveAll( $result[ 'tuitions' ], array( 'deep' => true ) );

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'User.idul' ), 'tuition-fees', $result[ 'md5Hash'] );
                }
			    break;
            /*
			case 'data|holds':
				// Suppression des données en cache
				$this->mCache->deleteCache('data|holds', 1);
				
				// Vérification des blocages
				$this->lcapsule->checkHolds();
				
				if ($result===false) {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'check-holds : parsing error');
					
					$error = 1;
				}
			    break;
            */
            default:
                $error = true;
                break;
		}
		
        CakeLog::write( 'loading-data', ob_get_clean() );
        
        if ( $error ) {
    		return new CakeResponse( array(
            	'body' => json_encode( array(
            		'status'  =>  false
            	) )
            ) );
        } else {
            return new CakeResponse( array(
                'body' => json_encode( array(
                    'status'  =>  true
                ) )
            ) );
        }
	}
}

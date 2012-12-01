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

		$this->CapsuleAuth->allow( 'reloadData' );
	}

	public function reloadData() {
		// Increase memory limit
		ini_set( 'memory_limit', '50M' );

		$this->Session->write( 'idul', 'alcle8' );

		$error = false;
		$auto = $this->request->query[ 'auto' ];				// TODO : rename query to data
		$dataObject = $this->request->query[ 'name' ];			// TODO : rename query to data

        // Force data reload if request has been called by the user (by clicking Reload data button)
        if ( $auto == 0 ) {
            $this->lcapsule->forceReload = true;
        }

        $this->CapsuleAuth->login( 'alcle8', 'intelliweb30' );

		// Test connection to Capsule server
		$this->CapsuleAuth->testConnection();

		switch ( $dataObject ) {
            case 'studies-details':
			case 'studies':
            case 'studies-summary':
            	// Get data request from DB
            	$request = $this->CacheRequest->find( 'first', array(
            		'conditions' => array( 'idul' => $this->Session->read( 'idul' ), 'name' => 'studies-summary' )
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
                    $this->CacheRequest->saveRequest( $this->Session->read( 'idul' ), 'studies-summary', $md5Hash );
                } elseif ( !$result ) {
                    // Unknown error
                    $error = true;
                } elseif ( !$result[ 'status' ] ) {
                    // Delete user's program(s) saved in DB
                    $this->User->Program->deleteAll( array(
                    	'Program.idul'	=>	$this->Session->read( 'idul' )
                    ) );

                    // Si l'étudiant n'a aucune information à son dossier, enregistrement dans la BD
                    $this->User->id = $this->Session->read( 'idul' );
                    $this->User->saveField( 'empty_data', true );

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'idul' ), 'studies-summary' );
                } elseif ( $result[ 'status' ] ) {
                    // Delete user's program(s) saved in DB
                    $this->User->Program->deleteAll( array(
                    	'Program.idul'	=>	$this->Session->read( 'idul' )
                    ) );

                    // Save student info
                    $this->User->id = $this->Session->read( 'idul' );
                    $this->User->save( array( 'User' => $result[ 'userInfo' ] ) );

					// Save program studies data
					$this->User->Program->saveAll( $result[ 'programs' ] );

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'idul' ), 'studies-summary', $result[ 'md5Hash'] );
				}

				// Check if user has programs, if not skip the next part
                $userPrograms = $this->User->Program->find( 'all', array(
                	'conditions'	=>	array( 'User.idul' => $this->Session->read( 'idul' ) )
                ) );

                if ( empty( $userPrograms ) ) break;

                // Get data request from DB
            	$requests = $this->CacheRequest->find( 'all', array(
            		'conditions' => array( 'idul' => $this->Session->read( 'idul' ), 'name LIKE' => 'studies-details-program-%' )
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
                        $this->CacheRequest->saveRequest( $this->Session->read( 'idul' ), $name, $hash );
                    }

                    // Save student info
                    if ( !empty( $result[ 'userInfo' ] ) ) {
                        $this->User->id = $this->Session->read( 'idul' );
                        $this->User->save( array( 'User' => $result[ 'userInfo' ] ) );
                    }

                    // Save programs studies data
                    $this->User->Program->saveAll( $result[ 'programs' ], array( 'deep' => true ) );
                } else {
					// Enregistrement de l'erreur
					//$this->mErrors->addError('reload-data', 'studies-details : parsing error');
					
					$error = true;
				}
			break;
			case 'studies-report':
                // Get data request from DB
                $request = $this->CacheRequest->find( 'first', array(
                    'conditions' => array( 'idul' => $this->Session->read( 'idul' ), 'name' => 'studies-report' )
                ) );
                if ( !empty( $request )) {
                    $md5Hash = $request[ 'CacheRequest' ][ 'md5' ];
                } else {
                    $md5Hash = null;
                }

				// Load report
                $result = $this->capsule->getReport( $md5Hash );

                if ( $result === true ) {
                	// Similar data have been found in DB (not reloaded)

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( $this->Session->read( 'idul' ), 'studies-report', $md5Hash );
                } elseif (is_array($result)) {
                    // Save student info
                	if ( $result[ 'userInfo' ] !== true ) {
	                    $this->User->id = $this->Session->read( 'idul' );
	                    $this->User->save( array( 'User' => $userInfo ) );
	                }

                    // Suppression des données en cache de cours de l'étudiant
                    $this->mStudies->deleteReportSemesters();
                    $this->mStudies->deleteReportAdmittedSections();

                    // Enregistrement des données d'études
                    $this->mStudies->deleteReports();
                    $this->mStudies->deleteReportCourses();
                    $this->mStudies->addReport($result['report']);

                    foreach ($result['admitted_sections'] as $section) {
                        $courses = $section['courses'];
                        unset($section['courses']);

                        // Enregistrement de la section
                        $section_id = $this->mStudies->addReportAdmittedSection($section);
                        foreach ($courses as $course) {
                            $course['section_id'] = $section_id;
                            $this->mStudies->addReportCourse($course);
                        }
                    }

                    foreach ($result['semesters'] as $semester) {
                        $courses = $semester['courses'];
                        unset($semester['courses']);

                        // Enregistrement du semestre
                        $semester_id = $this->mStudies->addReportSemester($semester);
                        foreach ($courses as $course) {
                            $course['semester_id'] = $semester_id;
                            $this->mStudies->addReportCourse($course);
                        }
                    }

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( 'studies-report' );
				} else {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'studies-report : parsing error');
					
					$error = 1;
				}
			    break;
			case 'schedule':
				// Chargement des horaires de cours
				$result = $this->lcapsule->getSchedule();

                if ($result === true) {
                    // Les données similaires existent déjà dans la BDD

                   	// Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( 'schedule' );
                } elseif (is_array($result)) {
                    // Suppression des données en cache
                    $this->mSchedule->deleteSemesters();
                    $this->mSchedule->deleteCourses();
                    $this->mSchedule->deleteClasses();

                    foreach($result as $semester => $schedule) {
                        if ($schedule === true) continue;

                        $courses = $schedule['courses'];
                        unset($schedule['courses']);
                        $schedule['semester'] = $semester;

                        // Enregistrement du semestre
                        $this->mSchedule->addSemester($schedule);
                        foreach ($courses as $course) {
                            $classes = $course['classes'];
                            unset($course['classes']);

                            $course['semester'] = $semester;
                            $this->mSchedule->addCourse($course);

                            foreach($classes as $class) {
                                $class['semester'] = $semester;
                                $class['nrc'] = $course['nrc'];
                                $this->mSchedule->addClass($class);
                            }
                        }
                    }

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( 'schedule' );
                } else {
                    // Enregistrement de l'erreur
                    //$this->mErrors->addError('reload-data', 'schedule-semesters : parsing error');

                    $error = true;
                }
			    break;
			case 'fees':
				// Chargement des détails des frais de scolarité
				$result = $this->lcapsule->getFees();

                if ($result === true) {
                    // Les données similaires existent déjà dans la BDD

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('fees');
                } elseif (is_array($result)) {
                    // Suppression des données en cache
                    $this->mTuitions->deleteAccount();
                    $this->mTuitions->deleteSemesters();

                    // Enregistrement du compte de frais
                    $this->mTuitions->addAccount($result['account']);

                    foreach($result['semesters'] as $semester) {
                        $this->mTuitions->addSemester($semester);
                    }

                    // Update last data checkup timestamp
                    $this->CacheRequest->saveRequest( 'fees' );
                } elseif ($result===false) {
                    // Enregistrement de l'erreur
                    //$this->mErrors->addError('reload-data', 'fees : parsing error');

                    $error = true;
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
		}
		/*
		return new CakeResponse( array(
        	'body' => json_encode( array(
        		
        	) )
        ) );
        */
	}
}

<?php
class RegistrationController extends AppController {
	public $uses = array( 'User', 'UniversityCourse' );

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
		$this->set( 'title_for_layout', 'Choix de cours' );

		$registeredCourses = array();
		$selectedCourses = array();

		$schedule = $this->User->ScheduleSemester->find( 'first', array(
			'conditions'	=>	array( 'ScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'ScheduleSemester.semester' => $this->registrationSemester  ),
        	'contain'		=>	array( 'Course' )
        ) );
		
		if ( !empty( $schedule[ 'Course' ] ) )
			$registeredCourses = $schedule[ 'Course' ];

		$sections = $this->Section->User->find( 'first', array(
            'conditions'    =>  array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
            'contain'       =>  array( 'Section' => array( 'Course' => array( 'conditions' => array( 'Course.code !=' => 'EHE-1899' ) ) ) ),
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

    	$this->setAssets( array( '/js/registration.js' ) );
    	$this->render( 'limited' );
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
			$nrc = $this->request->query[ 'nrc' ];
			$semester = $this->registrationSemester;
			$replace = null;
			if ( !empty( $this->request->query[ 'replace' ] ) )
				$replace = $this->request->query[ 'replace' ];

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
					'Course.nrc'	=>	$nrc
				) ) ) != 0 ) {
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
				) ) ) != 0 ) {
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
				) ) ) != 0 ) {
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
				) ) ) != 0 ) {
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
			if ( !$this->User->SelectedCourse->save( $selectedCourse ) ) {
				// Error : unknown error
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    	=>  false,
	            		'errorCode'		=>	2
	            	) )
	            ) );
			}

			// Get student selected courses for registration semester
			$selectedCourses = $this->User->SelectedCourse->find( 'all', array(
				'conditions'	=>	array(
					'SelectedCourse.idul' 		=>	$this->Session->read( 'User.idul' ),
					'SelectedCourse.semester'	=>	$semester
				)
			) );
			
			$credits = 0;
			
			ob_start();
			
			foreach ($selected_courses as $course) { ?>
				<li>
					<a href="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" class="course"><span style="font-size: 8pt;"><?php if (strlen($course['title'])>35) echo substr($course['title'], 0, 30)."..."; else echo $course['title']; ?></span><br />
				<div class="title" style="font-weight: bold; margin-bottom: 0px; float: left;"><?php echo $course['code']; ?></div>
				<div style="float: right; margin-bottom: 0px; color: green;">NRC : <?php echo $course['nrc']; ?></div><div style="clear: both;"></div></a>
					<a href="javascript:registrationObj.removeSelectedCourse('<?php echo $course['nrc']; ?>');" class="delete-link" title="Enlever le cours"><img src="./images/cross-gray.png" width="16" height="16" /></a>
					<div style="clear: both;"></div>
				</li>
				<?php
					$credits += $course['credits'];
				}
			
			$content = str_replace("\n", "", str_replace("\r", "", ob_get_clean()));
			
			?>top.$.modal.close();top.$('#courses-selection').html('<?php echo addslashes($content); ?>');top.registrationObj.addSelectedCourseCallback(1, '<?php echo $nrc; ?>', '<?php echo count($selected_courses); ?>', '<?php echo $credits; ?>');<?php
		}
	}
}

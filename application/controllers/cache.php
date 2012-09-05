<?php

class Cache extends CI_Controller {
	var $mobile = 0;
	var $_source;

	function Cache () {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
		
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lfetch');

		// Chargement des modèles
		$this->load->model('mBots');
		$this->load->model('mCourses');
        $this->load->model('mSchedule');
		$this->load->model('mUser');
		$this->load->model('mUsers');

        if ((!$this->mUser->isAuthenticated())) {
            $_SESSION['login_redirect'] = $this->uri->uri_string();
            redirect('login');
        }
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function s_deleteCache() {
		$name = $this->input->post('name');
		
		if ($this->mCache->deleteCache($name)) {
			?>statusRefreshData(1);<?php
		} else {
			?>statusRefreshData(2);<?php
		}
	}
	
	function s_getLocalStorageVars () {
		$dataList = array(
						  'data|studies,summary',
						  'data|studies,details',
						  'data|studies,report',
						  'data|schedule',
						  'data|fees',
						  'data|holds'
						  );
		
		ob_start();
		
		?>localStorageVars = new Array(<?php
		$num = 0;
		
		foreach ($dataList as $item) {
			if ($num != 0) echo ', ';
			
			echo '\''.$item.'\'';
			
			$num++;
		}
		
		?>);storeLocalData();<?php
		
		$content = ob_get_clean();
		
		echo $content;
	}
	
	function s_getLocalStorageValue () {
		$var = urldecode($this->uri->segment(3));
		
		$data = '';
		switch ($var) {
			case 'data|studies,summary':
				$data = json_encode($this->mUser->getStudies());
			break;
			case 'data|studies,details':
				$data['sections'] = $this->mUser->getCoursesSections($_SESSION['cap_iduser']);
				$data['courses'] = $this->mUser->getCourses($_SESSION['cap_iduser']);
				
				$cache = $this->mCache->getCache('data|studies,details');
				
				if ($cache!=array()) {
					$cache['value'] = unserialize($cache['value']);
					$data['other_courses'] = $cache['value']['other_courses'];
				}
				
				$data = json_encode($data);
			break;
			case 'data|studies,report':
				$cache = $this->mCache->getCache('data|studies,report');
			
				if ($cache!=array()) {
					$data = unserialize($cache['value']);
				}
				
				$data = json_encode($data);
			break;
			case 'data|schedule':
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|schedule,semesters');
				
				if ($cache!=array()) {
					$data['semesters'] = unserialize($cache['value']);
				}
				
				$data['schedule'] = array();
				
				foreach ($data['semesters'] as $semester => $name) {
					if (isset($name['title'])) {
					} else {
						// Vérification de l'existence des sessions en cache
						$cache = $this->mCache->getCache('data|schedule['.$semester.']');
						
						if ($cache!=array()) {
							$data['schedule'][$semester] = unserialize($cache['value']);
						}
					}
				}
								
				$data = json_encode($data);
			break;
			case 'data|fees':
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|fees,semesters');
				
				if ($cache!=array()) {
					$data['semesters'] = unserialize($cache['value']);
				}
				
				$data['fees'] = array();
				
				foreach ($data['semesters'] as $semester => $name) {
					// Vérification de l'existence des sessions en cache
					$cache = $this->mCache->getCache('data|fees['.$semester.']');
					
					if ($cache!=array()) {
						$data['fees'][$semester] = unserialize($cache['value']);
					}
				}
								
				$data = json_encode($data);
			break;
			case 'data|holds':
				$cache = $this->mCache->getCache('data|holds');
		
				if ($cache!=array()) {
					$data = unserialize($cache['value']);
				}
				
				$data = json_encode($data);
			break;
		}
		
		error_log($data);
		
		?>writeLocalData('<?php echo $var; ?>', '<?php echo addslashes($data); ?>');<?php
	}
	
	// Chargement des données de l'utilisateur depuis Capsule
	function ajax_reloadData () {
        $admin_mode = $this->input->get('admin');
        if ($admin_mode == 1) {
            $reload_name = $this->input->get('name');
            $auto = 0;
        } else {
            $reload_name = $this->input->post('name');
            $auto = $this->input->post('auto');

            ob_start();
        }

		// Augmentation de la limitation de mémoire
		ini_set('memory_limit', '30M');

		// Vérification que l'utilisateur soit connecté
		if ($this->session->userdata('pilule_user') == '') {
			$this->mErrors->addError('reload-data', 'iduser not set');

            respond(array(
                'status'    =>  false,
                'error'     =>  'Erreur lors du chargement des données.'
            ));

            return (false);
		}

		$error = 0;
		$dataErrors = array();

		// Test de connexion à Capsule
		$this->lcapsule->testConnection();

		switch ($reload_name) {
			case 'studies':
                $this->session->set_userdata('saved_data', '');

				// Chargement du programme d'études
				$result = $this->lcapsule->getStudies(CURRENT_SEMESTER);

                if ($result === true) {
                    // Les données similaires existent déjà dans la BDD

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-summary');
                } elseif (!$result) {
                    $this->mErrors->addError('reload-data', 'studies : parsing error');

                    $error = 1;
                } elseif ($result == 'no-info') {
                    // Suppression des données en cache
                    $this->mStudies->deletePrograms();

                    // Si l'étudiant n'a aucune information à son dossier, enregistrement dans la BD
                    $this->mUser->editUser(array('empty_data'=>true));

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-summary');
                } else {
                    // Suppression des données en cache
                    $this->mStudies->deletePrograms();

                    // Enregistrement des données d'études
                    $this->mUser->editUser($result['studies']);

					// Enregistrement des données des programmes
                    foreach ($result['programs'] as $program) {
                        if (!empty($program)) {
                            $this->mStudies->addProgram($program);
                        }
                    }

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-summary');
				}
			case 'studies-details':
                $programs = $this->mStudies->getPrograms();
                if (empty($programs)) break;

                $this->session->set_userdata('saved_data', '');

				// Chargement du rapport de cheminement
                $result = $this->lcapsule->getStudiesDetails(CURRENT_SEMESTER, $programs);

                if ($result === true) {
                    // Les données similaires existent déjà dans la BDD

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-details');
                } elseif (is_array($result)) {
                    // Enregistrement des données d'études
                    if ($result['studies'] !== true) $this->mUser->editUser($result['studies']);

                    // Enregistrement des sections de cours
                    foreach ($result['programs'] as $program) {
                        if ($program === true) continue;

                        $section_number = 1;

                        // Suppression des données en cache de cours de l'étudiant
                        $this->mStudies->deleteProgramSections($program['id']);
                        foreach ($program['sections'] as $section) {
                            $courses = $section['courses'];
                            unset($section['courses']);
                            $section['number'] = $section_number;
                            $section['program_id'] = $program['id'];

                            // Enregistrement de la section
                            $section_id = $this->mStudies->addProgramSection($section);
                            foreach ($courses as $course) {
                                $course['program_id'] = $program['id'];
                                $course['section_id'] = $section_id;
                                $this->mStudies->addProgramCourse($course);
                            }

                            $section_number++;
                        }

                        unset($program['sections']);
                        unset($program['link']);

                        // Enregistrement des données d'études
                        $this->mStudies->editProgram($program);
                    }

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-details');
				} else {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'studies-details : parsing error');
					
					$error = 1;
				}
			break;
			case 'studies-report':
				// Chargement du relevé de notes
                $result = $this->lcapsule->getReport();

                if ($result === true) {
                    // Les données similaires existent déjà dans la BDD

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-report');
                } elseif (is_array($result)) {
                    // Enregistrement des données d'études
                    $this->mUser->editUser($result['student']);

                    // Suppression des données en cache de cours de l'étudiant
                    $this->mStudies->deleteReportSemesters();
                    $this->mStudies->deleteReportAdmittedSections();

                    // Enregistrement des données d'études
                    $this->mStudies->deleteReports();
                    $this->mStudies->addReport($result['report']);

                    foreach ($result['admitted_sections'] as $section) {
                        $courses = $section['courses'];
                        unset($section['courses']);
                        //$section['number'] = $section_number;
                        //$section['program_id'] = $program['id'];

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
                        //$section['number'] = $section_number;
                        //$section['program_id'] = $program['id'];

                        // Enregistrement du semestre
                        $semester_id = $this->mStudies->addReportSemester($semester);
                        foreach ($courses as $course) {
                            $course['semester_id'] = $semester_id;
                            $this->mStudies->addReportCourse($course);
                        }
                    }

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('studies-report');
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

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('schedule');
                } elseif (is_array($result)) {
                    // Suppression des données en cache
                    $this->mSchedule->deleteSemesters();

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

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('schedule');
                } else {
                    // Enregistrement de l'erreur
                    $this->mErrors->addError('reload-data', 'schedule-semesters : parsing error');

                    $error = 1;
                }
			    break;
			case 'data|fees,summary':
				// Suppression des données en cache
				$this->mUser->deleteFeesSummary();
				
				// Chargement des détails des frais de scolarité
				$result = $this->lcapsule->getFeesSummary();
				
				if ($result===false) {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'fees : parsing error');
					
					$error = 1;
				}
			    break;
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
		}
		
		if ($admin_mode != 1) ob_clean();

		$this->mHistory->save('reload-data : '.$reload_name);
		
		if ($error==0) {
			// Renvoi d'un résultat positif
            respond(array(
                'status'    =>  true,
                'auto'      =>  $auto
            ));
		} else {
            // Renvoi d'une erreur
            respond(array(
                'status'    =>  false,
                'auto'      =>  $auto
            ));
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
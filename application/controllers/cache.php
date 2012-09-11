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
		$this->load->model('mCourses');
        $this->load->model('mSchedule');
        $this->load->model('mTuitions');
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
		ini_set('memory_limit', '50M');

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

        // Forcer le rechargement des données si la requête a été initée par l'utilisateur
        if ($auto == 0) {
            $this->lcapsule->forceReload = true;
        }

		// Test de connexion à Capsule
		$this->lcapsule->testConnection();

		switch ($reload_name) {
            case 'studies-details':
			case 'studies':
            case 'studies-summary':
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
                        $this->mStudies->deleteProgramCourses($program['id']);

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
                                error_log(print_r($class, true));
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

                    // Actualisation de la date de la dernière actualisation des données
                    $this->mCache->addRequest('fees');
                } elseif ($result===false) {
                    // Enregistrement de l'erreur
                    $this->mErrors->addError('reload-data', 'fees : parsing error');

                    $error = 1;
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
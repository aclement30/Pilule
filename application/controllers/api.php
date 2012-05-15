<?php

class Api extends CI_Controller {
	var $format = 'html';
	var $user;
	var $params;
	
	function Api() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		$this->load->library('lfetch');
		$this->load->library('lcapsule');
		
		// Chargement des modèles
		$this->load->model('mCourses');
		$this->load->model('mRegistration');
		$this->load->model('mUser');
		
		// Vérification de la cléç
		$this->params = $this->uri->uri_to_assoc(3);
		if (isset($this->params['format'])) $this->format = $this->params['format'];

		if ($this->params['key'] != '4sf90c7810f5a6c2ff59dbbf24f82af89') {
			if ($this->format == 'json') {
				echo json_encode(array('error'=>'wrong-key'));
			} else {
				echo 'error: wrong key';
			}
			
			die();
		}
	}
	
	function getProgramCourses () {
		$program_code = strtolower($this->params['program']);
		
		$data = array();
		$data['section'] = 'api';
		$data['page'] = 'program-courses';
		$data['semester'] = '201209';
		$data['current_semester'] = '201201';
		$data['mobile'] = 0;
		
		$data['display_index'] = 0;
		if (isset($this->params['index']) and $this->params['index'] == 'yes') $data['display_index'] = 1;
		
		// Sélection des sections du programme
		$sections = $this->mRegistration->getProgramSections($program_code);
		
		if ($sections == array()) {
			if ($this->format == 'json') {
				echo json_encode(array('error'=>'unknown-program'));
			} else {
				echo 'error: unknown program ('.$program_code.')';
			}
			
			return (false);
		}
		
		$data['sections'] = $sections;
		$program_courses = $this->mCourses->getProgramCourses('B-LMO');
		
		foreach ($program_courses as $prog_course) {
			if (!isset($courses[$prog_course['id']])) {
				$course = $this->mCourses->getCourseInfo($prog_course['id']);
				
				$prog_course['title'] = $course['title'];
				$prog_course['description'] = $course['description'];
				$prog_course['credits'] = $course['credits'];
				$prog_course['av'.$data['semester']] = $course['av'.$data['semester']];
				$prog_course['code'] = $prog_course['id'];
				$prog_course['semester'] = '';
				
				$prog_course['level'] = 4;
				if ($prog_course['note']!='') {
					$prog_course['level'] = 1;
				} elseif ($prog_course['semester']==$data['current_semester']) {
					$prog_course['level'] = 2;
				} elseif ($prog_course['av'.$data['semester']]=='1') {
					$prog_course['level'] = 3;
				} elseif ($prog_course['av'.$data['semester']]=='0') {
					$prog_course['level'] = 4;
				}
				
				$courses[$prog_course['id']] = $prog_course;
			}
		}
		$data['courses'] = $courses;
		
		$data['program_courses'] = array();
		foreach ($program_courses as $course) {
			if (isset($data['program_courses'][$course['category']])) {
				$data['program_courses'][$course['category']][] = $course;
			} else {
				$data['program_courses'][$course['category']] = array();
				$data['program_courses'][$course['category']][] = $course;
			}
		}
		
		// Chargement de la page d'inscription
		$this->load->view('api/program-courses', $data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
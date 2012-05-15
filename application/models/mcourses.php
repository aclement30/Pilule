<?php

class mCourses extends CI_Model {
	var $debug = 0;
	
	function mCourses () {
		parent::__construct();
	}
	
	// Cours et classes disponibles dans Capsule
	
	// Ajout d'un cours disponible
	function addCourse ($course) {
		if ($this->db->insert('courses', $course)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche de l'information sur un cours et les classes liées
	function getCourseInfo ($code, $loadClasses = false, $semester = '') {
		$this->db->where(array('id'=>$code));
		$result = $this->db->get('courses');
		
		$course = $result->row_array();
		
		if ($course!=array()) {
			if ($loadClasses) {
				$course['classes'] = $this->getClasses($code, $semester);
			}
			
			return ($course);
		} else {
			return (array());
		}
	}
	
	// Ajout d'une classe à un cours
	function addClass ($class) {
		if ($this->db->insert('classes', $class)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche d'une classe
	function getClass ($nrc) {
		$this->db->where('nrc', $nrc);
		
		$result = $this->db->get('classes');
		
		$class = $result->row_array();
		
		return ($class);
	}
	
	// Recherche de classes
	function getClasses ($code, $semester = '') {
		if ($semester=='') {
			$this->db->where(array('idcourse'=>$code));
		} else {
			$this->db->where(array('idcourse'=>$code, 'semester'=>$semester));
		}
		$result = $this->db->get('classes');
		
		$classes = array();
		foreach ($result->result_array() as $class) {
			$class['timetable'] = unserialize($class['timetable']);
			$class['spots'] = $this->getClassSpots($class['nrc'], true);
			$classes[] = $class;
		}
		
		if ($classes!=array()) {
			return ($classes);
		} else {
			return (array());
		}
	}
	
	// Suppression des classes d'un cours
	function deleteClasses ($idcourse, $semester) {
		if ($this->db->delete('classes', array('idcourse'=>$idcourse, 'semester'=>$semester))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Liste des places disponibles pour un cours
	function getClassSpots ($nrc, $update = false) {
		$this->db->where(array('nrc'=>$nrc));
		if (isset($_SESSION['registration-semester'])) {
			$semester = $_SESSION['registration-semester'];
		} else {
			$semester = '201201';
		}
		
		$result = $this->db->get('classes_spots');
		
		$spots = $result->row_array();
		
		if ($spots != array()) {
			if ($update) {
				// Vérification de la date d'actualisation
				if ($spots['remaining'] <= 10) {
					// Actualisation des places disponibles
					$this->lcapsule->updateClassSpots($nrc, $semester);
					
					$this->db->where(array('nrc'=>$nrc));
					
					$result = $this->db->get('classes_spots');
					
					$spots = $result->row_array();
				} elseif ($spots['remaining'] < 50 && $spots['last_update'] <= time()-60*15) {
					// Actualisation des places disponibles
					$this->lcapsule->updateClassSpots($nrc, $semester);
					
					//error_log(date('d-m-Y, H:i:s', $spots['last_update'])." -- ".date('d-m-Y, H:i:s', time()-60*15));
					
					$this->db->where(array('nrc'=>$nrc));
					
					$result = $this->db->get('classes_spots');
					
					$spots = $result->row_array();
				} elseif ($spots['remaining'] < 100 && $spots['last_update'] <= time()-3600) {
					// Actualisation des places disponibles
					$this->lcapsule->updateClassSpots($nrc, $semester);
					
					$this->db->where(array('nrc'=>$nrc));
					
					$result = $this->db->get('classes_spots');
					
					$spots = $result->row_array();
				} elseif ($spots['last_update'] <= time()-3600*24) {
					// Actualisation des places disponibles
					$this->lcapsule->updateClassSpots($nrc, $semester);
					
					$this->db->where(array('nrc'=>$nrc));
					
					$result = $this->db->get('classes_spots');
					
					$spots = $result->row_array();
				}
			}
			
			return ($spots);
		} else {
			return (false);
		}
	}
	
	// Modification des places disponibles pour un cours
	function updateClassSpots ($spots) {
		if (!$this->getClassSpots($spots['nrc'])) {
			$spots['last_update'] = time();
			if ($this->db->insert('classes_spots', $spots)) {
				return (true);
			} else {
				return (false);
			}
		} else {
			$this->db->where(array('nrc'=>$spots['nrc']));
			unset($spots['nrc']);
			$spots['last_update'] = time();
			
			if ($this->db->update('classes_spots', $spots)) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	function updateCoursesData ($semester) {
		// Sélection des données
		$result = $this->db->get('programs_courses');
		
		$courses = $result->result_array();
		
		if ($courses!=array()) {
			foreach ($courses as $course) {
				$this->db->where(array('id'=>$course['id']));
				$result = $this->db->get('courses');
				
				$course2 = $result->row_array();
				//if ($course2 == array()) {					
					$this->lcapsule->fetchCourse($course['id'], $semester);
				//}
			}
		}
	}
	
	// Cours de programmes
	
	// Ajout d'un cours à un programme
	function addProgramCourses ($courses) {
		foreach ($courses as $course) {
			if (!$this->db->insert('programs_courses', $course)) {
				return (false);
			}
		}
		
		return (true);
	}
	
	// Recherche des cours d'un programme
	function getProgramCourses ($program) {
		// Sélection des données
		$this->db->where(array('program'=>$program));
		$this->db->order_by('id asc, optional asc'); 
		$result = $this->db->get('programs_courses');
		
		$courses_list = $result->result_array();
		$courses = array();
		
		foreach ($courses_list as $course) {
			$this->db->where(array('id'=>$course['id']));
			$result = $this->db->get('courses');
			
			$course2 = $result->row_array();
			if (isset($course2['title'])) {
				$course['title'] = $course2['title'];
				$course['description'] = $course2['description'];
				$course['credits'] = $course2['credits'];
				$course['available'] = $course2['available'];
				$course['code'] = $course['id'];
				$course['note'] = '';
				
				$courses[] = $course;
			}
		}
		
		if ($courses!=array()) {
			return ($courses);
		} else {
			return (array());
		}
	}
	
	// Cours des utilisateurs
	
	// Recherche des cours des utilisateurs
	function getUsersCourses ($params) {
		if (is_array($params)) {
			$this->db->where($params);
		} else {
			$idcourse = $params;
			$this->db->where('id', $idcourse);
		}
		
		$result = $this->db->get('users_courses');
		
		$courses = $result->result_array();
		
		if ($courses!=array()) {
			if (isset($idcourse)) {
				return ($courses[0]);
			} else {
				return ($courses);
			}
		} else {
			return (array());
		}
	}
}
?>
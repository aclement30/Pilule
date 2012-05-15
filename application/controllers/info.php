<?php
class Info extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Info() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des modèles
		$this->load->model('mCourses');
		$this->load->model('mRegistration');
		$this->load->model('mUser');
		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) {
			$this->user = $this->mUser->info();
			$this->user['password'] = $_SESSION['cap_password'];
		}
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function course () {
		$data = array();
		$data['section'] = 'registration';
		$data['page'] = 'course';
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		$semester = '201201';
		
		$code = str_replace(" ", "-", trim(strtoupper($this->uri->segment(3))));
		if (strlen($code)==7) {
			$code = substr($code, 0, 3)."-".substr($code, 3, 4);
		}
		
		// Recherche du cours dans la base de données
		$course = $this->mCourses->getCourseInfo($code, true, $semester);
		
		if ($course != array()) {
			if (isset($_SESSION['cap_iduser'])) {
				// Redirection à la page du cours
				redirect('./registration/course/'.$course['id']);
			} else {
				$data['course'] = $course;
				
				// Chargement de l'entête
				if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
				
				// Chargement de la page demandée
				$this->load->view('registration/course-info', $data);
				
				// Chargement du bas de page
				if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
			}
		} else {
		
		}
	}
}
<?php

class Studies extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Studies() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Augmentation de la limitation de mémoire
		ini_set('memory_limit', '50M');
		
		// Chargement des modèles
		$this->load->model('mCourses');
		$this->load->model('mUser');
		
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') {
			$_SESSION['login_redirect'] = $this->uri->uri_string();
			redirect('login');
		}
		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) {
			$this->user = $this->mUser->info();
			$this->user['password'] = $_SESSION['cap_password'];
		}
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function index () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('studies-summary');
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		// Vérification de l'existence de la page en cache
		$cache = $this->mCache->getCache('data|studies,summary');
		
		if ($cache!=array()) {
			$data['cache_date'] = $cache['date'];
			$data['cache_time'] = $cache['time'];
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|studies,summary';
			}
		}

		// Chargement de la page
		if ($data['studies']!=array()) {
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('studies/home', $data, true)));
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Programme d\'études';
			$data['reload_name'] = 'data|studies,summary';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('studies/home');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('studies/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
		if ($this->mobile == 1) echo "$('h2.title').after($('#sidebar'));";
	}
	
	function details () {
		$data = array();
		$data['user'] = $this->user;
		$data['type'] = $this->uri->segment(3);
		if ($data['type']=='') $data['type'] = '1';
		
		$data['mobile'] = $this->mobile;
		if ($this->mobile==1) {
			$data['type'] = 2;
		}
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		if ($data['type'] == '1') {
			$this->mHistory->save('studies-details-attestation');
		} else {
			$this->mHistory->save('studies-details-education');
		}
		
		// Chargement de l'entête
		$data['tabs'] = array(
					  '0'	=>	array(
								  'url'		=>	'./studies/details/1',
								  'title'	=>	'Attestation',
								  'current'	=>	0
								  ),
					  '1'	=>	array(
								  'url'		=>	'./studies/details/2',
								  'title'	=>	'Formation',
								  'current'	=>	0
								  )
					  );
		
		$data['tabs'][$data['type']-1]['current'] = 1;
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		if ($data['studies']!=array()) {
			// Vérification de l'existence de la page en cache
			if ($data['type']=='2') {
				$sections = $this->mUser->getCoursesSections($_SESSION['cap_iduser']);
				$courses = $this->mUser->getCourses($_SESSION['cap_iduser']);
				
				foreach ($sections as $section) {
					$section['courses'] = array();
					
					foreach ($courses as $course) {
						if ($section['title']==$course['section']) {
							$course_info = $this->mCourses->getCourseInfo($course['idcourse']);
							if ($course_info!=array()) {
								$course['title'] = $course_info['title'];
								$course['credits'] = $course_info['credits'];
							}
							
							$section['courses'][] = $course;
						}
					}
					
					$data['sections'][] = $section;
				}
				
				$cache = $this->mCache->getCache('data|studies,details,2');
					
				if ($cache!=array()) {
					$data['cache_date'] = $cache['date'];
					$data['cache_time'] = $cache['time'];
					// Vérification de la date de chargement des données
					if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
						$data['reload_data'] = 'data|studies,details';
					}
				}
				
				$cache = $this->mCache->getCache('data|studies,details');
					
				if ($cache!=array()) {
					$cache['value'] = unserialize($cache['value']);
					$data['details']['other_courses'] = $cache['value']['other_courses'];
				}
			
				if ($this->mobile==1) {
					$data['details']['other'] = $cache['value']['other_courses'];
				}
			} else {
				$cache = $this->mCache->getCache('data|studies,details');
			
				if ($cache!=array()) {
					$data['cache_date'] = $cache['date'];
					$data['cache_time'] = $cache['time'];
					// Vérification de la date de chargement des données
					if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
						$data['reload_data'] = 'data|studies,details';
					}
				}
			}
			
			// Chargement de la page
			if (($data['type'] == '2' and (isset($data['cache_date']))) or ($data['type'] == '1' and $data['studies'] != array())) {
				$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('studies/details', $data, true)));
			} elseif ($data['type'] == '2') {
				// Chargement de la page d'erreur
				$data['title'] = 'Rapport de cheminement';
				$data['reload_name'] = 'data|studies,details';
				
				$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
			}
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Rapport de cheminement';
			$data['reload_name'] = 'data|studies,details';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('studies/details');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
	
	function report () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('studies-report');
		
		// Sélection des données des études
		$data['studies'] = $this->mUser->getStudies();
		
		// Vérification de l'existence de la page en cache
		$cache = $this->mCache->getCache('data|studies,report');
		
		if ($cache!=array()) {
			$data['report'] = unserialize($cache['value']);
			$data['cache_date'] = $cache['date'];
			$data['cache_time'] = $cache['time'];
			
			if ($data['report']!=array()) {
				// Vérification de la date de chargement des données
				if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
					$data['reload_data'] = 'data|studies,report';
				}
				
				// Chargement de la page
				$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('studies/report', $data, true)));
			} else {
				// Chargement de la page d'erreur
				$data['title'] = 'Relevé de notes';
				$data['reload_name'] = 'data|studies,report';
				
				$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
			}
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Relevé de notes';
			$data['reload_name'] = 'data|studies,report';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('studies/report');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
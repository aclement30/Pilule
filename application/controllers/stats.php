<?php

class Stats extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Stats() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		// Vérification de la connexion
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') redirect('login');
		
		// Vérification que l'utilisateur soit administrateur
		//if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser']!='alcle8') redirect('welcome');
 		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) $this->user = $this->mUser->getUser($_SESSION['cap_iduser']);
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function users() {
		ini_set('memory_limit','100M');
		
		$data = array();
		$data['section'] = 'admin';
		$data['page'] = 'users';
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
				
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		$items = $this->mUsers->getStatsUsers();
		$data['faculties'] = array();
		$data['programs'] = array();
		$data['total'] = 0;
		$programs = array();
		$faculties = array(
								   'Amén. architect. arts visuels'		=>	'FAAAV',
								   'DGFC-Formation continue'			=>	'Autres',
								   'DGPC-Programmes premier cycle'		=>	'Autres',
								   'Direction de l\'Université'			=>	'Autres',
								   'Droit'								=>	'Droit',
								   'FESP-Études sup. et postdoct.'		=>	'FESP',
								   'Foresterie, géographie géomat.'		=>	'FFGG',
								   'IQHEI-Hautes études internat.'		=>	'HEI',
								   'Lettres'							=>	'Lettres',
								   'Médecine'							=>	'Santé',
								   'Médecine dentaire'					=>	'Santé',
								   'Musique'							=>	'Mus.',
								   'Pharmacie'							=>	'Santé',
								   'Philosophie'						=>	'Philo.',
								   'Sc. agriculture, alimentation'		=>	'FSAA',
								   'Sciences de l\'administration'		=>	'FSA',
								   'Sciences de l\'éducation'			=>	'FSE',
								   'Sciences et génie'					=>	'FSG',
								   'Sciences infirmières'				=>	'Santé',
								   'Sciences sociales'					=>	'FSS',
								   'Théologie et sc.  religieuses'		=>	'FTSR'
								   );
		
		
		foreach ($items as $user) {
			if ($user['faculty'] != 'Aucune faculté désignée') {
				if (!isset($data['faculties'][$faculties[$user['faculty']]])) {
					$data['faculties'][$faculties[$user['faculty']]] = 0;
					$programs[$faculties[$user['faculty']]] = array();
				}
				
				$data['faculties'][$faculties[$user['faculty']]]++;
				
				if (!isset($programs[$faculties[$user['faculty']]][$user['program']])) $programs[$faculties[$user['faculty']]][$user['program']] = 0;
				
				$programs[$faculties[$user['faculty']]][$user['program']]++;
				$data['total']++;
			}
		}
		
		foreach ($programs as $faculty => $programs) {
			$n = 0;
			foreach ($programs as $program => $number) {
				$data['programs'][$faculty][$program] = round($number/$data['total']*100, 2);
				$n++;
				//if ($n == 3) break;
			}
		}
				
		// Chargement de la page
		$this->load->view('admin/stats/users', $data);
		
		// Chargement du menu
		$this->load->view('stats/m-menu', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function index() {
		ini_set('memory_limit','100M');
		
		$data = array();
		$data['section'] = 'admin';
		$data['page'] = 'visits';
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
				
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		$items = $this->mHistory->getLogins(15);
		$data['logins']['days'] = array();
		foreach ($items[0] as $login) {
			if (!isset($data['logins']['days'][$login['date']])) $data['logins']['days'][$login['date']] = 0;
			
			$data['logins']['days'][$login['date']]++;
		}
		$data['loadings']['days'] = array(15);
		foreach ($items[1] as $loading) {
			if (!isset($data['loadings']['days'][$loading['date']])) $data['loadings']['days'][$loading['date']] = 0;
			
			$data['loadings']['days'][$loading['date']]++;
		}
		$items = $this->mHistory->getLogins(90);
		$data['visits'] = array();
		$hours = array();

		foreach ($items[0] as $login) {
			if (!isset($hours[date('H', $login['timestamp'])])) $hours[date('H', $login['timestamp'])] = 0;
			
			$hours[date('H', $login['timestamp'])]++;
		}
		
		foreach ($hours as $hour => $number) {
			$data['visits']['hours'][$hour] = round($number/count($items[0])*100, 2);
		}
		
		$items = $this->mHistory->getPages(90);
		$data['pages'] = array();
		$pages = array();
		$pages['redirect-capsule'] = 0;
		$pages['redirect-elluminate'] = 0;
		
		$data['sections'] = array();
		$data['sections']['others'] = 0;
		$data['total'] = 0;
		
		foreach ($items as $page) {
			if ($page['description'] != 'login' && $page['description'] != 'loading-data' && $page['description'] != 'security-check' && $page['description'] != 'logout') {
				
				if ($page['description'] == 'registration-courses' || $page['description'] == 'phishing-email') {
					$data['sections']['others']++;
				} else {
					if (!isset($data['sections'][substr($page['description'], 0, strpos($page['description'], "-"))])) $data['sections'][substr($page['description'], 0, strpos($page['description'], "-"))] = 0;
					
					$data['sections'][substr($page['description'], 0, strpos($page['description'], "-"))]++;
				}
				
				if (!isset($pages[$page['description']])) $pages[$page['description']] = 0;
				
				$pages[$page['description']]++;
				$data['total']++;
			}
		}
		
		foreach ($pages as $page => $number) {
			$data['pages'][$page] = round($number/$data['total']*100, 2);
		}
		
		// Chargement de la page
		$this->load->view('admin/stats/visits', $data);
		
		// Chargement du menu
		$this->load->view('stats/m-menu', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function registration() {
		ini_set('memory_limit','100M');
		
		$data = array();
		$data['section'] = 'admin';
		$data['page'] = 'stats/registration';
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
				
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		$stats = $this->mHistory->getRegistrationStats(60);
		
		// Analyse des données de l'étape 1
		$step1_users = array();
		$step1_programs = array();
		foreach ($stats[0] as $user2) {
			$info = $this->mUser->info($user2['idul']);
			if (!array_key_exists($info['program'], $step1_programs)) {
				$step1_programs[$info['program']] = array();
				$step1_programs[$info['program']]['registration'] = 0;
				$step1_programs[$info['program']]['users'] = 0;
				$step1_programs[$info['program']]['result'] = 0;
			}
			if (!isset($step1_programs[$info['program']]['registration'])) $step1_programs[$info['program']]['registration'] = 0;
			
			if (!array_key_exists($user2['idul'], $step1_users)) {
				$step1_users[$user2['idul']] = array();
				$step1_programs[$info['program']]['registration']++;
			}
			if (!isset($step1_users[$user2['idul']]['time'])) $step1_users[$user2['idul']]['time'] = 0;
			$step1_users[$user2['idul']]['time']++;
		}
		$items = $this->mUsers->getStatsUsers();
		foreach ($items as $user2) {
			if (array_key_exists($user2['program'], $step1_programs)) {
				$step1_programs[$user2['program']]['users']++;
			}
		}
		$data['step1_users'] = $step1_users;

		// Analyse des données de l'étape 2
		$step2_users_register = array();
		foreach ($stats[1] as $user2) {
			if (!array_key_exists($user2['idul'], $step2_users_register)) $step2_users_register[$user2['idul']] = 0; 
			$step2_users_register[$user2['idul']]++;
		}
		$data['step2_users_register'] = $step2_users_register;
		
		$step2_users_remove = array();
		foreach ($stats[2] as $user2) {
			if (!array_key_exists($user2['idul'], $step2_users_remove)) $step2_users_remove[$user2['idul']] = 0; 
			$step2_users_remove[$user2['idul']]++;
		}
		$data['step2_users_remove'] = $step2_users_remove;
		
		// Analyse des données de l'étape 3
		$step3_users = array();
		foreach ($stats[3] as $user2) {
			$info = $this->mUser->info($user2['idul']);
			
			if (!array_key_exists($user2['idul'], $step3_users)) {
				$step3_users[$user2['idul']] = array();
				$step1_programs[$info['program']]['result']++;
			}
			if (!isset($step3_users[$user2['idul']]['time'])) $step3_users[$user2['idul']]['time'] = 0;
			$step3_users[$user2['idul']]['time']++;
		}
		$data['step3_users'] = $step3_users;
		$data['step1_programs'] = $step1_programs;
		arsort($data['step1_programs']);
		
		// Chargement de la page
		$this->load->view('admin/stats/registration', $data);
		
		// Chargement du menu
		$this->load->view('stats/m-menu', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
	
	function errors() {
		ini_set('memory_limit','100M');
		
		$data = array();
		$data['section'] = 'admin';
		$data['page'] = 'errors';
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
				
		// Chargement de l'entête
		if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		$items = $this->mErrors->getErrors(60);
		$errors = array();
		$data['total'] = 0;
		
		foreach ($items as $error) {
			if ($error['description'] != 'security-check : fail') {
				if (!isset($errors[$error['description']])) $errors[$error['description']] = 0;
				$errors[$error['description']]++;
				
				$data['total']++;
			}
		}
		
		foreach ($errors as $error => $number) {
			$data['errors'][$error] = round($number/$data['total']*100, 2);
		}
		
		arsort($data['errors']);
		
		$items_errors = $this->mErrors->getErrors(15);
		$items_logins = $this->mHistory->getLogins(15);
		$data['logins']['days'] = array();
		foreach ($items_logins[0] as $login) {
			if (!isset($data['logins']['days'][$login['date']])) $data['logins']['days'][$login['date']] = 0;
			
			$data['logins']['days'][$login['date']]++;
		}
		$data['daily_errors']['days'] = array();
		foreach ($items_errors as $error) {
			if (!isset($data['daily_errors']['days'][$error['date']])) $data['daily_errors']['days'][$error['date']] = 0;
			
			$data['daily_errors']['days'][$error['date']]++;
		}
		
		// Chargement de la page
		$this->load->view('admin/stats/errors', $data);
		
		// Chargement du menu
		$this->load->view('stats/m-menu', $data);
		
		// Chargement du bas de page
		if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
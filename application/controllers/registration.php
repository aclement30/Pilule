<?php

class Registration extends CI_Controller {
	var $mobile = 0;
	var $usebots = 0;
	var $user;
	var $registrationSemester = '201201';
	var $currentSemester = '201201';
	var $deadlines = array(
						   '201201'	=> array(
											'registration_start'=>	'20111107',
											'edit_selection'	=>	'20120106',
											'drop_nofee'		=>	'20120123',
											'drop_fee'			=>	'20120319'
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
	var $registrationSemesters = array('201201'=>'Hiver 2012', '201205'=>'Été 2012', '201209'=>'Automne 2012');
	//var $registrationSemesters = array('201201'=>'Hiver 2012');
	
	function Registration() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lfetch');
		if ($this->usebots==1) {
			$this->load->library('xmlrpc');
			
			$this->xmlrpc->set_debug(true);
			
			$_SESSION['usebots'] = 1;
		} else {
			$_SESSION['usebots'] = 0;
		}
		
		// Chargement des modèles
		$this->load->model('mBots');
		$this->load->model('mCourses');
		$this->load->model('mRegistration');
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
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
		if ($this->lmobile->isMobile()==1) {
			// Renvoi d'une erreur, section non disponible sur mobile
		}
		
		if (!isset($_SESSION['registration-semester'])) {
			$_SESSION['registration-semester'] = $this->registrationSemester;
		} else {
			$this->registrationSemester = $_SESSION['registration-semester'];
		}
		
		if (date('m')<5) {
			// Session d'hiver
			$this->currentSemester = date('Y').'01';
		} elseif (date('m')<9) {
			// Session d'été
			$this->currentSemester = date('Y').'05';
		} else {
			// Session d'automne
			$this->currentSemester = date('Y').'09';
		}
	}
	
	function configure () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		$program = $this->mRegistration->getProgramByName($data['user']['program']);
		
		$data['sections'] = $this->mRegistration->getProgramSections($program['code']);
				
		$data['program'] = $program['title'];
		$data['program_code'] = $program['code'];
		
		$data['user_sections'] = explode(",", $this->mUser->getParam('registration-'.$data['program_code'].'-sections'));
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('registration/configure', $data, true)));
		
		echo "setPageInfo('registration/configure');setPageContent(\"".addslashes($content)."\");";
	}
	
	function getMenu() {
		$data = array();
		$data['mobile'] = $this->mobile;
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$this->registrationSemester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		
		$data['registered_courses'] = $courses2;
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$this->registrationSemester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['selected_courses'] = $courses2;
		
		$data['deadlines'] = $this->deadlines;
		$data['semester'] = $this->registrationSemester;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('registration/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
		?>$('a.delete-link').mouseover(function(){$(this).children(":first").attr('src', './images/cross.png');});
$('a.delete-link').mouseout(function(){$(this).children(":first").attr('src', './images/cross-gray.png');});<?php
		?>registrationObj.selectionTotal=<?php echo count($data['selected_courses']); ?>;<?php
		?>registrationObj.semester='<?php echo $this->registrationSemester; ?>';<?php
		?>registrationObj.currentSemester='<?php echo $this->currentSemester; ?>';<?php
		?>registrationObj.currentDate='<?php echo date('Ymd'); ?>';<?php
		?>registrationObj.deadline_drop_fee='<?php echo $this->deadlines[$this->registrationSemester]['drop_fee']; ?>';<?php
		?>registrationObj.deadline_drop_nofee='<?php echo $this->deadlines[$this->registrationSemester]['drop_nofee']; ?>';<?php
		?>registrationObj.deadline_edit_selection='<?php echo $this->deadlines[$this->registrationSemester]['edit_selection']; ?>';<?php
		$params = $this->uri->uri_to_assoc(3);
		if (isset($params['newSemester']) and $params['newSemester'] != '') {
			echo 'stopLoading();';
		}
	}
	
	function s_configure () {
		$program_code = $this->input->post('program_code');
		$sections = $this->mRegistration->getProgramSections($program_code);
		
		$users_sections = array();
		
		foreach ($sections as $section) {
			if ($section['code'] != 'p-inter') {
				if ($this->input->post('section_'.str_replace("-", "_", $section['code'])) != '' || $section['compulsory'] == '1') {
					if ($this->input->post('section_'.str_replace("-", "_", $section['code'])) == 'yes') {
						$users_sections[] = $section['id'];
					} else {
						$users_sections[] = $section['id'];
						if ($this->input->post('section_'.str_replace("-", "_", $section['code'])) != '') $users_sections[] = $this->input->post('section_'.str_replace("-", "_", $section['code']));
					}
				}
			}
		}
		
		if ($this->mUser->setParam('registration-'.$program_code.'-sections', implode(",", $users_sections))) {
			?><script language="javascript">top.registrationObj.configureCallback(1);</script><?php
		} else {
			?><script language="javascript">top.registrationObj.configureCallback(2);</script><?php
		}
	}
	
	function index () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		$params = $this->uri->uri_to_assoc(3);
		if (isset($params['semester']) and $params['semester'] != '') {
			$newSemester = $params['semester'];
			$_SESSION['registration-semester'] = $newSemester;
			$this->registrationSemester = $newSemester;
		}
		
		// Création des variables d'inscription
		$data['current_semester'] = $this->currentSemester;
		$data['semester'] = $this->registrationSemester;
		$data['deadlines'] = $this->deadlines;
		$data['semesters'] = $this->registrationSemesters;
		
		$program = $this->mRegistration->getProgramByName($data['user']['program']);
		
		// Sélection des sections du programme
		$sections = $this->mRegistration->getProgramSections($program['code']);
		
		// Sélection des sections de programme choisies par l'utilisateur
		$data['user_sections'] = $this->mUser->getParam('registration-'.$program['code'].'-sections');
		
		if ((!$data['user_sections']) or $data['user_sections'] == array()) {
			// Redirection à la page de configuration de l'inscription
			?>document.location.hash="#!/registration/configure";<?php
		} else {
			$data['user_sections'] = explode(",", $data['user_sections']);
		}
		
		// Inclusion du plugin du programme
		switch ($program['code']) {
			case 'B-LMO':
				include ('./programs/'.'b-lmo'.'.php');
			break;
		}
		
		$data['sections'] = $sections;
		$program_courses = $this->mCourses->getProgramCourses($program['code']);
		
		// Recherche des informations des relevés de notes
		$cache = $this->mCache->getCache('data|studies,report');
		$courses2 = array();
		if ($cache!=array()) {
			$report = unserialize($cache['value']);
			
			foreach ($report['semesters'] as $semester) {
				$semester['title'] = explode(" ", $semester['title']);
				switch ($semester['title'][0]) {
					case 'Automne';
						$semester_name = 'A-'.substr($semester['title'][1], 2, 2);
					break;
					case 'Hiver';
						$semester_name = 'H-'.substr($semester['title'][1], 2, 2);
					break;
					case 'Été';
						$semester_name = 'E-'.substr($semester['title'][1], 2, 2);
					break;
					default:
						$semester_name = $semester['title'][0];
						if (isset($semester_name['title'][1])) $semester_name .= " ".$semester['title'][1];
					break;
				}
				
				foreach ($semester['courses'] as $course) {
					$course['semester'] = $semester_name;
					$courses2[$course['code']] = $course;
				}
			}
		}
		$report = $courses2;
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$data['semester'].']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['code']] = $course;
			}
		}
		$data['registered_courses'] = $courses2;
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$data['semester'].']');
		$courses2 = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['selected_courses'] = $courses2;
				
		// Recherche des informations de l'horaire
		$cache = $this->mCache->getCache('data|schedule['.$this->currentSemester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['code']] = $course;
			}
		}
		$schedule = $courses2;
		$courses = array();
		
		foreach ($program_courses as $prog_course) {
			if (!isset($courses[$prog_course['id']])) {
				$course = $this->mCourses->getCourseInfo($prog_course['id']);
				
				$prog_course['title'] = $course['title'];
				$prog_course['description'] = $course['description'];
				$prog_course['credits'] = $course['credits'];
				$prog_course['av'.$data['semester']] = $course['av'.$data['semester']];
				$prog_course['code'] = $prog_course['id'];
				$prog_course['semester'] = '';
				if (isset($report[$prog_course['id']])) {
					$prog_course['note'] = $report[$prog_course['id']]['note'];
					$prog_course['semester'] = $report[$prog_course['id']]['semester'];
				}
				if (isset($schedule[$prog_course['id']])) {
					$semester2 = explode(" ", $this->currentSemester);
					switch ($semester2[0]) {
						case 'Automne';
							$prog_course['semester'] = 'A-'.substr($semester2[1], 2, 2);
						break;
						case 'Hiver';
							$prog_course['semester'] = 'H-'.substr($semester2[1], 2, 2);
						break;
						case 'Été';
							$prog_course['semester'] = 'E-'.substr($semester2[1], 2, 2);
						break;
					}
					$prog_course['semester'] = $this->currentSemester;
				}
				
				$prog_course['level'] = 4;
				if ($prog_course['note']!='') {
					$prog_course['level'] = 1;
				} elseif ($prog_course['semester']==$this->currentSemester) {
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
		
		// Sélection des infos d'étude de l'utilisateur
		$data['studies'] = $this->mUser->getStudies();
		
		$this->mHistory->save('registration-courses');
		
		// Chargement de la page d'inscription
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('registration/courses', $data, true)));
		
		echo "setPageInfo('registration/courses');setPageContent(\"".addslashes($content)."\");";
		?>registrationObj.semester='<?php echo $this->registrationSemester; ?>';<?php
		?>registrationObj.currentSemester='<?php echo $this->currentSemester; ?>';<?php
		?>registrationObj.currentDate='<?php echo date('Ymd'); ?>';<?php
		?>registrationObj.deadline_drop_fee='<?php echo $this->deadlines[$this->registrationSemester]['drop_fee']; ?>';<?php
		?>registrationObj.deadline_drop_nofee='<?php echo $this->deadlines[$this->registrationSemester]['drop_nofee']; ?>';<?php
		?>registrationObj.deadline_edit_selection='<?php echo $this->deadlines[$this->registrationSemester]['edit_selection']; ?>';<?php
		?>$('.courses').each(function(index, value) { $(value).find('tr').css('backgroundColor', '#fff'); $(value).find('tr:visible:odd').css('backgroundColor', '#dae6f1'); });<?php
		
		// Vérification du cookie d'aide
		if (!isset($_COOKIE['pilule-registration-help'])) {
			echo 'displayHelp(1);';
		}
		
		if (isset($newSemester)) echo "sendData('GET','./registration/getMenu', 'newSemester/1');";
	}
	
	// Fonction de rétro-compatibilité
	function courses () {
		$this->index();
	}
	
	function search () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		$data['response'] = $this->uri->segment(3);
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('registration/search', $data, true)));
		
		echo "setPageInfo('registration/search');setPageContent(\"".addslashes($content)."\");";
	}
	
	function s_search () {
		$code = str_replace(" ", "-", trim(strtoupper($this->input->post('code'))));
		
		if (strlen($code)==7) {
			$code = substr($code, 0, 3)."-".substr($code, 3, 4);
		}
		
		// Recherche du cours dans la base de données
		$course = $this->mCourses->getCourseInfo ($code);
		
		if ($course==array()) {
			ob_start();
			
			if (!$this->lcapsule->fetchCourse($code, $this->registrationSemester)) {
				$content = ob_get_clean();
				
				// Affichage d'une erreur, cours introuvable
				?><script language="javascript">top.document.location.hash='#!/registration/search/unknown';</script><?php
			} else {
				$content = ob_get_clean();
				
				$course['id'] = $code;
			}
		}
		
		// Redirection à la page du cours
		?><script language="javascript">top.document.location.hash='#!/registration/course/<?php echo $course['id']; ?>';</script><?php
	}
	
	function course () {
		$code = $this->uri->segment(3);
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		// Recherche du cours dans la base de données
		$data['course'] = $this->mCourses->getCourseInfo($code, true, $this->registrationSemester);
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$this->registrationSemester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['registered_courses'] = $courses2;
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$this->registrationSemester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['selected_courses'] = $courses2;
		
		// Chargement de la page demandée
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('registration/course-info', $data, true)));
		echo "setPageInfo('registration/course');setPageContent(\"".addslashes($content)."\");";
	}
	
	function w_help () {
		$step = $this->uri->segment(3);
		if ($step=='') $step = 1;
		
		$data = array();
		$data['step'] = $step;
		
		if ($step==5) {
			// Enregistrement du cookie
			setcookie('pilule-registration-help', '5', time()+3600*24*365*2, '/');
		}
		
		// Chargement de la page d'aide
		$this->load->view('registration/w-help', $data);		
	}
	
	function s_selectCourse () {
		$data = array();
		$semester = $this->uri->segment(4);
		$nrc = $this->uri->segment(6);
		$replace = $this->uri->segment(8);
		
		$class = $this->mCourses->getClass($nrc);
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$semester.']');
		$courses2 = array();
		$found = 0;
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['nrc']] = $course;
				if ($course['nrc']==$nrc) {
					$found = 1;
				}
				if ($course['code']==$class['idcourse']) {
					$found = 2;
					$old_nrc = $course['nrc'];
				}
			}
		}
		$registered_courses = $courses2;
		
		if ($found==1) {
			// Déjà inscrit au cours
			?>registrationObj.addSelectedCourseCallback(5);<?php
		} elseif ($found==2) {
			if ($replace=='') {
				// Demande de remplacement du cours
				?>registrationObj.addSelectedCourseCallback(6, <?php echo $nrc; ?>);<?php
				return (true);
			} elseif ($replace=='yes') {
				// Remplacement du cours
			}
		}
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$semester.']');
		$courses2 = array();
		$found = 0;
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				if ($course['nrc']==$nrc) {
					$found = 1;
				}
				if ($course['code']==$class['idcourse']) {
					$found = 2;
					$old_nrc = $course['nrc'];
				}
				$courses2[$course['nrc']] = $course;
			}
		}
		$selected_courses = $courses2;
		
		$course2 = $this->mCourses->getCourseInfo($class['idcourse']);
		
		$new_course = array(
							'code'		=>	$class['idcourse'],
							'title'		=>	$course2['title'],
							'letter'	=> '',
							'nrc'		=>	$nrc,
							'teacher'	=>	$class['teacher'],
							'credits'	=>	$course2['credits'],
							'campus'	=>	$class['campus'],
							'classes'	=>	serialize($class['timetable'])
							);
		
		if ($found==1) {
			// Cours déjà sélectionné
			?>registrationObj.addSelectedCourseCallback(3);<?php
			return (true);
		} elseif ($found==2) {
			if ($replace=='') {
				// Demande de remplacement du cours
				?>registrationObj.addSelectedCourseCallback(4, <?php echo $nrc; ?>);<?php
				return (true);
			} elseif ($replace=='yes') {
				unset($selected_courses[$old_nrc]);
				
				$selected_courses[$nrc] = $new_course;
			} elseif ($replace=='no') {			
				$selected_courses[$nrc] = $new_course;
			}
		} else {
			$selected_courses[$nrc] = $new_course;
		}
		
		$selected_courses = serialize($selected_courses);
		
		// Actualisation des cours sélectionnés dans le cache
		if (!$this->mCache->updateCache('data|selected-courses['.$semester.']', $selected_courses, '1')) {
			?>registrationObj.addSelectedCourseCallback(2);<?php
			return (false);
		}
		
		$selected_courses = unserialize($selected_courses);
		
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
	
	function s_unselectCourse () {
		$semester = $this->uri->segment(4);
		$nrc = $this->uri->segment(6);
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$semester.']');
		$courses2 = array();
		$found = 0;
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				if ($course['nrc']==$nrc) {
					$found = 1;
				}
				$courses2[$course['nrc']] = $course;
			}
		}
		$selected_courses = $courses2;
		
		unset($selected_courses[$nrc]);
		
		$selected_courses = serialize($selected_courses);
		
		// Actualisation des cours sélectionnés dans le cache
		if (!$this->mCache->updateCache('data|selected-courses['.$semester.']', $selected_courses, '1')) {
			?>registrationObj.removeSelectedCourseCallback(2);<?php
			return (false);
		}
		
		$selected_courses = unserialize($selected_courses);
		
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
		
		?>$('#courses-selection').html('<?php echo addslashes($content); ?>');registrationObj.removeSelectedCourseCallback(1, '<?php echo $nrc; ?>', '<?php echo count($selected_courses); ?>', '<?php echo $credits; ?>');<?php
	}
	
	function s_registerCourses () {
		$semester = $this->uri->segment(4);
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$semester.']');
		$selected_courses = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				$selected_courses[] = $course['nrc'];
			}
		}
		
		$this->mHistory->save('registration-register-courses');
		
		ob_start();
		
		// Test de la connexion à Capsule
		$this->lcapsule->testConnection();
		
		// Essai d'inscription aux cours sélectionnés sur Capsule
		$results = $this->lcapsule->registerCourses($selected_courses, $semester);
		
		if ((!is_array($results)) and $results===false) {
			$content = ob_get_clean();
			// Problème d'inscription
			?>errorMessage("Une erreur inconnue est survenue durant l'inscription.");<?php
			return (false);
		}
		
		$this->mUser->deleteSchedule($_SESSION['cap_iduser'], $semester);
		
		// Test de la connexion à Capsule
		$this->lcapsule->testConnection();
		
		// Recherche de l'horaire du semestre sur Capsule
		$this->lcapsule->getSchedule($semester);
		
		$content = ob_get_clean();
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$semester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				// Actualisation des places disponibles
				$this->lcapsule->updateClassSpots($course['nrc'], $semester);
				
				reset($results);
				foreach ($results as $result) {
					if ($course['nrc']==$result['nrc']) {
						if ($result['registered']!=1) {
							$error = $result['error'];
		
							switch ($error) {
								case 'CLOS-L.A. PLEINE':
								case 'GROUPE CLOS':
								case 'HEURES MAX DÉPASSÉES':
								case 'NOTE TEST/PRÉAL-ERREUR':
								case 'OUVERT — L.A. REMPLIE':
								case 'RÉSA CLOSE':
								case 'RÉSA OUV.-L.A. REMPLIE':
								case 'RESERVE CLOSED-WL FULL %':
								case 'RESTRICTION CAMPUS':
								case 'RESTRICTION CLASS':
								case 'RESTRICTION CYCLE':
								case 'RESTRICTION FAC.':
								case 'RESTRICTION MJRE':
								case 'RESTRICTION PROG':
								default:
									if (substr($error, 0, 5)=='CLOS-' and strpos($error, "LISTE ATTENTE")>1) {
										$result['registered'] = 2;
									} elseif (substr($error, 0, 5)=='OUV.-' and strpos($error, "LST ATTENTE")>1) {
										$result['registered'] = 2;
									} elseif (substr($error, 0, 8)=='RÉSA OUV' and strpos($error, "EN L.A.")>1) {
										$result['registered'] = 2;
									}  elseif (substr($error, 0, 8)=='RESERVE C' and strpos($error, "ON WL")>1) {
										$result['registered'] = 2;
									}
								break;
							}
						}
						
						if ($result['registered']==0) $courses2[$course['nrc']] = $course;
						break;
					}
				}
			}
		}
		
		// Actualisation de la liste des cours sélectionnés dans le cache
		$selected_courses = serialize($courses2);
		$this->mCache->updateCache('data|selected-courses['.$semester.']', $selected_courses);
		
		// Enregistrement des résultats de l'inscription dans le cache
		$token = md5(time());
		if ($this->mCache->addCache('data|registration-result-'.$token, serialize($results))) {
			// Redirection à la page des résultats de l'inscription
			?>document.location.hash="#!/registration/result/<?php echo $token; ?>";stopLoading();sendData('GET','./registration/getMenu', '');<?php
		} else {
			// Renvoi d'une erreur
			?>errorMessage("Une erreur est survenue durant l'inscription.");<?php
		}
	}
	
	function s_removeRegisteredCourse () {
		$semester = $this->uri->segment(4);
		$nrc = $this->uri->segment(6);
		
		$this->mHistory->save('registration-remove-registered-course');
		
		ob_start();
		
		// Test de la connexion à Capsule
		$this->lcapsule->testConnection();
		
		// Essai de désinscription du cours sur Capsule
		if ($this->lcapsule->removeCourse($nrc, $semester)) {
			$this->mUser->deleteSchedule($_SESSION['cap_iduser'], $semester);
			
			// Test de la connexion à Capsule
			$this->lcapsule->testConnection();
			
			// Recherche de l'horaire du semestre sur Capsule
			$this->lcapsule->getSchedule($semester);
		} else {
			$content = ob_get_clean();
			?>errorMessage("Une erreur inconnue est survenue lors du retrait du cours.");<?php
			return (false);
		}
		
		$content = ob_get_clean();
		
		// Actualisation des places disponibles
		$this->lcapsule->updateClassSpots($nrc, $semester);
					
		ob_start();
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$semester.']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$registered_courses = $courses2;
		
		$credits = 0;
		foreach ($registered_courses as $course) { ?>
			<li>
				<a href="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" class="course"><span style="font-size: 8pt;"><?php if (strlen($course['title'])>35) echo substr($course['title'], 0, 30)."..."; else echo $course['title']; ?></span><br />
			<div class="title" style="font-weight: bold; margin-bottom: 0px; float: left;"><?php echo $course['code']; ?></div>
			<div style="float: right; margin-bottom: 0px; color: green;">NRC : <?php echo $course['nrc']; ?></div><div style="clear: both;"></div></a>
				<a href="javascript:registrationObj.removeRegisteredCourse('<?php echo $course['nrc']; ?>');" class="delete-link" title="Enlever le cours"><img src="./images/cross-gray.png" width="16" height="16" /></a>
				<div style="clear: both;"></div>
			</li>
			<?php
				$credits += $course['credits'];
			}
		
		$content = str_replace("\n", "", str_replace("\r", "", ob_get_clean()));
		
		?>$('#registered-courses ul').html('<?php echo addslashes($content); ?>');registrationObj.removeRegisteredCourseCallback(1, '<?php echo $nrc; ?>', '<?php echo count($registered_courses); ?>', '<?php echo $credits; ?>');<?php
	}
	
	function result () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
				
		$token = $this->uri->segment(3);
		
		$cache = $this->mCache->getCache('data|registration-result-'.$token);
		if ($cache!=array()) {
			$data['results'] = array();
			foreach (unserialize($cache['value']) as $result) {
				$class = $this->mCourses->getClass($result['nrc']);
				$course = $this->mCourses->getCourseInfo($class['idcourse']);
				$result['code'] = $class['idcourse'];
				$result['title'] = $course['title'];
				
				$data['results'][] = $result;
			}
		}
		
		$this->mHistory->save('registration-result');

		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('registration/result', $data, true)));
		
		echo "setPageInfo('registration/result');setPageContent(\"".addslashes($content)."\");";
		echo "sendData('GET','./registration/getMenu', '');";
	}
	
	function w_getCourseInfo () {
		$data = array();
		$data['code'] = $this->uri->segment(4);
		$data['semester'] = $this->uri->segment(6);
		
		$data['course'] = $this->mCourses->getCourseInfo($data['code']);
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$data['semester'].']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['registered_courses'] = $courses2;
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$data['semester'].']');
		$courses2 = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['selected_courses'] = $courses2;
		
		if ($data['course']!=array()) {
			$this->load->view('registration/w-course-info', $data);		
		}
	}
	
	function w_getAvailableClasses () {
		$data = array();
		$data['code'] = $this->uri->segment(4);
		$data['semester'] = $this->uri->segment(6);
		
		$data['course'] = $this->mCourses->getCourseInfo($data['code'], true, $data['semester']);
		
		// Recherche des cours déjà inscrits
		$cache = $this->mCache->getCache('data|schedule['.$data['semester'].']');
		$courses2 = array();
		if ($cache!=array()) {
			$schedule = unserialize($cache['value']);
			foreach ($schedule['courses'] as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['registered_courses'] = $courses2;
		
		// Recherche des cours sélectionnés
		$cache = $this->mCache->getCache('data|selected-courses['.$data['semester'].']');
		$courses2 = array();
		if ($cache!=array()) {
			$selected = unserialize($cache['value']);
			foreach ($selected as $course) {
				$courses2[$course['nrc']] = $course;
			}
		}
		$data['selected_courses'] = $courses2;
		
		if ($data['course']!=array()) {
			$content = str_replace("\n", "", str_replace("\r", "", $this->load->view('registration/w-available-classes', $data, true)));		
			?>$('#loading-classes').hide();$('#classes-list').html('<?php echo addslashes($content); ?>');<?php
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
<?php

class Schedule extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Schedule() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
				
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mFacebook');
		
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
		$data['semester_date'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		$default_semester = '201209';
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('schedule-timetable');
		
		if ($data['semester_date']=='') {
			if (isset($_SESSION['schedule_current_semester'])) {
				$data['semester_date'] = $_SESSION['schedule_current_semester'];
			} else {
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|schedule,semesters');
				
				if ($cache!=array()) {
					$semesters = unserialize($cache['value']);
					
					if ($semesters!=array()) {
						if (isset($semesters[$default_semester])) {
							$data['semester_date'] = $default_semester;
						} else {
							$data['semester_date'] = key($semesters);
						}
						$_SESSION['schedule_current_semester'] = $data['semester_date'];
					}
				}
			}
		} else {
			$_SESSION['schedule_current_semester'] = $data['semester_date'];
		}
		
		if ($data['semester_date']!='') {
			// Vérification de l'existence des sessions en cache
			$cache = $this->mCache->getCache('data|schedule['.$data['semester_date'].']');
			
			if ($cache!=array()) {
				$data['schedule'] = unserialize($cache['value']);
				$data['cache_date'] = $cache['date'];
				$data['cache_time'] = $cache['time'];
				// Vérification de la date de chargement des données
				if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
					$data['reload_data'] = 'data|schedule,semesters';
				}
			}
		}
		
		$cache = $this->mCache->getCache('data|schedule,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
			$data['timetable'] = array();
			if (isset($_SESSION['schedule_current_period'])) $data['current_period'] = $_SESSION['schedule_current_period'];
			
			$classMaxEndTime = 0;
			$periods = $data['semesters'][$data['semester_date']]['periods'];
			$data['semesters'][$data['semester_date']]['periods'] = array();
			
			if ($periods!=array()) {
				$day_start = '';
				foreach ($periods as $dates => $name) {
					if ((!isset($_SESSION['schedule_current_period'])) || $_SESSION['schedule_current_period'] == '0') {
						$data['current_period'] = $dates;
						$_SESSION['schedule_current_period'] = $dates;
					}
					
					$dates2 = explode('-', $dates);
					
					if ($day_start=='') $day_start = $dates2[0];
					if ($day_start==$dates2[0]) {
						$classes = $this->mUser->getClasses(array('day_start >='=>$day_start, 'day_start <='=>$dates2[1], 'day_end >='=>$dates2[1], 'idul'=>$_SESSION['cap_iduser']));
					} else {
						$classes = $this->mUser->getClasses(array('day_end >'=>$dates2[0], 'day_start <='=>$dates2[1], 'idul'=>$_SESSION['cap_iduser']));
					}
					
					$number = 0;
					foreach ($classes as $class) {
						if ($class['type'] != 'Sur Internet' && $class['type'] != 'Mobilité' && $class['type'] != 'Stage' && $class['type'] != 'Matériel imprimé' && $class['type'] != 'Télévisé-Canal Savoir') {
							if (!isset($data['timetable'][$dates][$class['day']])) $data['timetable'][$dates][$class['day']] = array();
							$data['timetable'][$dates][$class['day']][] = $class;
							if ($class['hour_end']>$classMaxEndTime) $classMaxEndTime = $class['hour_end'];
							$number++;
						}
					}
					
					if ($number != 0) {
						$data['semesters'][$data['semester_date']]['periods'][$dates] = $name;
					}
				}

				$data['semester']['periods'] = $data['semesters'][$data['semester_date']]['periods'];
			} else {
				$classes = $this->mUser->getClasses(array('semester'=>$data['semester_date'], 'idul'=>$_SESSION['cap_iduser']));
				
				foreach ($classes as $class) {
					if ($class['type'] != 'Sur Internet' && $class['type'] != 'Mobilité' && $class['type'] != 'Stage' && $class['type'] != 'Matériel imprimé' && $class['type'] != 'Télévisé-Canal Savoir') {
						if (!isset($data['timetable'][0][$class['day']])) $data['timetable'][0][$class['day']] = array();
						$data['timetable'][0][$class['day']][] = $class;
						if ($class['hour_end']>$classMaxEndTime) $classMaxEndTime = $class['hour_end'];
					}
				}
				
				$data['current_period'] = 0;
				$_SESSION['schedule_current_period'] = 0;
			}
			$data['max_end_time'] = $classMaxEndTime;
			
			$data['other_classes'] = $this->mUser->getClasses(array('type'=>array('Mobilité','Sur Internet','Stage','Matériel imprimé','Télévisé-Canal Savoir'), 'semester'=>$data['semester_date'], 'idul'=>$_SESSION['cap_iduser']));
		}
		
		if (isset($data['schedule']) and $data['schedule']!=array()) {
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$browser = '';
			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
			{ 
				$browser = "MSIE"; 
			} 
			elseif(preg_match('/Firefox/i',$u_agent)) 
			{ 
				$browser = "Firefox"; 
			} 
			elseif(preg_match('/Chrome/i',$u_agent)) 
			{ 
				$browser = "Chrome"; 
			} 
			elseif(preg_match('/Safari/i',$u_agent)) 
			{ 
				$browser = "Safari"; 
			} 
			elseif(preg_match('/Opera/i',$u_agent)) 
			{ 
				$browser = "Opera"; 
			} 
			elseif(preg_match('/Netscape/i',$u_agent)) 
			{ 
				$browser = "Netscape"; 
			}
			
			if ($browser == 'Firefox' || $browser == 'Safari' || $browser == 'Chrome') {
				$template = "new-timetable";
			} else {
				$template = "timetable";
			}
			
			// Chargement de la page
			if ($this->mobile!=1) $content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/'.$template, $data, true))); else $content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/m-timetable', $data, true)));
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Horaire de cours';
			$data['reload_name'] = 'data|schedule,semesters';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		/*
		// Vérification de la connexion à Facebook
		$fb_data = $_SESSION['fb_data']; // This array contains all the user FB information
		$data['fb_data'] = $fb_data;
		
		if((!$fb_data['uid']) or (!$fb_data['me'])) {
			$data['require_fb_login'] = 1;
		} else {
			$data['require_fb_login'] = 0;
		}
		*/
		
		echo "setPageInfo('schedule/timetable');setPageContent(\"".addslashes($content)."\");$('.class .class-title').shorten();$('.class').tipTip({maxWidth: 'auto', edgeOffset: -2, defaultPosition: 'top'});";
		if (isset($schedule) and $schedule!=array()) {
			echo "scheduleObj.currentPeriod='".$current_period."';";
		}
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
		if ($this->mobile == 1) echo "$('h2.title').after($('#sidebar'));";
	}
	
	function share () {
		$data = array();
		$data['section'] = 'schedule';
		$data['page'] = 'share';
		$data['user'] = $this->user;
		$data['semester_date'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		$default_semester = '201209';
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$fb_data = $this->session->userdata('fb_data');
		$data['fb_data'] = $fb_data;
		
		/*
		//exit();
		// Vérification de la connexion à Facebook
		if (!isset($_SESSION['fb_data'])) {
			redirect("cfacebook/auth/u/".base64_encode(site_url()."schedule/share"));
			return (true);
		}
		
		$fb_data = $_SESSION['fb_data']; // This array contains all the user FB information
		$data['fb_data'] = $fb_data;

		if ((!$fb_data['uid']) or (!$fb_data['me'])) {
			redirect("cfacebook/auth/u/".base64_encode(site_url()."schedule/share"));
		} else {
			&*/
			if (isset($fb_data) and isset($fb_data['uid'])) {
				$data['fb_friendlists'] = $this->mFacebook->getFriendlists();
				//$data['fb_friends'] = $this->mFacebook->getFriends();
			}
			
			// Chargement de la page
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/share-timetable', $data, true)));
			
			echo "setPageInfo('schedule/courses');setPageContent(\"".addslashes($content)."\");";
			if ((!isset($fb_data)) or (!$fb_data['uid']) or (!$fb_data['me'])) {
				echo 'scheduleObj.askFBAuth();';
			}
		//}
	}
	
	function w_askFBAuth () {
		$data = array();
		
		if ($this->input->get('error') != '') {
			$data['error'] == base64_decode($this->input->get('error'));
		}
		
		// Chargement de la page d'aide
		$this->load->view('schedule/w-fb-auth', $data);
	}
	
	function s_authFB () {
		if ($this->input->get('fbauth') != '') {
			if ($this->input->get('fbauth') == 'error') {
				redirect("schedule/w_askFBAuth?error=".base64_encode("L'authentification par Facebook a échouée."));
			}
		} else {
			//$user_fbdata = $this->mFacebook->getUserData();
			$user_fbdata = $this->session->userdata('fb_data'); // This array contains all the user FB information
 			
			$this->mUser->setParam('fbuid', $user_fbdata['uid']);
			$this->mUser->setParam('fbname', $user_fbdata['me']['name']);
			
			?><script language="javascript">top.document.location='<?php echo site_url(); ?>#!/schedule/share';top.refreshPage();top.$.modal.close();</script><?php
		}
	}
	
	function s_unlinkFB () {
		$this->facebook->destroySession();
		
		?>refreshPage();scheduleObj.askFBAuth();<?php
	}
	
	// Fonction de rétro-compatibilité
	function timetable() {
		$this->index();
	}
	
	function courses () {
		$data = array();
		$data['user'] = $this->user;
		$data['semester_date'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('schedule-courses');
		
		if ($data['semester_date']=='') {
			if (isset($_SESSION['schedule_current_semester'])) {
				$data['semester_date'] = $_SESSION['schedule_current_semester'];
			} else {
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|schedule,semesters');
				
				if ($cache!=array()) {
					$semesters = unserialize($cache['value']);
					
					if ($semesters!=array()) {
						$data['semester_date'] = key($semesters);
						$_SESSION['schedule_current_semester'] = $data['semester_date'];
					}
				}
			}
		} else {
			$_SESSION['schedule_current_semester'] = $data['semester_date'];
		}
		
		if ($data['semester_date']!='') {
			$courses = $this->mUser->getCourses($_SESSION['cap_iduser'], $data['semester_date']);

			$data['courses'] = array();
			foreach ($courses as $course) {
				$course['classes'] = $this->mUser->getClasses(array('idcourse'=>$course['idcourse'], 'semester'=>$data['semester_date'], 'idul'=>$_SESSION['cap_iduser']));
				$data['courses'][] = $course;
			}
		}
		
		$cache = $this->mCache->getCache('data|schedule,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|schedule,semesters';
			}
		}
		
		if (isset($data['semesters']) and $data['semesters']!=array()) {
			// Chargement de la page
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/courses', $data, true)));
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Liste des cours';
			$data['reload_name'] = 'data|schedule,semesters';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('schedule/courses');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
	
	function w_export () {
		$data['semester_date'] = $this->uri->segment(3);
		
		$this->mHistory->save('schedule-export');
		
		$_SESSION['schedule_current_semester'] = $data['semester_date'];
		$cache = $this->mCache->getCache('data|schedule,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
		}
		
		// Chargement de la page d'aide
		$this->load->view('schedule/w-export', $data);
	}
	
	function s_export() {
		$semester = $this->input->post('semester');
		$alarm = $this->input->post('alarm');
		$title = $this->input->post('title');
		$format = $this->input->post('format');
		
		$this->load->helper('download');
		
		$data = $this->mUser->exportSchedule($semester, $format, $alarm, $title);
		
		switch (substr($semester, 4, 2)) {
			case '01':
				$semester_name = 'hiver-'.substr($semester, 0, 4);
			break;
			case '01':
				$semester_name = 'automne-'.substr($semester, 0, 4);
			break;
			default:
				$semester_name = 'ete-'.substr($semester, 0, 4);
			break;
		}
		
		force_download('horaire-'.$semester_name.'.ics', $data);
	}
}
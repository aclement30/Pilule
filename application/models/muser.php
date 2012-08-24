<?php

class mUser extends CI_Model {
	var $expirationDelay = 0;
	
	function mUser () {
		parent::__construct();
		
		if (isset($_SESSION['cap_iduser'])) {
			if (!$this->getParam('data-expiration-delay')) {
				$this->expirationDelay = 3600*6;
			} else {
				$this->expirationDelay = $this->getParam('data-expiration-delay');
			}
		}
	}
	
	// Enregistrement de la dernière visite de l'utilisateur
	function registerLogin () {
		$this->db->where('idul', $_SESSION['cap_iduser']);
		
		if ($this->db->update('users', array('last_visit'=>time()))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function getParam ($name, $idul = '') {
		if ($idul == '') $idul = $_SESSION['cap_iduser'];
		
		// Sélection des données
		$this->db->where(array('idul'=>$idul, 'name'=>$name));
		$result = $this->db->get('params');
		
		$param = $result->row_array();
		
		if ($param!=array()) {
			// Renvoi du paramètre
			return ($param['value']);
		} else {
			return (false);
		}
	}
	
	// Modification d'un paramètre
	function setParam ($name, $value) {
		if (!$this->getParam($name)) {
			if ($this->db->insert('params', array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name, 'value'=>$value))) {
				return (true);
			} else {
				return (false);
			}
		} else {
			$this->db->where(array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name));
			
			if ($this->db->update('params', array('value'=>$value))) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	// Suppression d'un paramètre
	function deleteParam ($name) {
		if ($this->db->delete('params', array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Vérification de l'existence des données en cache
	function checkData () {
		if (isset($_SESSION['cap_datacheck']) and $_SESSION['cap_datacheck'] == 2) return (true);
		
		$studies = $this->getStudies();
		
		if ($studies!=array()) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function keepData () {
		if ($this->mUser->getParam('data-storage') == 'yes') {
			return (true);
		} else {
			return (false);
		}
	}
	
	function info ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		$this->db->where('idul', $idul);
				
		$result = $this->db->get('users');
		
		$user = $result->row_array();

		if ($idul == $_SESSION['cap_iduser']) {
			$user['registration'] = $this->canRegister($user['program']);
		}
		// Renvoi de l'utilisateur
		return ($user);
	}
	
	// Modification d'un utilisateur
	function editUser ($user) {
		if (!isset($user['idul'])) $user['idul'] = $_SESSION['cap_iduser'];
		
		$this->db->where('idul', $user['idul']);
		unset($user['idul']);
		
		if ($this->db->update('users', $user)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Fonction de rétro-compatibilité
	function getUser ($idul) {
		// Sélection des données
		return ($this->info($idul));
	}
	
	// Cours de l'utilisateur
	
	// Ajout d'une classe de l'utilisateur
	function addClass ($class) {
		if (!array_key_exists('idul', $class)) $class['idul'] = $_SESSION['cap_iduser'];
		
		if ($this->db->insert('users_classes', $class)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche des cours d'un utilisateur
	function getClasses ($params) {
		if (isset($params['type']) and is_array($params['type'])) {
			$types = $params['type'];
			unset($params['type']);
		}
		
		// Sélection des données
		$this->db->where($params);
		if (isset($types)) {
			$this->db->where_in('type', $types);
		}
		
		$this->db->order_by('hour_start');
		
		$result = $this->db->get('users_classes');
		
		$classes = $result->result_array();

		if ($classes!=array()) {			
			// Renvoi du paramètre
			return ($classes);
		} else {
			return (array());
		}
	}
	
	
	// Suppression des classes de l'utilisateur
	function deleteClasses ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		if ($idul == 'demo') return (true);
		
		if ($this->db->delete('users_classes', array('idul'=>$idul))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Ajout d'un cours de l'utilisateur
	function addCourse ($course) {
		if (!array_key_exists('idul', $course)) $course['idul'] = $_SESSION['cap_iduser'];
		
		if ($this->db->insert('users_courses', $course)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche des cours d'un utilisateur
	function getCourses ($idul, $semester = '') {
		// Sélection des données
		if ($semester != '') {
			$this->db->where(array('idul'=>$idul, 'semester'=>$semester));
			$this->db->group_by('idcourse');
		} else {
			$this->db->where(array('idul'=>$idul));
		}
		$this->db->order_by("idcourse", "asc");
		
		$result = $this->db->get('users_courses');
		
		$courses = $result->result_array();

		if ($courses!=array()) {			
			// Renvoi du paramètre
			return ($courses);
		} else {
			return (array());
		}
	}
	
	// Ajout d'une section de cours de l'utilisateur
	function addCoursesSection ($section) {
		if (!array_key_exists('idul', $section)) $section['idul'] = $_SESSION['cap_iduser'];
		
		if ($this->db->insert('users_courses_sections', $section)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche des sections de cours de l'utilisateur
	function getCoursesSections ($idul) {
		// Sélection des données
		$this->db->where(array('idul'=>$idul));
		$this->db->order_by("number", "asc");
		
		$result = $this->db->get('users_courses_sections');
		
		$sections = $result->result_array();
		
		if ($sections!=array()) {			
			// Renvoi du paramètre
			return ($sections);
		} else {
			return (array());
		}
	}
	
	// Suppression des cours de l'utilisateur
	function deleteCourses ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		if ($idul != 'demo') {
			$this->db->delete('users_courses', array('idul'=>$idul));
			$this->db->delete('users_courses_sections', array('idul'=>$idul));
		}
		return (true);
	}
	
	// Ajout des infos d'études de l'utilisateur
	function setStudies ($studies) {
		$this->db->where('idul', $_SESSION['cap_iduser']);
		
		$result = $this->db->from('studies');
		
		if (isset($studies['concentrations']) and is_array($studies['concentrations'])) $studies['concentrations'] = serialize($studies['concentrations']);
		if (isset($studies['data'])) unset($studies['data']);
		if (isset($studies['rawdata'])) unset($studies['rawdata']);
		
		if ($this->db->count_all_results()==0) {
			$studies['idul'] = $_SESSION['cap_iduser'];
			
			if ($this->db->insert('studies', $studies)) {
				return (true);
			} else {
				return (false);
			}
		} else {
			$this->db->where('idul', $_SESSION['cap_iduser']);
			
			if ($this->db->update('studies', $studies)) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	// Recherche des infos d'étude de l'utilisateur
	function getStudies ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		// Sélection des données
		$this->db->where(array('idul'=>$idul));
		$result = $this->db->get('studies');
		
		$studies = $result->row_array();

		if ($studies!=array()) {
			$studies['concentrations'] = unserialize($studies['concentrations']);
			
			// Renvoi du paramètre
			return ($studies);
		} else {
			return (array());
		}
	}
	
	// Suppression des infos d'études de l'utilisateur
	function deleteStudies ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		if ($idul == 'demo') return (true);
			
		if ($this->db->delete('studies', array('idul'=>$idul))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Suppression de l'horaire de cours de l'utilisateur
	function deleteSchedule ($idul = '', $semester = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		if ($idul == 'demo') return (true);
		
		if ($semester=='') {
			$this->db->delete('users_classes', array('idul'=>$idul));
			$this->db->delete('cache', array('idul'=>$idul, 'name'=>'data|schedule,semesters'));
			
			$this->db->like('name', 'data|schedule[', 'after');
		} else {
			$this->db->delete('users_classes', array('idul'=>$idul, 'semester'=>$semester));
			
			$this->db->where('name', 'data|schedule['.$semester.']');
		}
		$this->db->where('idul', $idul);
		
		if ($this->db->delete('cache')) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function exportSchedule ($semester, $format = "ical", $alarm = 'no', $title = 'name') {
		$classes = $this->getClasses(array('semester'=>$semester, 'day !='=>'', 'idul'=>$_SESSION['cap_iduser']));
        $courses = $this->getCourses($_SESSION['cap_iduser'], $semester);
		
        if (count($classes) > 0) {
			$ics = 
'BEGIN:VCALENDAR
CALSCALE:GREGORIAN
X-WR-TIMEZONE;VALUE=TEXT:Canada/Eastern
METHOD:PUBLISH
PRODID:-//Pilule //NONSGML iCalendar Template//EN
X-WR-CALNAME;VALUE=TEXT:Université Laval
VERSION:2.0';            
			$weekdays = array("L"=>0,"M"=>1,"R"=>2,"J"=>3,"V"=>4,"S"=>5);
			$sectors = array(
				 "Est"					=>	'PVE',
				 "Pavillon de l'Éducation physique et des sports"	=>	'EPS',
				 "PEPS"	=>	'PEPS',
				 "Médecine dentaire"	=>	'MDE',
				 "Centre de foresterie des Laurentides"	=>	'CFL',
				 "Abitibi-Price"		=>	'ABP',
				 "Palasis-Prince"		=>	'PAP',
				 "Maison Omer-Gingras"	=>	'OMG',
				 "Services"				=>	'PSA',
				 "Ferdinand-Vandry"		=>	'VND',
				 "Charles-Eugène-Marchand"=>'CHM',
				 "Alexandre-Vachon"		=>	'VCH',
				 "Adrien-Pouliot"		=>	'PLT',
				 "Charles-De Koninck"	=>	'DKN',
				 "Jean-Charles-Bonenfant"=>	'BNF',
				 "Sciences de l'éducation"=>'TSE',
				 "Félix-Antoine-Savard"	=>	'FAS',
				 "Louis-Jacques-Casault"=>	'CSL',
				 "Paul-Comtois"			=>	'CMT',
				 "Maison Eugène-Roberge"=>	'EGR',
				 "Maison Marie-Sirois"	=>	'MRS',
				 "Agathe-Lacerte"		=>	'LCT',
				 "Ernest-Lemieux"		=>	'LEM',
				 "Alphonse-Desjardins"	=>	'ADJ',
				 "Maurice-Pollack"		=>	'POL',
				 "H.-Biermans-L.-Moraud"=>	'PBM',
				 "Alphonse-Marie-Parent"=>	'PRN',
				 "J.-A.-DeSève"			=>	'DES',
				 "La Laurentienne"		=>	'LAU',
				 "Envirotron"			=>	'EVT',
				 "Optique-photonique"	=>	'COP',
				 "Gene-H.-Kruger"		=>	'GHK',
				 "Héma-Québec"			=>	'HQ',
				 "Maison Michael-John-Brophy"=>'BRY',
				 
				 "Maison Couillard"		=>	'MCO',
				 "Serres haute performance"=>'EVS',
				 'Édifice de La Fabrique'=>	'FAB',
				 'Édifice du Boulevard'	=>	'E-BLVD',
				 'Éd. Vieux-Séminaire-de-Québec'	=>	'SEM'
				 
				 );
			$readingWeekStart = mktime(0, 0, 0, 03, 05, 2012);
			$readingWeekEnd = mktime(0, 0, 0, 03, 11, 2012);
			
			//error_log(print_r($courses, true));
			foreach($classes as $class) {
				foreach ($courses as $course) {
					if ($course['idcourse'] == $class['idcourse']) {
						$class['name'] = $course['title'];
						break;
					}
				}
				if (!isset($class['name'])) $class['name'] = $class['idcourse'];
				
				$firstDay = mktime(floor($class['hour_start']), 0, 0, substr($class['day_start'], 4, 2), substr($class['day_start'], 6, 2), substr($class['day_start'], 0, 4))+($weekdays[$class['day']]*3600*24);
				$lastDay = mktime(floor($class['hour_end']), 0, 0, substr($class['day_end'], 4, 2), substr($class['day_end'], 6, 2), substr($class['day_end'], 0, 4));
				$currentDay = $firstDay;
				
				while ($currentDay < $lastDay) {
					if ($currentDay > $lastDay) break;
					if ($currentDay >= $readingWeekStart && $currentDay <= $readingWeekEnd) {
						// Semaine de lecture
					} else {
						$startTime = floor($class['hour_start']);
						if ($startTime < 10) $startTime = "0".$startTime;
						$startTime .= (ceil($class['hour_start'])-$class['hour_start'])*60;
						
						$endTime = floor($class['hour_end']);
						if ($endTime < 10) $endTime = "0".$endTime;
						$endTime .= (ceil($class['hour_end'])-$class['hour_end'])*60;
						
						$local = $class['local'];
						$sector = substr($local, 0, strrpos($local, ' '));
						$local_number = substr($local, strrpos($local, ' ')+1);
						if (array_key_exists($sector, $sectors)) {
							$location = $sectors[$sector]." ".$local_number;
						} else {
							$location = $sector.", local ".$local_number;
						}
						
						if ($title == 'name') {
							$eventTitle = $class['name'];
						} else {
							$eventTitle = $class['idcourse'];
						}
						
						$ics .= 
'
BEGIN:VEVENT
SEQUENCE:1
DTSTART;TZID=Canada/Eastern:'.date('Ymd', $currentDay).'T'.$startTime.'00
SUMMARY:'.$eventTitle.'
DTEND;TZID=Canada/Eastern:'.date('Ymd', $currentDay).'T'.$endTime.'00
LOCATION:'.$location;
						if ($alarm != 'no') {
							switch ($alarm) {
								case '1h':
									$alarm_trigger = 'PT1H';
								break;
								case '30m':
									$alarm_trigger = 'PT30M';
								break;
								case '15m':
									$alarm_trigger = 'PT15M';
								break;
							}
							
							$ics .=
'
BEGIN:VALARM
TRIGGER:-'.$alarm_trigger.'
ACTION:DISPLAY
DESCRIPTION:'.$eventTitle.'
END:VALARM';
						}
						$ics .=
'
END:VEVENT';
					}
					$currentDay += 3600*24*7;
					
				}
			}
        }
          
		 $ics .= 
'
END:VCALENDAR';
        
		return ($ics);
	}
	
	// Suppression des sommaires de frais de scolarité de l'utilisateur
	function deleteFeesSummary ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		if ($idul == 'demo') return (true);
		
		$this->db->delete('cache', array('idul'=>$idul, 'name'=>'data|fees,summary'));
		$this->db->delete('cache', array('idul'=>$idul, 'name'=>'data|fees,semesters'));
		
		$this->db->like('name', 'data|fees[', 'after');
		$this->db->where('idul', $idul);
		
		if ($this->db->delete('cache')) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche des modules de l'utilisateur
	function getModules ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		// Sélection des données
		$this->db->where(array('idul'=>$idul));
		$this->db->order_by('order asc');
		$result = $this->db->get('users_modules');
		
		$modules = $result->result_array();

		if ($modules!=array()) {
			// Renvoi du paramètre
			return ($modules);
		} else {
			return (array());
		}
	}
	
	function updateModules ($modules) {
		$idul = $_SESSION['cap_iduser'];
		
		$this->db->where('idul', $idul);
		$this->db->delete('users_modules');
		
		$num = 1;
		foreach ($modules as $module) {
			$this->db->insert('users_modules', array(
													 'idul'		=>	$idul,
													 'module'	=>	substr($module, 4),
													 'order'	=>	$num
													 ));
			
			$num++;
		}
	}
	
	// Suppression des préférences du tableau de bord de l'utilisateur
	function deleteModules ($idul = '') {
		if ($idul=='') $idul = $_SESSION['cap_iduser'];
		
		if ($idul == 'demo') return (true);
		
		if ($this->db->delete('users_modules', array('idul'=>$idul))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function canRegister ($program) {
		// Sélection des données
		$this->db->where(array('title'=>$program, 'active'=>'1'));
		
		$result = $this->db->get('registration_programs');
		
		$program = $result->row_array();
		
		if ($program != array()) {
			return (true);
		} else {
			return (false);
		}
	}
}
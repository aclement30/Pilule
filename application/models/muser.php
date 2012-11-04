<?php

class mUser extends CI_Model {
	var $expirationDelay = 0;
	
	function mUser () {
		parent::__construct();
		
		if ($this->session->userdata('pilule_user') != '') {
			if (!$this->getParam('data-expiration-delay')) {
				$this->expirationDelay = 3600*6;
			} else {
				$this->expirationDelay = $this->getParam('data-expiration-delay');
			}
		}
	}

    function isAuthenticated () {
        if ($this->session->userdata('pilule_user') == '') {
            return (false);
        } else {
            $this->user = $this->info();

            return (true);
        }
    }

	// Enregistrement de la dernière visite de l'utilisateur
	function registerLogin () {
		$this->db->where('idul', $this->session->userdata('pilule_user'));
		
		if ($this->db->update('users', array('last_visit'=>time()))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function getParam ($name, $idul = '') {
		if ($idul == '') $idul = $this->session->userdata('pilule_user');
		
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
			if ($this->db->insert('params', array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name, 'value'=>$value))) {
				return (true);
			} else {
				return (false);
			}
		} else {
			$this->db->where(array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name));
			
			if ($this->db->update('params', array('value'=>$value))) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	// Suppression d'un paramètre
	function deleteParam ($name) {
		if ($this->db->delete('params', array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name))) {
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
		if ($idul=='') $idul = $this->session->userdata('pilule_user');
		
		$this->db->where('idul', $idul);
				
		$result = $this->db->get('users');
		
		$user = $result->row_array();

		if ($idul == $this->session->userdata('pilule_user')) {
			$user['registration'] = $this->canRegister($user['program']);
		}

		// Renvoi de l'utilisateur
		return ($user);
	}
	
	// Modification d'un utilisateur
	function editUser ($user) {
		if (!isset($user['idul'])) $user['idul'] = $this->session->userdata('pilule_user');
		
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
	
	function exportSchedule ($semester, $format = "ical", $alarm = 'no', $title = 'name') {
		$classes = $this->getClasses(array('semester'=>$semester, 'day !='=>'', 'idul'=>$this->session->userdata('pilule_user')));
        $courses = $this->getCourses($this->session->userdata('pilule_user'), $semester);
		
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
	
	// Recherche des modules de l'utilisateur
	function getModules ($idul = '') {
		if ($idul=='') $idul = $this->session->userdata('pilule_user');
		
		// Sélection des données
		$this->db->where(array('idul'=>$idul));
		$this->db->order_by('order asc');
		$result = $this->db->get('users_modules_map');
		
		$modules = $result->result_array();

		if ($modules!=array()) {
			// Renvoi du paramètre
			return ($modules);
		} else {
			return (array());
		}
	}
	
	function updateModules ($modules) {
		$idul = $this->session->userdata('pilule_user');
		
		$this->db->where('idul', $idul);
		$this->db->delete('users_modules');
		
		$num = 1;
		foreach ($modules as $module) {
			$this->db->insert('users_modules_map', array(
													 'idul'		=>	$idul,
													 'module'	=>	substr($module, 4),
													 'order'	=>	$num
													 ));
			
			$num++;
		}
	}
	
	// Suppression des préférences du tableau de bord de l'utilisateur
	function deleteModules ($idul = '') {
		if ($idul=='') $idul = $this->session->userdata('pilule_user');
		
		if ($idul == 'demo') return (true);
		
		if ($this->db->delete('users_modules_map', array('idul'=>$idul))) {
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
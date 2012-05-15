<?php

// Modèle à revoir, fonctions (deprecated)

class mRegistration extends CI_Model {
	var $debug = 0;
	
	function mRegistration () {
		parent::__construct();
	}
	
	function fetchCourses ($semester = '', $code = '', $subject = '', $title = '') {
		$semester = "201201";
		$subject = "ACT";
		
		$this->lfetch->cookies = $_SESSION['cookies'];
		$this->lfetch->debug = $this->debug;
		
		if ($_SESSION['referer']=='') {
			$this->lfetch->referer = 'http://www.capsule.ulaval.ca/';
		} else {
			$this->lfetch->referer = $_SESSION['referer'];
		}
		
		$this->lfetch->protocol="https";
		
		$arguments['HostName'] = "capsuleweb.ulaval.ca";
		$arguments["RequestURI"] = "/pls/etprod7/bwckschd.p_disp_dyn_sched";
		
		$error=$this->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log('Ligne 25');
			return (false);
		}
		
		$error = $this->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log('Ligne 32');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 40');
			return (false);
		}
		
		$this->lfetch->Close();
		
		$this->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
		$this->lfetch->request_method="POST";
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'p_term'				=>	$semester,
			  'p_calling_proc'		=>	'bwckschd.p_disp_dyn_sched'
			  );
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckgens.p_proc_term_date";
		
		$error=$this->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log('Ligne 60');
			return (false);
		}
		
		$error = $this->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log('Ligne 67');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 75');
			return (false);
		}
		
		$this->lfetch->Close();
		
		$this->lfetch->request_method="POST";
		
		$this->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwckgens.p_proc_term_date';
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckschd.p_get_crse_unsec";
		
		if ($code!='') {
			$subject = substr($code, 0, 3);
			$number = substr($code, 4, 4);
		} else {
			$number = '';
		}
		
		if ($subject=='') $subject = 'dummy';
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'term_in'				=>	$semester,
			  'sel_subj'			=>	$subject,
			  'sel_day'				=>	'dummy',
			  'sel_schd'			=>	'dummy',
			  'sel_insm'			=>	'dummy',
			  'sel_camp'			=>	'dummy',
			  'sel_levl'			=>	'dummy',
			  'sel_sess'			=>	'dummy',
			  'sel_instr'			=>	'dummy',
			  'sel_ptrm'			=>	'dummy',
			  'sel_attr'			=>	'dummy',
			  'sel_crse'			=>	'',
			  'sel_title'			=>	'',
			  'sel_from_cred'		=>	'',
			  'sel_to_cred'			=>	'',
			  'begin_hh'			=>	'0',
			  'begin_mi'			=>	'0',
			  'end_hh'				=>	'23',
			  'end_mi'				=>	'59',
			  'end_ap'				=>	'x'
			  );
		
		$this->lfetch->Open($arguments);

		$error=$this->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log('Ligne 125');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 133');
			return (false);
		}
		
		$error = $this->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		print "<pre>".htmlentities($response)."</pre>";
		
		//error_log($response);
		$this->lfetch->Close();
		
		$_SESSION['referer'] = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwckschd.p_get_crse_unsec';
		
		$response = str_replace("<br />\nS ", "", $response);
		
		if (strpos($response, "horaire et inscription")>1) {
			$this->lfetch->SaveCookies($cookies);
			
			$_SESSION['cookies'] = $cookies;
			
			?><code><?php echo HtmlSpecialChars($response); ?></code><?php
			/*
			$content = substr($response, strpos($response, "<DIV class=\"pagebodydiv\">"));
			$content = substr($content, strpos($content, "<TABLE"));
			$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
			$content = str_replace("<br />", "", $content);
			$content = str_replace(":</TH>", "</TH>", $content);
			
			// Tri des données
			$studies = array();
			
			$program = substr($content, strpos($content, "Programme<"));
			$program = substr($program, strpos($program, "dddefault")+11);
			$program = substr($program, 0, strpos($program, "</TD>"));
			$studies['program'] = $program;
			
			$bachelor = substr($content, strpos($content, "Programme actuel"));
			$bachelor = substr($bachelor, strpos($bachelor, "dddefault")+11);
			$bachelor = substr($bachelor, 0, strpos($bachelor, "</TD>"));
			$studies['bachelor'] = $bachelor;
			
			$cycle = substr($content, strpos($content, ">Cycle"));
			$cycle = substr($cycle, strpos($cycle, "dddefault")+11);
			$cycle = substr($cycle, 0, strpos($cycle, "</TD>"));
			$studies['cycle'] = $cycle;
			
			$adm_semester = substr($content, strpos($content, "Session d'admission"));
			$adm_semester = substr($adm_semester, strpos($adm_semester, "dddefault")+11);
			$adm_semester = substr($adm_semester, 0, strpos($adm_semester, "</TD>"));
			$studies['adm_semester'] = $adm_semester;
			
			$adm_type = substr($content, strpos($content, "Type d'admission"));
			$adm_type = substr($adm_type, strpos($adm_type, "dddefault")+11);
			$adm_type = substr($adm_type, 0, strpos($adm_type, "</TD>"));
			$studies['adm_type'] = $adm_type;
			
			$major = substr($content, strpos($content, ">Majeure"));
			$major = substr($major, strpos($major, "dddefault")+11);
			$major = substr($major, 0, strpos($major, "</TD>"));
			$studies['major'] = $major;
			
			$concentrations = array();
			if (preg_match("#Concentration de majeure#", $content, $matches)) {
				$data = substr($content, strpos($content, "Concentration de majeure")-5);
				$data = substr($data, 0, strpos($data, "</TABLE>"));
				
				for ($n=0; $n<5; $n++) {
					if (strpos($data, "Concentration de majeure")<=0) break;
					
					$data = substr($data, strpos($data, "Concentration de majeure"));
					$concentration = substr($data, strpos($data, "dddefault")+11);
					$concentration = substr($concentration, 0, strpos($concentration, "</TD>"));
					$concentrations[] = $concentration;
					
					$data = substr($data, 20);
				}
			}
			$studies['concentrations'] = $concentrations;
			
			$status = substr($content, strpos($content, ">Statut"));
			$status = substr($status, strpos($status, "dddefault")+11);
			$status = substr($status, 0, strpos($status, "</TD>"));
			$studies['status'] = $status;
			
			$registered = substr($content, strpos($content, "Inscrit pour la session"));
			$registered = substr($registered, strpos($registered, "dddefault")+11);
			$registered = substr($registered, 0, strpos($registered, "</TD>"));
			$studies['registered'] = $registered;
			
			$first_sem = substr($content, strpos($content, "Première session de fréquentation"));
			$first_sem = substr($first_sem, strpos($first_sem, "dddefault")+11);
			$first_sem = substr($first_sem, 0, strpos($first_sem, "</TD>"));
			$studies['first_sem'] = $first_sem;
			
			$last_sem = substr($content, strpos($content, "Dernière session de fréquentation"));
			$last_sem = substr($last_sem, strpos($last_sem, "dddefault")+11);
			$last_sem = substr($last_sem, 0, strpos($last_sem, "</TD>"));
			$studies['last_sem'] = $last_sem;
			/*
			$program = substr($content, strpos($content, "Programme actuel"));
			$program = substr($program, strpos($program, "dddefault")+11);
			$program = substr($program, 0, strpos($program, "</TD>"));
			$studies['program'] = $program;
			
			*/
			//error_log($content);
			//return ($studies);
		} else {
			error_log('PROBLÈME');
			return (array());
		}
	}
	
	function getCourses ($semester = '', $code = '', $subject = '', $title = '') {
		$this->lfetch->cookies = $_SESSION['cookies'];
		$this->lfetch->debug = $this->debug;
		
		if ($_SESSION['referer']=='') {
			$this->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_StuMainMnu';
		} else {
			$this->lfetch->referer = $_SESSION['referer'];
		}
		
		$this->lfetch->protocol="https";
		
		$arguments['HostName'] = "capsuleweb.ulaval.ca";
		$arguments["RequestURI"] = "/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu";
		
		$error=$this->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log('Ligne 28');
			return (false);
		}
		
		$error = $this->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log('Ligne 35');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 42');
			return (false);
		}
		
		$this->lfetch->Close();
		
		$this->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_RegMnu';
		$arguments["RequestURI"] = "/pls/etprod7/bwskfcls.p_sel_crse_search";
		
		$error=$this->lfetch->Open($arguments);
	
		if ($error!="") {
			error_log('Ligne 54');
			return (false);
		}
		
		$error = $this->lfetch->SendRequest($arguments);
	
		if ($error!="") {
			error_log('Ligne 61');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 68');
			return (false);
		}
		
		$this->lfetch->Close();
		
		$this->lfetch->request_method="POST";
		
		$this->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfcls.p_sel_crse_search';
		
		$this->lfetch->Open($arguments);
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'p_term'				=>	$semester,
			  'p_calling_proc'		=>	'P_CrseSearch'
			  );
		
		$arguments["RequestURI"] = "/pls/etprod7/bwckgens.p_proc_term_date";
		
		$error=$this->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log('Ligne 91');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 98');
			return (false);
		}
		
		$this->lfetch->Close();
		
		$this->lfetch->referer = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwckgens.p_proc_term_date';
		$arguments["RequestURI"] = "/pls/etprod7/bwskfcls.P_GetCrse";
		
		$this->lfetch->Open($arguments);
		
		if ($code!='') {
			$subject = substr($code, 0, 3);
			$number = substr($code, 4, 4);
		} else {
			$number = '';
		}
		
		if ($subject=='') $subject = 'dummy';
		
		// Envoi du formulaire
		$arguments["PostValues"] = array(
			  'term_in'				=>	$semester,
			  'sel_subj'			=>	$subject,
			  'sel_day'				=>	'dummy',
			  'sel_schd'			=>	'%',
			  'sel_insm'			=>	'dummy',
			  'sel_camp'			=>	'%',
			  'sel_levl'			=>	'%',
			  'sel_sess'			=>	'%',
			  'sel_instr'			=>	'%',
			  'sel_ptrm'			=>	'%',
			  'sel_attr'			=>	'%',
			  'sel_crse'			=>	$number,
			  'sel_title'			=>	$title,
			  'sel_from_cred'		=>	'',
			  'sel_to_cred'			=>	'',
			  'begin_hh'			=>	'0',
			  'begin_mi'			=>	'0',
			  'end_hh'				=>	'23',
			  'end_mi'				=>	'59',
			  'end_ap'				=>	'x',
			  );
		
		$error=$this->lfetch->SendRequest($arguments);
		
		if ($error!="") {
			error_log('Ligne 145');
			return (false);
		}
		
		$headers=array();
		$error=$this->lfetch->ReadReplyHeaders($headers);
		if ($error!="") {
			error_log('Ligne 152');
			return (false);
		}
		
		$this->lfetch->debug = 1;
		
		$error = $this->lfetch->ReadWholeReplyBody($body);
		$response = utf8_encode(html_entity_decode($body));
		
		//error_log($response);
		$this->lfetch->debug = 0;
		$this->lfetch->Close();
		
		$_SESSION['referer'] = 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfcls.P_GetCrse';
		
		$response = str_replace("<br />\nS ", "", $response);
		
		if (strpos($response, "horaire et inscription")>1) {
			$this->lfetch->SaveCookies($cookies);
			
			$_SESSION['cookies'] = $cookies;
			
			?><code><?php echo HtmlSpecialChars($response); ?></code><?php
			/*
			$content = substr($response, strpos($response, "<DIV class=\"pagebodydiv\">"));
			$content = substr($content, strpos($content, "<TABLE"));
			$content = substr($content, 0, strpos($content, "<!--  ** START OF twbkwbis.P_CloseDoc **  -->"));
			$content = str_replace("<br />", "", $content);
			$content = str_replace(":</TH>", "</TH>", $content);
			
			// Tri des données
			$studies = array();
			
			$program = substr($content, strpos($content, "Programme<"));
			$program = substr($program, strpos($program, "dddefault")+11);
			$program = substr($program, 0, strpos($program, "</TD>"));
			$studies['program'] = $program;
			
			$bachelor = substr($content, strpos($content, "Programme actuel"));
			$bachelor = substr($bachelor, strpos($bachelor, "dddefault")+11);
			$bachelor = substr($bachelor, 0, strpos($bachelor, "</TD>"));
			$studies['bachelor'] = $bachelor;
			
			$cycle = substr($content, strpos($content, ">Cycle"));
			$cycle = substr($cycle, strpos($cycle, "dddefault")+11);
			$cycle = substr($cycle, 0, strpos($cycle, "</TD>"));
			$studies['cycle'] = $cycle;
			
			$adm_semester = substr($content, strpos($content, "Session d'admission"));
			$adm_semester = substr($adm_semester, strpos($adm_semester, "dddefault")+11);
			$adm_semester = substr($adm_semester, 0, strpos($adm_semester, "</TD>"));
			$studies['adm_semester'] = $adm_semester;
			
			$adm_type = substr($content, strpos($content, "Type d'admission"));
			$adm_type = substr($adm_type, strpos($adm_type, "dddefault")+11);
			$adm_type = substr($adm_type, 0, strpos($adm_type, "</TD>"));
			$studies['adm_type'] = $adm_type;
			
			$major = substr($content, strpos($content, ">Majeure"));
			$major = substr($major, strpos($major, "dddefault")+11);
			$major = substr($major, 0, strpos($major, "</TD>"));
			$studies['major'] = $major;
			
			$concentrations = array();
			if (preg_match("#Concentration de majeure#", $content, $matches)) {
				$data = substr($content, strpos($content, "Concentration de majeure")-5);
				$data = substr($data, 0, strpos($data, "</TABLE>"));
				
				for ($n=0; $n<5; $n++) {
					if (strpos($data, "Concentration de majeure")<=0) break;
					
					$data = substr($data, strpos($data, "Concentration de majeure"));
					$concentration = substr($data, strpos($data, "dddefault")+11);
					$concentration = substr($concentration, 0, strpos($concentration, "</TD>"));
					$concentrations[] = $concentration;
					
					$data = substr($data, 20);
				}
			}
			$studies['concentrations'] = $concentrations;
			
			$status = substr($content, strpos($content, ">Statut"));
			$status = substr($status, strpos($status, "dddefault")+11);
			$status = substr($status, 0, strpos($status, "</TD>"));
			$studies['status'] = $status;
			
			$registered = substr($content, strpos($content, "Inscrit pour la session"));
			$registered = substr($registered, strpos($registered, "dddefault")+11);
			$registered = substr($registered, 0, strpos($registered, "</TD>"));
			$studies['registered'] = $registered;
			
			$first_sem = substr($content, strpos($content, "Première session de fréquentation"));
			$first_sem = substr($first_sem, strpos($first_sem, "dddefault")+11);
			$first_sem = substr($first_sem, 0, strpos($first_sem, "</TD>"));
			$studies['first_sem'] = $first_sem;
			
			$last_sem = substr($content, strpos($content, "Dernière session de fréquentation"));
			$last_sem = substr($last_sem, strpos($last_sem, "dddefault")+11);
			$last_sem = substr($last_sem, 0, strpos($last_sem, "</TD>"));
			$studies['last_sem'] = $last_sem;
			/*
			$program = substr($content, strpos($content, "Programme actuel"));
			$program = substr($program, strpos($program, "dddefault")+11);
			$program = substr($program, 0, strpos($program, "</TD>"));
			$studies['program'] = $program;
			
			*/
			//error_log($content);
			//return ($studies);
		} else {
			error_log('PROBLÈME');
			return (array());
		}
	}
	
	function updateCourses ($semester) {
		// Sélection des données
		$result = $this->db->get('programs_courses');
		
		$courses = $result->result_array();
		
		if ($courses!=array()) {
			foreach ($courses as $course) {
				$this->db->where(array('id'=>$course['id']));
				$result = $this->db->get('courses');
		
				$course2 = $result->row_array();
				if ($course2==array()) {
					$this->lcapsule->fetchCourse($course['id'], $semester);
				}
			}
		}
	}
	
	/*
	function updateCourses () {
		// Sélection des données
		$this->db->where(array('credits'=>'0'));
		$result = $this->db->get('courses');
		
		$courses = $result->result_array();
		$semester = '201109';
		
		if ($courses!=array()) {
			foreach ($courses as $course) {
				$this->db->delete('courses', array('id'=>$course['id']));
				
				$this->fetchCourse($course['id'], $semester);
			}
		}
	}
	*/
	function getPrograms ($code = '') {
		// Sélection des données
		if ($code != '') {
			$this->db->where(array('code'=>$code, 'active'=>'1'));
		} else {
			$this->db->where(array('active'=>'1'));
		}
		
		$this->db->order_by('code asc');
		
		$result = $this->db->get('registration_programs');
		
		$programs = $result->result_array();
		
		if ($code != '') {
			return ($programs[0]);
		} else {
			return ($programs);
		}
	}
	
	function getProgram ($code) {
		return ($this->getPrograms($code));
	}
	
	function getProgramByName ($name) {
		// Sélection des données
		$this->db->where(array('title'=>$name, 'active'=>'1'));
		
		$result = $this->db->get('registration_programs');
		
		$program = $result->row_array();
		
		return ($program);
	}
	
	function getProgramSections ($program) {
		// Sélection des données
		$this->db->where(array('program'=>$program, 'parent'=>'0'));
		
		$this->db->order_by('order asc, code asc');
		
		$result = $this->db->get('programs_sections');
		
		$sections = $result->result_array();
		$programs_sections = array();
		
		foreach ($sections as $section) {
			$this->db->where(array('program'=>$program, 'parent'=>$section['id']));
			$result = $this->db->get('programs_sections');
			
			$children = $result->result_array();
			$children2 = array();
			
			foreach ($children as $child) {
				$this->db->where(array('program'=>$program, 'parent'=>$child['id']));
				$result = $this->db->get('programs_sections');
				
				$children3 = $result->result_array();
				
				$child['children'] = $children3;
				$children2[] = $child;
			}
			
			$section['children'] = $children2;
			
			$programs_sections[] = $section;
		}
		
		if ($sections!=array()) {
			// Renvoi du paramètre
			return ($programs_sections);
		} else {
			return (array());
		}
	}
	
	function addProgramCourses ($courses) {
		if ($this->db->insert_batch('programs_courses', $courses)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function addCourse ($course) {
		// Recherche d'un cours existant
		$this->db->where(array('id'=>$course['id']));
		$result = $this->db->get('courses');
		if ($result->row_array() == array()) {
			// Enregistrement du cours
			if ($this->db->insert('courses', $course)) {
				return (true);
			} else {
				return (false);
			}
		} else {
			$this->db->where(array('id'=>$course['id']));
			unset($course['id']);
			
			if ($this->db->update('courses', $course)) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	function addClass ($class) {
		// Recherche d'une classe existante
		$this->db->where(array('nrc'=>$class['nrc']));
		$result = $this->db->get('classes');
		if ($result->row_array() == array()) {
			// Enregistrement du cours
			if ($this->db->insert('classes', $class)) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	function deleteCourseClasses ($code, $semester) {
		if ($this->db->delete('classes', array('idcourse'=>$code[0]."-".$code[1], 'semester'=>$semester))) {
			return (true);
		} else {
			return (false);
		}
	}
}
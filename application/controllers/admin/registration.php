<?php

class Registration extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Registration() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		$this->load->library('lfetch');
		$this->load->library('lcapsule');
		
		// Chargement des modèles
		$this->load->model('mCourses');
		$this->load->model('mRegistration');
		$this->load->model('mUser');
		
		// Vérification de la connexion
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') redirect('login');
		
		// Vérification que l'utilisateur soit administrateur
		if (isset($_SESSION['cap_iduser']) and $_SESSION['cap_iduser']!='alcle8') redirect('welcome');
 		
		// Sélection des données de l'utilisateur
		if (isset($_SESSION['cap_iduser'])) $this->user = $this->mUser->info();
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function index () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
		
		$data['programs'] = $this->mRegistration->getPrograms();
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/registration/programs', $data, true)));		
		echo "setPageInfo('admin/registration');setPageContent(\"".addslashes($content)."\");";
		echo "$('.post-content table tr:even').css('backgroundColor', '#dae6f1');";
	}
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/registration/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
	}
	
	function program () {
		$program_code = $this->uri->segment(4);
		
		$data = array();
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
		
		$data['program'] = $this->mRegistration->getProgram($program_code);
		$data['sections'] = $this->mRegistration->getProgramSections($program_code);
		$program_courses = $this->mCourses->getProgramCourses($program_code);
		$data['program_courses'] = array();
		foreach ($program_courses as $course) {
			if (isset($data['program_courses'][$course['category']])) {
				$data['program_courses'][$course['category']][] = $course;
			} else {
				$data['program_courses'][$course['category']] = array();
				$data['program_courses'][$course['category']][] = $course;
			}
		}
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/registration/program', $data, true)));
		echo "setPageInfo('admin/registration/program');setPageContent(\"".addslashes($content)."\");";
		echo "$('.post-content table tr:even').css('backgroundColor', '#dae6f1');";
	}
	
	function addCourses() {
		$program_code = $this->uri->segment(4);
		
		$data = array();
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
				
		$data['program'] = $this->mRegistration->getProgram($program_code);
		$data['sections'] = $this->mRegistration->getProgramSections($program_code);
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/registration/add-courses', $data, true)));
		echo "setPageInfo('admin/registration/addcourses');setPageContent(\"".addslashes($content)."\");";
	}
	
	function s_getCategories() {
		$program_name = $this->uri->segment(5);
		
		include("./programs/".$program_name.".php");
		
		$categories = array();
		
		foreach ($program as $item) {
			foreach ($item as $subcat) {
				foreach ($subcat['categories'] as $category) {
					//error_log("CAT:".print_r($category, true));
					//error_log("KEY:".print_r(key($subcat['categories']), true));
					//error_log(' ');
				}
			}
		}
		
		ob_start();
		
		?><option value=""> </option><?php
		
		foreach ($categories as $category) {
			?><option value="<?php echo $category; ?>"> <?php echo $category; ?></option><?php
		}
		
		$content = str_replace("\r", "", str_replace("\n", "", ob_get_clean()));
		
		?>$('#category').html('<?php echo addslashes($content); ?>');<?php
	}
	
	function s_addcourses () {
		$program = strtoupper($this->input->post('program'));
		$category = trim(strtolower($this->input->post('category')));
		$optional = $this->input->post('optional');
		if ($optional=='') $optional = '0';
		$type = $this->input->post('type');
		$data = trim($this->input->post('data'));
		
		$courses = array();
		$credits = 0;
		if ($type=='normal') {
			$lines = explode("\n", $data);
			
			foreach ($lines as $line) {
				$course = array();
				
				$line = explode("\t", $line);
				
				$course = array(
								'id'		=>	trim(strtoupper($line[0])),
								'program'	=>	$program,
								'category'	=>	$category,
								'optional'	=>	$optional,
								);
				
				$courses[] = $course;
			}
		} else {
			$lines = explode(",", $data);
			
			foreach ($lines as $line) {
				$course = array();
				
				if (strpos($line, " à ")>-1) {
					$line = trim($line);
					$code = substr($line, 0, 3);
					$number1 = substr($line, 4, 4);
					$number2 = substr($line, strrpos($line, "-")+1);
					
					for ($n=$number1; $n<($number2+1); $n++) {
						$course = array(
								'id'		=>	($code."-".$n),
								'program'	=>	$program,
								'category'	=>	$category,
								'optional'	=>	$optional,
								);
						
						$courses[] = $course;
					}
				} else {
					$course = array(
									'id'		=>	trim(strtoupper($line)),
									'program'	=>	$program,
									'category'	=>	$category,
									'optional'	=>	$optional,
									);
					
					$courses[] = $course;
				}
			}
		}
		
		if ($this->mRegistration->addProgramCourses($courses)) {
			?><script language="javascript">top.resultMessage("<?php echo count($courses); ?> cours ajoutés au schéma de programme.");top.$('#data').val('');top.statusAdd();</script><?php
		} else {
			?><script language="javascript">top.errorMessage("Une erreur est survenue durant l'analyse des données.");</script><?php
		}
	}
	
	function s_convertRepertoire () {
		$data = $this->input->post('data_repertoire');
		
		$data = explode("Cycle(s):", $data);
		
		$codes = array();
		
		$n = 0;
		foreach ($data as $segment) {
			if ($n == 0) {
				$code = str_replace(" ", "-", strtoupper(trim(substr($segment, 0, strpos($segment, " - ")))));
			} else {
				$segment = substr($segment, strpos($segment, "Faculté:")+30);
				$segment = substr($segment, strpos($segment, " - ")-9);
				
				$code = str_replace(" ", "-", strtoupper(trim(substr($segment, 0, strpos($segment, " - ")))));
			}
			
			if ($code!='') $codes[] = $code;
			
			$n++;
		}
		
		?><script language="javascript">top.$('#data').val('<?php echo implode(", ", $codes); ?>');</script><?php
	}
	
	function updateCourses () {
		$data = array();
		$data['user'] = $this->user;
		$data['mobile_browser'] = $this->lmobile->isMobileBrowser();
		
		$data['semesters'] = array(
						   '201109'	=>	'Automne 2011',
						   '201201'	=>	'Hiver 2012',
						   '201205'	=>	'Été 2012',
						   '201209'	=>	'Automne 2012',
						   '201301'	=>	'Hiver 2013'
						   );
		
		// Chargement de la page
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('admin/registration/update-courses', $data, true)));
		echo "setPageInfo('admin/registration/updatecourses');setPageContent(\"".addslashes($content)."\");";
	}
	
	function s_updateCoursesData () {
		$semester = $this->input->post('semester');
		
		$this->mCourses->updateCoursesData($semester);
	}
	
	function updateSpots () {
		$classes = $this->mCourses->getClasses('MNG-1001');
		
		foreach ($classes as $class) {
			$this->lcapsule->updateClassSpots($class['nrc'], '201201');
		}
	}
	
	function coursesBLMO () {
		$data = array();
		$data['section'] = 'admin';
		$data['page'] = 'courses-blmo';
		$data['user'] = $this->user;
		$data['mobile'] = $this->mobile;
		
		// Création des variables d'inscription
		$current_semester = '201109';
		$data['current_semester'] = $current_semester;
		$data['semester'] = '201201';
		$data['deadline_edit_selection'] = '20110913';
		$data['deadline_drop_nofee'] = '20110920';
		$data['deadline_drop_fee'] = '20111115';
		
		// Chargement de l'entête
		//if ($this->mobile!=1) $this->load->view('header', $data); else $this->load->view('m-header', $data);
		
		$program_code = 'B-LMO';
		
		// Sélection des sections du programme
		$sections = $this->mRegistration->getProgramSections($program_code);

		// Inclusion du plugin du programme
		switch ($data['user']['program']) {
			case 'B études int.-langues modernes':
				//include ('./programs/'.'b-lmo'.'.php');
			break;
			case 'B génie civil':
			//	include ('./programs/'.'b-gci'.'.php');
			break;
			case 'B communication publique':
			//	include ('./programs/'.'b-com'.'.php');
			break;
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
		$this->load->view('admin/registration/courses-blmo', $data);
				
		// Chargement du menu
		//$this->load->view('registration/m-menu', $data);
		
		// Chargement du bas de page
		//if ($this->mobile!=1) $this->load->view('footer', $data); else $this->load->view('m-footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
<?php

class Cache extends CI_Controller {
	var $mobile = 0;
	
	function Cache () {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des librairies
		$this->load->library('lcapsule');
		$this->load->library('lfetch');
		
		// Chargement des modèles
		$this->load->model('mBots');
		$this->load->model('mCourses');
		$this->load->model('mUser');
		$this->load->model('mUsers');
		
		if (!isset($_SESSION['cap_iduser']) and $this->uri->segment(1)!='login' and $this->uri->segment(2)!='s_login') redirect('login');
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}
	
	function s_deleteCache() {
		$name = $this->input->post('name');
		
		if ($this->mCache->deleteCache($name)) {
			?>statusRefreshData(1);<?php
		} else {
			?>statusRefreshData(2);<?php
		}
	}
	
	function s_getLocalStorageVars () {
		$dataList = array(
						  'data|studies,summary',
						  'data|studies,details',
						  'data|studies,report',
						  'data|schedule',
						  'data|fees',
						  'data|holds'
						  );
		
		ob_start();
		
		?>localStorageVars = new Array(<?php
		$num = 0;
		
		foreach ($dataList as $item) {
			if ($num != 0) echo ', ';
			
			echo '\''.$item.'\'';
			
			$num++;
		}
		
		?>);storeLocalData();<?php
		
		$content = ob_get_clean();
		
		echo $content;
	}
	
	function s_getLocalStorageValue () {
		$var = urldecode($this->uri->segment(3));
		
		$data = '';
		switch ($var) {
			case 'data|studies,summary':
				$data = json_encode($this->mUser->getStudies());
			break;
			case 'data|studies,details':
				$data['sections'] = $this->mUser->getCoursesSections($_SESSION['cap_iduser']);
				$data['courses'] = $this->mUser->getCourses($_SESSION['cap_iduser']);
				
				$cache = $this->mCache->getCache('data|studies,details');
				
				if ($cache!=array()) {
					$cache['value'] = unserialize($cache['value']);
					$data['other_courses'] = $cache['value']['other_courses'];
				}
				
				$data = json_encode($data);
			break;
			case 'data|studies,report':
				$cache = $this->mCache->getCache('data|studies,report');
			
				if ($cache!=array()) {
					$data = unserialize($cache['value']);
				}
				
				$data = json_encode($data);
			break;
			case 'data|schedule':
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|schedule,semesters');
				
				if ($cache!=array()) {
					$data['semesters'] = unserialize($cache['value']);
				}
				
				$data['schedule'] = array();
				
				foreach ($data['semesters'] as $semester => $name) {
					if (isset($name['title'])) {
					} else {
						// Vérification de l'existence des sessions en cache
						$cache = $this->mCache->getCache('data|schedule['.$semester.']');
						
						if ($cache!=array()) {
							$data['schedule'][$semester] = unserialize($cache['value']);
						}
					}
				}
								
				$data = json_encode($data);
			break;
			case 'data|fees':
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|fees,semesters');
				
				if ($cache!=array()) {
					$data['semesters'] = unserialize($cache['value']);
				}
				
				$data['fees'] = array();
				
				foreach ($data['semesters'] as $semester => $name) {
					// Vérification de l'existence des sessions en cache
					$cache = $this->mCache->getCache('data|fees['.$semester.']');
					
					if ($cache!=array()) {
						$data['fees'][$semester] = unserialize($cache['value']);
					}
				}
								
				$data = json_encode($data);
			break;
			case 'data|holds':
				$cache = $this->mCache->getCache('data|holds');
		
				if ($cache!=array()) {
					$data = unserialize($cache['value']);
				}
				
				$data = json_encode($data);
			break;
		}
		
		error_log($data);
		
		?>writeLocalData('<?php echo $var; ?>', '<?php echo addslashes($data); ?>');<?php
	}
	
	// Chargement des données de l'utilisateur depuis Capsule
	function s_reloadData () {
		$reload_name = $this->input->post('name');
		$auto = $this->input->post('auto');
		
		ob_start();
		
		// Augmentation de la limitation de mémoire
		ini_set('memory_limit', '200M');
		
		$current_semester = '201109';
		
		// Vérification que l'utilisateur soit connecté
		if (!isset($_SESSION['cap_iduser'])) {
			$this->mErrors->addError('reload-data', 'iduser not set');
			
			?>statusReload(2, 'Erreur lors du chargement des données !');<?php
			die();
		}
		
		$error = 0;
		$dataErrors = array();
		
		// Test de connexion à Capsule
		$this->lcapsule->testConnection();
		
		switch ($reload_name) {
			case 'data|studies,summary':
				// Suppression des données en cache
				$this->mCache->deleteCache('data|studies,summary', 1);
				$this->mUser->deleteStudies();
				
				// Chargement du programme d'études
				$studies = $this->lcapsule->getStudies($current_semester);
				
				if ($studies['program']=='Programme pré-Banner') {
					// Erreur : programme inexistant (ex : employés Ulaval)
					?>statusReload(2, 'Votre programme d\'études ne peut pas être analysé par Pilule (err: programme pré-Banner).');<?php
					return (false);
				}
				
				if (is_array($studies)) {
					// Enregistrement des données
					if (!$this->mUser->setStudies($studies)) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'studies-summary : setUserStudies');
						
						$error = 1;
						
						// Mise en cache des données
						$this->mCache->addCache('data|studies,summary', $studies['rawdata'], '1');
					} else {
						// Mise en cache des données
						$this->mCache->addCache('data|studies,summary', $studies['data']);
					}
				} else {
					$this->mErrors->addError('reload-data', 'studies-summary : parsing error');
					
					$error = 1;
				}
			case 'data|studies,details':
				// Suppression des données en cache
				$this->mCache->deleteCache('data|studies,details', 1);
				$this->mCache->deleteCache('data|studies,details,2', 1);
				$this->mCache->deleteCache('data|studies,details,3', 1);
				
				// Vérification de l'existence des données
				$studies = $this->mUser->getStudies();
				
				// Chargement du rapport de cheminement
				if ($studies!=array() and $studies['code_permanent']!='') {
					$fetchDetails1 = false;
					$response = $this->lcapsule->getStudiesDetails($current_semester, false);
				} else {
					$fetchDetails1 = true;
					$response = $this->lcapsule->getStudiesDetails($current_semester);
				}
				
				if (is_array($response)) {
					if ($fetchDetails1) {
						// Enregistrement des données
						if (!$this->mUser->setStudies($response['studies'])) {
							// Enregistrement de l'erreur
							$this->mErrors->addError('reload-data', 'studies-details : setUserStudies');
							
							$error = 1;
							$dataErrors[] = $item;
							
							// Mise en cache des données
							$this->mCache->addCache('data|studies,details,1', $response['data']['details1'], '1');
						} else {
							// Mise en cache des données
							$this->mCache->addCache('data|studies,details,1', $response['data']['details1']);
						}
					}
					
					// Mise en cache des données
					$this->mCache->addCache('data|studies,details,2', $response['data']['details2']);
					$this->mCache->addCache('data|studies,details,3', $response['data']['details3']);
					$this->mCache->addCache('data|studies,details', $response['details']);
					
					if ($this->mobile == 1) $_SESSION['datacheck']['studies'] = 1;
				} else {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'studies-details : parsing error');
					
					$error = 1;
				}
			break;
			case 'data|studies,report':
				// Suppression des données en cache
				$this->mCache->deleteCache('data|studies,report', 1);
				
				// Chargement du relevé de notes
				$response = $this->lcapsule->getReport();
				
				if (is_array($response)) {
					// Enregistrement des données
					if (!$this->mUser->setStudies($response['studies'])) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'studies-report : setUserStudies');
						
						$error = 1;
						$dataErrors[] = $item;
						
						// Mise en cache des données
						$this->mCache->addCache('data|studies,report,rawdata', $response['rawdata'], '1');
					} else {
						// Mise en cache des données
						$this->mCache->addCache('data|studies,report', $response['report']);
						
						if ($this->mobile == 1) $_SESSION['datacheck']['report'] = 1;
					}
				} else {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'studies-report : parsing error');
					
					$error = 1;
				}
			break;
			case 'data|schedule,semesters':
				// Suppression des données en cache
				$this->mUser->deleteSchedule();
				
				// Chargement des horaires de cours
				$result = $this->lcapsule->getSchedule();
				
				if ($result===false) {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'schedule-semesters : parsing error');
					
					$error = 1;
				} else {
					if ($this->mobile == 1) $_SESSION['datacheck']['schedule'] = 1;
				}
			break;
			case 'data|fees,summary':
				// Suppression des données en cache
				$this->mUser->deleteFeesSummary();
				
				// Chargement des détails des frais de scolarité
				$result = $this->lcapsule->getFeesSummary();
				
				if ($result===false) {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'fees : parsing error');
					
					$error = 1;
				} else {
					if ($this->mobile == 1) $_SESSION['datacheck']['fees'] = 1;
				}
			break;
			case 'data|holds':
				// Suppression des données en cache
				$this->mCache->deleteCache('data|holds', 1);
				
				// Vérification des blocages
				$this->lcapsule->checkHolds();
				
				if ($result===false) {
					// Enregistrement de l'erreur
					$this->mErrors->addError('reload-data', 'check-holds : parsing error');
					
					$error = 1;
				}
			break;
		}
		
		if ($error == 1) {
			// Test de la connexion à Capsule
			$this->lcapsule->testConnection();
		
			// Second essai pour les problèmes de chargement
			$error = 0;
			switch ($reload_name) {
				case 'data|studies,summary':
					// Suppression des données en cache
					$this->mCache->deleteCache('data|studies,summary', 1);
					$this->mUser->deleteStudies();
					
					// Chargement du programme d'études
					$studies = $this->lcapsule->getStudies($current_semester);
					
					if ($studies['program']=='Programme pré-Banner') {
						// Erreur : programme inexistant (ex : employés Ulaval)
						?>statusReload(2, 'Votre programme d\'études ne peut pas être analysé par Pilule (err: programme pré-Banner).');<?php
						return (false);
					}
					
					if (is_array($studies)) {
						// Enregistrement des données
						if (!$this->mUser->setStudies($studies)) {
							// Enregistrement de l'erreur
							$this->mErrors->addError('reload-data', 'studies-summary : setUserStudies');
							
							$error = 1;
							
							// Mise en cache des données
							$this->mCache->addCache('data|studies,summary', $studies['rawdata'], '1');
						} else {
							// Mise en cache des données
							$this->mCache->addCache('data|studies,summary', $studies['data']);
						}
					} else {
						$this->mErrors->addError('reload-data', 'studies-summary : parsing error');
						
						$error = 1;
					}
				case 'data|studies,details':
					// Suppression des données en cache
					$this->mCache->deleteCache('data|studies,details', 1);
					$this->mCache->deleteCache('data|studies,details,2', 1);
					$this->mCache->deleteCache('data|studies,details,3', 1);
					
					// Vérification de l'existence des données
					$studies = $this->mUser->getStudies();
					
					// Chargement du rapport de cheminement
					if ($studies!=array() and $studies['code_permanent']!='') {
						$fetchDetails1 = false;
						$response = $this->lcapsule->getStudiesDetails($current_semester, false);
					} else {
						$fetchDetails1 = true;
						$response = $this->lcapsule->getStudiesDetails($current_semester);
					}
					
					if (is_array($response)) {
						if ($fetchDetails1) {
							// Enregistrement des données
							if (!$this->mUser->setStudies($response['studies'])) {
								// Enregistrement de l'erreur
								$this->mErrors->addError('reload-data', 'studies-details : setUserStudies');
								
								$error = 1;
								
								// Mise en cache des données
								$this->mCache->addCache('data|studies,details,1', $response['data']['details1'], '1');
							} else {
								// Mise en cache des données
								$this->mCache->addCache('data|studies,details,1', $response['data']['details1']);
								
								if ($this->mobile == 1) $_SESSION['datacheck']['studies'] = 1;
							}
						}
						
						// Mise en cache des données
						$this->mCache->addCache('data|studies,details,2', $response['data']['details2']);
						$this->mCache->addCache('data|studies,details,3', $response['data']['details3']);
						$this->mCache->addCache('data|studies,details', $response['details']);
					} else {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'studies-details : parsing error');
						
						$error = 1;
					}
				break;
				case 'data|studies,report':
					// Suppression des données en cache
					$this->mCache->deleteCache('data|studies,report', 1);
					
					// Chargement du relevé de notes
					$response = $this->lcapsule->getReport();
					
					if (is_array($response)) {
						// Enregistrement des données
						if (!$this->mUser->setStudies($response['studies'])) {
							// Enregistrement de l'erreur
							$this->mErrors->addError('reload-data', 'studies-report : setUserStudies');
							
							$error = 1;
							
							// Mise en cache des données
							$this->mCache->addCache('data|studies,report,rawdata', $response['rawdata'], '1');
						} else {
							// Mise en cache des données
							$this->mCache->addCache('data|studies,report', $response['report']);
							
							if ($this->mobile == 1) $_SESSION['datacheck']['report'] = 1;
						}
					} else {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'studies-report : parsing error');
						
						$error = 1;
					}
				break;
				case 'data|schedule,semesters':
					// Suppression des données en cache
					$this->mUser->deleteSchedule();
					
					// Chargement des horaires de cours
					$result = $this->lcapsule->getSchedule();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'schedule-semesters : parsing error');
						
						$error = 1;
					} else {
						if ($this->mobile == 1) $_SESSION['datacheck']['schedule'] = 1;
					}
				break;
				case 'data|fees,summary':
					// Suppression des données en cache
					$this->mUser->deleteFeesSummary();
					
					// Chargement des détails des frais de scolarité
					$result = $this->lcapsule->getFeesSummary();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'fees : parsing error');
						
						$error = 1;
					} else {
						if ($this->mobile == 1) $_SESSION['datacheck']['fees'] = 1;
					}
				break;
				case 'data|holds':
					// Suppression des données en cache
					$this->mCache->deleteCache('data|holds', 1);
					
					// Vérification des blocages
					$this->lcapsule->checkHolds();
					
					if ($result===false) {
						// Enregistrement de l'erreur
						$this->mErrors->addError('reload-data', 'check-holds : parsing error');
						
						$error = 1;
					}
				break;
			}
		}
		
		ob_clean();

		$this->mHistory->save('reload-data : '.$reload_name);
		
		if ($error==0) {
			// Renvoi d'un résultat positif
			?>statusReload(1, <?php if ($auto == 1) echo $auto; else echo 0; ?>);<?php
		} else {
			// Renvoi d'une erreur
			?>statusReload(2, <?php if ($auto == 1) echo $auto; else echo 0; ?>, 'Une erreur est survenue durant le chargement des données.');<?php
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
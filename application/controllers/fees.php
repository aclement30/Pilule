<?php

class Fees extends CI_Controller {
	var $mobile = 0;
	var $user;
	
	function Fees() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des modèles
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
		$data['semester'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('fees-summary');
		
		if ($data['semester']=='') {
			if (isset($_SESSION['fees_current_semester'])) {
				$data['semester'] = $_SESSION['fees_current_semester'];
			} else {
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|fees,semesters');
				
				if ($cache!=array()) {
					$semesters = unserialize($cache['value']);
					
					if ($semesters!=array()) {
						$data['semester'] = key($semesters);
						$_SESSION['fees_current_semester'] = $data['semester'];
					}
				}
			}
		} else {
			$_SESSION['fees_current_semester'] = $data['semester'];
		}
		
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees,summary');
		
		if ($cache!=array()) {
			$data['summary'] = unserialize($cache['value']);
			$data['cache_date'] = $cache['date'];
			$data['cache_time'] = $cache['time'];
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|fees,summary';
			}
			
			if ($data['summary']!=array()) {
				// Chargement de la page
				$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('fees/summary', $data, true)));
			} else {
				// Chargement de la page d'erreur
				$data['title'] = 'État de compte';
				$data['reload_name'] = 'data|fees,summary';
				
				$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
			}
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'État de compte';
			$data['reload_name'] = 'data|fees,summary';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('fees/summary');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|holds');
		
		if ($cache!=array()) {
			$data['holds'] = unserialize($cache['value']);
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|holds';
			}
		} else {
			$data['holds'] = array();
		}
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('fees/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
		if ($this->mobile == 1) echo "$('h2.title').after($('#sidebar'));";
	}
	
	function details () {
		$data = array();
		$data['user'] = $this->user;
		$data['semester'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('fees-details');
		
		if ($data['semester']=='') {
			if (isset($_SESSION['fees_current_semester'])) {
				$data['semester'] = $_SESSION['fees_current_semester'];
			} else {
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|fees,semesters');
				
				if ($cache!=array()) {
					$semesters = unserialize($cache['value']);
					
					if ($semesters!=array()) {
						$data['semester'] = key($semesters);
						$_SESSION['fees_current_semester'] = $data['semester'];
					}
				}
			}
		} else {
			$_SESSION['fees_current_semester'] = $data['semester'];
		}
		
		// Vérification de l'existence des sessions en cache
		$cache = $this->mCache->getCache('data|fees['.$data['semester'].']');
		
		if ($cache!=array()) {
			$data['fees'] = unserialize($cache['value']);
			$data['cache_date'] = $cache['date'];
			$data['cache_time'] = $cache['time'];
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|fees,summary';
			}
		}
		
		$cache = $this->mCache->getCache('data|fees,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
		}
		
		if (isset($data['fees']) and $data['fees']!=array()) {
			// Chargement de la page
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('fees/details', $data, true)));
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Relevé par session';
			$data['reload_name'] = 'data|fees,summary';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('fees/details');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
}
<?php

class mHistory extends CI_Model {
	
	function mHistory () {
		parent::__construct();
	}
	
	function getCache ($name) {
		// Sélection des données
		$this->db->where(array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name));
		$result = $this->db->get('cache');
		
		$cache = $result->row_array();
		
		if ($cache!=array() and $cache['timestamp']>(time()-3600*24)) {
			return ($cache);
		} else {
			return (array());
		}
	}
	
	function save ($description) {
		$item = array(
					  'description'	=>	$description,
					  'timestamp'	=>	time(),
					  'date'		=>	date('Ymd'),
					  'time'		=>	date('H:i')
					  );
		
		if (isset($_SESSION['cap_iduser'])) {
			$item['idul'] = $_SESSION['cap_iduser'];
		} elseif (isset($_SESSION['temp_iduser'])) {
			$item['idul'] = $_SESSION['temp_iduser'];
		} else {
			$item['idul'] = '';
		}
		
		if ($this->db->insert('history', $item)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Fonctions de statistiques
	function getLogins ($days) {
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description'=>'login', 'idul !='=>'alcle8'));
		$result = $this->db->get('history');
		$logins = $result->result_array();
		
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description'=>'loading-data', 'idul !='=>'alcle8'));
		$result = $this->db->get('history');
		$loadings = $result->result_array();
		
		if ($logins!=array()) {
			return (array($logins, $loadings));
		} else {
			return (array());
		}
	}
	
	function getPages ($days) {
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description !='=>'login', 'idul !='=>'alcle8'));
		$result = $this->db->get('history');
		$pages = $result->result_array();

		if ($pages!=array()) {
			return ($pages);
		} else {
			return (array());
		}
	}
	
	function getRegistrationStats ($days) {
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description'=>'registration-courses', 'idul !='=>'alcle8'));
		
		$result = $this->db->get('history');
		$step1 = $result->result_array();
		
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description'=>'registration-register-courses', 'idul !='=>'alcle8'));
		
		$result = $this->db->get('history');
		$step2_register = $result->result_array();
		
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description'=>'registration-remove-courses', 'idul !='=>'alcle8'));
		
		$result = $this->db->get('history');
		$step2_remove = $result->result_array();
		
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'description'=>'registration-result', 'idul !='=>'alcle8'));
		
		$result = $this->db->get('history');
		$step3 = $result->result_array();
		
		return (array($step1, $step2_register, $step2_remove, $step3));
	}
}
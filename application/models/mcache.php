<?php

class mCache extends CI_Model {
	function mCache () {
		parent::__construct();
	}
	
	// Suppression des données en cache depuis plus de 24h
	function cleanCache () {
		/*
		// Sélection des données
		$this->db->where(array('timestamp <'=>(time()-3600*36), 'protected'=>'0'));
		
		if ($this->db->delete('cache')) {
			return (true);
		} else {
			return (false);
		}
		*/
	}
	
	// Sélection des données en cache
	function getCache ($name) {
		// Sélection des données
		$this->db->where(array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name));
		$result = $this->db->get('cache');
		
		$cache = $result->row_array();
		
		if ($cache!=array()) {
			return ($cache);
		} else {
			return (array());
		}
	}
	
	// Mise en cache de données
	function addCache ($name, $value, $protected = '0') {
		// Sélection des données
		$this->db->where(array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name));
		$result = $this->db->get('cache');
		
		$cache = $result->row_array();
		
		if (is_array($value)) $value = serialize($value);
		
		if ($cache!=array()) {
			$this->updateCache($name, $value);
		} else {
			//if (isset($_SESSION['data-storage']) and $_SESSION['data-storage'] == 'yes') $protected = '1';
			
			if ($this->db->insert('cache', array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name, 'value'=>$value, 'date'=>date('Ymd'), 'time'=>date('H:i'), 'timestamp'=>time(), 'protected'=>$protected))) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	function lockUserCache () {
		// Sélection des données
		$this->db->where(array('idul'=>$_SESSION['cap_iduser']));
	
		if ($this->db->update('cache', array('protected'=>'1'))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function unlockUserCache ($name) {
		if ($idul == 'demo') return (true);
		
		// Sélection des données
		$this->db->where(array('idul'=>$_SESSION['cap_iduser']));
	
		if ($this->db->update('cache', array('protected'=>'0'))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Actualisation d'une donnée dans le cache
	function updateCache ($name, $value, $protected = '0') {
		// Vérification de l'existence du cache
		if ($this->getCache($name) == array()) {
			return ($this->addCache($name, $value, $protected));
		} else {
			// Sélection des données
			$this->db->where(array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name));
			
			//if (isset($_SESSION['data-storage']) and $_SESSION['data-storage'] == 'yes') $protected = '1';
			
			if ($this->db->update('cache', array('value'=>$value, 'date'=>date('Ymd'), 'time'=>date('H:i'), 'timestamp'=>time(), 'protected'=>$protected))) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	// Suppression de données dans le cache
	function deleteCache ($name, $forceDelete = 0) {
		if ($forceDelete == 1) {
			$param = array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name);
		} else {
			$param = array('idul'=>$_SESSION['cap_iduser'], 'name'=>$name, 'protected'=>'0');
		}
		
		if ($this->db->delete('cache', $param)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Suppression des données en cache de l'utilisateur
	function deleteUserCache ($idul) {
		if ($idul == 'demo') return (true);
		if ($this->db->delete('cache', array('idul'=>$idul, 'protected'=>'0'))) {
			return (true);
		} else {
			return (false);
		}
	}
}
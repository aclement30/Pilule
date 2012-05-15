<?php

class mErrors extends CI_Model {
	
	function mErrors () {
		parent::__construct();
	}
	
	function addError ($type, $description, $idul = '') {
		if ($idul=='') {
			if (isset($_SESSION['cap_iduser'])) {
				$idul = $_SESSION['cap_iduser'];
			} elseif (isset($_SESSION['temp_iduser'])) {
				$idul = $_SESSION['temp_iduser'];
			} else {
				$idul = '';
			}
		}
		
		// Enregistrement de l'erreur dans la base de données
		if ($this->db->insert('errors', array('idul'=>$idul, 'type'=>$type, 'description'=>$description, 'date'=>date('Ymd'), 'time'=>date('H:i'), 'timestamp'=>time(), 'ip'=>$_SERVER['REMOTE_ADDR']))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Fonctions de statistiques
	function getErrors ($days) {
		// Sélection des données
		$this->db->where(array('timestamp >=' => time()-3600*24*$days, 'idul !='=>'alcle8'));
		$result = $this->db->get('errors');
		$errors = $result->result_array();
		
		if ($errors!=array()) {
			return ($errors);
		} else {
			return (array());
		}
	}
}
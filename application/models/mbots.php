<?php

class mBots extends CI_Model {
	
	function mBots () {
		parent::__construct();
	}
	
	function getBot ($id) {
		return ($this->getBots($id));
	}

	function getBots ($id = '') {
		// Sélection des données
		if ($id!='') {
			$this->db->where(array('id'=>$id, 'active'=>'1'));
		} else {
			$this->db->where(array('active'=>'1'));
		}
		
		$result = $this->db->get('bots');
		
		$bots = $result->result_array();

		if ($id!='') {
			// Renvoi des données du bot
			return ($bots[0]);
		} else {
			// Renvoi de la liste des bots
			return ($bots);
		}
	}
}
<?php

class mUsers extends CI_Model {
	
	function mUsers () {
		parent::__construct();
	}
	
	function getUserByFbuid ($fbuid) {
		// Sélection des données de l'utilisateur
		$this->db->where('fbuid', $fbuid);
		$result = $this->db->get('users');
		
		$user = $result->row_array();

		if ($user!=array()) {
			// Utilisateur existant
			return ($user);
		} else {
			return (false);
		}
	}
	
	// Vérification de l'existence d'un utilisateur
	function userExists ($idul) {
		// Sélection des données de l'utilisateur
		$this->db->where('idul', $idul);
		$result = $this->db->get('users');
		
		$user = $result->row_array();

		if ($user!=array()) {
			// Utilisateur existant
			return (true);
		} else {
			return (false);
		}
	}
	
	function getUsers ($idul = '') {
		// Sélection des données
		if ($idul!='') {
			$this->db->where('idul', $idul);
		}
		
		$this->db->order_by('name');
		
		$result = $this->db->get('users');
		
		$users = $result->result_array();

		if ($idul!='') {
			// Renvoi de l'utilisateur
			return ($users[0]);
		} else {
			// Renvoi de la liste des utilisateurs
			return ($users);
		}
	}

	// Ajout d'un utilisateur
	function addUser ($user) {
		if ($this->db->insert('users', $user)) {
			return (true);
		} else {
			return (false);
		}
	}

	// Modification d'un utilisateur
	function editUser ($user) {
		$this->db->where('idul', $user['idul']);
		unset($user['idul']);
		
		if ($this->db->update('users', $user)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Suppression de l'utilisateur
	function removeUser ($idul) {
		// Suppression de l'utilisateur de la liste
		if ($this->db->delete('users', array('idul'=>$idul))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Recherche des modules de l'utilisateur
	function getDashboardModules ($default = true) {
		// Sélection des données
		if ($default) {
			$this->db->where(array('default'=>'1'));
			$this->db->order_by('order asc');
		} else {
			$this->db->order_by('description asc');
		}
		
		$result = $this->db->get('modules');
		
		$modules_list = $result->result_array();
		$modules = array();
		
		foreach ($modules_list as $module) {
			$module['data'] = unserialize($module['data']);
			$modules[] = $module;
		}
		
		if ($modules!=array()) {
			// Renvoi du paramètre
			return ($modules);
		} else {
			return (array());
		}
	}
	
	function getDashboardModule ($id) {
		// Sélection des données
		$this->db->where('id', $id);
		
		$result = $this->db->get('modules');
		
		$module = $result->row_array();
		$module['data'] = unserialize($module['data']);
		
		if ($module!=array()) {
			// Renvoi du paramètre
			return ($module);
		} else {
			return (array());
		}
	}
	
	// Fonctions de statistiques
	function getStatsUsers () {
		// Sélection des données
		$this->db->where(array('program !='=>'', 'faculty !='=>'', 'last_visit >='=>time()-3600*24*60));
		
		$result = $this->db->get('users');
		
		$users = $result->result_array();

		if ($users != array()) {
			// Renvoi de la liste des utilisateurs
			return ($users);
		}
	}
}
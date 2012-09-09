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
	
	// S�lection des donn�es en cache
	function getCache ($name) {
		// S�lection des donn�es
		$this->db->where(array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name));
		$result = $this->db->get('cache');
		
		$cache = $result->row_array();
		
		if ($cache!=array()) {
			return ($cache);
		} else {
			return (array());
		}
	}
	
	// Mise en cache de donn�es
	function addCache ($name, $value, $protected = '0') {
		// S�lection des donn�es
		$this->db->where(array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name));
		$result = $this->db->get('cache');
		
		$cache = $result->row_array();
		
		if (is_array($value)) $value = serialize($value);
		
		if ($cache!=array()) {
			$this->updateCache($name, $value);
		} else {
			//if (isset($_SESSION['data-storage']) and $_SESSION['data-storage'] == 'yes') $protected = '1';
			
			if ($this->db->insert('cache', array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name, 'value'=>$value, 'date'=>date('Ymd'), 'time'=>date('H:i'), 'timestamp'=>time(), 'protected'=>$protected))) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	function lockUserCache () {
		// S�lection des donn�es
		$this->db->where(array('idul'=>$this->session->userdata('pilule_user')));
	
		if ($this->db->update('cache', array('protected'=>'1'))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	function unlockUserCache ($name) {
		if ($idul == 'demo') return (true);
		
		// S�lection des donn�es
		$this->db->where(array('idul'=>$this->session->userdata('pilule_user')));
	
		if ($this->db->update('cache', array('protected'=>'0'))) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Actualisation d'une donn�e dans le cache
	function updateCache ($name, $value, $protected = '0') {
		// V�rification de l'existence du cache
		if ($this->getCache($name) == array()) {
			return ($this->addCache($name, $value, $protected));
		} else {
			// S�lection des donn�es
			$this->db->where(array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name));
			
			//if (isset($_SESSION['data-storage']) and $_SESSION['data-storage'] == 'yes') $protected = '1';
			
			if ($this->db->update('cache', array('value'=>$value, 'date'=>date('Ymd'), 'time'=>date('H:i'), 'timestamp'=>time(), 'protected'=>$protected))) {
				return (true);
			} else {
				return (false);
			}
		}
	}
	
	// Suppression de donn�es dans le cache
	function deleteCache ($name, $forceDelete = 0) {
		if ($forceDelete == 1) {
			$param = array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name);
		} else {
			$param = array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name, 'protected'=>'0');
		}
		
		if ($this->db->delete('cache', $param)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Suppression des donn�es en cache de l'utilisateur
	function deleteUserCache ($idul) {
		if ($idul == 'demo') return (true);
		if ($this->db->delete('cache', array('idul'=>$idul, 'protected'=>'0'))) {
			return (true);
		} else {
			return (false);
		}
	}

    // Ajout d'une requête Capsule
    function addRequest ($name, $md5 = '') {
        // Recherche d'une requête similaire
        $this->db->where(array('idul' => $this->session->userdata('pilule_user'), 'name' => $name));
        $result = $this->db->get('capsule_requests');

        $cache = $result->row_array();

        if ($cache!=array()) {
            // Actualisation de la requête
            $this->db->where(array('idul' => $this->session->userdata('pilule_user'), 'name' => $name));

            $this->db->update('capsule_requests', array('timestamp' => time(), 'md5' => $md5));
        } else {
            if ($this->db->insert('capsule_requests', array('idul'=>$this->session->userdata('pilule_user'), 'name'=>$name, 'md5'=>$md5, 'timestamp'=>time()))) {
                return (true);
            } else {
                return (false);
            }
        }
    }

    function requestExists ($name, $md5 = '') {
        // Recherche d'une requête similaire
        $this->db->where(array('idul' => $this->session->userdata('pilule_user'), 'name' => $name));
        $result = $this->db->get('capsule_requests');

        $request = $result->row_array();

        if (empty($request)) {
            return (false);
        }

        if (!empty($md5)) {
            if ($request['md5'] == $md5) {
                return (true);
            } else {
                return (false);
            }
        }
    }

    function isOutdated ($name, $min_timestamp) {
        // Recherche d'une requête similaire
        $this->db->where(array('idul' => $this->session->userdata('pilule_user'), 'name' => $name, 'timestamp >=' => $min_timestamp));
        $result = $this->db->get('capsule_requests');

        $request = $result->row_array();

        if (empty($request)) {
            return (true);
        } else {
            return (false);
        }
    }

    function getLastRequest ($name) {
        // Recherche d'une requête similaire
        $this->db->where(array('idul' => $this->session->userdata('pilule_user'), 'name' => $name));
        $result = $this->db->get('capsule_requests');

        $request = $result->row_array();

        if (empty($request)) {
            return (array());
        } else {
            return ($request);
        }
    }

    // Suppression des relevés de notes de l'étudiant
    function deleteRequests ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('capsule_requests', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }
}
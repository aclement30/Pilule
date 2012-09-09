<?php

class mTuitions extends CI_Model {
    function mTuitions () {
        parent::__construct();
    }

    // Ajout d'un semestre à l'utilisateur
    function addSemester ($semester) {
        if (!array_key_exists('idul', $semester)) $semester['idul'] = $this->session->userdata('pilule_user');
        if (is_array($semester['fees'])) $semester['fees'] = serialize($semester['fees']);
        if ($this->db->insert('stu_tuitions_semesters', $semester)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des semestres de frais d'un utilisateur
    function getSemesters ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);
        $this->db->order_by('semester DESC');

        $result = $this->db->get('stu_tuitions_semesters');

        $semesters = $result->result_array();
        foreach ($semesters as &$semester) {
            $semester['fees'] = unserialize($semester['fees']);
        }

        if ($semesters!=array()) {
            // Renvoi des données
            return ($semesters);
        } else {
            return (array());
        }
    }

    // Suppression des semestres de l'étudiant
    function deleteSemesters ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_tuitions_semesters', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }

    // Ajout d'un compte à l'utilisateur
    function addAccount ($account) {
        if (!array_key_exists('idul', $account)) $account['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_tuitions_accounts', $account)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des semestres de frais d'un utilisateur
    function getAccount ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);

        $result = $this->db->get('stu_tuitions_accounts');

        $account = $result->row_array();

        if ($account!=array()) {
            // Renvoi des données
            return ($account);
        } else {
            return (array());
        }
    }

    // Suppression des semestres de l'étudiant
    function deleteAccount ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_tuitions_accounts', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }
}
?>
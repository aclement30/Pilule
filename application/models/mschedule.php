<?php

class mSchedule extends CI_Model {
    function mSchedule () {
        parent::__construct();
    }

    // Ajout d'un semestre à l'utilisateur
    function addSemester ($semester) {
        if (!array_key_exists('idul', $semester)) $semester['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_schedule_semesters', $semester)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des semestres d'études d'un utilisateur
    function getSemesters ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);

        $result = $this->db->get('stu_schedule_semesters');

        $semesters = $result->result_array();

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

        if ($this->db->delete('stu_schedule_semesters', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }

    // Ajout d'un cours à l'utilisateur
    function addCourse ($course) {
        if (!array_key_exists('idul', $course)) $course['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_schedule_courses', $course)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des cours d'études d'un utilisateur
    function getCourses ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);

        $result = $this->db->get('stu_schedule_courses');

        $courses = $result->result_array();

        if ($courses!=array()) {
            // Renvoi des données
            return ($courses);
        } else {
            return (array());
        }
    }

    // Suppression des cours de l'étudiant
    function deleteCourses ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_schedule_courses', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }

    // Ajout d'une classe à l'utilisateur
    function addClass ($class) {
        if (!array_key_exists('idul', $class)) $class['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_schedule_classes', $class)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des classes d'études d'un utilisateur
    function getClasses ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['stu_schedule_classes.idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->select('stu_schedule_classes.*, stu_schedule_courses.*');
        $this->db->from('stu_schedule_classes');
        $this->db->where($params);
        $this->db->join('stu_schedule_courses', 'stu_schedule_courses.nrc = stu_schedule_classes.nrc', 'left');

        $result = $this->db->get();

        $classes = $result->result_array();

        if ($classes!=array()) {
            // Renvoi des données
            return ($classes);
        } else {
            return (array());
        }
    }

    // Suppression des cours de l'étudiant
    function deleteClasses ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_schedule_classes', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }
}
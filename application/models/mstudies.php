<?php

class mStudies extends CI_Model {
    function mStudies () {
        parent::__construct();
    }

    // Ajout d'un programme à l'utilisateur
    function addProgram ($program) {
        if (!array_key_exists('idul', $program)) $program['idul'] = $this->session->userdata('pilule_user');
        if (isset($program['concentrations']) and is_array($program['concentrations'])) $program['concentrations'] = serialize($program['concentrations']);

        if ($this->db->insert('stu_programs', $program)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des programmes d'études d'un utilisateur
    function getPrograms ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);

        $result = $this->db->get('stu_programs');

        $programs = $result->result_array();

        foreach ($programs as &$program) {
            $program['concentrations'] = unserialize($program['concentrations']);
        }

        if ($programs!=array()) {
            // Renvoi des données
            return ($programs);
        } else {
            return (array());
        }
    }

    function editProgram ($program) {
        if (!isset($program['idul'])) $program['idul'] = $this->session->userdata('pilule_user');
        if (isset($program['concentrations']) and is_array($program['concentrations'])) $program['concentrations'] = serialize($program['concentrations']);

        $this->db->where(array('id' => $program['id']));
        unset($program['id']);

        if ($this->db->update('stu_programs', $program)) {
            return (true);
        } else {
            return (false);
        }
    }

    // Suppression des programmes de l'étudiant
    function deletePrograms ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_programs', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }

    function getSemestersGPA ($first, $last) {
        $this->db->select('semester, cumulative_gpa AS gpa');
        $this->db->where(array('semester >=' => $first, 'semester <=' => $last));
        $result = $this->db->get('stu_reports_semesters');

        $average = $result->result_array();

        if (!empty($average)) {
            return($average);
        } else {
            return (array());
        }
    }

    function getCohortAverageGPA ($program_name, $session_repertoire) {
        $this->db->select('COUNT(*) AS number, AVG(gpa_program) AS average');
        $this->db->where(array('name' => $program_name, 'session_repertoire' => $session_repertoire));
        $result = $this->db->get('stu_programs');

        $average = $result->row_array();

        return(array('average' => $average['average'], 'number' => $average['number']));
    }

    // Ajout d'une classe de l'utilisateur
    function addClass ($class) {
        if (!array_key_exists('idul', $class)) $class['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_classes', $class)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des cours d'un utilisateur
    function getClasses ($params) {
        if (isset($params['type']) and is_array($params['type'])) {
            $types = $params['type'];
            unset($params['type']);
        }
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);
        if (isset($types)) {
            $this->db->where_in('type', $types);
        }

        $this->db->order_by('hour_start');

        $result = $this->db->get('stu_classes');

        $classes = $result->result_array();

        if ($classes!=array()) {
            // Renvoi des données
            return ($classes);
        } else {
            return (array());
        }
    }

    // Suppression des classes de l'utilisateur
    function deleteClasses ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_classes', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }

    // Ajout d'un cours de l'utilisateur
    function addProgramCourse ($course) {
        if (!array_key_exists('idul', $course)) $course['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_programs_courses', $course)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des cours d'un programme pour un utilisateur
    function getProgramCourses ( $where ) {
        if (!array_key_exists('idul', $where)) $where['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where( $where );
        $this->db->order_by("semester", "asc");

        $result = $this->db->get('stu_programs_courses');

        $courses = $result->result_array();

        if ($courses!=array()) {
            // Renvoi du paramètre
            return ($courses);
        } else {
            return (array());
        }
    }

    // Ajout d'une section de cours de l'utilisateur
    function addProgramSection ($section) {
        if (!array_key_exists('idul', $section)) $section['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_programs_sections', $section)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des sections de cours de l'utilisateur
    function getProgramSections ($program_id) {
        // Sélection des données
        $this->db->where(array('idul' => $this->session->userdata('pilule_user'), 'program_id'=>$program_id));
        $this->db->order_by("number", "asc");

        $result = $this->db->get('stu_programs_sections');

        $sections = $result->result_array();

        if ($sections!=array()) {
            // Renvoi des sections de programmes
            return ($sections);
        } else {
            return (array());
        }
    }

    // Suppression des sections de programmes de l'utilisateur
    function deleteProgramSections ($program_id = '') {
        if ($this->session->userdata('pilule_user') != 'demo') {
            $this->db->delete('stu_programs_sections', array('idul' => $this->session->userdata('pilule_user'), 'program_id' => $program_id));
        }

        return (true);
    }

    // Ajout d'une section de cours de l'utilisateur
    function addReportAdmittedSection ($section) {
        if (!array_key_exists('idul', $section)) $section['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_reports_admitted_sections', $section)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des sections de cours de l'utilisateur
    function getReportAdmittedSections () {
        // Sélection des données
        $this->db->where(array('idul' => $this->session->userdata('pilule_user')));

        $result = $this->db->get('stu_reports_admitted_sections');

        $sections = $result->result_array();

        if ($sections!=array()) {
            // Renvoi des sections du relevé de notes
            return ($sections);
        } else {
            return (array());
        }
    }

    // Suppression des sections de programmes de l'utilisateur
    function deleteReportAdmittedSections () {
        if ($this->session->userdata('pilule_user') != 'demo') {
            $this->db->delete('stu_reports_admitted_sections', array('idul' => $this->session->userdata('pilule_user')));
        }

        return (true);
    }

    // Ajout d'un semestre de cours de l'utilisateur
    function addReportSemester ($semester) {
        if (!array_key_exists('idul', $semester)) $semester['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_reports_semesters', $semester)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche de semestres de cours de l'utilisateur
    function getReportSemesters () {
        // Sélection des données
        $this->db->where(array('idul' => $this->session->userdata('pilule_user')));

        $result = $this->db->get('stu_reports_semesters');

        $semesters = $result->result_array();

        if ($semesters!=array()) {
            // Renvoi des semestres du relevé de notes
            return ($semesters);
        } else {
            return (array());
        }
    }

    // Suppression des semestres du relevé de notes de l'utilisateur
    function deleteReportSemesters () {
        if ($this->session->userdata('pilule_user') != 'demo') {
            $this->db->delete('stu_reports_semesters', array('idul' => $this->session->userdata('pilule_user')));
        }

        return (true);
    }

    // Ajout d'un cours de l'utilisateur
    function addReportCourse ($course) {
        if (!array_key_exists('idul', $course)) $course['idul'] = $this->session->userdata('pilule_user');

        if ($this->db->insert('stu_reports_courses', $course)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche des cours d'un programme pour un utilisateur
    function getReportCourses ( $where ) {
        if (!array_key_exists('idul', $where)) $where['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where( $where );
        //$this->db->order_by("semester", "asc");

        $result = $this->db->get('stu_reports_courses');

        $courses = $result->result_array();

        if ($courses!=array()) {
            // Renvoi du paramètre
            return ($courses);
        } else {
            return (array());
        }
    }

    // Ajout d'un relevé de notes à l'utilisateur
    function addReport ($report) {
        if (!array_key_exists('idul', $report)) $report['idul'] = $this->session->userdata('pilule_user');
        if (isset($report['programs']) and is_array($report['programs'])) $report['programs'] = serialize($report['programs']);

        if ($this->db->insert('stu_reports', $report)) {
            return ($this->db->insert_id());
        } else {
            return (false);
        }
    }

    // Recherche du relevé de notes d'un utilisateur
    function getReport ($params = array()) {
        if (!array_key_exists('idul', $params)) $params['idul'] = $this->session->userdata('pilule_user');

        // Sélection des données
        $this->db->where($params);

        $result = $this->db->get('stu_reports');

        $report = $result->row_array();
        if (!empty($report)) {
            $report['programs'] = unserialize($report['programs']);
        }

        if ($report!=array()) {
            // Renvoi des données
            return ($report);
        } else {
            return (array());
        }
    }

    function editReport ($report) {
        if (!isset($report['idul'])) $report['idul'] = $this->session->userdata('pilule_user');
        if (isset($report['programs']) and is_array($report['programs'])) $report['programs'] = serialize($report['programs']);

        $this->db->where(array('id' => $report['id']));
        unset($report['id']);

        if ($this->db->update('stu_reports', $report)) {
            return (true);
        } else {
            return (false);
        }
    }

    // Suppression des relevés de notes de l'étudiant
    function deleteReports ($idul = '') {
        if ($idul=='') $idul = $this->session->userdata('pilule_user');

        // Si le compte demo est activé, ne pas supprimer les données
        if ($idul == 'demo') return (true);

        if ($this->db->delete('stu_reports', array('idul'=>$idul))) {
            return (true);
        } else {
            return (false);
        }
    }
}
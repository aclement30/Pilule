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

    function export ($semester, $format = "ical", $alarm = 'no', $title = 'name') {
        $classes = $this->getClasses(array('stu_schedule_classes.semester'=>$semester, 'stu_schedule_classes.day !='=>''));

        if (count($classes) > 0) {
            $ics =
                'BEGIN:VCALENDAR
CALSCALE:GREGORIAN
X-WR-TIMEZONE;VALUE=TEXT:Canada/Eastern
METHOD:PUBLISH
PRODID:-//Pilule //NONSGML iCalendar Template//EN
X-WR-CALNAME;VALUE=TEXT:Université Laval
VERSION:2.0';
            $weekdays = array("L"=>0,"M"=>1,"R"=>2,"J"=>3,"V"=>4,"S"=>5);
            $sectors = array(
                "Est"					=>	'PVE',
                "Pavillon de l'Éducation physique et des sports"	=>	'EPS',
                "PEPS"	                =>	'PEPS',
                "Médecine dentaire"	    =>	'MDE',
                "Centre de foresterie des Laurentides"	=>	'CFL',
                "Abitibi-Price"		    =>	'ABP',
                "Palasis-Prince"		=>	'PAP',
                "Maison Omer-Gingras"	=>	'OMG',
                "Services"				=>	'PSA',
                "Ferdinand-Vandry"		=>	'VND',
                "Charles-Eugène-Marchand"=>'CHM',
                "Alexandre-Vachon"		=>	'VCH',
                "Adrien-Pouliot"		=>	'PLT',
                "Charles-De Koninck"	=>	'DKN',
                "Jean-Charles-Bonenfant"=>	'BNF',
                "Sciences de l'éducation"=>'TSE',
                "Félix-Antoine-Savard"	=>	'FAS',
                "Louis-Jacques-Casault"=>	'CSL',
                "Paul-Comtois"			=>	'CMT',
                "Maison Eugène-Roberge" =>	'EGR',
                "Maison Marie-Sirois"	=>	'MRS',
                "Agathe-Lacerte"		=>	'LCT',
                "Ernest-Lemieux"		=>	'LEM',
                "Alphonse-Desjardins"	=>	'ADJ',
                "Maurice-Pollack"		=>	'POL',
                "H.-Biermans-L.-Moraud" =>	'PBM',
                "Alphonse-Marie-Parent" =>	'PRN',
                "J.-A.-DeSève"			=>	'DES',
                "La Laurentienne"		=>	'LAU',
                "Envirotron"			=>	'EVT',
                "Optique-photonique"	=>	'COP',
                "Gene-H.-Kruger"		=>	'GHK',
                "Héma-Québec"			=>	'HQ',
                "Maison Michael-John-Brophy"=>'BRY',
                "Maison Couillard"		=>	'MCO',
                "Serres haute performance"=>'EVS',
                'Édifice de La Fabrique'=>	'FAB',
                'Édifice du Boulevard'	=>	'E-BLVD',
                'Éd. Vieux-Séminaire-de-Québec'	=>	'SEM'

            );

            $readingWeekStart = mktime(0, 0, 0, 03, 05, 2012);
            $readingWeekEnd = mktime(0, 0, 0, 03, 11, 2012);

            foreach($classes as $class) {
                //if (!isset($class['name'])) $class['name'] = $class['idcourse'];

                $firstDay = mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))+($weekdays[$class['day']]*3600*24);
                $lastDay = mktime(floor($class['hour_end']), 0, 0, substr($class['date_end'], 4, 2), substr($class['date_end'], 6, 2), substr($class['date_end'], 0, 4));
                $currentDay = $firstDay;

                while ($currentDay < $lastDay) {
                    if ($currentDay > $lastDay) break;
                    if ($currentDay >= $readingWeekStart && $currentDay <= $readingWeekEnd) {
                        // Semaine de lecture
                    } else {
                        $startTime = floor($class['hour_start']);
                        if ($startTime < 10) $startTime = "0".$startTime;
                        $startTime .= (ceil($class['hour_start'])-$class['hour_start'])*60;

                        $endTime = floor($class['hour_end']);
                        if ($endTime < 10) $endTime = "0".$endTime;
                        $endTime .= (ceil($class['hour_end'])-$class['hour_end'])*60;

                        $local = $class['location'];
                        $sector = substr($local, 0, strrpos($local, ' '));
                        $local_number = substr($local, strrpos($local, ' ')+1);
                        if (array_key_exists($sector, $sectors)) {
                            $location = $sectors[$sector]." ".$local_number;
                        } else {
                            $location = $sector.", local ".$local_number;
                        }

                        $eventTitle = $class['title'];
                        //$eventTitle = $class['idcourse'];

                        $ics .=
                            '
BEGIN:VEVENT
SEQUENCE:1
DTSTART;TZID=Canada/Eastern:'.date('Ymd', $currentDay).'T'.$startTime.'00
SUMMARY:'.$eventTitle.'
DTEND;TZID=Canada/Eastern:'.date('Ymd', $currentDay).'T'.$endTime.'00
LOCATION:'.$location;
                        $ics .=
                            '
END:VEVENT';
                    }
                    $currentDay += 3600*24*7;

                }
            }
        }

        $ics .=
            '
END:VCALENDAR';

        return ($ics);
    }
}
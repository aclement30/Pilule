<?php

class Schedule extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;
    var $debug = false;

	function Schedule() {
		parent::__construct();

        // Détection de l'origine de la requête (HTML, AJAX, iframe...)
        getRequestSource();
				
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mSchedule');

        // Vérification de la connexion
        if ((!$this->mUser->isAuthenticated())) {
            $_SESSION['login_redirect'] = $this->uri->uri_string();
            redirect('login');
        }
		
		// Détection des navigateurs mobiles
		$this->mobile = $this->lmobile->isMobile();
	}

	function index () {
        $data = array(
            'section'           =>  'studies',
            'user'              =>  $this->mUser->info(),
            'mobile_browser'    =>  $this->mobile,
            'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false,
            // Set page specific data
            'semester_date'     =>  ($this->uri->segment(2) != '') ? $this->uri->segment(2): CURRENT_SEMESTER
        );

		$this->mHistory->save('schedule-timetable');

        // Vérification de l'existence des données en cache
        $last_request = $this->mCache->getLastRequest('schedule');

        if (empty($last_request)) {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'Horaire de cours',
                'content'       =>  $this->load->view('errors/loading-data', $data, true),
                'reloadData'    =>  'schedule',
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/schedule',
                        'title' =>  'Horaire de cours'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'schedule', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }

        // Sélection de l'horaire pour le semestre sélectionné
        $data['classes'] = $this->mSchedule->getClasses(array('stu_schedule_classes.semester' => $data['semester_date']));
        $data['semesters'] = $this->mSchedule->getSemesters(array());

        ob_start();

        $startDate = date('Ymd', time()+3600*24*365);

        $timetable_classes = 0;                 // Nombre de classes affichées sur l'horaire
        $other_courses = 0;                     // Nombre de cours à distance

        if (!empty($data['semesters'])) {
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

            $holidays = array(
                'action-graces' =>  20121008,
                'reading-week'  =>  array(mktime(0, 0, 0, 10, 29, 2012), mktime(23, 59, 0, 11, 03, 2012)),
                'noel'          =>  array(mktime(0, 0, 0, 12, 22, 2012), mktime(23, 59, 0, 1, 2, 2013))
            );

            foreach($data['classes'] as $class) {
                if (empty($class['day'])) {
                    $other_courses++;
                    continue;
                }

                // Vérification que la session commence avant le 1er jour du cours
                if (($weekdays[$class['day']]+1) < date('N', mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4)))) {
                    $firstDay = mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))+(((6-$weekdays[$class['day']])+$weekdays[$class['day']])*3600*24);
                } else {
                    $firstDay = mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))+((($weekdays[$class['day']]+1)-date('N', mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))))*3600*24);
                }
                $lastDay = mktime(floor($class['hour_end']), 0, 0, substr($class['date_end'], 4, 2), substr($class['date_end'], 6, 2), substr($class['date_end'], 0, 4));
                $currentDay = $firstDay;

                if (date('Ymd', $firstDay) < $startDate) $startDate = date('Ymd', $firstDay);

                while ($currentDay < $lastDay) {
                    if ($currentDay > $lastDay) break;
                    // Check if currentDay is not a holiday
                    $holiday = false;

                    foreach ($holidays as $name => $range) {
                        if (is_array($range) && $currentDay >= $range[0] && $currentDay <= $range[1]) {
                            $holiday = true;
                        } elseif(!is_array($range) && date('Ymd', $currentDay) == $range) {
                            $holiday = true;
                        }
                    }

                    if (!$holiday) {
                        $startTime = floor($class['hour_start']);
                        if ($startTime < 10) $startTime = "0".$startTime;
                        $startTime .= ':' . (ceil($class['hour_start'])-$class['hour_start'])*60;

                        $endTime = floor($class['hour_end']);
                        if ($endTime < 10) $endTime = "0".$endTime;
                        $endTime .= ':' . (ceil($class['hour_end'])-$class['hour_end'])*60;

                        if (!empty($class['location'])) {
                            $local = $class['location'];
                            $sector = substr($local, 0, strrpos($local, ' '));
                            $local_number = substr($local, strrpos($local, ' ')+1);

                            if (array_key_exists($sector, $sectors)) {
                                $location = $sectors[$sector]." ".$local_number;
                            } else {
                                $location = $sector.", local ".$local_number;
                            }
                        } else {
                            $location = '';
                        }

                        $timetable_classes++;

                        $eventTitle = $class['title'];
                        ?>
                    {
                    title:  '<?php echo addslashes($eventTitle); ?>',
                    code:   '<strong><?php echo $class['code']; ?></strong>',
                    location:    '<?php if (!empty($location)) { ?><div style="margin-top: 5px;"><i class="icon-map-marker icon-white"></i> <span title="<?php echo addslashes($class['location']); ?>"><?php echo $location; ?></span></div><?php } if (!empty($class['teacher'])) { ?><div style="margin-bottom: 5px; margin-top:  5px;"><i class="icon-user icon-white"></i> <span><?php echo addslashes($class['teacher']); ?></span></div><?php } ?>',
                    start:  '<?php echo date('Y-m-d', $currentDay).' '.$startTime; ?>:00',
                    end:    '<?php echo date('Y-m-d', $currentDay).' '.$endTime; ?>:00',
                    allDay: false
                    },
                        <?php
                    }
                    $currentDay += 3600*24*7;
                }
            }

            if ($data['semester_date'] == CURRENT_SEMESTER) {
                $startDay = (int)date('d');
                $startMonth = (int)date('m');
                $startYear = date('Y');
            } else {
                $startDay = (int)substr($startDate, 6, 2);
                $startMonth = (int)substr($startDate, 4, 2);
                $startYear = substr($startDate, 0, 4);
            }

            $events = ob_get_clean();

            $code = <<<EOT
        var displayCalendar = function () {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                firstDay:   1,
                defaultView :   'agendaWeek',
                allDaySlot:     false,
                firstHour:      8,
                minTime:        8,
                maxTime:        22,
                weekends:   false,
                year:           {$startYear},
                month:          {$startMonth},
                timeFormat: 'H(:mm)', // uppercase H for 24-hour clock
                axisFormat: 'H:mm',
                 buttonText: {
                    prev: '',
                    next: ''
                },
                titleFormat:    {
                    month:  'MMMM yyyy',
                    week: "d[ MMM][ yyyy]{ '&#8212;' d MMM. yyyy}",
                    day: 'dddd, MMM d, yyyy'
                },
                eventRender: function(event, element) {
                    element.find(".fc-event-time").append(" " + event.code);
                    element.find(".fc-event-title").append("<br />" + event.location);
                },
                monthNamesShort:    ['janv', 'fév', 'mars', 'avril', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc'],
                dayNamesShort:      ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                columnFormat:       {
                    month: 'ddd',    // Mon
                    week: 'ddd. d', // Mon 9/7
                    day: 'dddd M/d'  // Monday 9/7
                },
                height: 650,
                events: [
                    {$events}
                ]
            });
         };

         // Wait until the refresh effect end so the chart is displayed and can be filled
         setTimeout(displayCalendar, 100);
EOT;

            // Si aucun cours à distance pour la session, on masque la colonne de droite
            if ($other_courses == 0) {
                $code .= "$('.widget-content .panel-right').hide();$('.widget-content .panel-left').css('width', '100%');";
            }

            // Ajout des données de la dernière requête Capsule
            $data['last_request'] = $last_request;

            // Chargement de la page
            respond(array(
                'title'         =>  'Horaire de cours',
                'content'       =>  $this->load->view('schedule/timetable', $data, true),
                'code'          =>  $code,
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay) && (!$this->debug)) ? 'schedule': false,
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/schedule',
                        'title' =>  'Horaire de cours'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'schedule', auto: 0});",
                        'type'  =>  'refresh'
                    ),
                    ($timetable_classes != 0) ? array(
                        'action'=>  "app.schedule.download('".$data['semester_date']."');",
                        'tip'   =>  "Télécharger l'horaire",
                        'type'  =>  'download'
                    ): false
                )
            ));
        } else {
            // Aucune données n'existe pour cette page
            respond(array(
                'title'         =>  'Horaire de cours',
                'content'       =>  $this->load->view('errors/no-data', $data, true),
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'breadcrumb'    =>  array(
                    array(
                        'url'   =>  '#!/dashboard',
                        'title' =>  'Tableau de bord'
                    ),
                    array(
                        'url'   =>  '#!/schedule',
                        'title' =>  'Horaire de cours'
                    )
                ),
                'buttons'       =>  array(
                    array(
                        'action'=>  "app.cache.reloadData({name: 'schedule', auto: 0});",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }
    }

	// Fonction de rétro-compatibilité
	function timetable() {
		$this->index();
	}

    function ical_download () {
        $requested_semester = $this->uri->segment(3);

        $classes = $this->mSchedule->getClasses(array('stu_schedule_classes.semester' => $requested_semester));

        $startDate = date('Ymd', time()+3600*24*365);

        if (!empty($classes)) {
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

            $holidays = array(
                'action-graces' =>  20121008,
                'reading-week'  =>  array(mktime(0, 0, 0, 10, 29, 2012), mktime(23, 59, 0, 11, 03, 2012)),
                'noel'          =>  array(mktime(0, 0, 0, 12, 22, 2012), mktime(23, 59, 0, 1, 2, 2013))
            );

            foreach($classes as $class) {
                if (empty($class['day'])) continue;

                // Vérification que la session commence avant le 1er jour du cours
                if (($weekdays[$class['day']]+1) < date('N', mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4)))) {
                    $firstDay = mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))+(((6-$weekdays[$class['day']])+$weekdays[$class['day']])*3600*24);
                } else {
                    $firstDay = mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))+((($weekdays[$class['day']]+1)-date('N', mktime(floor($class['hour_start']), 0, 0, substr($class['date_start'], 4, 2), substr($class['date_start'], 6, 2), substr($class['date_start'], 0, 4))))*3600*24);
                }
                $lastDay = mktime(floor($class['hour_end']), 0, 0, substr($class['date_end'], 4, 2), substr($class['date_end'], 6, 2), substr($class['date_end'], 0, 4));
                $currentDay = $firstDay;

                while ($currentDay < $lastDay) {
                    if ($currentDay > $lastDay) break;
                    // Check if currentDay is not a holiday
                    $holiday = false;

                    foreach ($holidays as $name => $range) {
                        if (is_array($range) && $currentDay >= $range[0] && $currentDay <= $range[1]) {
                            $holiday = true;
                        } elseif(!is_array($range) && date('Ymd', $currentDay) == $range) {
                            $holiday = true;
                        }
                    }

                    if (!$holiday) {
                        $startTime = floor($class['hour_start']);
                        if ($startTime < 10) $startTime = "0".$startTime;
                        $startTime .= (ceil($class['hour_start'])-$class['hour_start'])*60;

                        $endTime = floor($class['hour_end']);
                        if ($endTime < 10) $endTime = "0".$endTime;
                        $endTime .= (ceil($class['hour_end'])-$class['hour_end'])*60;

                        if (!empty($class['location'])) {
                            $local = $class['location'];
                            $sector = substr($local, 0, strrpos($local, ' '));
                            $local_number = substr($local, strrpos($local, ' ')+1);

                            if (array_key_exists($sector, $sectors)) {
                                $location = $sectors[$sector]." ".$local_number;
                            } else {
                                $location = $sector.", local ".$local_number;
                            }
                        } else {
                            $location = '';
                        }

                        $eventTitle = $class['title'];

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

            $ics .=
                '
END:VCALENDAR';

            // Chargement du helper de téléchargement de fichiers
            $this->load->helper('download');

            // Définition du nom du fichier
            switch (substr($requested_semester, 4, 2)) {
                case '01':
                    $semester_name = 'hiver-'.substr($requested_semester, 0, 4);
                    break;
                case '01':
                    $semester_name = 'automne-'.substr($requested_semester, 0, 4);
                    break;
                default:
                    $semester_name = 'ete-'.substr($requested_semester, 0, 4);
                    break;
            }

            // Envoi des données iCal sous forme de fichier ICS
            force_download('horaire-'.$semester_name.'.ics', $ics);

            return(true);
        }
    }
}
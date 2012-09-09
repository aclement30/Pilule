<?php

class Schedule extends CI_Controller {
	var $mobile = 0;
	var $user;
    var $_source;

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
                        'action'=>  "app.cache.reloadData('schedule');",
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
                if (empty($class['day'])) continue;

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
                    // Check if currentDay is not a holidays
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

                        $local = $class['location'];
                        $sector = substr($local, 0, strrpos($local, ' '));
                        $local_number = substr($local, strrpos($local, ' ')+1);

                        if (array_key_exists($sector, $sectors)) {
                            $location = $sectors[$sector]." ".$local_number;
                        } else {
                            $location = $sector.", local ".$local_number;
                        }

                        $eventTitle = $class['title'];
                        ?>
                    {
                    title:  '<?php echo addslashes($eventTitle); ?>',
                    code:   '<strong><?php echo $class['code']; ?></strong>',
                    location:    '<div style="margin-top: 5px;"><i class="icon-map-marker icon-white"></i> <span title="<?php echo addslashes($class['location']); ?>"><?php echo $location; ?></span></div><div style="margin-bottom: 5px; margin-top:  5px;"><i class="icon-user icon-white"></i> <span><?php echo addslashes($class['teacher']); ?></span></div>',
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

            // Chargement de la page
            respond(array(
                'title'         =>  'Horaire de cours',
                'content'       =>  $this->load->view('schedule/timetable', $data, true),
                'code'          =>  $code,
                'timestamp'     =>  time_ago($last_request['timestamp']),
                'reloadData'    =>  ($last_request['timestamp'] < (time()-$this->mUser->expirationDelay)) ? 'schedule': false,
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
                        'action'=>  "app.cache.reloadData('schedule');",
                        'type'  =>  'refresh'
                    )
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
                        'action'=>  "app.cache.reloadData('schedule');",
                        'type'  =>  'refresh'
                    )
                )
            ));

            return (false);
        }
    }
	
	function getMenu() {
		$data['mobile'] = $this->mobile;
		
		$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/m-menu', $data, true)));
		echo "$('#rcolumn').html(\"".addslashes($content)."\");updateMenu();";
		if ($this->mobile == 1) echo "$('h2.title').after($('#sidebar'));";
	}
	
	function share () {
		$data = array();
		$data['section'] = 'schedule';
		$data['page'] = 'share';
		$data['user'] = $this->user;
		$data['semester_date'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		$default_semester = '201209';
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$fb_data = $this->session->userdata('fb_data');
		$data['fb_data'] = $fb_data;
		
		/*
		//exit();
		// Vérification de la connexion à Facebook
		if (!isset($_SESSION['fb_data'])) {
			redirect("cfacebook/auth/u/".base64_encode(site_url()."schedule/share"));
			return (true);
		}
		
		$fb_data = $_SESSION['fb_data']; // This array contains all the user FB information
		$data['fb_data'] = $fb_data;

		if ((!$fb_data['uid']) or (!$fb_data['me'])) {
			redirect("cfacebook/auth/u/".base64_encode(site_url()."schedule/share"));
		} else {
			&*/
			if (isset($fb_data) and isset($fb_data['uid'])) {
				$data['fb_friendlists'] = $this->mFacebook->getFriendlists();
				//$data['fb_friends'] = $this->mFacebook->getFriends();
			}
			
			// Chargement de la page
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/share-timetable', $data, true)));
			
			echo "setPageInfo('schedule/courses');setPageContent(\"".addslashes($content)."\");";
			if ((!isset($fb_data)) or (!$fb_data['uid']) or (!$fb_data['me'])) {
				echo 'scheduleObj.askFBAuth();';
			}
		//}
	}
	
	function w_askFBAuth () {
		$data = array();
		
		if ($this->input->get('error') != '') {
			$data['error'] == base64_decode($this->input->get('error'));
		}
		
		// Chargement de la page d'aide
		$this->load->view('schedule/w-fb-auth', $data);
	}
	
	function s_authFB () {
		if ($this->input->get('fbauth') != '') {
			if ($this->input->get('fbauth') == 'error') {
				redirect("schedule/w_askFBAuth?error=".base64_encode("L'authentification par Facebook a échouée."));
			}
		} else {
			//$user_fbdata = $this->mFacebook->getUserData();
			$user_fbdata = $this->session->userdata('fb_data'); // This array contains all the user FB information
 			
			$this->mUser->setParam('fbuid', $user_fbdata['uid']);
			$this->mUser->setParam('fbname', $user_fbdata['me']['name']);
			
			?><script language="javascript">top.document.location='<?php echo site_url(); ?>#!/schedule/share';top.refreshPage();top.$.modal.close();</script><?php
		}
	}
	
	function s_unlinkFB () {
		$this->facebook->destroySession();
		
		?>refreshPage();scheduleObj.askFBAuth();<?php
	}
	
	// Fonction de rétro-compatibilité
	function timetable() {
		$this->index();
	}
	
	function courses () {
		$data = array();
		$data['user'] = $this->user;
		$data['semester_date'] = $this->uri->segment(3);
		$data['mobile'] = $this->mobile;
		
		if (isset($_SESSION['cap_offline']) and $_SESSION['cap_offline'] == 'yes') {
			$data['cap_offline'] = 1;
		}
		
		$this->mHistory->save('schedule-courses');
		
		if ($data['semester_date']=='') {
			if (isset($_SESSION['schedule_current_semester'])) {
				$data['semester_date'] = $_SESSION['schedule_current_semester'];
			} else {
				// Vérification de l'existence des sessions en cache
				$cache = $this->mCache->getCache('data|schedule,semesters');
				
				if ($cache!=array()) {
					$semesters = unserialize($cache['value']);
					
					if ($semesters!=array()) {
						$data['semester_date'] = key($semesters);
						$_SESSION['schedule_current_semester'] = $data['semester_date'];
					}
				}
			}
		} else {
			$_SESSION['schedule_current_semester'] = $data['semester_date'];
		}
		
		if ($data['semester_date']!='') {
			$courses = $this->mUser->getCourses($_SESSION['cap_iduser'], $data['semester_date']);

			$data['courses'] = array();
			foreach ($courses as $course) {
				$course['classes'] = $this->mUser->getClasses(array('idcourse'=>$course['idcourse'], 'semester'=>$data['semester_date'], 'idul'=>$_SESSION['cap_iduser']));
				$data['courses'][] = $course;
			}
		}
		
		$cache = $this->mCache->getCache('data|schedule,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
			// Vérification de la date de chargement des données
			if ($cache['timestamp']<(time()-$this->mUser->expirationDelay)) {
				$data['reload_data'] = 'data|schedule,semesters';
			}
		}
		
		if (isset($data['semesters']) and $data['semesters']!=array()) {
			// Chargement de la page
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('schedule/courses', $data, true)));
		} else {
			// Chargement de la page d'erreur
			$data['title'] = 'Liste des cours';
			$data['reload_name'] = 'data|schedule,semesters';
			
			$content = str_replace("\r", '', str_replace("\n", '', $this->load->view('errors/loading-data', $data, true)));
		}
		
		echo "setPageInfo('schedule/courses');setPageContent(\"".addslashes($content)."\");";
		if (isset($data['reload_data']) and $_SESSION['cap_iduser'] != 'demo' and $_SESSION['cap_offline'] != 'yes') echo "reloadData('".$data['reload_data']."', 1);";
	}
	
	function w_export () {
		$data['semester_date'] = $this->uri->segment(3);
		
		$this->mHistory->save('schedule-export');
		
		$_SESSION['schedule_current_semester'] = $data['semester_date'];
		$cache = $this->mCache->getCache('data|schedule,semesters');
		
		if ($cache!=array()) {
			$data['semesters'] = unserialize($cache['value']);
		}
		
		// Chargement de la page d'aide
		$this->load->view('schedule/w-export', $data);
	}

    function ical_download () {
        $data = $this->mSchedule->export(CURRENT_SEMESTER);

        $this->output->set_content_type('text/calendar');
        $this->output->set_output($data);

        return(true);
    }

	function s_export() {
		$semester = $this->input->post('semester');
		$alarm = $this->input->post('alarm');
		$title = $this->input->post('title');
		$format = $this->input->post('format');
		
		$this->load->helper('download');
		
		$data = $this->mUser->exportSchedule($semester, $format, $alarm, $title);
		
		switch (substr($semester, 4, 2)) {
			case '01':
				$semester_name = 'hiver-'.substr($semester, 0, 4);
			break;
			case '01':
				$semester_name = 'automne-'.substr($semester, 0, 4);
			break;
			default:
				$semester_name = 'ete-'.substr($semester, 0, 4);
			break;
		}
		
		force_download('horaire-'.$semester_name.'.ics', $data);
	}
}
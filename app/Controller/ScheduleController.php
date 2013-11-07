<?php
class ScheduleController extends AppController {
    private $sectors = array(
        "Est"                                           =>  'PVE',
        "Pavillon de l'Éducation physique et des sports"=>  'EPS',
        "PEPS"                                          =>  'PEPS',
        "Médecine dentaire"                             =>  'MDE',
        "Centre de foresterie des Laurentides"          =>  'CFL',
        "Abitibi-Price"                                 =>  'ABP',
        "Palasis-Prince"                                =>  'PAP',
        "Maison Omer-Gingras"                           =>  'OMG',
        "Services"                                      =>  'PSA',
        "Ferdinand-Vandry"                              =>  'VND',
        "Charles-Eugène-Marchand"                       =>  'CHM',
        "Alexandre-Vachon"                              =>  'VCH',
        "Adrien-Pouliot"                                =>  'PLT',
        "Charles-De Koninck"                            =>  'DKN',
        "Jean-Charles-Bonenfant"                        =>  'BNF',
        "Sciences de l'éducation"                       =>  'TSE',
        "Félix-Antoine-Savard"                          =>  'FAS',
        "Louis-Jacques-Casault"                         =>  'CSL',
        "Paul-Comtois"                                  =>  'CMT',
        "Maison Eugène-Roberge"                         =>  'EGR',
        "Maison Marie-Sirois"                           =>  'MRS',
        "Agathe-Lacerte"                                =>  'LCT',
        "Ernest-Lemieux"                                =>  'LEM',
        "Alphonse-Desjardins"                           =>  'ADJ',
        "Maurice-Pollack"                               =>  'POL',
        "H.-Biermans-L.-Moraud"                         =>  'PBM',
        "Alphonse-Marie-Parent"                         =>  'PRN',
        "J.-A.-DeSève"                                  =>  'DES',
        "La Laurentienne"                               =>  'LAU',
        "Envirotron"                                    =>  'EVT',
        "Optique-photonique"                            =>  'COP',
        "Gene-H.-Kruger"                                =>  'GHK',
        "Héma-Québec"                                   =>  'HQ',
        "Maison Michael-John-Brophy"                    =>  'BRY',
        "Maison Couillard"                              =>  'MCO',
        "Serres haute performance"                      =>  'EVS',
        'Édifice de La Fabrique'                        =>  'FAB',
        'Édifice du Boulevard'                          =>  'E-BLVD',
        'Éd. Vieux-Séminaire-de-Québec'                 =>  'SEM'
    );
    
    private $weekdays = array(
        'L' =>  0,
        'M' =>  1,
        'R' =>  2,
        'J' =>  3,
        'V' =>  4,
        'S' =>  5
    );

    private $holidays;
    private $semesterDates;

    public $uses = array( 'StudentScheduleSemester' );
	public $helpers = array( 'Time' );

	public function beforeFilter() {
		parent::beforeFilter();

        // Set holidays
        $this->holidays = array(
            'action-graces' =>  20121008,
            'reading-week-201209'  =>  array(
                strtotime( '10 October 2012' ),
                strtotime( '3 November 2012, 23:59' )
            ),
            'noel'          =>  array(
                strtotime( '22 December 2012' ),
                strtotime( '2 January 2013, 23:59' )
            ),
            'reading-week-201301'  =>  array(
                strtotime( '11 March 2013' ),
                strtotime( '16 March 2013, 23:59' )
            ),
            'easter'          =>  array(
                strtotime( '29 March 2013' ),
                strtotime( '1 April 2013, 23:59' )
            ),
        );

        // Set semester start/end dates
        $this->semesterDates = array(
            // Year 2011
            '201101'    =>  array(
                date( 'Y-m-d', strtotime( '10 January 2011' ) ), date( 'Y-m-d', strtotime( '24 April 2011' ) )
            ),
            '201109'    =>  array(
                date( 'Y-m-d', strtotime( '5 September 2011' ) ), date( 'Y-m-d', strtotime( '16 December 2011' ) )
            ),
            // Year 2012
            '201201'    =>  array(
                date( 'Y-m-d', strtotime( '9 January 2012' ) ), date( 'Y-m-d', strtotime( '20 April 2012' ) )
            ),
            '201205'    =>  array(
                date( 'Y-m-d', strtotime( '30 April 2012' ) ), date( 'Y-m-d', strtotime( '10 August 2012' ) )
            ),
            '201209'    =>  array(
                date( 'Y-m-d', strtotime( '3 September 2012' ) ), date( 'Y-m-d', strtotime( '11 January 2013' ) )
            ),
            // Year 2013
            '201301'    =>  array(
                date( 'Y-m-d', strtotime( '21 January 2013' ) ), date( 'Y-m-d', strtotime( '30 April 2013' ) )
            ),
            '201305'    =>  array(
                date( 'Y-m-d', strtotime( '6 May 2013' ) ), date( 'Y-m-d', strtotime( '16 August 2013' ) )
            ),
            '201309'    =>  array(
                date( 'Y-m-d', strtotime( '2 September 2013' ) ), date( 'Y-m-d', strtotime( '13 December 2013' ) )
            ),
            '201401'    =>  array(
                date( 'Y-m-d', strtotime( '13 January 2014' ) ), date( 'Y-m-d', strtotime( '25 April 2014' ) )
            )
        );
	}

	public function index ( $semester = null ) {
        // Check if a semester has been provided
        if ( empty( $semester ) )
            $semester = CURRENT_SEMESTER;
        
		// Set basic page parameters
		$this->set( 'breadcrumb', array(
            array(
                'url'   =>  '/dashboard',
                'title' =>  'Tableau de bord'
            ),
            array(
                'url'   =>  '/schedule',
                'title' =>  'Horaire de cours'
            )
        ) );
        $this->set( 'buttons', array(
        	array(
                'action'=>  "app.Cache.reloadData( { name: 'schedule', auto: 0 } );",
                'type'  =>  'refresh'
            ),
            array(
                'action'=>  "app.Schedule.download( '" . $semester . "' );",
                'tip'   =>  "Télécharger l'horaire",
                'type'  =>  'download'
            ),
            array(
                'action'=>  "window.print();",
                'type'  =>  'print'
            )
        ) );
		$this->set( 'title_for_layout', 'Horaire<span class="long"> de cours</span>' );
        $this->setAssets( array( '/js/schedule.js' ), array( '/css/schedule.css' ) );
        $this->set( 'dataObject', 'schedule' );
        
		$schedule = $this->StudentScheduleSemester->find( 'first', array(
			'conditions'	=>	array( 'StudentScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'StudentScheduleSemester.semester' => $semester  ),
        	'contain'		=>	array( 'Course' => array( 'Class' ) )
        ) );

        $semestersList = $this->StudentScheduleSemester->find( 'list', array(
            'conditions'    =>  array( 'StudentScheduleSemester.idul' => $this->Session->read( 'User.idul' ) )
        ) );

		// Check is data exists in DB
        if ( ( $lastRequest = $this->CacheRequest->requestExists( 'schedule' ) ) && !empty( $schedule[ 'Course' ] ) ) {
            $this->set( 'semester', $semester );
            $this->set( 'semesterDates', $this->semesterDates[ $semester ] );
            $this->set( 'semestersList', $semestersList );
        	$this->set( 'schedule', $schedule );

            // Set timetable params
            $this->set( 'sectors', $this->sectors );
            $this->set( 'weekdays', $this->weekdays );
            $this->set( 'holidays', $this->holidays );
            $this->set( 'startDate', date( 'Ymd', time() + 3600 * 24 * 365 ) );

        	$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );
        } else {
            $this->set( 'selectedSemester', $semester );

        	if ( !empty( $lastRequest ) )
				$this->set( 'timestamp', $lastRequest[ 'timestamp' ] );

            if ( !empty( $semestersList ) )
                $this->set( 'semestersList', $semestersList );

        	// No data exists for this page
        	$this->viewPath = 'Commons';
			$this->render( 'no_data' );

            return ( true );
        }

        /*
        if ( !empty($data['semesters'])) {
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
            
            
            if ( $this->mobile == 1 ) {
                $defaultView = 'agendaDay';
               // $titleFormat = <<<EOT
//EOT;
            } else {
                $defaultView = 'agendaWeek';
            }
            */
	}

    function ical_download ( $semester = null ) {
        // Disable layout and auto-rendering
        $this->layout = false;
        $this->autoRender = false;

        $schedule = $this->StudentScheduleSemester->find( 'first', array(
            'conditions'    =>  array( 'StudentScheduleSemester.idul' => $this->Session->read( 'User.idul' ), 'StudentScheduleSemester.semester' => $semester  ),
            'contain'       =>  array( 'Course' => array( 'Class' ) )
        ) );

        if ( !empty( $schedule[ 'Course' ] ) ) {
            $view = new View( $this, false );

            // Set timetable params
            $view->set( 'semester', $semester );
            $view->set( 'semesterDates', $this->semesterDates[ $semester ] );
            $view->set( 'schedule', $schedule );
            $view->set( 'sectors', $this->sectors );
            $view->set( 'weekdays', $this->weekdays );
            $view->set( 'holidays', $this->holidays );

            // Render calendar output
            $output = $view->render( 'ical' );

            // Set filename
            switch ( substr( $semester, 4, 2 ) ) {
                case '01':
                    $semesterName = 'hiver-' . substr( $semester, 0, 4 );
                    break;
                case '09':
                    $semesterName = 'automne-' . substr( $semester, 0, 4 );
                    break;
                default:
                    $semesterName = 'ete-' . substr( $semester, 0, 4 );
                    break;
            }


            // Define filetype and send result as iCal file
            $this->response->type( array( 'ics' => 'text/calendar' ) );
            $this->response->body( $output );
            $this->response->download( 'horaire-' . $semesterName . '.ics' );
        }
    }
}
<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses( 'Helper', 'View' );

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {
	public function convertSemester( $semester, $smallFormat = false ) {
        if ( is_numeric( $semester ) and strlen( $semester ) == 6 ) {
            // Semester format is YYYYMM
            switch ( substr( $semester, 5, 2 ) ) {
                case '09';
                    if ( $smallFormat ) {
                        $semester = 'A-' . substr( $semester, 2, 2 );
                    } else {
                        $semester = 'Automne ' . substr( $semester, 0, 4 );
                    }
                    break;
                case '01';
                    if ( $smallFormat ) {
                        $semester = 'H-' . substr( $semester, 2, 2 );
                    } else {
                        $semester = 'Hiver ' . substr( $semester, 0, 4 );
                    }
                    break;
                case '05';
                    if ( $smallFormat ) {
                        $semester = 'E-' . substr( $semester, 2, 2 );
                    } else {
                        $semester = 'Été ' . substr( $semester, 0, 4 );
                    }
                    break;
            }

            return ( $semester );
        } else {
            // Semester is in text format
            $textSemester = '';
            $semester = explode( ' ', $semester );
            $textSemester = $semester[ 1 ];
            if ( $semester[ 0 ] == 'Automne' ) $textSemester .= '09';
            elseif ( $semester[ 0 ] == 'Hiver' ) $textSemester .= '01';
            elseif ( $semester[ 0 ] == 'Été' ) $textSemester .= '05';

            return ( $textSemester );
        }
    }

    public function timeAgo ( $timestamp ) {
        $timeAgo = CakeTime::timeAgoInWords( $timestamp, array( 'accuracy' => array( 'hour' => 'hour' ), 'end' => '1 year' ) );
        $timeAgo = trim( str_replace( 'ago', '', str_replace( 'second', 'seconde', str_replace( 'hour', 'heure', str_replace( 'day', 'jour', str_replace( 'week', 'semaine', str_replace( 'month', 'mois', $timeAgo ) ) ) ) ) ) );

        if ( $timeAgo == 'just now' ) {
            $timeAgo = 'il y a quelques secondes';
        } else {
            $timeAgo = 'il y a ' . $timeAgo;
        }
        return $timeAgo;
    }

    public function buildCalendar ( $courses, $params = array() ) {
        $schedule = array(
            'regularCourses'    =>  0,
            'otherCourses'      =>  0,
            'startDate'         =>  $params[ 'startDate' ],
            'endDate'           =>  $params[ 'endDate' ],
            'events'            =>  array()
        );

        foreach( $courses as $course ) {
            foreach ( $course[ 'Course' ][ 'Class' ] as $class ) {
                // No day defined for this class, increment off-campus class and skip to the next class
                if ( empty( $class[ 'day' ] ) ) {
                    $schedule[ 'otherCourses' ]++;
                    continue;
                }

                // Check if semester begins before on first class day
                if ( ( $params[ 'weekdays' ][ $class[ 'day' ] ] + 1 ) < date( 'N', mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2 ), substr( $class[ 'date_start' ], 0, 4 ) ) ) ) {
                    $firstDay = mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2 ), substr( $class[ 'date_start' ], 0, 4 ) ) + ( ( ( 6 - $params[ 'weekdays' ][ $class[ 'day' ] ] ) + $params[ 'weekdays' ][ $class[ 'day' ] ] ) * 3600 * 24 );
                } else {
                    $firstDay = mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2 ), substr( $class[ 'date_start' ], 0, 4 ) ) + ( ( ( $params[ 'weekdays' ][ $class[ 'day' ] ] + 1 ) - date( 'N', mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2 ), substr( $class[ 'date_start' ], 0, 4 ) ) ) ) * 3600 * 24 );
                }
                $lastDay = mktime( floor( $class[ 'hour_end' ] ), 0, 0, substr( $class[ 'date_end' ], 4, 2 ), substr( $class[ 'date_end' ], 6, 2 ), substr( $class[ 'date_end' ], 0, 4 ) );
                $currentDay = $firstDay;

                // If first day of class start before the predicted semester start date, move back the semester start date
                if ( date( 'Y-m-d', $firstDay ) < $schedule[ 'startDate' ] )
                    $schedule[ 'startDate' ] = date( 'Y-m-d', $firstDay );

                while ( $currentDay < $lastDay ) {
                    if ( $currentDay > $lastDay ) break;

                    // Check if currentDay is not a holiday
                    $holiday = false;
                    foreach ( $params[ 'holidays' ] as $name => $range ) {
                        if ( is_array( $range ) && $currentDay >= $range[ 0 ] && $currentDay <= $range[ 1 ] ) {
                            $holiday = true;
                        } elseif ( !is_array( $range ) && date( 'Ymd', $currentDay ) == $range ) {
                            $holiday = true;
                        }
                    }

                    if ( !$holiday ) {
                        // Find class location
                        if ( !empty( $class[ 'location' ] ) ) {
                            $local = $class[ 'location' ];
                            $sector = substr( $local, 0, strrpos( $local, ' ' ) );
                            $localNumber = substr( $local, strrpos( $local, ' ' ) + 1 );

                            if ( array_key_exists( $sector, $params[ 'sectors' ] ) ) {
                                $location = $params[ 'sectors' ][ $sector ] . ' ' . $localNumber;
                            } else {
                                $location = $sector . ', local ' . $localNumber;
                            }
                        } else {
                            $location = '';
                        }

                        // Add class to schedule events
                        $schedule[ 'events' ][] = array(
                            'title'     =>  $course[ 'Course' ][ 'title' ],
                            'code'      =>  $course[ 'Course' ][ 'code' ],
                            'location'  =>  $location,
                            'teacher'   =>  $class[ 'teacher' ],
                            'startDay'  =>  date( 'Y-m-d' , $currentDay ),
                            'startTime' =>  $class[ 'hour_start' ],
                            'endDay'    =>  date( 'Y-m-d' , $currentDay ),
                            'endTime'   =>  $class[ 'hour_end' ],
                            'nrc'       =>  $course[ 'Course' ][ 'nrc' ],
                            'semester'  =>  $params[ 'semester' ],
                            'type'      =>  $class[ 'type' ],
                            'allDay'    =>  false
                        );
                    }

                    // Increment current day
                    $currentDay += 3600*24*7;
                }
            }
        }

        if ( $params[ 'semester' ] == CURRENT_SEMESTER ) {
            $schedule[ 'startDay' ] = ( int )date( 'd' );
            $schedule[ 'startMonth' ] = ( int )date( 'm' );
            $schedule[ 'startYear' ] = date( 'Y' );
        } else {
            $schedule[ 'startDay' ] = ( int )substr( $schedule[ 'startDate' ], 6, 2 );
            $schedule[ 'startMonth' ] = ( int )substr( $schedule[ 'startDate' ], 4, 2 );
            $schedule[ 'startYear' ] = substr( $schedule[ 'startDate' ], 0, 4 );
        }

        return $schedule;
    }

    public function buildTimetable ( $courses, $params = array() ) {
        $schedule = array(
            'regularCourses'    =>  0,
            'otherCourses'      =>  0,
            'startDate'         =>  $params[ 'startDate' ],
            'endDate'           =>  $params[ 'endDate' ],
            'events'            =>  array()
        );

        /*
        foreach( $courses as $course ) {
            foreach ( $course[ 'Course' ][ 'Class' ] as $class ) {
                // No day defined for this class, increment off-campus class and skip to the next class
                if ( empty( $class[ 'day' ] ) ) {
                    $schedule[ 'otherCourses' ]++;
                    continue;
                }

                // Check if semester begins before on first class day
                if ( ( $params[ 'weekdays' ][ $class[ 'day' ] ] + 1 ) < date( 'N', mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2 ), substr( $class[ 'date_start' ], 0, 4 ) ) ) ) {
                    $firstDay = mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2), substr( $class[ 'date_start' ], 0, 4 ) ) + ( ( ( 6 - $params[ 'weekdays' ][ $class[ 'day' ] ] ) + $params[ 'weekdays' ][ $class[ 'day' ] ] ) * 3600 * 24 );
                } else {
                    $firstDay = mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2), substr( $class[ 'date_start' ], 0, 4 ) ) + ( ( ( $params[ 'weekdays' ][ $class[ 'day' ] ] + 1 ) - date( 'N', mktime( floor( $class[ 'hour_start' ] ), 0, 0, substr( $class[ 'date_start' ], 4, 2 ), substr( $class[ 'date_start' ], 6, 2 ), substr( $class[ 'date_start' ], 0, 4 ) ) ) ) * 3600 * 24 );
                }
                $lastDay = mktime( floor( $class[ 'hour_end' ] ), 0, 0, substr( $class[ 'date_end' ], 4, 2 ), substr( $class[ 'date_end' ], 6, 2 ), substr( $class[ 'date_end' ], 0, 4 ) );
                $currentDay = $firstDay;

                // If first day of class start before the predicted semester start date, move back the semester start date
                if ( date( 'Y-m-d', $firstDay ) < $schedule[ 'startDate' ] )
                    $schedule[ 'startDate' ] = date( 'Y-m-d', $firstDay );

                // If start date is not a Monday, go back until Monday
                if ( date( 'N', $this->Time->toUnix( $timetable[ 'startDate' ] ) ) > 1 ) {
                    $schedule[ 'startDate' ] = date('Y-m-d', strtotime( '-' . ( $dayOfWeek - 1 ) . ' days', $this->Time->toUnix( $schedule[ 'startDate' ] ) ) );
                }

                while ( $currentDay < $lastDay ) {
                    if ( $currentDay > $lastDay ) break;

                    // Check if currentDay is not a holiday
                    $holiday = false;
                    foreach ( $params[ 'holidays' ] as $name => $range ) {
                        if ( is_array( $range ) && $currentDay >= $range[ 0 ] && $currentDay <= $range[ 1 ] ) {
                            $holiday = true;
                        } elseif ( !is_array( $range ) && date( 'Ymd', $currentDay ) == $range ) {
                            $holiday = true;
                        }
                    }

                    if ( !$holiday ) {
                        // Find class location
                        if ( !empty( $class[ 'location' ] ) ) {
                            $local = $class[ 'location' ];
                            $sector = substr( $local, 0, strrpos( $local, ' ' ) );
                            $localNumber = substr( $local, strrpos( $local, ' ' ) + 1 );

                            if ( array_key_exists( $sector, $params[ 'sectors' ] ) ) {
                                $location = $params[ 'sectors' ][ $sector ] . ' ' . $localNumber;
                            } else {
                                $location = $sector . ', local ' . $localNumber;
                            }
                        } else {
                            $location = '';
                        }

                        // Add class to schedule events
                        $schedule[ 'events' ][] = array(
                            'title'     =>  $course[ 'Course' ][ 'title' ],
                            'code'      =>  $course[ 'Course' ][ 'code' ],
                            'location'  =>  $location,
                            'teacher'   =>  $class[ 'teacher' ],
                            'startDay'  =>  date( 'Y-m-d' , $currentDay ),
                            'startTime' =>  $class[ 'hour_start' ],
                            'endDay'    =>  date( 'Y-m-d' , $currentDay ),
                            'endTime'   =>  $class[ 'hour_end' ],
                            'allDay'    =>  false
                        );
                    }

                    // Increment current day
                    $currentDay += 3600*24*7;
                }
            }
        }
    */

        $currentWeekFirstDay = $schedule[ 'startDate' ];

        while ( $currentWeekFirstDay < $schedule[ 'endDate' ] ) {
            $timetable = array();
            $currentTime = '8';

            for( $hour = 8.0; $hour < 23; $hour += 0.5 ) {
                $currentTime = str_replace( ',', '.', $hour );

                if ( !isset( $timetable[ $currentTime ] ) ) {
                    $timetable[ $currentTime ] = array();
                }

                $currentUnixDay = strtotime( $currentWeekFirstDay );
                
                foreach ( $params[ 'weekdays' ] as $weekday => $index ) {
                    if ( !isset( $timetable[ $currentTime ][ $index ] ) ) {
                        $timetable[ $currentTime ][ $index ] = array();
                    }

                    if ( $index != 0 ) {
                        $currentUnixDay = strtotime( '+1 day', $currentUnixDay );
                    }

                    // Check if currentUnixDay is not a holiday
                    $holiday = false;
                    foreach ( $params[ 'holidays' ] as $name => $range ) {
                        if ( is_array( $range ) && $currentUnixDay >= $range[ 0 ] && $currentUnixDay <= $range[ 1 ] ) {
                            $holiday = true;
                        } elseif ( !is_array( $range ) && date( 'Ymd', $currentUnixDay ) == $range ) {
                            $holiday = true;
                        }
                    }

                    if ( $holiday ) continue;

                    // Check if a class start at this hour
                    $class = Set::extract( '/Course/Class[day=' . $weekday . '][hour_start=' . $currentTime . ']', $courses );

                    if ( !empty( $class ) ) {
                        $class = array_shift( array_shift( $class ) );

                        if ( $class[ 'date_start' ] <= date( 'Ymd', $currentUnixDay ) && $class[ 'date_end' ] >= date( 'Ymd', $currentUnixDay ) ) {

                            $schedule[ 'regularCourses' ]++;
                            $classLength = $class[ 'hour_end' ] - $class[ 'hour_start' ];

                            // Find class location
                            if ( !empty( $class[ 'location' ] ) ) {
                                $local = $class[ 'location' ];
                                $sector = substr( $local, 0, strrpos( $local, ' ' ) );
                                $localNumber = substr( $local, strrpos( $local, ' ' ) + 1 );

                                if ( array_key_exists( $sector, $params[ 'sectors' ] ) ) {
                                    $class[ 'locationShort' ] = $params[ 'sectors' ][ $sector ] . ' ' . $localNumber;
                                } else {
                                    $class[ 'locationShort' ] = $sector . ', local ' . $localNumber;
                                }
                            } else {
                                $class[ 'locationShort' ] = '';
                            }

                            $class[ 'teacher' ] = str_replace( ' (P)', '', $class[ 'teacher' ] );
                            $class[ 'code' ] = array_shift( Set::extract( '/Course[id=' . $class[ 'course_id' ] . ']/code', $courses ) );
                            $class[ 'semester' ] = $params[ 'semester' ];

                            $timetable[ $currentTime ][ $index ][ 'class' ] = $class;
                            $timetable[ $currentTime ][ $index ][ 'length' ] = $classLength;

                            if ( $classLength > 0.5 ) {
                                for( $i = ( $currentTime + 0.5 ); $i < ( $currentTime + $classLength ); $i += 0.5 ) {
                                    $i = str_replace( ',', '.', $i );
                                    if ( !isset( $timetable[ $currentTime ][ $index ] ) ) {
                                        $timetable[ $i ][ $index ] = array();
                                    }

                                    $timetable[ $i ][ $index ][ 'cellCollapse' ] = true;
                                }
                            }
                        }
                    }
                }
            }

            // Set textual dates for the current week
            setlocale( LC_ALL, 'fr_FR.UTF-8' );
            $datesText = '';
            $firstDay = $currentWeekFirstDay;
            $lastDay = date( 'Y-m-d', strtotime( '+5 days', strtotime( $currentWeekFirstDay ) ) );
            
            if ( substr( $firstDay, 5, 2 ) == substr( $lastDay, 5, 2 ) ) {
                // Same month

                $datesText = substr( $firstDay, 8, 2 ) . '&nbsp;&nbsp;&mdash;&nbsp;&nbsp;' . substr( $lastDay, 8, 2 ) . '&nbsp;&nbsp;' . date( 'M.', strtotime( $currentWeekFirstDay ) );
            } else {
                // Different month
                $datesText = substr( $firstDay, 8, 2 ) . '&nbsp;&nbsp;' . date( 'M.', strtotime( $currentWeekFirstDay ) ) . '&nbsp;&nbsp;&mdash;&nbsp;&nbsp;' . substr( $lastDay, 8, 2 ) . '&nbsp;&nbsp;' . date( 'M.', strtotime( $lastDay ) );
            }

            $schedule[ 'weeks' ][] = array(
                'dates'     =>  array( $firstDay, $lastDay ),
                'datesText' =>  $datesText,
                'timetable' =>  $timetable
            );

            // Increment current day
            $currentWeekFirstDay = date( 'Y-m-d', strtotime( '+7 days', strtotime( $currentWeekFirstDay ) ) );
            if ( $currentWeekFirstDay > $schedule[ 'endDate' ] ) break;
        }

        if ( $params[ 'semester' ] == CURRENT_SEMESTER ) {
            $schedule[ 'startDay' ] = ( int )date( 'd' );
            $schedule[ 'startMonth' ] = ( int )date( 'm' );
            $schedule[ 'startYear' ] = date( 'Y' );
        } else {
            $schedule[ 'startDay' ] = ( int )substr( $schedule[ 'startDate' ], 6, 2 );
            $schedule[ 'startMonth' ] = ( int )substr( $schedule[ 'startDate' ], 4, 2 );
            $schedule[ 'startYear' ] = substr( $schedule[ 'startDate' ], 0, 4 );
        }

        return $schedule;
    }
}

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

App::uses('Helper', 'View');

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

            return ($semester);
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

    public function buildTimetable ( $courses, $params = array() ) {
        $timetable = array(
            'otherCourses'      =>  0,
            'startDate'         =>  $params[ 'startDate' ],
            'events'            =>  array()
        );

        foreach( $courses as $course ) {
            foreach ( $course[ 'Course' ][ 'Class' ] as $class ) {
                // No day defined for this class, increment off-campus class and skip to the next class
                if ( empty( $class[ 'day' ] ) ) {
                    $timetable[ 'otherCourses' ]++;
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
                if ( date( 'Ymd', $firstDay ) < $timetable[ 'startDate' ] )
                    $timetable[ 'startDate' ] = date( 'Ymd', $firstDay );

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
                        // Calculate class start time
                        $startTime = floor( $class[ 'hour_start' ] );
                        if ( $startTime < 10 ) $startTime = '0' . $startTime;
                        $startTime .= ':' . ( ceil( $class[ 'hour_start' ] ) - $class[ 'hour_start' ] ) * 60;

                        // Calculate class end time
                        $endTime = floor( $class[ 'hour_end' ] );
                        if ( $endTime < 10 ) $endTime = '0' . $endTime;
                        $endTime .= ':' . ( ceil( $class[ 'hour_end' ] ) - $class[ 'hour_end' ] ) * 60;

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

                        // Add class to timetable events
                        $timetable[ 'events' ][] = array(
                            'title'     =>  $course[ 'Course' ][ 'title' ],
                            'code'      =>  $course[ 'Course' ][ 'code' ],
                            'location'  =>  $location,
                            'teacher'   =>  $class[ 'teacher' ],
                            'start'     =>  date( 'Y-m-d' , $currentDay ) . ' ' . $startTime . ':00',
                            'end'       =>  date( 'Y-m-d' , $currentDay ) . ' ' . $endTime . ':00',
                            'allDay'    =>  false
                        );
                    }

                    // Increment current day
                    $currentDay += 3600*24*7;
                }
            }
        }

        if ( $params[ 'semester' ] == CURRENT_SEMESTER ) {
            $timetable[ 'startDay' ] = (int)date( 'd' );
            $timetable[ 'startMonth' ] = (int)date( 'm' );
            $timetable[ 'startYear' ] = date( 'Y' );
        } else {
            $timetable[ 'startDay' ] = (int)substr( $timetable[ 'startDate' ], 6, 2 );
            $timetable[ 'startMonth' ] = (int)substr( $timetable[ 'startDate' ], 4, 2 );
            $timetable[ 'startYear' ] = substr( $timetable[ 'startDate' ], 0, 4 );
        }

        return $timetable;
    }
}

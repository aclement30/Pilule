<?php
	// Extract courses from schedule
	$courses = Set::extract( '/Course', $schedule );

	// Build timetable
	$timetable = $this->App->buildCalendar( $courses, array(
		'startDate'		=>	$semesterDates[ 0 ],
		'endDate'		=>	$semesterDates[ 1 ],
		'sectors'		=>	$sectors,
		'holidays'		=>	$holidays,
		'weekdays'		=>	$weekdays,
		'semester'		=>	$semester
	) );
?>
BEGIN:VCALENDAR
CALSCALE:GREGORIAN
X-WR-TIMEZONE;VALUE=TEXT:Canada/Eastern
METHOD:PUBLISH
PRODID:-//Pilule //NONSGML iCalendar Template//EN
X-WR-CALNAME;VALUE=TEXT:Université Laval
VERSION:2.0
<?php
	foreach ( $timetable[ 'events' ] as $event ) :
		// Format start/end times
		if ( floor( $event[ 'startTime' ] ) != $event[ 'startTime' ] ) {
			$event[ 'startTime' ] = str_replace( '.5', ':30', $event[ 'startTime' ] );
		} else {
			$event[ 'startTime' ] .= ':00';
		}

		if ( floor( $event[ 'endTime' ] ) != $event[ 'endTime' ] ) {
			$event[ 'endTime' ] = str_replace( '.5', ':30', $event[ 'endTime' ] );
		} else {
			$event[ 'endTime' ] .= ':00';
		}

		// Add 0 padding before hour, if hour is before 10
		if ( substr( $event[ 'startTime' ], 0, 1 ) > 2 ) {
			$event[ 'startTime' ] = '0' . $event[ 'startTime' ];
		}
		if ( substr( $event[ 'endTime' ], 0, 1 ) > 2 ) {
			$event[ 'endTime' ] = '0' . $event[ 'endTime' ];
		}
	?>
BEGIN:VEVENT
SEQUENCE:1
DTSTART;TZID=Canada/Eastern:<?php echo str_replace( ':', '', str_replace( '-', '', str_replace( ' ', 'T', $event[ 'startDay' ] . ' ' . $event[ 'startTime' ] . '00' ) ) ); ?>

SUMMARY:<?php echo $event[ 'title' ]; ?>

DTEND;TZID=Canada/Eastern:<?php echo str_replace( ':', '', str_replace( '-', '', str_replace( ' ', 'T', $event[ 'endDay' ] . ' ' . $event[ 'endTime' ] . '00' ) ) ); ?>

LOCATION:<?php echo $event[ 'location' ]; ?>

END:VEVENT
<?php endforeach; ?>
END:VCALENDAR
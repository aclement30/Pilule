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
<?php foreach ( $timetable[ 'events' ] as $event ) : ?>
BEGIN:VEVENT
SEQUENCE:1
DTSTART;TZID=Canada/Eastern:<?php echo str_replace( ':', '', str_replace( '-', '', str_replace( ' ', 'T', $event[ 'start' ] ) ) ); ?>

SUMMARY:<?php echo $event[ 'title' ]; ?>

DTEND;TZID=Canada/Eastern:<?php echo str_replace( ':', '', str_replace( '-', '', str_replace( ' ', 'T', $event[ 'end' ] ) ) ); ?>

LOCATION:<?php echo $event[ 'location' ]; ?>

END:VEVENT
<?php endforeach; ?>
END:VCALENDAR
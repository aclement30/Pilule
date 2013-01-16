<?php
	// Extract courses from schedule
	$courses = Set::extract( '/Course', $schedule );

	// Build timetable
	$timetable = $this->App->buildTimetable( $courses, array(
		'startDate'		=>	$startDate,
		'sectors'		=>	$sectors,
		'holidays'		=>	$holidays,
		'weekdays'		=>	$weekdays,
		'semester'		=>	$semester
	) );
?>

<div class="request-description">
	Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.
</div>


<?php
	// Display semesters dropdown
    if ( !empty( $semestersList ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $semestersList, 'selectedSemester' => $semester ) );
    endif;
?>

<?php
	// Display semesters dropdown
    if ( !empty( $semestersList ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $semestersList, 'selectedSemester' => $semester, 'compact' => true ) );
    endif;
?>

<?php /* ?>
	<div class="alert alert-info sharing-notice">
	    <div style="float: left;">
	    	L'horaire de cette session est disponible à l'adresse suivante : <input type="text" value="/public/t/3472n28h26G362HSG26U" />
	    </div>
	    <div style="float: right;">
	    	<a href="javascript:app.Schedule.share('<?php echo $semester; ?>',false);" class="btn btn-danger">
	    		<i class="icon-remove icon-white"></i> Annuler
	    	</a> 
	    	<a href="/support/faq/#faq7" class="btn">
	    		<i class="icon-info-sign"></i> Aide
	    	</a>
	    </div>
	    <div style="clear: both;"></div>
	</div>
<?php */ ?>

<div id="calendar"></div>
<!--
                </div>
                <div id="external-events" class="panel-right no-print">
                    <div class="panel-title">
                    	<h5>Autres cours</h5>
                    </div>
                    <div class="panel-content">
                        <?php
                        	foreach ( $courses as $course ) :
                        		foreach ($course[ 'Course' ][ 'Class' ] as $class ) :
                        			if ( empty( $class[ 'day' ] ) ) :
                        				?>
		                                <div class="class">

		                                    <div class="title"><?php echo $course[ 'Course' ][ 'title' ]; ?></div>
		                                    <div class="code"><?php echo $course[ 'Course' ][ 'code' ]; ?></div>
		                                    <div class="nrc">NRC : <?php echo $class[ 'nrc' ]; ?></div>

		                                    <div style="clear: both;"></div>
		                                    <hr style="margin: 5px 0;" />

		                                    <div class="type"><i class="icon-briefcase"></i> <?php echo $class[ 'type' ]; ?></div>

		                                    <?php if ( !empty( $class[ 'teacher' ] ) && strlen( $class[ 'teacher' ] ) > 2 ) : ?>
			                                    <div class="teacher">
			                                        <i class="icon-user"></i> <?php echo $class[ 'teacher' ]; ?>
			                                    </div>
		                                    <?php endif; ?>

		                                    <hr style="margin: 5px 0;" />

		                                    <div class="dates">
		                                        <i class="icon-calendar"></i> <?php echo $class[ 'date_start' ]; ?> &mdash; <?php echo $class[ 'date_end' ]; ?>
		                                    </div>
		                                </div>
		                                <?php
		                            endif;
                            	endforeach;
                            endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<script type="text/javascript">
	var timetable = <?php echo json_encode( $timetable ); ?>;
</script>
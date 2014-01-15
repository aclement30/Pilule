<?php
	// Extract courses from schedule
	$courses = Set::extract( '/Course', $schedule );

	// Build timetable
	$schedule = $this->App->buildTimetable( $courses, array(
		'startDate'		=>	$semesterDates[ 0 ],
		'endDate'		=>	$semesterDates[ 1 ],
		'sectors'		=>	$sectors,
		'holidays'		=>	$holidays,
		'weekdays'		=>	$weekdays,
		'semester'		=>	$semester
	) );
?>

<div class="request-description no-print">
	Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.
</div>

<div class="row-fluid calendar-header<?php if ( $schedule[ 'regularCourses' ] == 0 ) echo ' no-regular-courses' ?>">
	<div class="span4">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button class="btn js-prec-calendar"><i class="icon-chevron-left"></i></button>
				<button class="btn js-next-calendar"><i class="icon-chevron-right"></i></button>
			</div>
		</div>
	</div>
	<div class="span4 dates">
		<h4><?php echo $schedule[ 'weeks' ][ 0 ][ 'datesText' ]; ?></h4>
	</div>
	<div class="span4 calendar-semester">
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
	</div>
</div>

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

<?php
	$weekdaysText = array( 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' );

	$currentDay = $schedule[ 'startDate' ];
?>

<?php if ( $schedule[ 'regularCourses' ] != 0 ) : ?>

	<div id="mobile-agenda">
		<?php foreach ( $schedule[ 'weeks' ] as $weekIndex => $week ) : ?>
			<div class="week week<?php echo $weekIndex; if ( $weekIndex ==  0 ) echo ' current'; ?>" data-week="<?php echo $weekIndex; ?>" data-title="<?php echo $week[ 'datesText' ]; ?>">
				<?php
					foreach ( $weekdays as $weekday => $index ) :
						$dayClasses = array();
						foreach ( $week[ 'timetable' ] as $hour ) {
							if ( !empty( $hour[ $index ][ 'class' ] ) ) {
								$dayClasses[] = $hour[ $index ][ 'class' ];
							}
						}

						if ( !empty( $dayClasses ) ) :
							?><h5><?php echo $weekdaysText[ $index ]; ?></h5><?php

							foreach ( $dayClasses as $class ) :
								// Extract the corresponding course
								$course = Set::extract( '/Course[id=' . $class[ 'course_id' ] . ']', $courses );

								if ( !empty( $course ) ) {
									$course = array_shift( array_shift( $course ) );
								}
			    				?>
			                    <div class="class row-fluid">
			                    	<div class="hours">
			                    		<span class="start">
			                    			<?php
			                    				echo str_replace( '.5', ':30', $class[ 'hour_start' ] );
			                    				if ( floor( $class[ 'hour_start' ] ) == $class[ 'hour_start' ] ) echo ':00';
			                    			?>
			                    		</span><br>
			                    		<span class="end">
			                    			<?php
			                    				echo str_replace( '.5', ':30', $class[ 'hour_end' ] );
			                    				if ( floor( $class[ 'hour_end' ] ) == $class[ 'hour_end' ] ) echo ':00';
			                    			?>
			                    		</span>
			                    	</div>
			                    	<div class="details">
			                    		<div class="code"><?php echo $course[ 'code' ]; ?></div>
				                        <div class="title"><?php echo $course[ 'title' ]; ?></div>

				                        <hr>

				                        <div class="type"><i class="icon-briefcase"></i> <?php echo $class[ 'type' ]; ?></div>

				                        <?php if ( !empty( $class[ 'teacher' ] ) && strlen( $class[ 'teacher' ] ) > 2 ) : ?>
				                            <div class="teacher">
				                                <i class="icon-user"></i> <?php echo $class[ 'teacher' ]; ?>
				                            </div>
				                        <?php endif; ?>
				                        <?php if ( !empty( $class[ 'location' ] ) && strlen( $class[ 'location' ] ) > 2 ) : ?>
				                            <div class="location">
				                                <i class="icon-map-marker"></i> <?php echo $class[ 'locationShort' ]; ?>
				                            </div>
				                        <?php endif; ?>
				                    </div>
			                    </div>
			                    <hr>
			                    <?php
				        	endforeach;
				        endif;
				    endforeach;
		        ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div id="agenda">
		<?php foreach ( $schedule[ 'weeks' ] as $weekIndex => $week ) : ?>
			<table class="table bordered week week<?php echo $weekIndex; if ( $weekIndex ==  0 ) echo ' current'; ?>" data-week="<?php echo $weekIndex; ?>" data-title="<?php echo $week[ 'datesText' ]; ?>">
				<thead>
					<th>&nbsp;</th>
					<?php
						foreach ( $weekdaysText as $index => $weekday ) {
							if ( $index == 5 ) break;

							echo '<th>' . $weekday . '</th>';
						}
					?>
				</thead>
				<tbody>
					<?php
						for( $hour = 8.0; $hour < 23; $hour += 0.5 ) {
							$oddCell = false;
							$hour = str_replace( ',', '.', $hour );

							if ( floor( $hour ) == $hour ) {
								$oddCell = true;
							}
							?>
							<tr>
								<th class="hour">
									<?php if ( $oddCell ) echo $hour . ':00'; ?>
								</th>
								<?php
									foreach ( $weekdaysText as $key => $weekday ) {
										if ( $key == 5 ) break;

										$cell = $week[ 'timetable' ][ $hour ][ $key ];
										if ( !empty( $cell ) ) {
											if ( isset( $cell[ 'cellCollapse' ] ) && $cell[ 'cellCollapse' ] ) {
												// Cell collapsing from above : do nothing
											} else {
												// Extract the corresponding course
												$course = Set::extract( '/Course[id=' . $cell[ 'class' ][ 'course_id' ] . ']', $courses );

												if ( !empty( $course ) ) {
													$course = array_shift( array_shift( $course ) );
												}

												// Display class
												$classNames = array( 'class' );

												if ( $cell[ 'length' ] == 0.5 ) {
													$classNames[] = 'length-half';
												} elseif ( $cell[ 'length' ] == 1 ) {
													$classNames[] = 'length-hour';
												} elseif ( $cell[ 'length' ] == 1.5 ) {
													$classNames[] = 'length-hour-half';
												}
												?>
												<td class="<?php echo implode( ' ', $classNames ) ?>" rowspan="<?php echo ( $cell[ 'length' ] * 2 ); ?>">
													<div class="inside" data-title="<?php echo $course[ 'code' ]; ?>" data-placement="top" data-trigger="hover" data-content="<?php echo $course[ 'title' ]; ?>">
														<div class="code"><?php echo $course[ 'code' ]; ?></div>
														<div class="title">
															<span class="short"><?php echo $this->Text->truncate( $course[ 'title' ], 35, array( 'exact' => false ) ); ?></span>
															<span class="full"><?php echo $course[ 'title' ]; ?></span>
														</div>
														<?php if ( !empty( $cell[ 'class' ][ 'teacher' ] ) ): ?>
															<div class="teacher"><i class="icon-user icon-white"></i> <?php echo $cell[ 'class' ][ 'teacher' ]; ?></div>
														<?php endif; ?>
														<?php if ( !empty( $cell[ 'class' ][ 'locationShort' ] ) ): ?>
															<div class="location"><i class="icon-map-marker icon-white"></i><span class="label">Local :</span> <?php echo $cell[ 'class' ][ 'locationShort' ]; ?></div>
														<?php else: ?>
															<div class="location"><i class="icon-briefcase icon-white"></i> <?php echo $cell[ 'class' ][ 'type' ]; ?></div>
														<?php endif; ?>
													</div>
												</td>
												<?php
											}
										} else {
											// Display empty cell
											?>
											<td class="empty">&nbsp;</td>
											<?php
										}
									}
								?>
							</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		<?php endforeach; ?>
		<hr>
	</div>
<?php endif; ?>

<?php
	$externalEvents = Set::extract( '/Course/Class[day=]', $courses );

	if ( !empty( $externalEvents ) ) :
		?>
		<div id="external-events" class="no-print">
		    <h4>Autres cours</h4>
		    <div class="row-fluid">
			    <?php
			    	foreach ( $courses as $course ) :
			    		foreach ( $course[ 'Course' ][ 'Class' ] as $class ) :
			    			if ( empty( $class[ 'day' ] ) ) :
			    				?>
			    				<div class="class span4">
			                    	<div class="details">
			                    		<div class="code"><?php echo $course[ 'Course' ][ 'code' ]; ?></div>
				                        <div class="title"><?php echo $course[ 'Course' ][ 'title' ]; ?></div>
				                        <div class="nrc">NRC : <?php echo $class[ 'nrc' ]; ?></div>
				                        <hr>

				                        <div class="type"><i class="icon-briefcase"></i> <?php echo $class[ 'type' ]; ?></div>

				                        <?php if ( !empty( $class[ 'teacher' ] ) && strlen( $class[ 'teacher' ] ) > 2 ) : ?>
				                            <div class="teacher">
				                                <i class="icon-user"></i> <?php echo $class[ 'teacher' ]; ?>
				                            </div>
				                        <?php endif; ?>
				                        
				                        <div class="dates">
				                            <i class="icon-calendar"></i> 
				                            <?php
				                            	echo date( 'd.m.Y', strtotime( substr( $class[ 'date_start' ], 0, 4 ) . '-' . substr( $class[ 'date_start' ], 4, 2 ) . '-' . substr( $class[ 'date_start' ], 6, 2 ) ) );
				                            	echo ' &mdash; ';
				                            	echo date( 'd.m.Y', strtotime( substr( $class[ 'date_end' ], 0, 4 ) . '-' . substr( $class[ 'date_end' ], 4, 2 ) . '-' . substr( $class[ 'date_end' ], 6, 2 ) ) );
				                            ?>
				                        </div>
				                    </div>
			                    </div>
			                    <?php
			                endif;
			        	endforeach;
			        endforeach;
			    ?>
			</div>
		</div>
		<?php
	endif;
?>
<div class="row-fluid">
	<?php
		$weekdays = array(
	        'L' =>  'Lun',
	        'M' =>  'Mar',
	        'R' =>  'Mer',
	        'J' =>  'Jeu',
	        'V' =>  'Ven',
	        'S' =>  'Sam'
	    );

		$number = 0;
		foreach ( $classes as $class ) :
			$number++
			?>
			<div class="class span4" data-nrc="<?php echo $class[ 'nrc' ]; ?>" style="<?php if ($number%3==0) echo 'margin-right: 0px;'; ?>">
				<div class="clearfix">
					<div class="type"><?php echo $class[ 'timetable' ][ 0 ][ 'type' ]; ?></div>
					<div class="semester">
						<?php echo $this->App->convertSemester( $class[ 'semester' ], true ); ?>
					</div>
				</div>
				<hr>
				<?php if ( trim( $class[ 'timetable' ][0][ 'day' ] ) != '' && $class[ 'timetable' ][0][ 'day' ] != ' ' ): ?>
					<div class="timetable">
						<?php
							$lastStartEndDates = '';

							foreach ( $class[ 'timetable' ] as $timetableClass ):
								?>
							<table cellspacing="0">
								<tbody>
									<tr>
										<td colspan="4" class="dates">
											<?php
												if ( ($timetableClass[ 'day_start' ] . $timetableClass[ 'day_end' ] ) != $lastStartEndDates ) {
													echo strtolower( date( 'd/m/Y', strtotime( substr( $timetableClass[ 'day_start' ], 0, 4 ) . '-' . substr( $timetableClass[ 'day_start' ], 4, 2 ) . '-' . substr( $timetableClass[ 'day_start' ], 6, 2 ) ) ) . " - " . date( 'd/m/Y', strtotime( substr( $timetableClass[ 'day_end' ], 0, 4 ) . '-' . substr( $timetableClass[ 'day_end' ], 4, 2 ) . '-' . substr( $timetableClass[ 'day_end' ], 6, 2 ) ) ) );
												}
												$lastStartEndDates = $timetableClass[ 'day_start' ].$timetableClass[ 'day_end' ];
											?>
										</td>
									</tr>
									<tr>
										<?php
											if (trim($timetableClass['day']) != '' and $timetableClass['day'] != ' '):
												?>
												<td style="width: 45%; padding-top: 5px;">
													<?php
														switch ($timetableClass['type']) {
															case 'Cours en classe':
																echo 'Classe';
															break;
															case 'Laboratoire':
																echo 'Lab.';
															break;
															case 'Classe virtuelle synchrone':
																echo 'C. virt. sync.';
															break;
															default:
																echo $timetableClass['type'];
															break;
														}
													?>
												</td>
												<td style="color: black; padding-top: 5px; font-size: 9pt; font-family: 'Lucida Console', Monaco, monospace;">
													<div style="background-color: #666; color: #fff; line-height: 8pt; padding: 2px 3px; float: left;">
														<?php echo strtoupper( substr( $weekdays[ $timetableClass[ 'day' ] ], 0, 3 ) ); ?>
													</div>
													<div style="clear: both;"></div>
												</td>
												<td style="text-align: right; padding-top: 5px;">
													<?php echo $timetableClass['hour_start'] . " - " . $timetableClass['hour_end']; ?></td>
												<?php
											endif;
										?>
									</tr>
								</tbody>
							</table>
						<?php
					endforeach;
							?>
						</div>
						<?php
					else:
						// Class does not have weekly schedule : display only start/end dates
						?>
						<div class="timetable" style="border-bottom: 1px dotted silver; padding-bottom: 5px;">
							<?php echo strtolower( date( 'd/m/Y', strtotime( substr( $class[ 'timetable' ][ 0 ][ 'day_start' ], 0, 4 ) . '-' . substr( $class[ 'timetable' ][ 0 ][ 'day_start' ], 4, 2 ) . '-' . substr( $class[ 'timetable' ][ 0 ][ 'day_start' ], 6, 2 ) ) ) . " - " . date( 'd/m/Y', strtotime( substr( $class[ 'timetable' ][ 0 ][ 'day_end' ], 0, 4 ) . '-' . substr( $class[ 'timetable' ][ 0 ][ 'day_end' ], 4, 2 ) . '-' . substr( $class[ 'timetable' ][ 0 ][ 'day_end' ], 6, 2 ) ) ) ); ?>
						</div>
						<?php
					endif;

					// Display available spots or waiting list status
					if ( !empty( $class[ 'Spot' ] ) ):
						$className = '';

						if ( $class[ 'Spot' ][ 'total' ] != 0 ):
							if ( $class[ 'Spot' ][ 'remaining' ] == 0 ) {
								$className = 'full';
							} elseif ( $class[ 'Spot' ][ 'remaining' ] <= 5 ) {
								$className = 'almost-full';
							}
							?>
							<div class="spots">
								Places disponibles : <span class="<?php echo $className; ?>"><?php echo $class[ 'Spot' ][ 'remaining' ]; ?></span>
							</div>
							<?php
						elseif ( $class[ 'Spot' ][ 'waiting_total' ] > 0 && $class[ 'Spot' ][ 'remaining' ] == 0 ):
							if ( $class[ 'Spot' ][ 'waiting_remaining' ] == 0 ) {
								$className = 'full';
							} elseif ( $class[ 'Spot' ][ 'waiting_remaining' ] <= 5 ) {
								$className = 'almost-full';
							}
							?>
							<div class="spots">
								Liste d'attente : <span class="<?php echo $className; ?>"><?php echo $class[ 'Spot' ][ 'waiting_remaining' ]; ?></span>
							</div>
							<?php
						endif;
					else:
						?>
						<div class="spots clearfix">
							Actualisation des places...<img src="<?php echo Router::url( '/' ) ?>img/loading-btn.gif" style="float: right;" />
						</div>
						<?php
					endif;

					// Display class notes, if any
					if ( !empty( $class[ 'notes' ] ) ):
						?>
						<hr>
						<div class="notes" title="<?php echo $class[ 'notes' ]; ?>">
							<?php
								if ( md5( $class[ 'notes' ] ) == '6496af59f3e58084b2a48b4fb93bf696' ):
									echo 'Section accessible aux étudiants provenant des autres programmes.';
								else:
									echo $this->text->Truncate( $class[ 'notes' ], 80, array( 'ellipsis' => '...' ) );
								endif;
							?>
						</div>
						<?php
					endif;

					if ( $class[ 'campus' ] != 'Principal' ):
						?>
						<div><?php echo $class[ 'campus' ]; ?></div>
						<?php
					endif;

					if ( !empty( $class['teacher'] ) ):
						?>
						<div>
							<i class="icon-user"></i>&nbsp;<?php echo $class[ 'teacher' ]; ?>
						</div>
						<?php
					endif;
				?>
				<hr>
				<div class="clearfix">
					<div class="nrc">NRC : <strong><?php echo $class[ 'nrc' ]; ?></strong></div>
					<div class="registration-state">
						<?php
							if ( in_array( $class[ 'nrc' ], $registeredCourses ) ) {
								echo '<span class="registered">Inscrit</span>';
							} elseif ( in_array( $class[ 'nrc' ], $selectedCourses ) ) {
								echo '<span class="selected">Sélectionné</span>';
							} else {
								echo $this->Html->link( '<i class="icon-plus"></i>&nbsp;Ajouter', '#', array( 'class' => 'btn btn-mini js-select-btn', 'escape' => false ) );
							}
						?>
						<img src="<?php echo Router::url( '/' ) ?>img/loading-btn.gif" class="loading-img" />
					</div>
				</div>
			</div>
		<?php
			if ( $number % 3 == 0 ) {
				?></div><div class="row-fluid"><?php
			}
		endforeach;
	?>
</div>
<div class="row-fluid">
	<?php
		$number = 0;
		foreach ( $classes as $class ) :
			$number++
			?>
			<div class="class span4" data-nrc="<?php echo $class[ 'nrc' ]; ?>" style="<?php if ($number%3==0) echo 'margin-right: 0px;'; ?>">
				<div>
					<div class="type"><?php echo $class[ 'timetable' ][ 0 ][ 'type' ]; ?></div>
					<div class="semester">
						<?php echo $this->App->convertSemester( $class[ 'semester' ], true ); ?>
					</div>
					<div style="clear: both;"></div>
				</div>
				<?php /*
			<?php if ( trim( $class[ 'timetable' ][0][ 'day' ] ) != '' && $class[ 'timetable' ][0][ 'day' ] != ' ' ): ?>
				<div class="timetable">
					<?php foreach ( $class[ 'timetable' ] as $timetableClass ): ?>
						<table cellspacing="0">
							<tbody>
								<tr>
									<td colspan="4" class="dates">
										<?php echo strtolower( date( 'd m Y', $timetableClass[ 'day_start' ] ) . " - " . date( 'd M Y', $timetableClass[ 'day_end' ] ) ); ?>
									</td>
								</tr>
								<tr>
									<?php
										$n = 0;
										foreach ($classes as $class2):
											if (trim($class2['day']) != '' and $class2['day'] != ' '):
												?>
												<td style="width: 40%; padding-top: 5px;">
													<?php
														switch ($class2['type']) {
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
																echo $class2['type'];
															break;
														}
													?>
												</td>
												<td style="color: black; padding-top: 5px; font-size: 9pt; font-family: 'Lucida Console', Monaco, monospace;">
													<div style="background-color: #666; color: #fff; line-height: 8pt; padding: 2px 3px; float: left;">
														<?php echo strtoupper(substr($weekdays[$class2['day']], 0, 3)); ?>
													</div>
													<div style="clear: both;"></div>
												</td>
												<td style="text-align: right; padding-top: 5px;"><?php echo $class2['hour_start']." - ".$class2['hour_end']; ?></td>
												<?php
												$n++;
											endif;
										endforeach;
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
					?>
					<div class="timetable" style="border-bottom: 1px dotted silver; padding-bottom: 5px;">
						<?php echo strtolower(currentDate($class['timetable'][0]['day_start'], 'd M Y')." - ".currentDate($class['timetable'][0]['day_end'], 'd M Y')); ?>
					</div>
					<?php
				endif;
				*/
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
					endif;

					// Display class notes, if any
					if ( !empty( $class[ 'notes' ] ) ):
						?>
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
				</div>
				<div style="clear: both;"></div>
			</div>
		<?php
			if ( $number % 3 == 0 ) {
				?></div><div class="row-fluid"><?php
			}
		endforeach;
	?>
</div>
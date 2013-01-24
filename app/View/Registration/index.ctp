<div style="float: left; font-weight: bold; padding-top: 7px;"><?php echo $user['program']; ?></div>

<div class="buttons semester-select">
	<div class="btn-group" style="float: right;">
	    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
	        <?php echo convertSemester( $registrationSemester ); ?>
	        <span class="caret"></span>
	    </a>
	    <ul class="dropdown-menu pull-right">
	        <?php
	        	foreach ( $registrationSemesters as $semester ):
	        		?><li><a href="javascript:app.Registration.displaySemester(<?php echo $semester; ?>);"<?php if ( $semester == $registrationSemester) echo ' style="font-weight: bold;"'; ?>><?php echo convertSemester( $semester ); ?></a></li><?php
	    		endforeach;
	    	?>
	    </ul>
	</div>
	<div style="float: right; margin-top: 5px; color: grey; margin-right: 5px;">Session : </div>
</div>

<div style="clear: both; height: 10px;"></div>

<div class="row-fluid">
	<div class="span8">
		<?php
		foreach ($sections as $section) {
			if (in_array($section['id'], $userSections) || $section['compulsory'] == '1') {
				?><h3><?php echo $section['title'];
				if ($section['children'] != array()) {
					foreach ($section['children'] as $subsection) {
						if (in_array($subsection['id'], $userSections)) {
							echo ' : '.$subsection['title'];
							break;
						}
					}
				}
				?></h3><?php
				if (isset($programCourses[$section['code']]) and $programCourses[$section['code']]!=array()) {
					if ($section['notes'] != '') {
						?><div class="notes"><?php echo str_replace("\n", "<br />", $section['notes']); ?></div><?php
					}
					?>
					<div class="row-fluid">

				    <div class="span12">
				        <div class="widget-box" style="margin-bottom: 0px;">
				            <div class="widget-content nopadding">
				                <table class="table courses table-bordered table-striped">
									<thead>
										<tr>
											<th style="font-weight: bold; text-align: left; width: 15%;">Cours</th>
											<th style="font-weight: bold; text-align: left; width: 45%;">Titre</th>
											<th style="font-weight: bold; text-align: center;">Session</th>
											<th style="font-weight: bold; text-align: center;">Crédits</th>
											<th style="font-weight: bold; text-align: center;">Note</th>
										</tr>
									</thead>
									<tbody
										<?php
											for ( $n = 1; $n < 5; $n++ ):
												foreach ( $programCourses[ $section[ 'code' ] ] as $course ):
													if ($course['level']==$n):
														?>
														<tr data-code="<?php echo $course['code']; ?>" class="<?php if (!$course[ 'av' . $registrationSemester ]) echo 'unavailable'; ?>">
															<td class="level<?php echo $course[ 'level' ]; ?>"><?php echo $course['code']; ?></td>
															<td class="level<?php echo $course[ 'level' ]; ?>"><?php echo $course['title']; ?></td>
															<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;">
																<?php
																	if ( isset( $course[ 'semester' ] ) ):
																		if ( strlen( $course[ 'semester' ] ) == 4 ):
																			echo $course['semester'];
																		else:
																			echo convertSemester( $course[ 'semester' ], true );
																		endif;
																	endif;
																?>
															</td>
															<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;"><?php echo $course[ 'credits' ]; ?></td>
															<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;"><?php if ( isset( $course[ 'note' ] ) ) echo $course[ 'note' ]; ?></td>				
														</tr>
														<?php
													endif;
												endforeach;
											endfor;
										?>
									</tbody>
								</table>
								</div>
					        </div>
					    </div>
					</div><!-- End of row-fluid -->
					<?php
				}
				
				foreach ($section['children'] as $subsection) {
					if (in_array($subsection['id'], $userSections) || $subsection['compulsory'] == '1') {
						if ($subsection['notes'] != '') {
							?><div class="notes"><?php echo str_replace("\n", "<br />", $subsection['notes']); ?></div><?php
						}
						
						if (isset($programCourses[$subsection['code']]) and $programCourses[$subsection['code']]!=array()) { ?>
								<div class="row-fluid">

							    <div class="span12">
							        <div class="widget-box" style="margin-bottom: 0px;">
							            <div class="widget-title">
							                                    <span class="icon">
							                                        <i class="icon-th"></i>
							                                    </span>
							                <?php if (isset($programCourses[$subsection['code']]) and $programCourses[$subsection['code']]!=array() and $subsection['credits'] == '') {
												?><h5 style="margin-bottom: 5px; float: left;"><?php if ($subsection['compulsory'] == '1') echo $subsection['title']; else echo 'Cours obligatoires'; ?></h5><?php
												if ($subsection['credits'] != '') {
													?><h5 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection['credits'])) echo $subsection['credits']; else echo str_replace("/", " à ", $subsection['credits']); ?> crédits</h5><?php
												}
												?><div style="clear: both;"></div><?php
											} elseif ($subsection['credits'] != '') {
												?><h5 style="margin-bottom: 5px; float: left;"><?php if ($subsection['compulsory'] == '1') echo $subsection['title']; else echo 'Cours disponibles'; ?></h5><?php
												if ($subsection['credits'] != '') {
													?><h5 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection['credits'])) echo $subsection['credits']; else echo str_replace("/", " à ", $subsection['credits']); ?> crédits</h5><?php
												}
												?><div style="clear: both;"></div><?php
											} ?>
							            </div>
							            <div class="widget-content nopadding">
							                <table class="table courses table-bordered table-striped">
							                    <thead>
								                    <tr>
														<th style="font-weight: bold; text-align: left; width: 15%;">Cours</th>
														<th style="font-weight: bold; text-align: left; width: 45%;">Titre</th>
														<th style="font-weight: bold; text-align: center;">Session</th>
														<th style="font-weight: bold; text-align: center;">Crédits</th>
														<th style="font-weight: bold; text-align: center;">Note</th>
													</tr>
												</thead>
												<tbody>
										<?php
											for ( $n = 1; $n < 5; $n++ ):
												foreach ( $programCourses[ $subsection[ 'code' ] ] as $course ):
													if ($course['level']==$n):
														?>
														<tr data-code="<?php echo $course['code']; ?>" class="<?php if (!$course[ 'av' . $registrationSemester ]) echo 'unavailable'; ?>">
															<td class="level<?php echo $course[ 'level' ]; ?>"><?php echo $course['code']; ?></td>
															<td class="level<?php echo $course[ 'level' ]; ?>"><?php echo $course['title']; ?></td>
															<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;">
																<?php
																	if ( isset( $course[ 'semester' ] ) ):
																		if ( strlen( $course[ 'semester' ] ) == 4 ):
																			echo $course['semester'];
																		else:
																			echo convertSemester( $course[ 'semester' ], true );
																		endif;
																	endif;
																?>
															</td>
															<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;"><?php echo $course[ 'credits' ]; ?></td>
															<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;"><?php if ( isset( $course[ 'note' ] ) ) echo $course[ 'note' ]; ?></td>				
														</tr>
														<?php
													endif;
												endforeach;
											endfor;
										?>
									</tbody>
								</table>
								</div>
					        </div>
					    </div>
					</div><!-- End of row-fluid -->
							<?php
						}
						
						foreach ($subsection['children'] as $subsection2) {
								if ($subsection2['notes'] != '') {
									?><div class="notes"><?php echo str_replace("\n", "<br />", $subsection2['notes']); ?></div><?php
								}
								
								if ($programCourses[$subsection2['code']]!=array()) { ?>
									<div class="row-fluid">

								    <div class="span12">
								        <div class="widget-box" style="margin-bottom: 0px;">
								            <div class="widget-title">
								                                    <span class="icon">
								                                        <i class="icon-th"></i>
								                                    </span>
								                <h5 style="margin-bottom: 5px; float: left;"><?php echo $subsection2['title']; ?></h5><?php
												if ($subsection2['credits'] != '') {
													?><h5 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection2['credits'])) echo $subsection2['credits']; else echo str_replace("/", " à ", $subsection2['credits']); ?> crédits</h5><?php
												}
												?><div style="clear: both;"></div>
								            </div>
								            <div class="widget-content nopadding">
								                <table class="table courses table-bordered table-striped">
								                    <thead>
														<tr>
															<th style="font-weight: bold; text-align: left; width: 15%;">Cours</th>
															<th style="font-weight: bold; text-align: left; width: 45%;">Titre</th>
															<th style="font-weight: bold; text-align: center;">Session</th>
															<th style="font-weight: bold; text-align: center;">Crédits</th>
															<th style="font-weight: bold; text-align: center;">Note</th>
														</tr>
													</thead>
													<tbody>
														<?php
															for ( $n = 1; $n < 5; $n++ ):
																foreach ( $programCourses[ $subsection2[ 'code' ] ] as $course ):
																	if ($course['level']==$n):
																		?>
																		<tr data-code="<?php echo $course['code']; ?>" class="<?php if (!$course[ 'av' . $registrationSemester ]) echo 'unavailable'; ?>">
																			<td class="level<?php echo $course[ 'level' ]; ?>"><?php echo $course['code']; ?></td>
																			<td class="level<?php echo $course[ 'level' ]; ?>"><?php echo $course['title']; ?></td>
																			<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;">
																				<?php
																					if ( isset( $course[ 'semester' ] ) ):
																						if ( strlen( $course[ 'semester' ] ) == 4 ):
																							echo $course['semester'];
																						else:
																							echo convertSemester( $course[ 'semester' ], true );
																						endif;
																					endif;
																				?>
																			</td>
																			<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;"><?php echo $course[ 'credits' ]; ?></td>
																			<td class="level<?php echo $course[ 'level' ]; ?>" style="text-align: center;"><?php if ( isset( $course[ 'note' ] ) ) echo $course[ 'note' ]; ?></td>				
																		</tr>
																		<?php
																	endif;
																endforeach;
															endfor;
														?>
													</tbody>
												</table>
											</div>
								        </div>
								    </div>
								</div><!-- End of row-fluid -->
								<?php
							}
						}
					}
				}
			}
		}
		?>
	</div>
</div>
<div id="modal-course" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true"></div>
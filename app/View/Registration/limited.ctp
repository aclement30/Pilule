<div style="float: left; font-weight: bold; padding-top: 7px;"><?php echo $user[ 'program' ]; ?></div>

<?php
    if ( !empty( $registrationSemesters ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $registrationSemesters, 'selectedSemester' => $registrationSemester ) );
    endif;
?>

<div style="clear: both; height: 10px;"></div>

<div class="row-fluid">
	<div class="span8">
		<?php
		    foreach ( $sections as $section ) :
		        $creditsCompleted = 0;
		        $isCompleted = false;

		        if ( empty( $section[ 'Course' ] ) || $section[ 'Section' ][ 'title' ] == 'Cours échoués' ) continue;

		        foreach ( $section[ 'Course' ] as $course ) {
		            if ( !empty( $course[ 'note' ] ) )
		                $creditsCompleted += $course[ 'credits' ];
		        }

		        if ( $creditsCompleted == $section[ 'Section' ][ 'credits' ] )
		            $isCompleted = true;

		        ?>
		        <div class="row-fluid">
		            <div class="span12">
		                <div class="widget-box<?php if ( $isCompleted ) echo ' completed'; ?>">
		                    <div class="widget-title">
		                        <span class="icon">
		                            <?php if ( $isCompleted ) echo '<i class="icon-ok"></i>'; else echo '<i class="icon-th"></i>'; ?>
		                        </span>
		                        <h5><?php echo $section[ 'Section' ][ 'title' ]; ?></h5>
		                    </div>
		                    <div class="widget-content nopadding">
		                        <table class="table table-bordered table-striped courses">
		                            <thead>
		                                <tr>
		                                    <th class="course-code">Cours</th>
		                                    <?php if ( !$isMobile ) : ?>
		                                        <th class="title">Titre</th>
		                                    <?php endif; ?>
		                                    <th class="semester">Session</th>
		                                    <th class="credits">Crédits</th>
		                                    <th class="note">Note</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <?php foreach ( $section[ 'Course' ] as $course ) : ?>
		                                    <tr class="<?php if ( empty( $course[ 'note' ] ) ) echo 'current'; ?>" data-code="<?php echo $course[ 'code' ]; ?>">

		                                        <?php if ( $isMobile ) : ?>
		                                            <td class="mobile-title">
		                                                <strong><?php echo $course[ 'code' ]; ?></strong><br />
		                                                <span><?php echo $course[ 'title' ]; ?></span>
		                                            </td>
		                                        <?php else : ?>
		                                            <td class="code"><?php echo $course[ 'code' ]; ?></td>
		                                            <td class="title"><?php echo $course[ 'title' ]; ?></td>
		                                        <?php endif; ?>

		                                        <td class="semester"><?php if ( !empty( $course[ 'semester' ] ) ) echo $this->App->convertSemester( $course[ 'semester' ], true ); ?></td>
		                                        <td class="credits"><?php echo $course[ 'credits' ]; ?></td>
		                                        <td class="note">
		                                            <?php
		                                                switch ( $course[ 'note' ] ) {
		                                                    case 'AUD':
		                                                        echo '<span class="label">Auditeur</span>';
		                                                        break;
		                                                    case 'NA':
		                                                        echo '<span class="label">Non évalué</span>';
		                                                        break;
		                                                    case 'V':
		                                                        echo '<span class="label label-info">Équivalence</span>';
		                                                        break;
		                                                    case 'X':
		                                                        echo '<span class="label" title="Abandon sans échec">Abandon</span>';
		                                                        break;
		                                                    case 'N':
		                                                        echo '<span class="label" title="Échec non contributoire">Échec (N)</span>';
		                                                        break;
		                                                    case 'W':
		                                                        echo '<span class="label label-important">Échec (W)</span>';
		                                                        break;
		                                                    case 'E':
		                                                        echo '<span class="label label-important">Échec</span>';
		                                                        break;
		                                                    default:
		                                                        echo $course[ 'note' ];
		                                                }
		                                            ?>
		                                        </td>
		                                    </tr>
		                                <?php endforeach; ?>
		                                <tr>
		                                    <th class="left" colspan="<?php if ( $isMobile ) echo 2; else echo 3; ?>">Total</th>
		                                    <td class="total-credits">
		                                        <?php
		                                            echo $creditsCompleted;
		                                            
		                                            if ( !empty( $section[ 'Section' ][ 'credits' ] ) ) echo ' / ' . $section[ 'Section' ][ 'credits' ];
		                                        ?>
		                                    </td>
		                                    <td>&nbsp;</td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
		        </div><!-- End of row-fluid -->
		        <?php
		    endforeach;
		?>
	</div>
	<div class="span4">
		<div id="registered-courses" style="margin-top: 27px;">
			<div class="row-fluid">
			    <div class="span12">
			        <div class="widget-box" style="margin-bottom: 0px;">
			            <div class="widget-title">
		                    <span class="icon">
		                        <i class="icon-th"></i>
		                    </span>
			                <h5 style="margin-bottom: 5px;"><?php echo $this->App->convertSemester( $registrationSemester, true ) ?> : Cours inscrits</h5>
			            </div>
			            <div class="widget-content nopadding">
			                <table class="table courses courses-list table-bordered table-striped">
			                    <thead>
									<tr>
										<th style="font-weight: bold; text-align: left;">Cours</th>
										<th style="font-weight: bold; text-align: center; width: 25%;">NRC</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$credits = 0;
										if ( is_array( $registeredCourses ) ):
											foreach ( $registeredCourses as $course ):
												?>
												<tr data-nrc="<?php echo $course[ 'nrc' ]; ?>">
													<td style="font-size: 8pt;">
														<?php
															if ( strlen( $course[ 'title' ] ) > 35 ):
																echo substr( $course[ 'title' ], 0, 30 ) . "...";
															else:
																echo $course[ 'title' ];
															endif;
														?>
														<br />
														NRC : <?php echo $course['nrc']; ?>
													</td>
													<td style="font-weight: bold; text-align: right;">
														<?php echo $course['code']; ?>
														<br />
														<a href="#" class="btn delete-link"><i class="icon-remove"></i></a>
													</td>
												</tr>
												<?php
												$credits += $course[ 'credits' ];
											endforeach;
										endif;
									?>
									<tr>
										<td colspan="2">
											<div class="courses-total" style="font-weight: bold; float: left;">
												<?php if (is_array($registeredCourses)) echo count($registeredCourses); else echo 0; ?> cours
											</div>
											<div class="credits-total" style="float: right;"><?php echo $credits; ?> crédits</div>
											<div style="clear: both;"></div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
			        </div>
			    </div>
			</div><!-- End of row-fluid -->
		</div>
	
		<div id="selected-courses" style="margin-top: 0px; margin-bottom: 20px;">
			<div class="row-fluid">
			    <div class="span12">
			        <div class="widget-box" style="margin-bottom: 0px;">
			            <div class="widget-title">
		                    <span class="icon">
		                        <i class="icon-th"></i>
		                    </span>
			                <h5 style="margin-bottom: 5px;">Sélection de cours</h5>
			            </div>
			            <div class="widget-content nopadding">
			                <table class="table courses courses-list table-bordered table-striped">
			                    <thead>
									<tr>
										<th>Cours</th>
										<th>NRC</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$credits = 0;
										if ( is_array( $selectedCourses ) ):
											foreach ( $selectedCourses as $course ):
												echo $this->element( 'registration/selected_course', array( 'course' => $course ) );
									
												$credits += $course[ 'UniversityCourse' ][ 'credits' ];
											endforeach;
										endif;
									?>
									<tr>
										<td colspan="2">
											<div class="courses-total" style="font-weight: bold; float: left;">
												<?php if (is_array($selectedCourses)) echo count($selectedCourses); else echo 0; ?> cours
											</div>
											<div class="credits-total" style="float: right;"><?php echo $credits; ?> crédits</div>
											<div style="clear: both;"></div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
			        </div>
			    </div>
			</div><!-- End of row-fluid -->
		</div>

		<?php
			if ( date( 'Ymd' ) >= $deadlines[ $registrationSemester ][ 'registration_start' ]
			  && date( 'Ymd' ) <= $deadlines[ $registrationSemester ][ 'edit_selection' ] ):
				?><div style="text-align: center;"><a href="javascript:app.Registration.registerCourses();" class='btn btn-success'><i class="icon-ok icon-white"></i> Inscription</a></div><?php
			elseif ( date( 'Ymd' ) >= $deadlines[ $registrationSemester ][ 'registration_start' ] 
				   && date( 'Ymd' ) >= $deadlines[ $registrationSemester ][ 'edit_selection' ] ):
				?>
				<div style="margin-top: 35px; line-height: 12px; text-align: center; width: 180px; margin-left: auto; margin-right: auto; margin-bottom: 10px; color: gray; font-size: 8pt;">
					La période d'inscription <?php echo $this->App->convertSemester( $registrationSemester, true ); ?><br />est terminée.
				</div>
				<?php
			elseif ( date( 'Ymd' ) <= $deadlines[ $registrationSemester ][ 'registration_start' ] ):
				?>
				<div style="margin-top: 35px; line-height: 12px; text-align: center; width: 180px; margin-left: auto; margin-right: auto; margin-bottom: 10px; color: gray; font-size: 8pt;">
					La période d'inscription <?php echo $this->App->convertSemester( $registrationSemester, true ); ?> commencera le <?php echo currentDate( $deadlines[ $registrationSemester ][ 'registration_start' ], "j F Y" ); ?>.
				</div>
				<?php
			endif;
		?>

	</div>
</div>
<div id="modal-course" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true"></div>
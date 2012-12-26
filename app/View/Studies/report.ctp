<div class="row-fluid">
    <div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.</div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
    			<span class="icon"><i class="icon-user"></i></span>
                <h5>Dossier de l'étudiant</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>Étudiant</th>
                        <td><?php echo $user[ 'name' ]; ?></td>
                    </tr>
                    <tr>
                        <th>Date de naissance</th>
                        <td><?php echo $user[ 'birthday' ] ; ?></td>
                    </tr>
                    <tr>
                        <th>Numéro de dossier</th>
                        <td><?php echo $user[ 'da' ] ; ?></td>
                    </tr>
                    <tr>
                        <th>Code permanent</th>
                        <td><?php echo $user[ 'code_permanent' ] ; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ( !empty( $report[ 'Report' ] ) ) : ?>
        <div class="span6">
            <div class="widget-box" style="margin-bottom: 0px;">
                <div class="widget-title">
                    <span class="icon"><i class="icon-signal"></i></span>
                    <h5>Bilan du relevé</h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th >Crédits</th>
                            <th>Points</th>
                            <th>Moyenne</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="left">
                                <?php if ( !$mobile_browser ) echo 'Université'; else echo 'U.'; ?> Laval
                            </th>
                            <td><?php echo $report[ 'Report' ][ 'credits_registered' ]; ?></td>
                            <td><?php echo $report[ 'Report' ][ 'points' ]; ?></td>
                            <td><?php echo $report[ 'Report' ][ 'ulaval_gpa' ]; ?></td>
                        </tr>
                        <tr>
                            <th class="left">Externes (reconnus)</th>
                            <td><?php echo $report[ 'Report' ][ 'credits_admitted' ]; ?></td>
                            <td><?php echo $report[ 'Report' ][ 'credits_admitted_points' ]; ?></td>
                            <td><?php echo $report[ 'Report' ][ 'gpa_admitted' ]; ?></td>
                        </tr>
                        <tr>
                            <th class="left">Total</th>
                            <td>
                                <?php echo ( $report[ 'Report' ][ 'credits_registered' ] + $report[ 'Report' ][ 'credits_admitted' ] ); ?>
                            </td>
                            <td>
                                <?php echo number_format( $report[ 'Report' ][ 'points' ] + $report[ 'Report' ][ 'credits_admitted_points' ], 2 ); ?>
                            </td>
                            <td><?php echo $report[ 'Report' ][ 'gpa_cycle' ]; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else : echo '<p>Le relevé de notes ne contient aucun cours.</p>'; endif; ?>

</div><!-- End of row-fluid -->

<div class="row-fluid">
    <div class="span12">
        <?php foreach ( unserialize( $report[ 'Report' ][ 'programs' ] ) as $program ) : ?>
            <div class="widget-box">
                <div class="widget-title">
					<span class="icon"><i class="icon-th"></i></span>
                    <h5>Programme d'études</h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <th>Programme</th>
                            <td><?php echo $program[ 'full_name' ] ; ?></td>
                        </tr>
                        <?php
                            if ( !empty( $program[ 'faculty' ] ) ) :
                                ?>
                                <tr>
                                    <th>Faculté</th>
                                    <td><?php echo $program['faculty'] ; ?></td>
                                </tr>
                                <?php
                            endif;

                            if ( !empty( $program[ 'major' ] ) ) :
                                ?>
                                <tr>
                                    <th>Majeure</th>
                                    <td><?php echo $program[ 'major' ] ; ?></td>
                                </tr>
                                <?php
                            endif;

                            if ( $program[ 'concentrations' ] != array() ) :
                                ?>
                                <tr>
                                    <th>Concentration(s)</th>
                                    <td><?php echo implode( ', ', $program[ 'concentrations' ] ); ?></td>
                                </tr>
                                <?php
                            endif;
                        ?>
                        <tr>
                            <th>Fréquentation</th>
                            <td><?php echo $program[ 'attendance' ]; ?> </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div><!-- End of row-fluid -->

<h2>Cours de l'Université Laval</h2>

<?php
    $totalCredits = 0;
    foreach ( $report[ 'Report' ][ 'Semester' ] as $semester ) :
	?>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box" style="margin-bottom: 0px;">
                <div class="widget-title">
                    <span class="icon"><i class="icon-th"></i></span>
                    <h5><?php echo $this->App->convertSemester( $semester['semester'] ); ?></h5>
                </div>
            	<?php if ( !empty( $semester[ 'Course' ] ) ) : ?>
            	   <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="course-code">Cours</th>
                                    <?php if ( !$mobile_browser ) : ?>
                                        <th>Titre</th>
                                        <th>Reprise</th>
                                    <?php endif; ?>
                                    <th>Crédits</th>
                                    <th>Note</th>
                                    <th >Points</th>
                                </tr>
                            </thead>
                            <tbody>
                    			<?php
                                    foreach ( $semester[ 'Course' ] as $course ) :
                        				$totalCredits += $course['credits'];
                        				?>
                            			<tr class="<?php if ( empty( $course[ 'note' ] ) ) echo 'current'; ?>">
                            				<td class="code"><?php echo $course[ 'code' ]; ?></td>
                            				<td class="title"><?php echo $course[ 'title' ]; ?></td>
                            				<td class="reprise"><?php echo $course[ 'reprise' ]; ?></td>
                            				<td class="credits"><?php echo $course[ 'credits' ]; ?></td>
                            				<td class="note">
                                                <?php
                                                    // Display note or special label (if applicable)
                                                    switch ( $course[ 'note' ] ) :
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
                                                    endswitch;
                                                ?>
                                            </td>
                            				<td class="points"><?php echo $course[ 'points' ]; ?></td>		
                            			</tr>
                    				    <?php
                                    endforeach;
                    			?>
                    			<tr>
                    				<td colspan="3">Total</td>
                    				<td><?php echo $semester[ 'credits_registered' ]; ?></td>
                    				<td><?php echo $semester[ 'gpa' ]; ?></td>
                    				<td><?php echo $semester[ 'points' ]; ?></td>
                    			</tr>
                            </tbody>
                        </table>
                    </div>
            	<?php else : echo '<p>Aucun cours</p>'; endif; ?>
            </div>
        </div>
    </div><!-- End of row-fluid -->

<?php endforeach; ?>

<h2 style="margin-top: 20px;">Cours reconnus</h2>

<?php foreach ( $report[ 'Report' ][ 'AdmittedSection' ] as $section ) : ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box" style="margin-bottom: 0px;">
                <div class="widget-title">
                    <span class="icon"><i class="icon-th"></i></span>
                    <h5><?php echo $section[ 'period' ] . ' - ' . $section[ 'title' ]; ?></h5>
                </div>
                <?php if ( !empty( $section[ 'Course' ] ) ) : ?>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="course-code">Cours</th>
                                    <?php if ( !$mobile_browser ) : ?>
                                        <th>Titre</th>
                                        <th>Reprise</th>
                                    <?php endif; ?>
                                    <th>Crédits</th>
                                    <th>Note</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ( $section[ 'Course' ] as $course ) :
                                        $totalCredits += $course[ 'credits' ];
                                        ?>
                                        <tr class="<?php if ( empty( $course[ 'note' ] ) ) echo 'current'; ?>">
                                            <td class="code"><?php echo $course[ 'code' ]; ?></td>
                                            <td class="title"><?php echo $course[ 'title' ]; ?></td>
                                            <td class="reprise"><?php echo $course[ 'reprise' ]; ?></td>
                                            <td class="credits"><?php echo $course[ 'credits' ]; ?></td>
                                            <td class="note">
                                                <?php
                                                    // Display note or special label (if applicable)
                                                    switch ( $course[ 'note' ] ) :
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
                                                    endswitch;
                                                ?>
                                            </td>
                                            <td class="points"><?php echo $course[ 'points' ]; ?></td>      
                                        </tr>
                                        <?php
                                    endforeach;
                                ?>
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td><?php echo $section[ 'credits_admitted' ]; ?></td>
                                    <td><?php echo $section[ 'credits_gpa' ]; ?></td>
                                    <td><?php echo $section[ 'points' ]; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else : echo '<p>Aucun cours</p>'; endif; ?>
            </div>
        </div>
    </div><!-- End of row-fluid -->

<?php endforeach; ?>
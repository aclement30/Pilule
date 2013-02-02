<div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.</div>

<div class="row-fluid">
    <div class="span6">
        <div class="table-panel">
            <h4> <i class="icon-user"></i>Dossier de l'étudiant</h4>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>Étudiant</th>
                        <td><?php echo $user[ 'name' ]; ?></td>
                    </tr>
                    <?php if ( !empty( $user[ 'birthday' ] ) ) : ?>
                        <tr>
                            <th>Date de naissance</th>
                            <td><?php echo $user[ 'birthday' ] ; ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ( !empty( $user[ 'da' ] ) ) : ?>
                        <tr>
                            <th>Numéro de dossier</th>
                            <td><?php echo $user[ 'da' ] ; ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ( !empty( $user[ 'code_permanent' ] ) ) : ?>
                        <tr>
                            <th>Code permanent</th>
                            <td><?php echo $user[ 'code_permanent' ] ; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ( !empty( $report[ 'Report' ] ) ) : ?>
        <div class="span6">
            <div class="table-panel">
                <h4> <i class="icon-signal"></i>Bilan du relevé</h4>
                <table class="table table-striped summary">
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
    <?php endif; ?>

</div><!-- End of row-fluid -->

<?php if ( !empty( $report[ 'Report' ][ 'programs' ] ) ) : ?>
    <div class="row-fluid">
        <div class="span12">
            <?php foreach ( unserialize( $report[ 'Report' ][ 'programs' ] ) as $program ) : ?>
                <div class="table-panel">
                    <h4> <i class="icon-th"></i>Programme d'études</h4>
                    <table class="table table-striped">
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
            <?php endforeach; ?>
        </div>
    </div><!-- End of row-fluid -->
<?php else : ?>
    <div class="row-fluid">
        <?php echo $this->element( 'empty_data', array( 'message' => 'Votre relevé de notes ne contient aucun programme d\'études.' ) ); ?>
    </div>
<?php endif; ?>

<?php if ( !empty( $report[ 'Report' ][ 'Semester' ] ) ) : ?>
    <h4 class="formation">Cours de l'Université Laval</h4>

    <?php
        $totalCredits = 0;
        foreach ( $report[ 'Report' ][ 'Semester' ] as $semester ) :
    	?>
        <div class="table-panel not-expandable">
            <h5> <i class="icon-th"></i><?php echo $this->App->convertSemester( $semester[ 'semester' ] ); ?></h5>
            <?php if ( !empty( $semester[ 'Course' ] ) ) : ?>
                <table class="table table-striped sortable courses-list">
                    <thead>
                        <tr>
                            <th class="course-code">Cours</th>
                            <th class="title">Titre</th>
                            <th>Reprise</th>
                            <th class="credits">Crédits</th>
                            <th class="note">Note</th>
                            <th class="points">Points</th>
                        </tr>
                    </thead>
                    <tbody>
            			<?php
                            foreach ( $semester[ 'Course' ] as $course ) :
                				$totalCredits += $course['credits'];
                				?>
                    			<tr class="<?php if ( empty( $course[ 'note' ] ) ) echo 'current'; ?>">
                    				<td class="code">
                                        <span class="course-code"><?php echo $course[ 'code' ]; ?></span><br />
                                        <span class="mobile-title"><?php echo $course[ 'title' ]; ?></span>
                                    </td>
                    				<td class="title"><?php echo $course[ 'title' ]; ?></td>
                    				<td class="reprise"><?php echo $course[ 'reprise' ]; ?></td>
                    				<td class="credits"><?php echo $course[ 'credits' ]; ?></td>
                    				<td class="note">
                                        <?php
                                            // Display note or special label (if applicable)
                                            switch ( $course[ 'note' ] ) :
                                                case 'AUD':
                                                    echo '<span class="label">Aud.</span>';
                                                    break;
                                                case 'NA':
                                                    echo '<span class="label">N. éval.</span>';
                                                    break;
                                                case 'V':
                                                    echo '<span class="label label-info">Équiv.</span>';
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
                    </tbody>
                    <tfoot>
            			<tr>
            				<th class="left" colspan="3">Total</th>
            				<td><?php echo $semester[ 'credits_registered' ]; ?></td>
            				<td><?php echo $semester[ 'gpa' ]; ?></td>
            				<td><?php echo $semester[ 'points' ]; ?></td>
            			</tr>
                    </tfoot>
                </table>
            <?php else : echo '<p>Aucun cours</p>'; endif; ?>
        </div>
    <?php endforeach; ?>
<?php elseif ( !empty( $report[ 'Report' ][ 'programs' ] ) ) : ?>
    <div class="row-fluid">
        <?php echo $this->element( 'empty_data', array( 'message' => 'Votre relevé de notes ne contient aucun résultat.' ) ); ?>
    </div>
<?php endif; ?>

<?php if ( !empty( $report[ 'Report' ][ 'AdmittedSection' ] ) ) : ?>
    <h4 class="formation">Cours reconnus</h4>

    <?php foreach ( $report[ 'Report' ][ 'AdmittedSection' ] as $section ) : ?>
        <div class="table-panel not-expandable">
            <h5> <i class="icon-th"></i><?php echo $section[ 'period' ] . ' - ' . $section[ 'title' ]; ?></h5>
            <?php if ( !empty( $section[ 'Course' ] ) ) : ?>
                <table class="table table-striped sortable courses-list">
                    <thead>
                        <tr>
                            <th class="course-code">Cours</th>
                            <th class="title">Titre</th>
                            <th>Reprise</th>
                            <th class="credits">Crédits</th>
                            <th class="note">Note</th>
                            <th class="points">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ( $section[ 'Course' ] as $course ) :
                                $totalCredits += $course[ 'credits' ];
                                ?>
                                <tr class="<?php if ( empty( $course[ 'note' ] ) ) echo 'current'; ?>">
                                    <td class="code">
                                        <span class="course-code"><?php echo $course[ 'code' ]; ?></span><br />
                                        <span class="mobile-title"><?php echo $course[ 'title' ]; ?></span>
                                    </td>
                                    <td class="title"><?php echo $course[ 'title' ]; ?></td>
                                    <td class="semester">
                                        <?php if ( !empty( $course[ 'semester' ] ) ) echo $this->App->convertSemester( $course[ 'semester' ], true ); ?>
                                    </td>
                                    <td class="reprise"><?php echo $course[ 'reprise' ]; ?></td>
                                    <td class="credits"><?php echo $course[ 'credits' ]; ?></td>
                                    <td class="note">
                                        <?php
                                            // Display note or special label (if applicable)
                                            switch ( $course[ 'note' ] ) :
                                                case 'AUD':
                                                    echo '<span class="label">Aud.</span>';
                                                    break;
                                                case 'NA':
                                                    echo '<span class="label">N. éval.</span>';
                                                    break;
                                                case 'V':
                                                    echo '<span class="label label-info">Équival.</span>';
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
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="left" colspan="3">Total</th>
                            <td><?php echo $section[ 'credits_admitted' ]; ?></td>
                            <td><?php echo $section[ 'credits_gpa' ]; ?></td>
                            <td><?php echo $section[ 'points' ]; ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php else : echo '<p>Aucun cours</p>'; endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
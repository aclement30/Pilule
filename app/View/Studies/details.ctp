<div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.</div>

<?php if ( empty( $programsList ) ) : ?>
    <div class="row-fluid">
        <?php echo $this->element( 'empty_data', array( 'message' => 'Votre dossier Capsule ne contient aucun programme d\'études.' ) ); ?>
    </div>
<?php endif; ?>

<?php
    if ( count( $programsList ) > 1 ) :
        ?><div class="no-print"><?php
            // Display programs dropdown
            echo $this->element( 'programs_dropdown', array( 'programsList' => $programsList, 'selectedProgram' => $program[ 'Program' ][ 0 ][ 'id' ] ) );

            echo '<hr>';
        ?></div><?php
    endif;
?>

<?php $program = $program[ 'Program' ][ 0 ]; ?>

<div class="stats no-print">
    <div class="row-fluid">
        <?php if ( !empty( $program[ 'gpa_program' ] ) ) : ?>
            <div class="span4">
                <div class="stat">
                    <h2><?php echo $program['gpa_program']; ?></h2>
                    <h6>Moyenne de programme</h6>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( $program[ 'credits_used' ] != 0 ) : ?>
            <div class="span4">
                <div class="stat">
                    <h2><?php echo $program[ 'credits_used' ]; ?></h2>
                    <h6>Crédits accumulés</h6>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( ( $program[ 'courses_used' ] + $program[ 'courses_admitted' ] ) != 0 ) : ?>
            <div class="span4">
                <div class="stat">
                    <h2><?php echo ( $program[ 'courses_used' ] + $program[ 'courses_admitted' ] ); ?></h2>
                    <h6>Cours complétés/reconnus</h6>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="table-panel">
    <h4> <i class="icon-user"></i>Dossier de l'étudiant</h4>
    <table class="table table-striped">
        <tbody>
            <tr>
                <th>Étudiant</th>
                <td><?php echo $user['name']; ?></td>
            </tr>
            <?php if ( !empty( $user[ 'code_permanent' ] ) ) : ?>
                <tr>
                    <th>Code permanent</th>
                    <td><?php echo $user['code_permanent'] ; ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>Programme</th>
                <td><?php echo $program['name'] ; ?> (<?php echo $program['diploma'] ; ?>)</td>
            </tr>
            <tr>
                <th>Cycle</th>
                <td><?php if ($program['cycle'] == 1) {
                    echo 'Premier cycle';
                } elseif ($program['cycle'] == 2) {
                    echo 'Deuxième cycle';
                } elseif ($program['cycle'] == 3) {
                    echo 'Troisième cycle';
                } ?></td>
            </tr>
            <tr>
                <th>Admission</th>
                <td><?php echo $this->App->convertSemester($program['adm_semester']); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $program['adm_type'] ; ?> </td>
            </tr>
            <tr>
                <th>Majeure</th>
                <td><?php echo $program['major'] ; ?></td>
            </tr>
            <?php if ( !empty( $program[ 'minor' ] ) ) : ?>
                <tr>
                    <th>Mineure(s)</th>
                    <td><?php echo $program['minor']; ?></td>
                </tr>
            <?php endif; ?>
            <?php
                if ( !empty( $program[ 'concentrations' ] ) && !is_array( $program[ 'concentrations' ] ) ) {
                    $program[ 'concentrations' ] = unserialize( $program[ 'concentrations' ] );
                }

                if ( !empty( $program[ 'concentrations' ] ) ) : ?>
                <tr>
                    <th>Concentration(s)</th>
                    <td><?php echo implode( ', ', $program[ 'concentrations' ] ); ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( !empty( $program[ 'session_repertoire' ] ) ) : ?>
                <tr>
                    <th>Session de répertoire</th>
                    <td><?php echo $this->App->convertSemester($program['session_repertoire']); ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( !empty( $program[ 'session_evaluation' ] ) ) : ?>
                <tr>
                    <th>Session d'évaluation</th>
                    <td><?php echo $this->App->convertSemester($program['session_evaluation']); ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( !empty( $program[ 'date_diplome' ] ) ) : ?>
                <tr>
                    <th>Date obtention du diplôme</th>
                    <td><?php echo $program['date_diplome'] ; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( !empty( $program[ 'date_attestation' ] ) ) : ?>
                <tr>
                    <th>Date de l'attestation</th>
                    <td><?php echo substr( $program[ 'date_attestation' ], 6, 2 ) . '-' . substr( $program[ 'date_attestation' ], 4, 2 ) . '-' . substr( $program[ 'date_attestation' ], 0, 4 ); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="table-panel">
            <h4> <i class="icon-th"></i>Cours et crédits</h4>
            <table class="table table-striped courses-credits" style="width: 100%;">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Utilisés</th>
                        <th>Reconnus</th>
                        <th>Prog.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Crédits</th>
                        <td><?php echo $program['credits_used']; ?></td>
                        <td><?php echo $program['credits_admitted']; ?></td>
                        <td><?php echo $program['credits_program']; ?></td>
                    </tr>
                    <tr>
                        <th>Cours</th>
                        <td><?php echo $program['courses_used']; ?></td>
                        <td><?php echo $program['courses_admitted']; ?></td>
                        <td><?php echo $program['courses_program']; ?></td>
                    </tr>
                    <tr>
                        <th>Exigences satisfaites</th>
                        <td colspan="3" class="requirements"><?php if ($program['requirements']) echo 'Oui'; else echo 'Non'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="span6">
        <div class="table-panel">
            <h4> <i class="icon-signal"></i>Moyennes</h4>
            <table class="table table-striped gpas">
                <thead>
                    <tr>
                        <th>Programme</th>
                        <th>Cheminement</th>
                        <?php if ( isset( $program[ 'cohort_gpa' ] ) && $program[ 'cohort_gpa' ][ 'number' ] >= 10 ) { ?>
                        <th class="no-print">Cohorte <?php echo substr($program['session_repertoire'], 0, 4); ?> *</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $program['gpa_program']; ?></td>
                        <td><?php echo $program['gpa_overall']; ?></td>
                        <?php if ( isset( $program[ 'cohort_gpa' ] ) && $program[ 'cohort_gpa' ][ 'number' ] >= 10 ) { ?>
                        <td class="no-print"><?php echo number_format($program['cohort_gpa']['average'], 2); ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--
    <div class="widget-box no-print style="margin-top: 0px; border-top:  0px; margin-bottom: 5px;">
        <div class="widget-content">
            <div class="chart" style="height: 98px;"></div>
        </div>
    </div>

    <?php if ( isset( $program[ 'cohort_gpa' ] ) && $program[ 'cohort_gpa' ][ 'number' ] >= 10 ) { ?>
    <p class="no-print" style="font-size: 7pt; color:  gray; line-height: 9pt;">* La moyenne de cohorte évalue l'ensemble des moyennes de programme des <?php echo $program['cohort_gpa']['number']; ?> étudiants de cette cohorte inscrits sur Pilule. Cette moyenne est affichée à titre indicatif.</p>
    <?php } ?>
    -->

</div><!-- End of row-fluid -->

<hr style="page-break-after: always;">

<h4 class="formation">Formation</h4>

<?php if ( !empty( $programsList ) && empty( $sections[ 'Section' ] ) ) : ?>
    <div class="row-fluid">
        <?php echo $this->element( 'empty_data', array( 'message' => 'Votre dossier Capsule ne contient aucun cours pour ce programme d\'études.' ) ); ?>
    </div>
<?php endif; ?>

<?php
    foreach ( $sections[ 'Section' ] as $section ) :
        $creditsCompleted = 0;
        $isCompleted = false;

        if ( empty( $section[ 'Course' ] ) ) continue;

        foreach ( $section[ 'Course' ] as $course ) {
            if ( !empty( $course[ 'note' ] ) )
                $creditsCompleted += $course[ 'credits' ];
        }

        if ( $creditsCompleted == $section[ 'credits' ] )
            $isCompleted = true;

        ?>
        <div class="table-panel<?php if ( $isCompleted ) echo ' completed'; ?> not-expandable">
            <h5> <?php if ( $isCompleted ) echo '<i class="icon-ok"></i>'; else echo '<i class="icon-th"></i>'; echo $section[ 'title' ]; ?></h5>
            <table class="table table-striped sortable courses-list">
                <thead>
                    <tr>
                        <th class="course-code">Cours</th>
                        <th class="title">Titre</th>
                        <th class="semester">Session</th>
                        <th class="credits">Crédits</th>
                        <th class="note">Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $section[ 'Course' ] as $course ) : ?>
                        <tr class="<?php if ( empty( $course[ 'note' ] ) ) echo 'current'; ?>">
                            <td class="code">
                                <span class="course-code"><?php echo $course[ 'code' ]; ?></span><br />
                                <span class="mobile-title"><?php echo $course[ 'title' ]; ?></span>
                            </td>
                            <td class="title"><?php echo $course[ 'title' ]; ?></td>
                            <td class="semester">
                                <?php if ( !empty( $course[ 'semester' ] ) ) echo $this->App->convertSemester( $course[ 'semester' ], true ); ?>
                            </td>
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
                </tbody>
                <tfoot>
                    <tr>
                        <th class="left" colspan="3">Total</th>
                        <td class="total-credits">
                            <?php
                                echo $creditsCompleted;

                                if ( !empty( $section[ 'credits' ] ) ) echo ' / ' . $section[ 'credits' ];
                            ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php
    endforeach;


    /*
    if ($details['other_courses']!=array()) { ?>
    <h2>Cours non utilisés</h2>

    <div class="row-fluid">

    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-th"></i>
                                    </span>
                <h5>Cours non utilisés</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="font-weight: bold; text-align: left; width: 10%;">Cours</th>
                            <?php if ( !$isMobile ) { ?><th style="font-weight: bold; text-align: left;">Titre</th><?php } ?>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Session</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Crédits</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details['other_courses'] as $course) {
                            ?>
                        <tr>
                            <?php if ( !$isMobile ) { ?>
                            <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['code']; ?></td>
                            <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['title']; ?></td>
                            <?php } else { ?>
                            <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
                            <?php } ?>
                            <td style="text-align: center;<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo str_replace("-20", "-", str_replace("Automne ", "A-", str_replace("Hiver ", "H-", str_replace("Été ", "E-", $course['semester'])))); ?></td>
                            <td style="text-align: center;<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo trim(str_replace("cr.", "", $course['credits'])); ?></td>
                            <td style="text-align: center;<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php
                            switch ($course['note']) {
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
                                    echo $course['note'];
                            } ?></td>
                        </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    }
    */
     ?>
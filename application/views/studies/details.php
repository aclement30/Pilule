<div class="row-fluid">
    <div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date('d/m/Y, à H:i', $last_request['timestamp']); ?>.</div>
</div>

<?php
if (count($programs) > 1) { ?>
    <p style="margin-top: 20px; margin-bottom: 0px; text-align: right;">Programme : <select onchange="javascript:app.studies.displayProgramPanel(this.options[this.selectedIndex].value);">
        <?php
        foreach ($programs as $program) {
            ?><option value="<?php echo $program['id']; ?>"> <?php echo $program['name']; ?></option><?php
        }
    ?></select></p>
    <?php
}
$program_number = 1;

foreach ($programs as $program) {
    ?>
<div class="program-panel" id="program-<?php echo $program['id']; ?>"<?php if ($program_number != 1) echo ' style="display: none;"'; ?>>

<div class="row-fluid no-print">
    <div class="span12">
        <div class="widget-box widget-plain">
            <div class="widget-content center">
                <ul class="stats-plain">
                    <li>
                        <h4><?php echo $program['gpa_program']; ?></h4>
                        <span>Moyenne de programme</span>
                    </li>
                    <li>
                        <h4><?php echo ($program['credits_used']+$program['credits_admitted']); ?></h4>
                        <span>Crédits accumulés</span>
                    </li>
                    <li>
                        <h4><?php echo ($program['courses_used']+$program['courses_admitted']); ?></h4>
                        <span>Cours complétés/reconnus</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">

        <div class="span7">
        <div class="widget-box">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-user"></i>
                                    </span>
                <h5>Dossier de l'étudiant</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th style="vertical-align: middle;">Étudiant</th>
                        <td><?php echo $user['name']; ?></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Code permanent</th>
                        <td><?php echo $user['code_permanent'] ; ?></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Programme</th>
                        <td><?php echo $program['name'] ; ?> (<?php echo $program['diploma'] ; ?>)</td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Cycle</th>
                        <td><?php if ($program['cycle'] == 1) {
                            echo 'Premier cycle';
                        } elseif ($program['cycle'] == 2) {
                            echo 'Deuxième cycle';
                        } elseif ($program['cycle'] == 3) {
                            echo 'Troisième cycle';
                        } ?></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Admission</th>
                        <td><?php echo convertSemester($program['adm_semester']); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $program['adm_type'] ; ?> </td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Majeure</th>
                        <td><?php echo $program['major'] ; ?></td>
                    </tr>
                    <?php if (!empty($program['minor'])) { ?>
                    <tr>
                        <th style="vertical-align: middle;">Mineure(s)</th>
                        <td><?php echo $program['minor']; ?></td>
                    </tr>
                    <?php } if ($program['concentrations']!=array()) { ?>
                    <tr>
                        <th style="vertical-align: middle;">Concentration(s)</th>
                        <td><?php echo implode(', ', $program['concentrations']); ?></td>
                    </tr>
                        <?php } ?>
                    <tr>
                        <th style="vertical-align: middle;">Session de répertoire</th>
                        <td><?php echo convertSemester($program['session_repertoire']); ?></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Session d'évaluation</th>
                        <td><?php echo convertSemester($program['session_evaluation']); ?></td>
                    </tr>
                    <?php if (strlen($program['date_diplome'])>2) { ?>
                    <tr>
                        <th style="vertical-align: middle;">Date obtention du diplôme</th>
                        <td><?php echo $program['date_diplome'] ; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th style="vertical-align: middle;">Date de l'attestation</th>
                        <td><?php echo $program['date_attestation'] ; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="span5">
        <div class="widget-box">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-th"></i>
                                    </span>
                <h5>Cours et crédits</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th style="text-align: center;">Utilisés</th>
                            <th style="text-align: center;">Reconnus</th>
                            <th style="text-align: center;">Programme</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Crédits</th>
                        <td style="text-align: center;"><?php echo $program['credits_used']; ?></td>
                        <td style="text-align: center;"><?php echo $program['credits_admitted']; ?></td>
                        <td style="text-align: center;"><?php echo $program['credits_program']; ?></td>
                    </tr>
                    <tr>
                        <th>Cours</th>
                        <td style="text-align: center;"><?php echo $program['courses_used']; ?></td>
                        <td style="text-align: center;"><?php echo $program['courses_admitted']; ?></td>
                        <td style="text-align: center;"><?php echo $program['courses_program']; ?></td>
                    </tr>
                    <tr>
                        <th>Exigences satisfaites</th>
                        <td colspan="3"><?php if ($program['requirements']) echo 'Oui'; else echo 'Non'; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="span5">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-signal"></i>
                                    </span>
                <h5>Moyennes</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align: center;">Programme</th>
                        <th style="text-align: center;">Cheminement</th>
                        <?php if ($program['cohort_gpa']['number'] >= 10) { ?>
                        <th style="text-align: center;" class="no-print">Cohorte <?php echo substr($program['session_repertoire'], 0, 4); ?> *</th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align: center;"><?php echo $program['gpa_program']; ?></td>
                        <td style="text-align: center;"><?php echo $program['gpa_overall']; ?></td>
                        <?php if ($program['cohort_gpa']['number'] >= 10) { ?>
                        <td style="text-align: center;" class="no-print"><?php echo number_format($program['cohort_gpa']['average'], 2); ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="widget-box no-print style="margin-top: 0px; border-top:  0px; margin-bottom: 5px;">
            <div class="widget-content">
                <div class="chart" style="height: 98px;"></div>
            </div>
        </div>
        <?php if ($program['cohort_gpa']['number'] >= 10) { ?>
        <p class="no-print" style="font-size: 7pt; color:  gray; line-height: 9pt;">* La moyenne de cohorte évalue l'ensemble des moyennes de programme des <?php echo $program['cohort_gpa']['number']; ?> étudiants de cette cohorte inscrits sur Pilule. Cette moyenne est affichée à titre indicatif.</p>
        <?php } ?>
    </div>

    </div><!-- End of row-fluid -->

    <h2>Formation</h2>

        <?php
    foreach ($program['sections'] as $section) {
        $credits_done = 0;
        foreach ($section['courses'] as $course) {
            if ($course['note']!='') $credits_done += $course['credits'];
        }

        $credits = $credits_done;

        if ($section['courses']!=array()) {
        ?>
    <div class="row-fluid">

    <div class="span12">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                                    <span class="icon">
                                        <?php if ($credits==$section['credits']) echo '<i class="icon-ok"></i>'; else echo '<i class="icon-th"></i>'; ?>
                                    </span>
                <h5 style="<?php if ($credits==$section['credits']) echo 'color: green;'; else echo ' color: #d05519;'; ?>"><?php echo $section['title']; ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="font-weight: bold; text-align: left; width: 10%;" class="course-code">Cours</th>
                        <?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left;">Titre</th><?php } ?>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Session</th>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Crédits</th>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Note</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php foreach ($section['courses'] as $course) {
                    ?>
                <tr>
                    <?php if ($mobile!=1) { ?>
                    <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['code']; ?></td>
                    <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['title']; ?></td>
                    <?php } else { ?>
                    <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>; font-size: 10pt;"><strong><?php echo $course['idcourse']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
                    <?php } ?>
                    <td style="text-align: center;<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php
                    switch (substr($course['semester'], 5, 2)) {
                        case '09';
                            echo 'A-'.substr($course['semester'], 2, 2);
                        break;
                        case '01';
                            echo 'H-'.substr($course['semester'], 2, 2);
                        break;
                        case '05';
                            echo 'E-'.substr($course['semester'], 2, 2);
                        break;
                    } ?></td>
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
                    }
                    ?></td>
                </tr>
                    <?php
                }
                ?>
                <tr>
                    <th class="left" style="font-weight: bold; vertical-align: middle; text-align: right;<?php if ($section['credits']!='0' and $section['credits']==$credits) echo ' color: green;'; ?>" colspan="<?php if ($mobile!=1) echo 3; else echo 2; ?>">Total</th>
                    <td style="text-align: center; font-weight: bold;<?php if ($section['credits']!='0' and $section['credits']==$credits) echo ' color: green;'; ?>"><?php echo $credits; ?><?php if ($section['credits']!='0') echo ' / '.$section['credits']; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <?php /*
                <tr>
                    <th style="font-weight: bold; text-align: right;" colspan="<?php if ($mobile!=1) echo 4; else echo 3; ?>" class="left">Moyenne</th>
                    <td style="text-align: center; font-weight: bold;"><?php echo $moyenne; ?></td>
                </tr>
                */ ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div><!-- End of row-fluid -->

        <?php
        }
    }
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
                            <?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left;">Titre</th><?php } ?>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Session</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Crédits</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details['other_courses'] as $course) {
                            ?>
                        <tr>
                            <?php if ($mobile!=1) { ?>
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

    </div><!-- End of row-fluid -->
</div>
<?php
    $program_number++;
} ?>
<div class="row-fluid">

<div class="span6">
    <div class="widget-box" style="margin-bottom: 0px;">
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
                    <th style="vertical-align: middle;">Date de naissance</th>
                    <td><?php echo $user['birthday'] ; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">Numéro de dossier</th>
                    <td><?php echo $user['da'] ; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">Code permanent</th>
                    <td><?php echo $user['code_permanent'] ; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    $total_credits = 0;

    if ($semesters!=array()) {
    ?>
<div class="span6">
    <div class="widget-box" style="margin-bottom: 0px;">
        <div class="widget-title">
                            <span class="icon">
                                <i class="icon-signal"></i>
                            </span>
            <h5>Bilan du relevé</h5>
        </div>
        <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th style="text-align: center;">Crédits</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Moyenne</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th style="text-align: left;" class="left"><?php if ($mobile!=1) echo 'Université'; else echo 'U.'; ?> Laval</th>
                    <td style="text-align: center;"><?php echo $report['credits_registered']; ?></td>
                    <td style="text-align: center;"><?php echo $report['points']; ?></td>
                    <td style="text-align: center;"><?php echo $report['ulaval_gpa']; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="left">Externes (reconnus)</th>
                    <td style="text-align: center;"><?php echo $report['credits_admitted']; ?></td>
                    <td style="text-align: center;"><?php echo $report['credits_admitted_points']; ?></td>
                    <td style="text-align: center;"><?php echo $report['gpa_admitted']; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left;" class="left">Total</th>
                    <td style="text-align: center;"><?php echo ($report['credits_registered']+$report['credits_admitted']); ?></td>
                    <td style="text-align: center;"><?php echo number_format($report['points']+$report['credits_admitted_points'], 2); ?></td>
                    <td style="text-align: center;"><?php echo $report['gpa_cycle']; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
    <?php
} else {
    echo '<p>Le relevé de notes ne contient aucun cours.</p>';
}
?>

</div><!-- End of row-fluid -->

<div class="row-fluid">

    <div class="span12">
        <?php
        foreach ($report['programs'] as $program) {
            ?>
            <div class="widget-box">
                <div class="widget-title">
								<span class="icon">
									<i class="icon-th"></i>
								</span>
                    <h5>Programme d'études</h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <th style="vertical-align: middle;">Programme</th>
                            <td><?php echo $program['full_name'] ; ?></td>
                        </tr>
                        <?php
                        if (!empty($program['faculty'])) {
                            ?>
                        <tr>
                            <th style="vertical-align: middle;">Faculté</th>
                            <td><?php echo $program['faculty'] ; ?></td>
                        </tr>
                        <?php } ?>
                        <?php
                        if (!empty($program['major'])) {
                            ?>
                        <tr>
                            <th style="vertical-align: middle;">Majeure</th>
                            <td><?php echo $program['major'] ; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if ($program['concentrations']!=array()) { ?>
                        <tr>
                            <th style="vertical-align: middle;">Concentration(s)</th>
                            <td><?php echo implode(', ', $program['concentrations']); ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <th style="vertical-align: middle;">Fréquentation</th>
                            <td><?php echo $program['attendance']; ?> </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
    </div>

</div><!-- End of row-fluid -->

<h2>Cours de l'Université Laval</h2>

<?php

foreach ($semesters as $semester) {
	?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-th"></i>
                                    </span>
                <h5><?php echo convertSemester($semester['semester']); ?></h5>
            </div>
	<?php
	if ($semester['courses']!=array()) { ?>
	<div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="font-weight: bold; width: 10%; text-align: left;" class="course-code">Cours</th>
                        <th style="font-weight: bold; text-align: left;">Titre</th>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Reprise</th>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Crédits</th>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Note</th>
                        <th style="font-weight: bold; text-align: center; width: 12%;">Points</th>
                    </tr>
                </thead>
                <tbody>
			<?php foreach ($semester['courses'] as $course) {
				$total_credits += $course['credits'];
				?>
			<tr>
				<?php if ($mobile!=1) { ?>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['code']; ?></td>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['title']; ?></td>
				<td style="text-align: center;"><?php echo $course['reprise']; ?></td>
				<?php } else { ?>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
				<?php } ?>
				<td style="text-align: center;"><?php echo $course['credits']; ?></td>
				<td style="text-align: center;"><?php switch ($course['note']) {
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
				<td style="text-align: center;"><?php echo $course['points']; ?></td>		
			</tr>
				<?php
			}
			?>
			<tr>
				<td style="font-weight: bold; text-align: right;<?php if ($mobile==1) echo 'font-size: 9pt;'; ?>" colspan="<?php if ($mobile!=1) echo 3; else echo 1; ?>">Total</td>
				<td style="text-align: center; font-weight: bold;"><?php echo $semester['credits_registered']; ?></td>
				<td style="text-align: center; font-weight: bold;"><?php echo $semester['gpa']; ?></td>
				<td style="text-align: center; font-weight: bold;"><?php echo $semester['points']; ?></td>
			</tr>
            </tbody>
        </table>
    </div>
	<?php
	} else {
		echo '<p>Aucun cours</p>';
	}
    ?>
        </div>
    </div>

</div><!-- End of row-fluid -->
    <?php
}
?>

<h2 style="margin-top: 20px;">Cours reconnus</h2>

<?php

foreach ($admitted_sections as $section) {
    ?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-th"></i>
                                    </span>
                <h5><?php echo $section['period'] . ' - ' . $section['title']; ?></h5>
            </div>
            <?php
            if ($section['courses']!=array()) { ?>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-weight: bold; width: 10%; text-align: left;" class="course-code">Cours</th>
                            <th style="font-weight: bold; text-align: left;">Titre</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Reprise</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Crédits</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Note</th>
                            <th style="font-weight: bold; text-align: center; width: 12%;">Points</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($section['courses'] as $course) {
                            $total_credits += $course['credits'];
                            ?>
                        <tr>
                            <?php if ($mobile!=1) { ?>
                            <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['code']; ?></td>
                            <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['title']; ?></td>
                            <td style="text-align: center;"><?php echo $course['reprise']; ?></td>
                            <?php } else { ?>
                            <td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
                            <?php } ?>
                            <td style="text-align: center;"><?php echo $course['credits']; ?></td>
                            <td style="text-align: center;"><?php switch ($course['note']) {
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
                            <td style="text-align: center;"><?php echo $course['points']; ?></td>
                        </tr>
                            <?php
                        }
                            ?>
                        <tr>
                            <td style="font-weight: bold; text-align: right;<?php if ($mobile==1) echo 'font-size: 9pt;'; ?>" colspan="<?php if ($mobile!=1) echo 3; else echo 1; ?>">Total</td>
                            <td style="text-align: center; font-weight: bold;"><?php echo $section['credits_admitted']; ?></td>
                            <td style="text-align: center; font-weight: bold;"><?php echo $section['credits_gpa']; ?></td>
                            <td style="text-align: center; font-weight: bold;"><?php echo $section['points']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                echo '<p>Aucun cours</p>';
            }
            ?>
        </div>
    </div>

</div><!-- End of row-fluid -->
<?php
}
?>
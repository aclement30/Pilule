<h2 class="title">Rapport de cheminement<?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|studies,details');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><div class="clear"></div><a href="javascript:window.print();" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/printer.png" align="absmiddle" />&nbsp;Imprimer</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<?php
if (isset($tabs) and is_array($tabs)) {
?><div class="content-tabs">
	<ul class="content-tabbed-area" class="clearfix">
		<?php
		foreach ($tabs as $tab) {
			?><li<?php if ($tab['current']==1) echo ' class="active"'; ?>><a href="<?php echo $tab['url']; ?>"><span><?php echo $tab['title']; ?></span></a><span class="arrow"></span></li>
			<?php
		}
		?><div style="clear: both;"></div>
	</ul>
</div> <!-- end #main-tabs -->
<div style="clear: both;"></div>
<?php
}
?>

<div id="notice" style="margin-bottom: 20px; margin-top: 0px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>

<?php
switch ($type) {
	default:
	case '1':
?>
<h3>Dossier de l'étudiant</h3>
<table>
	<tbody>
		<tr>
			<th class="left">Étudiant</th>
			<td><?php echo $user['name'] ; ?></td>
		</tr>
		<tr>
			<th class="left">Code permanent</th>
			<td><?php echo $studies['code_permanent'] ; ?></td>
		</tr>
		<tr>
			<th class="left">Programme</th>
			<td><?php echo $studies['program'] ; ?> (<?php echo $studies['diploma'] ; ?>)</td>
		</tr>
		<tr>
			<th class="left">Cycle</th>
			<td><?php echo $studies['cycle'] ; ?></td>
		</tr>
		<tr>
			<th class="left">Majeure</th>
			<td><?php echo $studies['major'] ; ?></td>
		</tr>
		<?php if ($studies['minor']!='') { ?>
		<tr>
			<th class="left">Mineure(s)</th>
			<td><?php echo $studies['minor']; ?></td>
		</tr>
		<?php } if ($studies['concentrations']!=array()) { ?>
		<tr>
			<th class="left">Concentration(s)</th>
			<td><?php echo implode('<br />', $studies['concentrations']); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th class="left">Session de répertoire</th>
			<td><?php echo $studies['session_repertoire'] ; ?></td>
		</tr>
		<tr>
			<th class="left">Session d'évaluation</th>
			<td><?php echo $studies['session_evaluation'] ; ?></td>
		</tr>
		<?php if (strlen($studies['date_diplome'])>2) { ?>
		<tr>
			<th class="left">Date obtention du diplôme</th>
			<td><?php echo $studies['date_diplome'] ; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th class="left">Date de l'attestation</th>
			<td><?php echo $studies['date_attestation'] ; ?></td>
		</tr>
	</tbody>
</table><br class="space" />
<h3>Cours et crédits</h3>
<table>
	<tbody>
		<tr>
			<th style="width: 30%;">&nbsp;</th>
			<th style="font-weight: bold; text-align: center;">Utilisés</th>
			<th style="font-weight: bold; text-align: center;">Reconnus</th>
			<th style="font-weight: bold; text-align: center;">Programme</th>
		</tr>
		<tr>
			<th class="left">Crédits</th>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['credits_used']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['credits_admitted']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['credits_program']); ?></td>
		</tr>
		<tr>
			<th class="left">Cours</th>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['courses_used']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['courses_admitted']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['courses_program']); ?></td>
		</tr>
		<tr>
			<th class="left">Exigences satisfaites</th>
			<td colspan="3"><?php echo $studies['requirements']; ?></td>
		</tr>
	</tbody>
</table><br class="space" />
<h3>Moyennes</h3>
<table>
	<tbody>
		<tr>
			<th class="left">Programme</th>
			<td><?php echo $studies['gpa_program']; ?></td>
		</tr>
		<tr>
			<th class="left">Cheminement</th>
			<td><?php echo $studies['gpa_overall']; ?></td>
		</tr>
	</tbody>
</table>
<?php
	break;
	case 2:
	if ($mobile==1) {
		?><h3 style="margin-top: 0px;">Cours et crédits</h3>
<table>
	<tbody>
		<tr>
			<th style="width: 30%;">&nbsp;</th>
			<th style="font-weight: bold; text-align: center;">Utilisés</th>
			<th style="font-weight: bold; text-align: center;">Reconnus</th>
			<th style="font-weight: bold; text-align: center;">Programme</th>
		</tr>
		<tr>
			<th class="left">Crédits</th>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['credits_used']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['credits_admitted']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['credits_program']); ?></td>
		</tr>
		<tr>
			<th class="left">Cours</th>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['courses_used']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['courses_admitted']); ?></td>
			<td style="text-align: center;"><?php echo str_replace(",0", "", $studies['courses_program']); ?></td>
		</tr>
		<tr>
			<th class="left">Exigences satisfaites</th>
			<td><?php echo $studies['requirements']; ?></td>
		</tr>
	</tbody>
</table><br class="space" />
<h3>Moyennes</h3>
<table>
	<tbody>
		<tr>
			<th class="left">Programme</th>
			<td><?php echo $studies['gpa_program']; ?></td>
		</tr>
		<tr>
			<th class="left">Cheminement</th>
			<td><?php echo $studies['gpa_overall']; ?></td>
		</tr>
	</tbody>
</table><?php
	}
?>
<h3>Formation</h3><?php
foreach ($sections as $section) {
	$credits_done = 0;
	foreach ($section['courses'] as $course) {
		if ($course['note']!='') $credits_done += $course['credits'];
	}
	
	$credits = $credits_done;
	//$moyenne = trim(str_replace("<", "", substr($bloc, strpos($bloc, "ACRONYM")-6, 5)));
	?>
	<h4 style="margin-bottom: 5px;<?php if ($credits==$section['credits']) echo 'color: green;'; else echo ' color: #d05519;'; ?>"><?php echo $section['title']; ?><?php if ($credits==$section['credits']) { ?><div style="float: right;"><img src="<?php echo site_url(); ?>images/accept.png" align="absmiddle" />&nbsp;</div><div style="clear: both;"></div><?php } ?></h4>
	<?php
	if ($section['courses']!=array()) {
		?>
	<table class="course">
		<tbody>
			<tr>
				<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Cours</th>
				<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 45%;">Titre</th><?php } ?>
				<th style="font-weight: bold; text-align: center;">Session</th>
				<th style="font-weight: bold; text-align: center;">Crédits</th>
				<th style="font-weight: bold; text-align: center;">Note</th>
			</tr>
			<?php foreach ($section['courses'] as $course) {
				?>
			<tr>
				<?php if ($mobile!=1) { ?>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['idcourse']; ?></td>
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
				<td style="text-align: center;<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['note']; ?></td>				
			</tr>
				<?php
			}
			?>
			<tr>
				<th class="left" style="font-weight: bold; text-align: right;<?php if ($section['credits']!='0' and $section['credits']==$credits) echo ' color: green;'; ?>" colspan="<?php if ($mobile!=1) echo 3; else echo 2; ?>">Total</th>
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
	</table><br class="space" />
	<?php
	} else {
		echo '<p class="no-courses">Aucun cours';
		if ($section['credits']!=0) echo ' ('.$section['credits'].' crédits)';
		echo '</p>';
	}
}
	if ($details['other_courses']!=array()) { ?>
	<br class="space" /><h3>Cours non utilisés</h3>
	<table>
		<tbody>
			<tr>
				<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Cours</th>
				<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 45%;">Titre</th><?php } ?>
				<th style="font-weight: bold; text-align: center;">Session</th>
				<th style="font-weight: bold; text-align: center;">Crédits</th>
				<th style="font-weight: bold; text-align: center;">Note</th>
			</tr>
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
				<td style="text-align: center;<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['note']; ?></td>				
			</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
	}
	break;
}
?>
<script language="javascript">
$('.post-content table tr:even').css('backgroundColor', '#dae6f1');
</script>
<style type="text/css">
.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

.post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}
</style>
<style type="text/css" media="print">
body {
	margin: 0px;
	font-family: Helvetica, Arial;
	font-size: 10pt;
	background: none;
	width: 8.5in;
}

#page {
	width: 100%;
}

#content {
	background: none;
}

.entry {
	margin: 0px;
	padding: 0px;
	width: 100%;
	background: none;
}

.entry-top, .entry-content {
	width: 100%;
	padding-top: 0px;
	background: none;
}

#header, #header-bottom, a.link, a.refresh, #footer, #sidebar, .content-tabs, #top-shadow {
	display: none;
}

#main-area, .entry-page, .post-content {
	width: 100%;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	border: 1px solid silver;
	padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
}

.post-content table th {
	text-align: left;
	font-weight: normal;
	text-transform: uppercase;
	border-bottom: 2px solid gray;
	padding: 10px;
	vertical-align: top;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
	text-transform: none;
}

.post-content table th.left, .post-content table td {
	padding: 10px;
	vertical-align: top;
	border-bottom: 1px solid silver;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}

#notice {
	background-color: none;
	padding: 0px;
	font-size: 7pt;
	margin-bottom: 25px;
	color: #999;
	display: block;
	text-align: right;
}

h1 {
	margin-bottom: 10px;
}

h2.title a.link {
	display: none;
}

h2.title {
	margin-bottom: 10px;
}
</style>
<style type="text/css">
<?php if ($mobile==1) { ?>
.post-content table th {
	width: 100px;
	font-size: 9pt;
}

br.space, .content-tabs {
	display: none;
}

.post-content h4 {
	font-size: 9pt;
	font-weight: normal;
	text-transform: uppercase;
}

.no-courses {
	font-size: 10pt; margin-top: 0pt;
}
<?php } ?>
</style>
<div class="clear"></div></div>
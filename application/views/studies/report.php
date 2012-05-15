<h2 class="title">Relevé de notes<?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|studies,report');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><a href="javascript:window.print();" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/printer.png" align="absmiddle" />&nbsp;Imprimer</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<?php if ($user['idul'] != 'demo') { ?>
<div id="notice" style="display: block;">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>
<?php } ?>
<?php if ($mobile!=1) { ?>
<h3>Dossier de l'étudiant</h3>
<table>
	<tbody>
		<tr>
			<th class="left">Étudiant</th>
			<td><?php echo $user['name'] ; ?></td>
		</tr>
		<tr>
			<th class="left">Date de naissance</th>
			<td><?php echo $studies['birthday']; ?></td>
		</tr>
		<tr>
			<th class="left">Numéro de dossier</th>
			<td><?php echo $studies['da'] ; ?></td>
		</tr>
		<tr>
			<th class="left">Programme</th>
			<td><?php echo $studies['program'] ; ?> (<?php echo $studies['diploma'] ; ?>)</td>
		</tr>
		<tr>
			<th class="left">Majeure</th>
			<td><?php echo $studies['major'] ; ?></td>
		</tr>
		<?php if ($studies['concentrations']!=array()) { ?>
		<tr>
			<th class="left">Concentration(s)</th>
			<td><?php echo implode('<br />', $studies['concentrations']); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th class="left">Fréquentation</th>
			<td><?php echo $studies['attendance'] ; ?></td>
		</tr>
	</tbody>
</table><?php } ?>
<br class="space" /><h3 class="courses-list">Liste des cours</h3><?php

$total_credits = 0;

foreach ($report['semesters'] as $semester) {
	?>
	<h3 style="margin-bottom: 5px; color: black;"><?php echo $semester['title']; ?></h3>
	<?php
	if ($semester['courses']!=array()) { ?>
	<table>
		<tbody>
			<tr>
				<th style="font-weight: bold; width: 15%;">Cours</th>
				<?php if ($mobile!=1) { ?><th style="font-weight: bold;">Titre</th>
				<th style="font-weight: bold; text-align: center;">Rép.</th><?php } ?>
				<th style="font-weight: bold; text-align: center;">Crédits</th>
				<th style="font-weight: bold; text-align: center;">Note</th>
				<th style="font-weight: bold; text-align: center;">Points</th>
			</tr>
			<?php foreach ($semester['courses'] as $course) {
				$total_credits += $course['credits'];
				?>
			<tr>
				<?php if ($mobile!=1) { ?>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['code']; ?></td>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>"><?php echo $course['title']; ?></td>
				<td style="text-align: center;"><?php echo $course['repeat']; ?></td>
				<?php } else { ?>
				<td style="<?php if ($course['note']=='') echo 'color: #d05519;'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
				<?php } ?>
				<td style="text-align: center;"><?php echo $course['credits']; ?></td>
				<td style="text-align: center;"><?php echo $course['note']; ?></td>		
				<td style="text-align: center;"><?php echo $course['points']; ?></td>		
			</tr>
				<?php
			}
			?>
			<tr>
				<td style="font-weight: bold; text-align: right;<?php if ($mobile==1) echo 'font-size: 9pt;'; ?>" colspan="<?php if ($mobile!=1) echo 3; else echo 1; ?>">Total</td>
				<td style="text-align: center; font-weight: bold;"><?php echo $semester['total_credits']; ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="font-weight: bold; text-align: right;<?php if ($mobile==1) echo 'font-size: 9pt;'; ?>" colspan="<?php if ($mobile!=1) echo 4; else echo 2; ?>">Moyenne</td>
				<td style="text-align: center; font-weight: bold;"><?php echo $semester['gpa']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="font-weight: bold; text-align: right;<?php if ($mobile==1) echo 'font-size: 9pt;'; ?>" colspan="<?php if ($mobile!=1) echo 5; else echo 3; ?>">Total</td>
				<td style="text-align: center; font-weight: bold;"><?php echo $semester['total_points']; ?></td>
			</tr>
		</tbody>
	</table>
	<?php
	} else {
		echo '<p>Aucun cours</p>';
	}
}

if ($report['semesters']!=array()) {
		?>
	<br class="space" /><h3>Bilan du relevé</h3>
	<table>
	<tbody>
		<tr>
			<th>&nbsp;</th>
			<th style="font-weight: bold; text-align: center;">Crédits</th>
			<th style="font-weight: bold; text-align: center;">Points</th>
			<th style="font-weight: bold; text-align: center;">Moyenne</th>
		</tr>
		<tr>
			<th style="text-align: left;" class="left"><?php if ($mobile!=1) echo 'Université'; else echo 'U.'; ?> Laval</th>
			<td style="text-align: center;"><?php echo $total_credits; ?></td>
			<td style="text-align: center;"><?php echo $total_credits*3; ?></td>
			<td style="text-align: center;"><?php echo $report['gpa']; ?></td>
		</tr>
	</tbody>
</table><br class="space" />
	<?php
	} else {
		echo '<p>Le relevé de notes ne contient aucun cours.</p>';
	}
?>
<style type="text/css" media="screen">
.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	text-align: left;
	font-weight: bold;
	font-size: 11pt;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

.post-content table th.left, .post-content table th, .post-content table td {
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

#notice {
	background-color: silver;
	padding: 7px 10px;
	font-size: 8pt;
	margin-top: 10px;
	display: none;
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

#header, #header-bottom, a.link, a.refresh, #footer, #sidebar, .content-tabs, #top-shadow, #sidebar-notices {
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
	margin-bottom: 30px;
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

.post-content h3 {
	border: 0px;
}

h3.courses-list {
	display: none;
}
</style>
<style type="text/css">
<?php if ($mobile==1) { ?>
.post-content table th {
	width: 100px;
	font-size: 9pt;
}

br.space {
	display: none;
}
<?php } ?>
</style>
<div class="clear"></div></div>
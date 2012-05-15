<h2 class="title">Liste des cours<span class="semester"> : <?php echo $semesters[$_SESSION['schedule_current_semester']]; ?></span><?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|schedule');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><a href="javascript:window.print();" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/printer.png" align="absmiddle" />&nbsp;Imprimer</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div id="notice" style="margin-bottom: 20px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du 8 janvier 2011 à 20h41.</div>
<div class="semester-selection">Session : <select name="semester" id="semester" onchange="javascript:scheduleObj.selectCoursesSemester(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semesters as $semester_date => $semester2) {
		?><option value="<?php echo $semester_date; ?>"<?php if ($_SESSION['schedule_current_semester']==$semester_date) echo ' selected="selected"'; ?>> <?php echo $semester2['title']; ?></option><?php
	}
	?>
</select></div><br />
<?php
if (isset($courses) and $courses!=array()) {
$sectors = array(
				 "Est"					=>	'PVE',
				 "Pavillon de l'Éducation physique et des sports"	=>	'EPS',
				 "Médecine dentaire"	=>	'MDE',
				 "Centre de foresterie des Laurentides"	=>	'CFL',
				 "Abitibi-Price"		=>	'ABP',
				 "Palasis-Prince"		=>	'PAP',
				 "Maison Omer-Gingras"	=>	'OMG',
				 "Services"				=>	'PSA',
				 "Ferdinand-Vandry"		=>	'VND',
				 "Charles-Eugène-Marchand"=>'CHM',
				 "Alexandre-Vachon"		=>	'VCH',
				 "Adrien-Pouliot"		=>	'PLT',
				 "Charles-De Koninck"	=>	'DKN',
				 "Jean-Charles-Bonenfant"=>	'BNF',
				 "Sciences de l'éducation"=>'TSE',
				 "Félix-Antoine-Savard"	=>	'FAS',
				 "Louis-Jacques-Casault"=>	'CSL',
				 "Paul-Comtois"			=>	'CMT',
				 "Maison Eugène-Roberge"=>	'EGR',
				 "Maison Marie-Sirois"	=>	'MRS',
				 "Agathe-Lacerte"		=>	'LCT',
				 "Ernest-Lemieux"		=>	'LEM',
				 "Alphonse-Desjardins"	=>	'ADJ',
				 "Maurice-Pollack"		=>	'POL',
				 "H.-Biermans-L.-Moraud"=>	'PBM',
				 "Alphonse-Marie-Parent"=>	'PRN',
				 "J.-A.-DeSève"			=>	'DES',
				 "La Laurentienne"		=>	'LAU',
				 "Envirotron"			=>	'EVT',
				 "Optique-photonique"	=>	'COP',
				 "Gene-H.-Kruger"		=>	'GHK',
				 "Héma-Québec"			=>	'HQ',
				 "Maison Michael-John-Brophy"=>'BRY',
				 
				 "Maison Couillard"		=>	'MCO',
				 "Serres haute performance"=>'EVS',
				 'Édifice de La Fabrique'=>	'FAB',
				 'Édifice du Boulevard'	=>	'E-BLVD',
				 'Éd. Vieux-Séminaire-de-Québec'	=>	'SEM'
				 
				 );

if (count($courses)!=0) { ?>
<?php
foreach ($courses as $course) {
	if ($course['classes'] != array()) {
		$class =  $course['classes'][0];
	} else {
		$class = array('teacher'=>'', 'day_start'=>'');
	}
		?>
		<div class="class" style="padding: 10px; margin-bottom: 20px; padding-top: 15px; background-color: #dae6f1;"><div style="border-bottom: 1px dotted white; margin-bottom: 5px;"><div class="class-title" style="float: left; font-size: 14pt;"><?php print $course["idcourse"]; ?></div><div class="class-info" style="float: right;"><?php echo $course['credits']; ?> crédits</div><div style="clear: both;"></div></div><div style="color: #666;"><?php print $course["title"]; ?></div><?php if ($class['teacher']!='ACU' and $class['teacher']!='') { ?><div class="class-teacher"><img src="./images/status_online.png" width="16" alt="Professeur" align="absmiddle" style="position: relative;" />&nbsp;<?php echo $class['teacher']; ?></div><?php } ?><?php if (isset($class['day_start']) and $class['day_start']!='') { ?><div class="class-period"><img src="./images/calendar.png" width="16" alt="Période" align="absmiddle" style="position: relative; padding-right: 2px;" />&nbsp;<?php echo strtolower(currentDate($class['day_start'], 'd M Y')." - ".currentDate($class['day_end'], 'd M Y')); ?></div><?php } ?><div style="clear: both;"></div></div>
		<?php
}
} else { echo "Aucun cours n'est inscrit à l'horaire pour cette session."; }
} else { echo "Aucun cours n'est inscrit à l'horaire pour cette session."; }
?><br class="space" />
<style type="text/css">
#content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

#content table th {
	width: 300px;
	font-weight: bold;
	text-align: center;
	vertical-align: bottom;
	padding-bottom: 0px;
	font-size: 12px;
}

#content table td {
	border: 1px solid #efefef;
}

#content table th.hour {
	text-align: right;
	font-size: 8pt;
	background-color: #fff;
	border-bottom: 0px;
	padding: 0px;
	font-weight: normal;
	width: 50px;
	vertical-align: top;
	padding-right: 5px;
	padding-left: 0px;
}

#content table td.class {
	background-color: #dae6f1;
	border: 1px solid white;
	font-size: 12px;
}

.class .class-title {
	font-size: 15px;
	font-weight: bold;
	padding-bottom: 5px;
}

.class .class-info {
	 padding-top: 5px;
}

.class .class-teacher {
	margin-top: 5px; padding: 8px 0px 3px; border-top: 1px dotted white; width: 35%; float: left;
}

.class .class-campus {
	margin-top: 5px; float: left; width: 45%; padding: 8px 0px 3px; border-top: 1px dotted white;
}

.class .class-period {
	margin-top: 5px; float: right; width: 55%; padding: 8px 0px 3px; border-top: 1px dotted white; text-align: right;
}

.class .class-teacher img, .class .class-campus img, .class .class-period img {
	top: 3px;
}

#content table td.class {
	padding: 10px;
}

#content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

#content a.type:hover, #content a.type.active {
	background-color: #888;
	color: #fff;
}

h2.title .semester {
	display: none;
}

#notice {
	background-color: silver;
	padding: 7px 10px;
	font-size: 8pt;
	margin-top: 10px;
	display: none;
}
<?php if ($mobile==1) { ?>
.semester-selection {
	font-size: 10pt;
}

.class {
	font-size: 10pt;
}

.class .class-info {
	 padding-top: 5px;
	 color: #666;
}

.class .class-teacher img, .class .class-campus img, .class .class-period img {
	top: 0px;
}

.class .class-teacher {
	width: 50%;
	padding-bottom: 8px;
}

.class .class-campus {
	width: 50%;
	padding-bottom: 8px;
}

.class .class-period {
	float: none;
	width: 100%;
	clear: both;
	text-align: left;
}

br.space {
	display: none;
}

<?php } ?>
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

#notice {
	background-color: none;
	padding: 0px;
	font-size: 7pt;
	margin-bottom: 25px;
	color: #999;
	display: block;
	text-align: right;
}

.post-content div.class {
	border: 1px solid silver;
}

#semester, #period {
	background-color: #fff;
	border: none;
	font-size: 10pt;
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
<div class="clear"></div></div>
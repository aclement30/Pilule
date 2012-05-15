<h2 class="title">Horaire des cours<span class="semester"> : <?php echo $semesters[$_SESSION['schedule_current_semester']]['title']; ?></span><a href="javascript:window.print();" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/printer.png" align="absmiddle" />&nbsp;Imprimer</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div id="notice" style="margin-bottom: 20px; margin-top: 0px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>

<div class="semester-selection">Session : <select name="semester" id="semester" onchange="javascript:selectSemester(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semesters as $semester_date => $semester2) {
		?><option value="<?php echo $semester_date; ?>"<?php if ($_SESSION['schedule_current_semester']==$semester_date) echo ' selected="selected"'; ?>> <?php echo $semester2['title']; ?></option><?php
		//if ($_SESSION['schedule_current_semester']==$semester_date) break;
	}
	?>
</select>
</div>

<h3>Cours en classe
<?php
$courses = array();
$min_hour = 8;
$max_hour = 22;
$weekdays = array(
						  "L"	=>	"lundi",
						  "M"	=>	"mardi",
						  "R"	=>	"mercredi",
						  "J"	=>	"jeudi",
						  "V"	=>	"vendredi",
						  "S"	=>	"samedi"
						  );
$sectors = array(
				 "Est"					=>	'PVE',
				 "Pavillon de l'Éducation physique et des sports"	=>	'EPS',
				 "PEPS"	=>	'PEPS',
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

foreach ($schedule['courses'] as $course) {
	$courses[$course['nrc']] = $course;
}

if (isset($semester) and $semester['periods']!=array()) {
?>
<div style="float: right; padding: 0px; margin-top: -3px; font-size: 9pt; color: black; font-family: Arial, Helvetica, sans-serif;">Période : <select name="period" id="period" onchange="javascript:changePeriod(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semester['periods'] as $dates => $period) {
		?><option value="<?php echo $dates; ?>"> <?php echo $period; ?></option><?php
	}
	?>
</select></div>
<?php
}
?><div style="clear: both;"></div></h3>

<?php
if (isset($semester) and $semester['periods']!=array()) {
?>
<div style="padding: 0px; margin-bottom: 15px;font-size: 9pt; color: black; font-family: Arial, Helvetica, sans-serif;">Période : <select name="period" id="period" onchange="javascript:changePeriod(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semester['periods'] as $dates => $period) {
		?><option value="<?php echo $dates; ?>"> <?php echo $period; ?></option><?php
	}
	?>
</select></div>
<?php
}

if (isset($schedule) and $schedule!=array()) {

foreach ($timetable as $period => $classes) {
	if ($classes!=array()) {
		?><div id="period-<?php echo $period; ?>" style="<?php if ($current_period!=$period) echo "display: none;"; ?>"><?php

		reset($weekdays);
		$number = 1;
		foreach ($weekdays as $lday => $day) {
			if (isset($classes[$lday]) and $classes[$lday]!=array()) {
				?><h4 style="margin-bottom: 0px;<?php if ($number==1) echo ' margin-top: 0px;'; ?>"><?php echo ucfirst($day); ?></h4><?php
				$hours = array();
				foreach ($classes[$lday] as $class) {
					$hours[] = str_replace(":30", ".5", str_replace(":00", "", $class['hour_start']));
				}
				array_multisort($hours, $classes[$lday]);
				foreach ($classes[$lday] as $class) {
						$course = $courses[$class['nrc']];
						if (strlen($course["title"])>36) {
							$course["title_short"] = substr($course["title"], 0, 36)."...";
						} else {
							$course['title_short'] = $course['title'];
						}
						?><div class="class"><div class="time"><?php echo str_replace(".5", ":30", $class['hour_start'])."<br />".str_replace(".5", ":30", $class['hour_end']); ?></div>
						<div class="info">
						<div class="class-code" style="color: #666;"><?php print $course["code"]; if ($class['type']!='Cours en classe') echo ' ('.$class['type'].')'; ?></div><div class="class-title"><?php print $course["title_short"]; ?></div><div class="class-local"><img src="./images/building.png" width="14" alt="Local" align="absmiddle" style="position: relative; top: 0px; padding-right: 2px;" />&nbsp;<?php
						if ($class['local'] != 'ACU') {
							$local = $class['local'];
							$sector = substr($local, 0, strrpos($local, ' '));
							$local_number = substr($local, strrpos($local, ' ')+1);
							if (array_key_exists($sector,$sectors)) {
								echo $sectors[$sector]." ".$local_number;
							} else {
								echo $sector.", local ".$local_number;
							}
						} else {
							echo '-';
						} ?></div><div class="class-teacher"><img src="./images/status_online.png" width="14" alt="Professeur" align="absmiddle" style="position: relative; top: 0px;" />&nbsp;<?php
						$teacher = explode(' ', $class['teacher']);
						switch (count($teacher)) {
							case 2:
								echo substr($teacher[0], 0, 1).". ".$teacher[1];
							break;
							case 3:
								echo substr($teacher[0], 0, 1).". ".$teacher[1]." ".$teacher[2];
							break;
							case 4:
								echo substr($teacher[0], 0, 1).". ".$teacher[2]." ".$teacher[3];
							break;
						}
						?></div><div style="clear: both;"></div></div>
						<div class="type"><?php
						switch ($class['type']) {
							case 'Atelier':
								?><img src="./images/class_types/workshop.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Atelier</h5><?php
							break;
							case 'Sur Internet':
								?><img src="./images/class_types/internet.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Internet</h5><?php
							break;
							case 'Classe virtuelle synchrone':
								?><img src="./images/class_types/cvs.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Classe virt. sync.</h5><?php
							break;
							case 'Laboratoire':
								?><img src="./images/class_types/lab.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Laboratoire</h5><?php
							break;
							case 'Télévisé-Canal Savoir':
								?><img src="./images/class_types/tv.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>TV/Canal Savoir</h5><?php
							break;
							case 'Stage':
								?><img src="./images/class_types/internship.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Stage</h5><?php
							break;
							case 'Rencontre':
								?><img src="./images/class_types/meeting.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Rencontre</h5><?php
							break;
							case 'Conférence':
								?><img src="./images/class_types/conference.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Conférence</h5><?php
							break;
							case 'Lectures dirigées':
								?><img src="./images/class_types/class.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Lectures dir.</h5><?php
							break;
							case 'Séminaire':
								?><img src="./images/class_types/class.png" width="48" alt="<?php echo $class['type']; ?>" /><h5>Séminaire</h5><?php
							break;
							default:
								?><img src="./images/class_types/class.png" width="48" alt="<?php echo $class['type']; ?>" /><?php
							break;
						}
						?><div style="clear: both;"></div></div><div style="clear: both;"></div></div><?php
				}
				$number++;
			}
		}
	?></div><?php
	} else {
		?><div style="font-size: 10pt; padding-bottom: 5px; margin-bottom: 10px; color: #999;">Aucun cours en classe pour cette session.</div><?php
	}
}

} else echo "Aucun cours ne figure à l'horaire pour cette session.";

if (isset($schedule) and $schedule!=array() and $other_classes != array()) {
	?><div class="page-break"></div>
<h3>Cours à distance</h3>
<?php
foreach ($other_classes as $class) {
	if (strtolower($class['type'])!='cours en classe') {
		$course = $courses[$class['nrc']];
		?>
		<div class="class" style="padding: 10px; margin-bottom: 20px; padding-top: 15px; background-color: #dae6f1;"><div style="border-bottom: 1px dotted white; margin-bottom: 5px;"><div class="class-title" style="float: left; font-size: 14pt;"><?php print $course["code"]; ?></div><div style="float: right; padding-top: 5px;">NRC : <strong><?php echo $course['nrc']; ?></strong>&nbsp;&nbsp;<span style="color: #999;">|</span>&nbsp;&nbsp;<?php echo $course['credits']; ?> crédits</div><div style="clear: both;"></div></div><div style="color: #666;"><?php print $course["title"]; ?></div><?php if ($class['teacher']!='ACU' and $class['teacher']!='') { ?><div style="margin-top: 5px; padding: 8px 0px 3px; border-top: 1px dotted white; width: 40%; float: left;"><img src="./images/status_online.png" width="16" alt="Professeur" align="absmiddle" style="position: relative; top: 3px;" />&nbsp;<?php echo $class['teacher']; ?></div><?php } ?><div style="margin-top: 5px; float: left; width: 25%; padding: 8px 0px 3px; border-top: 1px dotted white; "><img src="./images/building.png" width="16" alt="Campus" align="absmiddle" style="position: relative; top: 3px; padding-right: 2px;" />&nbsp;<?php if ($course['campus']=='Principal') echo 'Campus principal'; else echo $course['campus']; ?></div><?php if (isset($class['day_start']) and $class['day_start']!='') { ?><div style="margin-top: 5px; float: left; width: 35%; padding: 8px 0px 3px; border-top: 1px dotted white; text-align: right;"><img src="./images/calendar.png" width="16" alt="Période" align="absmiddle" style="position: relative; top: 3px; padding-right: 2px;" />&nbsp;<?php echo strtolower(currentDate($class['day_start'], 'd M Y')." - ".currentDate($class['day_end'], 'd M Y')); ?></div><?php } ?><div style="clear: both;"></div></div>
		<?php
	}
}
}
//}
?><script language="javascript">
function selectSemester (semester) {
	window.document.location = './schedule/timetable/'+semester;
}
<?php if (isset($schedule) and $schedule!=array()) { ?>
var currentPeriod = '<?php
echo $current_period; ?>';
<?php } ?>
function changePeriod(period) {
	$('#period-'+currentPeriod).hide();
	currentPeriod = period;
	$('#period-'+currentPeriod).fadeIn();
}
</script>
<style type="text/css">
.page-break { display:none; }

.class-panel {
	display: none;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	width: 300px;
	font-weight: bold;
	text-align: center;
	vertical-align: bottom;
	padding-bottom: 0px;
	font-size: 12px;
}

.post-content table td {
	border: 1px solid #efefef;
}

.post-content table th.hour {
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

.post-content table td.class {
	background-color: #dae6f1;
	border: 1px solid white;
	font-size: 12px;
}

.post-content .class .class-title {
	font-size: 15px;
	font-weight: bold;
	padding-bottom: 5px;
}

.post-content table td.class {
	padding: 10px;
}

.post-content .class .type {
	float: right;
	width: 200px;
	margin-top: 5px;
}

.post-content .class .type h5 {
	float: right;
}

.post-content .class .type img {
	float: right;
	margin-left: 10px;
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

.post-content a.type:hover, #content a.type.active {
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

.post-content .class .class-title {
	font-size: 15px;
	font-weight: bold;
	padding-bottom: 5px;
}

.post-content .class {
	padding: 10px;
	border-bottom: 1px solid silver;
	font-size: 12px;
}

.post-content .class .time {
	text-align: right;
	font-size: 8pt;
	width: 30px;
	font-weight: bold;
	float: left;
	padding-right: 15px;
	padding-left: 0px;
	padding-top: 15px;
}

.post-content .class .class-code {
	padding-bottom: 2px;
}

.post-content .class .class-teacher {
	float: left;
	color: #666;
	
}

.post-content .class .class-local {
	float: left;
	color: #666;
	width: 90px;
}

.post-content .class .info {
	float: left;
}

</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
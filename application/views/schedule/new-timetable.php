<h2 class="title">Horaire des cours<span class="semester"> : <?php echo $semesters[$_SESSION['schedule_current_semester']]['title']; ?></span><?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|schedule');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><a href="javascript:scheduleObj.exportSchedule('<?php echo $_SESSION['schedule_current_semester']; ?>');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/date_go.png" align="absmiddle" />&nbsp;Exporter</a><a href="javascript:window.print();" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/printer.png" align="absmiddle" />&nbsp;Imprimer</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div id="notice" style="margin-bottom: 20px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>

<div class="semester-selection">Session : <select name="semester" id="semester" onchange="javascript:scheduleObj.selectSemester(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semesters as $semester_date => $semester2) {
		?><option value="<?php echo $semester_date; ?>"<?php if ($_SESSION['schedule_current_semester']==$semester_date) echo ' selected="selected"'; ?>> <?php echo $semester2['title']; ?></option><?php
	}
	?>
</select>
</div>

<h3>Cours en classe<?php if (isset($semester) and $semester['periods']!=array()) {
?>
<div style="float: right; padding: 0px; margin-top: -3px; font-size: 9pt; color: black; font-family: Arial, Helvetica, sans-serif;">Période : <select name="period" id="period" onchange="javascript:scheduleObj.changePeriod(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semester['periods'] as $dates => $period) {
		?><option value="<?php echo $dates; ?>"> <?php echo $period; ?></option><?php
	}
	?>
</select></div>
<?php
}
?><div style="clear: both;"></div></h3>
<div id="hours">
<?php
$hour_height = 50;
$min_hour = 8;
$max_hour = 22;

for ($n=$min_hour; $n<($max_hour+1); $n=($n+0.5)) {
	if (round($n)==$n) {
		$hour = $n.":00";
		$half = 0;
	} else {
		$hour = floor($n).":30";
		$half = 1;
	}
	?><div class="hour"><?php if ($half == 1) echo '&nbsp;'; else echo $hour; ?></div><?php
}
?>
</div>
<?php
$courses = array();
foreach ($schedule['courses'] as $course) {
	$courses[$course['nrc']] = $course;
}
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

foreach ($timetable as $period => $classes) {
	if ($classes!=array()) {
		reset($weekdays);
		$columns = 5;
		if (isset($classes['S']) and count($classes['S']) != 0) $columns = 6;
		?><div class="timetable<?php if ($columns == 6) echo ' full-week'; ?>" id="period-<?php echo $period; ?>" style="<?php if ($current_period!=$period) echo "display: none;"; ?>"><?php
			foreach ($weekdays as $lday => $day) {
				if ($lday == 'S' and $columns == 5) break;
				?><div class="weekday<?php if ($lday == 'L') echo ' first'; ?>"><div class="title"><?php echo ucfirst($day); ?></div><div class="inside"><?php
				if (isset($classes[$lday]) and $classes[$lday]!=array()) {
					$firstClass = 1;
					$number = 0;
					$lastClassEnd = 0;
					
					foreach ($classes[$lday] as $class) {
						$classLength = $class["hour_end"]-$class["hour_start"];
						
						$course = $courses[$class['nrc']];
						
						// Affichage de l'espace vide
						if ($firstClass == 1) {
							?><div class="empty" style="height: <?php echo ($class["hour_start"]-$min_hour)*$hour_height; ?>px;">&nbsp;</div><?php
							$firstClass = 0;
						}
						
						if ($lastClassEnd != 0 and $class['hour_start'] != $lastClassEnd) {
							?><div class="empty" style="height: <?php echo ($class["hour_start"]-$lastClassEnd)*$hour_height; ?>px;">&nbsp;</div><?php
						}
						
						// Affichage de la classe
						?><div class="class" style="height: <?php echo ($classLength*$hour_height)-($number+2); ?>px;" title="<?php echo $course['title']; ?>">
						<div class="class-content">
							<div class="time"><?php echo str_replace(".5", ":30", $class["hour_start"]); ?></div>
							<div class="code"><?php print $course["code"]; ?></div>
							<div style="clear: both;"></div>
							<?php if ($classLength > 0.5) { ?>
							<?php if ($classLength > 1) { ?>
							<div class="class-title"><?php echo $course['title']; ?></div>
							<?php } ?>
							<?php if ($class['local']!='' and $class['local']!='ACU') { ?>
							<div class="location">
							<img src="./images/house.png" width="12" alt="Local" align="absmiddle" style="position: relative; top: 2px; padding-right: 2px;" />&nbsp;<?php
								$local = $class['local'];
								$sector = substr($local, 0, strrpos($local, ' '));
								$local_number = substr($local, strrpos($local, ' ')+1);
								if (array_key_exists($sector, $sectors)) {
									echo $sectors[$sector]." ".$local_number;
								} else {
									echo $sector.", local ".$local_number;
								}
							?>
							</div><?php } ?>
							<?php if ($class['teacher']!='' and $class['teacher']!='ACU' and $classLength > 1 and strpos($class['teacher'], ",")<=0) { ?>
							<div class="teacher">
							<img src="./images/status_online.png" width="12" alt="Professeur" align="absmiddle" style="position: relative; top: 2px;" />&nbsp;<span><?php
								$teacher = explode(' ', $class['teacher']);
								$teacher_name = '';
								switch (count($teacher)) {
									case 2:
										$teacher_name = substr(str_replace("É", "E", str_replace("È", "E", $teacher[0])), 0, 1).". ".$teacher[1];
									break;
									case 3:
										$teacher_name = substr(str_replace("É", "E", str_replace("È", "E", $teacher[0])), 0, 1).". ".$teacher[1]." ".$teacher[2];
									break;
									case 4:
										$teacher_name = substr(str_replace("É", "E", str_replace("È", "E", $teacher[0])), 0, 1).". ".$teacher[2]." ".$teacher[3];
									break;
								}
								if (strlen($teacher_name)>12) {
									$teacher_name = substr($teacher_name, 0, 12)."...";
								}
								echo $teacher_name;
							?></span>
							</div>
							<?php } ?>
							<?php } ?>
						</div></div><?php
						$lastClassEnd = $class['hour_end'];
					}
				} else {
					?>&nbsp;<?php
				}
				?></div></div><?php
			}
		?><div style="clear: both;"></div></div><?php
	} else { echo "</h3>Aucun cours ne figure à l'horaire pour cette session."; }
}
?><div style="clear: both;"></div>
<?php
if (isset($schedule) and $schedule!=array() and $other_classes != array()) {
	?>
	<div class="page-break"></div>
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
?>
<style type="text/css">
.page-break { display:none; }

.post-content #hours {
	width: 30px;
	float: left;
	margin-top: 11px;
}

.post-content #hours .hour {
	text-align: right;
	height: <?php echo $hour_height/2; ?>px;
	font-size: 8pt;
	color: #777;
}

.timetable {
	width: 540px;
	float: right;
	background-image: url(./images/timetable-bg.gif);
	background-repeat: repeat;
	background-position: 0 20px;
}

.post-content .timetable .weekday {
	width: 108px;
	float: left;
}

.post-content .timetable.full-week .weekday {
	width: 90px;
}

.weekday .title {
	font-weight: bold;
	text-align: center;
	vertical-align: bottom;
	padding-bottom: 0px;
	margin-bottom: 1px;
	font-size: 12px;
}

.post-content .weekday .empty {
}

.post-content .weekday .class {
	background-color: #dae6f1;
	border: 1px solid #a9c1d6;
	border-radius: 2px;
	cursor: default;
}

.post-content .weekday .class .class-content {
	padding: 2px 3px;
}

.post-content .weekday .class .time {
	text-shadow: #fff 1px 1px 1px;
	font-size: 8pt;
	float: left;
}

.post-content .weekday .class .code {
	text-shadow: #fff 1px 1px 1px;
	color: #3d9bf1;
	font-size: 8pt;
	float: right;
}

.post-content .weekday .class .class-title {
	text-shadow: #fff 1px 1px 1px;
	font-weight: bold;
	padding: 2px 0;
}

.post-content .weekday .class .location {
	text-shadow: #fff 1px 1px 1px;
	padding-top: 1px;
}

.post-content .weekday .class .teacher {
	text-shadow: #fff 1px 1px 1px;
	padding-top: 2px;
}

.weekday .inside {
	border-right: 1px solid #ddd;
	height: 701px;
	border-left: 0px;
}

.weekday.first .inside {
	border-left: 1px solid #ddd;
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

.timetable {
	width: 8.45in;
	float: left;
	margin-left: 15px;
}

.post-content .timetable .weekday {
	width: 1.5in;
}

.post-content .timetable.full-week .weekday {
	width: 1.4in;
}

.weekday .inside {
	padding: 5px;
	border-right: 1px solid #eee;
}

.weekday.first .inside {
	border-left: 1px solid #eee;
}

.post-content .weekday .class {
	background-color: #fff;
	border: 0px;
	border-radius: 0px;
	border-left: 3px solid #3d9bf1;
}

.post-content .weekday .class .class-content {
	padding-left: 5px;
}

.post-content #hours .hour {
	color: #666;
}

h2.title .semester {
	display: inline;
}

.semester-selection {
	display: none;
}

.post-content .weekday .class .time {
	text-shadow: none;
}

.post-content .weekday .class .code {
	text-shadow: none;
}

.post-content .weekday .class .class-title {
	text-shadow: none;
}

.post-content .weekday .class .location {
	text-shadow: none;
}

.post-content .weekday .class .teacher {
	text-shadow: none;
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

.page-break { display:block; page-break-before:always; }
</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
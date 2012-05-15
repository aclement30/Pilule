<h2 class="title" style="display: block;"><?php echo $semesters[$_SESSION['schedule_current_semester']]['title']; ?></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<?php
$courses = array();
$min_hour = 22;
$max_hour = 8;
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
<div style="padding: 0px; margin-bottom: 15px;font-size: 9pt; color: black; font-family: Arial, Helvetica, sans-serif;">Période : <select name="period" id="period" onchange="javascript:changePeriod(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semester['periods'] as $dates => $period) {
		?><option value="<?php echo $dates; ?>"> <?php echo $period; ?></option><?php
	}
	?>
</select></div>
<?php
}

foreach ($timetable as $period => $classes) {
	if ($classes!=array()) {
		?><div id="period-<?php echo $period; ?>" style="<?php if ($current_period!=$period) echo "display: none;"; ?>"><?php

		reset($weekdays);
		$number = 1;
		foreach ($weekdays as $lday => $day) {
			if (isset($classes[$lday]) and $classes[$lday]!=array()) {
				?><h3 style="margin-bottom: 0px;<?php if ($number==1) echo ' margin-top: 0px;'; ?>"><?php echo ucfirst($day); ?></h3><?php
				$hours = array();
				foreach ($classes[$lday] as $class) {
					$hours[] = str_replace(":30", ".5", str_replace(":00", "", $class['hour_start']));
				}
				array_multisort($hours, $classes[$lday]);
				foreach ($classes[$lday] as $class) {
						$course = $courses[$class['nrc']];
						if (strlen($course["title"])>26) {
							$course["title_short"] = substr($course["title"], 0, 26)."...";
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
							print $sectors[$sector]." ".$local_number;
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
						?></div><div style="clear: both;"></div></div><div style="clear: both;"></div></div><?php
				}
				$number++;
			}
		}
	?></div><?php
	} else {
		?><div style="font-size: 10pt; padding-bottom: 5px; margin-bottom: 10px; color: #999;">Aucun cours en classe pour cette session.</div><?php
	}
}
?>
<div class="semester-selection" style="font-size: 10pt; margin-top: 15px;">Autres sessions : <select name="semester" id="semester" onchange="javascript:selectSemester(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semesters as $semester_date => $semester2) {
		?><option value="<?php echo $semester_date; ?>"<?php if ($_SESSION['schedule_current_semester']==$semester_date) echo ' selected="selected"'; ?>> <?php echo $semester2['title']; ?></option><?php
	}
	?>
</select>
</div>
<?php

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
	padding-top: 18px;
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
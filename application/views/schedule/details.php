<h2 class="title">Horaire des cours<span class="semester"> : <?php echo $semesters[$_SESSION['schedule_current_semester']]; ?></span><?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|schedule');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><a href="javascript:window.print();" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/printer.png" align="absmiddle" />&nbsp;Imprimer</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div id="notice" style="margin-bottom: 20px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>

<div class="semester-selection">Session : <select name="semester" id="semester" onchange="javascript:selectSemester(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semesters as $semester => $name) {
		?><option value="<?php echo $semester; ?>"<?php if ($_SESSION['schedule_current_semester']==$semester) echo ' selected="selected"'; ?>> <?php echo $name; ?></option><?php
	}
	?>
</select>
</div>
<h3>Cours en classe
<?php
$classes = array();
$courses = array();
$timetable = array();
$min_hour = 22;
$max_hour = 0;
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
				 "Serres haute performance"=>'EVS'
				 
				 );

foreach ($weekdays as $lday => $day) {
	$timetable[$lday] = array();
}

foreach ($schedule['courses'] as $course) {
	$courses[$course['nrc']] = $course;
	
	foreach ($course['classes'] as $class) {
		$class['nrc'] = $course['nrc'];
		if (isset($class['hour_start'])) {
			$class['hour_start'] = str_replace(":30", ".5", str_replace(":00", "", str_replace(":20", ".5", $class['hour_start'])));
			if (strpos($class['hour_start'], ':50')>1) {
				$class['hour_start'] = str_replace(":50", "", $class['hour_start']);
				$class['hour_start']++;
			}
			if ($class['hour_start']<=$min_hour) $min_hour = $class['hour_start']-0.5;
		}
		if (isset($class['hour_end'])) {
			$class['hour_end'] = str_replace(":30", ".5", str_replace(":00", "", str_replace(":20", ".5", $class['hour_end'])));
			if (strpos($class['hour_end'], ':50')>1) {
				$class['hour_end'] = str_replace(":50", "", $class['hour_end']);
				$class['hour_end']++;
			}
			if ($class['hour_end']>=$max_hour) $max_hour = $class['hour_end'];
		}
		
		$classes[] = $class;
		$timetable[$class['day']][] = $class;
	}
}

$courses_type1 = 0;
$courses_type2 = 0;
$days_start = array();
$days_end = array();

foreach ($classes as $class) {
	if (strtolower($class['type'])!='cours en classe') {
		$courses_type2++;
	} else {
		$days_start[] = $class['day_start'];
		$days_end[] = $class['day_end'];
	}
}

$array_difference = array_count_values($days_start);
$array_difference2 = array_count_values($days_end);

$courses_type1 = count($classes)-$courses_type2;

if ($timetable['S']==array()) unset($weekdays['S']);

if (count($array_difference)>1) {
?>
<div style="float: right; padding: 0px; margin-top: -3px; font-size: 9pt; color: black; font-family: Arial, Helvetica, sans-serif;">Période : <select name="period" id="period" onchange="javascript:changePeriod(this.options[this.selectedIndex].value);">
	<?php
	reset($array_difference2);
	foreach ($array_difference as $period => $count) {
		?><option value="<?php echo $period; ?>"> <?php echo strtolower(currentDate($period, 'd M')." &ndash; ".currentDate(key($array_difference2), "d M Y")); ?></option><?php
		next($array_difference2);
	}
	?>
</select></div>
<?php
}
?><div style="clear: both;"></div></h3>
<?php
if ($courses_type1!=0) {

reset($array_difference2);
$number_period = 0;
reset($array_difference);
foreach ($array_difference as $period => $count) {
?>
<table cellspacing="1" id="period-<?php echo $period; ?>" style="<?php if ($number_period!=0) echo "display: none;"; ?>">
	<tbody>
		<tr>
			<th style="border: 0px;" width="50">&nbsp;</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Lundi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Mardi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Mercredi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Jeudi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Vendredi</th>
			<?php if ($timetable['S']!=array()) { ?><th style="font-weight: bold; text-align: center;">Samedi</th><?php } ?>
		</tr>
		<?php
		$hour_start = $min_hour;
		$hour_end = $max_hour;
		
		for ($n=$hour_start; $n<($hour_end+1); $n=($n+0.5)) {
			if (round($n)==$n) {
				$hour = $n.":00";
				$half = 0;
			} else {
				$hour = floor($n).":30";
				$half = 1;
			}
		?>
		<tr>
			<th class="hour left" style="width: 20px;<?php if ($half==0) { print "font-weight: bold;"; } else { print "color: gray;"; } ?>"><div style="position: relative; top: -6px;"><?php print $hour; ?></div></th>
			<?php
			reset($weekdays);
			foreach ($weekdays as $lday => $day) {
				if ($timetable[$lday]!=array()) {
					$number = 0;
					foreach ($timetable[$lday] as $class) {
						if ($class['day_start']>=$period and $class['day_end']<=key($array_difference2)) {
							if ($class["hour_start"]==$n) {
								$course = $courses[$class['nrc']];
								if (strlen($course["title"])>26) {
									$course["title_short"] = substr($course["title"], 0, 26)."...";
								} else {
									$course['title_short'] = $course['title'];
								}
								?><td class="class" rowspan="<?php print ($class["hour_end"]-$class["hour_start"])*2; ?>" style="vertical-align: top;"><div class="class-title"><?php print $course["code"]; ?></div><div style="cursor: help; color: #666;" title="<?php print $course["title"]; ?>"><?php print $course["title_short"]; ?></div><div style="cursor:help;margin-top: 5px; padding: 5px 0px 3px; border-top: 1px dotted white; border-bottom: 1px dotted white;" title="<?php echo $class['teacher']; ?>"><img src="./images/status_online.png" width="14" alt="Professeur" align="absmiddle" style="position: relative; top: 2px;" />&nbsp;<?php
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
								?></div><div style="margin-top: 5px; cursor:help;" title="<?php echo $class['local']; ?>"><img src="./images/house.png" width="14" alt="Local" align="absmiddle" style="position: relative; top: 2px; padding-right: 2px;" />&nbsp;<?php
								$local = $class['local'];
								$sector = substr($local, 0, strrpos($local, ' '));
								$local_number = substr($local, strrpos($local, ' ')+1);
								print $sectors[$sector]." ".$local_number; ?></div></td><?php
								$number = 1;
							} elseif ($class["hour_start"]<$n and $class["hour_end"]>$n) {
								$number = 1;
							}
						}
					}
					if ($number==0) {
						?><td class="empty<?php if ($half!=1) echo ' half'; ?>" rowspan="1"<?php if ($n>=($hour_end+1)) print " style=\"display: none;\""; ?>>&nbsp;</td><?php
					}
				} else {
					?><td class="empty<?php if ($half!=1) echo ' half'; ?>" rowspan="1"<?php if ($n>=($hour_end+1)) print " style=\"display: none;\""; ?>>&nbsp;</td><?php
				}
			}
	?></tr>
	<?php
		}
		?>
	</tbody>
</table>
<?php
	$number_period++;
	next($array_difference2);
}
} else { echo "Aucun cours ne figure à l'horaire pour cette session."; }

if ($courses_type2!=0) { ?>
<div class="page-break"></div>
<h3>Cours à distance</h3>
<?php
foreach ($classes as $class) {
	if (strtolower($class['type'])!='cours en classe') {
		$course = $courses[$class['nrc']];
		?>
		<div class="class" style="padding: 10px; margin-bottom: 20px; padding-top: 15px; background-color: #dae6f1;"><div style="border-bottom: 1px dotted white; margin-bottom: 5px;"><div class="class-title" style="float: left; font-size: 14pt;"><?php print $course["code"]; ?></div><div style="float: right; padding-top: 5px;">NRC : <strong><?php echo $course['nrc']; ?></strong>&nbsp;&nbsp;<span style="color: #999;">|</span>&nbsp;&nbsp;<?php echo $course['credits']; ?> crédits</div><div style="clear: both;"></div></div><div style="color: #666;"><?php print $course["title"]; ?></div><?php if ($class['teacher']!='ACU' and $class['teacher']!='') { ?><div style="margin-top: 5px; padding: 8px 0px 3px; border-top: 1px dotted white; width: 35%; float: left;"><img src="./images/status_online.png" width="16" alt="Professeur" align="absmiddle" style="position: relative; top: 3px;" />&nbsp;<?php echo $class['teacher']; ?></div><?php } ?><div style="margin-top: 5px; float: left; width: 30%; padding: 8px 0px 3px; border-top: 1px dotted white; "><img src="./images/house.png" width="16" alt="Campus" align="absmiddle" style="position: relative; top: 3px; padding-right: 2px;" />&nbsp;<?php if ($course['campus']=='Principal') echo 'Campus principal'; else echo $course['campus']; ?></div><?php if (isset($class['day_start']) and $class['day_start']!='') { ?><div style="margin-top: 5px; float: left; width: 35%; padding: 8px 0px 3px; border-top: 1px dotted white; text-align: right;"><img src="./images/calendar.png" width="16" alt="Période" align="absmiddle" style="position: relative; top: 3px; padding-right: 2px;" />&nbsp;<?php echo strtolower(currentDate($class['day_start'], 'd M Y')." - ".currentDate($class['day_end'], 'd M Y')); ?></div><?php } ?><div style="clear: both;"></div></div>
		<?php
	}
}
}
?><br />
<script language="javascript">
function selectSemester (semester) {
	window.document.location = '<?php echo site_url(); ?>schedule/timetable/'+semester;
}

var currentPeriod = '<?php
reset($array_difference);
echo key($array_difference); ?>';

function changePeriod(period) {
	$('#period-'+currentPeriod).hide();
	currentPeriod = period;
	$('#period-'+currentPeriod).fadeIn();
}
</script>
<style type="text/css">
.page-break { display:none; }

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
	text-align: right;
	display: block;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
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

.post-content table td.empty {
	border-right: 1px solid silver;
}

.post-content table td.empty.half {
	border-bottom: 1px dotted #ddd;
}

.post-content .class .class-title {
	font-size: 15px;
	font-weight: bold;
	padding-bottom: 5px;
}

.post-content table td.class {
	padding: 10px;
}

.post-content table th.left, .post-content table td {
	padding: 5px;
	vertical-align: top;
	border-bottom: 1px solid silver;
}

.post-content table td.class {
	background-color: #dae6f1;
	border: 1px solid silver;
	font-size: 12px;
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

#semester, #period {
	background-color: #fff;
	border: none;
	font-size: 10pt;
}

.post-content table th.weekday {
	width: 20%;
}

.post-content div.class {
	border: 1px solid silver;
}

.post-content div.class {
}

h2.title .semester {
	display: inline;
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
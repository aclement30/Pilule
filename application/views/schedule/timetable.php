<div class="row-fluid" style="margin-top: 10px;">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="icon-calendar"></i></span>
                <h5>Cours en classe</h5>
                <div class="buttons">
                    <a id="add-event" data-toggle="modal" href="#modal-add-event" class="btn btn-success btn-mini"><i class="icon-plus icon-white"></i> Add new event</a>
                    <div class="modal hide" id="modal-add-event">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h3>Add a new event</h3>
                        </div>
                        <div class="modal-body">
                            <p>Enter event name:</p>
                            <p><input id="event-name" type="text" /></p>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn" data-dismiss="modal">Cancel</a>
                            <a href="#" id="add-event-submit" class="btn btn-primary">Add event</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-content nopadding">
                <div class="panel-left">
                    <div id="fullcalendar"></div>
                </div>
                <div id="external-events" class="panel-right">
                    <div class="panel-title"><h5>Events</h5></div>
                    <div class="panel-content">
                        <div class="external-event ui-draggable label label-inverse">My Event 1</div>
                        <div class="external-event ui-draggable label label-inverse">My Event 2</div>
                        <div class="external-event ui-draggable label label-inverse">My Event 3</div>
                        <div class="external-event ui-draggable label label-inverse">My Event 4</div>
                        <div class="external-event ui-draggable label label-inverse">My Event 5</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h3>Cours en classe
<?php
if (isset($schedule) and $schedule!=array()) {
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
<?php
foreach ($timetable as $period => $classes) {
	if ($classes!=array()) {
?>
<table cellspacing="1" id="period-<?php echo $period; ?>" style="<?php if ($current_period!=$period) echo "display: none;"; ?>">
	<tbody>
		<tr>
			<th style="border: 0px;" width="50">&nbsp;</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Lundi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Mardi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Mercredi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Jeudi</th>
			<th class="weekday" style="font-weight: bold; text-align: center;">Vendredi</th>
			<?php if (isset($classes['S']) and $classes['S']!=array()) { ?><th style="font-weight: bold; text-align: center;">Samedi</th><?php } ?>
		</tr>
		<?php
		$hour_start = $min_hour;
		$hour_end = $max_hour;
		
		for ($n=$hour_start; $n<($max_end_time+1); $n=($n+0.5)) {
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
				if (isset($classes[$lday]) and $classes[$lday]!=array()) {
					$number = 0;
					foreach ($classes[$lday] as $class) {
						if ($class["hour_start"]==$n) {
							$course = $courses[$class['nrc']];
							if (strlen($course["title"])>26) {
								$course["title_short"] = substr($course["title"], 0, 26)."...";
							} else {
								$course['title_short'] = $course['title'];
							}
							?><td class="class" rowspan="<?php print ($class["hour_end"]-$class["hour_start"])*2; ?>" style="vertical-align: top;"><div class="class-title"><?php print $course["code"]; ?></div><div style="cursor: help; color: #666;" title="<?php print $course["title"]; ?>"><?php print $course["title_short"]; ?></div><?php if ($class['teacher']!='' and $class['teacher']!='ACU') { ?><div style="cursor:help;margin-top: 5px; padding: 5px 0px 3px; border-top: 1px dotted white; border-bottom: 1px dotted white;" title="<?php echo $class['teacher']; ?>"><img src="./images/status_online.png" width="14" alt="Professeur" align="absmiddle" style="position: relative; top: 2px;" />&nbsp;<?php
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
							?></div><?php } if ($class['local']!='' and $class['local']!='ACU') { ?><div style="margin-top: 5px; cursor:help;" title="<?php echo $class['local']; ?>"><img src="./images/building.png" width="14" alt="Local" align="absmiddle" style="position: relative; top: 2px; padding-right: 2px;" />&nbsp;<?php
							$local = $class['local'];
							$sector = substr($local, 0, strrpos($local, ' '));
							$local_number = substr($local, strrpos($local, ' ')+1);
							print $sectors[$sector]." ".$local_number; ?></div><?php } ?></td><?php
							$number = 1;
						} elseif ($class["hour_start"]<$n and $class["hour_end"]>$n) {
							$number = 1;
						}
					}
					if ($number==0) {
						?><td class="empty<?php if ($half!=1) echo ' half'; ?>" rowspan="1"<?php if ($n>=($hour_end+1)) print " style=\"display: none;\""; ?>>&nbsp;</td><?php
					}
				} elseif ($lday!='S') {
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
	} else { echo "</h3>Aucun cours ne figure à l'horaire pour cette session."; }
}
} else { echo "</h3>Aucun cours ne figure à l'horaire pour cette session."; }
if (isset($schedule) and $schedule!=array() and $other_classes != array()) {
//if ($courses_type2!=0) { ?>
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
//}
?><br />

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
				 "Serres haute performance"=>'EVS',
				 'Édifice de La Fabrique'=>	'FAB',
				 'Édifice du Boulevard'	=>	'E-BLVD',
				 'Éd. Vieux-Séminaire-de-Québec'	=>	'SEM'
				 
				 );

if (count($course['classes'])!=0) { ?>
<?php
$number = 0;
foreach ($course['classes'] as $class) {
		$number++;
		
		$timetable = array();
		$types = array();
		$distance = 0;
		
		foreach ($class['timetable'] as $class2) {
			if (!isset($timetable[$class2['day_start'].$class2['day_end']])) $timetable[$class2['day_start'].$class2['day_end']] = array();
			
			$timetable[$class2['day_start'].$class2['day_end']][] = $class2;
			if (!in_array($class2['type'], $types)) $types[] = $class2['type'];
			switch ($types[0]) {
				case 'Sur Internet';
				case 'Classe virtuelle synchrone';
					$distance++;
				break;
				default:
				break;
			}
		}
		
		?>
		<div class="class-choice" style="<?php if ($number%3==0) echo 'margin-right: 0px;'; ?>">
			<div class="type"><div style="float: left;"><?php
			if (count($types) == 1) {
				switch ($types[0]) {
					case 'Sur Internet';
						echo 'Cours à distance';
					break;
					default:
						echo $types[0];
					break;
				}
			} else {
				if (count($types) == $distance) {
					echo 'Cours à distance';
				} else {
					switch ($types[0]) {
						case 'Sur Internet';
							echo 'Cours à distance';
						break;
						default:
							echo 'Cours en classe';
						break;
					}
				}
			}
			?></div><div style="float: right; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #666;"><?php
			switch (substr($class['semester'], 4, 2)) {
				case '01':
					echo 'H-';
				break;
				case '05':
					echo 'E-';
				break;
				case '09':
					echo 'A-';
				break;
			}
			echo substr($class['semester'], 2, 2); ?></div><div style="clear: both;"></div></div>
			<?php
			if (trim($class['timetable'][0]['day']) != '' and $class['timetable'][0]['day'] != ' ') {
			?>
			<div class="timetable" style="line-height: 12pt; padding-top: 5px;">
			<?php
			foreach ($timetable as $period => $classes) {
			?>
				<table style="width: 100%; background-color: #cdcdcd; padding: 5px; margin-bottom: 5px;" cellspacing="0">
					<tbody>
						<tr><td colspan="4" style="font-weight: bold; padding-bottom: 5px; border-bottom: 1px dotted #999;"><?php echo strtolower(currentDate($class2['day_start'], 'd M Y')." - ".currentDate($class2['day_end'], 'd M Y')); ?></td><tr><?php
				$n = 0;
				foreach ($classes as $class2) {
					if (trim($class2['day']) != '' and $class2['day'] != ' ') {
					?>
						<td style="width: 40%; padding-top: 5px;"><?php
						switch ($class2['type']) {
							case 'Cours en classe':
								echo 'Classe';
							break;
							case 'Laboratoire':
								echo 'Lab.';
							break;
							case 'Classe virtuelle synchrone':
								echo 'C. virt. sync.';
							break;
							default:
								echo $class2['type'];
							break;
						} ?></td>
						<td style="color: black; padding-top: 5px; font-size: 9pt; font-family: 'Lucida Console', Monaco, monospace;"><div style="background-color: #666; color: #fff; line-height: 8pt; padding: 2px 3px; float: left;"><?php echo strtoupper(substr($weekdays[$class2['day']], 0, 3)); ?></div><div style="clear: both;"></div></td>
						<td style="text-align: right; padding-top: 5px;"><?php echo $class2['hour_start']." - ".$class2['hour_end']; ?></td>
					</tr>
					<?php
						$n++;
					}
				}
				?></tbody></table>
			<?php } ?>
			</div>
			<?php } else { ?><div class="timetable" style="border-bottom: 1px dotted silver; padding-bottom: 5px;"><?php echo strtolower(currentDate($class['timetable'][0]['day_start'], 'd M Y')." - ".currentDate($class['timetable'][0]['day_end'], 'd M Y')); ?></div><?php }
			if (is_array($class['spots'])) {
			if ($class['spots']['total'] != 0) { ?>
			<div class="teacher" style="float: none;">Places disponibles : <span style="color: <?php if ($class['spots']['remaining']==0) echo 'red'; elseif ($class['spots']['remaining']<=5) echo 'orange'; else echo '#444'; ?>;"><?php echo '<b>'.$class['spots']['remaining']."</b>"; ?></span></div><?php } ?>
			<?php if ($class['spots']['waiting_total']>0 and $class['spots']['remaining'] == 0) { ?>
			<div class="teacher" style="float: none;">Liste d'attente : <span style="color: <?php if ($class['spots']['waiting_remaining']==0) echo 'red'; elseif ($class['spots']['waiting_remaining']<=5) echo 'orange'; else echo '#444'; ?>;"><?php echo '<b>'.$class['spots']['waiting_remaining']."</b>"; ?></span></div><?php }
			}
			if ($class['notes'] != '' && md5($class['notes']) != '33adb44e733af8427874c76a56d93176' && md5($class['notes']) != '68b1763520d12df2db4eb98673cc888b') {
			?>
			<div class="notes" id="class-<?php echo $class['nrc']; ?>-notes"><?php
			if (md5($class['notes']) == '6496af59f3e58084b2a48b4fb93bf696') {
				echo 'Section accessible aux étudiants provenant des autres programmes.';
			} else {
				//echo md5($class['notes'])."<br>";
				echo substr(str_replace("", "'", $class['notes']), 0, 60); ?>...<div style="text-align: left; padding-top: 2px;"><a class="show-notes-link" href="javascript:displayNotes(<?php echo $class['nrc']; ?>);">&raquo; Voir plus</a></div><?php } ?></div>
			<div class="notes full" id="class-<?php echo $class['nrc']; ?>-notes-full" style="display: none;"><?php
			echo str_replace("", "'", $class['notes']);
			?></div>
			<?php }
			if ($class['campus'] != 'Principal') { ?><div class="teacher" style="float: none;"><?php echo $class['campus']; ?></div><?php }
			if ($class['teacher']!='ACU') { ?><div class="teacher" style="float: none;"><img src="<?php echo site_url(); ?>images/status_online.png" width="16" alt="Professeur" align="absmiddle" style="position: relative; top: 0px;" />&nbsp;<?php echo $class['teacher']; ?></div><?php } ?><div style="clear: both;"></div>
			<div class="nrc">NRC : <strong><?php echo $class['nrc']; ?></strong><div style="float: right; margin-left: 10px;"><?php
			$found = 0;
			foreach ($selected_courses as $course2) {
				if ($class['nrc']==$course2['nrc']) {
					$found = 1;
					break;
				}
			}
			if ($found==1) { ?><span style="color: green;">Sélectionné</span><?php }
			foreach ($registered_courses as $course2) {
				if ($class['nrc']==$course2['nrc']) {
					$found = 2;
					break;
				}
			}
			if ($found==2) { ?><span style="color: green;">Inscrit</span><?php }
			
			if ($found==0) {
				?><input type="button" value="+ Ajouter" onclick="javascript:top.registrationObj.addSelectedCourse('<?php echo $class['nrc']; ?>');" /><?php
			} ?></div><div style="clear: both;"></div>
			</div><div style="clear: both;"></div>
		</div>
		<?php
}
?><div style="clear: both;"></div>
<script language="javascript">
function displayNotes(nrc) {
	$('#class-'+nrc+'-notes').hide();
	$('#class-'+nrc+'-notes-full').show();
}
</script>
<?php
} ?>
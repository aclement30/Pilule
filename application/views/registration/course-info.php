<h2 class="title">Fiche du cours</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3><?php echo $course['title']; ?><div style="float: right; color: black;"><?php echo $course['id']; ?> - <?php echo $course['credits']; ?> crédits</div><div style="clear: both;"></div></h3>	

<?php echo $course['description']; ?>
<?php if ($course['restrictions'] != '') { ?><div style="float: left; width: 47%; margin-right: 30px;"><h4 style="margin-top: 10px;">Restrictions</h4>
<p style="line-height: 20px; margin-top: 0px; padding-top: 0px;"><?php if (md5($course['restrictions']) == 'e6a3382bd06b53ce1db9e05be135757a') echo 'Non disponible en formation continue'; else echo str_replace("<br /><br />", "<br />", str_replace("\n", "<br />", $course['restrictions'])); ?></p></div><?php } ?>
<?php if ($course['prerequisites'] != '') { ?><div style="float: left; width: 47%;"><h4 style="margin-top: 10px;">Préalables</h4>
<p style="line-height: 20px; margin-top: 0px; padding-top: 0px;"><?php echo str_replace(" ET ", " <strong>ET</strong> ", str_replace(" OU ", " <strong>OU</strong> ", $course['prerequisites'])); ?></p></div><?php } ?>
<div style="clear: both;"></div>
<h4 style="margin-top: 20px;">Cours disponibles</h4>
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

if (count($course['classes'])!=0) { ?>
<?php
$number = 0;
foreach ($course['classes'] as $class) {
		$number++;
		?>
		<div class="class-choice" style="<?php if ($number%3==0) echo 'margin-right: 0px;'; ?>">
			<div class="type"><div style="float: left;"><?php
			switch ($class["timetable"][0]['type']) {
				case 'Cours en classe':
					echo 'Cours en classe';
				break;
				case 'Sur Internet';
					echo 'Cours par Internet';
				break;
				default:
					echo $class["timetable"][0]['type'];
				break;
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
			if ($class["timetable"][0]['type']=='Cours en classe') {
			?>
			<div class="timetable">
				<table style="width: 100%;">
					<tbody><?php
				foreach ($class['timetable'] as $class2) {
					?><tr>
						<td style="color: black;" title="<?php echo strtolower(currentDate($class2['day_start'], 'd M Y')." - ".currentDate($class2['day_end'], 'd M Y')); ?>"><?php echo ucfirst($weekdays[$class2['day']]); ?></td>
						<td style="font-weight: bold; text-align: right;" title="<?php echo strtolower(currentDate($class2['day_start'], 'd M Y')." - ".currentDate($class2['day_end'], 'd M Y')); ?>"><?php echo $class2['hour_start']." - ".$class2['hour_end']; ?></td>
					</tr>
					<?php
				}
				?></tbody></table>
			</div>
			<div class="timetable" style="padding-top: 5px; padding-bottom: 5px;"><?php echo strtolower(currentDate($class['timetable'][0]['day_start'], 'd M Y')." - ".currentDate($class['timetable'][0]['day_end'], 'd M Y')); ?></div><?php
			} else {
				?><div class="timetable"><?php echo strtolower(currentDate($class['timetable'][0]['day_start'], 'd M Y')." - ".currentDate($class['timetable'][0]['day_end'], 'd M Y')); ?></div><?php
			}
			if ($class['timetable'][0]['local']!='ACU') { ?><div class="teacher" style="float: none;" title="<?php echo $class['timetable'][0]['local']; ?>"><img src="./images/house.png" width="16" alt="Professeur" align="absmiddle" style="position: relative; top: 2px;" />&nbsp;&nbsp;<?php
								$local = $class['timetable'][0]['local'];
								$sector = substr($local, 0, strrpos($local, ' '));
								$local_number = substr($local, strrpos($local, ' ')+1);
								print $sectors[$sector]." ".$local_number; ?></div><?php }
			if ($class['teacher']!='ACU') { ?><div class="teacher" style="float: none;"><img src="./images/status_online.png" width="16" alt="Professeur" align="absmiddle" style="position: relative; top: 2px;" />&nbsp;<?php echo $class['teacher']; ?></div><?php } ?><div style="clear: both;"></div>
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
				?><input type="button" value="+ Ajouter" onclick="javascript:registrationObj.addSelectedCourse('<?php echo $class['nrc']; ?>');" /><?php
			} ?></div><div style="clear: both;"></div>
			</div><div style="clear: both;"></div>
		</div>
		<?php
}
?><div style="clear: both;"></div><?php
} else { echo "<p>Ce cours n'est pas offert pour la session à venir.</p>"; }
?><div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
<style type="text/css">
.post-content .class-choice {
	width: 160px;
	background-color: #eee;
	padding: 10px;
	border: 1px solid silver;
	float: left;
	margin: 0px 15px 15px 0px;
}

.post-content .class-choice .type {
	padding-bottom: 5px; color: #333; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;font-size: 18px;font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 5px; margin-bottom: 5px;
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}

.post-content .class-choice .timetable, .post-content .class-choice .teacher {
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}


.post-content .class-choice .timetable {
	padding-top: 8px;
	padding-bottom: 8px;
}


.post-content .class-choice .nrc {
	margin-top: 8px;
	font-size: 10pt;
}
</style>
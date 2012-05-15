<h1 class="title">Cours offerts à l'Hiver 2012</h1>	
<div class="post-content">
<?php
foreach ($sections as $section) {
	if ($section['code'] != 'p-inter') {
		?><h2><?php echo $section['title']; ?></h2><?php
		if (isset($program_courses[$section['code']]) and $program_courses[$section['code']]!=array()) { ?>
				<table class="courses" cellspacing="0">
					<tbody>
						<tr>
							<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 10; else echo 60; ?>%;">Cours</th>
							<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 60%;">Titre</th><?php } ?>
							<th style="font-weight: bold; text-align: center;">Crédits</th>
						</tr>
						<?php
							foreach ($program_courses[$section['code']] as $prog_course) {
								$course = $courses[$prog_course['id']];
								if ($course['level']==3) {
								?>
							<tr>
								<?php if ($mobile!=1) { ?>
								<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
								<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
								<?php } else { ?>
								<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
								<?php } ?>
								<td style="text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
							</tr>
								<?php
								}
							}
						?>
					</tbody>
				</table><br class="space" />
			<?php
		}
		
		foreach ($section['children'] as $subsection) {
			?><h3><?php echo $subsection['title']; ?></h3><?php
				if (isset($program_courses[$subsection['code']]) and $program_courses[$subsection['code']]!=array() and $subsection['credits'] == '') {
					?><h4 style="margin-bottom: 5px; float: left;">Cours obligatoires</h4><?php
					if ($subsection['credits'] != '') {
						?><h4 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection['credits'])) echo $subsection['credits']; else echo str_replace("/", " à ", $subsection['credits']); ?> crédits</h4><?php
					}
					?><div style="clear: both;"></div><?php
				} elseif ($subsection['credits'] != '') {
					?><h4 style="margin-bottom: 5px; float: left;">Cours disponibles</h4><?php
					if ($subsection['credits'] != '') {
						?><h4 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection['credits'])) echo $subsection['credits']; else echo str_replace("/", " à ", $subsection['credits']); ?> crédits</h4><?php
					}
					?><div style="clear: both;"></div><?php
				}
				
				if (isset($program_courses[$subsection['code']]) and $program_courses[$subsection['code']]!=array()) { ?>
						<table class="courses" cellspacing="0">
							<tbody>
								<tr>
									<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 10; else echo 60; ?>%;">Cours</th>
									<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 60%;">Titre</th><?php } ?>
									<th style="font-weight: bold; text-align: center;">Crédits</th>
								</tr>
								<?php
									foreach ($program_courses[$subsection['code']] as $prog_course) {
										$course = $courses[$prog_course['id']];
										if ($course['level']==3) {
										?>
									<tr>
										<?php if ($mobile!=1) { ?>
										<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
										<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
										<?php } else { ?>
										<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
										<?php } ?>
										<td style="text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
									</tr>
										<?php
										}
									}
								?>
							</tbody>
						</table><br class="space" />
					<?php
				}
				
				foreach ($subsection['children'] as $subsection2) {
					if (isset($program_courses[$subsection2['code']]) and $program_courses[$subsection2['code']]!=array()) {
						?><h4 style="margin-bottom: 5px; float: left;"><?php echo $subsection2['title']; ?></h4><?php
						if ($subsection2['credits'] != '') {
							?><h4 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection2['credits'])) echo $subsection2['credits']; else echo str_replace("/", " à ", $subsection2['credits']); ?> crédits</h4><?php
						}
						?><div style="clear: both;"></div><?php
					}
						if (isset($program_courses[$subsection2['code']]) and $program_courses[$subsection2['code']]!=array()) { ?>
							<table class="courses" cellspacing="0">
								<tbody>
									<tr>
										<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 10; else echo 60; ?>%;">Cours</th>
										<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 60%;">Titre</th><?php } ?>
										<th style="font-weight: bold; text-align: center;">Crédits</th>
									</tr>
									<?php
										foreach ($program_courses[$subsection2['code']] as $prog_course) {
											$course = $courses[$prog_course['id']];
											if ($course['level']==3) {
											?>
										<tr>
											<?php if ($mobile!=1) { ?>
											<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
											<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
											<?php } else { ?>
											<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
											<?php } ?>
											<td style="text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
										</tr>
											<?php
											}
										}
									?>
								</tbody>
							</table><br class="space" />
						<?php
					}
				}
		}
		?><div style="page-break-after:always"></div><?php
	}
}
?>
</div>
<script language="javascript">
function setParam (name, value) {
	loading();
	
	!sendData('POST','./registration/s_setparam', 'name='+name+'&value='+encodeURIComponent(value));
}

var profile_concentration = '<?php echo $profile_concentration; ?>';

function resultSetParam (response, name) {
	if (response==1) {
		stopLoading();
		if (name=='b-lmo-profile-dtm-concentration') {
			if (profile_concentration=='') {
				$('#profile-concentration-first-choice').hide();
				$('#profile-concentration-selection').show();
				
				if ($('#profile-concentration').val()=='intervention') {
					$('#profile-concentration-select option[value="intervention"]').attr('selected', 'selected');
					profile_concentration = 'intervention';
				} else {
					$('#profile-concentration-select option[value="reflexion"]').attr('selected', 'selected');
					profile_concentration = 'reflexion';
				}
			}
			
			if (profile_concentration=='intervention') {
				$('#profile-concentration-panel-reflexion').hide();
				$('#profile-concentration-panel-intervention').fadeIn();
			} else {
				$('#profile-concentration-panel-intervention').hide();
				$('#profile-concentration-panel-reflexion').fadeIn();
			}
		}
	} else {
		errorMessage("Impossible d'afficher la concentration demandée.");
	}
}

var currentLink;

$('.post-content table tr:even').css('backgroundColor', '#dae6f1');

function selectSemester (semester) {
	window.document.location = '<?php echo site_url(); ?>schedule/timetable/'+semester;
}

function displayHelp (step) {
	$.fancybox.showActivity();
	
	!sendData('GET','./registration/w_help', step);
}

$(document).ready(function() {
	<?php if ($display_help==1) echo 'displayHelp(1);'; ?>
	
	/* Using custom settings */
	
	$("a#inline").fancybox({
		'hideOnContentClick': true,
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	500, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	});

	/* Apply fancybox to multiple items */
	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	});
	
});
</script>
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
}

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
	border-bottom: 2px solid gray;
}

.post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
}

.post-content table td {
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
	margin-bottom: 0px;
}
</style>
<style type="text/css" media="print">
body {
	margin: 0px;
	font-family: Helvetica, Arial;
	font-size: 10pt;
}

#page {
	width: 100%;
}

#header, #header-bottom, a.link, a.refresh, #footer, #sidebar, .content-tabs {
	display: none;
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
}

h1 {
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
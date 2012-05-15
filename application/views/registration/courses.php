<h2 class="title">Cours au programme<a id="edit-dashboard-link" href="./registration/configure" class="link" style="margin-right: 15px; font-size: 9pt;"><img src="<?php echo site_url(); ?>images/pencil.png" align="absmiddle" />&nbsp;&nbsp;Modifier les sections</a><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div style="float: left; font-weight: bold; padding-top: 7px;"><?php echo $user['program']; ?></div>
<div class="semester-selection" style="float: right; text-align: right;">Session : <strong><select name="semester" id="semester" onchange="javascript:registrationObj.selectSemester(this.options[this.selectedIndex].value);" style="font-weight: bold;">
	<?php
	if (isset($semesters)) {
		foreach ($semesters as $semester2 => $name) {
			if ($current_semester<=$semester2) {
			?><option value="<?php echo $semester2; ?>"<?php if ($semester==$semester2) echo ' selected="selected"'; ?>> <?php echo $name; ?></option><?php
			}
		}
	}
	?>
</select><?php /* switch (substr($semester, 4, 2)) {
			case '01':
				echo 'Hiver';
			break;
			case '09':
				echo 'Automne';
			break;
			default:
				echo 'Été';
			break;
		} echo " ".substr($semester, 0, 4);*/ ?></strong>&nbsp;&nbsp;|&nbsp;&nbsp;<select name="display-param" onchange="javascript:registrationObj.changeDisplay(this.options[this.selectedIndex].value);">
<option value="available"> Cours offerts</option>
<option value="all"> Tous les cours</option>
</select>

</div>
<div style="clear: both; height: 10px;"></div>
<?php
foreach ($sections as $section) {
	if (in_array($section['id'], $user_sections) || $section['compulsory'] == '1') {
		?><h3><?php echo $section['title'];
		if ($section['children'] != array()) {
			foreach ($section['children'] as $subsection) {
				if (in_array($subsection['id'], $user_sections)) {
					echo ' : '.$subsection['title'];
					break;
				}
			}
		}
		?></h3><?php
		if (isset($program_courses[$section['code']]) and $program_courses[$section['code']]!=array()) {
			if ($section['notes'] != '') {
				?><div class="notes"><?php echo str_replace("\n", "<br />", $section['notes']); ?></div><?php
			}
			?>
				<table class="courses">
					<tbody>
						<tr>
							<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Cours</th>
							<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 45%;">Titre</th><?php } ?>
							<th style="font-weight: bold; text-align: center;">Session</th>
							<th style="font-weight: bold; text-align: center;">Crédits</th>
							<th style="font-weight: bold; text-align: center;">Note</th>
						</tr>
						<?php
						for ($n=1; $n<5; $n++) {
							foreach ($program_courses[$section['code']] as $prog_course) {
								$course = $courses[$prog_course['id']];
								if ($course['level']==$n) {
								?>
							<tr class="<?php if ($course['av'.$semester]=='0') echo 'unavailable'; ?>">
								<?php if ($mobile!=1) { ?>
								<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
								<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
								<?php } else { ?>
								<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
								<?php } ?>
								<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if (isset($course['semester'])) {
									if (strlen($course['semester']) == 4) {
										echo $course['semester'];
									} else {
										switch (substr($course['semester'], 4, 2)) {
											case 'Automne';
												echo 'A-'.substr($course['semester'], 2, 2);
											break;
											case 'Hiver';
												echo 'H-'.substr($course['semester'], 2, 2);
											break;
											case 'Été';
												echo 'E-'.substr($course['semester'], 2, 2);
											break;
										}
									}
								}?></td>
								<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
								<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if (isset($course['note'])) echo $course['note']; ?></td>				
							</tr>
								<?php
								}
							}
						}
						?>
					</tbody>
				</table><br class="space" />
			<?php
		}
		
		foreach ($section['children'] as $subsection) {
			if (in_array($subsection['id'], $user_sections) || $subsection['compulsory'] == '1') {
				if (isset($program_courses[$subsection['code']]) and $program_courses[$subsection['code']]!=array() and $subsection['credits'] == '') {
					?><h4 style="margin-bottom: 5px; float: left;"><?php if ($subsection['compulsory'] == '1') echo $subsection['title']; else echo 'Cours obligatoires'; ?></h4><?php
					if ($subsection['credits'] != '') {
						?><h4 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection['credits'])) echo $subsection['credits']; else echo str_replace("/", " à ", $subsection['credits']); ?> crédits</h4><?php
					}
					?><div style="clear: both;"></div><?php
				} elseif ($subsection['credits'] != '') {
					?><h4 style="margin-bottom: 5px; float: left;"><?php if ($subsection['compulsory'] == '1') echo $subsection['title']; else echo 'Cours disponibles'; ?></h4><?php
					if ($subsection['credits'] != '') {
						?><h4 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection['credits'])) echo $subsection['credits']; else echo str_replace("/", " à ", $subsection['credits']); ?> crédits</h4><?php
					}
					?><div style="clear: both;"></div><?php
				}
				
				if ($subsection['notes'] != '') {
					?><div class="notes"><?php echo str_replace("\n", "<br />", $subsection['notes']); ?></div><?php
				}
				
				if (isset($program_courses[$subsection['code']]) and $program_courses[$subsection['code']]!=array()) { ?>
						<table class="courses">
							<tbody>
								<tr>
									<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Cours</th>
									<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 45%;">Titre</th><?php } ?>
									<th style="font-weight: bold; text-align: center;">Session</th>
									<th style="font-weight: bold; text-align: center;">Crédits</th>
									<th style="font-weight: bold; text-align: center;">Note</th>
								</tr>
								<?php
								for ($n=1; $n<5; $n++) {
									foreach ($program_courses[$subsection['code']] as $prog_course) {
										$course = $courses[$prog_course['id']];
										if ($course['level']==$n) {
										?>
									<tr class="<?php if ($course['av'.$semester]=='0') echo 'unavailable'; ?>">
										<?php if ($mobile!=1) { ?>
										<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
										<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
										<?php } else { ?>
										<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
										<?php } ?>
										<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if (isset($course['semester'])) {
											if (strlen($course['semester']) == 4) {
												echo $course['semester'];
											} else {
												switch (substr($course['semester'], 4, 2)) {
													case 'Automne';
														echo 'A-'.substr($course['semester'], 2, 2);
													break;
													case 'Hiver';
														echo 'H-'.substr($course['semester'], 2, 2);
													break;
													case 'Été';
														echo 'E-'.substr($course['semester'], 2, 2);
													break;
												}
											}
										} ?></td>
										<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
										<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if (isset($course['note'])) echo $course['note']; ?></td>				
									</tr>
										<?php
										}
									}
								}
								?>
							</tbody>
						</table><br class="space" />
					<?php
				}
				
				foreach ($subsection['children'] as $subsection2) {
						?><h4 style="margin-bottom: 5px; float: left;"><?php echo $subsection2['title']; ?></h4><?php
						if ($subsection2['credits'] != '') {
							?><h4 style="margin-bottom: 5px; float: right;"><?php if (is_int($subsection2['credits'])) echo $subsection2['credits']; else echo str_replace("/", " à ", $subsection2['credits']); ?> crédits</h4><?php
						}
						?><div style="clear: both;"></div><?php
						
						if ($subsection2['notes'] != '') {
							?><div class="notes"><?php echo str_replace("\n", "<br />", $subsection2['notes']); ?></div><?php
						}
						
						if ($program_courses[$subsection2['code']]!=array()) { ?>
							<table class="courses">
								<tbody>
									<tr>
										<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Cours</th>
										<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 45%;">Titre</th><?php } ?>
										<th style="font-weight: bold; text-align: center;">Session</th>
										<th style="font-weight: bold; text-align: center;">Crédits</th>
										<th style="font-weight: bold; text-align: center;">Note</th>
									</tr>
									<?php
									for ($n=1; $n<5; $n++) {
										foreach ($program_courses[$subsection2['code']] as $prog_course) {
											$course = $courses[$prog_course['id']];
											if ($course['level']==$n) {
											?>
										<tr class="<?php if ($course['av'.$semester]=='0') echo 'unavailable'; ?>">
											<?php if ($mobile!=1) { ?>
											<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
											<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
											<?php } else { ?>
											<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; <?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
											<?php } ?>
											<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if (isset($course['semester'])) {
											if (strlen($course['semester']) == 4) {
												echo $course['semester'];
											} else {
												switch (substr($course['semester'], 4, 2)) {
													case '09';
														echo 'A-'.substr($course['semester'], 2, 2);
													break;
													case '01';
														echo 'H-'.substr($course['semester'], 2, 2);
													break;
													case '05';
														echo 'E-'.substr($course['semester'], 2, 2);
													break;
												}
											}
										} ?></td>
											<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
											<td onclick="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" style="cursor: pointer; text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if (isset($course['note'])) echo $course['note']; ?></td>				
										</tr>
											<?php
											}
										}
									}
									?>
								</tbody>
							</table><br class="space" />
						<?php
					}
				}
			}
		}
	}
}
?>
<div style="display:none"><div id="data" class="course-info-box">
</div></div>
<style type="text/css">
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
}

.post-content table tr.unavailable {
	display: none;
}

.post-content table th, .post-content table td {
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

.post-content .notes {
	background-color: #eee;
	padding: 10px; font-size: 8pt;
	line-height: 10pt;
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
<div class="clear"></div></div>
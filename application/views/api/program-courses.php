<?php
if ($display_index == 1) {
	?><ol style="margin-bottom: 0px; padding-bottom: 0px;" id="pilule-sections-index"><?php
	foreach ($sections as $section) {
		if ($section['code'] != 'p-inter') {
				?><li><a href="#<?php echo $section['code']; ?>"><?php echo $section['title']; ?></a></li><?php
				
				if (isset($section['children']) and $section['children'] != array()) {
				?><ul type="square" style="padding-left: 15px; color: gray;"><?php
				foreach ($section['children'] as $subsection) {
					?><li><a href="#<?php echo $subsection['code']; ?>"><?php echo $subsection['title']; ?></a></li><?php
				}
				?></ul><?php
				}
		}
	}
	?></ol><?php
}
?>
<div id="pilule-courses-list">
<?php
foreach ($sections as $section) {
	if ($section['code'] != 'p-inter') {
		?><h2><?php echo $section['title']; ?><a name="<?php echo $section['code']; ?>">&nbsp;</a><?php if (isset($section['credits'])) { ?><div style="float: right;" class="section-credits"><?php if (is_int($section['credits'])) echo $section['credits']; else echo str_replace("/", " à ", $section['credits']); ?> crédits</div><div style="clear: both;"></div><?php } ?></h2><?php
		if (isset($program_courses[$section['code']]) and $program_courses[$section['code']]!=array()) {
			if ($section['notes'] != '') {
				?><div class="notes"><?php echo str_replace("\n", "<br />", $section['notes']); ?></div><?php
			}
			?>
				<table class="courses" cellspacing="0">
					<tbody>
						<tr>
							<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Code</th>
							<th style="font-weight: bold; text-align: left; width: 60%;">Titre</th>
							<th style="font-weight: bold; text-align: center;">A-2012</th>
							<th style="font-weight: bold; text-align: center;">Crédits</th>
						</tr>
						<?php
							foreach ($program_courses[$section['code']] as $prog_course) {
								$course = $courses[$prog_course['id']];
								?>
							<tr class="<?php if ($course['av'.$semester]=='0') echo 'unavailable'; ?>">
								<?php if ($mobile!=1) { ?>
								<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
								<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
								<?php } else { ?>
								<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
								<?php } ?>
								<td style="text-align: center; font-size: 7pt; text-transform: uppercase;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if ($course['av'.$semester]=='1') echo 'OUI'; else echo '&nbsp;'; ?></td>
								<td style="text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
							</tr>
								<?php
							}
						?>
					</tbody>
				</table><br class="space" />
			<?php
		}
		
		foreach ($section['children'] as $subsection) {
			?><h3><a name="<?php echo $subsection['code']; ?>"><?php echo $subsection['title']; ?></a></h3><?php
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
				
				if (isset($program_courses[$subsection['code']]) and $program_courses[$subsection['code']]!=array()) {
					if ($subsection['notes'] != '') {
						?><div class="notes"><?php echo str_replace("\n", "<br />", $subsection['notes']); ?></div><?php
					}
					?>
						<table class="courses" cellspacing="0">
							<tbody>
								<tr>
									<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Code</th>
									<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 60%;">Titre</th><?php } ?>
									<th style="font-weight: bold; text-align: center;">A-2012</th>
									<th style="font-weight: bold; text-align: center;">Crédits</th>
								</tr>
								<?php
									foreach ($program_courses[$subsection['code']] as $prog_course) {
										$course = $courses[$prog_course['id']];
										?>
									<tr class="<?php if ($course['av'.$semester]=='0') echo 'unavailable'; ?>">
										<?php if ($mobile!=1) { ?>
										<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
										<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
										<?php } else { ?>
										<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
										<?php } ?>
										<td style="text-align: center; font-size: 7pt; text-transform: uppercase;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if ($course['av'.$semester]=='1') echo 'OUI'; else echo '&nbsp;'; ?></td>
										<td style="text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
									</tr>
										<?php
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
						if (isset($program_courses[$subsection2['code']]) and $program_courses[$subsection2['code']]!=array()) {
							if ($subsection2['notes'] != '') {
								?><div class="notes"><?php echo str_replace("\n", "<br />", $subsection2['notes']); ?></div><?php
							}
							?>
							<table class="courses" cellspacing="0">
								<tbody>
									<tr>
										<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 15; else echo 60; ?>%;">Code</th>
										<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 60%;">Titre</th><?php } ?>
										<th style="font-weight: bold; text-align: center;">A-2012</th>
										<th style="font-weight: bold; text-align: center;">Crédits</th>
									</tr>
									<?php
										foreach ($program_courses[$subsection2['code']] as $prog_course) {
											$course = $courses[$prog_course['id']];
											?>
										<tr class="<?php if ($course['av'.$semester]=='0') echo 'unavailable'; ?>">
											<?php if ($mobile!=1) { ?>
											<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['code']; ?></td>
											<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['title']; ?></td>
											<?php } else { ?>
											<td style="<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>; font-size: 10pt;"><strong><?php echo $course['code']; ?></strong><br /><span style="font-size: 8pt;"><?php echo $course['title']; ?></span></td>
											<?php } ?>
											<td style="text-align: center; font-size: 7pt; text-transform: uppercase;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php if ($course['av'.$semester]=='1') echo 'OUI'; else echo '&nbsp;'; ?></td>
											<td style="text-align: center;<?php if ($course['note']!='') echo 'color: green;'; elseif ($course['semester']==$current_semester) echo 'color: #d05519;'; elseif ($course['av'.$semester]=='1') echo 'color: black;'; elseif ($course['av'.$semester]=='0') echo 'color: gray'; ?>"><?php echo $course['credits']; ?></td>
										</tr>
											<?php
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
<div style="font-size: 8pt; color: gray; font-family: Arial, Helvetica, sans-serif;">Les données ci-haut proviennent du système de gestion des études de l'Université Laval.</div>
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
</style>
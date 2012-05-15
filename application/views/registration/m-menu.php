<div id="sidebar">
	<div id="sidebar-bottom">
		<div id="sidebar-content">
			<h4 class="widgettitle">Inscription</h4>
			<div class="widget-content"><ul>
				<li class="leftEnd"><a href="./registration/" class="link-courses">Cours au programme</a></li>
				<li class="rightEnd"><a href="./registration/search" class="link-search">Recherche de cours</a></li>
				<li class="rightEnd"><a href="javascript:registrationObj.displayHelp(1);">Besoin d'aide ?</a></li>
			</ul></div>
		</div> <!-- end #sidebar-content -->
	</div> <!-- end #sidebar-bottom -->
	<div id="registered-courses" class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong><?php switch (substr($semester, 4, 2)) {
			case '01':
				echo 'H-';
			break;
			case '09':
				echo 'A-';
			break;
			default:
				echo 'E-';
			break;
		} echo substr($semester, 0, 4); ?> : Cours inscrits</strong></span>
		<br style="clear: both;" />
		<ul style="margin-top: 10px;" class="courses-list">
			<?php
			$credits = 0;
			if (is_array($registered_courses)) {
			foreach ($registered_courses as $course) { ?>
			<li>
				<a href="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" class="course"><span style="font-size: 8pt;"><?php if (strlen($course['title'])>35) echo substr($course['title'], 0, 30)."..."; else echo $course['title']; ?></span><br />
			<div class="title" style="font-weight: bold; margin-bottom: 0px; float: left;"><?php echo $course['code']; ?></div>
			<div style="float: right; margin-bottom: 0px; color: green;">NRC : <?php echo $course['nrc']; ?></div><div style="clear: both;"></div></a>
				<a href="javascript:registrationObj.removeRegisteredCourse('<?php echo $course['nrc']; ?>');" class="delete-link" title="Enlever le cours"><img src="./images/cross-gray.png" width="16" height="16" /></a>
				<div style="clear: both;"></div>
			</li>
			<?php
				$credits += $course['credits'];
			}
			}
			?>
		</ul>
		<div style="margin-top: 5px; border-top: 1px solid gray; padding-top: 10px; font-size: 10pt; margin-bottom: 10px; color: #000;">
		<div class="courses-total" style="font-weight: bold; float: left;"><?php if (is_array($registered_courses)) echo count($registered_courses); else echo 0; ?> cours</div>
		<div class="credits-total" style="float: right;"><?php echo $credits; ?> crédits</div>
		<div style="clear: both;"></div>
		</div>
	</div></div>
	<div id="selected-courses" class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Sélection de cours</strong></span>
		<br style="clear: both;" />
		<ul id="courses-selection" style="margin-top: 10px;" class="courses-list">
			<?php
			$credits = 0;
			if (is_array($selected_courses)) {
			foreach ($selected_courses as $course) { ?>
			<li>
				<a href="javascript:registrationObj.getCourseInfo(this, '<?php echo $course['code']; ?>');" class="course"><span style="font-size: 8pt;"><?php if (strlen($course['title'])>35) echo substr($course['title'], 0, 30)."..."; else echo $course['title']; ?></span><br />
			<div class="title" style="font-weight: bold; margin-bottom: 0px; float: left;"><?php echo $course['code']; ?></div>
			<div style="float: right; margin-bottom: 0px; color: green;">NRC : <?php echo $course['nrc']; ?></div><div style="clear: both;"></div></a>
				<a href="javascript:registrationObj.removeSelectedCourse('<?php echo $course['nrc']; ?>');" class="delete-link" title="Enlever le cours"><img src="./images/cross-gray.png" width="16" height="16" /></a>
				<div style="clear: both;"></div>
			</li>
			<?php
				$credits += $course['credits'];
			}
			}
			?>
		</ul>
		<div style="margin-top: 5px; border-top: 1px solid gray; padding-top: 10px; font-size: 10pt; margin-bottom: 0px; color: #000;">
		<div class="courses-total" style="font-weight: bold; float: left;"><?php if (is_array($selected_courses)) echo count($selected_courses); else echo 0; ?> cours</div>
		<div class="credits-total" style="float: right;"><?php echo $credits; ?> crédits</div>
		<div style="clear: both;"><?php if (date('Ymd')>=$deadlines[$semester]['registration_start'] and date('Ymd')<=$deadlines[$semester]['edit_selection']) { ?><a href="javascript:registrationObj.registerCourses();" class='icon-button signup-icon' style="margin-top: 15px; margin-left: 40px;"><span class='et-icon'><span>Inscription</span></span></a><div style="clear: both;"></div><?php } elseif (date('Ymd')>=$deadlines[$semester]['registration_start'] and date('Ymd')>=$deadlines[$semester]['edit_selection']) { ?><div style="margin-top: 35px; line-height: 12px; text-align: center; width: 180px; margin-left: auto; margin-right: auto; margin-bottom: 10px; color: gray; font-size: 8pt;">La période d'inscription <?php switch (substr($semester, 4, 2)) {
			case '01':
				echo 'H-';
			break;
			case '09':
				echo 'A-';
			break;
			default:
				echo 'E-';
			break;
		} echo substr($semester, 0, 4); ?><br />est terminée.</div><?php } elseif (date('Ymd')<=$deadlines[$semester]['registration_start']) { ?><div style="margin-top: 35px; line-height: 12px; text-align: center; width: 180px; margin-left: auto; margin-right: auto; margin-bottom: 10px; color: gray; font-size: 8pt;">La période d'inscription <?php switch (substr($semester, 4, 2)) {
			case '01':
				echo 'H-';
			break;
			case '09':
				echo 'A-';
			break;
			default:
				echo 'E-';
			break;
		} echo substr($semester, 0, 4); ?> commencera le <?php echo currentDate($deadlines[$semester]['registration_start'], "j F Y"); ?>.</div><?php } ?></div>
		</div>
	</div></div>
<style type="text/css">
#fancybox-content h3 {
	padding-bottom: 5px; color: #808080; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;
	font-size: 22px;
	font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 15px; margin-bottom: 5px;
	padding-bottom: 10px; border-bottom: 1px solid #e7e7e7; margin: 15px 0 20px 0;
			font-size: 24px;
}

#fancybox-content h3 a {
	color: #808080;
}

#fancybox-content h4 {
	padding-bottom: 5px; color: #808080; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;font-size: 18px;font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 15px; margin-bottom: 5px;
}

#fancybox-content .class-choice {
	width: 183px;
	background-color: #eee;
	padding: 10px;
	border: 1px solid silver;
	float: left;
	margin: 0px 15px 15px 0px;
}

#fancybox-content .class-choice .type {
	padding-bottom: 5px; color: #333; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;font-size: 18px;font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 5px; margin-bottom: 5px;
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}

#fancybox-content .class-choice .timetable, #fancybox-content .class-choice .teacher {
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}


#fancybox-content .class-choice .timetable {
	padding-top: 8px;
	padding-bottom: 8px;
}


#fancybox-content .class-choice .nrc {
	margin-top: 8px;
	font-size: 10pt;
}

.courses-list li {
	border-top: 1px dotted silver;
	margin-bottom: 5px;
	padding-top: 5px;
}

.courses-list li a.course {
	padding: 5px 8px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	display: block;
	width: 165px;
	float: left;
	color: #666;
}

.courses-list li a.delete-link {
	margin-top: 15px;
	cursor: pointer;
	display: block;
	width: 20px;
	float: right;
}

.courses-list li a.delete-link img {
	border: 0px;
}

.et-shadow .et-box-content {
	padding-left: 20px;
}

.et-box-content h5 {
	font-size: 10pt;
	margin-bottom: 5px;
	margin-top: 15px;
	color: #000;
	text-transform: uppercase;
	font-weight: bold;
}

.courses-list li a.course:hover {
	background-color: #dedede;
}
</style>
</div> <!-- end #sidebar -->
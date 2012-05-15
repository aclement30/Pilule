<h2 class="title">Actualisation des données des cours</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<form action="./admin/registration/s_updatecoursesdata" method="post" id="form-update" target="frame">
<div style="float: left; padding-top: 5px;">Semestre : <select name="semester" id="semester" onchange="javascript:selectSemester(this.options[this.selectedIndex].value);">
	<?php
	if (isset($semesters)) {
	foreach ($semesters as $semester => $name) {
		?><option value="<?php echo $semester; ?>"> <?php echo $name; ?></option><?php
	}
	}
	?>
</select></div>
</form>
<div style="float: right;"><a href="javascript:registrationObj.updateCourses();" class='icon-button signup-icon'><span class='et-icon'><span>Actualiser les données</span></span></a><div style="clear: both;"></div>
</div>
<div style="clear: both;"></div>
<div id="results" style="display: none;">
<h3>Résultats<div id="loading-bar" style="float: right; display: none; padding-top: 5px;"><img src="<?php echo site_url(); ?>images/loading-data.gif" /></div><div style="clear: both;"></div></h3>
<iframe name="frame" frameborder="0" onload="javascript:$('#loading-bar').fadeOut();" style="width: 575px; border: 1px solid #eee; height: 600px;"></iframe>
</div>
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

.post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
}

.line {
	padding: 5px;
	font-size: 10pt;
	vertical-align: top;
}

.line td {
	padding-bottom: 10px;
	padding-top: 5px;
}

.line td select {
	margin-top: 3px;
}

.line .error-message {
	color: #ba1007;
	font-weight: normal;
	font-size: 9pt;
	padding: 5px;
	padding-left: 35px;
	background-image: url(../images/error.png);
	background-repeat: no-repeat;
	background-position: 10px 4px;
	display: none;
	padding-top: 10px;
}

.line .field_title {
	font-size: 10pt;
	padding-bottom: 0px;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	
}

table .line .field_title {
	text-align: right;
	vertical-align: top;
	padding-top: 10px;
	padding-right: 15px;
}

table .line .field_title .description {
	color: gray;
	font-weight: normal;
	font-size: 8pt;
}

.line input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
	padding: 2px;
}

.line textarea {
	border: 1px solid gray;
	padding: 2px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
}

.section.compulsory, .section.top {
	font-weight: bold;
}

.section label {
	cursor: pointer;
}

.section {
	padding-left: 20px;
	padding-bottom: 5px;
}

.section.children {
	padding-left: 40px;
}

.section.subchildren {
	padding-left: 80px;
}

.post-content td.courses {
	font-size: 9pt;
	text-align: right;
}

h3 a.link {
	top: 5px;
	position: relative;
}

h3 a.link:hover {
	color: black;
}
</style>
<div class="clear"></div></div>
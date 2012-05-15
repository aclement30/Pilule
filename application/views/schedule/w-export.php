<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Exporter l'horaire</title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/course-info.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
</head>

<body style="background-color: #fff;">
<h1>Exporter l'horaire</h1>

<form action="./schedule/s_export" method="post" target="report-frame" id="form-export">
<table width="100%">
	<tbody>
		<tr class="line">
			<td class="field_title">Titre de l'événement</td>
			<td><select name="title" id="title">
					<option value="name"> Titre du cours</option>
					<option value="code"> Code du cours</option>
			</select></td>
		</tr>
		<tr class="line">
			<td class="field_title" style="width: 150px;">Alarme</td>
			<td style=""><select name="alarm" id="alarm">
					<option value="no"> Aucune alarme</option>
					<option value="1h"> 1 heure avant</option>
					<option value="30m"> 30 minutes avant</option>
					<option value="15m"> 15 minutes avant</option>
			</select></td>
		</tr>
		<tr class="line" style="display: none;">
			<td class="field_title">Format</td>
			<td><select name="format" id="format">
					<option value="ical" selected="selected"> iCal</option>
					<option value="outlook"> Outlook</option>
					<option value="gcal"> Google Calendar</option>
			</select></td>
		</tr>
		<tr>
			<td style="padding-left: 110px; padding-top: 10px;" colspan="2"><a href="javascript:submitForm();" class='icon-button export-schedule-icon'><span class='et-icon'><span>Télécharger</span></span></a><div style="float: left; padding-left: 10px; padding-top: 15px;"><img src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" id="loading-img" alt="Chargement" /></div><div style="clear: both;"></div></td>
	</tbody>
</table>
<input type="hidden" name="semester" value="<?php echo $semester_date; ?>" />
</form>
<script language="javascript">
function submitForm () {
	$('#loading-img').show();
	
	// Envoi du formulaire
	document.getElementById('form-export').submit();
	
	setTimeout("exportCallback()", 500);
}

function exportCallback () {
	$('#loading-img').hide();
	
	top.$.modal.close();
}
</script>
<style type="text/css">
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
</style>
</body></html>
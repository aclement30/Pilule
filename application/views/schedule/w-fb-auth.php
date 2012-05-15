<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Partager l'horaire</title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/course-info.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/dashboard.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/schedule.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/fees.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/settings.js"></script>
</head>

<body style="background-color: #fff;">
<h1>Partager l'horaire</h1>

<p>Pour partager votre horaire, veuillez vous authentifier avec votre compte Facebook et accepter les permissions demandées par l'application.</p>

<a href="javascript:$('#loading-img').fadeIn();document.location='<?php echo site_url() . "cfacebook/auth/u/".base64_encode(site_url()."schedule/s_authFB"); ?>';" class='icon-button facebook-icon' style="margin-left: 100px; margin-top: 30px;"><span class='et-icon'><span>Connexion via Facebook</span></span></a><div style="float: left; padding-left: 10px; padding-top: 35px;"><img src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" id="loading-img" alt="Chargement" /></div><div style="clear: both;"></div>
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
<script language="javascript">
$(document).ready(function(){
    <?php if (isset ($error)) {
		?>top.errorMessage('<?php echo addslashes($error); ?>');<?php
	} ?>
}); 
</script>
</body></html>
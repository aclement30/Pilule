<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Signaler un bug</title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/course-info.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
</head>

<body>
<h1>Signaler un bug</h1>
<p>Veuillez décrire le problème de fonctionnement ci-dessous :</p>
<form action="<?php echo site_url(); ?>support/s_reportbug" method="post" target="report-frame" id="form-report" enctype="multipart/form-data">
<table width="100%">
	<tbody>
		<tr class="line">
			<td class="field_title" style="width: 170px;">IDUL</td>
			<td style="width: 280px;"><input type="text" name="idul" id="idul" size="10" style="text-transform: lowercase;" value="<?php if (isset($user)) echo $user['idul']; ?>" /></td>
		</tr>
		<tr class="line">
			<td class="field_title" rowspan="2">E-mail</td>
			<td><input type="text" name="email" id="report-email" size="30" /><br /><span style="font-size: 8pt; color: gray; font-weight: normal; line-height: 10pt;">Votre adresse e-mail est confidentielle. Nous communiquerons avec vous seulement si nous avons besoin de plus de détails sur la nature du problème.</span></td>
		</tr>
		<tr class="line">
			<td id="error-message-report-email" class="error-message">Veuillez indiquer votre adresse e-mail.</td>
		</tr>
		<tr class="line">
			<td class="field_title" rowspan="2">Adresse de la page</td>
			<td><input type="text" name="url" id="report-url" size="50" /></td>
		</tr>
		<tr class="line">
			<td id="error-message-report-url" class="error-message">Veuillez indiquer l'adresse URL de la page.</td>
		</tr>
		<tr class="line">
			<td class="field_title">Catégorie</td>
			<td style="padding-top: 5px;"><select name="type" id="type">
				<option value="Problème d'affichage"> Problème d'affichage</option>
				<option value="Bug de fonctionnement"> Bug de fonctionnement</option>
				<option value="Message d'erreur"> Message d'erreur</option>
				<option value="Connexion impossible"> Connexion impossible</option>
				<option value="Commentaire/suggestion"> Commentaire/suggestion</option>
			</select></td>
		</tr>
		<tr class="line">
			<td class="field_title">Description du problème</td>
			<td><textarea name="description" id="description" style="width: 250px; height: 80px;"></textarea></td>
		</tr>
		<tr class="line">
			<td class="field_title">Copie-écran<br /><span style="font-size: 8pt; color: gray; font-weight: normal;">Facultatif</span></td>
			<td style="padding-top: 8px;"><input type="file" name="printscreen" id="printscreen" /></td>
		</tr>
		<tr>
			<td style="padding-left: 200px; padding-top: 10px;" colspan="2"><a href="javascript:sendBugReport();" class='icon-button signup-icon'><span class='et-icon'><span>Envoyer</span></span></a><div style="float: left; padding-left: 10px; padding-top: 15px;"><img src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" id="loading-img" alt="Chargement" /></div><div style="clear: both;"></div></td>
		</tr>
	</tbody>
</table>
<script language="javascript">
$('#report-url').val(top.document.location);
</script>
</form>
<style type="text/css">
#form-report .line {
	padding: 5px;
	font-size: 10pt;
	vertical-align: top;
}

#form-report .line td {
	padding-top: 5px;
	padding-bottom: 10px;
}

#form-report .line td select {
	margin-top: 3px;
}

#form-report .line .error-message {
	color: #ba1007;
	font-weight: normal;
	font-size: 9pt;
	padding: 5px;
	padding-left: 35px;
	background-image: url(../images/error.png);
	background-repeat: no-repeat;
	background-position: 10px 0px;
	display: none;
	padding-bottom: 10px;
	padding-top: 0px;
}

#form-report .line .field_title {
	font-size: 10pt;
	padding-bottom: 0px;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	
}

#form-report table .line .field_title {
	text-align: right;
	vertical-align: top;
	padding-top: 10px;
	padding-right: 15px;
}

#form-report table .line .field_title .description {
	color: gray;
	font-weight: normal;
	font-size: 8pt;
}

#form-report .line input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
	padding: 2px;
}

#form-report .line textarea {
	border: 1px solid gray;
	padding: 2px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
}
</style>
</body></html>
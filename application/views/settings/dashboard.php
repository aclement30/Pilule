<h2 class="title">Préférences</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3>Stockage des données</h3>
<p>Pour accélérer le chargement de Pilule, le système garde une copie de certaines données de votre dossier scolaire sur le serveur de l'Université Laval. Ces données sont automatiquement actualisées lorsqu'elles ont été enregistrées depuis un délai supérieur au délai indiqué ci-dessous.</p>
<form action="./settings/s_configure" method="post" id="form-configure-expiration-delay" target="frame">
<blockquote><span style="font-style:normal;">Délai d'expiration des données : </span><select name="delay">
<option value="<?php echo 3600*24; ?>"<?php if ($param['expiration-delay'] == 3600*24) echo ' selected="selected"'; ?>> 24 heures</option>
<option value="<?php echo 3600*12; ?>"<?php if ($param['expiration-delay'] == 3600*12) echo ' selected="selected"'; ?>> 12 heures</option>
<option value="<?php echo 3600*6; ?>"<?php if ($param['expiration-delay'] == 3600*6 || $param['expiration-delay'] == '') echo ' selected="selected"'; ?>> 6 heures</option>
<option value="<?php echo 3600*5; ?>"<?php if ($param['expiration-delay'] == 3600*5) echo ' selected="selected"'; ?>> 5 heures</option>
<option value="<?php echo 3600*2; ?>"<?php if ($param['expiration-delay'] == 3600*2) echo ' selected="selected"'; ?>> 2 heures</option>
<option value="<?php echo 60*60; ?>"<?php if ($param['expiration-delay'] == 3600) echo ' selected="selected"'; ?>> 1 heure</option>
<option value="<?php echo 60*30; ?>"<?php if ($param['expiration-delay'] == 60*30) echo ' selected="selected"'; ?>> 30 min</option>
<option value="<?php echo 60*15; ?>"<?php if ($param['expiration-delay'] == 60*15) echo ' selected="selected"'; ?>> 15 min</option>
</select></blockquote>
<input type="hidden" name="param" value="expiration-delay" />
</form>
<a href="javascript:settingsObj.submitForm('expiration-delay');" class='icon-button signup-icon' style="margin-left: 230px;"><span class='et-icon'><span>Enregistrer</span></span></a><div style="clear: both;"></div>
<?php
if ($autologon == 'yes') { ?>
<h3>Connexion automatique</h3>
<p>Pilule permet la connexion automatique en utilisant Facebook Connect ou Google Accounts. Lorsque vous serez connecté à un de ces deux services avec un compte autorisé, vous pourrez accéder directement à Pilule sans avoir besoin d'entrer votre IDUL et votre NIP. L'authentification aura déjà eu lieu lors de votre connexion à votre compte Facebook ou Google.</p>
<p><strong>Ce service est encore en phase expérimentale.</strong></p>
<form action="./settings/s_configure" method="post" id="form-configure-autologon" target="frame">
<blockquote><input type="checkbox" id="autologon" name="autologon" value="yes"<?php if ($autologon == 'yes') echo ' checked="checked"'; ?> />&nbsp;<label for="autologon" style="font-style: normal; color: black;">Activer la connexion automatique</label></blockquote>
<input type="hidden" name="param" value="autologon" />
<a href="javascript:settingsObj.submitForm('autologon');" class='icon-button signup-icon' style="margin-left: 230px;"><span class='et-icon'><span>Enregistrer</span></span></a><div style="clear: both;"></div>
<div id="autologon-accounts" style="display: <?php if ($autologon == 'yes') echo 'block'; else echo 'none'; ?>; margin-bottom: 20px;">
<h4>Comptes autorisés</h4>
<div style="float: left; width: 130px;"><img src="<?php echo site_url(); ?>images/facebook-logo.jpg" alt="Facebook" height="40" /></div>
<div id="fb-account">
<?php if (isset($fbuid) and $fbuid) { ?>
<div style="float: left; margin-top: 10px; margin-right: 20px;"><a href="http://www.facebook.com/profile.php?id=<?php echo $fbuid; ?>" target="_blank"><?php echo $fbname; ?></a></div>
<div style="float: right;"><a href="javascript:settingsObj.s_unlinkAccount('facebook');" class='icon-button block-icon' style="margin-top: 0px;"><span class='et-icon'><span>Supprimer</span></span></a><div style="clear: both;"></div></div>
<?php } else { ?>
<div style="float: left; margin-top: 10px; margin-right: 20px;">Aucun compte autorisé</div>
<div style="float: right;"><a href="javascript:document.location='<?php echo site_url()."cfacebook/auth/u/".base64_encode(site_url()."settings/s_linkaccount/account/facebook"); ?>';" class='icon-button add-icon' style="margin-top: 0px;"><span class='et-icon'><span>Ajouter</span></span></a><div style="clear: both;"></div></div>
<?php } ?>
<div style="clear: both;"></div>
</div>
</div>
<?php
}
if ($user['idul'] != 'demo') { ?>
<h3>Suppression des données</h3>
<p>Vous avez la possibilité de supprimer toutes vos données enregistrées sur le serveur de Pilule. Cela peut être utile si vous avez des problèmes d'utilisation de Pilule ou si les données stockées sont corrompues.</p>
<p><strong>Attention : vous serez automatiquement déconnecté après la suppression de vos données.</strong></p>
<a href="javascript:settingsObj.eraseData();" class='icon-button block-icon'><span class='et-icon'><span>Supprimer les données</span></span></a><div style="clear: both;"></div>
<?php } ?>
<iframe name="frame" style="width: 0px; height: 0px;" frameborder="0"></iframe>
<div class="clear"></div>
</div>
<style type="text/css">
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
	padding-left: 60px;
}

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
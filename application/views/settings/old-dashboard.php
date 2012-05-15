<h2 class="title">Préférences</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3>Stockage des données</h3>
<p>Pour accélérer le chargement de Pilule, vous pouvez choisir de sauvegarder vos données de manière sécurisée et permanente sur le serveur de Pilule. Lorsque vous vous connecterez à Pilule, le chargement sera immédiat et les données seront ensuite actualisées pendant que vous naviguez sur le site.<br />Cela remplace le chargement initial des données depuis Capsule qui se fait à chaque connexion.</p>
<form action="./settings/s_configure" method="post" id="form-configure-data-storage" target="frame">
<blockquote><input type="checkbox" id="data-storage" name="data-storage" value="yes"<?php if ($data_storage == 'yes') echo ' checked="checked"'; ?> />&nbsp;<label for="data-storage" style="font-style: normal; color: black;">Activer le stockage des données</label></blockquote>
<input type="hidden" name="param" value="data-storage" />
</form>
<a href="javascript:submitForm('data-storage');" class='icon-button signup-icon' style="margin-left: 230px;"><span class='et-icon'><span>Enregistrer</span></span></a><div style="clear: both;"></div>
<h3>Connexion automatique</h3>
<p>Pilule permet la connexion automatique en utilisant Facebook Connect ou Google Accounts. Lorsque vous serez connecté à un de ces deux services avec un compte autorisé, vous pourrez accéder directement à Pilule sans avoir besoin d'entrer votre IDUL et votre NIP. L'authentification aura déjà eu lieu lors de votre connexion à votre compte Facebook ou Google.</p>
<p><strong>Ce service est encore en phase expérimentale.<br />Le stockage des données est obligatoire pour pouvoir utiliser la connexion automatique.</strong></p>
<form action="./settings/s_configure" method="post" id="form-configure-autologon" target="frame">
<blockquote><input type="checkbox" id="autologon" name="autologon" value="yes"<?php if ($autologon == 'yes') echo ' checked="checked"'; ?> />&nbsp;<label for="autologon" style="font-style: normal; color: black;">Activer la connexion automatique</label></blockquote>
<input type="hidden" name="param" value="autologon" />
<a href="javascript:submitForm('autologon');" class='icon-button signup-icon' style="margin-left: 230px;"><span class='et-icon'><span>Enregistrer</span></span></a><div style="clear: both;"></div>
<div id="autologon-accounts" style="display: <?php if ($autologon == 'yes') echo 'block'; else echo 'none'; ?>">
<h4>Comptes autorisés</h4>
<div style="float: left; width: 130px;"><img src="<?php echo site_url(); ?>images/facebook-logo.jpg" alt="Facebook" height="40" /></div>
<div id="fb-account">
<?php if (isset($fbuid) and $fbuid) { ?>
<div style="float: left; margin-top: 10px; margin-right: 20px;"><a href="http://www.facebook.com/profile.php?id=<?php echo $fbuid; ?>" target="_blank"><?php echo $fbname; ?></a></div>
<div style="float: right;"><a href="javascript:s_unlinkAccount('facebook');" class='icon-button block-icon' style="margin-top: 0px;"><span class='et-icon'><span>Supprimer</span></span></a><div style="clear: both;"></div></div>
<?php } else { ?>
<div style="float: left; margin-top: 10px; margin-right: 20px;">Aucun compte autorisé</div>
<div style="float: right;"><a href="<?php echo site_url()."cfacebook/auth/u/".base64_encode(site_url()."settings/s_linkaccount/account/facebook"); ?>" class='icon-button add-icon' style="margin-top: 0px;"><span class='et-icon'><span>Ajouter</span></span></a><div style="clear: both;"></div></div>
<?php } ?>
<div style="clear: both;"></div>
</div>
</div>
</form>
<iframe name="frame" style="width: 0px; height: 0px;" frameborder="0"></iframe>
</div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
<script language="javascript">
function submitForm (form) {
	loading("Enregistrement en cours...");
	
	$('#form-configure-'+form).submit();
}

function statusConfigure (param, response, errMessage) {
	if (response == 1) {
		if (param == 'autologon' && $('#autologon').is(':checked')) {
			$('#data-storage').attr('checked','checked');
			
			$('#autologon-accounts').show();
		} else if (param == 'autologon' && (!$('#autologon').is(':checked'))) {
			$('#autologon-accounts').hide();
		}
		
		resultMessage('Les paramètres ont été enregistrés.');
		
		//setTimeout("document.location='./registration/courses'", 2000);
	} else {
		errorMessage(errMessage);
	}
}

function s_unlinkAccount (account) {
	loading();
	
	!sendData('GET','./settings/s_unlinkaccount', 'account/'+account);
}
$(document).ready(function() {
<?php if (isset($result_message)) { ?>
 resultMessage("<?php echo $result_message; ?>");
<?php }
if (isset($error_message)) { ?>
 errorMessage("<?php echo $error_message; ?>");
<?php } ?>
});
</script>
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
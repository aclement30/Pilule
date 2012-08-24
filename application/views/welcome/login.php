<h2 class="title">Connexion</h2>
<div class="clear"></div>
<div class="page-separator"></div>
<div id="ulaval-ribbon" style="position: absolute; left: 498px; top: 1px;"><a href="http://www.ulaval.ca" target="_blank"><img src="<?php echo site_url(); ?>images/approbation-ulaval.png" alt="Approuvé par l'Université Laval" style="border: 0px;" /></a></div>
<div class="post-content">
	<div class="error-message"></div>
	<div id="form-login">
	<table style="width: 100%;" id="login-table" border="0">
		<tbody>
			<tr>
				<th><label for="idul">IDUL</label></th>
				<td style="padding-right: 10px;"><input type="text" name="idul" id="idul" class="input" style="width: 140px;" autocomplete="off" /></td>
				<th><label for="password">NIP</label></th>
				<td><input type="password" name="password" id="password" class="input" style="width: 140px;" /></td>
				<td colspan="2" style="padding-top: 5px; padding-left: 15px; text-align: right; padding-bottom: 0px;">
				<div style="float: left; margin-top: 15px; margin-right: 8px; width: 16px;"><img src="<?php echo site_url(); ?>images/loading.gif" class="login-btn-img default" height="16" width="16" style="position: relative; top: 3px; display: none;" alt="Sécurité" align="absmiddle" /></div><a href="javascript:login();" class='icon-button login-icon' name="wp-submit" id="wp-submit" style=""><span class='et-icon'><span>Connexion</span></span></a><div style="clear: both;"></div>
				<!--
<img src="<?php echo site_url(); ?>images/lock.png" class="login-btn-img" height="16" width="16" style="position: relative; top: 3px;" alt="Sécurité" align="absmiddle" />&nbsp;&nbsp;<input type="button" onclick="javascript:login();" class="button-primary" name="wp-submit" id="wp-submit" value="&raquo;&nbsp;Connexion" /><img src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" alt="Chargement" />--></td>
			</tr>
			<tr>
				<td colspan="3"><span id="ask-load-data-choice" style="display: none;"><input type="checkbox" name="data-loading" id="data-loading" value="no" />&nbsp;<label for="data-loading" style="cursor: pointer;">Sauter le chargement des données.</label></span></td>
				<td colspan="3" style="font-size: 8pt; color: silver; vertical-align: top; padding-top: 0px;">Votre NIP ne sera pas enregistré dans le système.</td>
			</tr>
			<tr style="display: none;" id="fbauth-row">
				<td colspan="6" style="font-size: 8pt; color: silver; vertical-align: top;"><div style="padding-top: 15px; border-top: 1px dotted silver;"><a href="javascript:authFB();" class='icon-button facebook-icon' style="margin-left: 0px; margin-top: 0px; margin-bottom: 0px; float: right;"><span class='et-icon'><span>Connexion via Facebook</span></span></a><div style="float: right; margin-top: 5px; margin-right: 8px; width: 16px;"><img src="<?php echo site_url(); ?>images/loading.gif" class="login-btn-img fb" height="16" width="16" style="position: relative; top: 3px; display: none;" alt="Sécurité" align="absmiddle" /></div><div style="clear: both;"></div></div></td>
			</tr>
		</tbody>
	</table>
	</div>
	<div id="form-security-check" style="display: none;">
	<table style="width: 100%;" id="login-table">
		<tbody>
			<tr>
				<th style="width: 175px;"><label for="password">Mot de passe</label></th>
				<td style="width: 180px;"><input type="password" name="password" id="security-password" class="input" style="width: 150px;" autocomplete="off" /></td>
				<td style="text-align: left; padding-top: 5px; padding-left: 15px; padding-bottom: 0px;">
<img src="<?php echo site_url(); ?>images/lock.png" class="login-btn-img" height="16" width="16" style="position: relative; top: 3px;" alt="Sécurité" align="absmiddle" />&nbsp;&nbsp;<input type="button" onclick="javascript:checkSecurity();" class="button-primary" name="wp-submit" id="wp-submit" value="&raquo;&nbsp;Continuer" /><img src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" alt="Chargement" /></td>
			</tr>
		</tbody>
	</table>
	</div>
	<div id="loading-panel" style="padding-top: 10px; display: none;">
	<div style="float: left; font-size: 11pt;" class="current-step-description">
	&nbsp;
	</div>
	<div class="loading-img" style="float: right; padding-top: 2px;"><img src="<?php echo site_url(); ?>images/loading-data.gif" height="15" width="128" alt="Chargement" />
	</div><div style="clear: both;"></div>
	</div>
<style type="text/css">
.post-content .error-message {
	background-color: #a52c0f;
	padding: 8px 5px;
	-moz-border-radius: 5px;
	text-align: center;
	color: #fff;
	margin-bottom: 10px;
	display: none;
}

#login-table th {
	width: 30px;
	font-weight: bold;
	padding-top: 8px;
	padding-right: 10px;
	text-align: right;
}

#login-table td {
	padding-top: 10px;
}
</style>
<script language="javascript">
var loginMode = 'default';
var FBauthTest = 0;
var FBAuthTimeout;

$(document).ready(function(){
    if (Modernizr.localstorage && isMobile != 1) {
		if (localStorage.getItem('pilule-fbauth') != null) {
			if (localStorage.getItem('pilule-fbauth') == 1) {
				//$('#fbauth-row').show();
			}
		}
	}
});

function authFB (auto) {
	loginMode = 'facebook';
	
	if (auto == 1) {
		FBauthTest = 1;
		FBAuthTimeout = setTimeout("errorLogin();", 8000);
	}
	
	$('.login-btn-img.fb').fadeIn();
	$('.error-message').hide();
	
	$('#report-frame').attr('src', '<?php echo site_url()."cfacebook/auth/u/".base64_encode(site_url()."welcome/s_authFB"); ?>');
}

function loginDemo() {
	$('#idul').val('demo');
	$('#password').val('demo');
	
	setTimeout("login()", 500);
}

$('#password').keypress(function(e) {
        if(e.which == 13) {
            login();
        }
    });

$('#security-password').keypress(function(e) {
        if(e.which == 13) {
            checkSecurity();
        }
    });

$('#idul').focus();
var currentStep = 1;
var currentTimeout;
var ajaxTimeout;
var stepsDescription = new Array("Connexion au serveur", "Vérification des identifiants", "Programme d'études", "Rapport de cheminement", "Relevé de notes", "Horaire de cours", "Liste des cours", "Frais de scolarité", "Vérification des blocages", "Traitement des données");
var redirect_url = '<?php if (isset($redirect_url)) echo $redirect_url; ?>';

function login () {
	$('.login-btn-img.default').fadeIn();
	$('.error-message').hide();
	
	var idul = $('#idul').val();
	var password = $('#password').val();
	
	if (idul == '' && password == '') {
		authFB(1);
	} else {
		if ($('#data-loading').is(':checked')) {
			var loadData = 'no';
		} else {
			var loadData = 'yes';
		}
		
		!sendData('POST','./welcome/s_login', 'idul='+idul+'&password='+password+'&loaddata='+loadData);
	}
}

function acceptTerms () {
	$('.login-btn-img').fadeIn();
	$('.error-message').hide();

	if ($('#terms-response').attr('checked')!=true) {
		//$('#login-btn-img-2').attr('src', './images/lock.png');
		$('.error-message').html("Vous devez accepter les Conditions d'utilisation pour continuer...");
		$('.error-message').fadeIn();
	} else {
		!sendData('GET','./welcome/s_acceptterms', '');
	}
}

function askSecurityPassword() {
	$('#terms-panel').hide();
	$('#form-login').hide();
	$('.error-message').hide();
	$('#form-security-check').fadeIn();
	$('.login-btn-img').attr('src', './images/lock.png');
	$('h2.title').html('Vérification de sécurité');
	
	$('#security-password').focus();
}

function checkSecurity () {
	$('.login-btn-img').attr('src', './images/loading.gif');
	$('.error-message').hide();
	
	var password = $('#security-password').val();
	
	!sendData('POST','./welcome/s_checksecurity', 'password='+password);
}

function successLogin() {
	$('#ulaval-ribbon').fadeOut();
	$('#form-login').hide();
	$('#form-security-check').hide();
	$('#storage-panel').hide();
	$('#loading-panel').fadeIn();
	$('#loading-message').fadeIn();
	$('.login-btn-img').hide();
	
	$('h2.title').html('Chargement des données<div style="float: right; font-size: 15pt; padding-top: 2px; margin-right: 15px; font-family: Arial, Helvetica, sans-serif;"><span class="current-step" style="font-weight: bold;">1</span> / 10</div><div class="clear"></div>');
	$('h2 .current-step').html('1');
	$('.current-step-description').html("Connexion au serveur");
	
	currentStep++;
	ajaxTimeout = setTimeout('requestTimeout(1)', 30000);
	currentTimeout = setTimeout('nextLoadingStep()', '<?php if (isset($usebots)) echo 1800; else echo 1000; ?>');
	
	if (FBAuthTimeout != 'undefined') clearTimeout(FBAuthTimeout);
	
	!sendData('POST','./welcome/s_getuserdata', '');
}

function nextLoadingStep () {
	clearTimeout(currentTimeout);
	
	if (currentStep<11) {
		$('h2 .current-step').html(currentStep);
		$('.current-step-description').html(stepsDescription[(currentStep-1)]);
		
		currentStep++;
		currentTimeout = setTimeout('nextLoadingStep()', '<?php if (isset($usebots)) echo 1800; else echo 1000; ?>');
	}
}

function resultLoading (status) {
	clearTimeout(ajaxTimeout);
	clearTimeout(currentTimeout);
	
	if (status==1) {
		$('h2.title').html('Redirection...');
		if (redirect_url != '') {
			$('.current-step-description').html('Ouverture de la page');
		} else {
			$('.current-step-description').html('Ouverture du tableau de bord');
		}
		//$('#loading-panel .loading-img').hide();
		
		//$('#loading-panel').fadeOut();
		
		if (redirect_url != '') {
			$(window.location).attr('href', '<?php echo site_url(); ?>'+redirect_url);
		} else {
			$(window.location).attr('href', '<?php echo site_url(); ?>');
		}
		//window.document.location = './welcome/';
	} else if (status == 2) {
		$('h2.title').html('Redirection...');
		$('.current-step-description').html('Ouverture du tableau de bord');
		//$('#loading-panel .loading-img').hide();
		
		//$('#loading-panel').fadeOut();
		
		$(window.location).attr('href', '<?php echo site_url(); ?>');
		//window.document.location = './welcome/';
	}
}

function errorLogin(message) {
	$('.login-btn-img').hide();
	if (FBauthTest == 1) {
		clearTimeout(FBAuthTimeout);
		$('.error-message').html('Erreur de connexion ! Veuillez vérifier les identifiants de connexion...');
		$('.error-message').fadeIn();
	} else {
		$('.error-message').html(message);
		$('.error-message').fadeIn();
	}
	$('#ulaval-ribbon').fadeIn();
	currentStep = 1;
}

function askLoadData() {
	$('#ask-load-data-choice').show();
}

var timeoutCheckData = 0;

function requestTimeout (type) {
	clearTimeout(ajaxTimeout);
	clearTimeout(currentTimeout);
	
	if (timeoutCheckData == 0) {
		timeoutCheckData = 1;
		
		$('h2.title').html('Chargement des données');
		$('.current-step-description').html("Vérification des données");
		
		!sendData('GET','./welcome/s_checkdata', '');
	} else {
		timeoutCheckData = 0;
		
		$('h2.title').html('Connexion');
		if (type==1) {
			$('.error-message').html("Une erreur est survenue durant le chargement des données (err: timeout).");
		} else {
			$('.error-message').html("Une erreur est survenue durant le chargement des données...");
		}
		
		$('.error-message').fadeIn();
		$('#loading-panel').hide();
		$('#loading-message').hide();
		$('#form-login').fadeIn();
		$('#ulaval-ribbon').fadeIn();
		currentStep = 1;
	}
}
</script>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div id="loading-message" style="font-size: 8pt; display: none; text-align: center; color: #777;">Merci de patienter, le chargement des données peut prendre jusqu'à une minute...</div>
	<div style="text-align: right; display: none; float: right; margin-top: 0px; margin-right: 50px; padding-top: 0px; color: gray; font-size: 7pt; text-transform: uppercase; line-height: 25pt;">Projet hébergé par :<br /><img src="<?php echo site_url(); ?>images/ulaval.png" align="absmiddle" height="40" alt="Université Laval" /></div>
	<div style="text-align: center; margin-top: 10px; line-height: 16pt; color: silver;"><a href="<?php echo site_url(); ?>support/faq/">Est-il sécuritaire d'utiliser Pilule ?</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:loginDemo();"<?php if ($display_tooltip) echo ' id="demo-link" title="Première visite ?"'; else echo ' title="Voir une version démo du site"'; ?>>Tester Pilule</a></div>
<div class="clear"></div>
</div> <!-- end #main-area -->
<?php if ($mobile_browser!=1 and $display_tooltip==1) { ?>
<script language="javascript">
	$(document).ready(function() {
	$(function(){
		$("#demo-link").tipTip({maxWidth: "auto", activation: "focus", edgeOffset: 5, defaultPosition: "top"});
		$("#demo-link").focus();
		setTimeout('$("#demo-link").blur()', 2500);
	});
	});
</script>
<?php } ?>

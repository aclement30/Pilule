<h2 class="title" style="display: block;">Connexion</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content form">
	<div class="error-message"></div>
	<div id="form-login">
	<div class="form-elements" id="login-table">
		<div class="field first">
			<div class="field-title">
				IDUL
			</div>
			<input type="text" name="idul" id="idul" class="input blur" autocorrect="off" value="Identifiant" onfocus="javascript:fieldFocus(this, 'Identifiant');" onblur="javascript:fieldBlur(this, 'Identifiant');" style="width: 215px; text-transform: lowercase;" />
			<div style="clear: both;"></div>
		</div>
		<div class="field last">
			<div class="field-title">
				NIP
			</div>
			<input type="password" name="password" id="password" class="input blur" value="Code d'accès" onfocus="javascript:fieldFocus(this, 'Code d\'accès');" onblur="javascript:fieldBlur(this, 'Code d\'accès');" style="width: 215px;" />
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div style="margin-top: 5px; float: right;">
		<a href='javascript:login();' name="wp-submit" id="wp-submit" class='small-button smallsilver'><span>Connexion</span></a><div class="clear"></div>
	</div>
	<div style="margin-top: 16px; margin-left: 5px; float: left;">
		<img src="<?php echo site_url(); ?>images/mobile/loading.gif" id="loading-img" style="display: none;" height="24" width="24" alt="Chargement" />
	</div>
	<div style="margin-top: 22px; margin-left: 10px; float: left; font-size: 10pt; color: #fff; text-shadow: 1px 1px 1px #555; display: none;" id="auto-logon">
		Connexion automatique...
	</div>
	<div style="clear: both;"></div>
	</div>
	<div id="loading-panel" style="display: none; background: none; margin-top: 100px; text-align:center; padding: 10px; color: #fff; font-size: 10pt;">
		<img src="<?php echo site_url(); ?>images/mobile/bg-loading.gif" />
		<div style="margin-top: 20px; text-shadow: 1px 1px 1px #555;">Chargement</div>
	</div>
	<div id="appModeNote"><em>Application Web</em><span>Utilisez la fonction "Ajouter à l'écran d'accueil".</span></div>
<style type="text/css">
#appModeNote {
background-color: #333333 ;
border-top: 1px solid #000000 ;
bottom: 0px ;
color: #F0F0F0 ;
display: none;
font-family: helvetica ;
left: 0px ;
padding: 10px 0px 10px 0px ;
position: fixed ;
text-align: center ;
width: 100% ;
}
 
#appModeNote em {
display: block ;
font-size: 20px ;
font-weight: bold ;
line-height: 26px ;
font-style: normal;
margin-bottom: 5px;
}
 
#appModeNote span {
display: block ;
font-size: 14px ;
line-height: 20px ;
}

.post-content .error-message {
	background-color: #a52c0f;
	padding: 8px 5px;
	-moz-border-radius: 5px;
	text-align: center;
	color: #fff;
	margin-bottom: 10px;
	display: none;
}

.post-content.form {
	background: none;
	height: 100%;
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

body {
	background-image: url(./images/mobile/dashboard-bg.jpg);
	background-repeat: no-repeat;
	background-color: #686868;
}

#header {
	display: none;
}
</style>
<script language="javascript">
$(document).ready(function() {
	var appModeNote = $("#appModeNote");
	 
	// Get a reference to the body.
	var body = $( document.body );
	 
	if (("standalone" in window.navigator) && !window.navigator.standalone) {
		appModeNote.show();
		$('#header').show();
		
		setTimeout("$('#appModeNote').fadeOut()", 2000);
	} 
});

function fieldFocus (field, text) {
	if ($(field).val()==text) {
		$(field).toggleClass('blur');
		$(field).val('');
	}
}

function fieldBlur (field, text) {
	if ($(field).val()=='') {
		$(field).toggleClass('blur');
		$(field).val(text);
	}
}

$('#password').keypress(function(e) {
        if(e.which == 13) {
            login();
        }
    });

var currentStep = 1;
var currentTimeout;
var ajaxTimeout;
var stepsDescription = new Array("Connexion au serveur", "Vérification des identifiants", "Programme d'études", "Rapport de cheminement", "Relevé de notes", "Horaire de cours", "Liste des cours", "Frais de scolarité", "Vérification des blocages", "Traitement des données");

//$('#idul').focus();
var autoLogon = 0;

$(document).ready(function() {
	if (Modernizr.localstorage) {
			if (localStorage.getItem('pilule-autologon-idul') != null) {
				$('#idul').val(localStorage.getItem('pilule-autologon-idul'));
				$('#password').val(localStorage.getItem('pilule-autologon-password'));
				
				$('#wp-submit').hide();
				$('#auto-logon').show();
				
				login(1);
			}
	}
});

function login (askAutoLogon) {
	$('.error-message').hide();
	
	var idul = $('#idul').val();
	var password = $('#password').val();
	
	if (idul == 'Identifiant' || password == 'Code d\'accès') {
		alert('Vous devez entrer votre IDUL et votre NIP pour vous connecter.');
		
		if (idul == 'Identifiant') {
			$('#idul').focus();
			$('#idul').click();
		} else {
			$('#password').focus();
			$('#password').click();
		}
	} else {
		$('#wp-submit').hide();
		$('html,body').animate({scrollTop: 0},'fast');
		$('#loading-img').fadeIn();
		
		if (Modernizr.localstorage && askAutoLogon != 1) {
			if (localStorage.getItem('pilule-ask-autologon-'+idul) == null) {
				if (confirm("Voulez-vous que Pilule vous connecte automatiquement lors de votre prochaine visite depuis cet appareil ?")) {
					autoLogon = 1;
				} else {
					localStorage.setItem('pilule-ask-autologon-'+idul, 'no');
					autoLogon = 0;
				}
			}
		}
		
		!sendData('POST','./welcome/s_login', 'idul='+idul+'&password='+password);
	}
}

function successLogin() {
	$('#form-login').slideUp();
	$('h2.title').html('Ouverture...');
	$('#header').slideDown();
	$('#loading-panel').fadeIn();
	/*
	$('h2.title').html('Chargement des données<div style="float: right; font-size: 10pt; font-family: Arial, Helvetica, sans-serif;"><span class="current-step" style="font-weight: bold;">1</span><span style="font-weight: normal;"> / 10</span></div><div style="clear: both;"></div>');
	$('h2 .current-step').html('1');
	$('.current-step-description').html("Connexion au serveur");
	*/
	if (autoLogon == 1) {
		if (Modernizr.localstorage) {
			var idul = $('#idul').val();
			var password = $('#password').val();
			
			localStorage.setItem('pilule-autologon-idul', idul);
			localStorage.setItem('pilule-autologon-password', password);
		}
	}
	
	/*
	currentStep++;
	ajaxTimeout = setTimeout('requestTimeout()', 60000);
	currentTimeout = setTimeout('nextLoadingStep()', '<?php if (isset($usebots)) echo 1800; else echo 1000; ?>');
	*/
	!sendData('POST','./welcome/s_getuserdata', '');
	
	
	//setTimeout("$(window.location).attr('href', '<?php echo site_url(); ?>')", 500);
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
		
		$(window.location).attr('href', '<?php echo site_url(); ?>welcome/');
	} else if (status == 2) {
		$('h2.title').html('Redirection...');
		
		$(window.location).attr('href', '<?php echo site_url(); ?>welcome/');
	}
}

function errorLogin(message) {
	$('#loading-img').hide();
	alert(message);
	$('#auto-logon').hide();
	$('#wp-submit').fadeIn();
	$('.error-message').html(message);
	currentStep = 1;
}

var timeoutCheckData = 0;

function askLoadData() {
}

function requestTimeout(type) {
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
		//$('.error-message').fadeIn();
		$('#loading-panel').hide();
		$('#loading-message').hide();
		$('#form-login').fadeIn();
		alert("Une erreur est survenue durant le chargement des données...");
	}
}
</script>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div id="loading-message" style="font-size: 7pt; margin-top: 10px; display: none; text-align: center; color: #777;">Merci de patienter, le chargement des données<br />peut prendre jusqu'à une minute...</div>
<div class="clear"></div>
</div> <!-- end #main-area -->
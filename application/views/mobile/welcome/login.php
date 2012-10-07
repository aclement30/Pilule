<!DOCTYPE html>
<html>
	<base href="<?php echo site_url(); ?>" />
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité." />
	
	<meta property="og:image" content="<?php echo site_url(); ?>images/thumbnail.jpg"/>
	<meta property="og:title" content="Pilule - Gestion des études"/>
	<meta property="og:description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité."/>
	<meta property="og:url" content="<?php echo site_url(); ?>"/>
	<meta property="og:type" content="website"/>
	<meta property="og:site_name" content="Pilule - Gestion des études"/>
	<meta property="fb:app_id" content="102086416558659"/>
	
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0;" />
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-startup-image" href="<?php echo site_url(); ?>images/startup.png">
	
    <title>Pilule - Gestion des études</title>

    <!-- Our CSS stylesheet file -->
    <link href="<?php echo site_url(); ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url(); ?>css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/mobile.css" />
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/login-mobile.css" />

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-345357-28']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>
</head>

<div id="formContainer">
    <div id="login-form">
        <h1><img src="<?php echo site_url(); ?>img/logo.png" alt="Pilule - Gestion des études - Université Laval" /></h1>
        <div class="alert-error alert"></div>
        <form method="post" target="frame">
            <div class="control-group">
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="idul" id="idul" class="input-xlarge" autocorrect="off" spellcheck="false" placeholder="IDUL" /></div>
            </div>
            <div style="margin: 35px 0;" class="control-group">
                <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input type="password" name="password" id="password" class="input-xlarge" placeholder="Mot de passe" /></div>
            </div>
            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                <button type="button" id="btn-login" class="btn btn-success btn-large" onclick="javascript:app.users.login();"><i class="icon-chevron-right icon-white"></i>&nbsp;Connexion</button>
            </div>
            <div style="clear: both;"></div>
            <input type="hidden" name="redirect_url" id="redirect_url" value="<?php if (isset($url)) echo $url; ?>" />
        </form>
    </div>
    <div id="loading-panel">
            <div style="margin-top: 50%; text-align: center; color: #fff; font-weight: bold;"><span class="loading-message">Connexion en cours</span><br /><br />
            <img src="./img/loading-login.gif" /></div>
            <div class="waiting-notice" style="text-align: center; opacity: 0.5; -moz-opacity: 0.5; display: none; margin-top: 80px; color: #fff; font-size: 8pt;">Cette étape peut prendre jusqu'à une minute.<br />Merci de patienter.</div>
    </div>
</div>
	
	
<!-- JavaScript includes -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type='text/javascript' src="<?php echo site_url(); ?>js/bootstrap.min.js"></script>
<script src="<?php echo site_url(); ?>js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="<?php echo site_url(); ?>js/modernizr.custom.41742.js"></script>
<script src="<?php echo site_url(); ?>js/pilule.js?v=2.0.1"></script>
<script src="<?php echo site_url(); ?>js/users.js?v=2.0.1"></script>
<script src="<?php echo site_url(); ?>js/cache.js?v=2.0.1"></script>
<script src="<?php echo site_url(); ?>js/login.js?v=2.0.1"></script>
<script src="<?php echo site_url(); ?>js/path.min.js"></script>
<script src="<?php echo site_url(); ?>js/main.js?v=2.0.1"></script>
<script src="<?php echo site_url(); ?>js/ajax.js?v=2.0.1"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Mettre le focus sur le champ IDUL
        setTimeout("$('#idul').focus()", 500);

        // Quand la touche Enter est pressée dans le champ Password, valider le formulaire
        $('#password').keypress(function(e){
            if (e.which == 13) {
                app.users.login();
            }
        });
        
        if (Modernizr.localstorage) {
            if (localStorage.getItem('pilule-autologon-idul') != null) {
                $('#idul').val(localStorage.getItem('pilule-autologon-idul'));
                $('#password').val(localStorage.getItem('pilule-autologon-password'));
                
                app.users.login(1);
            }
        }
    });
</script>
</body>
</html>

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
</script>
	
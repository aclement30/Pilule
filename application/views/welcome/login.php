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
    <title>Pilule - Gestion des études</title>

    <!-- Our CSS stylesheet file -->
    <link href="<?php echo site_url(); ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url(); ?>css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/login.css" />

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

<body>

<div id="formContainer">
    <div id="login-form">
        <div id="ulaval-ribbon" style="position: relative; right: -45px; text-align: right; top: -10px; margin-bottom: -93px;"><img src="<?php echo site_url(); ?>img/approbation-ulaval.png" alt="Approuvé par l'Université Laval" style="border: 0px;" /></div>
        <h1><img src="<?php echo site_url(); ?>img/logo.png" alt="Pilule - Gestion des études - Université Laval" /></h1>
        <div class="alert-error alert"></div>
        <form method="post" target="frame">
            <div class="control-group">
                <label for="idul">IDUL</label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="idul" id="idul" autocorrect="off" spellcheck="false" /></div>
            </div>
            <div style="margin: 15px 0;" class="control-group">
                <label for="password">Mot de passe</label>
                <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input type="password" name="password" id="password"  /></div>
                <div style="font-size: 7pt; color: #4a99e6;" class="help-block">Votre NIP ne sera pas enregistré dans le système.</div>
            </div>
            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                <button type="button" id="btn-login" class="btn btn-success" onclick="javascript:app.users.login();"><i class="icon-chevron-right icon-white"></i>&nbsp;Connexion</button>
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
    <div id="loading-error">
        <div class="alert-error alert" style="display: block; margin-top: 10px;">Une erreur est survenue durant le chargement de vos données depuis Capsule. Vous pouvez :
        <ol style="margin-top: 5px;">
        	<li>Continuer sans charger les données.</li>
        	<li>Réessayer de vous connecter.</li>
        </ol>
        Note : certaines fonctions peuvent ne pas être disponibles si les données ne sont pas chargées.</div>
            <div style="margin-top: 15%; text-align: center;">
            	<div class="btn-group" style="text-align: center; margin-bottom: 20px;">
	            	<a class="btn btn-danger" href="javascript:app.users.redirectToDashboard();" style="float: none;"><i class="icon-warning-sign icon-white"></i>&nbsp;Continuer sans chargement</a>
            	</div><div class="btn-group" style="margin-left: 0px;">
	            	<a class="btn btn-success" href="javascript:app.users.retryLogin();" style="float: none;"><i class="icon-repeat icon-white"></i>&nbsp;Réessayer la connexion</a>
            	</div>
            </div>
    </div>
</div>

<footer>
    <p class="inside">
    <div class="navbar">
        <div class="navbar-inner">
            <ul class="nav" style="margin-top: 20px;">
                <li><a href="<?php echo site_url(); ?>support/terms">Conditions d'utilisation</a></li>
                <li><a href="<?php echo site_url(); ?>support/privacy">Confidentialité des données</a></li>
                <li><a href="<?php echo site_url(); ?>support/faq">F.A.Q.</a></li>
                <li><a href="<?php echo site_url(); ?>support/contact">Contact</a></li>
            </ul>
            <div class="nav" style="float: right; padding-top: 10px; margin-right: 20px;">
                <p style="float: right; margin-left: 40px; font-style: italic; font-size: 8pt; text-align: right;">Projet hébergé par<br /><img src="<?php echo site_url(); ?>img/ulaval-black.png" height="36" align="absbottom" style="padding-top: 6px; padding-bottom: 5px;" /></p>
                <p id="copyright" style="text-align: right; float: right;">Conception<br /><a href="http://www.alexandreclement.com" target="_blank" style="font-style: normal; font-size: 9pt;">Alexandre Clément</a></p>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
    </p>
</footer>

<!-- JavaScript includes -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="<?php echo site_url(); ?>js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="<?php echo site_url(); ?>js/pilule.js?ver=2.0.2"></script>
<script src="<?php echo site_url(); ?>js/users.js?ver=2.0.2"></script>
<script src="<?php echo site_url(); ?>js/cache.js?ver=2.0.2"></script>
<script src="<?php echo site_url(); ?>js/login.js?ver=2.0.2"></script>
<script src="<?php echo site_url(); ?>js/path.min.js"></script>
<script src="<?php echo site_url(); ?>js/main.js?ver=2.0.2"></script>
<script src="<?php echo site_url(); ?>js/ajax.js?ver=2.0.2"></script>
<script src="<?php echo site_url(); ?>js/modernizr.custom.41742.js"></script>
<script type='text/javascript' src="<?php echo site_url(); ?>js/bootstrap.min.js"></script>
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

        // Positionner la boîte de connexion verticalement
        $('#formContainer').css('marginTop', ($(window).height()/2)-200);

        $(window).resize(function() {
            $('#formContainer').css('marginTop', ($(window).height()/2)-200);
        });
    });
</script>
</body>
</html>


<!DOCTYPE html>
<html>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité." />
<meta property="og:image" content="<?php echo site_url(); ?>images/thumbnail.jpg"/>
<meta property="og:title" content="Pilule - Gestion des études"/>
<meta property="og:description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité."/>
<meta property="og:url" content="http://www.pilule.ulaval.ca"/>
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
</head>

<body>

<div id="formContainer">
    <form id="login-form" method="post" target="frame">
        <div id="ulaval-ribbon" style="position: relative; right: -45px; text-align: right; top: -41px; margin-bottom: -93px;"><img src="<?php echo site_url(); ?>img/approbation-ulaval.png" alt="Approuvé par l'Université Laval" style="border: 0px;" /></div>
        <h1><a href="#" id="flipToRecover" class="flipLink"><img src="<?php echo site_url(); ?>img/logo.png" alt="Pilule - Gestion des études - Université Laval" /></a></h1>
        <div class="alert-error alert"></div>
        <div class="control-group">
            <label for="username">IDUL</label>
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
    <form id="loading" method="post" action="./">
        <div style="margin-top:  7%; text-align: center; color: #fff; font-weight: bold;">Connexion en cours<br /><br />
        <img src="./img/loading-login.gif" /></div>
    </form>
</div>

<footer>
    <p class="inside">&copy; Pilule 2012</p>
</footer>

<!-- JavaScript includes -->
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="<?php echo site_url(); ?>js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="<?php echo site_url(); ?>js/users.js"></script>
<script src="<?php echo site_url(); ?>js/main.js"></script>
<script src="<?php echo site_url(); ?>js/ajax.js"></script>
<script type='text/javascript' src="<?php echo site_url(); ?>js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#idul').focus();

        $('#password').keypress(function(e){
            if (e.which == 13) {
                app.users.login();
            }
        });
    });
</script>
</body>
</html>


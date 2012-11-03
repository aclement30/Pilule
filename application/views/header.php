<!DOCTYPE html>
<html lang='en'>
<meta charset='utf-8'>
<head>
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
    
    <?php if ($mobile_browser) { ?>
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0;" />
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
	<link rel="apple-touch-startup-image" href="<?php echo site_url(); ?>images/startup.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/icons/apple-touch-icon-144x144-precomposed.png" />    
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/icons/apple-touch-icon-114x114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/icons/apple-touch-icon-72x72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="/img/icons/apple-touch-icon-precomposed.png" />
    <link rel="shortcut icon" href="/img/icons/apple-touch-icon-precomposed.png" />
    
	<?php } ?>
	
    <title>Pilule - Gestion des études</title>

    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="./css/fullcalendar.css" />
    <?php if ($mobile_browser) { ?>
    <link rel="stylesheet" href="./css/mobile.css?v=2.0.1" />
    <?php } else { ?>
    <link rel="stylesheet" href="./css/unicorn.main.css" />
    <link rel="stylesheet" href="./css/unicorn.grey.css" class="skin-color" />
    <link rel="stylesheet" href="./css/fullcalendar.print.css" media="print" />
    <link rel="stylesheet" href="./css/pilule.css?v=2.0.1" />
    <?php } ?>
    <link rel="stylesheet" href="./css/print.css" media="print" />
    
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-345357-28']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

      <?php if (isset($_GET['debug']) and $_GET['debug'] == 1) echo 'var debug = 1;'; else echo 'var debug=0;'; ?>
    </script>
</head>

<body>
<!--[if IE 6]>
<div style="background-color: red; width:100%; border-bottom: 2px solid black;">
<div style="padding: 20px; color: #fff; font-size: 11pt;"><strong>Votre navigateur (Internet Explorer 6) n'est pas supporté par Pilule.</strong> Veuillez mettre à jour votre navigateur ou utiliser un autre navigateur compatible (Firefox 3.5+, Safari 3+, Chrome, etc).</div>
</div>
<![endif]-->

<?php if ( $mobile_browser ) { ?>

<!-- Entête navigateur mobile -->

<div id="header">
	<div class="home"><a href="<?php echo site_url(); ?>#!/dashboard"><img src="<?php echo site_url(); ?>img/mobile/retina/home.png" alt="Tableau de bord"></a></div>
	<div class="logout"><a href="<?php echo site_url(); ?>logout"><img src="<?php echo site_url(); ?>img/mobile/retina/logout.png" alt="Déconnexion"></a></div>
</div>

<!-- Fin de l'entête mobile -->

<?php } else { ?>

<div id="header">
    <h1><a href="<?php echo site_url(); ?>#!/dashboard">Pilule</a></h1>
</div>

<?php
if (!empty($user)) { ?>
<div id="user-nav" class="navbar">
    <ul class="nav btn-group">
        <li class="user-name"><?php echo $user['name']; ?></li>
        <li class="link-settings"><a title="" href="#!/settings"><i class="icon icon-cog"></i> <span class="text">Préférences</span></a></li>
        <li><a title="" href="./logout"><i class="icon icon-off"></i> <span class="text">Déconnexion</span></a></li>
    </ul>
    <ul class="nav btn-group external-frame">
        <li><a title="Revenir au site de Pilule" href="javascript:app.closeExternalFrame();"><i class="icon icon-arrow-left"></i> <span class="text">Revenir à Pilule</span></a></li>
    </ul>
</div>

<div id="sidebar">
    <ul class="nav" style="border-bottom: 0px; margin-bottom: 0px;">
        <li class="active link-dashboard"><a href="#!/dashboard"><i class="icon icon-home"></i> <span>Tableau de bord</span></a></li>
        <li class="submenu link-studies">
            <a href="#!/studies"><i class="icon icon-folder-open"></i> <span>Dossier scolaire</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
            <ul class="nav">
                <li class="link-studies-summary"><a href="#!/studies">Programme d'études</a></li>
                <li class="link-studies-details"><a href="#!/studies/details">Rapport de cheminement</a></li>
                <li class="link-studies-report"><a href="#!/studies/report">Relevé de notes</a></li>
            </ul>
        </li>
        <li class="link-schedule"><a href="#!/schedule"><i class="icon icon-calendar"></i> <span>Horaire</span></a></li>
        <li class="submenu link-fees link-tuitions"><a href="#!/fees"><i class="icon icon-list"></i> <span>Frais de scolarité</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
            <ul class="nav">
                <li><a href="#!/fees">Sommaire du compte</a></li>
                <li><a href="#!/fees/details">Relevé par session</a></li>
            </ul>
        </li>
        <?php if (isset($user) and $user['admin']) { ?>
        <li class="link-admin"><a href="#!/admin"><i class="icon icon-briefcase"></i> <span>Administration</span></a></li>
        <?php } ?>
        <li style="height: 10px;">&nbsp;</li>
        <li id="notifications"><div class="alert alert-warning">
            <h4>Frais de scolarité</h4>
            <strong>Session Sept-Déc. 2012</strong><br />
            <div style="margin-bottom: 5px;">La facture sera expédiée vers le 9 octobre. Date limite de paiement : 26 octobre 2012.</div>
            <strong>Session Oct-Janv. 2012</strong><br />
            La facture sera expédiée vers le 19 octobre. Date limite de paiement : 12 novembre 2012.
        </div></li>
    </ul>
</div>

<?php } else { ?>

<div id="sidebar">
    <a href="#!/dashboard" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul class="nav">
        <li class="link-dashboard"><a href="/login"><i class="icon icon-home"></i> <span>Tableau de bord</span></a></li>
        <li class="submenu active open link-support">
            <a href="/support/terms"><i class="icon icon-folder-open"></i> <span>Support</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
            <ul class="nav">
                <li class="link-support-terms"><a href="/support/terms">Conditions d'utilisation</a></li>
                <li class="link-support-privacy"><a href="/support/privacy">Confidentialité des données</a></li>
                <li class="link-support-faq"><a href="/support/faq">F.A.Q.</a></li>
                <li class="link-support-contact"><a href="/support/contact">Contact</a></li>
            </ul>
        </li>
    </ul>
</div>

<?php } ?>
<?php } ?>
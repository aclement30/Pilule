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
    <title>Pilule - Gestion des études</title>

    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="./css/fullcalendar.css" />
    <link rel="stylesheet" href="./css/unicorn.main.css" />
    <link rel="stylesheet" href="./css/unicorn.grey.css" class="skin-color" />
    <link rel="stylesheet" href="./css/fullcalendar.print.css" media="print" />
    <link rel="stylesheet" href="./css/pilule.css" />
    <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!--[if lt IE 7]>
	<script type="text/javascript" src="<?php echo site_url(); ?>js/DD_belatedPNG_0.0.8a-min.js"></script>
    <!-- TODO : Corriger la liste des PNG -->
	<script type="text/javascript">
        $(document).ready(function() {
           // DD_belatedPNG.fix('img#logo, p#slogan, #menu, #top-overlay, #featured-overlay, span.overlay, .entry-content p.meta-info, #controllers span#right-arrow, #controllers span#left-arrow, #footer-content, #footer');
        });
    </script>
<![endif]-->
<?php if ($_SERVER["HTTP_HOST"]!='localhost') { ?>
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
<?php } ?>
</head>

<body>
<!--[if IE 6]>
<div style="background-color: red; width:100%; border-bottom: 2px solid black;">
<div style="padding: 20px; color: #fff; font-size: 11pt;"><strong>Votre navigateur (Internet Explorer 6) n'est pas supporté par Pilule.</strong> Veuillez mettre à jour votre navigateur ou utiliser un autre navigateur compatible (Firefox 3.5+, Safari 3+, Chrome, etc).</div>
</div>
<![endif]-->

<div id="header">
    <h1><a href="<?php echo site_url(); ?>#!/dashboard">Pilule</a></h1>
</div>

<div id="user-nav" class="navbar">
    <ul class="nav btn-group">
        <li class="user-name"><?php echo $user['name']; ?></li>
        <li class="link-settings"><a title="" href="#!/settings"><i class="icon icon-cog"></i> <span class="text">Préférences</span></a></li>
        <li><a title="" href="./logout"><i class="icon icon-off"></i> <span class="text">Déconnexion</span></a></li>
    </ul>
</div>

<div id="sidebar">
    <a href="#!/dashboard" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
        <ul class="nav">
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
    </ul>

</div>

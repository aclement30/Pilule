<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité." />
<meta property="og:image" content="https://www.pilule.ulaval.ca/images/thumbnail.jpg"/> 
<meta property="og:title" content="Pilule - Gestion des études"/> 
<meta property="og:description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité."/> 
<meta property="og:url" content="http://www.pilule.ulaval.ca"/> 
<meta property="og:type" content="website"/> 
<meta property="og:site_name" content="Pilule - Gestion des études"/> 
<meta property="fb:app_id" content="102086416558659"/>
<title>Pilule - Gestion des études</title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/tipTip.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/dashboard.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/ie6style.css" />
	<script type="text/javascript" src="<?php echo site_url(); ?>js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img#logo, p#slogan, #menu, #top-overlay, #featured-overlay, span.overlay, .entry-content p.meta-info, #controllers span#right-arrow, #controllers span#left-arrow, #footer-content, #footer');</script>
<![endif]-->
<!--[if gte IE 7]>
<link href="<?php echo site_url(); ?>css/dashboard-ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/ie7style.css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/ie8style.css" />
<![endif]-->
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery.easing-1.3.pack.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/modernizr.custom.40351.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery.tipTip.minified.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery.simplemodal.1.4.1.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery.shorten.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/dashboard.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/schedule.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/fees.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/registration.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/settings.js"></script>
<?php if (isset($section) && $section == 'admin') { ?>
<script language="javascript" src="<?php echo site_url(); ?>js/highcharts.js"></script>
<?php } ?>
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
		<div id="header-inner">
			<div class="container">
				<a href="./dashboard/" style="display: block; float: left; width: 320px;">
					<img src="./images/logo-3.png" id="logo" height="59" alt="Pilule - Facilite la gestion de vos études" />
				</a>
				<img src="./images/header-picture.png" style="float: left; display: block; margin-left: 20px; position: relative; top: -50px; margin-bottom: -60px;" width="480" height="157" alt="Campus de l'Université Laval" />	
				<div style="float: right; position: relative; top: -45px; margin-right: 15px;"><img src="./images/error.png" align="absmiddle" style="position: relative; top: 4px;" />&nbsp;<a href="javascript:reportBug();" style="color: #fff; font-size: 8pt;">Signaler un bug</a></div>	
				<div style="clear: both;"></div>
				<div style="position: absolute; top: 75px; left: 865px;"><img src="./images/beta-sticker.png" width="70" height="70" /></div>
			</div> <!-- end .container -->
		</div> <!-- end #header-inner -->
	</div> <!-- end #header -->
</div>
	<?php if (((isset($section) and $section!='login') or (!isset($section))) and isset($user)) { ?>
	<div id="header-bottom">
		<div class="container">
				<ul id="secondary-menu" class="nav clearfix">
					<li class="tab-welcome"><a href="./dashboard/">Tableau de bord</a></li>
					<?php if ($_SESSION['cap_datacheck'] == 1) { ?>
					<li class="tab-studies"><a href="./studies/">Dossier scolaire</a></li>
					<li class="tab-schedule"><a href="./schedule/">Horaire</a></li>
					<li class="tab-fees"><a href="./fees/">Frais de scolarité</a></li>
					<li class="tab-settings"><a href="./settings/">Préférences</a></li>
					<?php
					} ?>
					<?php if ($_SESSION['cap_iduser'] == 'alcle8' and (isset($section) and $section=='admin')) { ?><li class="tab-admin"><a href="./admin/dashboard/">Admin</a></li><?php } ?>
				</ul> <!-- end ul#nav -->
				<div id="search-bar" class="nav clearfix">
					<a style="margin-top: 0px; float: right;" href="./welcome/s_logout" class='icon-button logout-icon'><span class='et-icon'><span>Déconnexion</span></span></a><div style="float: right; margin-right: 15px; color: #4289CF; margin-top: 7px; font-size: 11pt;"><?php if (isset($user)) echo $user['name']; ?></div><div class="clear"></div>
				
				</div>
		</div> <!-- end .container -->
	</div> <!-- end #header-bottom -->
	<?php } ?>
	<div id="content" >
		<div id="right-shadow">
			<div id="top-shadow"></div>
			<div class="container clearfix">
			<div id="main-area"<?php if (isset($section) and $section=='login') { echo ' style="margin-left: 140px;"'; } ?>>
			
			<div class="entry page">
				<div class="entry-top"> 
					<div class="entry-content">
					
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; user-scalable=0; minimum-scale=1.0; maximum-scale=1.0" />
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-startup-image" href="<?php echo site_url(); ?>images/startup.png">
<title>Pilule - Gestion des études</title>
<link href="<?php echo site_url(); ?>css/m-style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/modernizr.custom.40351.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/dashboard.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/schedule.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/fees.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/settings.js"></script>
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
<div id="loading-message"><div style="float: left;"><img src="<?php echo site_url(); ?>images/mobile/loading.gif" id="loading-img" height="16" width="16" alt="Chargement" /></div><div style="float: left; margin-left: 5px; padding-top: 2px;">Actualisation des données en cours</div><div style="clear: both;"></div></div>
<div id="header">
		<div class="container">
			<div class="icon left"<?php if (!isset($user)) { echo ' style="display: none;"'; } ?>><a href="./dashboard/"><img src="./images/mobile/home-icon<?php if (isset($section) and ($section=='welcome' or $section=='login')) echo '-active'; ?>.png" width="44" height="44" style="border: 0px;" alt="Tableau de bord" /></a><img src="./images/mobile/home-icon-active.png" width="44" height="44" style="border: 0px; display: none;" /></div>
			<div id="logo"<?php if (!isset($user)) { echo ' style="float: none; margin: 0 auto;"'; } ?>><a href="./dashboard/"><img src="./images/mobile/logo.png" width="125" height="50" style="border: 0px;" alt="Tableau de bord" /></a></div>
			<div class="icon right"<?php if (!isset($user)) { echo ' style="display: none;"'; } ?>><a href="<?php echo site_url(); ?>welcome/s_logout" onclick="javascript:disableAutoLogon();"><img src="./images/mobile/logout-icon.png" width="44" height="44" style="border: 0px;" alt="Déconnexion" /></a><img src="./images/mobile/logout-icon-active.png" width="44" height="44" style="border: 0px; display: none;" /></div>
			<div style="clear: both;"></div>
		</div> <!-- end .container -->
	</div> <!-- end #header -->
</div>
<script language="javascript">
$('#header .container .icon.left a').focus(function() {
  $('#header .container .icon.left a img').attr('src', './images/mobile/home-icon-active.png');
});
<?php if (isset($section) and $section!='welcome') { ?>
$('#header .container .icon.left a').blur(function() {
  $('#header .container .icon.left a img').attr('src', './images/mobile/home-icon.png');
});
<?php } ?>
$('#header .container .icon.right a').focus(function() {
  $('#header .container .icon.right a img').attr('src', './images/mobile/logout-icon-active.png');
});

$('#header .container .icon.right a').blur(function() {
  $('#header .container .icon.right a img').attr('src', './images/mobile/logout-icon.png');
});
</script>
<div id="header-bottom" style="display: none;">
	<a href="./studies/"><img class="link-studies" src="./images/mobile/menu-1.png" width="80" height="52" alt="Dossier scolaire" /></a>
	<a href="./schedule/timetable/"><img class="link-schedule" src="./images/mobile/menu-2.png" width="80" height="52" alt="Horaire des cours" /></a>
	<?php if (isset($user)) { ?>
	<a href="./services/exchange/autologon/1/u/<?php echo base64_encode($user['idul']); ?>/p/<?php echo base64_encode($user['password']); ?>" class="newTab" target="_blank"><img class="link-exchange" src="./images/mobile/menu-3.png" width="80" height="52" alt="Exchange" /></a><?php } ?>
	<a href="./fees/"><img class="link-fees" src="./images/mobile/menu-4.png" width="80" height="52" alt="Frais de scolarité" /></a>
</div>
	<div id="content" >
		<div id="right-shadow">
			<div id="top-shadow"></div>
			<div class="container clearfix">
			<div id="main-area">
			
			<div class="entry page">
				<div class="entry-top"> 
					<div class="entry-content">
					
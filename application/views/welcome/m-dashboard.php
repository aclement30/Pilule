<h2 class="title" style="display: block;">Tableau de bord</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
	<div id="loading-panel" style="display: none; background: none; margin-top: 100px; text-align:center; padding: 10px; color: #fff; font-size: 10pt;">
		<img src="<?php echo site_url(); ?>images/mobile/bg-loading.gif" />
		<div style="margin-top: 20px; text-shadow: 1px 1px 1px #555;">Chargement</div>
	</div>
	<?php if (isset($cap_offline) and $cap_offline == 1) { ?>
	<div id="server-unavailable" style="background: #f7f4b4 url(./images/disconnect.png) no-repeat 8px 15px; margin-bottom: 5px; text-align: left; padding: 10px; padding-left: 35px; color: #000; font-size: 10pt;"><strong style="font-size: 9pt; text-transform: uppercase; line-height: 18px;">Serveur Capsule indisponible</strong><br />L'actualisation des données est impossible.</div><?php } ?>
<style type="text/css">
body {
	background-image: url(./images/mobile/dashboard-bg.jpg);
	background-repeat: no-repeat;
	background-color: #686868;
}

.module {
	text-align: center;
	float: left;
	margin: 10px 15px 5px 0px;
	width: 70px;
	border-radius: 10px;
	-moz-border-radius: 10px;
	 background-color: #444;
	 padding: 10px;
}

.module img {
	width: 50px;
	border: 0px;
	-webkit-user-select: none;
	-webkit-touch-callout: none;
}

.module.loading img {
	opacity: .4;
}

.module .img-link {
	display: block;
	
}

.module .title {
	padding-top: 11px;
}

.module .title a {
	margin: 0 auto;
	color: #fff;
	font-size: 7pt;
	text-decoration: none;
	text-shadow: 1px 1px 1px #000;
}

.module .loading {
	display: none;
	padding-top: 11px;
}

.module .loading-error {
	padding-top: 11px;
	margin: 0 auto;
	color: red;
	font-size: 7pt;
	text-decoration: none;
	text-shadow: 1px 1px 1px #000;
	display: none;
}
</style>

<div id="modules">

<?php
$number = 1;
$dataList = array();

foreach ($modules as $module) {
	$allowed = true;
	
	switch ($module['id']) {
		case 'registration':
			if ($user['registration'] != true) $allowed = false;
			if (time() >= '1319832000' and time() <= '1319832000') {
				$allowed = false;
			}
			$allowed = false;
		break;
		case'admin':
			if ($user['idul']!='alcle8') $allowed = false;
		break;
		/* Maintence de Capsule - 28-29 oct. 2011
		case 'studies':
		case 'fees':
		case 'schedule':
		case 'capsule':
		case 'registration':
			if (time() >= '1319832000' and time() <= '1319832000') {
				$allowed = false;
			}
		break;*/
		case 'registration':
			$allowed = false;
		break;
		default:
			$allowed = true;
		break;
	}
	
	if ($module['title'] == 'Frais de scolarité') $module['title'] = 'Frais scolarité';
	
	if ($_SESSION['cap_datacheck'] == 2 and isset($module['data']) and $module['data'] != '') {
		$allowed = false;
	}
	
	$module['loading'] = true;
	
	if ($allowed) {
	?>
<div id="module-<?php echo $module['id']; ?>" class="module box<?php echo $number; ?><?php if ($module['loading'] != true) echo ' loading'; ?>"<?php if ($number%3==0) echo ' style="margin-right: 0px;"'; ?> onmousedown="javascript:dashboardObj.mouseDown(<?php echo $number; ?>, 1);" <?php if (isset($module['target']) and $module['target'] == '_blank') echo 'onmouseup'; else echo 'onblur'; ?>="javascript:dashboardObj.mouseDown(<?php echo $number; ?>, 2);">
<a href="<?php if (isset($module['target']) and $module['target'] == '_blank') echo $module['url'].'autologon/1/u/'.base64_encode($user['idul']).'/p/'.base64_encode($user['password']); else { ?>javascript:dashboardObj.goTo('<?php echo $module['id']; ?>');<?php } ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link<?php if (isset($module['target']) and $module['target'] == '_blank') echo ' newTab'; ?>"><img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" width="50" height="50" /></a>
<div class="title" style="<?php if ($module['loading'] != true) echo 'display: none;'; ?>;"><a href="<?php if (isset($module['target']) and $module['target'] == '_blank') echo $module['url'].'autologon/1/u/'.base64_encode($user['idul']).'/p/'.base64_encode($user['password']); else { ?>javascript:dashboardObj.goTo('<?php echo $module['id']; ?>');<?php } ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="<?php if (isset($module['target']) and $module['target'] == '_blank') echo 'newTab'; ?>"><span><?php echo $module['title']; ?></span></a></div>
<div class="loading" style="<?php if ($module['loading'] != true) echo 'display: block;'; ?>"><img src="./images/mobile/dashboard-login.gif" width="16" height="11" style="width: 16px; opacity: 1;" /></div>
<div class="loading-error">Erreur</div><input type="hidden" id="module-<?php echo $module['id']; ?>-action" value="#!<?php echo str_replace("./", "/", $module['url']); ?>" />
</div>
	<?php
		$number++;
	}
}
?>

<div style="clear: both;"></div>

<div style="margin-top: 15px; font-size: 8pt; text-align: center;"><a href="./welcome/s_changedisplay/normal" style="color: #fff; text-decoration: none; text-shadow: 1px 1px 1px #555;">Site normal</a></div>

</div>
<div class="clear"></div></div>
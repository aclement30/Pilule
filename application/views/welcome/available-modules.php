<div class="post-content">
<h4>Modules disponibles<a href="javascript:dashboardObj.lockModules();" class="link" style="font-size: 9pt; font-family: Arial, Helvetica, sans-serif; float: right;"><img src="<?php echo site_url(); ?>images/cross.png" align="absmiddle" style="position: relative; top: 3px;" />&nbsp;&nbsp;Fermer</a><div style="clear: both;"></div></h4>

<ul class="modules-list">
<?php
// Sélection des modules de l'utilisateur
$number = 1;
foreach ($dashboard_modules as $module) {
	$allowed = true;
	
	switch ($module['id']) {
		case 'registration':
			if ($user['registration'] != 'true') $allowed = false;
		break;
		case'admin':
			if ($user['idul']!='alcle8') $allowed = false;
		break;
		default:
			$allowed = true;
		break;
	}
	
	foreach ($modules as $module2) {
		if ($module2['id'] == $module['id']) {
			$allowed = false;
			break;
		}
	}
	
	if ($allowed) {
	?>
<li id="box-<?php echo $module['id']; ?>" class="module available" onMouseOver="javascript:dashboardObj.mouseOver('<?php echo $module['id']; ?>', 1);" onMouseOut="javascript:dashboardObj.mouseOver('<?php echo $module['id']; ?>', 2);">
<a href="<?php if (strpos($module['url'], "s_connect")>0) echo "javascript:dashboardObj.connectTo('".$module['url']."');"; else echo $module['url']; ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link"><img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" /></a>
<div class="title"><a href='<?php if (strpos($module['url'], "s_connect")>0) { echo "javascript:dashboardObj.connectTo('".$module['url']."');"; } else { if (substr($module['url'], 0, 4)!='http') echo site_url(); echo $module['url']; } ?>'><?php echo $module['description']; ?></a></div></li>
	<?php
		$number++;
	}
}
?>


</ul>
<div class="page-separator"></div>
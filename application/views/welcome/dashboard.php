<h2 class="title">Tableau de bord<?php if ($_SESSION['cap_datacheck'] == 2) { } else { ?><a id="edit-dashboard-link" href="javascript:dashboardObj.edit();" class="link" style="margin-right: 15px; font-size: 9pt;"><img src="<?php echo site_url(); ?>images/pencil.png" align="absmiddle" />&nbsp;&nbsp;Modifier</a><div class="clear"></div><?php } ?></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div id="available-modules" style="display: none;"></div>

<div class="post-content">

<ul id="modules" class="modules-list">

<?php
// Sélection des modules de l'utilisateur
$number = 1;

foreach ($modules as $module) {
	$allowed = true;
	
	switch ($module['id']) {
		case 'registration':
			if ($user['registration'] != true) $allowed = false;
			if (time() >= '1319832000' and time() <= '1319832000') {
				$allowed = false;
			}
		break;
		case'admin':
			if ($user['idul']!='alcle8') $allowed = false;
		break;
		/* Maintence de Capsule - 28-29 oct. 2011 */
		case 'studies':
		case 'fees':
		case 'schedule':
		case 'capsule':
		case 'registration':
			if (time() >= '1319832000' and time() <= '1319832000') {
				$allowed = false;
			}
		break;
		default:
			$allowed = true;
		break;
	}
	
	if ($_SESSION['cap_datacheck'] == 2 and isset($module['data']) and $module['data'] != '') {
		$allowed = false;
	}
	/*
	if ($_SESSION['cap_datacheck'] == 2 and ($module['data'] != '' or $module['id'] == 'registration')) {
		$allowed = false;
	}
	*/
	if ($allowed) {
	?>
<li id="box-<?php echo $module['id']; ?>" class="module" onMouseOver="javascript:dashboardObj.mouseOver('<?php echo $module['id']; ?>', 1);" onMouseOut="javascript:dashboardObj.mouseOver('<?php echo $module['id']; ?>', 2);">
<a href="<?php if (strpos($module['url'], "s_connect")>0) echo "javascript:dashboardObj.connectTo('".$module['url']."');"; else echo $module['url']; ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link"><img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" /></a>
<div class="title"><a href='<?php if (strpos($module['url'], "s_connect")>0) { echo "javascript:dashboardObj.connectTo('".$module['url']."');"; } else { if (substr($module['url'], 0, 4)!='http') echo site_url(); echo $module['url']; } ?>'><?php echo $module['title']; ?></a></div></li>
	<?php
		$number++;
	}
}
?>

</ul>
<div class="clear"></div></div>
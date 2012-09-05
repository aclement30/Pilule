<div id="available-modules" style="display: none;"></div>

<div class="row-fluid">

    <div class="span12 center" style="text-align: center;">
        <ul class="quick-actions dashboard">
<?php
// Sélection des modules de l'utilisateur
$number = 1;

foreach ($modules as $module) {
	$allowed = true;
	
	switch ($module['id']) {
		case 'registration':
			if (!$user['registration']) $allowed = false;
		break;
		case'admin':
			if (!$user['admin']) $allowed = false;
		break;
		default:
			$allowed = true;
		break;
	}
	
	if ($capsule_offline and isset($module['data']) and $module['data'] != '') {
		$allowed = false;
	}

	if ($allowed) {
	?>
    <li>
        <a href="<?php if (strpos($module['url'], "s_connect")>0) echo "javascript:app.dashboard.connectTo('".$module['url']."');"; else echo $module['url']; ?>">
            <img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" />
            <?php echo $module['title']; ?>
        </a>
    </li>
<!--
<li id="box-<?php echo $module['id']; ?>" class="module" onMouseOver="javascript:app.dashboard.mouseOver('<?php echo $module['id']; ?>', 1);" onMouseOut="javascript:app.dashboard.mouseOver('<?php echo $module['id']; ?>', 2);">
<a href="<?php if (strpos($module['url'], "s_connect")>0) echo "javascript:app.dashboard.connectTo('".$module['url']."');"; else echo $module['url']; ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link"><img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" /></a>
<div class="title"><a href='<?php if (strpos($module['url'], "s_connect")>0) { echo "javascript:app.dashboard.connectTo('".$module['url']."');"; } else { if (substr($module['url'], 0, 4)!='http') echo site_url(); echo $module['url']; } ?>'><?php echo $module['title']; ?></a></div></li>-->
	<?php
		$number++;
	}
}
?>

        </ul>
    </div>
</div>

</div><!-- End of row-fluid -->
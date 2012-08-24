<h2 class="title">Administration</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<ul id="modules" class="modules-list">

<?php
$modules = array(
				 array(
					   'id'		=>	'maintenance',
					   'url'	=>	'#!/admin/maintenance/',
					   'img'	=>	'maintenance.png',
					   'title'	=>	'Maintenance'
					   ),
				 array(
					   'id'		=>	'stats',
					   'url'	=>	'#!/admin/stats/',
					   'img'	=>	'stats.gif',
					   'title'	=>	'Statistiques'
					   ),
				 array(
					   'id'		=>	'registration',
					   'url'	=>	'#!/admin/registration/',
					   'img'	=>	'registration.png',
					   'title'	=>	'Inscription'
					   ),
				 array(
					   'id'		=>	'google-analytics',
					   'url'	=>	'https://www.google.com/analytics/web/#report/visitors-overview/a345357w42905627p42875527/',
					   'img'	=>	'analytics.png',
					   'title'	=>	'Google Analytics',
					   'target'	=>	'_blank'
					   )
				 );

$number = 1;
foreach ($modules as $module) {
	$allowed = true;
	
	switch ($module['url']) {
		default:
			$allowed = true;
		break;
	}
	
	if ($allowed) {
	?>
<li id="box-<?php echo $module['id']; ?>" class="module" onMouseOver="javascript:mouseOver('<?php echo $number; ?>', 1);" onMouseOut="javascript:mouseOver('<?php echo $number; ?>', 2);">
<a href="<?php echo $module['url']; ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link admin-page"><img src="<?php echo site_url(); ?>images/<?php echo $module['img']; ?>" /></a>
<div class="title"><a href='<?php echo $module['url']; ?>' class="admin-page"><?php echo $module['title']; ?></a></div>
</li>
	<?php
		$number++;
	}
}
?>
</ul>
<script language="javascript">
function mouseOver (n, type) {
	if ($('#box-'+n).parent().attr('id') == 'modules' && (!$('#box-'+n).parent().hasClass('ui-sortable'))) {
		if (type==1) {
			$('#box-'+n).css('backgroundColor', '#999');
			$('#box-'+n+' a').css('color', '#fff');
		} else {
			$('#box-'+n).css('backgroundColor', '#efefef');
			$('#box-'+n+' a').css('color', '#444');
		}
	}
}
</script>
<div class="clear"></div></div>
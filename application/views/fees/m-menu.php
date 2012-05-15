<div id="sidebar">
	<div id="sidebar-bottom">
		<div id="sidebar-content">
			<h4 class="widgettitle">Frais de scolarité</h4>
			<div class="widget-content"><ul>
				<li class="leftEnd"><a href="./fees/" class="link-summary">État de compte</a></li>
				<li class="rightEnd"><a href="./fees/details" class="link-details">Relevé par session</a></li>
			</ul></div>
		</div> <!-- end #sidebar-content -->
	</div> <!-- end #sidebar-bottom -->
</div> <!-- end #sidebar -->

<div id="sidebar-notices">
<?php
if ($holds!=array()) {
foreach ($holds as $hold) {
	?>
<div class='et-box et-warning'>
<div class='et-box-content'>
	<span style="font-size: 17px;"><strong>Blocage</strong><div style="margin-top: 3px;"><?php echo $hold['type']; ?></div></span>
	<p style="padding-bottom: 0px; clear: both; margin-top: 7px;"><?php if ($hold['date_end']=='') { ?>Début : <?php echo currentDate($hold['date_start'], 'd M Y'); }
	else { ?>Période : <?php echo currentDate($hold['date_start'], 'd M Y')." - ".currentDate($hold['date_end'], 'd M Y'); } ?></p>
	<p style="color: grey; margin: 0px; padding-top: 5px; font-size: 8pt;"><strong>Actions bloquées : </strong><?php echo $hold['actions']; ?></p>
</div></div><?php
}
?><div class="explanation" style="font-size: 7pt; padding: 5px; padding-top: 0px; line-height:13px;">Même si le blocage n'est effectif que dans le système de gestion des études de l'université, il peut empêcher le bon fonctionnement de certaines fonctions de Pilule.</div>
<?php
}
?>

</div>
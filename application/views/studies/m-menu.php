<div id="sidebar">
	<div id="sidebar-bottom">
		<div id="sidebar-content">
			<h4 class="widgettitle">Dossier scolaire</h4>
			<div class="widget-content"><ul>
				<li class="leftEnd"><a href="./studies" class="link-home"><?php if ($mobile!=1) echo "Programme d'études"; else echo "Programme"; ?></a></li>
				<li class=""><a href="./studies/details/1" class="link-details"><?php if ($mobile!=1) echo "Rapport de cheminement"; else echo "Cheminement"; ?></a></li>
				<li class="rightEnd"><a href="./studies/report" class="link-report"><?php if ($mobile!=1) echo "Relevé de notes"; else echo "Relevé notes"; ?></a></li>
			</ul></div>
		</div> <!-- end #sidebar-content -->
	</div> <!-- end #sidebar-bottom -->
</div> <!-- end #sidebar -->

<div id="sidebar-notices">
<?php
/*
if ($page == 'report') {
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
}
*/
?>

</div>
<h2 class="title"><?php if (!isset($title)) echo 'Erreur de chargement'; else echo $title; ?></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<div style="float: left; width: 120px; margin-top: 8px;">
	<img src="./images/file_broken.png" width="100" height="100" />
</div>
<div style="float: left; width: 450px;">
<h3 style="margin-bottom: 10px;">Données introuvables !</h3>
<p>Une erreur est survenue durant le chargement des données depuis le serveur de Capsule. Les données demandées ne sont pas disponibles.</p>
<?php if (isset($reload_name)) { ?><p><a href="javascript:reloadData('<?php echo $reload_name; ?>');" class='icon-button reload-icon'><span class='et-icon'><span>Recharger les données</span></span></a><div style="clear: both;"></div></p><?php } ?>
</div>
<div style="clear: both;"></div>
</div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
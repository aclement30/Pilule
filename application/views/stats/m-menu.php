<div id="sidebar">
	<div id="sidebar-bottom">
		<div id="sidebar-content">
			<div class="widget">
				<h4 class="widgettitle">Statistiques</h4>
				<div class="widget-content"><ul>
					<li class="leftEnd<?php if ($page=='visits') echo ' selected'; ?>"><a href="<?php echo site_url(); ?>stats/"<?php if ($page=='visits') echo ' class="active"'; ?>>Visites</a></li>
					<li class="<?php if ($page=='users') echo ' selected'; ?>"><a href="<?php echo site_url(); ?>stats/users/"<?php if ($page=='users') echo ' class="active"'; ?>>Utilisateurs</a></li>
					<li class="<?php if ($page=='stats/registration') echo ' selected'; ?>"><a href="<?php echo site_url(); ?>stats/registration/"<?php if ($page=='stats/registration') echo ' class="active"'; ?>>Inscription</a></li>
					<li class="rightEnd<?php if ($page=='errors') echo ' selected'; ?>"><a href="<?php echo site_url(); ?>stats/errors/"<?php if ($page=='errors') echo ' class="active"'; ?>>Erreurs</a></li>
				</ul></div>
			</div>
		</div> <!-- end #sidebar-content -->
	</div> <!-- end #sidebar-bottom -->
</div> <!-- end #sidebar -->
	
	<?php if ( !$mobile_browser ) { ?>
	<div class="row-fluid footer">
	    <div class="navbar">
	        <div class="navbar-inner">
	            <ul class="nav">
	                <li><a href="<?php echo site_url(); ?>support/terms">Conditions d'utilisation</a></li>
	                <li><a href="<?php echo site_url(); ?>support/privacy">Confidentialité des données</a></li>
	                <li><a href="<?php echo site_url(); ?>support/faq">F.A.Q.</a></li>
	                <li><a href="<?php echo site_url(); ?>support/contact">Contact</a></li>
	            </ul>
	            <div class="nav" style="float: right; padding-top: 10px; margin-right: 20px;">
	                <p style="float: right; margin-left: 40px; color: gray; font-style: italic; font-size: 8pt; text-align: right;">Projet hébergé par<br /><img src="<?php echo site_url(); ?>img/ulaval-black.png" height="36" align="absbottom" style="padding-top: 6px; padding-bottom: 5px;" /></p>
	                <p id="copyright" style="text-align: right; float: right;">Conception<br /><a href="http://www.alexandreclement.com" target="_blank" style="font-style: normal; font-size: 9pt;">Alexandre Clément</a></p>
	                <div style="clear: both;"></div>
	            </div>
	        </div>
	    </div>
	</div>
	<?php } else { ?>
	<div class="navbar menu">
		<div class="navbar-inner">
			<ul class="nav">
				<li class="link-studies link-studies-summary link-studies-details link-studies-report dropdown">
					<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><img src="/img/modules/report.png" /></a>
					<ul class="dropdown-menu bottom-up" role="menu">
						<li class="link-studies-summary"><a href="#!/studies">Programme d'études</a></li>
						<li class="link-studies-details"><a href="#!/studies/details">Rapport de cheminement</a></li>
						<li class="link-studies-report"><a href="#!/studies/report">Relevé de notes</a></li>
					</ul>
				</li>
				<li class="link-schedule"><a href="#!/schedule"><img src="/img/modules/schedule.png" /></a></li>
				<li class="link-exchange"><a href="#!/exchange"><img src="/img/modules/mail.png" /></a></li>
				<li class="link-tuitions dropdown">
					<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><img src="/img/modules/fees.png" /></a>
					<ul class="dropdown-menu bottom-up pull-right" role="menu">
		                <li><a href="#!/fees">Sommaire du compte</a></li>
		                <li><a href="#!/fees/details">Relevé par session</a></li>
		            </ul>
				</li>
				
			</ul>
		</div>
	</div>
	<?php } ?>
	
    <script type='text/javascript' src="<?php echo site_url(); ?>js/bootstrap.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/bootstrap-tooltip.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/pilule.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/users.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/studies.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/tuitions.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/settings.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/dashboard.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/schedule.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/cache.js?ver=2.0.2"></script>
    <?php if (!isset($user)) { ?>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/login.js?ver=2.0.2"></script>
    <?php } ?>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/main.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/ajax.js?ver=2.0.2"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/path.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/fullcalendar.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/jquery.flot.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/jquery.flot.pie.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/jquery.flot.resize.min.js"></script>
    <script src="<?php echo site_url(); ?>js/modernizr.custom.41742.js"></script>
	<script language="javascript">
	$(document).ready(function() {
        // Définition du statut connecté de l'utilisateur
        app.user.isAuthenticated = true;

        // Définition de la disponibilité de Capsule
        app.isCapsuleOffline = <?php echo $capsule_offline; ?>;

        // Création d'un iframe invisible pour ouvrir des pages en arrière-plan
        $('<iframe id="frame" name="frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">').appendTo('#sidebar');
        if ( isMobile != 1 ) {
        	$('<iframe id="external-frame" name="external-frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">').appendTo('body');
        	app.resizeExternalFrame();
        }
    });
	</script>
</body>
</html>
<script type='text/javascript' src="/js/bootstrap.min.js"></script>
<script type='text/javascript' src="/js/bootstrap-tooltip.js"></script>
<script type='text/javascript' src="/js/pilule.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/users.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/studies.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/tuitions.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/settings.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/dashboard.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/schedule.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/cache.js?ver=2.0.2"></script>
<?php if ( !isset( $user ) ): ?>
    <script type='text/javascript' src="/js/login.js?ver=2.0.2"></script>
<?php endif; ?>
<script type='text/javascript' src="/js/main.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/ajax.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/path.min.js"></script>
<script type='text/javascript' src="/js/fullcalendar.min.js"></script>
<script type='text/javascript' src="/js/jquery.flot.min.js"></script>
<script type='text/javascript' src="/js/jquery.flot.pie.min.js"></script>
<script type='text/javascript' src="/js/jquery.flot.resize.min.js"></script>
<script type='text/javascript' src="/js/modernizr.custom.41742.js"></script>

<script language="javascript">
$( document ).ready( function() {
    // Définition du statut connecté de l'utilisateur
    app.user.isAuthenticated = true;

    // Définition de la disponibilité de Capsule
    app.isCapsuleOffline = <?php echo $isCapsuleOffline; ?>;

    // Création d'un iframe invisible pour ouvrir des pages en arrière-plan
    $('<iframe id="frame" name="frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">').appendTo('#sidebar');
    if ( isMobile != 1 ) {
    	$('<iframe id="external-frame" name="external-frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">').appendTo('body');
    	app.resizeExternalFrame();
    }

    $( '.buttons.semester-select select' ).live( 'click', function (e) { app.schedule.displaySemester( $( e.currentTarget ).val() ); } );
});
</script>
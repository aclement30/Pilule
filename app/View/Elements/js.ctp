<!-- Load external libraries -->
<script type='text/javascript' src="/js/libs/bootstrap.min.js"></script>
<script type='text/javascript' src="/js/libs/fullcalendar.min.js"></script>
<script type='text/javascript' src="/js/libs/modernizr.custom.41742.js"></script>
<script type='text/javascript' src="/js/ajax.js"></script>
<!--<script type='text/javascript' src="/js/path.min.js"></script>-->

<!-- Load Pilule-specific JS files -->
<?php
    // Define site-wide scripts
    $scripts = array( '/js/pilule.js', '/js/cache.js' );

    if ( isset( $assets ) && isset( $assets[ 'js' ] ) ) {
        $scripts = array_merge( $scripts, $assets[ 'js' ] );
    }

    // Add version number to each JS path : clear old JS files in browser cache
    $currentVersion = '2.1.1';

    foreach ( $scripts as &$path ) {
        $path .= '?ver=' . $currentVersion;
    }

    echo $this->Html->script( $scripts );
?>
<!--
<script type='text/javascript' src="/js/pilule.js?ver=2.1"></script>
<script type='text/javascript' src="/js/users.js?ver=2.1"></script>
<script type='text/javascript' src="/js/studies.js?ver=2.1"></script>
<script type='text/javascript' src="/js/tuitions.js?ver=2.1"></script>
<script type='text/javascript' src="/js/settings.js?ver=2.1"></script>
<script type='text/javascript' src="/js/dashboard.js?ver=2.1"></script>
<script type='text/javascript' src="/js/schedule.js?ver=2.1"></script>
<script type='text/javascript' src="/js/cache.js?ver=2.1"></script>
-->
<!--
<?php if ( !isset( $user ) ): ?>
    <script type='text/javascript' src="/js/login.js?ver=2.0.2"></script>
<?php endif; ?>
<script type='text/javascript' src="/js/main.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/ajax.js?ver=2.0.2"></script>
<script type='text/javascript' src="/js/path.min.js"></script>
<script type='text/javascript' src="/js/jquery.flot.min.js"></script>
<script type='text/javascript' src="/js/jquery.flot.pie.min.js"></script>
<script type='text/javascript' src="/js/jquery.flot.resize.min.js"></script>

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
-->
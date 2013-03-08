<?php
    // Define site-wide scripts
    $scripts = array( '/js/pilule.js', '/js/cache.js', '/js/libs/bootstrap.min.js', '/js/libs/fullcalendar.min.js', '/js/libs/jquery.knob.js', '/js/libs/jquery.sparkline.min.js', '/js/libs/toastr.js', '/js/libs/jquery.tablesorter.min.js', '/js/libs/jquery.peity.min.js', '/js/libs/modernizr.custom.41742.js', '/js/ajax.js' );

    if ( isset( $assets ) && !empty( $assets[ 'js' ] ) ) {
        $scripts = array_merge( $scripts, $assets[ 'js' ] );
    }

    // Add version number to each JS path : clear old JS files in browser cache
    $currentVersion = '3.0.5';

    foreach ( $scripts as &$path ) {
        if ( strpos( $path, '/libs/' ) < 1 ) {
            $path = str_replace( '.js', '-' . $currentVersion . '.js', $path );
        }
    }

    echo $this->Html->script( $scripts );
?>

<script language="javascript">
    $( document ).ready( function() {
        // Define Capsule availability
        app.isCapsuleOffline = <?php if ( $isCapsuleOffline ) echo 'true'; else echo 'false'; ?>;

        app.ipAddress = '<?php echo $this->request->clientIp(); ?>';
        app.baseUrl = '<?php echo Router::url( '/', true ); ?>';
        app.isMobile = <?php if ( $isMobile ) echo 'true'; else echo 'false'; ?>;
        app.init();
        
        // Define data expiration delay
        <?php
            if ( empty( $userParams[ 'data-expiration-delay' ] ) ) {
                $expirationDelay = DATA_EXPIRATION_DELAY;
            } else {
                $expirationDelay = $userParams[ 'data-expiration-delay' ];
            }
        ?>
        var dataExpirationDelay = <?php echo $expirationDelay; ?>;

        <?php
            // Check if data need to be fetched automatically because timestamp is expired
            if ( !empty( $timestamp ) && $timestamp < ( time() - $expirationDelay ) ) {
                ?>app.Cache.reloadData( { name: '<?php echo $dataObject; ?>', auto: 1 } );<?php
            }
        ?>
    });
</script>
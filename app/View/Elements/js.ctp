<!-- Load external libraries -->
<script type='text/javascript' src="/js/libs/bootstrap.min.js"></script>
<script type='text/javascript' src="/js/libs/fullcalendar.min.js"></script>

<script type='text/javascript' src="/js/libs/jquery.knob.js"></script>
<!--<script type='text/javascript' src="http://d3js.org/d3.v3.min.js"></script>-->
<!--script type='text/javascript' src="/js/d3-setup.js"></script>-->
<script type='text/javascript' src="/js/libs/jquery.sparkline.min.js"></script>
<script type='text/javascript' src="/js/libs/toastr.js"></script>
<script type='text/javascript' src="/js/libs/jquery.tablesorter.min.js"></script>
<script type='text/javascript' src="/js/libs/jquery.peity.min.js"></script>

<script type='text/javascript' src="/js/libs/modernizr.custom.41742.js"></script>
<script type='text/javascript' src="/js/ajax-3.0.0.js"></script>

<!-- Load Pilule-specific JS files -->
<?php
    // Define site-wide scripts
    $scripts = array( '/js/pilule-3.0.0.js', '/js/cache-3.0.0.js' );

    if ( isset( $assets ) && !empty( $assets[ 'js' ] ) ) {
        $scripts = array_merge( $scripts, $assets[ 'js' ] );
    }

    // Add version number to each JS path : clear old JS files in browser cache
    $currentVersion = '3.0.0';

    foreach ( $scripts as &$path ) {
        $path .= str_replace( '.js', '-' . $currentVersion . '.js' );
    }

    echo $this->Html->script( $scripts );
?>

<script language="javascript">
    $( document ).ready( function() {
        // Define Capsule availability
        app.isCapsuleOffline = <?php if ( $isCapsuleOffline ) echo 'true'; else echo 'false'; ?>;

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

        app.ipAddress = '<?php echo $this->request->clientIp(); ?>';

        app.init();
    });
</script>
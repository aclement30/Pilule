<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" />
<?php //if ( $isMobile ): ?>
    <!--<link rel="stylesheet" href="/css/mobile.css?v=2.0.1" />-->
<?php //else: ?>
	<?php
		// Add version number to each CSS path : clear old CSS files in browser cache
    	$currentVersion = '2.2';

		if ( isset( $assets ) && !empty( $assets[ 'css' ] ) ) {
	        $stylesheets = $assets[ 'css' ];

	        foreach ( $stylesheets as &$path ) {
		        $path .= '?ver=' . $currentVersion;
		    }

		    echo $this->Html->css( $stylesheets );
	    } else {
	    	echo $this->Html->css( '/css/global.css?ver=' . $currentVersion );
	    }
	?>
    <link rel="stylesheet" href="/css/toastr.css?v=2.2" />
    <link rel="stylesheet" href="/css/fullcalendar.css?v=2.2" />
    <link rel="stylesheet" href="/css/fullcalendar.print.css" media="print" />
<?php //endif; ?>
<link rel="stylesheet" href="/css/print.css" media="print" />
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" />
<?php
	// Add version number to each CSS path : clear old CSS files in browser cache
	$currentVersion = '2.5';

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
<link rel="stylesheet" href="<?php echo Router::url( '/' ) ?>css/toastr.css?v=2.5" />
<link rel="stylesheet" href="<?php echo Router::url( '/' ) ?>css/fullcalendar.css?v=2.5" />
<link rel="stylesheet" href="<?php echo Router::url( '/' ) ?>css/fullcalendar.print.css?v=2.5" media="print" />
<link rel="stylesheet" href="<?php echo Router::url( '/' ) ?>css/print.css?v=2.5" media="print" />
<!--[if lt IE 9]>
<link rel="stylesheet" href="<?php echo Router::url( '/' ) ?>css/ie.css?v=2.5" media="screen" type="text/css" />
 <![endif]-->
<!DOCTYPE html>
<html lang='en'>
<meta charset='utf-8'>
<head>
    
    <base href="http://www.pilule.ulaval.ca" />

    <?php echo $this->element( 'metas' ); ?>
	
    <title><?php if ( isset( $title_for_layout ) ) echo $title_for_layout . ' | '; ?>Pilule - Gestion des études</title>

    <?php echo $this->element( 'css/bootstrap' ); ?>
    <?php echo $this->element( 'css/default' ); ?>
    
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-345357-28']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        <?php if ( isset( $_GET[ 'debug' ] ) and $_GET[ 'debug' ] == 1 ) echo 'var debug = 1;'; else echo 'var debug=0;'; ?>
    </script>
</head>

<body>
	<!--[if IE 6]>
	<div style="background-color: red; width:100%; border-bottom: 2px solid black;">
	<div style="padding: 20px; color: #fff; font-size: 11pt;"><strong>Votre navigateur (Internet Explorer 6) n'est pas supporté par Pilule.</strong> Veuillez mettre à jour votre navigateur ou utiliser un autre navigateur compatible (Firefox 3.5+, Safari 3+, Chrome, etc).</div>
	</div>
	<![endif]-->

	<?php echo $this->element( 'header' ); ?>

    <div class="page">
        <div class="page-container">
            <div class="container">
                <div class="row">
                    <div class="span3">
                        <?php
                            if ( !empty( $sidebar ) )
                                echo $this->element( 'sidebars/' . $sidebar );
                        ?>
                    </div>
                    <div class="span9">
                        <div class="btn-group action-buttons pull-right">
                            <div class="buttons">
                              <?php
                                if ( isset( $buttons ) && !empty( $buttons ) )
                                  echo $this->element( 'action_buttons', $buttons );
                              ?>
                            </div>
                            <div class="timestamp no-print">
                              <?php
                                if ( isset( $timestamp ) && !empty( $timestamp ) )
                                    echo 'Données actualisées : ' . $this->App->timeAgo( $timestamp ) . '.';
                              ?>
                            </div>
                            <div class="loading-status"></div>
                        </div>

                        <h4 class="header"><?php echo $title_for_layout; ?></h4>

                        <?php echo $this->Session->flash(); ?>

                        <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"></div>

	<?php echo $this->element( 'footer' ); ?>

	<?php echo $this->element('sql_dump'); ?>

	<?php echo $this->element( 'js' ); ?>
</body>
</html>

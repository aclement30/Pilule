<!DOCTYPE html>
<html lang='en'>
<meta charset='utf-8'>
<head>    
    <?php echo $this->element( 'metas' ); ?>
	
    <title><?php if ( isset( $title_for_layout ) ) echo $title_for_layout . ' | '; ?>Pilule - Gestion des études</title>

    <?php echo $this->element( 'css/bootstrap' ); ?>
    <?php echo $this->element( 'css/default' ); ?>
    
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
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

	<!-- Entête navigateur mobile -->

	<?php echo $this->element( 'header' ); ?>

	<!-- Fin de l'entête mobile -->

	<div id="container">
		<div id="header">
			<h1><?php echo $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	
	<?php echo $this->element( 'footer' ); ?>

	<?php echo $this->element( 'js' ); ?>
</body>
</html>

<!DOCTYPE html>
<html lang='en'>
<meta charset='utf-8'>
<head>    
    <?php echo $this->element( 'metas' ); ?>
    
    <title><?php if ( isset( $title_for_layout ) ) echo $title_for_layout . ' | '; ?>Pilule - Gestion des études</title>

    <?php echo $this->element( 'css/bootstrap' ); ?>
    <?php echo $this->element( 'css/login' ); ?>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

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

    <?php echo $this->fetch('content'); ?>

    <footer>
        <p class="inside">
            <div class="navbar">
                <div class="navbar-inner">
                    <ul class="nav" style="margin-top: 20px;">
                        <li><a href="/support/terms">Conditions d'utilisation</a></li>
                        <li><a href="/support/privacy">Confidentialité des données</a></li>
                        <li><a href="/support/faq">F.A.Q.</a></li>
                        <li><a href="/support/contact">Contact</a></li>
                    </ul>
                    <div class="nav" style="float: right; padding-top: 10px; margin-right: 20px;">
                        <p style="float: right; margin-left: 40px; font-style: italic; font-size: 8pt; text-align: right;">Projet hébergé par<br /><img src="/img/ulaval-black.png" height="36" align="absbottom" style="padding-top: 6px; padding-bottom: 5px;" /></p>
                        <p id="copyright" style="text-align: right; float: right;">Conception<br /><a href="http://www.alexandreclement.com" target="_blank" style="font-style: normal; font-size: 9pt;">Alexandre Clément</a></p>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </p>
    </footer>

    <?php echo $this->element('sql_dump'); ?>

    <?php echo $this->element('js'); ?>

    <script type="text/javascript">
    $(document).ready(function() {
        // Mettre le focus sur le champ IDUL
        //setTimeout( "$('#idul').focus()", 500 );

        // Quand la touche Enter est pressée dans le champ Password, valider le formulaire
        $('#password').keypress(function(e){
            if (e.which == 13) {
                app.users.login();
            }
        });

        // Positionner la boîte de connexion verticalement
        $( '#formContainer' ).css( 'marginTop', ( $( window ).height() / 2 ) - 200 );

        $( window ).resize( function() {
            $( '#formContainer' ).css( 'marginTop', ( $(window).height()/2)-200);
        } );
    });
</script>
</body>
</html>

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
    <!--[if IE 7]>
    <div style="background-color: red; width:100%; border-bottom: 2px solid black;">
    <div style="padding: 20px; color: #fff; font-size: 11pt;"><strong>Votre navigateur (Internet Explorer 7) n'est pas supporté par Pilule.</strong> Veuillez mettre à jour votre navigateur ou utiliser un autre navigateur compatible (Firefox 3.5+, Safari 3+, Chrome, etc).</div>
    </div>
    <![endif]-->

    <?php echo $this->fetch('content'); ?>

    <footer class="login">
        <p class="inside">
            <div class="navbar">
                <div class="navbar-inner">
                    <ul class="nav menu">
                        <li><a href="/support/terms">Conditions d'utilisation</a></li>
                        <li><a href="/support/privacy">Confidentialité des données</a></li>
                        <li><a href="/support/faq">F.A.Q.</a></li>
                        <li><a href="/support/contact">Contact</a></li>
                    </ul>
                    <div class="nav credits clearfix">
                        <p class="hosting">
                            Projet hébergé par<br />
                            <img src="/img/ulaval-white.png" />
                        </p>
                        <p class="conception">
                            Conception<br />
                            <a href="http://www.alexandreclement.com" target="_blank">Alexandre Clément</a>
                        </p>
                    </div>
                </div>
            </div>
        </p>
    </footer>

    <?php echo $this->element('js'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $( '#btn-login' ).on( 'click', app.Users.login );
            $( '#login-form input.idul, #login-form input.password' ).on( 'keyup', function ( e ) {
                // If Enter key is pressed, submit login form
                if ( e.keyCode == 13 ) {
                    app.Users.login();
                }
            } );
            $( '#loading-error .btn-redirect-dashboard' ).on( 'click', app.Users.redirectToDashboard );
            $( '#loading-error .btn-retry-login' ).on( 'click', app.Users.retryLogin );

            $( '#login-form input.idul' ).focus();

            $( '#login-form .help-btn' ).on( 'mouseover', function( e ) {
                $( this ).addClass( 'btn-danger' );
                $( this ).find( 'i' ).addClass( 'icon-white' );
            });

            $( '#login-form .help-btn' ).on( 'mouseout', function( e ) {
                $( this ).removeClass( 'btn-danger' );
                $( this ).find( 'i' ).removeClass( 'icon-white' );
            });

            $( '#login-form .help-btn' ).tooltip();

            if ( Modernizr.localstorage ) {
                if ( localStorage.getItem( 'pilule-autologon-idul' ) == null ) {
                    if ( !app.ipAddress.match( /132\.203\.[0-9]{1,3}\.[0-9]{1,3}/g ) ) {
                        $( '.js-save-idul' ).parent().show();
                    }
                } else {
                    $( '#login-form input.idul' ).val( localStorage.getItem( 'pilule-autologon-idul' ) );
                    $( '#login-form input.password' ).focus();
                }
            }

            var updateResponsive = function () {
                if ( $( window ).width() <= 480 ) {
                    $( '#login-form input.idul, #login-form input.password' ).addClass( 'input-xlarge' );
                    $( '#login-form #btn-login, #login-form .help-btn' ).addClass( ' btn-large' );

                    $( '#login-form input.idul' ).attr( 'placeholder', $( '#login-form input.idul' ).data( 'placeholder' ) );
                    $( '#login-form input.password' ).attr( 'placeholder', $( '#login-form input.password' ).data( 'placeholder' ) );
                    
                    $( '#formContainer' ).css( 'marginTop', '0px' );
                } else {
                    $( '#login-form input.idul, #login-form input.password' ).removeClass( 'input-xlarge' );
                    $( '#login-form #btn-login, #login-form .help-btn' ).removeClass( ' btn-large' );

                    $( '#login-form input.idul' ).removeAttr( 'placeholder' );
                    $( '#login-form input.password' ).removeAttr( 'placeholder' );

                    // Vertically position the form container
                    $( '#formContainer' ).css( 'marginTop', ( $( window ).height() / 2 ) - 250 );
                }
            };

            ( updateResponsive )();

            $( window ).resize( updateResponsive );
        });
    </script>
</body>
</html>

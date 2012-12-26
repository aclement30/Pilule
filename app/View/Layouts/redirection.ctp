<!DOCTYPE html>
<html lang='en'>
<meta charset='utf-8'>
<head>
    <base href="http://www.pilule.ulaval.ca" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Connexion à <?php echo $title_for_layout; ?></title>
    
    <?php echo $this->element( 'css/bootstrap' ); ?>

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

    </script>
</head>

<body>
    <?php echo $this->fetch('content'); ?>

    <script language="javascript">
        $( document ).ready( function() {
            if ( $( 'form.js-login-form' ).length != 0 && $( '#loadingFrame' ).length == 0 ) {

                <?php if ( $user['idul'] == 'demo' ) : ?>
                	setTimeout( "$('form.js-login-form').submit()", 2000 );
                <?php else: ?>
                	$( 'form.js-login-form' ).submit();
                <?php endif; ?>
            }

            $( 'form.js-login-form' ).on( 'submit', function( e ) {
                $( 'form.js-login-form input[name=_method]' ).remove();
            });
        });
    </script>
</body>
</html>
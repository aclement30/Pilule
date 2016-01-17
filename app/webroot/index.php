<!DOCTYPE html>
<html lang='fr'>
<meta charset='utf-8'>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité." />

    <!-- Facebook metas -->
    <meta property="og:image" content="/img/thumbnail.jpg"/>
    <meta property="og:title" content="Pilule - Gestion des études"/>
    <meta property="og:description" content="Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité."/>
    <meta property="og:url" content="/"/>
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="Pilule - Gestion des études"/>
    <meta property="fb:app_id" content="102086416558659"/>

    <!-- Windows 8 metas -->
    <meta name="application-name" content="Pilule"/>
    <meta name="msapplication-TileColor" content="#134989"/>
    <meta name="msapplication-TileImage" content="/img/windows8-tile.png"/>

    <meta name="google-site-verification" content="Ik8Zit9T1rOcM6WMr7ls6yGI5apxQl9L_ERciD4aR2E" />

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
    <link rel="apple-touch-startup-image" href="/img/startup.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/icons/apple-touch-icon-144x144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/icons/apple-touch-icon-114x114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/icons/apple-touch-icon-72x72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="/img/icons/apple-touch-icon-57x57-precomposed.png" />

    <title>Pilule - Gestion des études</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-responsive.min.css" />

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="/css/ie.css?v=2.5" media="screen" type="text/css" />
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <style type="text/css">
        body { background: none; }
        footer .hosting { display: none; }
        footer .hosting.ie-only { display: block; }
    </style>
    <![endif]-->

    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push( [ '_setAccount', 'UA-345357-28' ] );
        _gaq.push( [ '_trackPageview' ] );

        ( function() {
            var ga = document.createElement( 'script' ); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ( 'https:' == document.location.protocol ? 'https://ssl' : 'http://www' ) + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName( 'script' )[ 0 ]; s.parentNode.insertBefore( ga, s );
        } )();
    </script>
</head>

<body>
<style>
    /*-------------------------
        Simple reset
    --------------------------*/

    * {
        margin: 0;
        padding: 0;
    }

    /*-------------------------
        General Styles
    --------------------------*/

    body {
        background: url('../img/images/login-bg.gif');
        background-attachment: fixed;
        margin: 0px;
    }

    .farewell {
        margin: 40px auto 0;
        max-width: 640px;
        font-family: 'Roboto', sans-serif;
    }

    .farewell .inside {
        background-color: #fafafa;
        border-radius: 3px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.065);
        padding: 30px;
    }

    .farewell h2 {
        font-weight: 300;
    }

    .farewell .unplug-icon {
        text-align: center;
        margin: 10px 0 20px;
    }

    .farewell .unplug-icon img {
        height: 100px;
    }

    .farewell h1 {
        margin-top: 20px;
        text-align: center;
        margin-bottom: 30px;
    }

    .farewell h1 img {
        width: 150px;
    }

    .farewell .gif {
        text-align: center;
        margin: 30px 0 10px;
    }

    .footer {
        margin: 20px 10px;
    }

    .footer p {
        color: silver;
        font-size: 10px;
        float: right;
    }

    .footer a {
        color: white;
        font-size: 14px;
        border-bottom: 1px dotted white;
    }

    .footer a:hover {
        text-decoration: none;
        color: #ffad26;
        border-bottom-color: #ffad26;
    }

    @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 667px)
    and (orientation : portrait) {
        .farewell {
            margin: 0;
        }
    }
</style>

<div class="farewell">
    <h1><img src="/img/logo.png" alt="Pilule - Gestion des études - Université Laval" /></h1>
    <div class="inside">
        <div class="unplug-icon"><img src="img/electricy-off.png"></div>

        <h2>Pilule tire sa révérence</h2>

        <p>Après plus de 5 ans en service, le moment est venu de retirer Pilule.
            Plusieurs raisons ont motivé cette décision, notamment le manque de temps pour continuer le support technique et la difficulté croissante à maintenir la compatibilité de Pilule avec les nouvelles versions de Capsule.
            De plus, le peu d'intérêt et de support de la part de l'Université envers le projet a aussi pesé dans la balance.</p>

        <p>Depuis son lancement, Pilule a été utilisé par des dizaines de milliers d'étudiants cherchant une alternative au labyrinthe obscur et complexe que représente le système de gestion des études de l'Université.
            Pilule a aussi offert depuis longtemps la connexion automatique aux services de l'Université, ainsi qu'une version mobile du site. En terme de statistiques, le site recevait chaque mois près de 20 000 visites.
            Au total, plus de 21 300 étudiants ont utilisé Pilule depuis 2011.</p>

        <p>Même si Pilule est désormais hors service, le code source de l'application reste disponible sur <a href="https://github.com/aclement30/Pilule" target="_blank">Github</a>.
            Les personnes et organisations intéressées à reprendre le projet sont invitées à <a href="mailto:web@alexandreclement.com">me contacter</a> pour obtenir plus d'informations.</p>

        <p>Merci à tous les étudiants qui ont utilisé le service depuis sa création, ainsi qu'à l'Université Laval qui a hébergé le projet depuis 2011. Ce fut une belle aventure de lancer le projet et de le voir grandir avec vous !</p>

        <p style="margin-top: 30px;">Alexandre Clément<br><span style="font-style: italic; color: darkgray">Créateur de Pilule</span></p>

        <div class="gif"><img src="img/peace-out.gif" width="400"></div>
    </div>
    <div class="footer">
        <p class="hosting">
            Projet hébergé par<br />
            <img src="/img/ulaval-white.png" style="height: 40px;" />
        </p>
        <p class="conception" style="margin-right: 30px;">
            Conception<br />
            <a href="http://www.alexandreclement.com" target="_blank">Alexandre Clément</a>
        </p>
        <div style="clear: both"></div>
    </div>
</div>
</body>
</html>
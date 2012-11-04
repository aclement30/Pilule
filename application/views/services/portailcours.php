<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Connexion à Portail des cours</title>
    <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
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
<div id="safari-warning" style="display: none; padding: 20px; margin: 100px auto; background-color: #efefef; border-radius: 10px; -moz-border-radius: 10px; width: 400px; font-family: Helvetica, Arial; font-size: 10pt;">
<h2 style="border-bottom: 1px dotted silver; padding-bottom: 10px; margin-top: 0px;">Portail des cours</h2>
<div style="width: 60px; float: left;"><img src="./images/safari-warning.png" alt="Safari" width="40" /></div>
<div style="width: 340px; float: left;">
<h3 style="margin-top: 10px;">Navigateur Safari</h3>
<div>Le navigateur Safari n'est pas encore supporté par le Portail des cours. Suivez les étapes ci-dessous pour contourner cette restriction&nbsp;:</div><br />
<strong>Activer le menu Développement</strong>
<ol>
	<li>Ouvrir les <strong>Préférences</strong> de Safari.</li>
	<li>Cliquer sur l'onglet <strong>Avancé</strong>.</li>
	<li>Cocher la case <strong>Afficher le menu Développement dans la barre des menus</strong>.</li>
</ol>
<strong>Simuler le navigateur Firefox</strong>
<ol>
	<li>Dans le menu <strong>Développement &gt; Agent d'utilisateur</strong>, sélectionner le navigateur <strong>Firefox 4.0.1 - Mac</strong>.</li>
</ol>
</div>
<div style="clear: both;"></div>
</div>

<div id="chrome-warning" style="display: none; padding: 20px; margin: 100px auto; background-color: #efefef; border-radius: 10px; -moz-border-radius: 10px; width: 400px; font-family: Helvetica, Arial; font-size: 10pt;">
<h2 style="border-bottom: 1px dotted silver; padding-bottom: 10px; margin-top: 0px;">Portail des cours</h2>
<div style="width: 60px; float: left;"><img src="./images/chrome-warning.png" alt="Chrome" width="40" /></div>
<div style="width: 340px; float: left;">
<h3 style="margin-top: 10px;">Navigateur Google Chrome</h3>
<div>Le navigateur Google Chrome n'est pas encore supporté par le Portail des cours. Suivez les étapes ci-dessous pour contourner cette restriction&nbsp;:</div><br />
<strong>Ajouter le plugin User-Agent Switcher</strong>
<ol>
	<li>Ajouter le <a href="https://chrome.google.com/webstore/detail/djflhoibgkdhkhhcedjiklpkjnoahfmg" target="_blank">plugin User-Agent Switcher</a> depuis le Chrome Web Store.</li>
</ol>

</div>
<div style="clear: both;"></div>
</div>

<div id="loading-message" style="padding: 20px; margin: 100px auto; background-color: #efefef; text-align: center; border-radius: 10px; -moz-border-radius: 10px; width: 400px; font-family: Helvetica, Arial; font-size: 10pt;">
<?php if ($user['idul'] == 'demo') { ?><div style="margin-bottom: 25px; color: red;">La connexion automatique n'est pas<br /> disponible pour la version démo.</div><?php } ?>
<div style="margin-bottom: 20px; margin-top: 10px;"><img src="./img/redirect-loading.gif" alt="Chargement" /></div><strong>Redirection vers Portail des cours...</strong></div>
<form id="login-form" action="https://www.portaildescours.ulaval.ca/portail/j_security_check" method="post">
<input type="hidden" name="j_username" value="<?php echo $user['idul']; ?>">
<input type="hidden" name="j_password" value="<?php echo $user['password']; ?>">
</form>
<script language="javascript">
$(document).ready(function() {
<?php if ($user['idul'] == 'demo') { ?>
	setTimeout("$('#login-form').submit()", 2000);
<?php } else { ?>
	$('#login-form').submit();
<?php } ?>
});
</script>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Connexion à WebCT</title>
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
<div style="padding: 20px; margin: 100px auto; background-color: #efefef; text-align: center; border-radius: 10px; -moz-border-radius: 10px; width: 400px; font-family: Helvetica, Arial; font-size: 10pt;">
<?php if ($user['idul'] == 'demo') { ?><div style="margin-bottom: 25px; color: red;">La connexion automatique n'est pas<br /> disponible pour la version démo.</div><?php } ?>
<div style="margin-bottom: 20px; margin-top: 10px;"><img src="./img/redirect-loading.gif" alt="Chargement" /></div><strong>Redirection vers WebCT...</strong></div>
<form id="login-form" action="https://www.webct.ulaval.ca/webct/ticket/ticketLogin" method="post">
<input type="hidden" name="request_uri" value="/webct/homearea/homearea?">
<input type="hidden" name="action" value="webform_user">
<input type="hidden" name="WebCT_ID" value="<?php echo $user['idul']; ?>">
<input type="hidden" name="Password" value="<?php echo $user['password']; ?>">
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
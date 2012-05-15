<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Connexion à l'Intranet de la FSE</title>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<?php if ($_SERVER["HTTP_HOST"]!='localhost') { ?>
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
<?php } ?>
</head>

<body>
<div style="padding: 20px; margin: 100px auto; background-color: #efefef; text-align: center; border-radius: 10px; -moz-border-radius: 10px; width: 400px; font-family: Helvetica, Arial; font-size: 10pt;">
<?php if ($user['idul'] == 'demo') { ?><div style="margin-bottom: 25px; color: red;">La connexion automatique n'est pas<br /> disponible pour la version démo.</div><?php } ?>
<div style="margin-bottom: 20px; margin-top: 10px;"><img src="./images/redirect-loading.gif" alt="Chargement" /></div><strong>Redirection vers l'Intranet de la FSE...</strong></div>
<?php if ($autologon == 'yes') { ?>
<form id="login-form" action="https://www.intranet.fse.ulaval.ca/check_login.php" method="post" target="_top">
<input type="hidden" name="idul" value="<?php echo strtoupper($credentials['username']); ?>">
<input type="hidden" name="user_pass" value="<?php echo $credentials['password']; ?>">
<input type="hidden" name="pageencours" value="/index.php">
<input type="hidden" name="pageencours_get" value="">
</form>
<?php } else { ?>
<form id="login-form" action="https://www.intranet.fse.ulaval.ca/" method="get" target="_top">
</form>
<script language="javascript">
$(document).ready(function() {
	$('#login-form').submit();
	top.stopLoading();
	setTimeout("top.$.modal.close()", 500);
});
</script>
<?php } ?>
</body>
</html>
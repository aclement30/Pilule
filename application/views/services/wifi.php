<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Redirection vers le site de l'Université Laval</title>
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
<div style="padding: 20px; margin: 100px auto; background-color: #efefef; text-align: center; border-radius: 10px; -moz-border-radius: 10px; width: 400px; font-family: Helvetica, Arial; font-size: 10pt;"><div style="margin-bottom: 20px; margin-top: 10px;"><img src="./img/redirect-loading.gif" alt="Chargement" /></div><strong>Redirection vers le site de l'Université Laval...</strong></div>
<form id="wifi-form" action="https://secure.dexero.com/ulaval/SFEW/Catalogue.dispatch.action;jsessionid=$sessionID" method="post">
<input type="hidden" name="hidProductSeqID" value="5762" />
<input type="hidden" name="txtProductID" value="20114" />
<input type="hidden" name="add.x" value="97" />
<input type="hidden" name="add.y" value="17" />
</form>
<script language="javascript">
$(document).ready(function() {
   $('#wifi-form').submit();
 });
</script>
</body>
</html>
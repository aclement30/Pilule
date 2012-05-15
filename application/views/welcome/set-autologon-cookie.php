<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>AUTOLOGON</title>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/dashboard.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/schedule.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/fees.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/settings.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/modernizr.custom.40351.js"></script>
</head>

<body>
<div id="message" style="display: none; color: green;">Authentification Facebook activée</div>
<div id="error" style="display: none; color: red;">Erreur lors de l'activation de l'authentification Facebook</div>

<script language="javascript">
$(document).ready(function(){
    if (Modernizr.localstorage) {
		localStorage.setItem('pilule-fbauth', '1');
			$('#message').fadeIn();
			setTimeout("document.location='<?php echo site_url(); ?>'", 3000);
		
	} else {
		$('#error').fadeIn();
	}
}); 
</script>
</body></html>
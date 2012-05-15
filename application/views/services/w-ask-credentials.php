<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title; ?></title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url(); ?>css/course-info.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/functions.js"></script>
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

<body style="background-color: #fff;">
<h1><div style="float: left;">Connexion à <span style="color: #000;"><?php echo $service_name; ?></span></div><div style="float: right;"><img src="<?php echo site_url(); ?>images/keychain.png" height="32" width="32" style="" alt="Sécurité" align="absmiddle" /></div><div style="clear: both;"></div></h1>
<div style="float: left; width: 320px; border-right: 1px dotted silver; padding-right: 20px;">
<h4 style="margin-top: 0px;">Connexion automatique</h4>
<p style="margin-bottom: 10px;">Pilule permet la <strong>connexion automatique à <?php echo $service_name; ?></strong>.<br />Vos identifiants de connexion seront stockées de manière sécurisée sur le serveur de Pilule et permettront de vous connecter automatiquement lors de vos prochaines visites.</p>
<div id="form-login">
	<table style="width: 100%;" id="login-table">
		<tbody>
			<tr>
				<th style="width: 110px; text-align: right; padding-right: 20px;"><label for="idul"><?php echo $username; ?></label></th>
				<td style="padding-right: 10px;"><input type="text" name="username" id="username" class="input" style="width: 150px;" autocomplete="off" value="<?php if (isset($username_value)) echo $username_value; ?>" /></td>
			</tr>
			<tr>
				<th style="text-align: right; padding-right: 20px; padding-top: 5px;"><label for="password"><?php echo $password; ?></label></th>
				<td style="padding-top: 5px;"><input type="password" name="password" id="password" class="input" style="width: 150px;" /></td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top: 5px; padding-right: 85px; text-align: center; padding-top: 20px; padding-bottom: 0px;"><a href="javascript:loginService();" class='icon-button login-icon' style="float: right;"><span class='et-icon'><span>Connexion</span></span></a><div style="float: right; margin: 15px 10px;"><img class="login-btn-img" src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" alt="Chargement" /></div><div style="clear: both;"></div></td>
			</tr>
		</tbody>
	</table>
	</div>
</div>
<div style="float: right; width: 300px;">
<h4 style="margin-top: 0px;">Connexion manuelle</h4>
<p style="margin-bottom: 10px;">Vous pouvez aussi choisir de <strong>ne pas enregistrer vos identifiants de connexion</strong> sur Pilule et de vous connecter manuellement à chaque visite sur <?php echo $service_name; ?>.</p>
<p><input type="checkbox" name="ask-credentials" value="no" id="ask-credentials" />&nbsp;<label for="ask-credentials">Mémoriser mon choix pour <?php echo $service_name; ?>.</label></p>
<div style="padding-right: 90px;">
<a href="javascript:skipAutoLogon();" class='icon-button next-icon' style="float: right;"><span class='et-icon'><span>Continuer</span></span></a><div style="clear: both;"></div></div>
</div><div style="clear: both;"></div>
<script language="javascript">
function loginService () {
	$('.login-btn-img').show();
	$('.error-message').hide();
	
	var username = $('#username').val();
	var password = $('#password').val();
	
	top.loading('Connexion en cours...');
	
	!sendData('POST','./services/s_tryLogin', 'username='+username+'&password='+password+'&service=<?php echo $service; ?>');
}

function loginServiceCallback (response) {
	$('.login-btn-img').hide();
	
	if (response == 1) {
		top.loading('Ouverture de la page...');
		window.document.location = '<?php echo site_url(); ?>services/<?php if (isset($service_url)) echo $service_url; else echo $service; ?>';
	} else {
		top.errorMessage('Impossible de se connecter à <?php echo $service_name; ?> avec les informations fournies.<br />Veuillez vérifier les identifiants de connexion.');
	}
}

function skipAutoLogon () {
	if ($("#ask-credentials").attr("checked")==true) {
		var autologon = 0;
	} else {
		var autologon = 1;
	}
	
	!sendData('GET','./services/s_skipAutoLogon', 'service/<?php echo $service; ?>/autologin/'+autologon);
}
</script>
</body></html>
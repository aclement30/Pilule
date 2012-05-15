<?php $code = md5(time()); ?>
<div style="background-image: url(<?php echo site_url(); ?>images/error-bg.png); background-color: #5d5d5d; padding: 20px; margin:10px 0 10px 0; -moz-box-shadow: inset 1px 1px 10px #222; -moz-border-radius: 10px; text-align: center; color: #eee;">
<div id="wt-script-error-message-<?php echo $code; ?>">
<a href="javascript:document.getElementById('wt-script-error-<?php echo $code; ?>').style.display='block';document.getElementById('wt-script-error-message-<?php echo $code; ?>').style.display='none';void(0);" title="Afficher les détails"><img src="<?php echo site_url(); ?>images/error-message.png" style="border: 0px; text-decoration: none;" onmouseover="javascript:this.src='<?php echo site_url(); ?>images/error-message-over.png';" onmouseout="javascript:this.src='<?php echo site_url(); ?>images/error-message.png';" alt="Erreur" /></a>
<h4 style="line-height: 20px; text-shadow: #333 1px 1px 3px; margin-top: 5px; margin-bottom: 5px; font-family: Arial, Helvetica, sans-serif;">Une erreur de fonctionnement empêche l'affichage de ce composant.</h4>
</div>
<div id="wt-script-error-<?php echo $code; ?>" style="display: none; font-family: Arial, Helvetica, sans-serif;">
<p style="color: #eee; margin: 0px; padding: 0px; text-shadow: #333 1px 1px 3px;"><strong>Niveau de l'erreur : </strong><?php switch ($severity) {
	case 'Notice':
		echo 'Faible';
	break;
	case 'Warning':
		echo 'Moyen';
	break;
	case 'Fatal':
		echo 'Élevé';
	break;
	default:
		echo 'Inconnu';
	break; } ?></p>
<div style="background-image: url(<?php echo site_url(); ?>images/error-message-bg.png); -moz-border-radius: 5px; margin: 5px 0; font-weight: bold; color: #333; padding: 5px 5px;"><code><?php echo $message; ?></code></div>
<p style="color: #eee; margin: 0px; padding: 0px; text-shadow: #333 1px 1px 3px;"><strong>Fichier : </strong><?php echo $filepath; ?> (ligne <?php echo $line; ?>)</p>
</div>
</div>
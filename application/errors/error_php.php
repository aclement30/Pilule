<?php $code = md5(time()); ?>
<div style="background-image: url(<?php echo site_url(); ?>images/error-bg.png); background-color: #5d5d5d; padding: 20px; margin:10px 0 10px 0; -moz-box-shadow: inset 1px 1px 10px #444; box-shadow: inset 1px 1px 10px #444; moz-border-radius: 5px; border-radius: 5px; text-align: center; color: #eee;">
<div id="wt-script-error-<?php echo $code; ?>" style="font-family: Arial, Helvetica, sans-serif;">
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
<div style="-moz-border-radius: 5px; margin: 5px 0; font-weight: bold; color: #333; padding: 5px 5px;"><code><?php echo $message; ?></code></div>
<p style="color: #eee; margin: 0px; padding: 0px; text-shadow: #333 1px 1px 3px;"><strong>Fichier : </strong><?php echo $filepath; ?> (ligne <?php echo $line; ?>)</p>
</div>
</div>
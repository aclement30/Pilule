<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo site_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Comment fonctionne l'inscription ?</title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/course-info.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1><div style="float: left;">Comment fonctionne l'inscription ?</div><div style="float: right; color: black;"><?php echo $step; ?> / 5</div><div style="clear: both;"></div></h1>
<?php
switch ($step) {
	case 1:
		?>
<div style="float: left; width: 265px; margin-right: 20px;">
	<img src="<?php echo site_url(); ?>images/reg-help-courses-list.gif" width="260" height="313" />
</div>
<div style="float: left; width: 350px;">
<h4>Liste des cours disponibles</h4>
<p style="line-height: 20px;">Pilule analyse votre cheminement scolaire pour déterminer les cours que vous avez déjà réussis et les cours qui sont offerts dans votre programme.</p>
<p style="line-height: 20px;">Le schéma des cours de votre programme contient tous les cours disponibles, selon votre profil et vos choix de concentrations.</p>
<p style="line-height: 20px;">Pour faciliter le classement des cours, des couleurs sont utilisées pour représenter les différents cours :</p>
<ul type="square" style="margin-bottom: 10px;">
	<li style="color: green;">- Vert : cours déjà faits</li>
	<li style="color: black;">- Noir : cours disponibles à la session d'inscription</li>
	<li style="color: gray;">- Gris : cours offerts, mais non disponibles</li>
</ul>
<p style="line-height: 20px;">Lorsque vous cliquez sur le titre du cours, une fenêtre s'ouvre avec les informations, la description et les différents choix de cours offerts.</p>
	<?php
	break;
	case 2:
		?>
<div style="float: left; width: 265px; margin-right: 20px;">
	<img src="<?php echo site_url(); ?>images/reg-help-class-info.gif" width="260" height="200" />
</div>
<div style="float: left; width: 350px;">
<h4>Informations sur le cours</h4>
<p style="line-height: 20px;">La fenêtre d'information sur le cours affiche la liste des cours disponibles pour la session d'inscription.</p>
<p style="line-height: 20px;">La vignette du cours contient l'horaire des classes, la durée du cours et le code NRC correspondant.</p>
<p style="line-height: 20px;">Lorsque vous cliquez sur le bouton <strong>Ajouter</strong>, le cours est ajouté à votre sélection de cours.</p>
		<?php
	break;
	case 3:
		?>
<div style="float: left; width: 265px; margin-right: 20px;">
	<img src="<?php echo site_url(); ?>images/reg-help-selected-courses.gif" width="260" height="325" />
</div>
<div style="float: left; width: 350px;">
<h4>Sélection de cours</h4>
<p style="line-height: 20px;">Les cours sélectionnés sont affichés dans la colonne de droite.</p>
<p style="line-height: 20px;">Vous pouvez à tout moment ajuster la sélection en ajoutant ou en enlevant des cours.</p>
<p style="line-height: 20px;">Pour enlever un cours, <strong>cliquez sur le X</strong> à droite du cours.</p>
<p style="line-height: 20px;">Lorsque vous avez complété votre sélection de cours, cliquez sur le bouton <strong>Inscription</strong> pour procéder à l'inscription des cours.</p>
		<?php
	break;
	case 4:
		?>
<div style="text-align: center;">
	<img src="<?php echo site_url(); ?>images/reg-help-results.gif" width="600" height="140" />
</div>
<div style="padding: 0px 20px;">
<h4>Inscription aux cours</h4>
<p style="line-height: 20px;">Lorsque vous validez votre sélection de cours, Pilule enverra votre sélection de cours au système de gestion des études de l'université. Cette étape peut prendre jusqu'à 1 minute.</p>
<p style="line-height: 20px;">Lorsque l'inscription est complétée, le résultat de l'inscription pour chaque cours est affichée.</p>
<p style="line-height: 20px;">S'il y a lieu, le message d'erreur est affiché pour expliquer les éventuels problèmes d'inscription.</p>
		<?php
	break;
	case 5:
		?>
<div style="float: left; width: 265px; margin-right: 20px;">
	<img src="<?php echo site_url(); ?>images/reg-help-registered-courses.gif" width="260" height="330" />
</div>
<div style="float: left; width: 350px;">
<h4>Cours inscrits</h4>
<p style="line-height: 20px;">Lorsque votre inscription est confirmée pour un cours, celui-ci s'affiche dans la liste des cours inscrits, située dans la colonne de droite. Cette liste correspond aux cours inscrits à votre horaire de cours officiel.</p>
<p style="line-height: 20px;">Pour vous désinscrire d'un cours, <strong>cliquez sur le X</strong> à droite du cours. Une confirmation vous sera demandée.</p>
<p style="line-height: 20px;">Note : comme sur Capsule, il est <strong>possible que des frais/mention d'échec s'appliquent</strong> lors d'une désinscription. Avant tout, consultez le calendrier de l'université pour connaître les dates d'abandon de cours.</p>
<p style="line-height: 20px;">Après confirmation, votre demande de désinscription sera envoyée automatiquement au système de gestion des études. Cette étape peut prendre jusqu'à 1 minute. Votre liste de cours sera ensuite réajustée.</p>
		<?php
	break;
}

if ($step!=5) {
?>
<a href="<?php echo site_url(); ?>registration/w_help/<?php echo $step+1; ?>" class='icon-button next-icon' style="float: right;"><span class='et-icon'><span>Étape suivante</span></span></a><div style="clear: both;"></div>
<?php } else { ?>
<a href="javascript:top.$.modal.close();" class='icon-button signup-icon' style="float: right;"><span class='et-icon'><span>Terminer</span></span></a><div style="clear: both;"></div>
<?php } ?>
</div>
<div style="clear: both;"></div>
</body></html>
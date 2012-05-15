<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $course['title']; ?></title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:light,regular,bold&amp;subset=latin' rel='stylesheet' type='text/css' />
<link href="<?php echo site_url(); ?>css/course-info.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo site_url(); ?>js/ajax.js"></script>
<script language="javascript" src="<?php echo site_url(); ?>js/jquery-1.4.2.min.js"></script>
</head>

<body>
<h1><div style="float: left;"><?php echo $course['title']; ?></div><div style="float: right; color: black;"><?php echo $code; ?></div><div style="clear: both;"></div></h1>
<p style="line-height: 20px; margin-top: 0px; padding-top: 0px;"><?php echo str_replace("", "'", $course['description']); ?></p>
<?php if ($course['restrictions'] != '') { ?><div style="float: left; width: 47%; margin-right: 30px;"><h4 style="margin-top: 10px;">Restrictions</h4>
<p style="line-height: 20px; margin-top: 0px; padding-top: 0px;"><?php if (md5($course['restrictions']) == 'e6a3382bd06b53ce1db9e05be135757a') echo 'Non disponible en formation continue'; else echo str_replace("<br /><br />", "<br />", str_replace("\n", "<br />", $course['restrictions'])); ?></p></div><?php } ?>
<?php if ($course['prerequisites'] != '') { ?><div style="float: left; width: 47%;"><h4 style="margin-top: 10px;">Préalables</h4>
<p style="line-height: 20px; margin-top: 0px; padding-top: 0px;"><?php echo str_replace(" ET ", " <strong>ET</strong> ", str_replace(" OU ", " <strong>OU</strong> ", $course['prerequisites'])); ?></p></div><?php } ?>
<div style="clear: both;"></div>
<h4 style="margin-top: 10px;">Cours disponibles</h4>
<?php
if ($course['av'.$semester] == 1) {
	?>
<div id="loading-classes" style="width: 150px; margin: 50px auto 0px; text-align: center;"><img src="<?php echo site_url(); ?>images/loading-classes.gif" width="31" height="31" /><p>Recherche de cours...</p></div>
<div id="classes-list"></div>
<script language="javascript">
!sendData('GET','<?php echo site_url(); ?>registration/w_getavailableclasses', 'code/<?php echo $course['id']; ?>/semester/<?php echo $semester; ?>');
</script>
	<?php
} else { ?><p>Ce cours n'est pas offert pour la session à venir.</p><?php }
?></body></html>
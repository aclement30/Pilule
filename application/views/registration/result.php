<h2 class="title">Résultats de l'inscription<div style="clear: both;"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div id="notice">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php //echo currentDate($cache_date, 'd F Y'); ?> à <?php //echo str_replace(":", "h", $cache_time); ?>.</div>
<br />
<table class="courses">
	<tbody>
		<tr>
			<th style="font-weight: bold; text-align: left; width: 10%;">Cours</th>
			<?php if ($mobile!=1) { ?><th style="font-weight: bold; text-align: left; width: 45%;">Titre</th><?php } ?>
			<th style="font-weight: bold; text-align: left; width: 45%;">Résultat</th>
			<th>&nbsp;</th>
		</tr>
<?php
$regError = 0;
foreach ($results as $result) {
	if ($result['registered']==1) {
		$message = "Inscrit";
	} else {
		$regError = 1;
		$error = $result['error'];
		
		switch ($error) {
			case 'CLOS‐L.A. PLEINE':
				$message = "Inscription refusée : liste d'attente complète.";
			break;
			case 'ERREUR LIEN: R EXIGÉ':
				$message = "Inscription refusée : cours régulier exigé.";
			break;
			case 'ERREUR LIEN: C EXIGÉ':
				$message = "Inscription refusée : cours connexe exigé.";
			break;
			case 'ERREUR LIEN: S EXIGÉ':
				$message = "Inscription refusée : stage exigé.";
			break;
			case 'ERREUR LIEN: T EXIGÉ':
				$message = "Inscription refusée : cours temps plein exigé.";
			break;
			case 'GROUPE CLOS':
				$message = "Inscription refusée : groupe complet.";
			break;
			case 'HEURES MAX DÉPASSÉES':
				$message = "Inscription refusée : nombre de crédits maximum atteint.";
			break;
			case 'NOTE TEST/PRÉAL-ERREUR':
				$message = "Inscription refusée : test préalable requis.";
			break;
			case 'OUVERT — L.A. REMPLIE':
				$message = "Inscription refusée : liste d'attente complète.";
			break;
			case 'RÉSA CLOSE':
				$message = "Inscription refusée : places disponibles complètes.";
			break;
			case 'RÉSA OUV.‐L.A. REMPLIE':
				$message = "Inscription refusée : places disponibles complètes.";
			break;
			case 'RESERVE CLOSED‐WL FULL %':
				$message = "Inscription refusée : places disponibles complètes.";
			break;
			case 'RESTRICTION CAMPUS':
				$message = "Inscription refusée : restriction de campus.";
			break;
			case 'RESTRICTION CLASS':
				$message = "Inscription refusée : restriction de niveau.";
			break;
			case 'RESTRICTION CYCLE':
				$message = "Inscription refusée : restriction de cycle.";
			break;
			case 'RESTRICTION FAC.':
				$message = "Inscription refusée : restriction de faculté.";
			break;
			case 'RESTRICTION MJRE':
				$message = "Inscription refusée : restriction de majeure.";
			break;
			case 'RESTRICTION PROG.':
				$message = "Inscription refusée : restriction de programme.";
			break;
			default:
				if (substr($error, 0, 5)=='CLOS-' and strpos($error, "LISTE ATTENTE")>1) {
					$students = substr($error, 5);
					$students = trim(substr($students, 0, strpos($students, " ")));
					$message = "Liste d'attente : ".$students." étudiants.";
					$result['registered'] = 2;
				} elseif (substr($error, 0, 5)=='OUV.‐' and strpos($error, "LST ATTENTE")>1) {
					$students = substr($error, 5);
					$students = trim(substr($students, 0, strpos($students, " ")));
					$message = "Liste d'attente : ".$students." étudiants.";
					$result['registered'] = 2;
				} elseif (substr($error, 0, 8)=='RÉSA OUV' and strpos($error, "EN L.A.")>1) {
					$students = substr($error, 13);
					$students = trim(substr($students, 0, strpos($students, " ")));
					$message = "Liste d'attente : ".$students." étudiants.";
					$result['registered'] = 2;
				}  elseif (substr($error, 0, 8)=='RESERVE C' and strpos($error, "ON WL")>1) {
					$students = substr($error, 15);
					$students = trim(substr($students, 0, strpos($students, " ")));
					$message = "Liste d'attente : ".$students." étudiants.";
					$result['registered'] = 2;
				} else {
					switch (substr($error, 0, 7)) {
						case 'CONCOM_':
							$course = substr($error, 7);
							$course = strtoupper(str_replace(" ", "-", trim(substr($course, 0, strrpos($course, " ")))));
							$message = "Inscription refusée : autre cours exigé : ".$course.".";
						break;
						case 'CONFLIT':
							$course = str_replace(" ", "-", trim(substr($error, strrpos($error, " ")+1)));
							$message = "Inscription refusée : conflit d'horaire avec ".$course.".";
						break;
						case 'COP COU':
							$message = "Inscription refusée : déjà inscrit à ce cours.";
						break;
						case 'REPEAT':
							$message = "Inscription refusée : nombre d'inscriptions successives atteint.";
						break;
					}
				}
			break;
		}
	}
	?>
		<tr>
			<td style="<?php if ($result['registered']==1) echo 'color: green;'; elseif ($result['registered']==2) echo 'color: orange;'; else echo 'color: red;'; ?>"><?php echo $result['code']; ?></td>
			<td style="font-weight: bold; <?php if ($result['registered']==1) echo 'color: green;'; elseif ($result['registered']==2) echo 'color: orange;'; else echo 'color: red;'; ?>"><?php if (strlen($result['title'])>35) echo substr($result['title'], 0, 30)."..."; else echo $result['title']; ?></td>
			<td style="<?php if ($result['registered']==1) echo 'color: green;'; elseif ($result['registered']==2) echo 'color: orange;'; else echo 'color: #333;'; ?> font-size: 9pt;"><?php if (!isset($message)) echo $error; else echo $message; ?></td>
			<td style="text-align: right;"><?php if ($result['registered']==1) echo '<img src="./images/accept.png" align="absmiddle" />'; elseif ($result['registered']==2) echo '<img src="./images/asterisk_orange.png" align="absmiddle" />'; else echo '<img src="./images/false.png" align="absmiddle" />'; ?></td>		
		</tr>
	<?php } ?>
	</tbody>
</table>
<?php if ($regError==1) { ?>
<h4 style="margin-top: 20px;">Erreurs d'inscription</h4>
<p style="margin-bottom: 0px; padding-bottom: 0px;">Selon le système de gestion des études, votre inscription a été refusée à un ou plusieurs cours. Il est recommandé de réessayer l'inscription à ces cours ou de contacter votre directeur de programme si les problèmes d'inscription persistent.</p>
<?php } ?>
<div style="padding: 20px;"><a href="./registration/courses/">&laquo;&nbsp;Revenir à la page d'inscription</a></div><br />
<div style="display:none"><div id="data" class="course-info-box">

</div></div>
<style type="text/css">
#fancybox-content h3 {
	padding-bottom: 5px; color: #808080; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;
	font-size: 22px;
	font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 15px; margin-bottom: 5px;
	padding-bottom: 10px; border-bottom: 1px solid #e7e7e7; margin: 15px 0 20px 0;
			font-size: 24px;
}

#fancybox-content h3 a {
	color: #808080;
}

#fancybox-content h4 {
	padding-bottom: 5px; color: #808080; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;font-size: 18px;font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 15px; margin-bottom: 5px;
}

#fancybox-content .class-choice {
	width: 183px;
	background-color: #eee;
	padding: 10px;
	border: 1px solid silver;
	float: left;
	margin: 0px 15px 15px 0px;
}

#fancybox-content .class-choice .type {
	padding-bottom: 5px; color: #333; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;font-size: 18px;font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 5px; margin-bottom: 5px;
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}

#fancybox-content .class-choice .timetable, #fancybox-content .class-choice .teacher {
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}


#fancybox-content .class-choice .timetable {
	padding-top: 8px;
	padding-bottom: 8px;
}


#fancybox-content .class-choice .nrc {
	margin-top: 8px;
	font-size: 10pt;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

.post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}
</style>
<style type="text/css" media="print">
body {
	margin: 0px;
	font-family: Helvetica, Arial;
	font-size: 10pt;
}

#page {
	width: 100%;
}

#header, #header-bottom, a.link, a.refresh, #footer, #sidebar, .content-tabs {
	display: none;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	border: 1px solid silver;
	padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
}

.post-content table th {
	text-align: left;
	font-weight: normal;
	text-transform: uppercase;
	border-bottom: 2px solid gray;
	padding: 10px;
	vertical-align: top;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
	text-transform: none;
}

.post-content table th.left, .post-content table td {
	padding: 10px;
	vertical-align: top;
	border-bottom: 1px solid silver;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}

#notice {
	background-color: none;
	padding: 0px;
	font-size: 7pt;
	margin-bottom: 25px;
	color: #999;
}

h1 {
	margin-bottom: 10px;
}
</style>
<style type="text/css">
<?php if ($mobile==1) { ?>
.post-content table th {
	width: 100px;
	font-size: 9pt;
}

br.space, .content-tabs {
	display: none;
}

.post-content h4 {
	font-size: 9pt;
	font-weight: normal;
	text-transform: uppercase;
}

.no-courses {
	font-size: 10pt; margin-top: 0pt;
}
<?php } ?>
</style>
<div class="clear"></div></div>
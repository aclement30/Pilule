<?php
$displayNotice = false;

if (isset($cap_offline) and $cap_offline == 1) {
	$displayNotice = true;
	?><div id="sidebar-warning">
<div class='et-box et-info'>
<div class='et-box-content' style="background: #f7f4b4 url(./images/file_broken-small.png) no-repeat 18px 13px;">
	<span style="font-size: 14px;"><strong>Maintenance de Capsule/Banner</strong></span>
	<?php if ($cap_datacheck == 1) { ?>
	<p style="padding-bottom: 0px; clear: both; margin-top: 7px;">Dû à une maintenance du Système de gestion des études, certaines fonctions ne sont pas disponibles.</p>
	<p style="padding-bottom: 5px; clear: both; margin-top: 7px;">Les données affichées ont été téléchargées lors de votre dernière connexion et peuvent ne pas être à jour.</p>
	<?php } else { ?>
	<p style="padding-bottom: 0px; clear: both; margin-top: 7px;">Dû à une maintenance du Système de gestion des études, certains modules ne sont pas disponibles.</p>
	<p style="padding-bottom: 5px; clear: both; margin-top: 7px;">Les modules seront à nouveau disponibles dès la fin de la maintenance.</p>
	<?php } ?>
</div></div></div><?php
}
?>
<div id="sidebar">
	<div id="sidebar-bottom">
		<div id="sidebar-content">
		<?php
		$services = array();
		switch ($user['faculty']) {
			case 'Sciences de l\'administration':
				$service_box = "FSA";
				$services = array(
								  'fsa-bv',
								  'fsa-locaux',
								  'elluminate'
								  );
			break;
			case 'Amén. architect. arts visuels':
				switch ($user['program']) {
					case 'B design graphique':
					case 'B arts visuels et médiatiques':
					case 'B art, science de l\'animation':
					case 'B enseignement arts plastiques':
					case 'M arts visuels':
						$service_box = "École des arts visuels";
						$services = array(
										  'arv-impression',
										  'arv-locaux',
										  );
					break;
					case 'B architecture':
					case 'M architecture':
						$service_box = "École d'architecture";
						$services = array(
										  'arc-web-depot',
										  'arc-locaux'
										  );
					break;
				}
			break;
			case 'Lettres':
				$service_box = "Faculté de lettres";
				$services = array(
								  'webct',
								  'com-guide',
								  'elluminate'
								  );
			break;
			case 'Médecine':
				$service_box = "Faculté de médecine";
				$services = array(
								  'med-intranet'
								  );
			break;
			case 'Sciences de l\'éducation':
				$service_box = "Sciences de l'éducation";
				$services = array(
								  'fse-intranet',
								  'fse-materiel'
								  );
			break;
			case 'Sciences et génie':
				$service_box = "Sciences et génie";
				$services = array(
								  'elluminate'
								  );
			break;
		}
		
		if ($services != array()) {
		?>
		<div class="widget" style="margin-bottom: 20px;">
			<h4 class="widgettitle"><?php echo $service_box; ?></h4>
			<div class="widget-content"><ul>
				<?php foreach ($services as $service) {
					switch ($service) {
						case 'fsa-bv':
							?><li><a href="http://bv.fsa.ulaval.ca/default.aspx" target="_blank"><?php if ($service_box != 'FSA') echo 'FSA - '; ?>Bureaux virtuels</a></li><?php
						break;
						case 'fsa-locaux':
							?><li><a href="http://www.fsa.ulaval.ca/GestionLocauxSTE/GestionLocaux.htm" target="_blank">Réservations de locaux</a></li><?php
						break;
						case 'elluminate':
							?><li><a href="<?php echo site_url(); ?>services/elluminate" target="_blank">Elluminate</a></li><?php
						break;
						case 'arv-impression':
							?><li><a href="http://www.arv.ulaval.ca/impression/" target="_blank">Impression en ligne</a></li><?php
						break;
						case 'arv-locaux':
							?><li><a href="http://www.arv.ulaval.ca/reservation/day.php?area=3" target="_blank">Réservation de studios</a></li><?php
						break;
						case 'arc-web-depot':
							?><li><a href="http://www.webdepot.arc.ulaval.ca/" target="_blank">Web Dépôt</a></li><?php
						break;
						case 'arc-locaux':
							?><li><a href="http://www.reservations.arc.ulaval.ca/" target="_blank">Réservation de salles</a></li><?php
						break;
						case 'com-guide':
							?><li><a href="http://www.com.ulaval.ca/etudes/guides-de-letudiant/boite-a-outils/guide-de-redaction/" target="_blank">Guide de rédaction (comm.)</a></li><?php
						break;
						case 'ecole-langues':
							?><li><a href="http://www.elul.ulaval.ca/" target="_blank">École de langues</a></li><?php
						break;
						case 'med-intranet':
							?><li><a href="https://intranet.fmed.ulaval.ca/intranet/usagers/identification.asp" target="_blank">Intranet</a></li><?php
						break;
						case 'fse-intranet':
							?><li><a href="<?php echo site_url(); ?>services/fseintranet" target="_blank">Intranet</a></li><?php
						break;
						case 'fse-materiel':
							?><li><a href="http://www.sites.fse.ulaval.ca/autres_documents/reservation/" target="_blank">Réservation de matériel</a></li><?php
						break;
						case 'webct':
							?><li><a href="<?php echo site_url(); ?>services/webct" target="_blank">WebCT</a></li><?php
						break;
					}
				}
				?>
			</ul></div>
		</div>
		<?php } ?>
		<div class="widget">
			<h4 class="widgettitle">Autres services</h4>
			<div class="widget-content"><ul>
				<?php if ($user['faculty'] != 'Lettres') { ?>
				<li><a href="<?php echo site_url(); ?>services/webct" target="_blank">WebCT</a></li>
				<?php }
				if ($user['program'] == 'B études int.-langues modernes') { ?>
				<li><a href="http://bv.fsa.ulaval.ca/default.aspx" target="_blank">FSA - Bureaux virtuels</a></li>
				<?php }
				if (substr($_SERVER['REMOTE_ADDR'], 0, 7) != '132.203') {
				?>
				<li><a href="<?php echo site_url(); ?>services/wifi" target="_blank">Abonnement au réseau Wi-Fi</a></li>
				<?php } ?>
				<li><a href="<?php echo site_url(); ?>services/capsule" target="_blank">Capsule</a></li>
				<li style="display: none;"><a href="<?php echo site_url(); ?>services/bus" target="_blank">Abonnement carte OPUS (RTC)</a></li>
				<li style="display: none;"><a href="<?php echo site_url(); ?>services/books" target="_blank">Manuels scolaires</a></li>
			</ul></div>
		</div>
		</div> <!-- end #sidebar-content -->
	</div> <!-- end #sidebar-bottom -->
	
</div> <!-- end #sidebar -->
<div id="sidebar-notices">
<?php
if (isset($fees) and $fees["balance"]>0 and date("Ymd")<20120216 and (!$displayNotice)) {
	$displayNotice = true; ?>
<div class='et-box et-<?php if (date('Ymd')<20120211) echo 'info'; else echo 'warning'; ?>' style="margin-bottom: 15px;">
<div class='et-box-content' style="background: #<?php if (date('Ymd')<20120211) echo 'f7f4b4'; else echo 'ffcebe'; ?> url(./images/fees-small.png) no-repeat 18px 13px;">
	<span style="font-size: 14px;"><strong>Frais de scolarité</strong></span>
	<p style="padding-bottom: 5px; clear: both; margin-top: 7px;">La date limite de paiement est le <strong>15 février</strong> pour payer vos frais de scolarité sans pénalité.</p><p>Après cette date, des frais d'intérêts de 3% seront ajoutés à votre facture.</p>
</div></div>
<?php
}

/*
if ($_SERVER["HTTP_HOST"]!='localhost' and (!$display_feesNotice)) {
	?>
<div class='et-box et-bio'>
<div class='et-box-content'>
	<span style="font-size: 17px;"><strong>Inviter des gens</strong></span>
	<br style="clear: both;" />
	Faites découvrir Pilule à d'autres étudiants en recommandant le site.
	<div style="clear: both;"></div>
	<div style="margin-top: 10px; margin-bottom: 10px; width: 210px; position: relative; text-align: center; left: -55px;">
		<!--<a href="javascript:inviteFB();" class='icon-button facebook-icon'><span class='et-icon'><span>Inviter par Facebook</span></span></a>-->
		<div id="fb-root"></div><script src="https://connect.facebook.net/fr_FR/all.js#appId=102086416558659&amp;xfbml=1"></script><fb:like href="http://www.pilule.ca" send="true" layout="button_count" width="210" show_faces="false" action="recommend" font="lucida grande"></fb:like>
		</div>
		<div style="clear: both;"></div>
	<!--
		<textarea name="emails" id="emails" style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width: 145px;"></textarea>
			<span style="font-size: 8pt; color: gray; line-height: 10pt;">Adresses e-mail séparées par une virgule(,)</span>
		<a href="javascript:inviteFriends();" class='icon-button people-icon'><span class='et-icon'><span>Inviter</span></span></a><div style="clear: both;">-->
	<script language="javascript">
	function inviteFB () {
		FB.ui({
          method: 'send',
		  display: 'popup',
		  show_error: true,
		 // redirect_uri: '<?php echo site_url(); ?>welcome/fbinvitewindow/',
		  description: 'Pilule est un système de gestion des études pour les étudiants de l\'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l\'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité.',
          name: 'Invitation pour Pilule',
		  picture: 'https://www.pilule.ca/thumbnail.jpg',
          link: 'https://www.pilule.ca/joinfb/invitecode/<?php echo base64_encode($user['idul']); ?>'
			  }
          );
	}
	</script>
</div></div>
<?php
}
*/
if (isset($_SESSION['loading-errors']) and $_SESSION['loading-errors'] == 1) {
	?><div class='et-box et-info' style="margin-top: 15px;">
<div class='et-box-content' style="background: #f7f4b4 url(./images/file_broken-small.png) no-repeat 18px 13px;">
	<span style="font-size: 14px;"><strong>Données manquantes</strong></span>
	<p style="padding-bottom: 0px; clear: both; margin-top: 7px;">Des erreurs sont survenues durant le chargement des données depuis Capsule.</p>
	<p style="padding-bottom: 5px; clear: both; margin-top: 7px;">Certains modules de Pilule pourraient rencontrer des problèmes d'affichage.</p>
</div></div><?php
} else {
	if ($displayNotice == false) {
		$ads = array('schedule', 'mobile');
		if ($user['registration'] == true) $ads[] = 'registration';
		if ($user['faculty'] == 'Sciences de l\'éducation') $ads[] = 'fse-intranet';
		if ($user['faculty'] == 'Médecine') $ads[] = 'med-intranet';
		
		$selectedAd = rand(0,(count($ads)-1));
		switch ($ads[$selectedAd]) {
				case 'med-intranet':
					?>
	<div class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Intranet MED</strong></span>
		<br style="clear: both;" />
		<div style="width: 65px; margin-top: 10px; float: left;"><img src="<?php echo site_url(); ?>images/keychain.png" width="50" /></div>
		<div style="float: left; margin-top: 5px; width: 130px;">
		<p style="margin-top: 5px; margin-bottom: 0px;"><strong>Connexion auto</strong><br />Accédez directement à l'Intranet avec Pilule.</p>
		<?php
		$found = 0;
		if (isset($custom_dashboard)) {
			foreach ($modules as $module) {
				if ($module['module'] == 'med-intranet') {
					$found = 1;
					break;
				}
			}
		}
		if ($found == 0) { ?>
		<p style="margin-top: 0px; padding-top: 0px; margin-bottom: 0px;"><a href="javascript:editDashboard();">+&nbsp;Ajouter le module</a></p><?php } ?>
		</div><div style="clear: both;"></div>
	</div></div>
				<?php
				break;
				case 'fse-intranet':
					?>
	<div class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Intranet FSE</strong></span>
		<br style="clear: both;" />
		<div style="width: 65px; margin-top: 10px; float: left;"><img src="<?php echo site_url(); ?>images/keychain.png" width="50" /></div>
		<div style="float: left; margin-top: 5px; width: 130px;">
		<p style="margin-top: 5px; margin-bottom: 0px;"><strong>Connexion auto</strong><br />Accédez directement à l'Intranet avec Pilule.</p>
		<?php
		$found = 0;
		if (isset($custom_dashboard)) {
			foreach ($modules as $module) {
				if ($module['module'] == 'fse-intranet') {
					$found = 1;
					break;
				}
			}
		}
		if ($found == 0) { ?>
		<p style="margin-top: 0px; padding-top: 0px; margin-bottom: 0px;"><a href="javascript:editDashboard();">+&nbsp;Ajouter le module</a></p><?php } ?>
		</div><div style="clear: both;"></div>
	</div></div>
				<?php
				break;
				case 'registration':
					?>
	<div class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Inscription</strong></span>
		<br style="clear: both;" />
		<div style="width: 65px; margin-top: 10px; float: left;"><img src="<?php echo site_url(); ?>images/registration.png" width="50" /></div>
		<div style="float: left; margin-top: 5px; width: 130px;">
		<p style="margin-top: 5px; margin-bottom: 0px;"><strong>Choix de cours</strong><br />Faites votre sélection de cours avec Pilule.</p>
		<?php
		$found = 0;
		if (isset($custom_dashboard)) {
			foreach ($modules as $module) {
				if ($module['module'] == 'registration') {
					$found = 1;
					break;
				}
			}
		}
		if ($found == 0) { ?>
		<p style="margin-top: 0px; padding-top: 0px; margin-bottom: 0px;"><a href="javascript:addRegistrationModule();">+&nbsp;Ajouter le module</a></p><?php } ?>
		</div><div style="clear: both;"></div>
		<div style="font-size: 7pt; margin-top: 0px; margin-bottom: 10px; line-height: 9pt; border-top: 1px dotted silver; padding-top: 5px;">Note : module en version bêta et disponible pour un nombre limité d'utilisateurs</div>
	</div></div>
				<?php
				break;
				case 'mobile':
				?>
	<div class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Navigation mobile</strong></span>
		<br style="clear: both;" />
		<div style="width: 60px; height: 95px; margin-top: 10px; float: left; background-image: url(./images/iphone-bg.gif); background-repeat: no-repeat;"><img src="./images/iphone-2.gif" width="50" /></div>
		<div style="float: left; margin-top: 5px; width: 130px;">
		<p style="margin-top: 5px;">Accédez à Pilule depuis votre téléphone intelligent, à l'adresse :</p>
		<p style="text-align: center; font-weight: bold; margin: 0px; font-size: 10pt; color: black;">www.pilule.ulaval.ca</p>
		</div><div style="clear: both;"></div>
		<div style="font-size: 7pt; margin-top: 10px; border-top: 1px dotted silver; padding-top: 1px;">Version mobile optimisée pour iPhone</div>
	</div></div>
				<?php
			break;
		/*
			case 1:
			?>
	<div class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Relevé de notes</strong></span>
		<br style="clear: both;" />
		<div style="width: 65px; margin-top: 10px; float: left;"><img src="<?php echo site_url(); ?>images/report.png" width="50" /></div>
		<div style="float: left; margin-top: 5px; width: 130px;">
		<p style="margin-top: 5px;"><a href="<?php echo site_url(); ?>studies/report">Consultez votre relevé de notes</a> directement sur Pilule.</p>
		</div><div style="clear: both;"></div>
	</div></div><?php
		break;
		*/
		case 'schedule':
			?>
	<div class='et-box et-shadow' style="margin-top: 15px;">
	<div class='et-box-content'>
		<span style="font-size: 17px;"><strong>Horaire de cours</strong></span>
		<br style="clear: both;" />
		<div style="width: 65px; margin-top: 10px; float: left;"><img src="<?php echo site_url(); ?>images/export-schedule.png" width="50" /></div>
		<div style="float: left; margin-top: 5px; width: 130px;">
		<p style="margin-top: 5px;">Exportez votre <a href="<?php echo site_url(); ?>schedule/">horaire de cours</a> au format iCal (aussi compatible avec Google/Outlook)</p>
		</div><div style="clear: both;"></div>
	</div></div><?php
		break;
								  }
	}
}
?>

</div>
<h2 class="title">Recherche de cours</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
	<?php if (isset($response) and $response!='') { ?><div class="error-message"><?php
		if ($response=='unknown') echo "Aucun cours ne correspond à ce code...";
	?></div><?php } ?>
<form action="./registration/s_search" method="post" target="report-frame">
<table width="100%">
	<tbody>
		<tr>
			<th>Code du cours</th>
			<td><input type="text" style="width: 80px; font-size: 10pt; padding: 2px; text-transform: uppercase;" name="code" onkeyup="javascript:registrationObj.formatFieldCode(this);" />&nbsp;&nbsp;<span style="color: gray; font-size: 8pt;">Ex : GGR-1000</span></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><input type="submit" value="Chercher" /></td>
		</tr>
	</tbody>
</table>
<!--
<h3>Recherche par titre de cours</h3>
<table width="100%">
	<tbody>
		<tr>
			<th>Matière</th>
			<td><select name="subject">
			<option value=""> </option>
<option value="ACT">Actuariat</option>
<option value="AEE">Admin. et éval. en éducation</option>
<option value="ADM">Administration</option>
<option value="ADS">Administration scolaire</option>
<option value="APR">Affaires publiques et représ.</option>
<option value="AGC">Agro-économie</option>
<option value="AGF">Agroforesterie</option>
<option value="AGN">Agronomie</option>
<option value="ALL">Allemand</option>
<option value="AME">Aménagement (du territoire)</option>
<option value="ANM">Anatomie</option>
<option value="ANG">Anglais</option>
<option value="ANL">Anglais (langue)</option>
<option value="ANT">Anthropologie</option>
<option value="ARA">Arabe</option>
<option value="ARL">Archéologie</option>
<option value="ARC">Architecture</option>
<option value="GAD">Archivistique</option>
<option value="ANI">Art et science de l'animation</option>
<option value="ARV">Arts visuels</option>
<option value="BCM">Biochimie</option>
<option value="BIF">Bio-informatique</option>
<option value="BIO">Biologie</option>
<option value="BMO">Biologie cell. et moléculaire</option>
<option value="BVG">Biologie végétale</option>
<option value="BPH">Biophotonique</option>
<option value="CAT">Catéchèse</option>
<option value="CHM">Chimie</option>
<option value="CHN">Chinois</option>
<option value="CIN">Cinéma</option>
<option value="COM">Communication</option>
<option value="CTB">Comptabilité</option>
<option value="CNS">Consommation</option>
<option value="CSO">Counseling et orientation</option>
<option value="DES">Design graphique</option>
<option value="DDU">Développement durable</option>
<option value="DRI">Dével. rural intégré</option>
<option value="DID">Didactique</option>
<option value="DRT">Droit</option>
<option value="ERU">Économie rurale</option>
<option value="ECN">Économique</option>
<option value="EDC">Éducation</option>
<option value="EPS">Éducation physique</option>
<option value="ENS">Enseignement secondaire</option>
<option value="EER">Ens. en éthique et cult. rel.</option>
<option value="ENT">Entrepreneuriat</option>
<option value="ENV">Environnement</option>
<option value="EPM">Épidémiologie</option>
<option value="ERG">Ergothérapie</option>
<option value="ESP">Espagnol</option>
<option value="ESG">Espagnol (langue)</option>
<option value="ETH">Éthique</option>
<option value="EFN">Ethno. des franc. en Am. du N.</option>
<option value="ETN">Ethnologie</option>
<option value="EAN">Études anciennes</option>
<option value="FEM">Études féministes</option>
<option value="ETI">Études internationales</option>
<option value="GPL">Études pluridisciplinaires</option>
<option value="EXD">Examen de doctorat</option>
<option value="FOR">Foresterie</option>
<option value="FIS">Formation interdisc. en santé</option>
<option value="FPT">Formation prof. et technique</option>
<option value="FRN">Français</option>
<option value="FLE">Français lang. étr. ou seconde</option>
<option value="GAA">Génie agroalimentaire</option>
<option value="GAE">Génie agroenvironnemental</option>
<option value="GAL">Génie alimentaire</option>
<option value="GCH">Génie chimique</option>
<option value="GCI">Génie civil</option>
<option value="GPG">Génie de la plasturgie</option>
<option value="GEX">Génie des eaux</option>
<option value="GEL">Génie électrique</option>
<option value="GGL">Génie géologique</option>
<option value="GIN">Génie industriel</option>
<option value="GLO">Génie logiciel</option>
<option value="GMC">Génie mécanique</option>
<option value="GML">Génie métallurgique</option>
<option value="GMN">Génie minier</option>
<option value="GPH">Génie physique</option>
<option value="GGR">Géographie</option>
<option value="GLG">Géologie</option>
<option value="GMT">Géomatique</option>
<option value="GSO">Gestion des opérations</option>
<option value="GRH">Gestion des ressources hum.</option>
<option value="GSE">Gestion économique</option>
<option value="GSF">Gestion financière</option>
<option value="GIE">Gestion internationale</option>
<option value="HST">Histoire</option>
<option value="HAR">Histoire de l'art</option>
<option value="IFT">Informatique</option>
<option value="IED">Intervention éducative</option>
<option value="ITL">Italien</option>
<option value="JAP">Japonais</option>
<option value="JOU">Journalisme</option>
<option value="KIN">Kinésiologie</option>
<option value="LMO">Langue modernes</option>
<option value="LOA">Langues orientales anciennes</option>
<option value="LAT">Latin</option>
<option value="LNG">Linguistique</option>
<option value="LIT">Littérature</option>
<option value="MNG">Management</option>
<option value="MRK">Marketing</option>
<option value="MAT">Mathématiques</option>
<option value="MED">Médecine</option>
<option value="MDD">Médecine dentaire</option>
<option value="MDX">Médecine expérimentale</option>
<option value="MEV">Mesure et évaluation</option>
<option value="MQT">Méthodes quantitatives</option>
<option value="MCB">Microbiologie</option>
<option value="MSL">Muséologie</option>
<option value="MUS">Musique</option>
<option value="NRB">Neurobiologie</option>
<option value="NUT">Nutrition</option>
<option value="OCE">Océanographie</option>
<option value="ORT">Orthophonie</option>
<option value="PST">Pastorale</option>
<option value="PHA">Pharmacie</option>
<option value="PHC">Pharmacologie</option>
<option value="PHI">Philosophie</option>
<option value="PHS">Physiologie</option>
<option value="PHT">Physiothérapie</option>
<option value="PHY">Physique</option>
<option value="PLG">Phytologie</option>
<option value="PSA">Psychiatrie</option>
<option value="PSY">Psychologie</option>
<option value="PPG">Psychopédagogie</option>
<option value="RLT">Relations industrielles</option>
<option value="RUS">Russe</option>
<option value="SAT">Santé au travail</option>
<option value="SAC">Santé communautaire</option>
<option value="POL">Science politique</option>
<option value="SAN">Sciences animales</option>
<option value="SCR">Sciences des religions</option>
<option value="SBO">Sciences du bois</option>
<option value="SCG">Sciences géomatiques</option>
<option value="SHR">Sciences humaines et religions</option>
<option value="SIN">Sciences infirmières</option>
<option value="STA">Sciences, technologie aliments</option>
<option value="SVS">Service social</option>
<option value="SOC">Sociologie</option>
<option value="SLS">Sols</option>
<option value="STT">Statistique</option>
<option value="SIO">Système information organisat.</option>
<option value="TEN">Technologie éducative</option>
<option value="THT">Théâtre</option>
<option value="THL">Théologie</option>
<option value="TRE">Thèse, Recherche, Mémoire</option>
<option value="TXM">Toxicomanie</option>
<option value="TRD">Traduction</option>
<option value="TED">Troubles envahissants du dével.</option>
</select></td>
		</tr>
		<tr>
			<th>Titre du cours</th>
			<td><input type="text" style="width: 300px; font-size: 10pt; padding: 2px;" name="title" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><input type="submit" value="Chercher" /></td>
		</tr>
	</tbody>
</table>
-->
</form>
<style type="text/css">
.post-content .error-message {
	background-color: #a52c0f;
	padding: 8px 5px;
	-moz-border-radius: 5px;
	text-align: center;
	color: #fff;
	margin-bottom: 10px;
}

#content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

#content table th {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

#content table th, #content table td {
	padding: 10px;
	vertical-align: top;
}

#content table th {
	padding-top: 15px;
}

#content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

#content a.type:hover, #content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}
</style>
<div class="clear"></div></div>
<h2 class="title">Programme d'études<?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|studies,summary');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<br class="space" />
<div id="notice" style="margin-bottom: 20px; margin-top: 0px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>
<table>
	<tbody>
		<tr>
			<th>Programme</th>
			<td><?php echo $studies['program'] ; ?> (<?php echo $studies['diploma'] ; ?>)</td>
		</tr>
		<tr>
			<th>Cycle</th>
			<td><?php echo $studies['cycle'] ; ?></td>
		</tr>
		<tr>
			<th>Admission</th>
			<td><?php echo $studies['adm_semester'] ; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $studies['adm_type'] ; ?></td>
		</tr>
		<tr>
			<th>Majeure</th>
			<td><?php echo $studies['major'] ; ?></td>
		</tr>
		<?php if ($studies['concentrations']!=array()) { ?>
		<tr>
			<th>Concentration(s)</th>
			<td><?php echo implode('<br />', $studies['concentrations']); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table><br class="space" />
<h3>Détails sur l'étudiant</h3>
<table>
	<tbody>
		<tr>
			<th>Statut</th>
			<td><?php echo $studies['status'] ; ?></td>
		</tr>
		<tr>
			<th>Inscrit actuellement</th>
			<td><?php echo $studies['registered'] ; ?></td>
		</tr>
		<tr>
			<th>1ère session</th>
			<td><?php echo $studies['first_sem'] ; ?></td>
		</tr>
		<tr>
			<th>Dernière session</th>
			<td><?php echo $studies['last_sem'] ; ?></td>
		</tr>
	</tbody>
</table>
<style type="text/css">
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
	vertical-align: top;
	font-size: 11pt;
}

.post-content table th, .post-content table td {
	padding: 10px;
}

<?php if ($mobile==1) { ?>
.post-content table th {
	width: 100px;
	font-size: 9pt;
}

br.space {
	display: none;
}
<?php } ?>
</style>
<div class="clear"></div></div>
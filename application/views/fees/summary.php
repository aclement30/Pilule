<h2 class="title">État de compte<?php if ($user['idul'] != 'demo' and ((!isset($cap_offline)) or $cap_offline != 1)) { ?><a href="javascript:reloadData('data|fees');" class="link" style="margin-right: 15px;"><img src="<?php echo site_url(); ?>images/arrow_refresh.png" align="absmiddle" />&nbsp;Actualiser les données</a><?php } ?><div class="clear"></div></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div id="notice" style="margin-bottom: 20px;<?php if (isset($cap_offline) and $cap_offline == 1) echo 'display: block;'; ?>">Ces données sont extraites du système Capsule de l'Université Laval, en date du <?php echo currentDate($cache_date, 'd F Y'); ?> à <?php echo str_replace(":", "h", $cache_time); ?>.</div>

<?php
if (count($summary['semesters']!=0)) {
	$semester_date = explode(" ", $summary['semesters'][0]['name']);
	
	switch (strtolower($semester_date[0])) {
		case 'hiver':
			$deadline_payment = '15 février '.$semester_date[1];
			$deadline_date = $semester_date[1]."0215";
		break;
		case 'automne':
			$deadline_payment = '15 octobre '.$semester_date[1];
			$deadline_date = $semester_date[1]."1015";
		break;
		default:
			$deadline_payment = '15 juin '.$semester_date[1];
			$deadline_date = $semester_date[1]."0615";
		break;
	}
}
?><br class="space" />
<table class="student-info">
	<tbody>
		<tr>
			<th style="font-size: 10pt;">Numéro de client</th>
			<td><?php echo $summary['client_number'] ; ?></td>
		</tr>
		<tr>
			<th style="<?php if ($summary['balance']!='0,00' and date('Ymd')>$deadline_date) echo ' color: red;'; ?> font-size: 10pt;">Solde du compte</th>
			<td style="<?php if ($summary['balance']!='0,00' and date('Ymd')>$deadline_date) echo ' color: red;'; ?>"><?php echo $summary['balance'] ; ?> $</td>
		</tr>
		<?php if ($summary['balance']!='0,00') { ?>
		<tr>
			<th style="<?php if ($summary['balance']!='0,00' and date('Ymd')>=$deadline_date) echo ' color: #f90;'; ?> font-size: 10pt;">Date limite de paiement</th>
			<td style="<?php if ($summary['balance']!='0,00' and date('Ymd')>=$deadline_date) echo ' color: #f90;'; ?>"><?php echo $deadline_payment; ?></td>
		</tr><?php } ?>
	</tbody>
</table><br class="space" />
<h3>Frais de scolarité<?php if ($mobile!=1) echo' de la session - '; else echo ' - '; echo $summary['semesters'][0]['name']; ?></h3>
<?php
$semester = $summary['semesters'][0]; ?>
<table>
	<tbody>
		<tr>
		<th style="font-weight: bold; text-align: left; width: <?php if ($mobile!=1) echo 80; else echo 73; ?>%">Description</th>
		<th style="font-weight: bold; text-align: right; width: 50px;">Frais ($)</th>
	</tr>
	<?php
	foreach ($semester['fees'] as $fee) {
		if ($fee['type']=='fee') {
		?>
		<tr>
			<td><?php echo $fee['name']; ?></td>
			<td style="text-align: right;"><?php echo $fee['amount']; ?></td>
		</tr>
		<?php
		}
	}
	?>
	<tr>
		<td style="font-weight: bold; text-align: right;">Total</td>
		<td style="text-align: right; font-weight: bold;"><?php echo $semester['total_fees']; ?> $</td>
	</tr>
	<?php
	if ($semester['total_payments']!='0,00') { ?>
	<tr>
		<td style="font-weight: bold; text-align: right;">Paiements / crédits</td>
		<td style="text-align: right; font-weight: bold;"><?php echo $semester['total_payments']; ?> $</td>
	</tr>
	<?php } ?>
	<tr>
		<td style="font-weight: bold; text-align: right;<?php if ($semester['balance']=='0,00') echo ' color: green;'; else echo ' color: red;'; ?>">Solde à payer</td>
		<td style="text-align: right; font-weight: bold;<?php if ($semester['balance']=='0,00') echo ' color: green;'; else echo ' color: red;'; ?>"><?php echo $semester['balance']." $"; ?></td>
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

.student-info tbody th {
	width: 150px;
}
<?php } ?>
</style>
<div class="clear"></div></div>
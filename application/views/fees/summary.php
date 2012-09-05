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
?>
<div class="row-fluid">
    <div class="span7">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-th"></i>
								</span>
                <h5>Frais de scolarité - <?php echo $summary['semesters'][0]['name']; ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th style="font-weight: bold; text-align: left;">Description</th>
                        <th style="font-weight: bold; text-align: center; width: 20%;">Frais ($)</th>
                    </tr>
                    <?php
                    $semester = $summary['semesters'][0];
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
            </div>
        </div>
    </div>

    <div class="span5">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-th"></i>
								</span>
                <h5>Sommaire du compte</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align: center;">Numéro de client</th>
                        <th style="text-align: center;">Solde</th>
                        <th style="text-align: center;">Date limite de paiement</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align: center;"><?php echo $summary['client_number'] ; ?></td>
                        <td style="text-align: center; <?php if ($summary['balance']!='0,00' and date('Ymd')>$deadline_date) echo ' color: red;'; ?>"><?php echo $summary['balance'] ; ?> $</td>
                        <td style="text-align: center; <?php if ($summary['balance']!='0,00' and date('Ymd')>$deadline_date) echo ' color: red;'; ?>"><?php echo $deadline_payment; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div><!-- End of row-fluid -->
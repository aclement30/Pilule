<?php
if (substr(CURRENT_SEMESTER, 4, 2) == '01') {
    $deadline_payment = '15 février '.substr(CURRENT_SEMESTER, 0, 4);
    $deadline_payment_small = '15 fév.';
    $deadline_date = substr(CURRENT_SEMESTER, 0, 4)."0215";
} elseif (substr(CURRENT_SEMESTER, 4, 2) == '09') {
    $deadline_payment = '15 octobre '.substr(CURRENT_SEMESTER, 0, 4);
    $deadline_payment_small = '15 oct.';
    $deadline_date = substr(CURRENT_SEMESTER, 0, 4)."1015";
} elseif (substr(CURRENT_SEMESTER, 4, 2) == '05') {
    $deadline_payment = '15 juin '.substr(CURRENT_SEMESTER, 0, 4);
    $deadline_payment_small = '15 juin';
    $deadline_date = substr(CURRENT_SEMESTER, 0, 4)."0615";
}
?>

<div class="row-fluid">
    <div class="span12">
        <div class="widget-box widget-plain">
            <div class="widget-content center">
                <ul class="stats-plain">
                    <li>
                        <h4><?php echo number_format($account['balance'], 2); ?> $</h4>
                        <span>Solde du compte</span>
                    </li>
                    <li>
                        <h4><?php echo $deadline_payment_small; ?></h4>
                        <span>Date limite de paiement</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span7">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-list"></i>
								</span>
                <h5>Frais de scolarité - <?php echo convertSemester($summary['semester']); ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th style="font-weight: bold; text-align: left;">Description</th>
                        <th style="font-weight: bold; text-align: center; width: 20%;">Frais ($)</th>
                    </tr>
                    <?php
                    foreach ($summary['fees'] as $fee) {
                        ?>
                        <tr>
                            <td><?php echo $fee['name']; ?></td>
                            <td style="text-align: right;"><?php echo number_format($fee['amount'], 2); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td style="font-weight: bold; text-align: right;">Total</td>
                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($summary['total'], 2); ?> $</td>
                    </tr>
                    <?php
                    if ($summary['payments']!='0.00') { ?>
                    <tr>
                        <td style="font-weight: bold; text-align: right;">Paiements / crédits</td>
                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($summary['payments'], 2); ?> $</td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td style="font-weight: bold; text-align: right;<?php if ($summary['balance']=='0,00') echo ' color: green;'; else echo ' color: red;'; ?>">Solde à payer</td>
                        <td style="text-align: right; font-weight: bold;<?php if ($summary['balance']=='0,00') echo ' color: green;'; else echo ' color: red;'; ?>"><?php echo number_format($summary['balance'], 2)." $"; ?></td>
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
                        <td style="text-align: center;"><?php echo $account['account_number'] ; ?></td>
                        <td style="text-align: center; <?php if ($account['balance']!='0.00' and date('Ymd')>$deadline_date) echo ' color: red;'; ?>"><?php echo number_format($account['balance'], 2); ?> $</td>
                        <td style="text-align: center; <?php if ($account['balance']!='0.00' and date('Ymd')>$deadline_date) echo ' color: red;'; ?>"><?php echo $deadline_payment; ?></td>
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
									<i class="icon-signal"></i>
								</span>
                <h5>Répartition des frais connexes</h5>
            </div>
            <div class="widget-content">
                <div class="chart" style="height: 200px;"></div>
            </div>
        </div>
    </div>
</div><!-- End of row-fluid -->
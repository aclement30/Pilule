<div class="row-fluid" style="margin-top: 20px;">
    <div class="btn-group" style="float: right;">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <?php echo convertSemester($semester_date); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu pull-right">
            <?php foreach ($semesters as $semester2) {
            ?><li><a href="javascript:app.tuitions.displaySemester(<?php echo $semester2['semester']; ?>);"<?php if ($semester2['semester'] == $semester_date) echo ' style="font-weight: bold;"'; ?>><?php echo convertSemester($semester2['semester']); ?></a></li><?php
        } ?>
        </ul>
    </div>
    <div style="float: right; font-size: 8pt; color: grey; margin-right: 5px; margin-top: 5px;">Session affichée : </div>
    <div style="clear: both;"></div>
</div><!-- End of row-fluid -->

<?php if (!empty($semester)) { ?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-list"></i>
								</span>
                <h5>Frais de scolarité - <?php echo convertSemester($semester['semester']); ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th style="font-weight: bold; text-align: left;">Description</th>
                        <th style="font-weight: bold; text-align: center; width: 20%;">Frais ($)</th>
                    </tr>
                    <?php
                    foreach ($semester['fees'] as $fee) {
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
                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($semester['total'], 2); ?> $</td>
                    </tr>
                    <?php
                    if ($semester['payments']!='0.00') { ?>
                    <tr>
                        <td style="font-weight: bold; text-align: right;">Paiements / crédits</td>
                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($semester['payments'], 2); ?> $</td>
                    </tr>
                        <?php } ?>
                    <tr>
                        <td style="font-weight: bold; text-align: right;<?php if ($semester['balance']=='0.00') echo ' color: green;'; else echo ' color: red;'; ?>">Solde à payer</td>
                        <td style="text-align: right; font-weight: bold;<?php if ($semester['balance']=='0.00') echo ' color: green;'; else echo ' color: red;'; ?>"><?php echo number_format($semester['balance'], 2)." $"; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- End of row-fluid -->
<?php } else {
?>
<div class="row-fluid" style="padding-top: 15px;">

    <div class="hero-unit no-data span12">
        <div class="span1">&nbsp;</div>
        <div class="span3" style="text-align: right; padding-right: 15px;">
            <img src="./img/lego-man.png" alt="Lego Man" />
        </div>
        <div class="span7" style="padding-top: 40px;">
            <p class="lead">Aucune donnée enregistrée</p>
            Votre dossier Capsule ne contient aucune donnée pour cette page.
        </div>
        <div class="span1">&nbsp;</div>
        <div style="clear: both;"></div>
    </div>
</div>
<?php
}
?>
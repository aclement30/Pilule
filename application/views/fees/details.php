<div class="row-fluid">

<div class="semester-field">Session : <select name="semester" id="semester" onchange="javascript:app.fees.selectSemester(this.options[this.selectedIndex].value);">
	<?php
	foreach ($semesters as $semester => $name) {
		?><option value="<?php echo $semester; ?>"<?php if ($this->session->userdata('fees_current_semester') == $semester) echo ' selected="selected"'; ?>> <?php echo $name; ?></option><?php
	}
	?>
</select></div>
</div><!-- End of row-fluid -->

<?php
$semester = $fees;
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-th"></i>
								</span>
                <h5>Frais de scolarité - <?php echo $semester['name']; ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th style="font-weight: bold; text-align: left;">Description</th>
                        <th style="font-weight: bold; text-align: center; width: 10%;">Frais ($)</th>
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
            </div>
        </div>
    </div>
</div><!-- End of row-fluid -->
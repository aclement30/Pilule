<div class="row-fluid">
    <div class="request-description">
        Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.
    </div>
</div>

<?php
    if ( !empty( $semestersList ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $semestersList, 'selectedSemester' => $semester ) );
    endif;
?>

<?php if (!empty($semester)) { ?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
				<span class="icon"><i class="icon-list"></i></span>
                <h5>Frais de scolarité - <?php echo $this->App->convertSemester( $semester ); ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Description</th>
                            <th>Frais ($)</th>
                        </tr>
                        <?php foreach ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['fees'] as $fee ) : ?>
                            <tr>
                                <td><?php echo $fee[ 'name' ]; ?></td>
                                <td class="amount"><?php echo number_format( $fee['amount'], 2 ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'total' ], 2); ?> $</td>
                        </tr>
                        <?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'payments' ] != '0.00' ) : ?>
                            <tr>
                                <td>Paiements / crédits</td>
                                <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'payments' ], 2 ); ?> $</td>
                            </tr>
                        <?php endif; ?>
                        <tr class="<?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['balance'] == '0.00' ) echo 'amount-paid'; else echo 'amount-to-pay'; ?>">
                            <td>Solde à payer</td>
                            <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['balance'], 2 ) . ' $'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- End of row-fluid -->
<?php } else { ?>
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
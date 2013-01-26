<div class="request-description">
    Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.
</div>

<?php
    if ( !empty( $semestersList ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $semestersList, 'selectedSemester' => $semester ) );
    endif;
?>

<div class="table-panel not-expandable">
    <h4> <i class="icon-list"></i>Frais de scolarité - <?php echo $this->App->convertSemester( $semester ); ?></h4>
    <table class="table table-striped fees">
        <thead>
            <tr>
                <th>Description</th>
                <th>Frais ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['fees'] as $fee ) : ?>
                <tr>
                    <td><?php echo $fee[ 'name' ]; ?></td>
                    <td class="amount"><?php echo number_format( $fee['amount'], 2 ); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'total' ], 2); ?> $</td>
            </tr>
            <?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'payments' ] != '0.00' ) : ?>
                <tr>
                    <th>Paiements / crédits</th>
                    <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'payments' ], 2 ); ?> $</td>
                </tr>
            <?php endif; ?>
            <tr class="<?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['balance'] == '0.00' ) echo 'amount-paid'; else echo 'amount-to-pay'; ?>">
                <th>Solde à payer</th>
                <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['balance'], 2 ) . ' $'; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
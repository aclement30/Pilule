<div class="stats no-print">
    <div class="row-fluid">
        <div class="span2"></div>
        <div class="span4">
            <div class="stat">
                <h2><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'balance' ], 2 ); ?> $</h2>
                <h6>Solde du compte</h6>
            </div>
        </div>
        <div class="span4">
            <div class="stat">
                <h2><?php echo $deadline[ 'small' ]; ?></h2>
                <h6>Date limite de paiement</h6>
            </div>
        </div>
        <div class="span2"></div>
    </div>
</div>

<div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.</div>

<div class="row-fluid">
    <div class="span7">
        <div class="table-panel not-expandable">
            <h4> <i class="icon-list"></i>Frais de scolarité - <?php echo $this->App->convertSemester( CURRENT_SEMESTER ); ?></h4>
            <table class="table table-striped fees">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Frais ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'fees' ] as $fee ) : ?>
                        <tr>
                            <td><?php echo $fee[ 'name' ]; ?></td>
                            <td class="amount"><?php echo number_format( $fee[ 'amount' ], 2 ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'total' ], 2 ); ?> $</td>
                    </tr>
                    <?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['payments'] != '0.00' ) : ?>
                        <tr>
                            <th>Paiements / crédits</th>
                            <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'payments' ], 2 ); ?> $</td>
                        </tr>
                    <?php endif; ?>
                    <tr class="<?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['balance'] == '0,00' ) echo 'amount-paid'; else echo 'amount-to-pay'; ?>">
                        <th>Solde à payer</th>
                        <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'balance' ], 2 ) . ' $'; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="span5">
        <div class="table-panel not-expandable">
            <h4> <i class="icon-th"></i>Sommaire du compte</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Numéro de client</th>
                        <th>Solde</th>
                        <th>Date limite de paiement</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="<?php if ( $tuitions[ 'TuitionAccount' ][ 'balance' ] != '0.00' && date( 'Ymd' ) > $deadline[ 'date' ] ) echo 'late'; ?>">
                        <td><?php echo $tuitions[ 'TuitionAccount' ][ 'account_number' ] ; ?></td>
                        <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'balance' ], 2 ); ?> $</td>
                        <td><?php echo $deadline[ 'long' ]; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--
    <div class="span5 no-print">
        <div class="table-panel expandable">
            <h4> <i class="icon-signal"></i>Répartition des frais connexes</h4>
            <div class="widget-content">
                <div class="chart" style="height: 200px;"></div>
            </div>
        </div>
    </div>
    -->

</div><!-- End of row-fluid -->

<script type="text/javascript">
    var chartData = [<?php echo $chartData; ?>];
</script>
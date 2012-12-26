<div class="row-fluid no-print">
    <div class="span12">
        <div class="widget-box widget-plain">
            <div class="widget-content center">
                <ul class="stats-plain">
                    <li>
                        <h4><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'balance' ], 2 ); ?> $</h4>
                        <span>Solde du compte</span>
                    </li>
                    <li>
                        <h4><?php echo $deadline[ 'small' ]; ?></h4>
                        <span>Date limite de paiement</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.</div>
</div>

<div class="row-fluid">
    <div class="span7">
        <div class="widget-box">
            <div class="widget-title">
				<span class="icon"><i class="icon-list"></i></span>
                <h5>Frais de scolarité - <?php echo $this->App->convertSemester( CURRENT_SEMESTER ); ?></h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Description</th>
                            <th>Frais ($)</th>
                        </tr>
                        <?php foreach ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'fees' ] as $fee ) : ?>
                            <tr>
                                <td><?php echo $fee[ 'name' ]; ?></td>
                                <td class="amount"><?php echo number_format( $fee[ 'amount' ], 2 ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'total' ], 2 ); ?> $</td>
                        </tr>
                        <?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['payments'] != '0.00' ) : ?>
                            <tr>
                                <td>Paiements / crédits</td>
                                <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'payments' ], 2 ); ?> $</td>
                            </tr>
                        <?php endif; ?>
                        <tr class="<?php if ( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ]['balance'] == '0,00' ) echo 'amount-paid'; else echo 'amount-to-pay'; ?>">
                            <td>Solde à payer</td>
                            <td><?php echo number_format( $tuitions[ 'TuitionAccount' ][ 'Semester' ][ 0 ][ 'balance' ], 2 ) . ' $'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="span5">
        <div class="widget-box">
            <div class="widget-title">
				<span class="icon"><i class="icon-th"></i></span>
                <h5>Sommaire du compte</h5>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
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
    </div>

    <div class="span5 no-print">
        <div class="widget-box">
            <div class="widget-title">
				<span class="icon"><i class="icon-signal"></i></span>
                <h5>Répartition des frais connexes</h5>
            </div>
            <div class="widget-content">
                <div class="chart" style="height: 200px;"></div>
            </div>
        </div>
    </div>
</div><!-- End of row-fluid -->

<script type="text/javascript">
    var chartData = [<?php echo $chartData; ?>];
</script>
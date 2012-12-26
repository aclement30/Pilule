<div class="row-fluid">
    <div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date( 'd/m/Y, à H:i', $timestamp ); ?>.</div>
</div>

<div class="row-fluid">
	<div class="span12">
        <div class="widget-box">
            <div class="widget-title">
				<span class="icon"><i class="icon-signal"></i></span>
                <h5>Répartition des frais connexes</h5>
            </div>
            <div class="widget-content">
                <div class="chart" style="height: 600px;"></div>
            </div>
        </div>
    </div>
</div><!-- End of row-fluid -->

<?php
	$series = array();
	$chartData = array();
	$n = 0;
	$notCountedList = array( '' );

	foreach ( $tuitions as $semester ) {
		foreach( $semester[ 'Semester' ][ 'fees' ] as $fee ) {
			$series[ $fee[ 'name' ] ][ $semester[ 'Semester' ][ 'semester' ] ] = $fee[ 'amount' ];
		}
		/*
        $chartData[] = '[' . $n . ', ' . $semester['gpa'] . ']';
        $chart_x_axis[] = '[' . $n . ', \'' . convertSemester($semester['semester'], true) . '\']';

        if ($semester['gpa'] < $smallest) $smallest = $semester['gpa'];
        if ($semester['gpa'] > $highest) $highest = $semester['gpa'];
		*/
        $n++;
    }

    pr($series);
?>
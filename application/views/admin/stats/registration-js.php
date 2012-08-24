var chart;
chart = new Highcharts.Chart({
    chart: {
        renderTo: 'step1_programs',
        defaultSeriesType: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: [<?php
        $number = 0;
        foreach ($step1_programs as $name => $program) {
            echo '\''.$name.'\'';
            $number++;
            if ($number != count($step1_programs)) echo ',';
        } ?>]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Utilisateurs'
        }
    },
    legend: {
        layout: 'vertical',
        backgroundColor: '#FFFFFF',
        align: 'left',
        verticalAlign: 'top',
        x: 100,
        y: 70,
        floating: true,
        shadow: true
    },
    tooltip: {
        formatter: function() {
            return ''+
                this.x +': '+ this.y;
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
        series: [{
        name: 'Total',
        data: [<?php
        $number = 0;
        foreach ($step1_programs as $name => $program) {
            echo $program['users'];
            $number++;
            if ($number != count($step1_programs)) echo ',';
        } ?>]

    }, {
        name: 'Liste des cours',
        data: [<?php
        $number = 0;
        foreach ($step1_programs as $name => $program) {
            echo $program['registration'];
            $number++;
            if ($number != count($step1_programs)) echo ',';
        } ?>]

    }, {
        name: 'Inscription',
        data: [<?php
        $number = 0;
        foreach ($step1_programs as $name => $program) {
            echo $program['result'];
            $number++;
            if ($number != count($step1_programs)) echo ',';
        } ?>]

    }]
});

$('#registration-stats').html('<h4 style="float: left;">1. Liste des cours : <?php echo count($step1_users); ?> utilisateurs</h4><h4 style="float: right;">Moyenne : <?php
$total = 0;
foreach ($step1_users as $user2) {
	$total += $user2['time'];
}
echo round(($total/count($step1_users)), 1);
?> visites</h4><div style="clear: both;"></div><h4 style="float: left;">2. Inscription : <?php echo count($step3_users); ?> utilisateurs (<?php echo round((count($step3_users)/count($step1_users)*100), 1); ?> %)</h4><h4 style="float: right;">Moyenne : <?php
$total = 0;
foreach ($step3_users as $user2) {
	$total += $user2['time'];
}
echo round(($total/count($step3_users)), 1);
?> utilisations</h4><div style="clear: both;"></div>');
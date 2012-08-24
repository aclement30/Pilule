var pages; // globally available
var colors = Highcharts.getOptions().colors,
categories = [<?php
                 $n = 0;
                 foreach ($faculties as $faculty => $number) {
                    print "'".addslashes($faculty)."'";
                    $n++;
                    if ($n!=count($faculties)) echo ', ';
                 } ?>],
name = 'Facultés',
data = [<?php
        $number1 = 0;
        foreach ($faculties as $faculty => $number2) { ?>{ 
        y: <?php echo round($number2/$total*100, 2); ?>,
        color: colors[<?php echo $number1; ?>],
        drilldown: {
            name: '<?php echo $faculty; ?>',
            categories: [<?php
                 $n = 0;
                 foreach ($programs[$faculty] as $program => $number3) {
                     switch ($program) {
                         case 'B études int.-langues modernes':
                            print "'B-LMO'";
                         break;
                         case 'B sciences infirmières':
                            print "'B-SIN'";
                         break;
                         case 'B ens. éduc. physique & santé':
                            print "'B-EDP'";
                         break;
                         case 'B enseignement secondaire':
                            print "'B-ENS'";
                         break;
                         case 'B éduc. préscol.-ens. primaire':
                            print "'B-ENP'";
                         break;
                         case 'B communication publique':
                            print "'B-COM'";
                         break;
                         case 'B administration des affaires':
                            print "'BAA'";
                         break;
                         case 'M administration des affaires':
                            print "'MBA'";
                         break;
                         
                         default:
                            print "'".addslashes($program)."'";
                        break;
                     }
                    $n++;
                    if ($n!=count($programs[$faculty])) echo ', ';
                 } ?>],
            data: [<?php
                 $n = 0;
                 foreach ($programs[$faculty] as $program => $number3) {
                    echo $number3;
                    $n++;
                    if ($n!=count($programs[$faculty])) echo ', ';
                 } ?>],
            color: colors[<?php echo $number1; ?>]
        }
    }<?php
        $number1++;
        if ($number1!=count($faculties)) echo ', ';
        } ?>];


// Build the data arrays
var browserData = [];
var versionsData = [];
for (var i = 0; i < data.length; i++) {

// add browser data
browserData.push({
    name: categories[i],
    y: data[i].y,
    color: data[i].color
});

// add version data
for (var j = 0; j < data[i].drilldown.data.length; j++) {
    var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
    versionsData.push({
        name: data[i].drilldown.categories[j],
        y: data[i].drilldown.data[j],
        color: Highcharts.Color(data[i].color).brighten(brightness).get()
    });
}
}

// Create the chart
chart = new Highcharts.Chart({
chart: {
    renderTo: 'programs', 
    type: 'pie'
},
title: {
    text: ''
},
yAxis: {
    title: {
        text: 'Pourcentage des programmes'
    }
},
plotOptions: {
    pie: {
        shadow: false
    }
},
tooltip: {
    formatter: function() {
        return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
    }
},
series: [{
    name: 'Facultés',
    data: browserData,
    size: '60%',
    dataLabels: {
        formatter: function() {
            return this.y > 5 ? this.point.name : null;
        },
        color: 'white',
        distance: -30
    }
}, {
    name: 'Programmes',
    data: versionsData,
    innerSize: '60%',
    dataLabels: {
        formatter: function() {
            // display only if larger than 1
            return this.y > 1.5 ? '<b>'+ this.point.name +':</b> '+ this.y +' %'  : null;
        }
    }
}]
});
var monthly_visits; // globally available
chart1 = new Highcharts.Chart({
   chart: {
	  renderTo: 'two-weeks-visits',
	  type: 'column'
   },
   title: {
	  text: ''
   },
   xAxis: {
	  categories: [<?php
				   $n = 0;
				   foreach ($logins['days'] as $day => $number) {
					  echo substr($day, 6, 2);
					  $n++;
					  if ($n!=16) echo ', ';
				   } ?>]
   },
   yAxis: {
	  title: {
		 text: 'Nombre'
	  }
   },
   series: [{
	  name: 'Connexions',
	  data: [<?php echo implode(', ', $logins['days']); ?>]
   }, {
	  name: 'Chargements données',
	  data: [<?php echo implode(', ', $loadings['days']); ?>]
   }]
});

var monthly_visits; // globally available
chart1 = new Highcharts.Chart({
   chart: {
	  renderTo: 'daily-logins',
	  type: 'area',
	  zoomType: 'xy'
   },
   title: {
	  text: ''
   },
   xAxis: {
	  title: {
		 text: 'Heures'
	  },
	  categories: [0, 1, 2,3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
   },
   yAxis: {
	  title: {
		 text: 'Visites (%)'
	  }
   },
   series: [{
	  name: 'Connexions',
	  data: [<?php echo implode(', ', $visits['hours']); ?>]
   }]
});

var pages; // globally available
var colors = Highcharts.getOptions().colors,
  categories = ['Dossier scol.', 'Horaire', 'Frais $', 'Services', 'Divers'],
  name = 'Browser brands',
  data = [{ 
		  y: <?php echo round($sections['studies']/$total*100, 2); ?>,
		  color: colors[0],
		  drilldown: {
			  name: 'Dossier scolaire',
			  categories: ['Sommaire', 'Attestation', 'Formation', 'Relevé notes'],
			  data: [<?php echo $pages['studies-summary']; ?>, <?php echo $pages['studies-details-attestation']; ?>, <?php echo $pages['studies-details-education']; ?>, <?php echo $pages['studies-report']; ?>],
			  color: colors[0]
		  }
	  }, {
		  y: <?php echo round($sections['schedule']/$total*100, 2); ?>,
		  color: colors[1],
		  drilldown: {
			  name: 'Horaire',
			  categories: ['Horaire', 'Liste des cours'],
			  data: [<?php echo $pages['schedule-timetable']; ?>, <?php echo $pages['schedule-courses']; ?>],
			  color: colors[1]
		  }
	  }, {
		  y: <?php echo round($sections['fees']/$total*100, 2); ?>,
		  color: colors[2],
		  drilldown: {
			  name: 'Frais scolarité',
			  categories: ['Sommaire', 'Détails'],
			  data: [<?php echo $pages['fees-summary']; ?>, <?php echo $pages['fees-details']; ?>],
			  color: colors[2]
		  }
	  }, {
		  y: <?php echo round($sections['redirect']/$total*100, 2); ?>,
		  color: colors[3],
		  drilldown: {
			  name: 'Services',
			  categories: ['Abo. RTC', 'Capsule', 'Elluminate', 'Exchange', 'Pixel', 'ENA', 'WebCT'],
			  data: [<?php if (isset($pages['redirect-bus'])) echo $pages['redirect-bus']; else echo 0; ?>, <?php echo $pages['redirect-capsule']; ?>, <?php echo $pages['redirect-elluminate']; ?>, <?php echo $pages['redirect-exchange']; ?>, <?php echo $pages['redirect-pixel']; ?>, <?php echo $pages['redirect-portailcours']; ?>, <?php echo $pages['redirect-webct']; ?>],
			  color: colors[3]
		  }
	  }, {
		  y: <?php echo round($sections['others']/$total*100, 2); ?>,
		  color: colors[4],
		  drilldown: {
			  name: 'Autres',
			  categories: ['Inscription', 'Phising'],
			  data: [<?php echo $pages['registration-courses']; ?>, <?php if (isset($pages['phishing-email'])) echo $pages['phishing-email']; ?>],
			  color: colors[4]
		  }
	  }];


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
	  renderTo: 'top-pages', 
	  type: 'pie'
  },
  title: {
	  text: ''
  },
  yAxis: {
	  title: {
		  text: 'Pourcentage des pages vues'
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
	  name: 'Sections',
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
	  name: 'Pages',
	  data: versionsData,
	  innerSize: '60%',
	  dataLabels: {
		  formatter: function() {
			  // display only if larger than 1
			  return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ this.y +' %'  : null;
		  }
	  }
  }]
});
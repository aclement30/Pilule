<h2 class="title">Statistiques des visites</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3>Visites 15 jours</h3>
<div id="two-weeks-visits"></div>
<script language="javascript">
var monthly_visits; // globally available
$(document).ready(function() {
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
   });
</script>
<h3>Connexions pendant la journée</h3>
<div id="daily-logins"></div>
<script language="javascript">
var monthly_visits; // globally available
$(document).ready(function() {
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
   });
</script>
<h3>Pages les plus visitées</h3>
<div id="top-pages"></div>
<script language="javascript">
var pages; // globally available
$(document).ready(function() {
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
					data: [<?php echo $pages['registration-courses']; ?>, <?php echo $pages['phishing-email']; ?>],
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
   });
</script>
<style type="text/css" media="screen">
.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	text-align: left;
	font-weight: bold;
	font-size: 11pt;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

.post-content table th.left, .post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}

#notice {
	background-color: silver;
	padding: 7px 10px;
	font-size: 8pt;
	margin-top: 10px;
	display: none;
}
</style>
<style type="text/css" media="print">
body {
	margin: 0px;
	font-family: Helvetica, Arial;
	font-size: 10pt;
}

#page {
	width: 100%;
}

#header, #header-bottom, a.link, a.refresh, #footer, #sidebar {
	display: none;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	border: 1px solid silver;
	padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
}

.post-content table th {
	text-align: left;
	font-weight: normal;
	text-transform: uppercase;
	border-bottom: 2px solid gray;
	padding: 10px;
	vertical-align: top;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
	text-transform: none;
}

.post-content table th.left, .post-content table td {
	padding: 10px;
	vertical-align: top;
	border-bottom: 1px solid silver;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}

#notice {
	background-color: none;
	padding: 0px;
	font-size: 7pt;
	margin-bottom: 25px;
	color: #999;
}

h1 {
	margin-bottom: 10px;
}
</style>
<style type="text/css">
<?php if ($mobile==1) { ?>
.post-content table th {
	width: 100px;
	font-size: 9pt;
}

br.space {
	display: none;
}
<?php } ?>
</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
<h2 class="title">Statistiques des utilisateurs</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3>Facultés et programmes</h3>
<div id="programs"></div>
<script language="javascript">
var pages; // globally available
$(document).ready(function() {
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
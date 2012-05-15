<h2 class="title">Statistiques des inscriptions</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h4 style="float: left;">1. Liste des cours : <?php echo count($step1_users); ?> utilisateurs</h4>
<h4 style="float: right;">Moyenne : <?php
$total = 0;
foreach ($step1_users as $user2) {
	$total += $user2['time'];
}
echo round(($total/count($step1_users)), 1);
?> visites</h4><div style="clear: both;"></div>
<h4 style="float: left;">2. Inscription : <?php echo count($step3_users); ?> utilisateurs (<?php echo round((count($step3_users)/count($step1_users)*100), 1); ?> %)</h4>
<h4 style="float: right;">Moyenne : <?php
$total = 0;
foreach ($step3_users as $user2) {
	$total += $user2['time'];
}
echo round(($total/count($step3_users)), 1);
?> utilisations</h4><div style="clear: both;"></div>
<div id="step1_programs" style="margin-top: 20px;"></div>
<script language="javascript">
var chart;
	$(document).ready(function() {
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
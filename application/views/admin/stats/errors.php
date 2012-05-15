<h2 class="title">Statistiques des erreurs</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3>Erreurs les plus fréquentes</h3>
<div id="top-errors"></div>
<script language="javascript">
var top_errors; // globally available
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'top-errors',
            type: 'column'
         },
         title: {
            text: ''
         },
         xAxis: {
            categories: [<?php
						 $n = 0;
						 foreach ($errors as $error => $number) {
							 switch ($error) {
								case 'credentials':
								 	print "'Connexion'";
								break;
								case '':
								 	print "'".$error."'";
								break;
								case '':
								 	print "'".$error."'";
								break;
								case '':
								 	print "'".$error."'";
								break;
								case '':
								 	print "'".$error."'";
								break;
								case '':
								 	print "'".$error."'";
								break;
								case '':
								 	print "'".$error."'";
								break;
								case 'server-connection':
								 	print "'Serveur Capsule'";
								break;
								default:
									print "'".ucfirst(str_replace("-", " ", str_replace(" : parsing error", "", $error)))."'";
								break;
							 }
							$n++;
							if ($n!=count($errors)) echo ', ';
						 } ?>],
			labels: {
							rotation: -45,
							align: 'right',
							style: {
								 font: 'normal 13px Verdana, sans-serif'
							}
						}
         },
         yAxis: {
            title: {
               text: 'Pourcentage'
            }
         },
         series: [{
            name: 'Erreurs',
            data: [<?php echo implode(', ', $errors); ?>]
         }]
      });
   });
</script>
<h3>Erreurs 15 derniers jours</h3>
<div id="daily-errors"></div>
<script language="javascript">
var monthly_visits; // globally available
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'daily-errors',
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
            name: 'Erreurs',
            data: [<?php echo implode(', ', $daily_errors['days']); ?>]
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
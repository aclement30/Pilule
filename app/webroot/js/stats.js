// JavaScript Document
var statsObj = {
	visitsCharts: function (twoWeeksVisits, dailyLogins) {
		chart1 = new Highcharts.Chart({
			 chart: {
				renderTo: 'two-weeks-visits',
				type: 'column'
			 },
			 title: {
				text: ''
			 },
			 xAxis: {
				categories: [twoWeeksVisits.catgories]
			 },
			 yAxis: {
				title: {
				   text: 'Nombre'
				}
			 },
			 series: [{
				name: 'Connexions',
				data: [twoWeeksVisits.serie1]
			 }, {
				name: 'Chargements données',
				data: [twoWeeksVisits.serie2]
			 }]
		  });
		chart2 = new Highcharts.Chart({
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
            data: [dailyLogins]
         }]
      });
	}
};
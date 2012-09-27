var schedule = {
    controllerURL: './schedule/',
    object:        'schedule',

    // Affichage de l'horaire de cours pour un semestre
    displaySemester: function (semester) {
        document.location.hash = '#!/schedule/'+semester;
    },

    // Téléchargement de l'horaire au format iCal
    download: function (semester) {
        // Notification à Google Analytics d'un téléchargement de l'horaire
        _gaq.push(['_trackEvent', 'Schedule', 'Download', 'Téléchargement de l\'horaire']);

        $('#frame').attr('src', './schedule/ical_download/'+semester);
        $('#frame').load(function() {
            //alert('calendar loaded');
        });
    }
}

addChild(app, 'schedule', schedule);

// JavaScript Document
var scheduleObj = {
	currentPeriod: '',
	selectSemester: function (semester) {
		document.location.hash = '#!/schedule/timetable/'+semester;
	},
	selectCoursesSemester: function (semester) {
		document.location.hash = '#!/schedule/courses/'+semester;
	},
	changePeriod: function (period) {
		$('#period-'+currentPeriod).hide();
		this.currentPeriod = period;
		$('#period-'+currentPeriod).fadeIn();
	},
	exportSchedule: function (semester) {
		var src = './schedule/w_export/'+semester;
		var popupHeight = 250;
		$.modal('<iframe src="' + src + '" height="'+popupHeight+'" width="400" style="border:0;" onload="javascript:stopLoading();">', {
			containerCss:{
				backgroundColor:"#fff",
				borderColor:"#fff",
				height: popupHeight,
				padding: 0,
				width: 400
			},
			overlayClose:true
		});
	},
	askFBAuth: function () {
		var src = './schedule/w_askFBAuth/';
		var popupHeight = 250;
		$.modal('<iframe src="' + src + '" height="'+popupHeight+'" width="450" style="border:0;" onload="javascript:stopLoading();">', {
			containerCss:{
				backgroundColor:"#fff",
				borderColor:"#fff",
				height: popupHeight,
				padding: 0,
				width: 450,
				modal: true
			}
		});
	},
	unlinkFBAccount: function () {
		!sendData('GET','./schedule/s_unlinkFB', '');
	},
	selectFriendlist: function (id) {
		$('#friendlist-'+id).toggleClass('selected');
	}
};
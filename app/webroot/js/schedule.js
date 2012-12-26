if ( !app ) {
    var app = {};
}

app.Schedule = {
    controllerURL: '/schedule/',
    object:        'schedule'
};

// Display semester schedule
app.Schedule.displaySemester = function ( e ) {
	if ( e.currentTarget ) {
		e.preventDefault;

		// Param is an event, retrieve the semester
		document.location = '/schedule/' + $( this ).data( 'semester' );
	} else {
    	document.location = '/schedule/' + semester;
    }

    return false;
};

app.Schedule.display = function ( calendar ) {
    $( '#calendar' ).fullCalendar( {
        header: 		{
            left: 	'prev, next',
            center: 'title',
            right: 	''
        },
        firstDay:   	1,
        defaultView :   'agendaWeek',
        allDaySlot:     false,
        firstHour:      8,
        minTime:        8,
        maxTime:        22,
        weekends:   	false,
        year:           calendar.startYear,
        month:          calendar.startMonth,
        timeFormat: 	'H(:mm)', // uppercase H for 24-hour clock
        axisFormat: 	'H:mm',
         buttonText: 	{
            prev: '',
            next: ''
        },
        titleFormat:    {
            month:  'MMMM yyyy',
            week: 	"d[ MMM][ yyyy]{ '&#8212;' d MMM. yyyy}",
            day: 	'dddd, d MMM. yyyy'
        },
        eventRender: 	function( event, element ) {
            element.find( '.fc-event-time' ).append( ' ' + event.code );
            var description = '<br /><div style="margin-top: 5px;"><i class="icon-map-marker icon-white"></i> <span>' + event.location + '</span></div>';
            if ( event.teacher != '' ) description += '<div style="margin-bottom: 5px; margin-top:  5px;"><i class="icon-user icon-white"></i> <span>' + event.teacher + '</span></div>';
            element.find( '.fc-event-title' ).append( description );
        },
        monthNamesShort:    [ 'janv', 'fév', 'mars', 'avril', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc' ],
        dayNamesShort:      [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
        dayNames:           [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
        columnFormat:       {
            month: 	'ddd',    // Mon
            week: 	'ddd. d', // Mon 9/7
            day: 	''  // Nothing
        },
        height: 		650,
        events: 		calendar.events
    } );

	// If no other courses for this semester, hide right column
	if ( calendar.otherCourses == 0 ) {
		//$( '.widget-content .panel-right' ).hide();
		//$( '.widget-content .panel-left' ).css( 'width', '100%' );
	}

    //setTimeout(displayCalendar, 100);
};

// Download schedule in iCal format
app.Schedule.download = function ( semester ) {
    // Notify Google Analytics of Schedule-download action
    _gaq.push(['_trackEvent', 'Schedule', 'Download', 'Téléchargement de l\'horaire']);

    // Download schedule iCal file
    $( '#frame' ).attr( 'src', app.Schedule.controllerURL + 'ical_download/' + semester );
};

app.Schedule.init = function () {
	// Check if calendar data exists
	if ( typeof timetable != 'undefined' ) {
		app.Schedule.display( timetable );
	}

	$( '.semesters-dropdown ul li a' ).on( 'click', app.Schedule.displaySemester );
};

$( document ).ready( app.Schedule.init );

/*
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
};*/
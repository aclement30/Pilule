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
		document.location = '/horaire/' + $( this ).data( 'semester' );
	} else {
    	document.location = '/horaire/' + semester;
    }

    return false;
};

app.Schedule.displayPreviousWeek = function ( e ) {
	var container = '#agenda';

	if ( $( window ).width() <= 660 ) {
		container = '#mobile-agenda';
	}

	var currentWeek = parseInt( $( container + ' .week.current' ).data( 'week' ) );
	currentWeek--;

	// Display previous week if exists
	if ( $( container + ' .week.week' + currentWeek ).length != 0 ) {
		$( container + ' .week.current' ).fadeOut( 'fast', function(){
			$( container + ' .week.current' ).removeClass( 'current' );
			$( container + ' .week.week' + currentWeek ).fadeIn().addClass( 'current' );

			$( '.calendar-header .dates h4' ).html( $( container + ' .week.week' + currentWeek ).data( 'title' ) );
		} );
	}
};

app.Schedule.displayNextWeek = function ( e ) {
	var container = '#agenda';

	if ( $( window ).width() <= 660 ) {
		container = '#mobile-agenda';
	}

	var currentWeek = parseInt( $( container + ' .week.current' ).data( 'week' ) );
	currentWeek++;

	// Display previous week if exists
	if ( $( container + ' .week.week' + currentWeek ).length != 0 ) {
		$( container + ' .week.current' ).fadeOut( 'fast', function(){
			$( container + ' .week.current' ).removeClass( 'current' );
			$( container + ' .week.week' + currentWeek ).fadeIn().addClass( 'current' );

			$( '.calendar-header .dates h4' ).html( $( container + ' .week.week' + currentWeek ).data( 'title' ) );
		} );
	}
};

// Download schedule in iCal format
app.Schedule.download = function ( semester ) {
    // Notify Google Analytics of Schedule-download action
    _gaq.push(['_trackEvent', 'Schedule', 'Download', 'Téléchargement de l\'horaire']);

    // Download schedule iCal file
    $( '#external-frame' ).attr( 'src', app.Schedule.controllerURL + 'ical_download/' + semester );
};

app.Schedule.init = function () {
	$( '.main' ).on( 'click', '.semesters-dropdown ul li a', app.Schedule.displaySemester );

	$( '.calendar-header .semesters-dropdown.compact' ).appendTo( '.main .action-buttons .buttons' );

	if ( $( window ).width() <= 480 ) {		
		if ( $( '.main .no-data' ).length == 0 )
			$( '.calendar-header .semesters-dropdown' ).not( '.compact' ).hide();
	}

	var tableCells = $( '#agenda table tbody td.class' );

	$( '#agenda table tbody td.class .inside' ).popover();

	$( '.main' ).on( 'click', '.calendar-header .js-prec-calendar', app.Schedule.displayPreviousWeek );
	$( '.main' ).on( 'click', '.calendar-header .js-next-calendar', app.Schedule.displayNextWeek );
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
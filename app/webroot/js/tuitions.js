if ( !app ) {
    var app = {};
}

app.Tuitions = {
    controllerURL: './fees/',
    object:        'fees'
};

app.Tuitions.displaySemester = function ( e ) {
    if ( e.currentTarget ) {
        e.preventDefault;

        // Param is an event, retrieve the semester
        if ( $( e.currentTarget ).is( 'select' ) ) {
            document.location = '/tuitions/details/' + $( e.currentTarget ).val();
        } else {
            document.location = '/tuitions/details/' + $( this ).data( 'semester' );
        }
    }

    return false;
};

app.Tuitions.displaySummaryGraph = function ( data ) {
	var displayChart = function () {
        var pie = $.plot( $( '.chart' ), data , {
            series: {
                pie: {
                    show: 		true,
                    radius: 	3/4,
                    label: 		{
                        show: 		true,
                        radius: 	3/4,
                        formatter: 	function( label, series ) {
                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + Math.round( series.percent ) + '%</div>';
                        },
                        background: {
                            opacity: 0.5,
                            color: 	'#000'
                        }
                    },
                    innerRadius: 0.2
                },
                legend: {
                    show: false
                }
            }
        });
    };

    // Wait until the refresh effect end so the chart is displayed and can be filled
    setTimeout( displayChart, 100 );
};

app.Tuitions.init = function () {
	// Check if chart data exists
	if ( typeof chartData != 'undefined' ) {
		app.Tuitions.displaySummaryGraph( chartData );
	}

    $( '.main' ).on( 'click', '.semesters-dropdown ul li a', app.Tuitions.displaySemester );
    $( '.main ' ).on( 'blur', '.semesters-dropdown select', app.Tuitions.displaySemester );
};

$( document ).ready( app.Tuitions.init );
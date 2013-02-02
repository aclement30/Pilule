if ( !app ) {
    var app = {};
}

app.Studies = {
    controllerURL: './studies/',
    object:        'studies'
};

// Display program info
app.Studies.displayProgram = function ( e ) {
	if ( e.currentTarget ) {
		e.preventDefault;

		// Param is an event, retrieve the program id
		document.location = '/dossier-scolaire/rapport-cheminement/' + $( this ).data( 'program' );
	}

    return false;
};

app.Studies.init = function () {
	$( '.main' ).on( 'click', '.programs-dropdown ul li a', app.Studies.displayProgram );
};

$( document ).ready( app.Studies.init );

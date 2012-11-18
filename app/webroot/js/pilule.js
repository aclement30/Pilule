var app = {
    // Define app properties
    isCapsuleOffline    :   false,                 // Capsule availability
    isMobile            :   false
};

app.init = function () {
    // If Capsule is offline, display information notice
    if ( this.isCapsuleOffline ) $( '.capsule-offline' ).show();
};

app.Common = {};

// Resize external view iframe on window resize
app.Common.resizeExternalFrame = function () {
    $( '#external-frame' ).css( 'width', $( window ).width() );
    $( '#external-frame' ).css( 'height', $( window ).height() - 42 );
};

app.Common.closeExternalFrame = function () {
    // Hide external view frame
    $( '#external-frame' ).fadeOut();
    $( '#external-frame' ).attr( 'src', 'blank.html' );

    // Reset default menu navigation
    $( '#user-nav .nav.external-frame' ).hide();
    $( '#user-nav .nav.external-frame li' ).removeClass( 'active' );
    $( '#user-nav .nav:not(.external-frame)' ).fadeIn();

    // Show sidebar
    $( '#sidebar' ).show();

    // Reset default logo
    $( '#header h1' ).removeClass('small');
};

// Display loading message
app.Common.loading = function ( object, message ) {

};

// Hide loading message
app.Common.stopLoading = function () {

};

// Display error message
app.Common.displayError = function ( message ) {
    $( '.alert.alert-error' ).html( message );
    $( '.alert.alert-error' ).fadeIn();
};

// Functions used for retrocompatibility
function loading( object, message ) {
    app.Common.loading( object, message );
}

function stopLoading () {
    app.Common.stopLoading();
}

function errorMessage( message ) {
    app.Common.displayError( message );
}
$( document ).ready( function() { app.init(); } );
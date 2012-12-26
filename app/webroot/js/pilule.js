var app = {
    // Define app properties
    isCapsuleOffline    :   false,                 // Capsule availability
    isMobile            :   false,
    isLogged            :   true,
    errorHandler        :   null
};

app.init = function () {
    // If Capsule is offline, display information notice
    if ( this.isCapsuleOffline ) $( '.capsule-offline' ).show();

    if ( $( '#login-form input.idul' ).length != 0 ) {
        $( '#btn-login' ).on( 'click', app.Users.login );
        $( '#login-form input.idul, #login-form input.password' ).on( 'keyup', function ( e ) {
            // If Enter key is pressed, submit login form
            if ( e.keyCode == 13 ) {
                app.Users.login();
            }
        } );
        $( '#loading-error .btn-redirect-dashboard' ).on( 'click', app.Users.redirectToDashboard );
        $( '#loading-error .btn-retry-login' ).on( 'click', app.Users.retryLogin );
    }

    $( '#sidebar ul li.submenu>a' ).on( 'click', app.Common.displaySubmenu );

    if ( !app.isMobile ) {
        $( '<iframe id="external-frame" name="external-frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">' ).appendTo( 'body' );
        app.Common.resizeExternalFrame();
    }
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

app.Common.clearPanel = function () {

};

app.Common.loadContent = function ( url, pageRefreshEffect ) {
    if (pageRefreshEffect) pageRefresh = true;

    // Display left menu
    $( '.navbar.menu' ).show();

    switch ( url ) {
        case '/welcome/dashboard':
            $( '#content' ).addClass( 'dashboard' );
            $( '.navbar.menu' ).hide();
            break;
        case '/studies':
        case '/studies/details':
        case '/studies/report':
            $('#sidebar li.submenu ul').not('.link-studies ul').slideUp();
            $('#sidebar li.submenu').not('.link-studies').removeClass('open');

            $( '#content').removeClass( 'dashboard' );
            break;
        case '/fees':
        case '/fees/details':
            $('#sidebar li.submenu ul').not('.link-tuitions ul').slideUp();
            $('#sidebar li.submenu').not('.link-tuitions').removeClass('open');

            $( '#content').removeClass( 'dashboard' );
            break;
        default:

            $('#sidebar li.submenu ul').slideUp();
            $('#sidebar li.submenu').removeClass('open');

            $( '#content').removeClass( 'dashboard' );
            break;
    }

    // Send AJAX request to get page content
    ajax.request({
        url:        url,
        callback:   function ( response ) {
            // Hide content layer for the fadeIn effect
            if (pageRefresh)
                $('#content-layer').hide();

            if ( response.content ) {
                // Content title
                $( 'h1' ).html( response.title );

                // Main content
                $( '#content-layer .content-inside' ).html( response.content );

                // Display breadcrumbs
                if ( response.breadcrumb )
                    displayBreadcrumb( response.breadcrumb );

                // Display action buttons
                if ( response.buttons ) {
                    displayActionButtons( response.buttons );
                } else {
                    $( '.action-buttons .buttons' ).hide();
                }

                // If data expired, call cache reaload function
                if ( response.reloadData )
                    app.cCache.reloadData( [ { name: response.reloadData, auto: 1 } ] );

                // If needed, execute JS code
                if (response.code)
                    eval(response.code);
                if (debug == 1) {
                    alert(response.content);
                    alert(response.code);
                }

                // Display data timestamp
                if ( response.timestamp ) {
                    $( '#content-header .timestamp' ).html( 'Données actualisées : il y a ' + response.timestamp + '.' ).show();
                } else {
                    $( '#content-header .timestamp' ).hide();
                }

                if ( pageRefresh ) {
                    // Display content with fadeIn effect
                    $( '#content-layer' ).fadeIn( 'normal', function () {
                        fixLayout();
                    });

                    pageRefresh = false;
                } else {
                    fixLayout();
                }

                // If callback, execute callback
                if ( loadContentCallback ) {
                    ( loadContentCallback )();
                    loadContentCallback = null;
                }

                // Send page info to Google Analytics for stats
                if ( typeof _gaq !== 'undefined' )
                    _gaq.push( [ '_trackPageview', document.location.hash.substr( 2 ) ] );
            } else {
                if ( response.error ) {
                    errorMessage ( 'Erreur interne : impossible d\'afficher la page demandée.' );
                }
            }
        }
    });
};

app.Common.displaySubmenu = function ( e ) {
    object = $( e.currentTarget );
    var submenu = $( object ).siblings( 'ul' );
    var li = $( object ).parents( 'li' );
    var submenus = $( '#sidebar li.submenu ul' );
    var submenus_parents = $( '#sidebar li.submenu' );
    if ( li.hasClass( 'open' ) ) {
        if ( ( $( window ).width() > 768 ) || ( $( window ).width() < 479 ) ) {
            submenu.slideUp();
        } else {
            submenu.fadeOut( 250 );
        }
        li.removeClass( 'open' );
    } else {
        if ( ( $( window ).width() > 768 ) || ( $( window ).width() < 479 ) ) {
            submenus.slideUp();
            submenu.slideDown();
        } else {
            submenus.fadeOut( 250 );
            submenu.fadeIn( 250 );
        }

        submenus_parents.removeClass( 'open' );
        li.addClass( 'open' );
    }

    return false;
};

// Display loading message
app.Common.loading = function ( object, message ) {

};

// Hide loading message
app.Common.stopLoading = function () {

};

// Display error message
app.Common.displayError = function ( message, object, autoHide ) {
    if ( object == null ) {
        object = '.alert.alert-error';
    }

    $( object ).html( message );
    $( object ).fadeIn();

    if ( autoHide != false ) {
        setTimeout( function(){ $( object ).fadeOut(); }, 2500 );
    }
};

app.Common.refreshPage = function () {
    location.reload();
};

app.Common.setErrorHandler = function ( handler ) {
    app.errorHandler = handler;
};

app.Common.unsetErrorHandler = function ( handler ) {
    app.errorHandler = null;
};

app.Common.dispatchError = function ( error ) {
    // Attempt to display the error message
    if ( typeof error.message == 'undefined' ) {
        switch ( error.context ) {
            case 'ajax-server-error':
                error.message = 'Une erreur est survenue durant l\'exécution de la fonction demandée sur le serveur';
                break;
            case 'ajax-server-timeout':
                error.message = 'Le serveur n\'a pas répondu dans un délai suffisant';
                break;
            case 'ajax-server-invalid-response':
                error.message = 'Le serveur a renvoyé une réponse invalide';
                break;
            default:
                error.message = 'Une erreur inconnue est survenue durant l\'exécution de la fonction demandée';
                break;
        }
    }

    // Check if an error handler is defined
    if ( app.errorHandler != null ) {
        // Pass the error message to the error handler
        ( app.errorHandler )( error );
    } else {
        app.Common.displayError( error.message );
    }
};
// Functions used for retrocompatibility
function loading( object, message ) {
    app.Common.loading( object, message );
}

function stopLoading () {
    app.Common.stopLoading();
}

function errorMessage( message ) {
    // Check if an error handler is defined
    if ( app.errorHandler != null ) {
        // Pass the error message to the error handler
        ( app.errorHandler )( message );
    } else {
        // Attempt to display the error message
        app.Common.displayError( message );
    }
}
$( document ).ready( function() { app.init(); } );
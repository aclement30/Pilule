if ( !app ) {
    var app = {};
}

app.Cache = {
    controllerURL	: '/cache/',
    reloadCallback	: null,
    isLoading		: false,
    loadingQueue	: new Array()
};

// Add requested elements from Capsule to loading queue
app.Cache.reloadData = function ( params ) {
    // Check if Capsule is offline
    if ( app.isCapsuleOffline ) return ( false );

    // Add requested items to loading queue
    if ( $.isArray( params ) ) {
        $.each( params, function( key, value ) {
            app.Cache.loadingQueue.push( value );
        });
    } else {
        app.Cache.loadingQueue.push( params );
    }

    // Start loading data, if not loading already
    if ( !app.Cache.isLoading ) app.Cache.loadData();
};

app.Cache.loadData = function () {
    app.Cache.isLoading = true;

    // Notify Google Analytics of a Data-loading action
    if ( app.Cache.loadingQueue[0].auto != 1 ) _gaq.push( [ '_trackEvent', 'Cache', 'Reload', app.Cache.loadingQueue[0].name ] );

    // Display loading notice
    $( '.action-buttons .btn-refresh i' ).hide();
    $( '.action-buttons .btn-refresh img' ).fadeIn();
    $( '.action-buttons .btn-refresh' ).parent().attr( 'title', 'Actualisation des données en cours' );
    $( '.action-buttons .timestamp' ).hide();
    $( '#content-header .loading-status' ).removeClass( 'error' ).html( 'Actualisation des données en cours' ).fadeIn();

    // Set callback
    app.Cache.reloadCallback = app.Cache.loadingQueue[0].callback;

    ajax.request({
        type:           'PUT',
        url:            '/cache/fetchData.json',
        data:           {
            name:       app.Cache.loadingQueue[0].name,
            auto:       app.Cache.loadingQueue[0].auto
        },
        error:			function ( xhr, ajaxOptions, thrownError ) {
            alert(thrownError);
        },
        callback:       function ( response ) {
        	if ( typeof( response ) === 'undefined' || response == null ) {
        		// Loading failed...

        		app.Cache.isLoading = false;
        		
            	if ( login ) {
            		// Display loading error
            		$( '#loading-panel' ).fadeOut( 'fast', function () {
                        $( '#loading-error' ).fadeIn();
                    });
            	} else {
            		// Display loading error notice
	            	$( '#content-header .loading-status' ).addClass( 'error' );
	            	$( '.action-buttons .btn-refresh img' ).hide();
                    $( '.action-buttons .btn-refresh i' ).fadeIn();
                    if ( response.error ) {
                        errorMessage( response.error, $( '#content-header .loading-status' ), false );
                    } else {
                        errorMessage( 'Erreur lors de l\'actualisation des données.', $( '#content-header .loading-status' ), false );
                    }
            	}
        	} else {
        		// Loading succeeded...

                if ( response.status ) {
                	// Remove item from loading queue
                    app.Cache.loadingQueue.shift();
                    if ( app.Cache.loadingQueue.length == 0 ) app.Cache.isLoading = false;

                    // Execute callback
                    if ( app.Cache.reloadCallback != null && app.Cache.reloadCallback != undefined ) {
                        ( app.Cache.reloadCallback )();
                        app.Cache.reloadCallback = null;
                    } else {
                        if ( response.auto == 1 ) {
                            app.Common.refreshPage( false );
                        } else {
                            app.Common.refreshPage( true );
                        }
                    }
					
                    if ( app.Cache.loadingQueue.length == 0 ) {
                    	// Hide loading message
                        $( '#content-header .loading-status' ).hide();
                        $( '.action-buttons .btn-refresh img' ).hide();
                        $( '.action-buttons .btn-refresh i' ).fadeIn();
                        $( '.action-buttons .btn-refresh' ).parent().attr('title', 'Actualiser les données');
                    } else {
                        // Load next item
                        app.Cache.loadData();
                    }
                } else {
                    $( '#content-header .loading-status' ).addClass( 'error' );
                    if ( response.error ) {
                    	// Hide loading status
                        errorMessage( response.error, $( '#content-header .loading-status' ), false );
                    } else {
                    	// Display error message
                        errorMessage( 'Erreur lors de l\'actualisation des données.', $( '#content-header .loading-status' ), false);
                    }
                }
            }
        }
    });
};
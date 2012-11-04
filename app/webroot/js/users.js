var login = false;

var app.Users = {
    controllerURL: '/users/'
};

var app.Users.login = function ( askAutoLogon ) {
    var idul = $( '#login-form .idul' ).val();
    var password = $( '#login-form .password' ).val();

    $( '#login-form .alert-error' ).hide();

    $( '#login-form' ).fadeOut( 'fast', function () {
        $( '#loading-panel' .fadeIn();
    });
    
    var autoLogon = 0;
    
    if ( isMobile == 1 ) {
        // Si le visiteur accède au site depuis un navigateur mobile et que le stockage local est disponible,
        // l'option de mémoriser son mot de passe lui est offerte
        
        if ( Modernizr.localstorage && askAutoLogon != 1 ) {
            if ( localStorage.getItem( 'pilule-ask-autologon-' + idul ) == null ) {
                if ( confirm( "Voulez-vous que Pilule vous connecte automatiquement lors de votre prochaine visite depuis cet appareil ?" ) ) {
                    autoLogon = 1;
                } else {
                    // Mémorisation de la réponse
                    localStorage.setItem( 'pilule-ask-autologon-'+idul, 'no' );
                    autoLogon = 0;
                }
            }
        }
    }
    
    // Send login request
    ajax.request({
        url:            app.Users.controllerURL,
        method:         'login',
        data:           {
            idul:       idul,
            password:   password
        },
        callback:       function ( response ) {
            if ( response.status ) {
                if ( isMobile == 1 ) {
                    // Si l'utilisateur a choisi de mémoriser son mot de passe, l'IDUL et le mot de passe sont mémorisés sur l'appareil
                    if ( autoLogon == 1 ) {
                        if ( Modernizr.localstorage ) {
                            var idul = $( '#login-form .idul' ).val();
                            var password = $( '#login-form .password' ).val();
                            
                            localStorage.setItem( 'pilule-autologon-idul', idul );
                            localStorage.setItem( 'pilule-autologon-password', password );
                        }
                    }
                }
                
                if ( response.loading ) {
                    $( '#loading-panel .loading-message' ).html( 'Chargement de vos données' );
                    $( '#loading-panel .waiting-notice' ).fadeIn();

                    var reloadItems = new Array();
                    $.each( response.reloadList, function( key, value ) {
                        // Ajout d'un élément à la liste
                        reloadItems.push( { name: value, auto: 1, callback: function() {
                            if ( app.cache.loadingQueue.length == 0 && ( !app.cache.isLoading ) ) {
                                app.users.redirectToDashboard();
                            }
                        }});
                    });

                    // Actualisation de la liste d'éléments
                    app.cache.reloadData( reloadItems );
                } else {
                    var redirectURL = $( '#redirect_url' ).val();

                    $( '#formContainer' ).fadeOut();

                    // Redirection à la page demandée, s'il y a lieu
                    if ( redirectURL != '' && redirectURL != undefined ) {
                        document.location = redirectURL;
                    } else {
                        document.location = './welcome/';
                    }
                }
            } else {
                errorMessage( response.error, $( '#login-form .alert-error' ), false );

                $( '#loading-panel' ).fadeOut( 'fast', function () {
                    $( '#login-form' ).fadeIn();
                });
            }
        }
    });
}
var users = {
    controller: '/users/',

    login: function ( askAutoLogon ) {
        var idul = $( '#login-form .idul' ).val();
        var password = $( '#login-form .password' ).val();

        $( '#login-form .alert-error' ).hide();

        $( '#login-form' ).fadeOut( 'fast', function () {
            $( '#loading-panel' .fadeIn();
        });
        
        var autoLogon = 0;
        
        if ( isMobile == 1 ) {
        	// Si le visiteur accède au site depuis un navigateur mobile et que le stockage local est disponible,
        	// l'option de mémoriser son mot de passe lui est offerte
        	
	        if ( Modernizr.localstorage && askAutoLogon != 1 ) {
				if ( localStorage.getItem( 'pilule-ask-autologon-' + idul ) == null ) {
					if ( confirm( "Voulez-vous que Pilule vous connecte automatiquement lors de votre prochaine visite depuis cet appareil ?" ) ) {
						autoLogon = 1;
					} else {
						// Mémorisation de la réponse
						localStorage.setItem( 'pilule-ask-autologon-'+idul, 'no' );
						autoLogon = 0;
					}
				}
			}
		}
		
        // Send login request
        ajax.request({
            url:        this.controller,
            method:         'json/login',
            data:           {
                idul:       idul,
                password:   password
            },
            callback:       function ( response ) {
                if (response.status) {
	                if (isMobile == 1) {
	                	// Si l'utilisateur a choisi de mémoriser son mot de passe, l'IDUL et le mot de passe sont mémorisés sur l'appareil
		                if (autoLogon == 1) {
							if (Modernizr.localstorage) {
								var idul = $('#idul').val();
								var password = $('#password').val();
								
								localStorage.setItem('pilule-autologon-idul', idul);
								localStorage.setItem('pilule-autologon-password', password);
							}
						}
	                }
	                
                    if (response.loading) {
                        $('#loading-panel .loading-message').html('Chargement de vos données');
                        $('#loading-panel .waiting-notice').fadeIn();

                        var reloadItems = new Array();
                        $.each(response.reloadList, function(key, value) {
                            // Ajout d'un élément à la liste
                            reloadItems.push({name: value, auto: 1, callback: function() {
                                if (app.cache.loadingQueue.length == 0 && (!app.cache.isLoading)) {
                                    app.users.redirectToDashboard();
                                }
                            }});
                        });

                        // Actualisation de la liste d'éléments
                        app.cache.reloadData(reloadItems);
                    } else {
                        var redirectURL = $('#redirect_url').val();

                        $('#formContainer').fadeOut();

                        // Redirection à la page demandée, s'il y a lieu
                        if (redirectURL != '' && redirectURL != undefined) {
                            document.location = redirectURL;
                        } else {
                            document.location = './welcome/';
                        }
                    }
                } else {
                    errorMessage(response.error, $('#login-form .alert-error'), false);

                    $('#loading-panel').fadeOut('fast', function () {
                        $('#login-form').fadeIn();
                    });
                }
            }
        });
    },

    redirectToDashboard: function () {
        if ( !app.cache.isLoading ) {
            var redirectURL = $('#redirect_url').val();

            $('#formContainer').fadeOut();

            // Redirection à la page demandée, s'il y a lieu
            if (redirectURL != '' && redirectURL != undefined) {
                setTimeout("document.location = redirectURL;", 100);
            } else {
                setTimeout("document.location = './#!/dashboard';", 100);
            }
        }
    },
    
    retryLogin: function () {
	    $('#loading-error').fadeOut('fast', function () {
            $('#login-form').fadeIn();
        });
    }
}

var user = {
    isAuthenticated: false,

    eraseData: function () {
        if (confirm("Voulez-vous vraiment effacer toutes vos données des serveurs de Pilule ?")) {
            ajax.request({
                controller:     './settings/',
                method:         'eraseData',
                data:           {},
                callback:       function (response) {
                    resultMessage("Vos données ont été supprimées.");

                    setTimeout("document.location='welcome/';", 1500);
                }
            });
        }
    }
}

addChild(app, 'users', users);
addChild(app, 'user', user);

function loading() {

}

function stopLoading() {

}

function supportsCSS3D() {
    var props = [
        'perspectiveProperty', 'WebkitPerspective', 'MozPerspective'
    ], testDom = document.createElement('a');

    for(var i=0; i<props.length; i++){
        if(props[i] in testDom.style){
            return true;
        }
    }

    return false;
}
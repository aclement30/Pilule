var login = false;

var users = {
    controllerURL: './users/',
    object:        'users',

    login: function () {
        $('#login-form .alert-error').hide();

        $('#login-form').fadeOut('fast', function () {
            $('#loading-panel').fadeIn();
        });

        // Send login request
        ajax.request({
            controller:     this.controllerURL,
            method:         'ajax_login',
            data:           {
                idul:       $('#idul').val(),
                password:   $('#password').val()
            },
            callback:       function ( response ) {
                if (response.status) {

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
        if (!app.cache.isLoading) {
            var redirectURL = $('#redirect_url').val();

            $('#formContainer').fadeOut();

            // Redirection à la page demandée, s'il y a lieu
            if (redirectURL != '' && redirectURL != undefined) {
                setTimeout("document.location = redirectURL;", 100);
            } else {
                setTimeout("document.location = './#!/dashboard';", 100);
            }
        }
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
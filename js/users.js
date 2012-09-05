var users = {
    controllerURL: './users/',
    object:        'users',

    login: function () {
        $('#login-form').removeClass('error');
        $('#login-form .alert-error').hide();

        // Checking for CSS 3D transformation support
        $.support.css3d = supportsCSS3D();

        var formContainer = $('#formContainer');

        // Flipping the forms
        formContainer.toggleClass('flipped');

        // If there is no CSS3 3D support, simply
        // hide the login form (exposing the recover one)
        if(!$.support.css3d){
            $('#login-form').toggle();
        }

        // Send login request
        ajax.request({
            controller:     this.controllerURL,
            method:         'ajax_login',
            data:           {
                idul:   $('#idul').val(),
                password:   $('#password').val()
            },
            callback:       function ( response ) {
                if (response.status) {
                    var redirectURL = $('#redirect_url').val();

                    formContainer.fadeOut();

                    // Redirection à la page demandée, s'il y a lieu
                    if (redirectURL != '' && redirectURL != undefined) {
                        document.location = redirectURL;
                    } else {
                        document.location = './welcome/';
                    }
                } else {
                    $('#login-form').addClass('error');
                    errorMessage(response.error, $('#login-form .alert-error'), false);

                    // Flipping the forms
                    formContainer.toggleClass('flipped');

                    // If there is no CSS3 3D support, simply
                    // hide the login form (exposing the recover one)
                    if(!$.support.css3d){
                        $('#login-form').toggle();
                    }
                }
            }
        });
    }
}

function addChild(ob, childName, childOb) {
    ob[childName] = childOb;
    childOb.parent = ob;
}

var app = {};

addChild(app, 'users', users);

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
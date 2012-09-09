var cache = {
    controllerURL: './cache/',

    reloadData: function ( params, auto ) {
        if (isMobile == 0) {
            // Affichage du symbole de chargement sur le bouton
            $('.action-buttons .btn-refresh i').hide();
            $('.action-buttons .btn-refresh img').fadeIn();
            $('.action-buttons .btn-refresh').parent().attr('title', 'Actualisation des données en cours');
        } else {
            $('#loading-message').slideDown();
        }

        $('#content-header .timestamp').html('');
        $('#content-header .timestamp').html('');
        $('#content-header .loading-status').removeClass('error');
        $('#content-header .loading-status').html('Actualisation des données en cours');

        ajax.request({
            type:           'POST',
            controller:     this.controllerURL,
            method:         'ajax_reloadData',
            data:           {
                name:       params,
                auto:       auto
            },
            callback:       function (response) {
                $('#content-header .loading-status').html('');
                $('.action-buttons .btn-refresh img').hide();
                $('.action-buttons .btn-refresh i').fadeIn();
                $('.action-buttons .btn-refresh').parent().attr('title', 'Actualiser les données');

                if (response.status) {
                    //alert('Données actualisées !');
                    if (response.auto == 1) {
                        refreshPage(false);
                    } else {
                        refreshPage(true);
                    }
                } else {
                    $('#content-header .timestamp').html('');
                    $('#content-header .loading-status').addClass('error');
                    if (response.error) {
                        errorMessage(response.error, $('#content-header .loading-status'), false);
                    } else {
                        errorMessage('Erreur lors de l\'actualisation des données.', $('#content-header .loading-status'), false);
                    }
                }
            }
        });
    }
}

addChild(app, 'cache', cache);
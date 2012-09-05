var cache = {
    controllerURL: './cache/',

    reloadData: function ( params, auto ) {
        if (isMobile == 0) {
            if (auto == 1) {
                loading('Actualisation des données');
            } else {
                // Affichage du symbole de chargement sur le bouton
                $('.action-buttons .btn-refresh i').hide();
                $('.action-buttons .btn-refresh img').fadeIn();
                $('.action-buttons .btn-refresh').parent().attr('title', 'Actualisation des données en cours');

                loading('Chargement des données depuis Capsule...');
            }
        } else {
            $('#loading-message').slideDown();
        }

        ajax.request({
            type:           'POST',
            controller:     this.controllerURL,
            method:         'ajax_reloadData',
            data:           {
                name:       params,
                auto:       auto
            },
            callback:       function (response) {
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
                    if (response.error) {
                        errorMessage(response.error);
                    } else {
                        errorMessage('Erreur lors de l\'actualisation des données.');
                    }
                }
            }
        });
    }
}

addChild(app, 'cache', cache);
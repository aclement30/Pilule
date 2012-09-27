var cache = {
    controllerURL: './cache/',
    reloadCallback: null,
    isLoading: false,
    loadingQueue: new Array(),

    // Fonction d'ajout d'éléments à recharger depuis Capsule à la file d'attente
    reloadData: function ( params ) {
        // Vérification de la disponibilité de Capsule
        if ( app.isCapsuleOffline ) return ( false );

        if ($.isArray(params)) {
            $.each(params, function(key, value) {
                app.cache.loadingQueue.push(value);
            });
        } else {
            this.loadingQueue.push(params);
        }

        if (!this.isLoading) this.loadData();
    },

    loadData: function () {
        this.isLoading = true;

        // Notification à Google Analytics d'une demande d'actualisation des données par l'utilisateur
        if (this.loadingQueue[0].auto != 1) _gaq.push(['_trackEvent', 'Cache', 'Reload', this.loadingQueue[0].name]);

        // Affichage du symbole de chargement sur le bouton Actualiser
        $('.action-buttons .btn-refresh i').hide();
        $('.action-buttons .btn-refresh img').fadeIn();
        $('.action-buttons .btn-refresh').parent().attr('title', 'Actualisation des données en cours');
        $('.action-buttons .timestamp').hide();
        $('#content-header .loading-status').removeClass('error').html('Actualisation des données en cours').fadeIn();

        // Définition du callback
        this.reloadCallback = this.loadingQueue[0].callback;

        ajax.request({
            type:           'POST',
            controller:     this.controllerURL,
            method:         'ajax_reloadData',
            data:           {
                name:       this.loadingQueue[0].name,
                auto:       this.loadingQueue[0].auto
            },
            callback:       function (response) {
                if (response.status) {
                    app.cache.loadingQueue.shift();
                    if (app.cache.loadingQueue.length == 0) app.cache.isLoading = false;

                    // Exécution du callback
                    if (app.cache.reloadCallback != null && app.cache.reloadCallback != undefined) {
                        (app.cache.reloadCallback)();
                        app.cache.reloadCallback = null;
                    } else {
                        if (response.auto == 1) {
                            refreshPage(false);
                        } else {
                            refreshPage(true);
                        }
                    }

                    if (app.cache.loadingQueue.length == 0) {
                        $('#content-header .loading-status').hide();
                        $('.action-buttons .btn-refresh img').hide();
                        $('.action-buttons .btn-refresh i').fadeIn();
                        $('.action-buttons .btn-refresh').parent().attr('title', 'Actualiser les données');
                    } else {
                        // Chargement de l'élément suivant
                        app.cache.loadData();
                    }
                } else {
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
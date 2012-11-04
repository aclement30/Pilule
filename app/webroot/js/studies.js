var studies = {
    controllerURL: './studies/',
    object:        'studies',

    displayProgramPanel: function ( id ) {
        $('.program-panel').hide();
        $('#program-'+id+'.program-panel').fadeIn();
    }
}

addChild(app, 'studies', studies);
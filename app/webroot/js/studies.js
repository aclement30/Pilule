if ( !app ) {
    var app = {};
}

app.Studies = {
    controllerURL: './studies/',
    object:        'studies'
};

app.Studies.displayProgramPanel = function ( id ) {
    $('.program-panel').hide();
    $('#program-'+id+'.program-panel').fadeIn();
};
if ( !app ) {
    var app = {};
}

app.Tuitions = {
    controllerURL: './fees/',
    object:        'fees'
};

app.Tuitions.displaySemester = function ( semester ) {
    document.location.hash = '#!/fees/details/' + semester;
};
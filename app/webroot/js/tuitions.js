var tuitions = {
    controllerURL: './fees/',
    object:        'fees',

    displaySemester: function (semester) {
        document.location.hash = '#!/fees/details/'+semester;
    }
}

addChild(app, 'tuitions', tuitions);
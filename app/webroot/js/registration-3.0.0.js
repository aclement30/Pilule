if ( !app ) {
    var app = {};
}

app.Registration = {
	controllerURL: 				'./registration/',
	selectionTotal: 			0,
	registrationSemester: 		'',
	currentSemester: 			'',
	currentDate: 				'',
	deadline_drop_fee: 			'',
	deadline_drop_nofee: 		'',
	deadline_edit_selection: 	''
};

app.Registration.init = function ( params ) {
	if ( params.selectionTotal ) app.Registration.selectionTotal = params.selectionTotal;
	if ( params.registrationSemester ) app.Registration.registrationSemester = params.registrationSemester;
	if ( params.currentSemester ) app.Registration.currentSemester = params.currentSemester;
	if ( params.currentDate ) app.Registration.currentDate = params.currentDate;
	if ( params.deadline_drop_fee ) app.Registration.deadline_drop_fee = params.deadline_drop_fee;
	if ( params.deadline_drop_nofee ) app.Registration.deadline_drop_nofee = params.deadline_drop_nofee;
	if ( params.deadline_edit_selection ) app.Registration.deadline_edit_selection = params.deadline_edit_selection;

	$( '.main' ).on( 'click', 'table.courses-list tr td', function ( e ) {
		var code = $( e.currentTarget ).parent().data( 'code' );

		if ( code != '' && code != undefined ) app.Registration.getCourseInfo( code );
	} );

	$( '#modal' ).on( 'click', '.js-select-btn', app.Registration.selectCourse );

	// Init courses popover
	$( '.container' ).on( 'click', '.aside .registered-courses table tbody tr', app.Registration.togglePopover );
	$( '.container' ).on( 'click', '.aside .selected-courses table tbody tr', app.Registration.togglePopover );

	$( '.container' ).on( 'click', '.aside .registered-courses .popover-title button.remove-course', app.Registration.unregisterCourse );
	$( '.container' ).on( 'click', '.aside .selected-courses .popover-title button.remove-course', app.Registration.unselectCourse );

	$( '.aside .btn.register-courses' ).on( 'click', app.Registration.registerCourses );

	$( '.aside a.js-capsule-link' ).on( 'click', function( e ) {
		app.Common.openExternalWebsite( app.baseUrl + 'services/capsule-registration/' );

		return false;
	} );

	$( '.main' ).on( 'click', '.semesters-dropdown ul li a', app.Registration.changeSemester );
    $( '.main ' ).on( 'blur', '.semesters-dropdown select', app.Registration.changeSemester );

    $( '.main' ).on( 'click', '.programs-dropdown ul li a', app.Registration.changeProgram );
    $( '.main ' ).on( 'blur', '.programs-dropdown select', app.Registration.changeProgram );

    $( '.main' ).on( 'click', '.courses-display ul li a', app.Registration.toggleCoursesDisplay );

    $( '.courses-list tbody tr.not-available' ).hide( 'fast' ).promise().done( app.Registration.repaintTableRows );
};

app.Registration.repaintTableRows = function () {
	$( '.main .courses-list tbody' ).each( function( index, tbody ) {
		$( tbody ).find( 'tr' ).css( 'backgroundColor', '#fff' );
		$( tbody ).find( 'tr:visible:even' ).css( 'backgroundColor', '#f9f9f9' );
	});
};

app.Registration.toggleCoursesDisplay = function ( e ) {
	var displayMode = $( e.currentTarget ).data( 'list' );

	if ( displayMode == 'all' ) {
		$( '.courses-list tbody tr.not-available' ).show( 'fast' ).promise().done( app.Registration.repaintTableRows );
	} else {
		$( '.courses-list tbody tr.not-available' ).hide( 'fast' ).promise().done( app.Registration.repaintTableRows );
	}

	$( e.currentTarget ).closest( 'ul' ).find( 'li' ).removeClass( 'selected' );
	$( e.currentTarget ).parent().addClass( 'selected' );
	$( e.currentTarget ).closest( '.courses-display' ).find( '.dropdown-toggle' ).html( $( e.currentTarget ).html() + ' <span class="caret"></span>' );
	$( e.currentTarget ).closest( '.courses-display' ).removeClass( 'open' );

	return false;
};

app.Registration.togglePopover = function ( e ) {
	$( e.currentTarget ).siblings( 'tr' ).popover( 'hide' );

	if ( $( e.currentTarget ).parent().find( '.popover.in' ).length != 0 ) {
		$( e.currentTarget ).popover( 'hide' );
	} else {
		$( e.currentTarget ).popover( 'show' );
	}
};

app.Registration.getCourseInfo = function ( code ) {
	app.Common.showModal( {
		url: 		'/registration/getCourseInfo/' + code,
		callback: 	function() {
			// Check if a classes are available for this course
			if ( $( '#modal .loading-classes' ).length != 0 ) {
				app.Registration.getAvailableClasses( code );
			}
		}
	} );
};

app.Registration.getAvailableClasses = function ( code ) {
	$( '#modal .classes-list' ).load( app.baseUrl + 'registration/getAvailableClasses/' + code, function() {
		$( '#modal .loading-classes' ).hide();
	} );
};

app.Registration.selectCourse = function ( e ) {
	var nrc = $( e.currentTarget ).closest( '.class' ).data( 'nrc' );

	$( e.currentTarget ).parent().addClass( 'loading' );
	
	// Send AJAX request
    ajax.request( {
        url:     		'/registration/selectCourse.json',
        data:           {
           	nrc: 		nrc
        },
        callback:       app.Registration.addSelectedCourse
    } );

    return false;
};

app.Registration.addSelectedCourse = function ( response ) {
	// Hide loading wheel
	$( '#modal .classes-list .registration-state' ).removeClass( 'loading' );

	if ( response.status ) {				// Course selection has been saved successfully
		// Close modal
		$( '#modal' ).modal( 'hide' );

		$( '.aside .table-panel.selected-courses' ).load( document.location + ' .aside .selected-courses table', function( e ) {
	        app.Common.displayMessage( "Le cours a été ajouté à votre sélection." );

	        // Flash the selected courses table to alert the user of the update
	        $( '.aside .table-panel.selected-courses' ).fadeOut( 200, function(){
	            $( '.aside .table-panel.selected-courses' ).fadeIn( 400 );
	        } );
	    } );
	} else {
		if ( response.errorCode == 2 ) {		// Unknown error during course selection
			app.Common.dispatchError({
				message: 	"Le cours n'a pas pu être ajouté à votre sélection.",
				context: 	'registration-error'
			});
		} else if ( response.errorCode == 3 ) {		// Unknown error during course selection
			stopLoading();
		} else if ( response.errorCode == 4 ) {		// Error : similar course already selected
			stopLoading();

			if ( confirm( "Vous avez déjà sélectionné un cours similaire, mais avec un horaire différent. Voulez-vous le remplacer par celui-ci ?" ) ) {
				ajax.request({
			        url:     		'/registration/selectCourse.json',
			        data:           {
			            replace: 	'yes',
			           	nrc: 		response.nrc
			        },
			        callback:       app.Registration.addSelectedCourse
			    });
			} else {
				ajax.request({
			        url:     		'/registration/selectCourse.json',
			        data:           {
			            replace: 	'no',
			           	nrc: 		response.nrc
			        },
			        callback:       app.Registration.addSelectedCourse
			    });
			}
		} else if ( response.errorCode == 5 ) {		// Error : course already registered
			app.Common.dispatchError({
				message: 	"Vous êtes déjà inscrit à ce cours.",
				context: 	'registration-error'
			});
		} else if ( response.errorCode == 6 ) {		// Error : similar course already registered
			app.Common.dispatchError({
				message: 	"Vous êtes déjà inscrit à un cours similaire, mais avec un horaire différent.",
				context: 	'registration-error'
			});
		}
	}
};

app.Registration.unselectCourse = function ( e ) {
	var nrc = $( e.currentTarget ).data( 'nrc' );
	$( e.currentTarget ).parent().addClass( 'loading' );

	// Send AJAX request
    ajax.request( {
        url:     		'/registration/unselectCourse.json',
        data:           {
           	nrc: 		nrc
        },
        callback:       app.Registration.removeSelectedCourse
    } );

    return false;
};

app.Registration.removeSelectedCourse = function ( response ) {
	if ( response.status ) {				// Course has been successfully removed from selection
		$( '.aside .table-panel.selected-courses' ).load( document.location + ' .aside .selected-courses table', function( e ) {
	        app.Common.displayMessage( "Le cours a été retiré de votre sélection." );

	        // Flash the selected courses table to alert the user of the update
	        $( '.aside .table-panel.selected-courses' ).fadeOut( 200, function(){
	            $( '.aside .table-panel.selected-courses' ).fadeIn( 400 );
	        } );
	    } );
	} else {
		app.Common.dispatchError({
			message: 	"Le cours n'a pas pu être enlevé de votre sélection.",
			context: 	'registration-error'
		});
	}
};

app.Registration.registerCourses = function () {
	// Check if at least one course has been selected
	if ( $( '.aside .table-panel.selected-courses table tbody tr' ).length != 0 ) {
		app.Common.showLoadingModal({
			title: 		'Inscription en cours',
			message: 	'Veuillez patienter pendant que Pilule procède à l\'inscription des cours sélectionnés.'
		});
		
		// Send AJAX request
	    ajax.request( {
	        url:     		'/registration/registerCourses.json',
	        callback:       app.Registration.addRegisteredCourses
	    } );
	} else {
		errorMessage( "Vous devez d'abord ajouter un cours à votre sélection pour vous inscrire." );
	}

	return false;
};

app.Registration.addRegisteredCourses = function ( response ) {
	$( '#modal' ).modal( 'hide' );

	if ( response.status ) {				// Courses registration request has been successfull
		document.location = app.baseUrl + 'choix-cours/resultats/' + response.token;
	} else {
		app.Common.dispatchError({
			message: 	"L'inscription aux cours sélectionnés a échouée.",
			context: 	'registration-error'
		});
	}
};

app.Registration.unregisterCourse = function ( e ) {
	var nrc = $( e.currentTarget ).data( 'nrc' );
	$( e.currentTarget ).parent().addClass( 'loading' );

	var question = '';
	if (app.Registration.currentDate > app.Registration.deadline_drop_nofee) {
		question = "Si vous abandonnez le cours, vous payerez les droits de scolarité, mais vous n'aurez pas de mention d'échec.\nVoulez-vous continuer ?";
	} else if (app.Registration.currentDate > app.Registration.deadline_edit_selection) {
		question = "Si vous abandonnez le cours, vous ne payerez pas les droits de scolarité et n'aurez pas de mention d'échec.\nVoulez-vous continuer ?";
	} else {
		question = "Êtes-vous certain de vouloir retirer ce cours de votre horaire ?\nCette modification est irréversible.";
	}

	if (confirm(question)) {
		app.Common.showLoadingModal({
			title: 		'Désinscription en cours',
			message: 	'Veuillez patienter pendant que Pilule procède à la désinscription du cours demandé.'
		});

		// Send AJAX request
	    ajax.request( {
	        url:     		'/registration/unregisterCourse.json',
	        data:           {
	           	nrc: 		nrc
	        },
	        callback:       app.Registration.removeRegisteredCourse
	    } );
	}
};

app.Registration.removeRegisteredCourse = function ( response ) {
	$( '#modal' ).modal( 'hide' );

	if ( response.status ) {				// Course has been successfully removed from selection
		$( '.aside .table-panel.registered-courses' ).load( document.location + ' .aside .registered-courses table', function( e ) {
	        app.Common.displayMessage( "Vous avez été désinscrit du cours " + response.nrc + '.' );

	        // Flash the registered courses table to alert the user of the update
	        $( '.aside .table-panel.registered-courses' ).fadeOut( 200, function(){
	            $( '.aside .table-panel.registered-courses' ).fadeIn( 400 );
	        } );
	    } );
	} else {
		app.Common.dispatchError({
			message: 	"La désinscription du cours a échouée.",
			context: 	'registration-error'
		});
	}
};

app.Registration.configure = function () {
	loading("Enregistrement en cours...");
	
	$('#form-configure').submit();
};

app.Registration.configureCallback = function ( response ) {
	if ( response.status ) {
		resultMessage('Les paramètres ont été enregistrés.');
		
		document.location.hash = '#!/registration/courses';
	} else {
		errorMessage("Une erreur est survenue durant l'enregistrement des paramètres...");
	}
};

app.Registration.changeDisplay = function ( type ) {
	if (type == 'all') {
		$('.courses tr.unavailable').show();
	} else {
		$('.courses tr.unavailable').hide();
	}

	$('.courses').each(function(index, value) {
		$(value).find('tr').css('backgroundColor', '#fff');
		$(value).find('tr:visible:odd').css('backgroundColor', '#dae6f1');
	});
};

app.Registration.changeSemester = function ( e ) {
	if ( e.currentTarget ) {
		e.preventDefault;

		// Param is an event, retrieve the semester
		if ( $( e.currentTarget ).is( 'select' ) ) {
            document.location = app.baseUrl + 'choix-cours/' + $( e.currentTarget ).val();
        } else {
			document.location = app.baseUrl + 'choix-cours/' + $( this ).data( 'semester' );
		}
	} else {
    	document.location = app.baseUrl + 'choix-cours/' + semester;
    }

    return false;
};

app.Registration.changeProgram = function ( e ) {
	var semester = $( '.semesters-dropdown select' ).val();

	if ( e.currentTarget ) {
		e.preventDefault;

		// Param is an event, retrieve the semester
		if ( $( e.currentTarget ).is( 'select' ) ) {
            document.location = app.baseUrl + 'choix-cours/' + semester + '/' + $( e.currentTarget ).val();
        } else {
			document.location = app.baseUrl + 'choix-cours/' + semester + '/' + $( this ).data( 'program' );
		}
	} else {
    	document.location = app.baseUrl + 'choix-cours/' + semester + '/' + e;
    }

    return false;
};

app.Registration.displayHelp = function ( step ) {
	loading();
	
	if ($(window).height()<570) {
		var popupHeight = $(window).height() - 50;
	} else {
		var popupHeight = 520;
	}

	loading();

	var src = './registration/w_help/';
	$.modal('<iframe src="' + src + '" height="'+popupHeight+'" width="700" style="border:0;" onload="javascript:stopLoading();">', {
		containerCss:{
			backgroundColor:"#fff",
			borderColor:"#fff",
			height: popupHeight,
			padding: 0,
			width: 700
		},
		overlayClose:true
	});
};

app.Registration.formatFieldCode = function ( input ) {
	var v = input.value;
	if (v.match(/^[a-zA-Z]{3}$/) !== null) {
		input.value = v + '-';
	}
};

app.Registration.updateCourses = function () {
	$('#loading-bar').show();
	$('#results').fadeIn();
	
	$('#form-update').submit();
};

$( document ).ready( app.Registration.init );

// Old JavaScript Document
var registrationObj = {
	selectionTotal: 0,
	semester: '',
	currentSemester: '',
	currentDate: '',
	deadline_drop_fee: '',
	deadline_drop_nofee: '',
	deadline_edit_selection: '',
	getCourseInfo: function (that, code) {
		if ($(window).height()<650) {
			var popupHeight = $(window).height() - 50;
		} else {
			var popupHeight = 600;
		}
	
		loading();
	
		var src = './registration/w_getcourseinfo/code/'+code+'/semester/'+this.semester;
		$.modal('<iframe src="' + src + '" height="'+popupHeight+'" width="700" style="border:0;" onload="javascript:stopLoading();">', {
			containerCss:{
				backgroundColor:"#fff",
				borderColor:"#fff",
				height: popupHeight,
				padding: 0,
				width: 700
			},
			overlayClose:true
		});
	},
	addSelectedCourse: function (nrc) {
		loading("Ajout du cours à la sélection...");
		
		!sendData('GET','./registration/s_selectcourse', 'semester/'+this.semester+'/nrc/'+nrc);
	},
	addSelectedCourseCallback: function (response, nrc, total, credits) {
		if (response==1) {
			$.modal.close();
			
			resultMessage("Le cours a été ajouté à votre sélection.");
			
			$('#selected-courses .credits-total').html(credits+' crédits');
			$('#selected-courses .courses-total').html(total+' cours');
			
			$('a.delete-link').mouseover(function(){$(this).children(":first").attr('src', './images/cross.png');});
			$('a.delete-link').mouseout(function(){$(this).children(":first").attr('src', './images/cross-gray.png');});
			
			this.selectionTotal++;
		} else if (response==2) {
			errorMessage("Le cours n'a pas pu être ajouté à votre sélection.");
		} else if (response==3) {
			stopLoading();
		} else if (response==4) {
			stopLoading();
			if (confirm("Vous avez déjà sélectionné un cours similaire, mais avec un horaire différent. Voulez-vous le remplacer par celui-ci ?")) {
				!sendData('GET','./registration/s_selectcourse', 'semester/'+this.semester+'/nrc/'+nrc+'/replace/yes');
			} else {
				!sendData('GET','./registration/s_selectcourse', 'semester/'+this.semester+'/nrc/'+nrc+'/replace/no');
			}
		} else if (response==5) {
			errorMessage("Vous êtes déjà inscrit à ce cours.");
		} else if (response==6) {
			errorMessage("Vous êtes déjà inscrit à un cours similaire, mais avec un horaire différent.");
		}
	},
	removeSelectedCourse: function (nrc) {
		loading("Retrait du cours de la sélection...");
		
		!sendData('GET','./registration/s_unselectcourse', 'semester/'+this.semester+'/nrc/'+nrc);
	},
	removeSelectedCourseCallback: function (response, nrc, total, credits) {
		if (response==1) {
			resultMessage("Le cours a été retiré de votre sélection.");
			
			$('#selected-courses .credits-total').html(credits+' crédits');
			$('#selected-courses .courses-total').html(total+' cours');
			
			this.selectionTotal--;
		} else if (response==2) {
			errorMessage("Le cours n'a pas pu être enlevé de votre sélection.");
		}
	},
	removeRegisteredCourse: function (nrc) {
		var question = '';
		if (this.currentDate>this.deadline_drop_nofee) {
			question = "Si vous abandonnez le cours, vous payerez les droits de scolarité, mais vous n'aurez pas de mention d'échec.\nVoulez-vous continuer ?";
		} else if (this.currentDate>this.deadline_edit_selection) {
			question = "Si vous abandonnez le cours, vous ne payerez pas les droits de scolarité et n'aurez pas de mention d'échec.\nVoulez-vous continuer ?";
		} else {
			question = "Voulez-vous vraiment retirer ce cours de votre horaire ?";
		}
		if (confirm(question)) {
			loading("Désinscription du cours...");
			
			!sendData('GET','./registration/s_removeregisteredcourse', 'semester/'+this.semester+'/nrc/'+nrc);
		}
	},
	removeRegisteredCourseCallback: function (response, nrc, total, credits) {
		if (response==1) {
			resultMessage("Vous avez été désinscrit du cours.");
			
			$('#registered-courses .credits-total').html(credits+' crédits');
			$('#registered-courses .courses-total').html(total+' cours');
		}
	},
	registerCourses: function () {
		if (this.selectionTotal!=0) {
			loading("Inscription aux cours sélectionnés...");
			
			!sendData('GET','./registration/s_registercourses', 'semester/'+this.semester);
		} else {
			errorMessage("Vous devez d'abord ajouter un cours à votre sélection pour vous inscrire.");
		}
	},
	configure: function () {
		loading("Enregistrement en cours...");
		
		$('#form-configure').submit();
	},
	configureCallback: function (response) {
		if (response == 1) {
			resultMessage('Les paramètres ont été enregistrés.');
			
			document.location.hash = '#!/registration/courses';
		} else {
			errorMessage("Une erreur est survenue durant l'enregistrement des paramètres...");
		}
	},
	changeDisplay: function (type) {
		if (type == 'all') {
			$('.courses tr.unavailable').show();
		} else {
			$('.courses tr.unavailable').hide();
		}
		
		$('.courses').each(function(index, value) {
			$(value).find('tr').css('backgroundColor', '#fff');
			$(value).find('tr:visible:odd').css('backgroundColor', '#dae6f1');
		});
	},
	selectSemester: function (semester) {
		loading('Chargement des cours au programme...');
		
		!sendData('GET','./registration/courses', 'semester/'+semester);
	},
	displayHelp: function (step) {
		loading();
		
		if ($(window).height()<570) {
			var popupHeight = $(window).height() - 50;
		} else {
			var popupHeight = 520;
		}
	
		loading();
	
		var src = './registration/w_help/';
		$.modal('<iframe src="' + src + '" height="'+popupHeight+'" width="700" style="border:0;" onload="javascript:stopLoading();">', {
			containerCss:{
				backgroundColor:"#fff",
				borderColor:"#fff",
				height: popupHeight,
				padding: 0,
				width: 700
			},
			overlayClose:true
		});
	},
	formatFieldCode: function (input) {
		var v = input.value;
		if (v.match(/^[a-zA-Z]{3}$/) !== null) {
			input.value = v + '-';
		}
	},
	updateCourses: function () {
		$('#loading-bar').show();
		$('#results').fadeIn();
		
		$('#form-update').submit();
	}
};
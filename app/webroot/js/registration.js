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

	$( 'table.courses tr td' ).bind( 'click', function ( e ) {
		var code = $( e.currentTarget ).parent().data( 'code' );

		if ( code != '' && code != undefined ) app.Registration.getCourseInfo( code );
	} );

	$( '#modal' ).on( 'click', '.js-select-btn', app.Registration.selectCourse );

	$( '#registered-courses' ).delegate( 'a.delete-link', 'click', function ( e ) { app.Registration.removeRegisteredCourse( $( e.currentTarget ).parent().parent().data( 'nrc' ) ); } );
	$( '#selected-courses' ).delegate( 'a.delete-link', 'click', function ( e ) { app.Registration.removeSelectedCourse( $( e.currentTarget ).parent().parent().data( 'nrc' ) ); } );
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
	$( '#modal .classes-list' ).load( '/registration/getAvailableClasses/' + code, function() {
		$( '#modal .loading-classes' ).hide();
	} );
};

app.Registration.selectCourse = function ( e ) {
	var nrc = $( e.currentTarget ).closest( 'class' ).data( 'nrc' );

	loading("Ajout du cours à la sélection...");
	
	// Send AJAX request
    ajax.request({
        url:     		'/registration/selectCourse.json',
        data:           {
           	nrc: 		nrc
        },
        callback:       app.Registration.addSelectedCourse
    });
};

app.Registration.addSelectedCourse = function ( response ) {
	if ( response.status == 1 ) {				// Course selection has been saved successfully
		$.modal.close();
		
		resultMessage( "Le cours a été ajouté à votre sélection." );
		
		$( '#selected-courses .credits-total' ).html( response.credits + ' crédits' );
		$( '#selected-courses .courses-total' ).html( response.total + ' cours' );
		
		app.Registration.selectionTotal++;
	} else if ( response.status == 2 ) {		// Unknown error during course selection
		errorMessage( "Le cours n'a pas pu être ajouté à votre sélection." );
	} else if ( response.status == 3 ) {		// Unknown error during course selection
		stopLoading();
	} else if ( response.status == 4 ) {		// Error : similar course already selected
		stopLoading();

		if ( confirm( "Vous avez déjà sélectionné un cours similaire, mais avec un horaire différent. Voulez-vous le remplacer par celui-ci ?" ) ) {
			ajax.request({
		        url:     		'/registration/selectCourse.json',
		        data:           {
		            replace: 	'yes',
		           	nrc: 		nrc
		        },
		        callback:       app.Registration.addSelectedCourse
		    });
		} else {
			ajax.request({
		        url:     		'/registration/selectCourse.json',
		        data:           {
		            replace: 	'no',
		           	nrc: 		nrc
		        },
		        callback:       app.Registration.addSelectedCourse
		    });
		}
	} else if ( response.status == 5 ) {		// Error : course already registered
		errorMessage( "Vous êtes déjà inscrit à ce cours." );
	} else if ( response.status == 6 ) {		// Error : similar course already registered
		errorMessage( "Vous êtes déjà inscrit à un cours similaire, mais avec un horaire différent." );
	}
};

app.Registration.removeSelectedCourse = function ( nrc ) {
	loading("Retrait du cours de la sélection...");
		
	// Send AJAX request
    ajax.request({
        controller:     app.Registration.controllerURL,
        method:         's_unselectcourse',
        data:           {
            semester:   app.Registration.registrationSemester,
           	nrc: 		nrc
        },
        callback:       function ( response ) {
        	if ( response.status ) {
				resultMessage( "Le cours a été retiré de votre sélection." );
				
				$( '#selected-courses .credits-total' ).html( response.credits + ' crédits' );
				$( '#selected-courses .courses-total' ).html( response.total + ' cours' );
				
				app.Registration.selectionTotal--;
			} else {
				errorMessage( "Le cours n'a pas pu être enlevé de votre sélection." );
			}
        }
    });
};

app.Registration.registerCourses = function () {
	if (this.selectionTotal!=0) {
		loading("Inscription aux cours sélectionnés...");
		
		// Send AJAX request
		ajax.request({
		    controller:     app.Registration.controllerURL,
		    method:         's_registercourses',
		    data:           {
		        semester:   app.Registration.registrationSemester
		    },
		    callback:       function ( response ) {
		    	alert( response );
		    }
		});
	} else {
		errorMessage("Vous devez d'abord ajouter un cours à votre sélection pour vous inscrire.");
	}
};

app.Registration.removeRegisteredCourse = function ( nrc ) {
	var question = '';
	if (app.Registration.currentDate > app.Registration.deadline_drop_nofee) {
		question = "Si vous abandonnez le cours, vous payerez les droits de scolarité, mais vous n'aurez pas de mention d'échec.\nVoulez-vous continuer ?";
	} else if (app.Registration.currentDate > app.Registration.deadline_edit_selection) {
		question = "Si vous abandonnez le cours, vous ne payerez pas les droits de scolarité et n'aurez pas de mention d'échec.\nVoulez-vous continuer ?";
	} else {
		question = "Voulez-vous vraiment retirer ce cours de votre horaire ?";
	}

	if (confirm(question)) {
		loading("Désinscription du cours...");
		
		// Send AJAX request
		ajax.request({
		    controller:     app.Registration.controllerURL,
		    method:         's_removeregisteredcourse',
		    data:           {
		        semester:   app.Registration.registrationSemester,
		       	nrc: 		nrc
		    },
		    callback:       function ( response ) {
		    	if ( response.status ) {
					resultMessage("Vous avez été désinscrit du cours.");
					
					$( '#selected-courses .credits-total' ).html( response.credits + ' crédits' );
					$( '#selected-courses .courses-total' ).html( response.total + ' cours' );
				} else {
					errorMessage( "La désinscription du cours a échouée." );
				}
		    }
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

app.Registration.selectSemester = function ( semester ) {
	loading('Chargement des cours au programme...');
	
	document.location.hash = '#!/registration/courses/semester/' + semester;
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
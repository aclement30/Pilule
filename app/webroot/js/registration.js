// JavaScript Document
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
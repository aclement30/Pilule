// JavaScript Document
var settingsObj = {
	submitForm: function (form) {
		loading("Enregistrement en cours...");
		
		$('#form-configure-'+form).submit();
	},
	configureCallback: function (param, response, errMessage) {
		if (response == 1) {
			if (param == 'autologon' && $('#autologon').is(':checked')) {
				$('#data-storage').attr('checked','checked');
				
				$('#autologon-accounts').show();
			} else if (param == 'autologon' && (!$('#autologon').is(':checked'))) {
				$('#autologon-accounts').hide();
			}
			
			resultMessage('Les paramètres ont été enregistrés.');
			
			//setTimeout("document.location='./registration/courses'", 2000);
		} else {
			errorMessage(errMessage);
		}
	},
	eraseData: function () {
		if (confirm("Voulez-vous vraiment effacer toutes vos données des serveurs de Pilule ?")) {
			!sendData('GET','./settings/s_erasedata', '');
		}
	},
	eraseDataCallback: function () {
		resultMessage("Vos données ont été supprimées.");
		
		setTimeout("document.location='welcome/';", 1500);
	},
	s_unlinkAccount: function (account) {
		loading();
		
		!sendData('GET','./settings/s_unlinkaccount', 'account/'+account);
	}
};
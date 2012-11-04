// JavaScript Document
var settings = {
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
			
			resultMessage('Les préférences ont été enregistrées.');
			
			//setTimeout("document.location='./registration/courses'", 2000);
		} else {
			errorMessage(errMessage);
		}
	},
	unlinkAccount: function (account) {
		loading();
		
		!sendData('GET','./settings/s_unlinkaccount', 'account/'+account);
	}
};

addChild(app, 'settings', settings);
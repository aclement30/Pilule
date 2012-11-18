if ( !app ) {
    var app = {};
}

app.Settings = {};

app.Settings.submitForm = function ( form ) {
	loading( "Enregistrement en cours..." );
	
	$('#form-configure-'+form).submit();
};

// TODO : update this function to use object as param
app.Settings.configureCallback = function ( param, response, errMessage ) {
	if (response == 1) {
		if (param == 'autologon' && $('#autologon').is(':checked')) {
			$('#data-storage').attr('checked','checked');
			
			$('#autologon-accounts').show();
		} else if (param == 'autologon' && (!$('#autologon').is(':checked'))) {
			$('#autologon-accounts').hide();
		}
		
		resultMessage('Les préférences ont été enregistrées.');
	} else {
		errorMessage(errMessage);
	}
};

app.Settings.unlinkAccount = function ( account ) {
	loading();
	
	!sendData('GET','./settings/s_unlinkaccount', 'account/'+account);
};
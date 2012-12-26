 /**
  * Envoie des données à l'aide d'XmlHttpRequest?
  * @param string methode d'envoi ['GET'|'POST']
  * @param string url
  * @param string données à envoyer sous la forme var1=value1&var2=value2...
  */

var ajax = {
	loadingDisplayTimeout: 0,

	// Define default parameters
	defaults: 	{
        async:          true,
		type: 			'PUT',
		data: 			{},
		controller:		null,
		object: 		null,
		method:			null,
		url:			null,
		callback:		null
	}
};

ajax.request = function ( params ) {
	var defaults = ajax.defaults;

	// Replace default parameters with function parameters
	var p =  $.extend( defaults, params );

	if ( p.url == null ) {
		// Define AJAX URL
		p.url = p.controller + p.method + '.json';
	}

    if (p.data != null) {
	    p.data._source = 'ajax';
    } else {
        p.data = { _source : 'ajax' };
    }

	ajax.loadingDisplayTimeout = setTimeout("loading('Ouverture de la page...');", 2000);
	
	$.ajax({
        async:p.async,
		type: p.type,
		url: p.url,
		data: p.data,
		success: function (data) {
			clearTimeout( ajax.loadingDisplayTimeout );
			stopLoading();

			// Check if response contains PHP Error
			if ( data.search( 'Une erreur de fonctionnement empêche' ) >= 0 ) {
				data = stripslashes( data );
				var error = data.substr( data.search( 'Une erreur de fonctionnement empêche' ) );
				error = error.substr(error.search('</h4>'));
				error = error.substr(error.search('<strong>'));
				error = error.substr(0, error.search('<div class="clear">'));
				error = error.replace(/(<\/p>)/ig, '[r]');
				error = error.replace(/(<\/div>)/ig, '[r]');
				error = error.replace('<\h4><p>', '[r]');
				error = error.replace(/(<([^>]+)>)/ig,"");
				error = error.replace(/\[r\]/ig, '<br />');
				error = error.replace(/(<br\s\/><br\s\/><br\s\/>)/ig, '');
				errorMessage("Erreur interne : impossible d'afficher la page :<br /><blockquote>"+error+"</blockquote>");
			} else {
				if (p.callback != null && data.search('PageContent')<=0) {
					// Try to parse JSON data
					var response;
					
					try {
						response = jQuery.parseJSON(data);
					}
					catch(err) {
						app.Common.dispatchError( {
							context: 		'ajax-server-invalid-response',
							code: 			err.status,
							description: 	err.responseText
						} );
					}
					
					p.callback( response );
				} else {
					eval(data);
				}
			}
		},
		timeout: 60000,
		error: function ( request, status, message ) {
			clearTimeout( ajax.loadingDisplayTimeout );
			stopLoading();
			
			if (status == 'timeout') {
				app.Common.dispatchError( {
					context: 		'ajax-server-timeout',
					code: 			request.status,
					text: 			message,
					description: 	request.responseText
				} );
			} else {
				app.Common.dispatchError( {
					context: 		'ajax-server-error',
					code: 			request.status,
					text: 			message,
					description: 	request.responseText
				} );
			}
		},
		statusCode: {
			404: function() {
				app.Common.dispatchError( {
					context: 		'ajax-server-error',
					code: 			request.status,
					text: 			message,
					description: 	request.responseText
				} );
			},
			500: function( request, status, message ) {
				app.Common.dispatchError( {
					context: 		'ajax-server-error',
					code: 			request.status,
					text: 			message,
					description: 	request.responseText
				} );
			}
		},
	});
};

var loadingDisplayTimeout;
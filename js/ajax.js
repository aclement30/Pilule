 /**
  * Envoie des données à l'aide d'XmlHttpRequest?
  * @param string methode d'envoi ['GET'|'POST']
  * @param string url
  * @param string données à envoyer sous la forme var1=value1&var2=value2...
  */

var ajax = {
	request: function ( params ) {
		// Define default parameters
		var defaults = {
            async:          true,
			type: 			'GET',
			data: 			{},
			controller:		null,
			object: 		null,
			method:			null,
			url:			null,
			callback:		null
		}
		
		// Replace default parameters with function parameters
		var p =  $.extend(defaults, params);
		
		if (p.url == null) {
			// Define AJAX URL
			p.url = p.controller + p.method;
		}
		
		p.data._source = 'ajax';
		
		loadingDisplayTimeout = setTimeout("loading('Ouverture de la page...');", 2000);
		
		$.ajax({
            async:p.async,
			type: p.type,
			url: p.url,
			data: p.data,
			success: function (data) {
				clearTimeout(loadingDisplayTimeout);
				stopLoading();

                //alert(data);

				// Check if response contains PHP Error
				if (data.search('Une erreur de fonctionnement empêche')>=0) {
					data = stripslashes(data);
					var error = data.substr(data.search('Une erreur de fonctionnement empêche'));
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
						p.callback(jQuery.parseJSON(data));
					} else {
						eval(data);
					}
				}
			},
			timeout: 30000,
			error: function ( request, status, message ) {
				clearTimeout(loadingDisplayTimeout);
				stopLoading();
				
				if (status == 'timeout') {
					errorMessage('Le serveur n\'a pas répondu à la requête dans un délai suffisant (erreur : timeout).');
				} else {
					errorMessage('Une erreur inconnue est survenue durant l\'exécution de la fonction demandée : (' + status + ') ' + message);
				}
			},
			statusCode: {
				404: function() {
					errorMessage('Erreur inconnue : fonction introuvable sur le serveur.');
				}
			}
		});
	}
}

var loadingDisplayTimeout;
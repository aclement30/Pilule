 /**
  * Envoie des données à l'aide d'XmlHttpRequest?
  * @param string methode d'envoi ['GET'|'POST']
  * @param string url
  * @param string données à envoyer sous la forme var1=value1&var2=value2...
  */
  
var cacheGet = new Object;
cacheGet.admin = new Object;
var cachePost = new Object;
cachePost.admin = new Object;
var ajaxUrl = '';
var ajaxMethod = '';
var ajaxQuery = '';
var cacheResponse = '';

function sendData(method, url, data) {
    var xmlhttp = getHTTPObject(url);
	
    if (!xmlhttp) {
        return false;
    }
	
	ajaxMethod = method;
	ajaxUrl = url;
	
	if (method == "GET") {
		// Vérification du cache
		switch (url) {
			case 'admin/w_addcontent':
				if (cacheGet.admin.addContent) {
					eval (cacheGet.admin.addContent);
					return (true);
				}
			break;
			case 'admin/w_addcategory':
				if (cacheGet.admin.addCategory) {
					eval (cacheGet.admin.addCategory);
					return (true);
				}
			break;
			case 'admin/w_addpage':
				if (cacheGet.admin.addPage) {
					eval (cacheGet.admin.addPage);
					return (true);
				}
			break;
			case 'admin/w_addimage':
				if (cacheGet.admin.addImage) {
					eval (cacheGet.admin.addImage);
					return (true);
				}
			break;
			case 'admin/w_addlink':
				if (cacheGet.admin.addLink) {
					eval (cacheGet.admin.addLink);
					return (true);
				}
			break;
			case 'admin/w_addtable':
				if (cacheGet.admin.addTable) {
					eval (cacheGet.admin.addTable);
					return (true);
				}
			break;
		}
		
		if(data == 'null') {
			xmlhttp.open("GET", url, true); //ouverture asynchrone
		} else {
			xmlhttp.open("GET", url+"/"+data, true);
		}
		
        xmlhttp.send(null);
	} else if(method == "POST") {
		xmlhttp.open("POST", url, true); //ouverture asynchrone
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xmlhttp.send(data);
	}
    return true;
 }
 
 function getHTTPObject(url)
{
  var xmlhttp = false;

  /* Compilation conditionnelle d'IE */
  /*@cc_on
  @if (@_jscript_version >= 5)
     try
     {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
     }
     catch (e)
     {
        try
        {
           xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (E)
        {
           xmlhttp = false;
        }
     }
  @else
     xmlhttp = false;
  @end @*/

  /* on essaie de créer l'objet si ce n'est pas déjà fait */
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
  {
     try
     {
        xmlhttp = new XMLHttpRequest();
     }
     catch (e)
     {
        xmlhttp = false;
     }
  }

  if (xmlhttp)
  {
     /* on définit ce qui doit se passer quand la page répondra */
     xmlhttp.onreadystatechange=function()
     {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) /* 4 : état "complete" */
        {

			 // Traitement de la réponse.
			var response = xmlhttp.responseText;
			
			// Vérification de la validité de la réponse
			var error = 0;
			if (response.search('A PHP Error was encountered')>=0) {
				error = 1;
				
				// Affichage d'une notification d'erreur
				var errMessage = response.replace('<p>', '<p style="color: white;">');
				for (n=1; n<25; n++) errMessage = errMessage.replace('<p>', '<p style="color: white;">');
				errorNotification('Une erreur est survenue durant l\'exécution de la fonction demandée :<br /><br />'+errMessage);
				alert(errMessage);
				
			}

			if (error!=1) eval(xmlhttp.responseText);
        }
     }
  }
  return xmlhttp;
}
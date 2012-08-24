// JavaScript Document
var isMobile = 0;

function processLinks() {
	var n = 0;
    //All of a tag in the DOM
    $("a").each(function() {
        //set a temporary variable
        var thr = $(this).attr('href');
        //if exist
		if (n<10) {
			//alert(thr+' --- ('+thr.indexOf('javascript')+')');
		}
        if ((thr && thr.indexOf('javascript:')!=-1) || (thr && thr.indexOf('http:')!=-1) || (thr && thr.indexOf('https:')!=-1) || (thr && thr.indexOf('mailto:')!=-1) || (thr && thr.indexOf('documents/')!=-1) || (thr && thr.substr(0,2)=='#!') || ($(this).attr('target') == '_blank') || (thr && thr.indexOf('welcome/s_logout')!=-1) || (thr && thr.indexOf('welcome/s_changedisplay')!=-1) || $(this).hasClass('admin-page') || thr == undefined) {
			// Appel de fonction javascript
		} else {
            //Set up the browser URL
            if (thr && thr.substr(0,2) == './') {
				$(this).attr('href', '#!'+thr.substr(1));
			} else {
				$(this).attr('href', '#!'+thr.substr(0));
			}
			
			//After this, the <a> tag clicking returned false (no page load), and the browser url set.
			$(this).attr('onClick','location.hash="'+$(this).attr('href')+'";return false;');
        }
		n++;
    });
} 

function hashChange() {
	if ((dashboardObj.editMode) && dashboardObj.editMode == 1) {
		setTimeout('hashChange()',100);
		return(true);
	}
    //Check the hash change
    if (hash != (hash = document.location.hash)) {
        // Send AJAX request
		ajax.request({
			url:	'./'+hash.substr(2),
			data:	{},
			callback:	function ( response ) {
				if (typeof(response) == 'object' && response.content) {
					setPageInfo(response.pageName);
					setPageContent(response.content);
					if (response.code) eval(response.code);
				} else {
					eval (response);
				}
			}
		});
    }
	
    //checking the url hash change
    setTimeout('hashChange()',100);
}

var currentSection = 'index';
var currentPage = 'home';
var currentPageName = 'index/home';

function setPageContent (content) {
	/*if (isMobile == 1) {
		$('#rcolumn').append($('#sidebar'));
	}*/
	$('.entry-content').html(content);
	if (currentPageName != 'schedule/timetable') $('.post-content table tr:even').css('backgroundColor', '#dae6f1');
	processLinks();
	/*if (isMobile == 1) {
		$('h2.title').after($('#sidebar'));
	}*/
}

function setPageInfo (pageName) {
	pageName = pageName.split('/');
	
	$('#secondary-menu li.current-menu-item').toggleClass('current-menu-item');
	$('#secondary-menu li.tab-'+pageName[0]).toggleClass('current-menu-item');
	//$('#sidebar-content ul li a.link-'+pageName[0]).parent().toggleClass('selected');
	
	if (isMobile == 1) {
		switch (currentSection) {
			case 'studies':
				$('#header-bottom .link-studies').attr('src', './images/mobile/menu-1.png');
			break;
			case 'schedule':
				$('#header-bottom .link-schedule').attr('src', './images/mobile/menu-2.png');
			break;
			case 'fees':
				$('#header-bottom .link-fees').attr('src', './images/mobile/menu-4.png');
			break;
		}
	}
	
	if ((currentSection != pageName[0] && (isMobile == 0 || pageName[0] != 'welcome')) || (isMobile == 1 && pageName[0] != 'welcome')) {
		// Demande du nouveau menu de droite
		if (pageName[0] != 'admin') {
			sendData('GET','./'+pageName[0]+'/getMenu', '');
		} else if (pageName[1] == 'dashboard') {
			sendData('GET','./'+pageName[0]+'/getMenu', '');
		} else {
			sendData('GET','./admin/'+pageName[1]+'/getMenu', '');
		}
	}
	
	if (isMobile == 1 && pageName[0] == 'welcome') {
		$('#rcolumn').hide();
	} else {
		$('#rcolumn').show();
	}
	
	currentSection = pageName[0];
	currentPage = pageName[1];
	currentPageName = pageName.join('/');
	
	$('#rcolumn li a.active').toggleClass('active');
	$('#rcolumn li a.link-'+currentPage).toggleClass('active');
	
	if (isMobile == 1) {
		if (currentSection != 'welcome') {
			$('#header-bottom').show();
		} else {
			$('#header-bottom').hide();
		}
		switch (currentSection) {
			case 'studies':
				$('#header-bottom .link-studies').attr('src', './images/mobile/menu-1-active.png');
			break;
			case 'schedule':
				$('#header-bottom .link-schedule').attr('src', './images/mobile/menu-2-active.png');
			break;
			case 'fees':
				$('#header-bottom .link-fees').attr('src', './images/mobile/menu-4-active.png');
			break;
		}
	}
	
	if(typeof _gaq !== 'undefined')
			_gaq.push(['_trackPageview', document.location.hash.substr(2)]);
}

function refreshPage () {
	hash = document.location.hash;
	getPage(hash.substr(2));
}

function getPage (url) {
	sendData('GET','./'+url,'');
	
	stopLoading();
}

function updateMenu () {
	$('#sidebar-content ul li a.active').toggleClass('active');
	$('#sidebar-content ul li a.link-'+currentPage).toggleClass('active');
	processLinks();
	
	if (isMobile == 1) {
		//$('#sidebar-content ul li a.active').parent().toggleClass('selected');
		$('#sidebar-content ul li a.link-'+currentPage).parent().toggleClass('selected');
	}
}

var hash = '';
$(document).ready(function(){
    processLinks();
    hashChange();
}); 

function reportBug () {
	if ($(window).height()<650) {
		var popupHeight = $(window).height() - 50;
	} else {
		var popupHeight = 600;
	}

	loading();

	var src = './support/w_reportbug';
	$.modal('<iframe src="' + src + '" height="'+popupHeight+'" width="550" style="border:0;" onload="javascript:stopLoading();">', {
		containerCss:{
			backgroundColor:"#fff",
			borderColor:"#fff",
			height: popupHeight,
			padding: 0,
			width: 550
		},
		overlayClose:true
	});
}

function sendBugReport () {
	formReportError = 0;
	
	checkReportField('report-url');
	checkReportField('report-email');
	
	if (formReportError==0) {
		$('#form-report #loading-img').show();
		
		// Envoi du formulaire
		document.getElementById('form-report').submit();
	}
}

var formReportError = 0;

function checkReportField (field) {
	var value = document.getElementById(field).value;
	document.getElementById('error-message-'+field).style.display = 'none';
	
	switch (field) {
		case 'report-url':
			if (value=='') {
				document.getElementById('error-message-'+field).style.display = 'block';
				formReportError = 1;
			}
		break;
		case 'report-email':
			if (value=='') {
				document.getElementById('error-message-'+field).style.display = 'block';
				formReportError = 1;
			}
		break;
	}
}

function reloadData (name, auto) {
	if (isMobile == 0) {
		if (auto == 1) {
			loading('Actualisation des données');
		} else {
			loading('Chargement des données depuis Capsule...');
		}
	} else {
		$('#loading-message').slideDown();
	}
	
	!sendData('POST', 'cache/s_reloaddata', 'name='+name+'&auto='+auto);
}

function statusReload (response, auto, message) {
	if (response==1) {
		if (isMobile == 1) {
			$('#loading-message').slideUp();
		}
		
		refreshPage();
		if (auto != 1) resultMessage("Les données ont été actualisées.");
	} else {
		errorMessage(message);
	}
}

function statusRefreshData (response) {
	if (response==1) {
		refreshPage();
		//resultMessage("Les données ont été actualisées.");
	}
}

function getScrollTop() {
  if ( document.documentElement.scrollTop )
    return document.documentElement.scrollTop;

  return document.body.scrollTop;
}

function scrollHandler() {
   var e = document.getElementById('notification');
   e.style.top = getScrollTop();
}

function notification (type, message) {
	if (isMobile == 1) return(true);
	
  var elem = document.getElementById('notification');
  elem.style.display = 'block'; 
  elem.style.visibility = 'visible';

  if ( elem.currentStyle && 
       elem.currentStyle.position == 'absolute' ) 
  {
    elem.style.top = getScrollTop();
    window.onscroll = scrollHandler;
  }

elem.style.paddingBottom = '15px';

	switch (type) {
		case 'loading':
			elem.innerHTML = '<img src="./images/loading-small.gif" style="position: relative; top: -7px; left: -7px; display: block; float: left;" /><div style="float: left; margin-left: 15px;">'+message+'</div><div style="clear: both;"></div>';
			elem.style.paddingBottom = '5px';
		break;
		case 'result':
			elem.innerHTML = '<img src="./images/true.png" style="position: relative; top: 0px; display: block; float: left;" /><div style="float: left; margin-left: 15px;">'+message+'</div><div style="clear: both;"></div>';
			setTimeout("hideNotification()", 1500);
		break;
		case 'error':
			elem.innerHTML = '<img src="./images/false.png" style="position: relative; top: 0px;  display: block; float: left;" /><div style="float: left; margin-left: 15px;">'+message+'</div><div style="clear: both;"></div>';
			setTimeout("hideNotification()", 1500);
		break;
	}
}

function stopLoading () {
	hideNotification();
}

function loading (message) {	
	if (message==undefined) message = 'Chargement des données...';
	notification('loading', message);
}

function resultMessage (message) {
	hideNotification();
	
	notification('result', message);
}

function errorMessage (message) {
	hideNotification();
	
	notification('error', message);
}

function hideNotification() {
  var elem = document.getElementById('notification');
  if (elem) {
	  elem.style.display = 'none';
	  elem.style.visibility = 'hidden';
  }
  window.onscroll = null;
}

function addslashes(str) {
str=str.replace(/\\/g,'\\\\');
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\0/g,'\\0');
return str;
}
function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\0/g,'\0');
	str=str.replace(/\\\\/g,'\\');
	str=str.replace(/\t/g, "\t");
	str=str.replace(/\\\//g, '\\');
	return str;
}

function displayStatsGraph ( graph ) {
	$.getScript("./admin/stats/ajax_displayGraphs/"+graph)
	/*
	// Send AJAX request
	ajax.request({
		url:	'./admin/stats/ajax_displayGraphs/'+graph,
		data:	{},
		callback:	function ( response ) {
			if (response.code) {
				eval (response.code);
			}
		}
	});
	*/
}
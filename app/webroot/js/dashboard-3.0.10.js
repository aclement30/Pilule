if ( !app ) {
    var app = {};
}

app.Dashboard = {
    controllerURL:  './welcome/'
};

app.Dashboard.goTo = function ( moduleName ) {
    document.location.hash = $( '#module-' + moduleName + '-action' ).val();
};

app.Dashboard.connectTo = function ( url ) {
    loading('Ouverture de la page...');

    !sendData('GET',url, '');
};

app.Dashboard.edit = function () {
    // Notify Google Analytics of Dashboard-edit action
    _gaq.push(['_trackEvent', 'Dashboard', 'Edit', 'Modification du Tableau de bord']);

    $('.action-buttons .buttons a').tooltip('hide');
    $('.action-buttons a.js-edit-btn' ).hide();
    $('.action-buttons a.js-save-btn' ).fadeIn();
    
    // Display hidden dashboard modules
    $( 'ul.dashboard' ).addClass( 'edit-mode' );

    var modules = $( 'ul.dashboard li' );

	$.each( modules, function( index, module ) {
		var link = $( module ).find( 'a' );

		// Unbind default click event
		link.off( 'click' );

		// Bind toggle event
		link.on( 'click', app.Dashboard.toggleModule );
	} );
};

app.Dashboard.save = function () {
    // Display save notice when page is refreshed
    loadContentCallback = function () { resultMessage( 'Les modifications au tableau de bord ont été enregistrées.' ); }

    $('.action-buttons .buttons a').tooltip('hide');
    $('.action-buttons a.js-save-btn' ).hide();
    $('.action-buttons a.js-edit-btn' ).fadeIn();

    // Hide disabled dashboard modules
    $( 'ul.dashboard' ).removeClass( 'edit-mode' );

    $( 'ul.dashboard li a' ).off( 'click' );

    app.Dashboard.initModules();
};

app.Dashboard.toggleModule = function ( e ) {
	var module = $( e.currentTarget ).closest( 'li' );

	// Toggle enabled/disabled class
    if ( module.hasClass( 'enabled' ) ) {
    	module.removeClass( 'enabled' ).addClass( 'disabled' );
    } else {
    	module.removeClass( 'disabled' ).addClass( 'enabled' );
    }

    // Get the list of all enabled modules
    var enabledModules = new Array();
    var modules = $( 'ul.dashboard li.enabled' );

	$.each( modules, function( index, module ) {
		enabledModules.push( $( module ).data( 'id' ) );
	});

	// Update enabled modules list via AJAX
    ajax.request({
        type:           'PUT',
        url:     		'users/saveDashboard.json',
        data:           {
            enabledModules:         enabledModules
        },
        callback:       function ( response ) { }
    });

    return false;
};

app.Dashboard.initModules = function () {
	var modules = $( '.dashboard .module:not(.offline)' );

	$.each( modules, function( index, module ) {
		if ( $( module ).hasClass( 'external' ) ) {
			var link = $( module ).find( 'a' );
			
			if ( $( module ).data( 'target' ) == '_blank' ) {
				// Do nothing
				return true;
			} else {
				// Open external website in an iframe on the current page
				$( module ).find( 'a' ).on( 'click', function( e ) {
					app.Common.openExternalWebsite( $( module ).data( 'url' ) );

					return false;
				} );
			}
		} else {
			$( module ).find( 'a' ).on( 'click', function( e ) {
				document.location = $( module ).data( 'url' );

				return false;
			} );
		}
	} );
};

app.Dashboard.init = function () {
	app.Dashboard.initModules();

	$('.action-buttons a.js-save-btn' ).hide();

	// Init sidebar links
	$( '.aside a.link-capsule-address' ).on( 'click', function( e ) {
		app.Common.openExternalWebsite( app.baseUrl + 'services/capsule-address/' );

		return false;
	} );
	$( '.aside a.link-capsule-password' ).on( 'click', function( e ) {
		app.Common.openExternalWebsite( 'https://oraweb.ulaval.ca/pls/prv/prv_changement_nip.changer_nip_personnel_1?' );

		return false;
	} );
	$( '.aside a.link-capsule-fiscal-statement' ).on( 'click', function( e ) {
		app.Common.openExternalWebsite( app.baseUrl + 'services/capsule-fiscal-statement/' );

		return false;
	} );
	$( '.aside a.link-capsule-admission' ).on( 'click', function( e ) {
		app.Common.openExternalWebsite( app.baseUrl + 'services/capsule-admission/' );

		return false;
	} );
};

$( document ).ready( function() { app.Dashboard.init(); } );
/*
// JavaScript Document
var dashboardObj = {
	dataList: new Array(),
	currentLoadingItem: '',
	currentLoadingNum: 0,
	loadingTry: 0,
	editMode: 0,
	goTo: function (moduleName) {
		if ($('#module-'+moduleName).hasClass('loading')) {
			switch (moduleName) {
				case 'studies':
					this.currentLoadingNum = 0;
					
					this.loadingTry = 0;
					
					$('#module-studies .loading-error').hide();
					$('#module-studies .loading').fadeIn();
					
					this.loadItem();
				break;
				case 'schedule':
					this.currentLoadingNum = 2;
					
					this.loadingTry = 0;
					
					$('#module-schedule .loading-error').hide();
					$('#module-schedule .loading').fadeIn();
					
					this.loadItem();
				break;
				case 'fees':
					this.currentLoadingNum = 3;
					
					this.loadingTry = 0;
					
					$('#module-fees .loading-error').hide();
					$('#module-fees .loading').fadeIn();
					
					this.loadItem();
				break;
			}
		} else {
			$('#modules').hide();
			$('#loading-panel').show();
			
			document.location.hash = $('#module-'+moduleName+'-action').val();
		}
	},
	loadItem: function () {
		this.currentLoadingItem = this.dataList[this.currentLoadingNum];
		
		this.loadingTry++;
		
		// Demande de chargement du contenu pour chaque variable
		!sendData('POST','./cache/s_reloadData', 'name='+this.currentLoadingItem);
	},
	mouseOver: function (n, type) {
		if ($('#box-'+n).parent().attr('id') == 'modules' && (!$('#box-'+n).parent().hasClass('ui-sortable'))) {
			if (type==1) {
				$('#box-'+n).css('backgroundColor', '#999');
				$('#box-'+n+' a').css('color', '#fff');
			} else {
				$('#box-'+n).css('backgroundColor', '#efefef');
				$('#box-'+n+' a').css('color', '#444');
			}
		}
	},
	mouseDown: function (n, type) {
		if (type==1) {
			$('.box'+n).css('opacity', '0.5');
			$('.box'+n).css('-moz-opacity', '0.5');
			
			//$('.box'+n+' a').css('color', '#fff');
		} else {
			$('.box'+n).css('opacity', '1');
			$('.box'+n).css('-moz-opacity', '1');
			//$('.box'+n+' .img-link').css('backgroundColor', '#fff');
			//$('.box'+n+' a').css('color', '#444');
		}
	},
	connectTo: function (url) {
		loading('Ouverture de la page...');
		
		!sendData('GET',url, '');
	},
	askCredentials: function (service) {
		var src = 'https://www.pilule.ulaval.ca/services/askCredentials/'+service;
		var popupHeight = 360;
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
	addRegistrationModule: function () {
		loading();
		
		this.saveModules();
		
		setTimeout("!sendData('GET','./welcome/s_addRegistrationModule', '')", 500);
	},
	edit: function () {
		loading();
		
		!sendData('GET','./welcome/s_getAvailableModules', '');
	},
	saveModules: function () {
		var modules_list = '';
		
		// Enregistrement de la liste des modules
		var modules = $('#modules').find('li');
		
		$.each(modules, function(key, value) { 
			modules_list += ','+$(value).attr('id');
		});
		
		!sendData('POST','./welcome/s_saveDashboard', 'modules='+encodeURIComponent(modules_list));
	},
	unlockModules: function () {
		// Ajout de modules
		//$("#available-modules ul .module").draggable({ containment: $('.entry-content'), helper: 'clone', scope: 'modules', revert: 'invalid', connectToSortable: '.modules-list', opacity: 0.35});
		
		$(".modules-list").sortable({
									cursor: 'move',
									containment: $('.entry-content'),
									scope: 'modules',
									revert: 'invalid',
									connectToSortable: '.modules-list',
									opacity: 0.35,
									connectWith: '.modules-list',
									placeholder: 'ui-state-highlight',
									tolerance: 'pointer',
									forcePlaceholderSize: true,
									stop: function(e, ui) {
										if (ui.item.parent().attr('id') == 'modules') {
											ui.item.removeClass('available');
											ui.item.css('backgroundColor', '#efefef');
											
											dashboardObj.saveModules();
										} else {
											ui.item.addClass('available');
											ui.item.css('backgroundColor', '#fff');
										}
									}
									});
		
		// Suppression de modules
		//$("#modules .module").draggable({ containment: $('.entry-content'), helper: 'clone', scope: 'modules', revert: 'invalid', connectToSortable: '.modules-list'});
		
		$( ".modules-list" ).disableSelection();
		$( ".modules-list li" ).disableSelection();
		
		$(".modules-list li a").css('cursor', 'move');
		$(".modules-list li a").bind('click', function() {
			return false;
		});
		
		this.editMode = 1;
	},
	lockModules: function () {
		this.saveModules();
		
		$('#available-modules').slideUp();
		
		$(".modules-list").sortable( "destroy" );
		
		$( ".modules-list" ).enableSelection();
		$( ".modules-list li" ).enableSelection();
		
		$(".modules-list li a").css('cursor', 'pointer');
		
		$(".modules-list li a").unbind('click');
		
		$('#edit-dashboard-link').show();
		
		processLinks();
		this.editMode = 0;
		document.location.hash = '#!/dashboard/';
		hash = '#!/dashboard/';
	}
};

addChild(app, 'dashboard', dashboard);*/
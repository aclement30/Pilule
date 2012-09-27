function displaySubmenu ( object ) {
    var submenu = $(object).siblings('ul');
    var li = $(object).parents('li');
    var submenus = $('#sidebar li.submenu ul');
    var submenus_parents = $('#sidebar li.submenu');
    if(li.hasClass('open'))
    {
        if(($(window).width() > 768) || ($(window).width() < 479)) {
            submenu.slideUp();
        } else {
            submenu.fadeOut(250);
        }
        li.removeClass('open');
    } else
    {
        if(($(window).width() > 768) || ($(window).width() < 479)) {
            submenus.slideUp();
            submenu.slideDown();
        } else {
            submenus.fadeOut(250);
            submenu.fadeIn(250);
        }
        submenus_parents.removeClass('open');
        li.addClass('open');
    }
}

$(document).ready(function(){
    // === Sidebar navigation === //

    $('.submenu > a').click(function(e)
    {
        e.preventDefault();
        var submenu = $(this).siblings('ul');
        var li = $(this).parents('li');
        var submenus = $('#sidebar li.submenu ul');
        var submenus_parents = $('#sidebar li.submenu');
        if(li.hasClass('open'))
        {
            if(($(window).width() > 768) || ($(window).width() < 479)) {
                submenu.slideUp();
            } else {
                submenu.fadeOut(250);
            }
            li.removeClass('open');
        } else
        {
            if(($(window).width() > 768) || ($(window).width() < 479)) {
                submenus.slideUp();
                submenu.slideDown();
            } else {
                submenus.fadeOut(250);
                submenu.fadeIn(250);
            }
            submenus_parents.removeClass('open');
            li.addClass('open');
        }
    });

    var ul = $('#sidebar > ul');

    $('#sidebar > a').click(function(e)
    {
        e.preventDefault();
        var sidebar = $('#sidebar');
        if(sidebar.hasClass('open'))
        {
            sidebar.removeClass('open');
            ul.slideUp(250);
        } else
        {
            sidebar.addClass('open');
            ul.slideDown(250);
        }
    });

    // === Resize window related === //
    $(window).resize(function()
    {
        if($(window).width() > 479)
        {
            ul.css({'display':'block'});
            $('#content-header .btn-group').css({width:'auto'});
        }
        if($(window).width() < 479)
        {
            ul.css({'display':'none'});
            fix_position();
        }
        if($(window).width() > 768)
        {
            $('#user-nav > ul').css({width:'auto',margin:'0'});
        }

        app.resizeExternalFrame();
    });

    if($(window).width() < 468)
    {
        ul.css({'display':'none'});
        fix_position();
    }
    if($(window).width() > 479)
    {
        ul.css({'display':'block'});
    }

    // === Fixes the position of buttons group in content header and top user navigation === //
    function fix_position()
    {
        var uwidth = $('#user-nav > ul').width();
        $('#user-nav > ul').css({width:uwidth,'margin-left':'-' + uwidth / 2 + 'px'});
    }

    $(window).on("load",function(){
        fixLayout();
    });
    $(window).on("resize",function(){
        fixLayout();
    });

    //FLIP SIDEBAR ACTIVE
    $('.sideBar>ul>li>figure:first-child').on("click",function(){
        if(!$(this).parent().find('.subSide').length){
            $('.subSide').slideUp();
        }
        var ac = $('.sideBar > ul >li.active')[0];
        $(this).parent().addClass('active');
        $(ac).removeClass('active');

    });

    //BIND DROPPER SIDEBAR ACTIONS
    $('.sideBar > ul > li.dropper >figure:first-child').on("click",function(){
        $(this).parent().find('.subSide').slideToggle(300);
        var ac = $('.sideBar > ul >li.active')[0];

        var par = $(this).parent()[0];
        $(ac).removeClass('active');
        $(par).addClass('active');
    });

    //Switch NavLinks Activation State
    $('.nav li').on("click",function(e){
        var active = $('.nav li.active')[0];
        $(active).removeClass('active');
        $(this).addClass('active');
    });


    if (!login) {
        Path.map("#!/dashboard").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-dashboard').addClass('active');

            loadContent('/welcome/dashboard');
        }).enter(clearPanel);

        Path.map("#!/dashboard/edit").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-dashboard').addClass('active');

            loadContent('/welcome/dashboard_edit');
        }).enter(clearPanel);

        Path.map("#!/studies").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-studies').addClass('active');

            loadContent('/studies');
        }).enter(clearPanel);

        Path.map("#!/studies/details").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-studies').addClass('active');

            loadContent('/studies/details');
        }).enter(clearPanel);

        Path.map("#!/studies/report").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-studies').addClass('active');

            loadContent('/studies/report');
        }).enter(clearPanel);

        Path.map("#!/schedule").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-schedule').addClass('active');

            loadContent('/schedule');
        }).enter(clearPanel);

        Path.map("#!/schedule/:semester").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-schedule').addClass('active');

            loadContent('/schedule/'+this.params['semester']);
        }).enter(clearPanel);

        Path.map("#!/fees").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-tuitions').addClass('active');

            loadContent('/fees');
        }).enter(clearPanel);

        Path.map("#!/fees/details").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-tuitions').addClass('active');

            loadContent('/fees/details');
        }).enter(clearPanel);

        Path.map("#!/fees/details/:semester").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-tuitions').addClass('active');

            loadContent('/fees/details/'+this.params['semester']);
        }).enter(clearPanel);

        Path.map("#!/settings").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-settings').addClass('active');

            loadContent('/settings');
        }).enter(clearPanel);

        Path.map("#!/support/terms").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-terms').addClass('active');

            loadContent('/support/terms');
        }).enter(clearPanel);

        Path.map("#!/support/privacy").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-privacy').addClass('active');

            loadContent('/support/privacy');
        }).enter(clearPanel);

        Path.map("#!/support/faq").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-faq').addClass('active');

            loadContent('/support/faq');
        }).enter(clearPanel);

        Path.map("#!/support/contact").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-contact').addClass('active');

            loadContent('/support/contact');
        }).enter(clearPanel);


        Path.root("#!/dashboard");

        Path.listen();
    } else {
        Path.map("#!/support/terms").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-terms').addClass('active');

            loadContent('/support/terms');
        }).enter(clearPanel);

        Path.map("#!/support/privacy").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-privacy').addClass('active');

            loadContent('/support/privacy');
        }).enter(clearPanel);

        Path.map("#!/support/faq").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-faq').addClass('active');

            loadContent('/support/faq');
        }).enter(clearPanel);

        Path.map("#!/support/contact").to(function(){
            var active = $('.nav li.active')[0];
            $(active).removeClass('active');
            $('.nav .link-support-contact').addClass('active');

            loadContent('/support/contact');
        }).enter(clearPanel);

        Path.listen();
    }
});

function refreshPage (pageRefreshEffect) {
    switch (document.location.hash) {
        case '#!/studies':
            loadContent('/studies', pageRefreshEffect);
            break;
        case '#!/studies/details':
            loadContent('/studies/details', pageRefreshEffect);
            break;
        case '#!/studies/report':
            loadContent('/studies/report', pageRefreshEffect);
            break;
        case '#!/schedule':
            loadContent('/schedule', pageRefreshEffect);
            break;
        case '#!/fees':
            loadContent('/fees', pageRefreshEffect);
            break;
        case '#!/fees/details':
            loadContent('/fees/details', pageRefreshEffect);
            break;
    }
}

function clearPanel () {

}

function loading () {

}

function stopLoading () {

}

function displayActionButtons ( items ) {
    var buttons = '';

    $.each(items, function(index, value) {
        if (value) {
            var tooltipData = false;

            if (value.type) {
                switch (value.type) {
                    case 'refresh':
                        // Vérification de la disponibilité de Capsule
                        if (app.isCapsuleOffline) return true;

                        value.title = '<div class="btn-refresh"><i class="icon-refresh"></i><img src="./img/loading-btn.gif" style="position: relative; height:  14px; top: -3px;" /></div>';
                        value.tip = 'Actualiser les données';
                        break;
                    case 'print':
                        value.title = '<div><i class="icon-print"></i></div>';
                        value.tip = 'Imprimer la page';
                        break;
                    case 'download':
                        value.title = '<div><i class="icon-download-alt"></i></div>';
                        break;
                    case 'edit':
                        value.title = '<div><i class="icon-pencil"></i></div>';
                        break;
                    case 'share':
                        value.title = '<div><i class="icon-share-alt"></i></div>';
                        break;
                    case 'save':
                        value.title = '<div><i class="icon-ok"></i></div>';
                        break;
                }
            }

            buttons += '<a class="btn btn-large';
            if (value.tip) buttons += '';
            buttons += '" href="javascript:' + value.action + '"';
            if (tooltipData) buttons += ' tip-left';
            if (value.tip) {
                if (index == (items.length-1)) {
                    buttons += ' data-placement="left"';
                } else {
                    buttons += ' data-placement="bottom"';
                }
                buttons += ' data-title="' + value.tip + '"';
            }
            buttons += '>' + value.title + '</a>';
        }
    });

    $('.action-buttons .buttons').html(buttons);
    $('.action-buttons .buttons').show();
    $('.action-buttons .buttons a').tooltip();
}

function displayBreadcrumb ( pages ) {
    var breadcrumb = '';

    $.each(pages, function(index, value) {
        if (value.url == '#!/dashboard') value.title = '<i class="icon-home"></i>' + value.title;

        if (index != (pages.length-1)) {
            breadcrumb += '<a href="' + value.url + '">' + value.title + '</a>';
        } else {
            breadcrumb += '<a href="' + value.url + '" class="current">' + value.title + '</a>';
        }
    });

    $('#breadcrumb').html(breadcrumb);
    $('#breadcrumb').show();
}

function fixLayout (){
    if ($('#content').height() < $(window).height()) {
        $('#content').css('minHeight', ($(window).height()-170));
    } else {
        $('#content').css('minHeight', 'auto');
    }
    //$('#content').css('height',getDocHeight()-170+'px');
    //$('#sideBar').css('height',getDocHeight()-170+'px');
}

var isMobile = 0;
var pageRefresh = false;
var loadContentCallback = null;

function loadContent(url, pageRefreshEffect) {
    if (pageRefreshEffect) pageRefresh = true;

    switch (url) {
        case '/studies':
        case '/studies/details':
        case '/studies/report':
            $('#sidebar li.submenu ul').not('.link-studies ul').slideUp();
            $('#sidebar li.submenu').not('.link-studies').removeClass('open');
            break;
        case '/fees':
        case '/fees/details':
            $('#sidebar li.submenu ul').not('.link-tuitions ul').slideUp();
            $('#sidebar li.submenu').not('.link-tuitions').removeClass('open');
            break;
        default:
            $('#sidebar li.submenu ul').slideUp();
            $('#sidebar li.submenu').removeClass('open');
            break;
    }

    // Envoi de la requête AJAX pour obtenir le contenu à afficher
    ajax.request({
        url:        url,
        callback:   function ( response ) {
            // Masquer le layer de contenu pour faire un effet d'apparition
            if (pageRefresh) $('#content-layer').hide();

            if (response.content) {
                $('h1').html(response.title);
                $('#content-layer .content-inside').html(response.content);

                // Affichage du fil d'Ariane
                if (response.breadcrumb) displayBreadcrumb(response.breadcrumb);

                // Affichage des boutons à gauche du titre, s'il y a lieu
                if (response.buttons) {
                    displayActionButtons(response.buttons);
                } else {
                    $('.action-buttons .buttons').hide();
                }

                // Si les données ont expirées, appeler la fonction d'actualisation des données
                if (response.reloadData) app.cache.reloadData([{name: response.reloadData, auto: 1}]);

                // S'il y a lieu, exécuter le code JS
                if (debug == 1) alert(response.content);
                if (debug == 1) alert(response.code);
                if (response.code) eval(response.code);

                // Affichage du timestamp des données
                if (response.timestamp) {
                    $('#content-header .timestamp').html('Données actualisées : il y a ' + response.timestamp + '.');
                    $('#content-header .timestamp').show();
                } else {
                    $('#content-header .timestamp').hide();
                }

                if (pageRefresh) {
                    // Effet d'apparition de la page
                    $('#content-layer').fadeIn('normal', function () {
                        fixLayout();
                    });

                    pageRefresh = false;
                } else {
                    fixLayout();
                }

                // Si un callback a été défini par Javascript, exécution du callback
                if (loadContentCallback) {
                    (loadContentCallback)();
                    loadContentCallback = null;
                }

                if(typeof _gaq !== 'undefined')
                    _gaq.push(['_trackPageview', document.location.hash.substr(2)]);
            } else {
                if (response.error) {
                    errorMessage ('Erreur interne : impossible d\'afficher la page demandée.');
                }
            }
        }
    });
}

var alertObject;

function resultMessage ( message, object ) {
    if (object === undefined || object == null) {
        alertObject = $('#content-layer .alert.alert-success');
    } else {
        alertObject = object;
    }

    $('#content .alert').hide();
    alertObject.html( message );
    alertObject.fadeIn();
    setTimeout('alertObject.hide()', 2000);
}

var alertErrorObject;

function errorMessage ( message, object, autoHide ) {
    if (autoHide == undefined) autoHide = true;
    stopLoading();

    if (object === undefined || object == null) {
        alertErrorObject = $('#content-layer .alert.alert-error');
    } else {
        alertErrorObject = object;
    }

    $('#content-layer .alert').hide();
    alertErrorObject.html( message );
    alertErrorObject.fadeIn();

    if (autoHide) {
        setTimeout('alertErrorObject.hide();', 2500);
    }
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

/* --------- FEATURED ON JAMES PADOLSEY's Website ------------*/
function getDocHeight() {
   return ($(window).height());
}
function getDocWidth() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollWidth, D.documentElement.scrollWidth),
        Math.max(D.body.offsetWidth, D.documentElement.offsetWidth),
        Math.max(D.body.clientWidth, D.documentElement.clientWidth)
    );
}
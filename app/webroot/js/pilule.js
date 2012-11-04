// Fonction d'ajout d'un objet à un parent
function addChild(ob, childName, childOb) {
    ob[childName] = childOb;
    childOb.parent = ob;
}

var app = {
    // Définition des propriétés de l'application
    isCapsuleOffline:       false,                 // Disponibilité de Capsule

    init: function () {
        // Si Capsule est hors ligne, affichage de la notice d'information
        if (this.isCapsuleOffline) $('.capsule-offline').show();
    },
    resizeExternalFrame: function () {
        $('#external-frame').css('width', $(window).width());
        $('#external-frame').css('height', $(window).height()-42);
    },
    closeExternalFrame: function () {
        $('#external-frame').fadeOut();
        $('#external-frame').attr('src', 'blank.html');
        $('#user-nav .nav.external-frame').hide();
        $('#user-nav .nav.external-frame li').removeClass('active');
        $('#user-nav .nav:not(.external-frame)').fadeIn();
        $('#sidebar').show();
        $('#header h1').removeClass('small');
    }
};

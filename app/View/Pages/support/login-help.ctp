<h5><i class="icon-hdd"></i> 1. Disponibilité des serveurs</h5>
<p>Vérifiez d'abord que le serveur de Capsule est disponible. Lorsque Capsule est hors ligne (ex : pour maintenance), Pilule vérifie vos identifiants en essayant de vous connecter via le serveur Exchange. Notez que pour que cette méthode alternative fonctionne, vous devez préalablement vous être connecté au moins une fois avec succès alors que Capsule était disponible.</p>
<p>Si les deux serveurs sont indisponibles, la connexion à Pilule sera impossible puisqu'il est impossible de vérifier vos identifiants.</p>
<br />
<div class="row-fluid">
    <div class="span2">&nbsp;</div>
    <div class="span4 server-name">Serveur de Capsule&nbsp;&nbsp;&nbsp;&nbsp;
        <?php if ( $serversAvailability[ 'capsule' ] ) : ?>
            <span class="label label-success">Disponible</span>
        <?php else : ?>
            <span class="label label-important">Indisponible</span>
        <?php endif; ?>
    </div>
    <div class="span4 server-name">Serveur Exchange UL&nbsp;&nbsp;&nbsp;&nbsp;
        <?php if ( $serversAvailability[ 'exchange' ] ) : ?>
            <span class="label label-success">Disponible</span>
        <?php else : ?>
            <span class="label label-important">Indisponible</span>
        <?php endif; ?>
    </div>
    <div class="span2">&nbsp;</div>
</div>

<h5><i class="icon-exclamation-sign"></i> 2. Informations de connexion</h5>
<p>Vous l'avez probablement déjà essayé, mais au cas où :</p>
<ul>
    <li>Videz la <a href="http://www.commentcamarche.net/faq/3037-vider-le-cache-internet" target="_blank">mémoire cache de votre navigateur</a>. Ils arrivent que des vieux fichiers s'accumulent et causent des erreurs.</li>
    <li>Vérifiez que la touche Caps Lock n'est pas activée.</li>
    <li>Assurez-vous d'avoir les bons identifiants en main (IDUL et mot de passe de Capsule).</li>
    <li>Essayez de vous <a href="https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin" target="_blank">connecter sur Capsule</a>.</li>
    <li>Si c'est votre première visite sur Pilule, assurez-vous de vous être connecté au moins une fois sur Capsule au préalable. Les étudiants qui n'ont jamais ouvert de session utilisateur sur Capsule obtiendront une erreur à la connexion sur Pilule.</li>
</ul>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                <span class="icon"><i class="icon-hdd"></i></span>
                <h5>1. Disponibilité des serveurs</h5>
            </div>
            <div class="widget-content">
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
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                <span class="icon"><i class="icon-exclamation-sign"></i></span>
                <h5>2. Informations de connexion</h5>
            </div>
            <div class="widget-content">
                <p>Vous l'avez probablement déjà essayé, mais au cas où :</p>
                <ul>
                    <li>Vérifiez que la touche Caps Lock n'est pas activée.</li>
                    <li>Assurez-vous d'avoir les bons identifiants en main (IDUL et mot de passe de Capsule).</li>
                    <li>Essayez de vous <a href="https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin" target="_blank">connecter sur Capsule</a>.</li>
                    <li>Videz la <a href="http://www.commentcamarche.net/faq/3037-vider-le-cache-internet" target="_blank">mémoire cache de votre navigateur</a>. Ils arrivent que des vieux fichiers s'accumulent et causent des erreurs.</li>
                    <li>Si c'est votre première visite sur Pilule, assurez-vous de vous être connecté au moins une fois sur Capsule au préalable. Les étudiants qui n'ont jamais ouvert de session utilisateur sur Capsule obtiendront une erreur à la connexion sur Pilule.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box" style="margin-bottom: 0px;">
            <div class="widget-title">
                <span class="icon"><i class="icon-remove"></i></span>
                <h5>3. Réinitialiser vos données</h5>
            </div>
            <div class="widget-content">
                <p>Si vous vous êtes déjà connecté à Pilule antérieurement ou que vous avez réussi à vous connecter partiellement, et que vous restez bloqués à l'étape du chargement des données ou de la connexion, vous pouvez essayer de réinitialiser vos données sur Pilule. Pilule tentera alors de récupérer vos données lors de votre prochaine connexion.</p>
                <p style="font-weight: bold;">Cette action supprimera toutes les données vous concernant sur les serveurs de Pilule, mais n'aura aucun impact sur Capsule. Votre dossier scolaire, votre horaire, vos notes et autres informations contenues dans le système de gestion des études de l'Université Laval resteront intactes ; seules les données stockées temporairement sur les serveurs de Pilule seront effacées.</p>
                <p>Veuillez remplir les champs ci-dessous pour vous identifier et supprimer vos données :</p>
                <?php echo $this->Form->create( 'Reset', array( 'type' => 'post', 'class' => 'clearfix', 'inputDefaults' => array( 'div' => false ) ) ); ?>
                    <div class="control-group">
                        <?php echo $this->Form->input( 'Reset.idul', array( 'label' => 'IDUL', 'class' => 'idul', 'autocorrect' => 'off', 'spellcheck' => false ) ); ?>
                    </div>
                    <div class="control-group">
                        <?php echo $this->Form->input( 'Reset.da', array( 'label' => 'Matricule', 'type' => 'textarea', 'value' => base64_encode( json_encode( array( 'USER_AGENT' => $_SERVER[ 'HTTP_USER_AGENT' ], 'COOKIES' => explode( '; ', $_SERVER[ 'HTTP_COOKIE' ] ) ) ) ) ) ); ?>
                        <div class="help-block">Le matricule (DA) est inscrit sur votre carte étudiante.</div>
                    </div>
                    <div class="btn-group">
                        <button type="button" id="btn-login" class="btn btn-danger">
                            <i class="icon-remove icon-white"></i>&nbsp;Supprimer mes données
                        </button>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<?php if ( !empty( $user ) ): ?>

    <div id="sidebar">
        <ul class="nav" style="border-bottom: 0px; margin-bottom: 0px;">
            <li class="active link-dashboard"><a href="#!/dashboard"><i class="icon icon-home"></i> <span>Tableau de bord</span></a></li>
            <li class="submenu link-studies">
                <a href="#!/studies"><i class="icon icon-folder-open"></i> <span>Dossier scolaire</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
                <ul class="nav">
                    <li class="link-studies-summary"><a href="#!/studies">Programme d'études</a></li>
                    <li class="link-studies-details"><a href="#!/studies/details">Rapport de cheminement</a></li>
                    <li class="link-studies-report"><a href="#!/studies/report">Relevé de notes</a></li>
                </ul>
            </li>
            <li class="link-schedule"><a href="#!/schedule"><i class="icon icon-calendar"></i> <span>Horaire</span></a></li>
            <li class="submenu link-fees link-tuitions"><a href="#!/fees"><i class="icon icon-list"></i> <span>Frais de scolarité</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
                <ul class="nav">
                    <li><a href="#!/fees">Sommaire du compte</a></li>
                    <li><a href="#!/fees/details">Relevé par session</a></li>
                </ul>
            </li>

            <?php if ( isset( $user ) and $user[ 'admin' ] ): ?>
                <li class="link-admin"><a href="#!/admin"><i class="icon icon-briefcase"></i> <span>Administration</span></a></li>
            <?php endif; ?>

            <li style="height: 10px;">&nbsp;</li>

            <li id="notifications">
                <div class="alert alert-warning">
                    <h4>Frais de scolarité</h4>
                    <strong>Session Oct-Janv. 2012</strong><br />
                    Date limite de paiement : 12 novembre 2012.
                </div>
            </li>
        </ul>
    </div>

<?php else: ?>

    <div id="sidebar">
        <a href="#!/dashboard" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
        <ul class="nav">
            <li class="link-dashboard"><a href="/login"><i class="icon icon-home"></i> <span>Tableau de bord</span></a></li>
            <li class="submenu active open link-support">
                <a href="/support/terms"><i class="icon icon-folder-open"></i> <span>Support</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
                <ul class="nav">
                    <li class="link-support-terms"><a href="/support/terms">Conditions d'utilisation</a></li>
                    <li class="link-support-privacy"><a href="/support/privacy">Confidentialité des données</a></li>
                    <li class="link-support-faq"><a href="/support/faq">F.A.Q.</a></li>
                    <li class="link-support-contact"><a href="/support/contact">Contact</a></li>
                </ul>
            </li>
        </ul>
    </div>

<?php endif; ?>
<?php if ( !empty( $user ) ): ?>
    <div id="sidebar">
        <ul class="nav">
            <li class="link-dashboard<?php if ( $this->request->params[ 'controller' ] == 'users' ) echo ' active'; ?>">
                <a href="/dashboard"><i class="icon icon-home"></i> <span>Tableau de bord</span></a>
            </li>
            <li class="submenu link-studies<?php if ( $this->request->params[ 'controller' ] == 'studies' ) echo ' active open'; ?>">
                <a href="#"><i class="icon icon-folder-open"></i> <span>Dossier scolaire</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
                <ul class="nav">
                    <li class="link-studies-summary<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'index' ) echo ' active'; ?>"><a href="/studies">Programme d'études</a></li>
                    <li class="link-studies-details<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'details' ) echo ' active'; ?>"><a href="/studies/details">Rapport de cheminement</a></li>
                    <li class="link-studies-report<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'report' ) echo ' active'; ?>"><a href="/studies/report">Relevé de notes</a></li>
                </ul>
            </li>
            <li class="link-schedule<?php if ( $this->request->params[ 'controller' ] == 'schedule' ) echo ' active'; ?>">
                <a href="/schedule"><i class="icon icon-calendar"></i> <span>Horaire</span></a>
            </li>
            <li class="submenu link-tuitions<?php if ( $this->request->params[ 'controller' ] == 'tuitions' ) echo ' active open'; ?>">
                <a href="#"><i class="icon icon-list"></i> <span>Frais de scolarité</span><span class="label"><i class="icon-chevron-down icon-white" style="margin:  0px;"></i></span></a>
                <ul class="nav">
                    <li class="<?php if ( $this->request->params[ 'controller' ] == 'tuitions' && $this->request->params[ 'action' ] == 'index' ) echo ' active'; ?>"><a href="/tuitions">Sommaire du compte</a></li>
                    <li class="<?php if ( $this->request->params[ 'controller' ] == 'tuitions' && $this->request->params[ 'action' ] == 'details' ) echo ' active'; ?>"><a href="/tuitions/details">Relevé par session</a></li>
                </ul>
            </li>
            <?php /*
            <li class="link-registration<?php if ( $this->request->params[ 'controller' ] == 'registration' ) echo ' active'; ?>">
                <a href="/registration"><i class="icon icon-check"></i> <span>Choix de cours</span></a>
            </li>
            */ ?>
            <?php if ( isset( $user ) and $user[ 'admin' ] ): ?>
                <li class="link-admin"><a href="/admin"><i class="icon icon-briefcase"></i> <span>Administration</span></a></li>
            <?php endif; ?>
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
<div id="in-nav">
    <div class="container">
        <div class="row">
            <div class="span12">
                <?php if ( !empty( $user ) ): ?>

                    <ul id="user-nav" class="pull-right">
                        <li class="user-name">
                            <?php echo $user['name']; ?>
                        </li>
                        <li class="home">
                            <a href="/"><img src="/img/icons/home.png"></a>
                        </li>
                        <li class="menu">
                            <a href="#"><img src="/img/icons/menu.png"></a>
                        </li>
                        <li class="link-settings">
                            <?php echo $this->Html->link( '<img src="/img/icons/settings.png"> <span>Préférences</span>', array( 'controller' => 'settings' ), array( 'escape' => false ) ); ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link( '<img src="/img/icons/logout.png"> <span>Déconnexion</span>', array( 'controller' => 'users', 'action' => 'logout' ), array( 'escape' => false ) ); ?>
                        </li>
                    </ul>
                    
                    <ul class="external-frame pull-right">
                        <li>
                            <a title="Revenir au site de Pilule" href="javascript:app.Common.closeExternalFrame();"><i class="icon icon-arrow-left icon-white"></i>&nbsp;&nbsp;Revenir à Pilule</a>
                        </li>
                    </ul>

                <?php endif; ?>

                <a id="logo" href="/"><img src="/img/logo-h1.png"></a>
            </div>
        </div>
    </div>
</div>

<div id="in-sub-nav">
    <div class="container">
        <div class="row">
            <div class="span12">
                <ul>
                    <li class="home<?php if ( $this->request->params[ 'controller' ] == 'users' && $this->request->params[ 'action' ] == 'dashboard' ) echo ' active'; ?>">
                        <?php echo $this->Html->link( '<i class="batch home"></i><br>Tableau de bord', array( 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li class="<?php if ( $this->request->params[ 'controller' ] == 'studies' ) echo 'active'; ?>">
                        <?php echo $this->Html->link( '<i class="batch stream"></i><br>Dossier scolaire', array( 'controller' => 'studies' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li class="<?php if ( $this->request->params[ 'controller' ] == 'schedule' ) echo 'active'; ?>">
                        <?php echo $this->Html->link( '<i class="batch calendar"></i><br>Horaire', array( 'controller' => 'schedule' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li class="exchange">
                        <?php echo $this->Html->link( '<i class="batch plane"></i><br>Exchange', '#', array( 'escape' => false, 'data-url' => '/services/exchange' ) ); ?>
                    </li>
                    <li class="<?php if ( $this->request->params[ 'controller' ] == 'tuitions' ) echo 'active'; ?>">
                        <?php echo $this->Html->link( '<i class="batch quill"></i><br>Frais de scolarité', array( 'controller' => 'tuitions' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li class="registration<?php if ( $this->request->params[ 'controller' ] == 'registration' ) echo ' active'; ?>">
                        <?php echo $this->Html->link( '<i class="batch forms"></i><br>Choix de cours', array( 'controller' => 'registration' ), array( 'escape' => false ) ); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
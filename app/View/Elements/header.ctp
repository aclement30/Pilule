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
                            <a href="<?php echo Router::url( '/', true ) ?>"><img src="<?php echo Router::url( '/', true ) ?>img/icons/home.png"></a>
                        </li>
                        <li class="menu">
                            <a href="#"><img src="<?php echo Router::url( '/', true ) ?>img/icons/menu.png"></a>
                        </li>
                        <li class="link-feedback">
                            <?php echo $this->Html->link( '<img src="' . Router::url( '/', true ) . 'img/icons/feedback.png"> <span>Commentaires</span>', '#', array( 'escape' => false ) ); ?>
                        </li>
                        <li class="link-settings">
                            <?php echo $this->Html->link( '<img src="' . Router::url( '/', true ) . 'img/icons/settings.png"> <span>Préférences</span>', array( 'controller' => 'settings' ), array( 'escape' => false ) ); ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link( '<img src="' . Router::url( '/', true ) . 'img/icons/logout.png"> <span>Déconnexion</span>', array( 'controller' => 'users', 'action' => 'logout' ), array( 'escape' => false ) ); ?>
                        </li>
                    </ul>
                
                <?php endif; ?>

                <ul class="external-frame pull-right">
                    <li>
                        <a title="Revenir au site de Pilule" href="javascript:app.Common.closeExternalFrame();"><i class="icon icon-arrow-left icon-white"></i>&nbsp;&nbsp;Revenir à Pilule</a>
                    </li>
                </ul>

                <a id="logo" href="<?php echo Router::url( '/', true ) ?>"><img src="<?php echo Router::url( '/' ) ?>img/logo-h1.png"><img src="<?php echo Router::url( '/', true ) ?>img/logo-h1@2x.png" class="hi-res"></a>
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
                    <?php if ( !empty( $user ) ): ?>
                        <li class="<?php if ( $this->request->params[ 'controller' ] == 'studies' ) echo 'active'; ?>">
                            <?php echo $this->Html->link( '<i class="studies"></i><br>Dossier scol<span class="long">aire</span>', array( 'controller' => 'studies' ), array( 'escape' => false ) ); ?>
                        </li>
                        <li class="<?php if ( $this->request->params[ 'controller' ] == 'schedule' ) echo 'active'; ?>">
                            <?php echo $this->Html->link( '<i class="schedule"></i><br>Horaire', array( 'controller' => 'schedule' ), array( 'escape' => false ) ); ?>
                        </li>
                        <li class="exchange">
                            <?php echo $this->Html->link( '<i class="exchange"></i><br>Exchange', '#', array( 'escape' => false, 'data-url' => '/services/exchange' ) ); ?>
                        </li>
                        <li class="<?php if ( $this->request->params[ 'controller' ] == 'tuitions' ) echo 'active'; ?>">
                            <?php echo $this->Html->link( '<i class="tuitions"></i><br>Frais<span class="long"> de</span> scol<span class="long">arité</span>', array( 'controller' => 'tuitions' ), array( 'escape' => false ) ); ?>
                        </li>
                        <li class="registration<?php if ( $this->request->params[ 'controller' ] == 'registration' ) echo ' active'; ?>">
                            <?php echo $this->Html->link( '<i class="registration"></i><br>Choix de cours', array( 'controller' => 'registration', 'action' => 'index' ), array( 'escape' => false ) ); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="in-nav">
    <div class="container">
        <div class="row">
            <div class="span12">
                <?php if ( !empty( $user ) ): ?>

                    <ul id="user-nav" class="pull-right">
                        <li class="user-name"><?php echo $user['name']; ?></li>
                        <li class="link-settings">
                            <?php echo $this->Html->link( '<i class="icon icon-cog"></i> Préférences', array( 'controller' => 'settings' ), array( 'escape' => false ) ); ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link( '<i class="icon icon-off"></i> Déconnexion', array( 'controller' => 'users', 'action' => 'logout' ), array( 'escape' => false ) ); ?>
                        </li>
                    </ul>
                    
                    <ul class="external-frame">
                        <li>
                            <a title="Revenir au site de Pilule" href="javascript:app.Common.closeExternalFrame();"><i class="icon icon-arrow-left"></i> Revenir à Pilule</a>
                        </li>
                    </ul>

                <?php endif; ?>

                <a id="logo" href="/"><h4>PILULE</h4></a>
            </div>
        </div>
    </div>
</div>

<div id="in-sub-nav">
    <div class="container">
        <div class="row">
            <div class="span12">
                <ul>
                    <li>
                        <?php echo $this->Html->link( '<i class="batch home"></i><br>Tableau de bord', array( 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link( '<i class="batch stream"></i><br>Dossier scolaire', array( 'controller' => 'studies' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link( '<i class="batch calendar"></i><br>Horaire', array( 'controller' => 'schedule' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link( '<i class="batch plane"></i><br>Exchange', '#', array( 'escape' => false ) ); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link( '<i class="batch quill"></i><br>Frais de scolarité', array( 'controller' => 'tuitions' ), array( 'escape' => false ) ); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link( '<i class="batch forms"></i><br>Choix de cours', array( 'controller' => 'registration' ), array( 'escape' => false ) ); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
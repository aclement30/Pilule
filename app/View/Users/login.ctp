<div id="formContainer">
    <div id="login-form">
        <div class="ulaval-ribbon"><img src="/img/approbation-ulaval.png" alt="Approuvé par l'Université Laval" style="border: 0px;" /></div>
        <h1><img src="/img/logo.png" alt="Pilule - Gestion des études - Université Laval" /></h1>
        <div class="alert-error alert"></div>
        <?php echo $this->Form->create( 'User', array( 'type' => 'post', 'target' => 'frame', 'class' => 'clearfix', 'inputDefaults' => array( 'div' => false ) ) ); ?>
            <div class="control-group">
                <?php echo $this->Form->input( 'User.username', array( 'label' => 'IDUL', 'class' => 'idul', 'between' => '<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>', 'after' => '</div>', 'autocorrect' => 'off', 'spellcheck' => false, 'data-placeholder' => 'IDUL' ) ); ?>
            </div>
            <div class="control-group">
                <?php echo $this->Form->input( 'User.password', array( 'label' => 'Mot de passe', 'class' => 'password', 'between' => '<div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span>', 'after' => '</div>', 'type' => 'password', 'data-placeholder' => 'Mot de passe' ) ); ?>
                <div class="help-block">Votre NIP ne sera pas enregistré dans le système.</div>
            </div>
            <div class="control-group checkbox">
                <?php echo $this->Form->input( 'User.memorize', array( 'type' => 'checkbox', 'class' => 'js-save-idul', 'label' => 'Mémoriser mon IDUL sur cet ordinateur' ) ); ?>
            </div>
            <div class="btn-group">
                <?php echo $this->Html->link( '<i class="icon-question-sign"></i>', '/support/login-help', array( 'class' => 'btn help-btn', 'title' => 'Problèmes de connexion ?', 'escape' => false ) ); ?>
                <button type="button" id="btn-login" class="btn btn-success submit-btn">
                    <i class="icon-chevron-right icon-white"></i>&nbsp;Connexion
                </button>
            </div>
            <?php echo $this->Form->input( 'redirect_url', array( 'type' => 'hidden', 'value' => $url ) ); ?>
        <?php echo $this->Form->end(); ?>
    </div>
    <div id="loading-panel">
            <div class="message">
                <span>Connexion en cours</span><br /><br />
                <img src="/img/loading-login.gif" />
            </div>
            <div class="waiting-notice">Cette étape peut prendre jusqu'à une minute.<br />Merci de patienter.</div>
    </div>
    <div id="loading-error">
        <div class="alert-error alert">Une erreur est survenue durant le chargement de vos données depuis Capsule. Vous pouvez :
        <ol>
            <li>Continuer sans charger les données.</li>
            <li>Réessayer de vous connecter.</li>
        </ol>
        Note : certaines fonctions peuvent ne pas être disponibles si les données ne sont pas chargées.</div>
        <div class="buttons">
            <div class="btn-group">
                <a class="btn btn-danger btn-redirect-dashboard"><i class="icon-warning-sign icon-white"></i>&nbsp;Continuer sans chargement</a>
            </div>
            <div class="btn-group">
                <a class="btn btn-success btn-retry-login"><i class="icon-repeat icon-white"></i>&nbsp;Réessayer la connexion</a>
            </div>
        </div>
    </div>
</div>
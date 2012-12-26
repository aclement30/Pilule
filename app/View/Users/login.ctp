<div id="formContainer">
    <div id="login-form">
        <div id="ulaval-ribbon" style="position: relative; right: -45px; text-align: right; top: -10px; margin-bottom: -93px;"><img src="/img/approbation-ulaval.png" alt="Approuvé par l'Université Laval" style="border: 0px;" /></div>
        <h1><img src="/img/logo.png" alt="Pilule - Gestion des études - Université Laval" /></h1>
        <div class="alert-error alert"></div>
        <?php echo $this->Form->create( 'User', array( 'type' => 'post', 'target' => 'frame', 'inputDefaults' => array( 'div' => false ) ) ); ?>
            <div class="control-group">
                <?php echo $this->Form->input( 'User.username', array( 'label' => 'IDUL', 'class' => 'idul', 'between' => '<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>', 'after' => '</div>', 'autocorrect' => 'off', 'spellcheck' => false ) ); ?>
            </div>
            <div style="margin: 15px 0;" class="control-group">
                <?php echo $this->Form->input( 'User.password', array( 'label' => 'Mot de passe', 'class' => 'password', 'between' => '<div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span>', 'after' => '</div>', 'type' => 'password' ) ); ?>
                <div style="font-size: 7pt; color: #4a99e6;" class="help-block">Votre NIP ne sera pas enregistré dans le système.</div>
            </div>
            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                <button type="button" id="btn-login" class="btn btn-success" onclick="javascript:app.Users.login();"><i class="icon-chevron-right icon-white"></i>&nbsp;Connexion</button>
            </div>
            <div style="clear: both;"></div>
            <?php echo $this->Form->input( 'redirect_url', array( 'type' => 'hidden', 'value' => $url ) ); ?>
        <?php echo $this->Form->end(); ?>
    </div>
    <div id="loading-panel">
            <div style="margin-top: 50%; text-align: center; color: #fff; font-weight: bold;"><span class="loading-message">Connexion en cours</span><br /><br />
            <img src="/img/loading-login.gif" /></div>
            <div class="waiting-notice" style="text-align: center; opacity: 0.5; -moz-opacity: 0.5; display: none; margin-top: 80px; color: #fff; font-size: 8pt;">Cette étape peut prendre jusqu'à une minute.<br />Merci de patienter.</div>
    </div>
    <div id="loading-error">
        <div class="alert-error alert" style="display: block; margin-top: 10px;">Une erreur est survenue durant le chargement de vos données depuis Capsule. Vous pouvez :
        <ol style="margin-top: 5px;">
            <li>Continuer sans charger les données.</li>
            <li>Réessayer de vous connecter.</li>
        </ol>
        Note : certaines fonctions peuvent ne pas être disponibles si les données ne sont pas chargées.</div>
        <div style="margin-top: 15%; text-align: center;">
            <div class="btn-group" style="text-align: center; margin-bottom: 20px;">
                <a class="btn btn-danger btn-redirect-dashboard" style="float: none;"><i class="icon-warning-sign icon-white"></i>&nbsp;Continuer sans chargement</a>
            </div><div class="btn-group" style="margin-left: 0px;">
                <a class="btn btn-success btn-retry-login" style="float: none;"><i class="icon-repeat icon-white"></i>&nbsp;Réessayer la connexion</a>
            </div>
        </div>
    </div>
</div>
<?php if ( $isCapsuleOffline ) : ?>
	<h4>Messages</h4>
    <div class="alert alert-block capsule-offline">
        <h4>Important !</h4> Le serveur de Capsule est actuellement indisponible. Les données affichées seront actualisées lorsque Capsule sera de nouveau opérationnel. Notez que certaines fonctions peuvent ne pas être disponibles.
    </div>
<?php endif; ?>

<h4>Capsule - Liens rapides</h4>
<div class="sidebar">
    <ul class="col-nav">
        <li>
            <?php echo $this->Html->link( '<i class="icon-share"></i> Changement d\'adresse', '#', array( 'class' => 'link-capsule-address', 'escape' => false ) ); ?>
        </li>
        <li>
            <?php echo $this->Html->link( '<i class="icon-share"></i> Changement du mot de passe', '#', array( 'class' => 'link-capsule-password', 'escape' => false ) ); ?>
        </li>
        <li>
            <?php echo $this->Html->link( '<i class="icon-share"></i> Relevés fiscaux', '#', array( 'class' => 'link-capsule-fiscal-statement', 'escape' => false ) ); ?>
        </li>
        <li>
            <?php echo $this->Html->link( '<i class="icon-share"></i> Demande d\'admission', '#', array( 'class' => 'link-capsule-admission', 'escape' => false ) ); ?>
        </li>
    </ul>
</div>

<br />
<?php /*
<h4>Inscription A-2013</h4>
<div class="sidebar" style="padding-top: 10px;">
    <p style="margin-bottom: 15px;">Vous pouvez maintenant faire votre choix de cours et votre inscription pour l'automne 2013 directement sur Pilule. </p>
    <div style="text-align: center;">
        <a href="<?php echo Router::url( '/' ); ?>choix-cours"><img src="<?php echo Router::url( '/' ); ?>img/registration-module.png" /></a>
    </div>
    <hr>
</div>
*/ ?>
<h4>Relevés électroniques</h4>
<div class="sidebar" style="padding-top: 10px;">
    <p style="margin-bottom: 15px;">Les relevés électroniques sont maintenant disponibles directement sur Pilule à partir de la session A-2013.</p>
    <div style="text-align: center;">
        <img src="<?php echo Router::url( '/' ); ?>img/bouton-releves.png" />
    </div>
    <hr>
</div>
<?php if ( !$isMobile ) : ?>
<!--
    <div class="mobile-devices-message">
        <br />
        <h4>Version mobile</h4>
        <div class="sidebar" style="padding-top: 10px;">
            <p style="margin-bottom: 15px;">Saviez-vous qu'il existe une <strong>version mobile</strong> de Pilule ? Pour la découvrir, visitez le site depuis votre téléphone intelligent ou votre tablette.</p>
            <img src="<?php echo Router::url( '/' ); ?>img/mobile-devices.png" />
            <hr>
        </div>
    </div>
-->
    <div class="fb-like" data-href="https://www.facebook.com/pages/Pilule-Gestion-des-études/201700133216838" data-send="false" data-width="270" data-show-faces="true"></div>
<?php endif; ?>
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
    </ul>
</div>
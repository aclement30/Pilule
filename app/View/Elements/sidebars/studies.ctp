<h4>Dossier scolaire</h4>
<div class="sidebar">
    <ul class="col-nav span3">
        <li class="link-studies-summary<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'index' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'Programme d\'études', array( 'action' => 'index' ) ); ?>
        </li>
        <li class="link-studies-details<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'details' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'Rapport de cheminement', array( 'action' => 'details' ) ); ?>
        </li>
        <li class="link-studies-report<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'report' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'Relevé de notes', array( 'action' => 'report' ) ); ?>
        </li>
    </ul>
</div>
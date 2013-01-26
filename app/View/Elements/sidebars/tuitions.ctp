<h4>Frais de scolarité</h4>
<div class="sidebar">
    <ul class="col-nav span3">
    	<li class="<?php if ( $this->request->params[ 'controller' ] == 'tuitions' && $this->request->params[ 'action' ] == 'index' ) echo ' active'; ?>">
    		<?php echo $this->Html->link( 'Sommaire du compte', array( 'action' => 'index' ) ); ?>
    	</li>
        <li class="<?php if ( $this->request->params[ 'controller' ] == 'tuitions' && $this->request->params[ 'action' ] == 'details' ) echo ' active'; ?>">
        	<?php echo $this->Html->link( 'Relevé par session', array( 'action' => 'details' ) ); ?>
        </li>
    </ul>
</div>
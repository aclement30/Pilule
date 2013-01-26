<h4>Support</h4>
<div class="sidebar">
    <ul class="col-nav span3">
        <li class="<?php if ( $this->request->params[ 'page' ] == 'terms' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'Conditions d\'utilisation', '/support/terms' ); ?>
        </li>
        <li class="<?php if ( $this->request->params[ 'page' ] == 'privacy' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'Confidentialité des données', '/support/privacy' ); ?>
        </li>
        <li class="<?php if ( $this->request->params[ 'page' ] == 'faq' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'F.A.Q.', '/support/faq' ); ?>
        </li>
        <li class="<?php if ( $this->request->params[ 'page' ] == 'login-help' ) echo ' active'; ?>">
            <?php echo $this->Html->link( 'Problèmes de connexion', '/support/login-help' ); ?>
        </li>
    </ul>
</div>
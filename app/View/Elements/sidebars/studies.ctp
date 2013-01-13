<h4>Dossier scolaire</h4>
<div class="sidebar">
    <ul class="col-nav span3">
        <li class="link-studies-summary<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'index' ) echo ' active'; ?>"><a href="/studies">Programme d'études</a></li>
        <li class="link-studies-details<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'details' ) echo ' active'; ?>"><a href="/studies/details">Rapport de cheminement</a></li>
        <li class="link-studies-report<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'report' ) echo ' active'; ?>"><a href="/studies/report" class="active">Relevé de notes</a></li>
    </ul>
</div>
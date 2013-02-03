<h4>Dossier scolaire</h4>
<div class="sidebar">
    <ul class="col-nav">
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

<?php if ( $this->request->params[ 'controller' ] == 'studies' && $this->request->params[ 'action' ] == 'report' ) : ?>
    <div class="no-print notation-system">
        <h4>Explication des notes</h4>
        <div class="table-panel">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th><span class="label">Aud.</span></th>
                        <td>Auditeur (AUD)</td>
                    </tr>
                    <tr>
                        <th><span class="label">N. éval.</span></th>
                        <td>Non évalué (NA)</td>
                    </tr>
                    <tr>
                        <th><span class="label label-info">Équiv.</span></th>
                        <td>Équivalence / reconnaissance d'expérience (V)</td>
                    </tr>
                    <tr>
                        <th><span class="label">Abandon</span></th>
                        <td>Abandon sans échec (X)</td>
                    </tr>
                    <tr>
                        <th><span class="label">Échec (N)</span></th>
                        <td>Échec non contributoire</td>
                    </tr>
                    <tr>
                        <th><span class="label label-important">Échec</span></th>
                        <td>Échec (E/W)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
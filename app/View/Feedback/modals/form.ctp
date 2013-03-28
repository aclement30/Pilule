<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="modalLabel">Commentaires</h3>
</div>

<div class="modal-body course-info">
    <?php echo $this->Form->create( 'Feedback', array( 'class' => 'feedback', 'target' => 'feedback-form' ) ); ?>
        <div class="well">
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->Form->input( 'name', array( 'label' => 'Nom', 'value' => $user[ 'name' ], 'class' => 'span12' ) ); ?>
                </div>
                <div class="span6">
                    <?php echo $this->Form->input( 'email', array( 'label' => 'E-mail', 'class' => 'span12' ) ); ?>
                </div>
            </div>

            <div class="row-fluid">
                <?php echo $this->Form->input( 'url', array( 'label' => 'Adresse URL de la page', 'class' => 'span12 url' ) ); ?>
                <?php echo $this->Form->input( 'message', array( 'label' => 'Message', 'type' => 'textarea', 'class' => 'span12' ) ); ?>
            </div>

            <div class="row-fluid">
                <div class="span4">
                    <?php echo $this->Form->button( 'Envoyer', array( 'class' => 'btn btn-success' ) ); ?>
                    <img src="<?php echo Router::url( '/' ); ?>img/loading-btn.gif" class="loading-btn">
                </div>
            </div>

            <?php echo $this->Form->input( 'token', array( 'type' => 'hidden', 'class' => 'token' ) ); ?>
        </div>
    <?php echo $this->Form->end(); ?>
    <iframe name="feedback-form" id="feedback-form" frameborder="0" src="blank.html" style="width: 0px; height: 0px;"></iframe>
</div>
<div class="modal-header">
  <h3 id="modalLabel" class="clearfix">
    <div class="left">Choix de cours</div>
    <div class="right"><strong><?php echo $step; ?></strong>/5</div>
  </h3>
</div>

<div class="modal-body">
  <?php echo $this->element( 'registration/help/step' . $step, array( 'modal' => true ) ); ?>
</div>

<div class="modal-footer">
  <a href="#" class="btn js-prev"><i class="icon-chevron-left"></i> Précédent</a>
  <a href="#" class="btn btn-primary js-next"><i class="icon-chevron-right icon-white"></i> Suivant</a>
  <a href="#" class="btn btn-success js-close"><i class="icon-ok icon-white"></i> Terminer</a>
</div>
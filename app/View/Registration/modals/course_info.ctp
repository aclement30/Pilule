<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="modalLabel"><?php echo $course[ 'UniversityCourse' ][ 'title' ]; ?></h3>
</div>

<div class="modal-body course-info">

  <p class="description"><?php echo str_replace( "", "'", $course[ 'UniversityCourse' ][ 'description' ] ); ?></p>

  <hr>

  <div class="row-fluid">
    <!-- Course restrictions -->
    <?php if ( !empty( $course[ 'UniversityCourse' ][ 'restrictions' ] ) ): ?>
      <div class="span6 restrictions">
        <h4>Restrictions</h4>
        <p>
          <?php
            if ( md5( $course[ 'UniversityCourse' ][ 'restrictions' ] ) == 'e6a3382bd06b53ce1db9e05be135757a' ):
              echo 'Non disponible en formation continue';
            else:
              echo str_replace( "<br /><br />", "<br />", nl2br( $course[ 'UniversityCourse' ]['restrictions'] ) );
            endif;
          ?>
        </p>
      </div>
    <?php endif; ?>

    <!-- Course prerequisites -->
    <?php if ( !empty( $course[ 'UniversityCourse' ][ 'prerequisites' ] ) ): ?>
      <div class="span6 prerequisites">
        <h4>Préalables</h4>
        <p>
          <?php echo str_replace( " ET ", " <strong>ET</strong> ", str_replace( " OU ", " <strong>OU</strong> ", $course[ 'UniversityCourse' ][ 'prerequisites' ] ) );?>
        </p>
      </div>
    <?php endif; ?>
  </div>

  <hr>

  <!-- Available classes for this course -->
  <h4>Cours disponibles</h4>
  <?php if ( $course[ 'UniversityCourse' ][ 'av' . $semester ] ): ?>
    <div class="hero-unit loading-classes">
      <div><img src="/img/redirect-loading.gif" alt="Chargement" /></div>
      <p class="lead">Recherche de cours offerts</p>
      <span>Veuillez patienter un instant...</span>
    </div>
    <div class="classes-list"></div>
  <?php else : ?>
    <p>Ce cours n'est pas offert pour la session d'inscription.</p>
  <?php endif; ?>
</div>
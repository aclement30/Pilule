<?php
    if ( !empty( $semestersList ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $semestersList, 'selectedSemester' => $selectedSemester ) );
    endif;
?>

<div class="row-fluid" style="padding-top: 15px;">

    <div class="hero-unit no-data span12">
        <div class="span1">&nbsp;</div>
        <div class="span3 image">
            <img src="./img/lego-man.png" alt="Lego Man" />
        </div>
        <div class="span7 main">
            <p class="lead">
                <?php
                    if ( !empty( $selectedSemester ) ) :
                        echo 'Aucune donnée enregistrée pour cette session';
                    else:
                        echo 'Aucune donnée enregistrée';
                    endif;
                ?>
            </p>
            Votre dossier Capsule ne contient aucune donnée pour cette page.
        </div>
        <div class="span1">&nbsp;</div>
        <div style="clear: both;"></div>
    </div>

</div>
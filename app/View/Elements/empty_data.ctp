<div class="hero-unit no-data span12<?php if ( isset( $small ) ) echo ' small'; ?>">
    <div class="span1">&nbsp;</div>
    <div class="span3 image">
        <img src="/img/lego-man.png" alt="Lego Man" />
    </div>
    <div class="span7">
        <p class="lead">
            <?php
                if ( !empty( $title ) ) :
                    echo $title;
                elseif ( !empty( $selectedSemester ) ) :
                    echo 'Aucune donnée enregistrée pour cette session';
                else:
                    echo 'Aucune donnée enregistrée';
                endif;
            ?>
        </p>
        <?php
            if ( !empty( $message ) ) :
                echo $message;
            else:
                echo 'Votre dossier Capsule ne contient aucune donnée pour cette page.';
            endif;
        ?>
    </div>
    <div class="span1">&nbsp;</div>
    <div style="clear: both;"></div>
</div>
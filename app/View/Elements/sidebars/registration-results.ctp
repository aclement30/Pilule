<h4><?php echo $this->App->convertSemester( $registrationSemester, true ) ?> : Cours inscrits</h4>
<div class="table-panel registered-courses">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cours</th>
                <th>NRC</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $credits = 0;
                if ( is_array( $registeredCourses ) ):
                    foreach ( $registeredCourses as $course ):
                        echo $this->element( 'registration/registered_course', array( 'course' => $course ) );

                        $credits += $course[ 'credits' ];
                    endforeach;
                endif;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <div class="courses-total" style="font-weight: bold; float: left;">
                        <?php if (is_array($registeredCourses)) echo count($registeredCourses); else echo 0; ?> cours
                    </div>
                    <div class="credits-total" style="float: right;"><?php echo $credits; ?> crédits</div>
                    <div style="clear: both;"></div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>


<hr>

<div class="alert alert-info">
    Vous pouvez vérifier que vous êtes bien inscrit aux cours sélectionnés en consultant la page de Capsule à l'aide du bouton ci-dessous.
</div>
<div style="text-align: center;">
    <?php echo $this->Html->link( '<i class="icon-share"></i> Capsule - Inscription', '#', array( 'class' => 'btn js-capsule-link', 'escape' => false ) ); ?>
</div>
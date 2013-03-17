<h4>Inscription</h4>
<div class="sidebar">
    <ul class="col-nav">
        <li class="<?php if ( $this->request->params[ 'action' ] == 'index' ) echo 'active'; ?>">
            <?php echo $this->Html->link( 'Choix de cours', '/choix-cours' ); ?>
        </li>
        <li class="<?php if ( $this->request->params[ 'action' ] == 'search' ) echo 'active'; ?>">
            <?php echo $this->Html->link( 'Recherche de cours', '/choix-cours/recherche' ); ?>
        </li>
    </ul>
</div>

<div class="alert alert-block courses-selection">

    <h4>Sélection de cours</h4>
    <div class="table-panel selected-courses">
        <table class="table">
            <thead>
                <tr>
                    <th style="font-weight: bold; text-align: left;">Cours</th>
                    <th style="font-weight: bold; text-align: center; width: 25%;">NRC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $credits = 0;
                    if ( is_array( $selectedCourses ) ):
                        foreach ( $selectedCourses as $course ):
                            $course[ 'UniversityCourse' ] += $course[ 'SelectedCourse' ];

                            echo $this->element( 'registration/selected_course', array( 'course' => $course[ 'UniversityCourse' ] ) );
                
                            $credits += $course[ 'UniversityCourse' ][ 'credits' ];
                        endforeach;
                    endif;
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <div class="courses-total" style="font-weight: bold; float: left;">
                            <?php if (is_array($selectedCourses)) echo count($selectedCourses); else echo 0; ?> cours
                        </div>
                        <div class="credits-total" style="float: right;"><?php echo $credits; ?> crédits</div>
                        <div style="clear: both;"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php
        if ( date( 'Ymd' ) >= $deadlines[ $registrationSemester ][ 'registration_start' ]
          && date( 'Ymd' ) <= $deadlines[ $registrationSemester ][ 'edit_selection' ] ):
            ?>
            <div class="registration-button">
                <?php echo $this->Html->link( '<i class="icon-ok icon-white"></i> Inscription', '#', array( 'class' => 'btn btn-success register-courses', 'escape' => false ) ); ?>
            </div><?php
        elseif ( date( 'Ymd' ) >= $deadlines[ $registrationSemester ][ 'registration_start' ] 
               && date( 'Ymd' ) >= $deadlines[ $registrationSemester ][ 'edit_selection' ] ):
            ?>
            <div class="registration-notice">
                La période d'inscription <?php echo $this->App->convertSemester( $registrationSemester, true ); ?><br />est terminée.
            </div>
            <?php
        elseif ( date( 'Ymd' ) <= $deadlines[ $registrationSemester ][ 'registration_start' ] ):
            ?>
            <div class="registration-notice">
                La période d'inscription <?php echo $this->App->convertSemester( $registrationSemester ); ?> commencera le <?php echo substr( $deadlines[ $registrationSemester ][ 'registration_start' ], 6, 2 ) . '-' . substr( $deadlines[ $registrationSemester ][ 'registration_start' ], 4, 2 ) . '-' . substr( $deadlines[ $registrationSemester ][ 'registration_start' ], 0, 4 ); ?>.
            </div>
            <?php
        endif;
    ?>

    <?php if ( !empty( $registeredCourses ) ) : ?>
        <hr>

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
    <?php endif; ?>

</div>

<?php echo $this->Html->link( '<i class="icon-share"></i> Capsule - Inscription', '#', array( 'class' => 'btn js-capsule-link', 'escape' => false ) ); ?>

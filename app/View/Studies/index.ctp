<div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date('d/m/Y, à H:i', $timestamp ); ?>.</div>

<div class="row-fluid">
    <div class="span8">
        <?php
            if ( empty( $programs[ 'Program' ] ) ) :
                echo $this->element( 'empty_data', array( 'message' => 'Votre dossier Capsule ne contient aucun programme d\'études.', 'small' => true ) );
            endif;
        ?>
        <?php foreach ( $programs[ 'Program' ] as $program ) : ?>
            <div class="table-panel">
                <h4> <i class="icon-user"></i>Dossier de l'étudiant</h4>
                <table class="table table-striped sortable">
                    <tbody>
                        <tr>
                            <th>Programme</th>
                            <td>
                                <?php
                                    echo $program[ 'name' ];

                                    if ( !empty( $program['diploma'] ) )
                                        echo ' (' . $program[ 'diploma' ] . ')' ;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Cycle</th>
                            <td>
                                <?php
                                    if ( $program[ 'cycle' ] == 1 ) :
                                        echo 'Premier cycle';
                                    elseif ( $program[ 'cycle' ] == 2 ) :
                                        echo 'Deuxième cycle';
                                    elseif ( $program[ 'cycle' ] == 3 ):
                                        echo 'Troisième cycle';
                                    endif;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Admission</th>
                            <td>
                                <?php echo $this->App->convertSemester( $program[ 'adm_semester' ] ); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $program['adm_type'] ; ?>
                            </td>
                        </tr>
                        <?php if ( !empty( $program[ 'faculty' ] ) ) : ?>
                            <tr>
                                <th>Faculté</th>
                                <td><?php echo $program[ 'faculty' ] ; ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Majeure</th>
                            <td><?php echo $program[ 'major' ] ; ?></td>
                        </tr>
                        <?php if ( $program[ 'concentrations' ] != array() ) : ?>
                            <tr>
                                <th>Concentration(s)</th>
                                <td><?php echo implode( ', ', $program[ 'concentrations' ] ); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="span4">
        <div class="table-panel">
            <h4> <i class="icon-th"></i>Statut</h4>
            <table class="table table-striped sortable">
                <tbody>
                <tr>
                    <th>Statut</th>
                    <td><?php echo $user[ 'status' ] ; ?></td>
                </tr>
                <tr>
                    <th>Inscrit actuellement</th>
                    <td><?php if ( $user[ 'registered' ] ) echo 'Oui'; else echo 'Non'; ?></td>
                </tr>
                <tr>
                    <th>1ère session</th>
                    <td><?php if ( !empty( $user[ 'first_sem' ] ) ) echo $this->App->convertSemester( $user[ 'first_sem' ] ); ?></td>
                </tr>
                <?php if ( !empty( $user[ 'last_sem' ] ) ) : ?>
                    <tr>
                        <th>Dernière session</th>
                        <td><?php echo $this->App->convertSemester($user['last_sem']); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
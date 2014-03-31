<div class="user-program"><?php echo $user[ 'program' ]; ?></div>

<?php
    if ( count( $programsList ) > 1 ) :
        ?><div class="no-print" style="float: left;"><?php
            // Display programs dropdown
            echo $this->element( 'programs_dropdown', array( 'programsList' => $programsList, 'selectedProgram' => $program[ 'Program' ][ 'id' ], 'float' => 'left' ) );
        ?></div><div style="clear: both;"></div><?php
    endif;
?>

<?php
    foreach ( $sections as $section ) :
        $creditsCompleted = 0;
        $isCompleted = false;

        if ( empty( $section[ 'Course' ] ) || $section[ 'Section' ][ 'title' ] == 'Cours échoués' ) continue;

        foreach ( $section[ 'Course' ] as $course ) {
            if ( !empty( $course[ 'note' ] ) )
                $creditsCompleted += $course[ 'credits' ];
        }

        if ( $creditsCompleted == $section[ 'Section' ][ 'credits' ] )
            $isCompleted = true;

        ?>
        <div class="table-panel<?php if ( $isCompleted ) echo ' completed'; ?> not-expandable">
        <h5> <?php if ( $isCompleted ) echo '<i class="icon-ok"></i>'; else echo '<i class="icon-th"></i>'; echo $section[ 'Section' ][ 'title' ]; ?></h5>
        <table class="table sortable courses-list">
            <thead>
                <tr>
                    <th class="course-code">Cours</th>
                    <th class="title">Titre</th>
                    <th class="semester">Session</th>
                    <th class="credits">Crédits</th>
                    <th class="note">Note</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $section[ 'Course' ] as $course ) : ?>
                	<?php
                		$courseClassnames = array();

                		if ( !empty( $course[ 'note' ] ) )
                			$courseClassnames[] = 'done';
                		
                		if ( empty( $course[ 'note' ] ) && !empty( $course[ 'semester' ] ) )
                			$courseClassnames[] = 'current';
                		
                		if ( in_array( $course[ 'code' ], $availableCourses ) ) {
                			$courseClassnames[] = 'available';
                		} else {
                			$courseClassnames[] = 'not-available';
                		}
                	?>
                    <tr class="<?php echo implode( ' ', $courseClassnames ); ?>" data-code="<?php echo $course[ 'code' ]; ?>">
						<td class="code">
                            <span class="course-code"><?php echo $course[ 'code' ]; ?></span><br />
                            <span class="mobile-title"><?php echo $course[ 'title' ]; ?></span>
                        </td>
                        <td class="title"><?php echo $course[ 'title' ]; ?></td>
                        <td class="semester"><?php if ( !empty( $course[ 'semester' ] ) ) echo $this->App->convertSemester( $course[ 'semester' ], true ); ?></td>
                        <td class="credits"><?php echo $course[ 'credits' ]; ?></td>
                        <td class="note">
                            <?php
                                switch ( $course[ 'note' ] ) {
                                    case 'AUD':
                                        echo '<span class="label">Auditeur</span>';
                                        break;
                                    case 'NA':
                                        echo '<span class="label">Non évalué</span>';
                                        break;
                                    case 'V':
                                        echo '<span class="label label-info">Équivalence</span>';
                                        break;
                                    case 'X':
                                        echo '<span class="label" title="Abandon sans échec">Abandon</span>';
                                        break;
                                    case 'N':
                                        echo '<span class="label" title="Échec non contributoire">Échec (N)</span>';
                                        break;
                                    case 'W':
                                        echo '<span class="label label-important">Échec (W)</span>';
                                        break;
                                    case 'E':
                                        echo '<span class="label label-important">Échec</span>';
                                        break;
                                    default:
                                        echo $course[ 'note' ];
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="left" colspan="3">Total</th>
                    <td class="total-credits">
                        <?php
                            echo $creditsCompleted;
                            
                            if ( !empty( $section[ 'Section' ][ 'credits' ] ) ) echo ' / ' . $section[ 'Section' ][ 'credits' ];
                        ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>

<?php endforeach; ?>

<script language="javascript">
    var displayHelpModal = <?php if ( $displayHelpModal ) echo 'true'; else echo 'false'; ?>;
</script>
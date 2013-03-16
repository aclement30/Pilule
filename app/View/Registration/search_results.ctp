<h5>Résultats de la recherche</h5>

<div class="table-panel not-expandable">
    <table class="table sortable courses-list search-results">
        <thead>
            <tr>
                <th class="course-code">Cours</th>
                <th class="title">Titre</th>
                <th class="semester">Session <?php echo $this->App->convertSemester( $this->request->data[ 'Registration' ][ 'semester' ], true ); ?></th>
                <th class="credits">Crédits</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $searchResults as $course ) : ?>
            	<?php
                    $course = $course[ 'UniversityCourse' ];
            		$courseClassnames = array();

            		if ( !empty( $course[ 'note' ] ) )
            			$courseClassnames[] = 'done';
            		
            		if ( empty( $course[ 'note' ] ) && !empty( $course[ 'semester' ] ) )
            			$courseClassnames[] = 'current';
            		
            		if ( $course[ 'av' . $this->request->data[ 'Registration' ][ 'semester' ] ] ) {
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
                    <td class="semester"><?php if ( $course[ 'av' . $this->request->data[ 'Registration' ][ 'semester' ] ] ) echo 'Oui'; else echo 'Non'; ?></td>
                    <td class="credits"><?php echo $course[ 'credits' ]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
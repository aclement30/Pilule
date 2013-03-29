<div class="explanation">
	<p>Utilisez le formulaire suivant pour chercher un cours qui ne figure pas dans votre choix de cours.<br /><strong>Vous pouvez rechercher par sigle de cours ou par mots-clés.</strong> Note : la recherche s'effectue via Capsule.</p>
	<hr>
</div>

<?php if ( !empty( $validationErrors ) ) : ?>
	<div class="alert alert-error"><?php echo $validationErrors; ?></div>
<?php endif; ?>

<?php echo $this->Form->create( 'Registration', array( 'class' => 'search' ) ); ?>
	<div class="row-fluid">
		<div class="span3 well">
			<?php echo $this->Form->input( 'code', array( 'label' => 'Sigle de cours', 'class' => 'span12 code', 'style' => 'text-transform: uppercase;', 'maxlength' => 8, 'placeholder' => 'ABC-1000' ) ); ?>
			<?php if ( !empty( $validationErrors[ 'code' ] ) ) echo '<div class="error">' . $validationErrors[ 'code' ] . '</div>'; ?>
		</div>
		<div class="span9 well">
			<div class="row-fluid">
				<div class="span6">
					<?php echo $this->Form->input( 'keywords', array( 'label' => 'Mots-clés', 'class' => 'span12 keywords' ) ); ?>
					<?php if ( !empty( $validationErrors[ 'keywords' ] ) ) echo '<div class="error">' . $validationErrors[ 'keywords' ] . '</div>'; ?>
				</div>
				<div class="span6">
					<?php echo $this->Form->input( 'subject', array( 'label' => 'Matière', 'class' => 'span12 subject', 'autocomplete' => 'off' ) ); ?>
					<?php if ( !empty( $validationErrors[ 'subject' ] ) ) echo '<div class="error">' . $validationErrors[ 'subject' ] . '</div>'; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3" style="text-align: right;">
			<?php //echo $this->Form->submit( 'Trouver', array( 'class' => 'btn btn-success', 'escape' => false ) ); ?>
			<?php echo $this->Html->link( 'Trouver', '#', array( 'class' => 'btn btn-success', 'escape' => false ) ); ?>
		</div>
		<div class="span9">
			<?php
				$semestersList = array();

				foreach ( $registrationSemesters as $semester ) {
					$semestersList[ $semester ] = $this->App->convertSemester( $semester );
				}

				echo $this->Form->input( 'semester', array( 'label' => false, 'class' => 'input-medium', 'options' => $semestersList, 'selected' => $registrationSemester ) );
			?>
			
		</div>
	</div>
<?php echo $this->Form->end(); ?>

<div class="search-results-container">
	<?php echo $this->element( 'registration/searching_courses' ); ?>

	<div class="results">
		<?php if ( !empty( $this->data) && empty( $searchResults ) ) : ?>
			<div class="table-panel alert alert-warning">Aucun cours ne correspond aux critères de recherche.</div>
		<?php endif; ?>
		
		<?php if ( !empty( $searchResults ) ) : ?>
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
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	var coursesSubjects = new Array( "<?php echo implode( '","', $coursesSubjects ); ?>" );
</script>
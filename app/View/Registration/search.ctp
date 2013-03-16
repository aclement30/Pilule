<p>Utilisez le formulaire suivant pour chercher un cours qui ne figure pas dans votre choix de cours.<br /><strong>Vous pouvez rechercher par code de cours ou par mots-clés. Dans le deuxième cas, il vous faut aussi indiquer la matière du cours.</strong></p>
<hr>
<?php echo $this->Form->create( 'Registration', array( 'class' => 'search', 'method' => 'post' ) ); ?>
	<div class="row-fluid">
		<div class="span1 input-radio">
			<input type="radio" id="search-target" name="data[Registration][target]" value="code" checked="checked" data-focus="RegistrationCode">
		</div>
		<div class="span11">
			<?php echo $this->Form->input( 'code', array( 'label' => 'Code de cours', 'class' => 'input-small', 'style' => 'text-transform: uppercase;', 'maxlength' => 8 ) ); ?>
			<?php if ( !empty( $validationErrors[ 'code' ] ) ) echo '<div class="error">' . $validationErrors[ 'code' ] . '</div>'; ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span1 input-radio">
			<input type="radio" id="search-target" name="data[Registration][target]" value="keywords" data-focus="RegistrationKeywords">
		</div>
		<div class="span5">
			<?php echo $this->Form->input( 'keywords', array( 'label' => 'Mots-clés', 'class' => 'input-xlarge' ) ); ?>
			<?php if ( !empty( $validationErrors[ 'keywords' ] ) ) echo '<div class="error">' . $validationErrors[ 'keywords' ] . '</div>'; ?>
		</div>
		<div class="span6">
			<?php echo $this->Form->input( 'subject', array( 'label' => 'Matière *', 'class' => 'input-xlarge', 'autocomplete' => 'off' ) ); ?>
			<?php if ( !empty( $validationErrors[ 'subject' ] ) ) echo '<div class="error">' . $validationErrors[ 'subject' ] . '</div>'; ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span11">
			<?php
				$semestersList = array();

				foreach ( $registrationSemesters as $semester ) {
					$semestersList[ $semester ] = $this->App->convertSemester( $semester );
				}

				echo $this->Form->input( 'semester', array( 'label' => 'Session', 'class' => 'input-medium', 'options' => $semestersList, 'selected' => $registrationSemester ) );
			?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span11" style="margin-top: 15px;">
			<?php echo $this->Form->submit( 'Trouver', array( 'class' => 'btn btn-success', 'escape' => false ) ); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>

<?php if ( $loadingSearchResults ) echo $this->element( 'registration/searching_courses' ); ?>

<script type="text/javascript">
	var coursesSubjects = new Array( "<?php echo implode( '","', $coursesSubjects ); ?>" );
	var launchSearch = <?php if ( $loadingSearchResults ) echo true; else echo false; ?>;
</script>
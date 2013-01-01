<tr data-nrc="<?php echo $course[ 'SelectedCourse' ][ 'nrc' ]; ?>" data-credits="<?php echo $course[ 'UniversityCourse' ][ 'credits' ]; ?>">
	<td>
		<span class="title"><?php
			if ( strlen( $course[ 'UniversityCourse' ][ 'title' ] ) > 35 ):
				echo substr( $course[ 'UniversityCourse' ][ 'title' ], 0, 30 ) . "...";
			else:
				echo $course[ 'UniversityCourse' ][ 'title' ];
			endif;
		?></span>
		<br />
		<span class="nrc">NRC : <?php echo $course[ 'SelectedCourse' ]['nrc']; ?></span>
	</td>
	<td class="credits">
		<?php echo $course[ 'SelectedCourse' ]['code']; ?>
		<br />
		<a href="#" class="btn delete-link"><i class="icon-remove"></i></a>
	</td>
</tr>
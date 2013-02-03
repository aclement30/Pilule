<?php
    if ( !empty( $semestersList ) ) :
        echo $this->element( 'semesters_dropdown', array( 'semestersList' => $semestersList, 'selectedSemester' => $selectedSemester ) );
    endif;
?>

<div class="row-fluid" style="padding-top: 15px;">

    <?php echo $this->element( 'empty_data' ); ?>

</div>
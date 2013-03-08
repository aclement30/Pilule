<div class="btn-group semesters-dropdown">
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        <?php echo $this->App->convertSemester( $selectedSemester ); ?>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu pull-right">
        <?php foreach ( $semestersList as $semester ) : ?>
            <li<?php if ( $semester == $selectedSemester ) echo ' class="selected"'; ?>>
                <a href="#" data-semester="<?php echo $semester; ?>">
                    <?php echo $this->App->convertSemester( $semester ); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="btn-group courses-display">
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Cours disponibles <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right">
        <li><a href="#" data-list="all">Tous les cours</a></li>
        <li class="selected"><a href="#" data-list="available">Cours disponibles</a></li>
    </ul>
</div>
<div class="semester-select semesters-dropdown">
    <?php if ( !$isMobile ) : ?>
        <div class="btn-group">
            <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
                <?php echo $this->App->convertSemester( $selectedSemester ); ?>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <?php foreach ( $semestersList as $semester ) : ?>
                    <li>
                        <a href="#" data-semester="<?php echo $semester; ?>">
                            <?php echo $this->App->convertSemester( $semester ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>Session affichée : </div>
    <?php else : ?>
        <select class="input-medium">
            <?php foreach ( $semestersList as $semester ) : ?>
                <option value="<?php echo $semester; ?>"> <?php echo $this->App->convertSemester( $semester ); ?></option>
            <?php endforeach; ?>
        </select>
        <div>Session affichée : </div>
    <?php endif; ?>
    <div style="clear: both;"></div>
</div>
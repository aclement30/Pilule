<div class="semester-select semesters-dropdown clearfix right<?php if ( !empty( $compact) && $compact ) echo ' compact'; ?>">
    <?php //if ( !$isMobile ) : ?>
        <div class="btn-group">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <?php
                    if ( !empty( $compact) && $compact ):
                        echo $this->App->convertSemester( $selectedSemester, true );
                    else:
                        echo $this->App->convertSemester( $selectedSemester );
                    endif;
                ?>
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
        <div class="select-label"><?php if ( isset( $label ) ) echo $label; else echo 'Session affichée : '; ?></div>
    <?php /* else : ?>
        <select class="input-medium">
            <?php foreach ( $semestersList as $semester ) : ?>
                <option value="<?php echo $semester; ?>"> <?php echo $this->App->convertSemester( $semester ); ?></option>
            <?php endforeach; ?>
        </select>
        <div class="label">Session affichée : </div>
    <?php endif; */ ?>
    <div style="clear: both;"></div>
</div>
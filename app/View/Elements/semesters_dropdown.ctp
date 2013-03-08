<div class="semester-select semesters-dropdown <?php if ( !isset( $clearfix ) || $clearfix ) echo 'clearfix'; ?> right<?php if ( !empty( $compact) && $compact ) echo ' compact'; ?>">
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
    <select class="input-<?php if ( !empty( $compact) && $compact ) echo 'small'; else echo 'medium'; ?>">
        <?php foreach ( $semestersList as $semester ) : ?>
            <option value="<?php echo $semester; ?>"<?php if ( $semester == $selectedSemester ) echo ' selected="selected"'; ?>> 
                    <?php
                    if ( !empty( $compact) && $compact ):
                        echo $this->App->convertSemester( $semester, true );
                    else:
                        echo $this->App->convertSemester( $semester );
                    endif;
                ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="select-label"><?php if ( isset( $label ) ) echo $label; else echo 'Session affichée : '; ?></div>
</div>
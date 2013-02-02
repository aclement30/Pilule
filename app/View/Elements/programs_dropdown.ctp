<div class="programs-dropdown clearfix right<?php if ( !empty( $compact) && $compact ) echo ' compact'; ?>">
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <?php echo $programsList[ $selectedProgram ]; ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu pull-right">
            <?php foreach ( $programsList as $id => $programName ) : ?>
                <li<?php if ( $id == $selectedProgram ) echo ' class="selected"'; ?>>
                    <a href="#" data-program="<?php echo $id; ?>">
                        <?php echo $programName; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <select class="input-<?php if ( !empty( $compact) && $compact ) echo 'small'; else echo 'medium'; ?>">
            <?php foreach ( $programsList as $id => $programName ) : ?>
                <option value="<?php echo $id; ?>"<?php if ( $id == $selectedProgram ) echo ' selected="selected"'; ?>> 
                    <?php
                        echo $programName;
                    ?>
                </option>
            <?php endforeach; ?>
        </select>
    <div class="select-label"><?php if ( isset( $label ) ) echo $label; else echo 'Programme : '; ?></div>
    <div style="clear: both;"></div>
</div>
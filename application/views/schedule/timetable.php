<div class="alert alert-info" style="display: none; margin-top: 20px; margin-bottom: 0px;">Nous avons actuellement quelques soucis avec l'affichage de l'horaire et nous sommes en train de travailler à résoudre le problème.<br />Si vous avez un problème avec votre horaire, merci de nous envoyer une capture d'écran et votre IDUL à l'adresse <a href="mailto:pilule@alexandreclement.com" style="font-weight: bold;">pilule@alexandreclement.com</a>.</div>
<div class="row-fluid" style="margin-top: 5px;">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="icon-calendar"></i></span>
                <h5>Cours en classe</h5>
                <div class="buttons">
                    <div class="btn-group" style="float: right;">
                        <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
                            <?php echo convertSemester($semester_date); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <?php foreach ($semesters as $semester) {
                            ?><li><a href="javascript:app.schedule.displaySemester(<?php echo $semester['semester']; ?>);"<?php if ($semester['semester'] == $semester_date) echo ' style="font-weight: bold;"'; ?>><?php echo convertSemester($semester['semester']); ?></a></li><?php
                        } ?>
                        </ul>
                    </div>
                    <div style="float: right; font-size: 8pt; color: grey; margin-right: 5px; margin-top: 1px;">Session affichée : </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div class="widget-content nopadding">
                <div class="panel-left">
                    <div id="calendar">
                    </div>
                </div>
                <div id="external-events" class="panel-right no-print">
                    <div class="panel-title"><h5>Autres cours</h5></div>
                    <div class="panel-content">
                        <?php
                        foreach ($classes as $class) {
                            if (empty($class['day'])) {
                                ?>
                                <div class="class">
                                    <div class="title">
                                        <?php echo $class['title']; ?>
                                    </div>
                                    <div class="code">
                                        <?php echo $class['code']; ?>
                                    </div>
                                    <div class="nrc">
                                        NRC : <?php echo $class['nrc']; ?>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <hr style="margin: 5px 0;" />
                                    <div class="type">
                                        <i class="icon-briefcase"></i> <?php echo $class['type']; ?>
                                    </div>
                                    <?php
                                    if ((!empty($class['teacher'])) and strlen($class['teacher']) > 2) { ?>
                                    <div class="teacher">
                                        <i class="icon-user"></i> <?php echo $class['teacher']; ?>
                                    </div>
                                    <?php } ?>
                                    <hr style="margin: 5px 0;" />
                                    <div class="dates">
                                        <i class="icon-calendar"></i> <?php echo currentDate($class['date_start'], 'j M Y'); ?> &mdash; <?php echo currentDate($class['date_end'], 'j M Y'); ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
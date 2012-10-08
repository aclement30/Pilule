<div class="row-fluid">
    <div class="request-description">Données extraites du système de gestion des études de l'Université Laval, le <?php echo date('d/m/Y, à H:i', $last_request['timestamp']); ?>.</div>
</div>
<?php
/*
?>
<div class="alert alert-info sharing-notice">
    <div style="float: left;">L'horaire de cette session est disponible à l'adresse suivante :
    <input type="text" value="<?php echo site_url(); ?>public/t/3472n28h26G362HSG26U" /></div>
    <div style="float: right;"><a href="javascript:app.schedule.share('<?php echo $semester_date; ?>',false);" class="btn btn-danger"><i class="icon-remove icon-white"></i> Annuler</a> <a href="<?php echo site_url(); ?>support/faq/#faq7" class="btn"><i class="icon-info-sign"></i> Aide</a></div>
    <div style="clear: both;"></div>
</div>
<?php */ ?>

<?php if ( !empty( $classes ) ) { ?>
<div class="row-fluid" style="margin-top: 5px;">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="icon-calendar"></i></span>
                <h5>Cours en classe</h5>
                <div class="buttons semester-select">
                    <div class="btn-group" style="float: right;">
                        <a class="btn<?php if ( $mobile_browser != 1 ) echo ' btn-mini'; ?> dropdown-toggle" data-toggle="dropdown" href="#">
                            <?php echo convertSemester($semester_date); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <?php foreach ($semesters as $semester) {
                            ?><li><a href="javascript:app.schedule.displaySemester(<?php echo $semester['semester']; ?>);"<?php if ($semester['semester'] == $semester_date) echo ' style="font-weight: bold;"'; ?>><?php echo convertSemester($semester['semester']); ?></a></li><?php
                        } ?>
                        </ul>
                    </div>
                    <div style="float: right;<?php if ( $mobile_browser != 1 ) echo ' font-size: 8pt; margin-top: 1px;'; else echo ' margin-top: 5px;'; ?> color: grey; margin-right: 5px;">Session affichée : </div>
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
<?php } else {
?>
<div class="buttons semester-select">
    <div class="btn-group" style="float: right;">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <?php echo convertSemester($semester_date); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu pull-right">
            <?php foreach ($semesters as $semester) {
            ?><li><a href="javascript:app.schedule.displaySemester(<?php echo $semester['semester']; ?>);"<?php if ($semester['semester'] == $semester_date) echo ' style="font-weight: bold;"'; ?>><?php echo convertSemester($semester['semester']); ?></a></li><?php
        } ?>
        </ul>
    </div>
    <div style="float: right; margin-top: 5px; color: grey; margin-right: 5px;">Session affichée : </div>
    <div style="clear: both;"></div>
</div>

<div class="row-fluid" style="padding-top: 15px;">

    <div class="hero-unit no-data span12">
        <div class="span1">&nbsp;</div>
        <div class="span3" style="text-align: right; padding-right: 15px;">
            <img src="./img/lego-man.png" alt="Lego Man" />
        </div>
        <div class="span7" style="padding-top: 40px;">
            <p class="lead">Aucune donnée enregistrée</p>
            Votre dossier Capsule ne contient aucune donnée pour cette page.
        </div>
        <div class="span1">&nbsp;</div>
        <div style="clear: both;"></div>
    </div>
</div>
<?php
}
?>

<div class="row-fluid footer">
    <div class="navbar">
        <div class="navbar-inner">
            <ul class="nav">
                <li><a href="<?php echo site_url(); ?>support/terms">Conditions d'utilisation</a></li>
                <li><a href="<?php echo site_url(); ?>support/privacy">Confidentialité des données</a></li>
                <li><a href="<?php echo site_url(); ?>support/faq">F.A.Q.</a></li>
                <li><a href="<?php echo site_url(); ?>support/contact">Contact</a></li>
            </ul>
            <div class="nav" style="float: right; padding-top: 10px; margin-right: 20px;">
                <p style="float: right; margin-left: 40px; color: gray; font-style: italic; font-size: 8pt; text-align: right;">Projet hébergé par<br /><img src="<?php echo site_url(); ?>img/ulaval-black.png" height="36" align="absbottom" style="padding-top: 6px; padding-bottom: 5px;" /></p>
                <p id="copyright" style="text-align: right; float: right;">Conception<br /><a href="http://www.alexandreclement.com" target="_blank" style="font-style: normal; font-size: 9pt;">Alexandre Clément</a></p>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/bootstrap.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/users.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/studies.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/tuitions.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/settings.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/schedule.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/cache.js"></script>
    <?php if (!isset($user)) { ?>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/login.js"></script>
    <?php } ?>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/main.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/ajax.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/path.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/fullcalendar.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/gcal.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/jquery.flot.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/jquery.flot.pie.min.js"></script>
    <script type='text/javascript' src="<?php echo site_url(); ?>js/jquery.flot.resize.min.js"></script>
	<script language="javascript">
	$(document).ready(function() {
        app.user.isAuthenticated = true;
		//$('<iframe id="report-frame" name="report-frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">').appendTo('body');
	});
	</script>
</body>
</html>
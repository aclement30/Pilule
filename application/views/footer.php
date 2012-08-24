				<?php if (isset($content)) echo $content; ?>
				</div>
			</div> <!-- end .container -->
		</div> 	<!-- end #right-shadow -->
				
	</div> <!-- end #content -->
	
	<div id="footer">
		<div id="footer-content">
			<div class="container clearfix">
			
			</div> <!-- end .container -->	
				
			<div id="footer-bottom">	
				<div class="container clearfix">
					
						<ul id="footer-menu" class="bottom-nav">
							<?php if (((isset($section) and $section!='login') or (!isset($section))) and isset($user)) { ?><li class="current_page_item"><a href="./welcome">Tableau de bord</a></li><?php } ?>	
							<li><a href="<?php echo site_url(); ?>support/terms">Conditions d'utilisation</a></li>
							<li><a href="<?php echo site_url(); ?>support/privacy">Confidentialité des données</a></li>
							<li><a href="<?php echo site_url(); ?>support/faq">F.A.Q.</a></li>
							<li><a href="<?php echo site_url(); ?>support/contact">Contact</a></li>
							<?php if (isset($mobile_browser) and $mobile_browser==1) { ?><li><a href="<?php echo site_url(); ?>welcome/s_changedisplay/mobile">Site mobile</a></li><?php } ?>
						</ul> <!-- end ul#nav -->
					<p style="float: right; margin-left: 40px; margin-right: 20px; color: gray; font-style: italic; font-size: 8pt; text-align: right;">Projet hébergé par<br /><img src="<?php echo site_url(); ?>images/ulaval-black.png" height="36" align="absbottom" style="padding-top: 6px; padding-bottom: 5px;" /></p>
					<p id="copyright" style="text-align: right;">Conception<br /><a href="http://www.alexandreclement.com" target="_blank" style="font-style: normal; font-size: 9pt;">Alexandre Clément</a></p>
					<div style="clear: both;"></div>
				</div> <!-- end .container -->	
			</div> <!-- end #footer-bottom -->
		</div> <!-- end #footer-content -->
	</div> <!-- end #footer -->
	<span><div id="notification"></div></span>
	<script language="javascript">
	$(document).ready(function() {
		$('<iframe id="report-frame" name="report-frame" frameborder="0" src="blank.html" style="width: 0px; height: 0px;">').appendTo('body');
		
		$('#secondary-menu li.tab-welcome').toggleClass('current-menu-item');
	});
	</script>
</body>
</html>
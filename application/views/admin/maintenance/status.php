<h2 class="title">Maintenance</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<?php
if ($maintenance == 1) { ?>
<div style="float: left; width: 200px; margin-top: 7px; background-color: #eee; margin-right: 10px; padding: 0px 10px 7px; border-radius: 20px; box-shadow:inset 0 0 5px #aaa;"><div style="float: left;"><img src="./images/false-big.png" width="24" style="position: relative; top: 4px;" /></div><div style="font-size: 11pt; float: left; margin: 8px 7px 0;">Site hors ligne</div><div style="clear: both;"></div></div>
<div style="float: left;"><a href="javascript:unblockSite();" class='icon-button unblock-icon'><span class='et-icon'><span>Débloquer le site</span></span></a></div>
<?php } else { ?>
<div style="float: left; width: 200px; margin-top: 7px; background-color: #eee; margin-right: 10px; padding: 0px 10px 7px; border-radius: 20px; box-shadow:inset 0 0 5px #aaa;"><div style="float: left;"><img src="./images/true-big.png" width="24" style="position: relative; top: 4px;" /></div><div style="font-size: 11pt; float: left; margin: 8px 7px 0;">Site en ligne</div><div style="clear: both;"></div></div>
<div style="float: left;"><a href="javascript:blockSite();" class='icon-button block-icon'><span class='et-icon'><span>Bloquer le site</span></span></a></div>
<?php } ?>
<div style="clear: both;"></div>

<script language="javascript">
function blockSite () {
	loading();
	
	!sendData('GET','./admin/maintenance/s_blockSite', '');
}

function statusBlockSite (result) {
	resultMessage('Le site a été bloqué.');
	
	window.location.reload();
}

function unblockSite () {
	loading();
	
	!sendData('GET','./admin/maintenance/s_unblockSite', '');
}

function statusUnblockSite (result) {
	resultMessage('Le site a été débloqué.');
	
	window.location.reload();
}
</script>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
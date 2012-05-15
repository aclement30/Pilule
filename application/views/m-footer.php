			<div class="entry page">
				<div class="entry-top"> 
					<div class="entry-content">
					</div>
				</div>
			</div>
			
			</div>
			</div>
	</div>
<script language="javascript">
$('#header-bottom').after($('#sidebar'));
/*
$("a").not('.newTab').click(function (event) {
    event.preventDefault();
    window.location = $(this).attr("href");
});
*/

<?php if ($mobile_browser == 1) echo 'isMobile = 1;'; ?>

function disableAutoLogon () {
	if (Modernizr.localstorage) {
		localStorage.removeItem('pilule-autologon-idul');
		localStorage.removeItem('pilule-autologon-password');
	}
}
</script>
</body>
</html>
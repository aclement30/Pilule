<h2 class="title">Ajouter un utilisateur</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<div class="post-content form">
	<div class="result-message"></div>
	<div class="error-message"></div>
	<div id="form-add">
	<div class="form-elements" id="add-table">
		<div class="field first">
			<div class="field-title">
				Nom
			</div>
			<input type="text" name="name" id="name" class="input blur" value="Nom complet" onfocus="javascript:fieldFocus(this, 'Nom complet');" onblur="javascript:fieldBlur(this, 'Nom complet');" style="width: 220px; text-transform: capitalize;" />
			<div style="clear: both;"></div>
		</div>
		<div class="field last">
			<div class="field-title">
				IDUL
			</div>
			<input type="text" name="idul" id="idul" class="input blur" value="Identifiant" onfocus="javascript:fieldFocus(this, 'Identifiant');" onblur="javascript:fieldBlur(this, 'Identifiant');" style="width: 220px; text-transform: lowercase;" />
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div style="margin-top: 5px; float: right;">
		<a href='javascript:addUser();' name="wp-submit" id="wp-submit" class='small-button smallsilver'><span>+ Ajouter</span></a><div class="clear"></div>
	</div>
	</div>
</div>
<script language="javascript">
function fieldFocus (field, text) {
	if ($(field).val()==text) {
		$(field).toggleClass('blur');
		$(field).val('');
	}
}

function fieldBlur (field, text) {
	if ($(field).val()=='') {
		$(field).toggleClass('blur');
		$(field).val(text);
	}
}

function addUser () {
	$('#loading-img').fadeIn();
	$('.error-message').hide();
	
	var idul = $('#idul').val();
	var name = $('#name').val();
	
	!sendData('POST','./admin/s_addbetauser', 'idul='+idul+'&name='+name);
}

function errorAdd (message) {
	alert(message);
	$('.error-message').html(message);
	//$('.error-message').fadeIn();
}


function successAdd(message) {
	//alert(message);
	$('.result-message').html(message);
	$('.result-message').fadeIn();
	setTimeout("window.document.location='./admin/'", 1500);
}

$('#name').focus();
</script>
<style type="text/css" media="screen">
.post-content .error-message {
	background-color: #a52c0f;
	padding: 8px 5px;
	-moz-border-radius: 5px;
	text-align: center;
	color: #fff;
	margin-bottom: 10px;
	display: none;
}

.post-content .result-message {
	background-color: #060;
	padding: 8px 5px;
	-moz-border-radius: 5px;
	text-align: center;
	color: #fff;
	margin-bottom: 10px;
	display: none;
}

#add-table th {
	width: 30px;
	font-weight: bold;
	padding-top: 8px;
	padding-right: 10px;
	text-align: right;
}

#add-table td {
	padding-top: 10px;
}

body {
	background-color: #dae6f1;
}

.widget-content {
	background-color: #fff;
}
</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
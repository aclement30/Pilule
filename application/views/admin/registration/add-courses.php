<h2 class="title">Ajout de cours</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<form action="./admin/registration/s_addcourses" method="post" target="frame" id="form-courses">
<table width="100%">
	<tbody>
		<tr class="line">
			<td class="field_title" style="width: 130px;">Programme</td>
			<td style="width: 280px; padding-top: 10px;"><?php echo $program['title']; ?></td>
		</tr>
		<tr class="line">
			<td class="field_title">Catégorie</td>
			<td><select name="category" id="category">
					<option value=""> </option>
				<?php foreach ($sections as $section) {
					?><option value="<?php echo $section['code']; ?>"> <?php echo $section['title']; ?></option><?php
					if ($section['children'] != array()) {
						foreach ($section['children'] as $subsection) {
							?><option value="<?php echo $subsection['code']; ?>" style="padding-left: 20px;"> <?php echo $subsection['title']; ?></option><?php
							if ($subsection['children'] != array()) {
								foreach ($subsection['children'] as $subsection2) {
									?><option value="<?php echo $subsection2['code']; ?>" style="padding-left: 40px;"> <?php echo $subsection2['title']; ?></option><?php
								}
							}
						}
					}
				}
				?>
			</select></td>
		</tr>
		<tr class="line">
			<td class="field_title">Cours optionnels</td>
			<td><input type="checkbox" name="optional" id="optional" class="field" value="1" autocomplete="off" style="cursor: pointer;" /> <label for="optional" style="cursor: pointer;">Cours optionnels</label></td>
		</tr>
		<tr class="line">
			<td class="field_title">Type de données</td>
			<td><select name="type" id="type" onchange="if(this.selectedIndex==2){$('#field_repertoire').fadeIn();$('#field_repertoire').focus();}else{$('#field_repertoire').hide();}">
					<option value="normal"> Données brutes</option>
					<option value="list"> Liste de codes</option>
					<option value="list"> Entrées du répertoire</option>
			</select></td>
		</tr>
		<tr class="line" id="field_repertoire" style="display: none;">
			<td class="field_title">Entrées du répertoire</td>
			<td><textarea name="repertoire" id="repertoire" style="width: 400px; height: 200px;" onchange="javascript:convertRepertoire();" autocomplete="off"></textarea></td>
		</tr>
		<tr class="line">
			<td class="field_title">Données</td>
			<td><textarea name="data" id="data" style="width: 400px; height: 200px;" autocomplete="off"></textarea></td>
		</tr>
		<tr>
			<td style="padding-left: 230px;" colspan="2"><a href="javascript:submitForm();" class='icon-button signup-icon'><span class='et-icon'><span>Ajouter</span></span></a><div style="float: left; padding-left: 10px; padding-top: 15px;"><img src="<?php echo site_url(); ?>images/loading.gif" height="16" width="16" style="display: none;" id="loading-img" alt="Chargement" /></div><div style="clear: both;"></div></td>
	</tbody>
</table>
<input type="hidden" name="program" value="<?php echo $program['code']; ?>" />
</form>
<form id="form-repertoire" target="frame" action="./admin/registration/s_convertRepertoire" method="post">
<input type="hidden" name="data_repertoire" id="repertoire-value" value="" />
</form>
<iframe name="frame" frameborder="0" style="width: 0px; height: 0px;"></iframe>
<script language="javascript">
$('#name').focus();
</script>
<script language="javascript">
var formError = 0;

function convertRepertoire () {
	$('#repertoire-value').val($('#repertoire').val());
	
	$('#form-repertoire').submit();
}

function selectProgram (program) {
	//!sendData('GET','./admin/registration/s_getcategories', 'program/'+program);
}

function checkField (field) {
	var value = document.getElementById(field).value;
	document.getElementById('error-message-'+field).style.display = 'none';
	
	switch (field) {
		case 'name':
			if (value=='') {
				displayError(field);
				formError = 1;
			}
		break;
		case 'idul':
			if (value=='') {
				displayError(field);
				formError = 1;
			}
		break;
	}
}

function submitForm () {
	formError = 0;
	
	//checkField('name');
	//checkField('idul');
	
	if (formError==0) {
		$('#loading-img').show();
		
		// Envoi du formulaire
		document.getElementById('form-courses').submit();
	}
}

function statusAdd (response) {
	$('#loading-img').hide();
}

function displayError (id) {
	document.getElementById('error-message-'+id).style.display = 'block';
}
</script>
<style type="text/css">
.line {
	padding: 5px;
	font-size: 10pt;
	vertical-align: top;
}

.line td {
	padding-bottom: 10px;
	padding-top: 5px;
}

.line td select {
	margin-top: 3px;
}

.line .error-message {
	color: #ba1007;
	font-weight: normal;
	font-size: 9pt;
	padding: 5px;
	padding-left: 35px;
	background-image: url(../images/error.png);
	background-repeat: no-repeat;
	background-position: 10px 4px;
	display: none;
	padding-top: 10px;
}

.line .field_title {
	font-size: 10pt;
	padding-bottom: 0px;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	
}

table .line .field_title {
	text-align: right;
	vertical-align: top;
	padding-top: 10px;
	padding-right: 15px;
}

table .line .field_title .description {
	color: gray;
	font-weight: normal;
	font-size: 8pt;
}

.line input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
	padding: 2px;
}

.line textarea {
	border: 1px solid gray;
	padding: 2px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
}
</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
<h2 class="title">Programmes disponibles</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<br />
<table>
	<tbody>
		<?php
		foreach ($programs as $program) { ?>
		<tr>
			<th class="left" style="width: 50px;" onClick="javascript:document.location.hash='#!/admin/registration/program/<?php echo $program['code']; ?>';"><?php echo $program['code']; ?></th>
			<td onClick="javascript:document.location.hash='#!/admin/registration/program/<?php echo $program['code']; ?>';"><?php echo $program['title'] ; ?></td>
			<td style="font-size: 8pt;" onClick="javascript:document.location.hash='#!/admin/registration/program/<?php echo $program['code']; ?>';"><?php if ($program['active'] == '1') echo '<span style="color: green;">ACTIF</span>'; else echo '<span style="color: red;">INACTIF</span>'; ?></td>
		</tr><?php } ?>
	</tbody>
</table>
<style type="text/css">
.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

.post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
	cursor: pointer;
}

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
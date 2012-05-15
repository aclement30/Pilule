<h2 class="title">Liste des utilisateurs</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<?php
$last_letter = '';

foreach ($users as $user2) {
	$current_letter = strtoupper(substr($user2['idul'], 0, 1));

	if ($current_letter>$last_letter) {
		$last_letter = $current_letter;
		
		?><a href="<?php echo site_url(); ?>admin/users/#<?php echo $last_letter; ?>"><?php echo $last_letter; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php
	}
}

$last_letter = '';
$anchor = 0;
?><br /><br />
<table>
	<tbody>
		<tr>
			<th>Nom</th>
			<th>IDUL</th>
			<th>Dernière visite</th>
			<th>&nbsp;</th>
		</tr>
		<?php
		foreach ($users as $user2) {
			$current_letter = strtoupper(mb_substr($user2['idul'], 0, 1, 'UTF-8'));

			if ($current_letter>$last_letter) {
				$last_letter = $current_letter;
				$anchor = 1;
			}
			?><tr id="user-<?php echo $user2['idul']; ?>">
				<td><?php echo $user2['name']; if ($anchor==1) { ?><a style="visibility: hidden;" name="<?php echo $last_letter; ?>"><?php echo $last_letter; ?></a><?php } ?></td>
				<td><?php echo $user2['idul']; ?></td>
				<td><?php if ($user2['last_visit']!='') {
						echo currentDate(date('Ymd', $user2['last_visit']), 'd M Y');
					} ?></td>
				<td><a href="javascript:removeUser('<?php echo $user2['name']; ?>', '<?php echo $user2['idul']; ?>');" title="Supprimer l'utilisateur"><img src="./images/cross.png" /></a></td>
			</tr><?php
			$anchor = 0;
		}
		?>
	</tbody>
</table>
<div style="padding: 10px; font-size: 8pt; color: gray; text-align: center;"><?php echo count($users); ?> utilisateurs</div>
<script language="javascript">
$('.post-content table tr:even').css('backgroundColor', '#dae6f1');

function removeUser (name, idul) {
	if (confirm("Voulez-vous vraiment supprimer les données de "+name+" ?")) {
		!sendData('POST','./admin/users/s_removeuser', 'idul='+idul);
	}
}

function statusRemove (response, idul) {
	if (response==1) {
		resultMessage("Les données de l'utilisateur ont été supprimées.");
		
		$('#user-'+idul).fadeOut();
		$('#user-'+idul).remove();

		$('.post-content table tr:odd').css('backgroundColor', '#fff');
		$('.post-content table tr:even').css('backgroundColor', '#dae6f1');
	} else {
		errorMessage("Erreur lors de la suppression de l'utilisateur.");
	}
}
</script>
<style type="text/css" media="screen">
.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	text-align: left;
	font-weight: bold;
	font-size: 11pt;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
}

.post-content table th.left, .post-content table th, .post-content table td {
	padding: 10px;
	vertical-align: top;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}

#notice {
	background-color: silver;
	padding: 7px 10px;
	font-size: 8pt;
	margin-top: 10px;
	display: none;
}
</style>
<style type="text/css" media="print">
body {
	margin: 0px;
	font-family: Helvetica, Arial;
	font-size: 10pt;
}

#page {
	width: 100%;
}

#header, #header-bottom, a.link, a.refresh, #footer, #sidebar {
	display: none;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	border: 1px solid silver;
	padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
}

.post-content table th {
	text-align: left;
	font-weight: normal;
	text-transform: uppercase;
	border-bottom: 2px solid gray;
	padding: 10px;
	vertical-align: top;
}

.post-content table th.left {
	width: 170px;
	font-weight: bold;
	text-align: right;
	padding-right: 20px;
	text-transform: none;
}

.post-content table th.left, .post-content table td {
	padding: 10px;
	vertical-align: top;
	border-bottom: 1px solid silver;
}

.post-content a.type {
	background-color: #eee;
	-moz-border-radius: 5px;
	text-decoration: none;
	color: #444;
	padding: 8px 15px;
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.post-content a.type:hover, .post-content a.type.active {
	background-color: #888;
	color: #fff;
}

h3 {
	color: #666;
	margin-top: 25px;
}

#notice {
	background-color: none;
	padding: 0px;
	font-size: 7pt;
	margin-bottom: 25px;
	color: #999;
}

h1 {
	margin-bottom: 10px;
}
</style>
<style type="text/css">
<?php if ($mobile==1) { ?>
.post-content table th {
	width: 100px;
	font-size: 9pt;
}

br.space {
	display: none;
}
<?php } ?>
</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
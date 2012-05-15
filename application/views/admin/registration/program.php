<h2 class="title"><?php echo $program['title']; ?></h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<h3>Schéma du programme<a href="./admin/registration/addcourses/<?php echo $program['code']; ?>" class="link" style="font-size: 10pt; font-family: Helvetica; float: right; text-decoration: none;"><img src="<?php echo site_url(); ?>images/add.png" align="absbottom" style="position: relative; top: 2px;" />&nbsp;&nbsp;Ajouter des cours</a><div style="clear: both;"></div></h3>
<table>
	<tbody>
<?php foreach ($sections as $section) {
	if ($section['code'] != 'p-inter') {
		?><tr>
			<td class="section top"><?php echo $section['title']; ?></td>
			<td class="courses"><strong><?php if (isset($program_courses[$section['code']])) echo count($program_courses[$section['code']]) . ' cours'; ?></strong></td>
		</tr><?php
		if ($section['children'] != array()) {
			foreach ($section['children'] as $subsection) {
		?><tr>
			<td class="section children"><?php echo $subsection['title']; ?></td>
			<td class="courses"><?php if (isset($program_courses[$subsection['code']])) echo count($program_courses[$subsection['code']]) . ' cours'; ?></td>
		</tr><?php
				if ($subsection['children'] != array()) {
					foreach ($subsection['children'] as $subsection2) {
				?><tr>
					<td class="section subchildren"><?php echo $subsection2['title']; ?></td>
					<td class="courses"><?php if (isset($program_courses[$subsection2['code']])) echo count($program_courses[$subsection2['code']]) . ' cours'; ?></td>
				</tr><?php
					}
				}
			}
		}
	}
} ?></tbody></table>
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

.section.compulsory, .section.top {
	font-weight: bold;
}

.section label {
	cursor: pointer;
}

.section {
	padding-left: 20px;
	padding-bottom: 5px;
}

.section.children {
	padding-left: 40px;
}

.section.subchildren {
	padding-left: 80px;
}

.post-content td.courses {
	font-size: 9pt;
	text-align: right;
}

h3 a.link {
	top: 5px;
	position: relative;
}

h3 a.link:hover {
	color: black;
}
</style>
<div class="clear"></div></div>
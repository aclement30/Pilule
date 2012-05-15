<h2 class="title">Paramètres d'affichage</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<p>
Veuillez choisir les sections de cours à afficher dans la page d'inscription :
</p>
<h3><?php echo $program; ?></h3>
<form action="./registration/s_configure" method="post" id="form-configure" target="frame">
<div class="section compulsory"><input type="checkbox" id="section-compulsory" checked="checked" disabled="disabled" />&nbsp;<label for="section-compulsory">Formation commune</label></div>
<?php foreach ($sections as $section) {
	if ($section['code'] != 'compulsory' and $section['code'] != 'p-inter') {
		?><div class="section top"><?php if ($section['compulsory'] == '1') { ?><input type="checkbox" checked="checked" disabled="disabled" /><?php } else { ?><input type="checkbox" name="section_<?php echo str_replace("-", "_", $section['code']); ?>" id="section_<?php echo str_replace("-", "_", $section['code']); ?>" value="yes"<?php if (in_array($section['id'], $user_sections)) echo ' checked="checked"'; ?> /><?php } ?>&nbsp;<label for="section_<?php echo str_replace("-", "_", $section['code']); ?>"><?php echo $section['title']; ?></label></div><?php
		if ($section['children'] != array() and $section['unique'] == '1') {
			foreach ($section['children'] as $section2) {
		?><div class="section children"><?php if ($section2['compulsory'] == '1') { ?><input type="checkbox" checked="checked" disabled="disabled" /><?php } else { ?><input type="radio" name="section_<?php echo str_replace("-", "_", $section['code']); ?>" id="section_<?php echo str_replace("-", "_", $section['code']."_".$section2['code']); ?>" value="<?php echo $section2['id']; ?>"<?php if (in_array($section2['id'], $user_sections)) echo ' checked="checked"'; ?> /><?php } ?>&nbsp;<label for="section_<?php echo str_replace("-", "_", $section['code']."_".$section2['code']); ?>"><?php echo $section2['title']; ?></label></div><?php
			}
		}
	}
} ?>
<input type="hidden" name="program_code" value="<?php echo $program_code; ?>" />
</form>
<iframe name="frame" style="width: 0px; height: 0px;" frameborder="0"></iframe>
<a href="javascript:registrationObj.configure();" class='icon-button signup-icon' style="margin-top: 15px; margin-left: 230px;"><span class='et-icon'><span>Enregistrer</span></span></a><div style="clear: both;"></div>
<style type="text/css">
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
	padding-left: 60px;
}

.post-content .class-choice {
	width: 160px;
	background-color: #eee;
	padding: 10px;
	border: 1px solid silver;
	float: left;
	margin: 0px 15px 15px 0px;
}

.post-content .class-choice .type {
	padding-bottom: 5px; color: #333; /*letter-spacing: -1px;*/ line-height: 1em; font-weight: normal;font-size: 18px;font-family: 'Yanone Kaffeesatz', Arial, sans-serif; margin-top: 5px; margin-bottom: 5px;
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}

.post-content .class-choice .timetable, .post-content .class-choice .teacher {
	border-bottom: 1px dotted silver;
	padding-bottom: 5px;
	margin-bottom: 5px;
}


.post-content .class-choice .timetable {
	padding-top: 8px;
	padding-bottom: 8px;
}


.post-content .class-choice .nrc {
	margin-top: 8px;
	font-size: 10pt;
}
</style>
<div class="clear"></div></div>
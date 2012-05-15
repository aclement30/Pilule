<h2 class="title" style="display: block;">Partager mon horaire</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">
<div style="float: right;"><a href="javascript:scheduleObj.unlinkFBAccount();" class='icon-button facebook-icon' style="margin-left: 20px; margin-top: 0px;"><span class='et-icon'><span>Changer</span></span></a></div><div style="float: right; padding-top: 8px;"><strong>Compte : </strong><?php if (isset($fb_data)) { ?><a href="<?php echo $fb_data['me']['link']; ?>" target="_blank"><?php echo $fb_data['me']['name']; ?></a><?php } ?></div>
<div style="clear: both;"></div>

<h3>Préférences de partage</h3>
<h4>Tous les amis</h4>
<input type="radio" name="sharing_type" value="all" id="sharing_type_all" /> <label for="sharing_type_all" style="cursor: pointer;">Permettre à <strong>tous mes amis Facebook</strong> de consulter mon horaire de cours sur Pilule.</label>
<h4>Listes d'amis sélectionnées</h4>
<input type="radio" name="sharing_type" value="lists" id="sharing_type_lists" checked="checked" /> <label for="sharing_type_lists" style="cursor: pointer;">Permettre aux <strong>listes d'amis sélectionnées</strong> de consulter mon horaire de cours sur Pilule.</label>
<div style="clear: both;"></div>
<div style="margin-top: 10px;">
<?php
if (isset($fb_friendlists) and is_array($fb_friendlists)) {
	foreach ($fb_friendlists['data'] as $friendlist) {
		?><div onclick="javascript:scheduleObj.selectFriendlist('<?php echo $friendlist['id']; ?>');" id="friendlist-<?php echo $friendlist['id']; ?>" class="user_list <?php if ($friendlist['list_type'] == 'education' || $friendlist['list_type'] == 'close_friends') echo 'selected'; ?>"><div class="inside" style="background-image: url(images/fb_list_<?php echo $friendlist['list_type']; ?>.png);"><?php echo $friendlist['name']; ?></div></div><?php
	}
}
?><div style="clear: both;"></div>
</div>
<!--
<h4>Amis sélectionnés</h4>
<input type="radio" name="sharing_type" value="selected" id="sharing_type_selected" /> <label for="sharing_type_selected" style="cursor: pointer;">Permettre aux <strong>amis sélectionnés</strong> de consulter mon horaire de cours sur Pilule.</label>
-->
<style type="text/css">
.user_list {
	cursor: pointer;
	border: 1px solid silver;
	border-radius: 5px;
	margin-right: 5px;
	margin-bottom: 5px;
	float: left;
	background-color: #efefef;
	border: 2px solid white;
}

.user_list .inside {
	border-radius: 4px;
	border: 1px solid silver;
	background-repeat: no-repeat;
	background-position: 4px center;
	padding: 3px 6px;
	padding-left: 24px;
}

.user_list.selected {
	border: 2px solid gray;
	background-color: #666;
	color: #fff;
}

.user_list:hover {
	background-color: #ddd;
}

.user_list.selected:hover {
	background-color: #666;
}

.page-break { display:none; }

.class-panel {
	display: none;
}

.post-content table {
	width: 100%;
	font-size: 10pt;
	padding: 0px;
}

.post-content table th {
	width: 300px;
	font-weight: bold;
	text-align: center;
	vertical-align: bottom;
	padding-bottom: 0px;
	font-size: 12px;
}

.post-content table td {
	border: 1px solid #efefef;
}

.post-content table th.hour {
	text-align: right;
	font-size: 8pt;
	background-color: #fff;
	border-bottom: 0px;
	padding: 0px;
	font-weight: normal;
	width: 50px;
	vertical-align: top;
	padding-right: 5px;
	padding-left: 0px;
}

.post-content table td.class {
	background-color: #dae6f1;
	border: 1px solid white;
	font-size: 12px;
}

.post-content .class .class-title {
	font-size: 15px;
	font-weight: bold;
	padding-bottom: 5px;
}

.post-content table td.class {
	padding: 10px;
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

.post-content a.type:hover, #content a.type.active {
	background-color: #888;
	color: #fff;
}

h2.title .semester {
	display: none;
}

#notice {
	background-color: silver;
	padding: 7px 10px;
	font-size: 8pt;
	margin-top: 10px;
	display: none;
}

.post-content .class .class-title {
	font-size: 15px;
	font-weight: bold;
	padding-bottom: 5px;
}

.post-content .class {
	padding: 10px;
	border-bottom: 1px solid silver;
	font-size: 12px;
}

.post-content .class .time {
	text-align: right;
	font-size: 8pt;
	width: 30px;
	font-weight: bold;
	float: left;
	padding-right: 15px;
	padding-left: 0px;
	padding-top: 18px;
}

.post-content .class .class-code {
	padding-bottom: 2px;
}

.post-content .class .class-teacher {
	float: left;
	color: #666;
	
}

.post-content .class .class-local {
	float: left;
	color: #666;
	width: 90px;
}

.post-content .class .info {
	float: left;
}

</style>
<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
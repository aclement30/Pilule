<h2 class="title">Stockage HTML5</h2>	
<div class="clear"></div>
<div class="page-separator"></div>
<div class="post-content">

<input type="button" value="Stocker les données localement" onclick="javascript:getLocalStorageVars();" />

<script language="javascript">
if (Modernizr.localstorage) {
// alert('yes');
} else {
  //alert('no');
}

var localStorageVars = new Array();

function getLocalStorageVars () {
	// Demande de liste des variables à stocker
	!sendData('GET','./cache/s_getLocalStorageVars', '');
}

function storeLocalData () {
	if (localStorageVars) {
		$.each(localStorageVars, function(key, value) {
			// Demande du contenu pour chaque variable
			!sendData('GET','./cache/s_getLocalStorageValue', encodeURIComponent(value));
		});
	}
}

function writeLocalData (key, value) {
	// Enregistrement du contenu de la variable dans l'ordinateur du client
	localStorage.setItem(key, value);
}
</script>

<div class="clear"></div></div>
</div> <!-- end .entry-content -->
</div> <!-- end .entry-top -->
</div> <!-- end .entry -->
<div class="clear"></div>
</div> <!-- end #main-area -->
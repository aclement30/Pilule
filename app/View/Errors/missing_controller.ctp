<div class="row-fluid" style="padding-top: 15px;">

    <div class="hero-unit no-data span12<?php if ( isset( $small ) ) echo ' small'; ?>">
	    <div class="span1">&nbsp;</div>
	    <div class="span3 image">
	        <img src="/img/lego-man.png" alt="Lego Man" />
	    </div>
	    <div class="span7">
	        <p class="lead">
	            Impossible de trouver la page demandée
	        </p>
	        <pre><?php echo $this->request->here; ?></pre>
	        Vous avez trouvé une page qui n'existe pas sur le site...
	    </div>
	    <div class="span1">&nbsp;</div>
	    <div style="clear: both;"></div>
	</div>

</div>
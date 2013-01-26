<div class="row-fluid loading-service-notice" style="margin-top: 10%;">
	<div class="span4">&nbsp;</div>
    <div class="hero-unit span4" style="text-align: center;">
    	<div style="margin-bottom: 30px;">
    		<img src="/img/redirect-loading.gif" alt="Chargement" />
    	</div>
    	<p class="lead">
    		<?php
    			if ( $insideIframe ) :
    				echo 'Ouverture de ' . $title_for_layout;
    			else:
    				echo 'Redirection vers ' . $title_for_layout;
    			endif;
    		?>
    	</p>
        <span style="color: gray;">Veuillez patienter un instant...</span>
    </div>
    <div class="span4">&nbsp;</div>
</div>

<!-- Hidden auto-login form -->
<?php 
	echo $this->Form->create( null, array( 'url' => $formUrl, 'class' => 'js-login-form', 'type' => 'POST' ) );

	foreach ( $fields as $name => $value ) :
        ?><input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><?php
    endforeach;

    echo $this->Form->end();
?>

<?php if ( !empty( $loadingFrameUrl ) ) : ?>
    <iframe id="loadingFrame" src="<?php echo $loadingFrameUrl; ?>" width="0" height="0" frameborder="0" onload="javascript:$( 'form.js-login-form' ).submit();"></iframe>
<?php endif; ?>
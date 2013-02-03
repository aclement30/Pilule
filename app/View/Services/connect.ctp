<div class="row-fluid loading-service-notice" style="margin-top: 10%;">
	<div class="span4">&nbsp;</div>
    <div class="hero-unit span4" style="text-align: center;">
    	<div style="margin-bottom: 30px;">
    		<img src="<?php echo Router::url( '/' ) ?>img/redirect-loading.gif" alt="Chargement" />
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
    if ( is_array( $formUrl ) ) {
        // Multiple forms
        foreach ( $formUrl as $index => $url ) {
            if ( $index != ( count( $formUrl ) - 1 ) ) {
                $target = 'loading-frame';
            } else {
                $target = '_self';
            }
            echo $this->Form->create( null, array( 'url' => $url, 'class' => 'js-external-frame-form js-form-' . $index, 'type' => 'POST', 'target' => $target ) );

            foreach ( $fields[ $index ] as $name => $value ) :
                ?><input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><?php
            endforeach;

            echo $this->Form->end();
        }
    } else {
    	echo $this->Form->create( null, array( 'url' => $formUrl, 'class' => 'js-external-frame-form js-form-0', 'type' => 'POST' ) );

    	foreach ( $fields as $name => $value ) :
            ?><input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><?php
        endforeach;

        echo $this->Form->end();
    }
?>

<?php if ( !empty( $loadingFrameUrl ) ) : ?>
    <iframe id="loadingFrame" name="loading-frame" src="<?php echo $loadingFrameUrl; ?>" width="0" height="0" frameborder="0"></iframe>
    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#loadingFrame' ).on( 'load', top.app.Common.loadExternalFrameForm );

            $( 'form.js-external-frame-form input[name=_method]' ).remove();
        } );
    </script>
<?php endif; ?>
<?php
    foreach ( $buttons as $index => $button ) :
        $toolTip = false;
        $content = null;

        switch ( $button[ 'type' ] ) {
            case 'refresh':
                $content = '<div class="btn-refresh"><i class="icon-refresh"></i><img src="' . Router::url( '/' ) . 'img/loading-btn.gif" style="position: relative; height:  14px; top: -3px;" /></div>';
                $toolTip = 'Actualiser les données';
                break;
            case 'print':
                $content = '<div><i class="icon-print"></i></div>';
                $toolTip = 'Imprimer la page';
                break;
            case 'download':
                $content = '<div><i class="icon-download-alt"></i></div>';
                break;
            case 'edit':
                $content = '<div><i class="icon-pencil"></i></div>';
                break;
            case 'share':
                $content = '<div><i class="icon-share-alt"></i></div>';
                break;
            case 'save':
                $content = '<div><i class="icon-ok"></i></div>';
                break;
        }

        // Don't display the refresh button if Capsule is offline
        if ( $button[ 'type' ] == 'refresh' && $isCapsuleOffline ) continue; 
        ?>
        <a class="btn js-<?php echo $button[ 'type' ]; ?>-btn" href="javascript:<?php echo $button[ 'action' ]; ?>"<?php
        	if ( $toolTip ) {
    	        if ( $index == ( count( $buttons ) - 1 ) ) {
    	            echo ' data-placement="left"';
    	        } else {
    	            echo ' data-placement="bottom"';
    	        }
    	        echo ' data-title="' + $toolTip + '"';
    	    } ?>><?php echo $content; ?></a>
    	<?php
    endforeach;
?>
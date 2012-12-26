<div class="alert alert-block capsule-offline<?php if ( $isCapsuleOffline ) echo ' offline'; ?>">
    <h4>Important !</h4> Le serveur de Capsule est actuellement indisponible. Les données affichées seront actualisées lorsque Capsule sera de nouveau opérationnel. Notez que certaines fonctions peuvent ne pas être disponibles.
</div>

<div class="row-fluid" style="margin-top: 10px;">

    <div class="span12 center" style="text-align: left;">
        <ul class="quick-actions dashboard">
			<?php
				$number = 1;
				foreach ( $modules as $module ) :
					if ( isset( $module[ 'Module' ] ) )
						$module = array_shift( $module );
					
					$allowed = true;
					$displayed = false;
					$classNames = array();

					switch ( $module[ 'alias' ] ):
						case 'registration':
							$allowed = true;
							//if ( !$user[ 'registration' ] ) $allowed = false;
							break;
						case 'admin':
							if ( !$user[ 'admin' ] ) $allowed = false;
							break;
						default:
							$allowed = true;
							break;
					endswitch;

					// Check if user has selected this module or module is displayed by default
					if ( empty( $userModules[ 'Module' ] ) ) {
						if ( $module[ 'default' ] )
							$displayed = true;
					} else {
						$userModule = Set::extract( '/Module[id=' . $module[ 'id' ] . ']', $userModules );
						if ( !empty( $userModule ) )
							$displayed = true;
					}

					if ( $displayed ) {
						$classNames[] = 'enabled';
					} else {
						$classNames[] = 'disabled';
					}

					if ( $module[ 'external' ] )
						$classNames[] = 'external';

					if ( $module[ 'alias' ] == 'capsule' && $isCapsuleOffline ) {
						$classNames[] = 'offline';
						$module[ 'icon' ] = 'capsule-offline.png';
					}

					if ( $allowed ) :
						?>
					    <li data-id="<?php echo $module[ 'id' ]; ?>" data-url="<?php echo $module[ 'url' ]; ?>" class="<?php echo implode( ' ', $classNames ); ?>">
					        <a href="#"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?>>
					            <img src="/img/modules/<?php echo $module['icon']; ?>" />
					            <div class="title"><?php echo $module['name']; ?></div>
					        </a>
					    </li>
						<?php
						$number++;
					endif;
				endforeach;
			?>
        </ul>
    </div>
</div>

</div><!-- End of row-fluid -->
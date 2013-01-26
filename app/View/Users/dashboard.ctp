<div class="row-fluid dashboard">
	<?php
		$number = 1;
		foreach ( $modules as $module ) :
			if ( isset( $module[ 'Module' ] ) )
				$module = array_shift( $module );
			
			$allowed = true;
			$displayed = false;
			$classNames = array( 'panel module' );

			switch ( $module[ 'alias' ] ):
				case 'registration':
					$allowed = false;
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
				<div class="<?php echo implode( ' ', $classNames ); ?>" data-id="<?php echo $module[ 'id' ]; ?>" data-url="<?php echo $module[ 'url' ]; ?>">
					<a href="#"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?>>
						<div class="top primary">
							<img src="/img/modules/<?php echo $module[ 'alias' ]; ?>.png" />
							<!--<i class="batch-big b-database"></i>-->
						</div>
						<div class="bottom">
							<h5 class="title"><?php echo $module['name']; ?></h5>
						</div>
					</a>
				</div>
				<?php
				$number++;
			endif;
		endforeach;
	?>
</div>
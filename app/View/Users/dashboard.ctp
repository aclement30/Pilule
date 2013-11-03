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
				// Add base URL to module URL, if internal URL
				if ( substr( $module[ 'url' ], 0, 5 ) != 'http:' ) $module[ 'url' ] = '/' . substr( $module[ 'url' ], 1 );

				?>
				<div class="<?php echo implode( ' ', $classNames ); ?>" data-id="<?php echo $module[ 'id' ]; ?>" data-url="<?php echo $module[ 'url' ]; ?>" data-target="<?php echo $module[ 'target' ]; ?>">
					<?php
						// If link is external with _blank target, display a normal link
						if ( $module[ 'external' ] && $module[ 'target' ] == '_blank' ) :
							echo '<a href="' . $module[ 'url' ] . '" target="_blank">';
						else:
							echo '<a href="#">';
						endif;
					?>
						<div class="top primary">
							<img src="/img/modules/<?php echo $module[ 'alias' ]; ?>.png" />
							<!--<i class="batch-big b-database"></i>-->
						</div>
						<div class="bottom">
							<h5 class="title"><?php echo $module[ 'name' ]; ?></h5>
						</div>
					</a>
				</div>
				<?php
				$number++;
			endif;
		endforeach;
	?>
</div>
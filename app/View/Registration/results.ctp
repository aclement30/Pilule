<div class="table-panel">
	<table class="table table-striped registration-results">
		<thead>
			<tr>
				<th>Cours</th>
				<th>Titre</th>
				<th>NRC</th>
				<th>Résultat</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$registrationErrors = false;

				foreach ($results as $nrc => $result) :
					if ( $result[ 'registered' ] ) {
						$message = "Inscrit";
					} else {
						$registrationErrors = true;
								
						switch ( $result[ 'error' ] ) {
							case 'CLOS‐L.A. PLEINE':
								$message = "Inscription refusée : liste d'attente complète.";
							break;
							case 'ERREUR LIEN: R EXIGÉ':
								$message = "Inscription refusée : cours régulier exigé.";
							break;
							case 'ERREUR LIEN: C EXIGÉ':
								$message = "Inscription refusée : cours connexe exigé.";
							break;
							case 'ERREUR LIEN: S EXIGÉ':
								$message = "Inscription refusée : stage exigé.";
							break;
							case 'ERREUR LIEN: T EXIGÉ':
								$message = "Inscription refusée : cours temps plein exigé.";
							break;
							case 'GROUPE CLOS':
								$message = "Inscription refusée : groupe complet.";
							break;
							case 'HEURES MAX DÉPASSÉES':
								$message = "Inscription refusée : nombre de crédits maximum atteint.";
							break;
							case 'NOTE TEST/PRÉAL-ERREUR':
								$message = "Inscription refusée : test préalable requis.";
							break;
							case 'OUVERT — L.A. REMPLIE':
								$message = "Inscription refusée : liste d'attente complète.";
							break;
							case 'RÉSA CLOSE':
								$message = "Inscription refusée : places disponibles complètes.";
							break;
							case 'RÉSA OUV.‐L.A. REMPLIE':
								$message = "Inscription refusée : places disponibles complètes.";
							break;
							case 'RESERVE CLOSED‐WL FULL %':
								$message = "Inscription refusée : places disponibles complètes.";
							break;
							case 'RESTRICTION CAMPUS':
								$message = "Inscription refusée : restriction de campus.";
							break;
							case 'RESTRICTION CLASS':
								$message = "Inscription refusée : restriction de niveau.";
							break;
							case 'RESTRICTION CYCLE':
								$message = "Inscription refusée : restriction de cycle.";
							break;
							case 'RESTRICTION FAC.':
								$message = "Inscription refusée : restriction de faculté.";
							break;
							case 'RESTRICTION MJRE':
								$message = "Inscription refusée : restriction de majeure.";
							break;
							case 'RESTRICTION PROG.':
								$message = "Inscription refusée : restriction de programme.";
							break;
							default:
								if ( substr( $result[ 'error' ], 0, 5)=='CLOS-' and strpos( $result[ 'error' ], "LISTE ATTENTE" ) > 1 ) {
									$waitingListNumber = substr( $result[ 'error' ], 5 );
									$waitingListNumber = trim( substr( $waitingListNumber, 0, strpos( $waitingListNumber, " " ) ) );
									$message = "Liste d'attente : " . $waitingListNumber . " étudiants.";
									$result[ 'registered' ] = 'waiting';
								} elseif ( substr( $result[ 'error' ], 0, 5 ) == 'OUV.‐' and strpos( $result[ 'error' ], "LST ATTENTE" ) > 1 ) {
									$waitingListNumber = substr( $result[ 'error' ], 5 );
									$waitingListNumber = trim( substr( $waitingListNumber, 0, strpos( $waitingListNumber, " " ) ) );
									$message = "Liste d'attente : " . $waitingListNumber . " étudiants.";
									$result[ 'registered' ] = 'waiting';
								} elseif ( substr( $result[ 'error' ], 0, 8 ) == 'RÉSA OUV' and strpos( $result[ 'error' ], "EN L.A." ) > 1 ) {
									$waitingListNumber = substr( $result[ 'error' ], 5 );
									$waitingListNumber = trim( substr( $waitingListNumber, 0, strpos( $waitingListNumber, " " ) ) );
									$message = "Liste d'attente : " . $waitingListNumber . " étudiants.";
									$result[ 'registered' ] = 'waiting';
								}  elseif ( substr( $result[ 'error' ], 0, 8 ) == 'RESERVE C' and strpos( $result[ 'error' ], "ON WL" ) > 1 ) {
									$waitingListNumber = substr( $result[ 'error' ], 5 );
									$waitingListNumber = trim( substr( $waitingListNumber, 0, strpos( $waitingListNumber, " " ) ) );
									$message = "Liste d'attente : " . $waitingListNumber . " étudiants.";
									$result[ 'registered' ] = 'waiting';
								} else {
									switch ( substr( $result[ 'error' ], 0, 7 ) ) {
										case 'CONCOM_':
											$courseName = substr( $result[ 'error' ], 7);
											$courseName = strtoupper( str_replace( " ", "-", trim( substr( $courseName, 0, strrpos( $course, " " ) ) ) ) );
											$message = "Inscription refusée : autre cours exigé : " . $courseName . ".";
										break;
										case 'CONFLIT':
											$courseName = str_replace( " ", "-", trim( substr( $result[ 'error' ], strrpos( $result[ 'error' ], " " ) + 1 ) ) );
											$message = "Inscription refusée : conflit d'horaire avec " . $courseName . ".";
										break;
										case 'COP COU':
											$message = "Inscription refusée : déjà inscrit à ce cours.";
										break;
										case 'REPEAT':
											$message = "Inscription refusée : nombre d'inscriptions successives atteint.";
										break;
									}
								}
							break;
						}
					}

					foreach ( $courses as $course ) {
						if ( $course[ 'Class' ][ 'nrc' ] == $nrc ) {
							$course = $course[ 'UniversityCourse' ];
							break;
						}
					}
				?>
				<tr class="<?php if ( $result[ 'registered' ] === 'waiting' ) echo 'waiting'; elseif ( $result[ 'registered' ] ) echo 'success registered'; else echo 'error'; ?>">
					<td class="code"><?php echo $course[ 'code' ]; ?></td>
					<td class="title"><?php echo $this->Text->truncate( $course[ 'title' ], 35 ); ?></td>
					<td class="nrc"><?php echo $nrc; ?></td>
					<td class="message"><?php if ( empty( $message ) ) echo $errorMessage; else echo $message; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php if ( $registrationErrors ) : ?>
	<h4 class="header">Erreurs d'inscription</h4>
	<p>Selon le système de gestion des études, votre inscription a été refusée à un ou plusieurs cours. Il est recommandé de réessayer l'inscription à ces cours ou de contacter votre directeur de programme si les problèmes d'inscription persistent.</p>
<?php endif; ?>
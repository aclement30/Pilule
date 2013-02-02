<?php
class UsersController extends AppController {

	public $uses = array( 'CacheRequest', 'Module', 'User' );

	public function beforeFilter() {
		parent::beforeFilter();

		$this->CapsuleAuth->allow( 'login' );
	}

	public function login () {
		if ( $this->request->is( 'ajax' ) ) {
			// Check if auth credentials are provided
			if ( empty( $this->request->data[ 'idul' ] ) || empty( $this->request->data[ 'password' ] ) ) {
				return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    =>  false,
		                'error'		=>  'Erreur : IDUL ou mot de passe absent.'
	            	) )
	            ) );
			}
			
			// Attempt to authenticate the user
			if ( $this->CapsuleAuth->login( strtolower( $this->request->data[ 'idul' ] ), $this->request->data[ 'password' ] ) ) {
				// Check is user's data already exist in DB
	            $dataFetched = true;
	            $fetchList = array();
	            $dataObjects = array( 'studies-summary', 'studies-details', 'studies-report', 'schedule', 'tuition-fees' );

	            foreach ( $dataObjects as $objectName ) {
	            	$lastRequest = $this->CacheRequest->find( 'first', array(
	            		'conditions' => array(
	            			'CacheRequest.idul' => $this->Session->read( 'User.idul' ),
	            			'CacheRequest.name' => $objectName
	            		)
	            	) );

	                // If no data found, add element to fetch list
	                if ( empty( $lastRequest ) ) {
	                    $fetchList[] = $objectName;
	                    $dataFetched = false;
	                }
	            }

	            return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    		=>  true,
		                'userDataFetched'   =>  $dataFetched,
		                'fetchList'			=>  $fetchList
	            	) )
	            ) );
	        } else {
	        	$authError = '';

	            switch ( $this->CapsuleAuth->authResponse ) {
	                case 'credentials':
	                	$authError = 'Erreur : IDUL ou mot de passe erroné.';
	                break;
	                case 'server-connection':
	                	$authError = 'Erreur : serveur Capsule indisponible.';
	                break;
	                case 'fallback-auth-first-visit':
	                	$authError = 'Erreur : serveur Capsule indisponible (1ère connexion).';
	                break;
	                default:
	                	$authError = 'Erreur interne lors de l\'authentification.';
	                break;
	            }

	            return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    =>  false,
		                'error'		=>  $authError
	            	) )
	            ) );
	        }
		}

		// Define layout
		$this->layout = 'login';
		$this->set( 'title_for_layout', 'Pilule - Gestion des études' );
		$this->set( 'url', '' );
		$this->setAssets( array( '/js/users.js' ), null );
	}

	public function logout() {
		$this->Session->destroy();

	    $this->redirect( $this->CapsuleAuth->logout() );
	}

	public function dashboard () {
		$idul = $this->Session->read( 'User.idul' );
		if ( empty( $idul ) ) {
			$this->redirect( '/connexion' );
			exit();
		}
		
		/*
        // Find user dashboard modules
        $userModules = $this->Module->User->find( 'first', array(
        	'conditions'	=>	array( 'User.idul' => $this->Session->read( 'User.idul' ) ),
        	'contain'		=>	array( 'Module' => array( 'conditions' => array( 'Module.active' => true ) ) ),
        	'fields'		=> 	array( 'User.idul' )
        ) );
        */

        $userModules = array();

		if ( empty( $modules[ 'Module' ] ) ) {
			// Load default modules
			$modules = $this->User->Module->find( 'all', array(
	        	'conditions'	=>	array( 'Module.active' => true ),
	        	'order'			=>	'Module.order'
	        ) );
		}

		/*
        $this->set( 'buttons', array(
        	array(
	            'action'=>  "app.Dashboard.edit();",
	            'type'  =>  'edit',
	            'tip'   =>  'Modifier le tableau de bord'
	        ),
	        array(
	            'action'=>  "app.Dashboard.save();",
	            'type'  =>  'save',
	            'tip'   =>  'Enregistrer le tableau de bord'
	        )
        ) );
        */
        $this->set( 'modules', $modules );
        $this->set( 'userModules', $userModules );

		$this->set( 'title_for_layout', 'Tableau de bord' );
		$this->set( 'sidebar', 'dashboard' );
		$this->setAssets( array( '/js/dashboard.js' ), array( '/css/dashboard.css' ) );
	}

	public function saveDashboard () {
		if ( $this->request->is( 'ajax' ) ) {
			$enabledModules = $this->request->data[ 'enabledModules' ];

	        // Add module to user dashboard
            $modules = array( 'User' => array( 'idul' => $this->Session->read( 'User.idul' ) ), 'Module' => array( 'Module' => array() ) );
            foreach ( $enabledModules as $id ) {
           		$modules[ 'Module' ][ 'Module' ][] = $id;
           	}

            $this->User->set( $modules );

            if ( $this->User->saveAll( $modules ) ) {
                return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    =>  true
	            	) )
	            ) );
            } else {
                return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    =>  false
	            	) )
	            ) );
            }
		}
	}

	function eraseData () {
		// Effacement des données enregistrées en cache
        $this->User->deleteAll( array( 'User.idul' => $this->Session->read( 'User.idul' ) ), true );
		
		$this->logout();
	}
}
<?php
class UsersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->CapsuleAuth->allow( 'login' );
	}

	public function login () {
		if ( $this->request->is( 'ajax' ) ) {
			// Attempt to authenticate the user
			if ( $this->CapsuleAuth->login( strtolower( $this->request->data[ 'idul' ] ), $this->request->data[ 'password' ] ) ) {
				// Check is user's data already exist in DB
	            $dataLoaded = true;
	            $reloadList = array();
	            $dataRequests = array( 'studies-summary', 'studies-details', 'studies-report', 'schedule', 'fees' );

	            foreach ($dataRequests as $requestName) {
	                //$lastRequest = $this->mCache->getLastRequest($requestName);

	                // If no data found, add element to loading list
	                if ( empty( $lastRequest ) ) {
	                    $reloadList[] = $requestName;
	                    $dataLoaded = false;
	                }
	            }

	            return new CakeResponse( array(
	            	'body' => json_encode( array(
	            		'status'    =>  true,
		                'loading'   =>  ( !$dataLoaded ) ? true: false,
		                'reloadList'=>  $reloadList
	            	) )
	            ) );
	        } else {
	        	$authError = '';

	            switch ( $this->CapsuleAuth->authResponse ) {
	                case 'credentials':
	                	$authError = 'Erreur : IDUL ou mot de passe erroné.';
	                break;
	                case 'server-connection':
	                	$authError = 'Erreur : serveur de Capsule indisponible.';
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

		$this->set( 'url', '' );
	}

	public function logout() {
	    $this->redirect( $this->Auth->logout() );
	}
}
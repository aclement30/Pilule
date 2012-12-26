<?php
App::import('Component', 'Auth');
App::import('Vendor', 'HttpFetcher' );
App::import('Vendor', 'Capsule' );
App::import('Vendor', 'domparser' );

class CapsuleAuthComponent extends AuthComponent {
	private $Capsule;
	private $HttpFetcher;
	private $domparser;
	public $authResponse;
	private $controller;

	public function initialize( Controller &$controller ) {
		$this->controller = $controller;

		// Load HTTP Fetcher, DOM Parser and Capsule libraries in Controller
		$this->controller->HttpFetcher = new HttpFetcher;
		$this->controller->domparser = new domparser;
		$this->controller->Capsule = new Capsule( $this->controller->HttpFetcher, $this->controller->domparser );
	}

	public function login( $idul = null, $password ) {
		$this->_setDefaults();
		$isAuthenticated = false;

		$this->identify( $idul, $password );

		switch ( $this->authResponse ) {
            case 'success':
            	// Check if user already exists in DB
		        if ( $this->controller->User->find( 'count', array( 'conditions' =>	array( 'User.idul' => $idul ) ) ) == 0 ) {
		            // Save user in DB
		            $user = array( 'User' => array(
						'idul'	=>	$idul,
						'name'	=>	$this->controller->Capsule->userName
					) );

		            $this->controller->User->create();
		            $this->controller->User->set( $user );
		            $this->controller->User->save( $user );
		        }

                // Authentication successful
                $isAuthenticated = true;
            break;
            case 'server-unavailable':
            case 'server-connection':
                // Second attempt
                $this->identify( $idul, $password );

                if ( $this->authResponse == 'success' ) {
                	$isAuthenticated = true;
                } else {
	                // Capsule seems offline
	                $this->Session->write( 'Capsule.isOffline', true );
	            }
            break;
        }

		if ( $isAuthenticated ) {
			$this->Session->renew();
			$this->Session->write( self::$sessionKey, $idul, $this->controller->Capsule->cookies );
			$this->Session->write( 'User.idul', $idul );
			$this->Session->write( 'User.password', $password) ;
		} else {
			return false;
		}

		return $this->loggedIn();
	}

    public function identify ( $idul, $password, $method = 'capsule' ) {
    	$this->authResponse = $this->controller->Capsule->login( $idul, $password );

    	return true;
    }

    public function testConnection () {
    	$this->controller->Capsule->testConnection();
    }
    
}
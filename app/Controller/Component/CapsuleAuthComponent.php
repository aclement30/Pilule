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

	public function initialize() {
		$this->HttpFetcher = new HttpFetcher;
		$this->domparser = new domparser;
		$this->Capsule = new Capsule( &$this->HttpFetcher, $this->domparser );
	}

	public function login( $idul = null, $password ) {
		$this->_setDefaults();
		$isAuthenticated = false;

		$this->identify( $idul, $password );
		
		switch ( $this->authResponse ) {
            case 'success':
            	// Check if user already exists in DB
		        if ( $this->User->userExists( $idul ) === false ) {
		            // Save user in DB
		            $user = array( 'User' => array(
						'idul'	=>	$idul,
						'name'	=>	$this->Capsule->userName
					) );

		            $this->User->create;
		            $this->User->save( $user );
		        }

                // Authentication successful
                $isAuthenticated = true;
            break;
            case 'server-unavailable':
            case 'server-connection':
                // Second attempt
                $this->authResponse = $this->identify( $idul, $password );

                if ( $this->authResponse == 'success' ) {
                	$isAuthenticated = true;
                } else {
	                // Capsule seems offline
	                $this->isCapsuleOffline = true;
	            }
            break;
        }

		if ( $isAuthenticated ) {
			$this->Session->renew();
			$this->Session->write( self::$sessionKey, $idul, $this->Capsule->cookies );
		} else {
			return false;
		}

		return $this->loggedIn();
	}

    public function identify ( $idul, $password, $method = 'capsule' ) {
    	$this->authResponse = $this->Capsule->login( $idul, $password );

    	return true;
    }
    
}
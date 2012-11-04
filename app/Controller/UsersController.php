<?php

App::uses( 'AppController', 'Controller' );
App::import( 'Lib', 'Fetch' );
App::import( 'Lib', 'Capsule' );

class UsersController extends AppController {

	public function login () {
		if ( $this->request->is( 'post' ) ) {
			if ($this->Auth->login()) {
	            return $this->redirect( $this->Auth->redirect() );
	        } else {
	            $this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
	        }
		}

		// Define layout
		$this->layout = 'login';

		$this->set( 'url', '' );
	}
}
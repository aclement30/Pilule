<?php
App::uses( 'BaseAuthenticate', 'Controller/Component/Auth' );

class CapsuleAuthenticate extends BaseAuthenticate {
    public function authenticate ( CakeRequest $request, CakeResponse $response ) {
        // Do things for openid here.
        error_log('Capsule login');
    }
}
<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {
	var $isMobile;
	public $components = array(
        'Session',
        'CapsuleAuth'		=>	array(
            'logoutRedirect'	=> array( 'controller' => 'users', 'action' => 'login' ),
            'loginAction'		=> array( 
            	'controller'	=> 'users',
                'action' 		=> 'login',
                'plugin'		=> false,
                'admin'			=> false
            )
        )
    );

	var $isCapsuleOffline = false;

	public function beforeFilter() {
		// Check if browser is mobile
		$this->_isMobile();

		// Set mobile browser property
		if ( $this->isMobile ) {
			$this->set( 'mobile_browser', 1 );
		} else {
			$this->set( 'mobile_browser', 0 );
		}

		// Check is Capsule is offline
		//'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false

		$this->CapsuleAuth->allow( 'index' );
	}

	public function beforeRender() {
		$this->set( 'isCapsuleOffline', $this->isCapsuleOffline );
	}

	private function _isMobile() {
		$this->isMobile = false;

		// Detect mobile browsers
		if ( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) and preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower( $_SERVER[ 'HTTP_USER_AGENT' ] ) ) ) {
			$this->isMobile = true;
		}
		
		if ( ( isset( $_SERVER[ 'HTTP_ACCEPT' ] ) and strpos( strtolower( $_SERVER[ 'HTTP_ACCEPT' ] ),'application/vnd.wap.xhtml+xml' ) > 0) or ( ( isset( $_SERVER[ 'HTTP_X_WAP_PROFILE' ] ) or isset( $_SERVER[ 'HTTP_PROFILE' ] ) ) ) ) {
			$this->isMobile = true;
		}    
		 
		if ( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
			$mobile_ua = strtolower( substr( $_SERVER[ 'HTTP_USER_AGENT' ], 0, 4 ) );
			$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda ','xda-');
			 
			if ( in_array( $mobile_ua, $mobile_agents ) ) {
				$this->isMobile = true;
			}
			 
			if ( strpos( strtolower( $_SERVER[ 'HTTP_USER_AGENT' ] ), 'windows' ) > 0 ) {
				$this->isMobile = false;
			}
		}

		// If user selected a display mode, use selected display mode instead
		if ( isset( $_SESSION[ 'display_mode' ] ) ) {
			if ( $_SESSION[ 'display_mode' ] == 'normal' ) {
				$this->isMobile = false;
			} elseif ( $_SESSION[ 'display_mode' ] == 'mobile' ) {
				$this->isMobile = true;
			}
		}

		return $this->isMobile;
	}
}

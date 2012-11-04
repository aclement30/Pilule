<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $isMobile;
	var $components = array( 'Auth' );

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
		$this->set( 'isCapsuleOffline', 0 );
		//'capsule_offline'   =>  ($this->session->userdata('capsule_offline') == 'yes') ? true: false

		$this->Auth->authenticate = array( 'Capsule' );
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

<?php
class ServicesController extends AppController {
	public $layout = 'redirection';
	public $uses = array( 'User' );

	public function connect( $service = null ) {
		if ( empty( $service ) )
			throw new NotFoundException();

		$title_for_layout = null;
		$formUrl = null;
		$fields = null;
		$loadingFrameUrl = null;
		$insideIframe = false;

		$user = array_shift( $this->User->findByIdul( $this->Session->read( 'User.idul' ) ) );

		switch ( $service ) {
			case 'bus':
				$this->redirect( 'http://www.rtcquebec.ca/Tarifs/Programmesdabonnement/LabonneBUSUniversit%C3%A9Laval/tabid/259/Default.aspx' );
				break;
			case 'capsule':
				$title_for_layout = 'Capsule';
				$formUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_ValLogin';
				$loadingFrameUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin';
				$insideIframe = true;
				$fields = array(
					'sid'		=>	$this->Session->read( 'User.idul' ),
					'PIN'		=>	$this->Session->read( 'User.password' )
				);
				break;
			case 'elluminate':
				$title_for_layout = 'Elluminate';
				$formUrl = 'https://classevirtuelle.ulaval.ca/elm_login.event?loginPage=index.html';
				$insideIframe = true;
				$fields = array(
					'username'		=>	$this->Session->read( 'User.idul' ),
					'password'		=>	$this->Session->read( 'User.password' )
				);
				break;
			case 'exchange':
				$title_for_layout = 'Exchange';
				$formUrl = 'https://exchange.ulaval.ca/exchweb/bin/auth/owaauth.dll';
				$loadingFrameUrl = 'https://exchange.ulaval.ca/owa/auth/logon.aspx';
				$insideIframe = true;
				$fields = array(
					'destination'	=>	'https://exchange.ulaval.ca/exchange/',
					'flags'			=>	0,
					'forcedownlevel'=>	0,
					'username'		=>	$this->Session->read( 'User.idul' ),
					'password'		=>	$this->Session->read( 'User.password' ),
					'isUtf8'		=>	1,
					'trusted'		=>	0
				);
				break;
			case 'pixel':
				$title_for_layout = 'Pixel';
				$formUrl = 'https://pixel.fsg.ulaval.ca/index.pl';
				$fields = array(
					'envoi'				=>	'Se connecter',
					'code_utilisateur'	=>	$this->Session->read( 'User.idul' ),
					'password'			=>	$this->Session->read( 'User.password' )
				);
				break;
			case 'portailcours':
				$title_for_layout = 'Portail des cours';
				$formUrl = 'https://www.portaildescours.ulaval.ca/portail/j_security_check';
				$fields = array(
					'j_username'	=>	$this->Session->read( 'User.idul' ),
					'j_password'	=>	$this->Session->read( 'User.password' )
				);
				break;

		}

		$this->set( compact( 'title_for_layout', 'formUrl', 'fields', 'loadingFrameUrl', 'insideIframe' ) );
	}

	public function bus () {
		$this->redirect( 'http://www.rtcquebec.ca/Tarifs/Programmesdabonnement/LabonneBUSUniversit%C3%A9Laval/tabid/259/Default.aspx' );

		return true;
	}
}

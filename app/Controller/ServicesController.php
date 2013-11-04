<?php
class ServicesController extends AppController {
	public $layout = 'redirection';
	public $uses = array( 'User' );

	public function connect( $service = null, $redirectUrl = null ) {
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
			case 'capsule-registration':
				$title_for_layout = 'Capsule';
				$formUrl = array( 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_ValLogin', 'https://capsuleweb.ulaval.ca/pls/etprod7/bwskfreg.P_AltPin' );
				$loadingFrameUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin';
				$insideIframe = true;
				$fields = array(
					// Login form
					array(
						'sid'		=>	$this->Session->read( 'User.idul' ),
						'PIN'		=>	$this->Session->read( 'User.password' )
					),
					// Registration page
					array(
						'term_in'		=>	$this->Session->read( 'Registration.semester' )
					)
				);
				break;
			case 'capsule-address':
				$title_for_layout = 'Capsule';
				$formUrl = array( 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_ValLogin', 'https://capsuleweb.ulaval.ca/pls/etprod7/bwgkogad.P_SelectAtypUpdate' );
				$loadingFrameUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin';
				$insideIframe = true;
				$fields = array(
					// Login form
					array(
						'sid'		=>	$this->Session->read( 'User.idul' ),
						'PIN'		=>	$this->Session->read( 'User.password' )
					),
					// Address page
					array(
					)
				);
				break;
			case 'capsule-fiscal-statement':
				$title_for_layout = 'Capsule';
				$formUrl = array( 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_ValLogin', 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_GenMenu?name=bmenu.P_CanTaxMnu' );
				$loadingFrameUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin';
				$insideIframe = true;
				$fields = array(
					// Login form
					array(
						'sid'		=>	$this->Session->read( 'User.idul' ),
						'PIN'		=>	$this->Session->read( 'User.password' )
					),
					// Fiscal statement page
					array(
					)
				);
				break;
			case 'capsule-pdf-statement':
				$title_for_layout = 'Capsule';
				$formUrl = array( 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_ValLogin', base64_decode( $redirectUrl ) );
				$loadingFrameUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin';
				$insideIframe = true;
				$fields = array(
					// Login form
					array(
						'sid'		=>	$this->Session->read( 'User.idul' ),
						'PIN'		=>	$this->Session->read( 'User.password' )
					),
					// PDF statement page
					array(
					)
				);
				break;
			case 'capsule-admission':
				$title_for_layout = 'Capsule';
				$formUrl = array( 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_ValLogin', 'https://capsuleweb.ulaval.ca/pls/etprod7/bwzkappl.P_Offer' );
				$loadingFrameUrl = 'https://capsuleweb.ulaval.ca/pls/etprod7/twbkwbis.P_WWWLogin';
				$insideIframe = true;
				$fields = array(
					// Login form
					array(
						'sid'		=>	$this->Session->read( 'User.idul' ),
						'PIN'		=>	$this->Session->read( 'User.password' )
					),
					// Admission page
					array(
					)
				);
				break;
			case 'elluminate':
				$title_for_layout = 'Elluminate';
				$formUrl = 'https://classevirtuelle.ulaval.ca/elm_login.event?loginPage=index.html';
				$loadingFrameUrl = Configure::read( 'Pilule.baseUrl' ) . 'blank.html';
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
				$loadingFrameUrl = Configure::read( 'Pilule.baseUrl' ) . 'blank.html';
				$fields = array(
					'envoi'				=>	'Se connecter',
					'code_utilisateur'	=>	$this->Session->read( 'User.idul' ),
					'password'			=>	$this->Session->read( 'User.password' )
				);
				break;
			case 'portailcours':
				$title_for_layout = 'Portail des cours';
				$formUrl = 'https://www.portaildescours.ulaval.ca/portail/j_security_check';
				$loadingFrameUrl = Configure::read( 'Pilule.baseUrl' ) . 'blank.html';
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

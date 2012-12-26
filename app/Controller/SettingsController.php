<?php
class SettingsController extends AppController {

    public $uses = array( 'Param' );

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index () {
        if ( $this->request->is( 'post' ) ) {
            foreach ( $this->request->data[ 'Settings' ] as $paramName => $value ) {
                // Check if param already exists
                $paramData = $this->Param->find( 'first', array(
                    'conditions'    =>  array( 'Param.idul' => $this->Session->read( 'User.idul' ), 'Param.name' => $paramName )
                ) );

                $paramData[ 'Param' ][ 'value' ] = $value;

                $this->Param->create();
                $this->Param->set( $paramData );

                if ( $this->Param->save( $paramData ) ) {
                    $this->Session->setFlash( 'Les préférences ont été enregistrées', 'flash_success' );
                } else {
                    $this->Session->setFlash( 'Une erreur est survenue durant l\'enregistrement des préférences', 'flash_error' );
                }
            }
        }
        //TODO : Réparer la connexion auto
        /*
        $data['autologon'] = 'no';
        if ($data['autologon'] == 'yes') {
            $data['fbuid'] = $this->mUser->getParam('fbuid');
            if ($data['fbuid']) {
                $data['fbname'] = $this->mUser->getParam('fbname');
            }
        }
        */
        
        $expirationDelay = $this->Param->find( 'first', array(
            'conditions'    =>  array( 'Param.idul' => $this->Session->read( 'User.idul' ), 'Param.name' => 'data-expiration-delay' ),
            'fields'        =>  array( 'Param.value' )
        ) );
        if ( is_array( $expirationDelay ) )
            $expirationDelay = array_shift( array_shift( $expirationDelay ) );

        $this->data = array( 'Settings' => array( 'data-expiration-delay' => $expirationDelay ) );

        // Set basic page parameters
        $this->set( 'breadcrumb', array(
            array(
                'url'   =>  '/dashboard',
                'title' =>  'Tableau de bord'
            ),
            array(
                'url'   =>  '/settings',
                'title' =>  'Préférences'
            )
        ) );
        $this->set( 'title_for_layout', 'Préférences' );
        $this->setAssets( array(
            '/js/settings.js'
        ) );
	}
}
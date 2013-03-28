<?php
App::uses( 'CakeEmail', 'Network/Email' );

class FeedbackController extends AppController {

    public $uses = array( );

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function send () {
        if ( $this->request->is( 'post' ) ) {
            // Check security token
            if ( empty( $this->request->data[ 'Feedback' ][ 'token' ] ) || $this->request->data[ 'Feedback' ][ 'token' ] != '!pilule$' ) {
                throw new NotFoundException();
            }

            // Send feedback via email
            $Email = new CakeEmail();
            if ( !empty( $this->request->data[ 'Feedback' ][ 'name' ] ) && !empty( $this->request->data[ 'Feedback' ][ 'email' ] ) ) {
                $Email->from( array( $this->request->data[ 'Feedback' ][ 'email' ] => $this->request->data[ 'Feedback' ][ 'name' ] ) );
            } else {
                $Email->from( 'web@alexandreclement.com' );
            }
            $Email->to( 'web@alexandreclement.com' );
            $Email->subject( 'Pilule - Commentaires' );
            $Email->template( 'feedback' );
            $Email->emailFormat( 'html' );
            $Email->viewVars( array( 'message' => $this->request->data ) );
            if ( $Email->send() ) {
                return new CakeResponse( array(
                    'body' => json_encode( array(
                        'status'    =>  true
                    ) )
                ) );
            } else {
                return new CakeResponse( array(
                    'body' => json_encode( array(
                        'status'    =>  false
                    ) )
                ) );
            }
        } elseif ( $this->request->is( 'ajax' ) ) {
            $this->layout = 'ajax';
            $this->render( 'modals/form' );
        }
	}
}
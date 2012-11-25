<?php

class CacheRequest extends Model {
	public $useTable = 'capsule_requests';

	// Save Capsule data request
	public function saveRequest ( $idul, $dataObject, $md5Hash = '' ) {
		$request = array( 'Request' => array(
			'idul'			=>	$idul,
			'name'			=>	$dataObject,
			'timestamp'		=>	time()
		) );

		if ( !empty( $md5Hash ) )
			$request[ 'Request' ][ 'md5' ] = $md5Hash;

		// Update data request timestamp
		if ( $this->save( $request ) ) {
			return true;
		} else {
			return false;
		}
	}

	// Check if a data request exists
    public function requestExists ( $dataObject, $md5Hash = '') {
    	// Find similar request in DB
		$request = $this->find( 'first', array(
			'conditions'	=>	array( 'Request.idul' => $this->Session->read( 'idul' ), 'Request.name' => $dataObject )
		) );

		if ( !empty( $request ) ) {
			// Check if MD5 hash is the same
			if ( $request[ 'Request' ][ 'md5' ] == $md5Hash ) {
                return true;
            }
        }

        // No request found or the MD5 hash is different from the one provided
        return false;
    }

    // Check if a data request is outdated
    public function isRequestOutdated ( $dataObject, $timestamp ) {
    	// Find similar request in DB
		$request = $this->find( 'first', array(
			'conditions'	=>	array(
				'Request.idul'			=>	$this->Session->read( 'idul' ),
				'Request.name' 			=> 	$dataObject,
				'Request.timestamp >='	=>	$timestamp
			)
		) );

		if ( !empty( $request ) ) {
			return true;
		} else {
			return false;
		}
    }
}
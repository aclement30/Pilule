<?php

class CacheRequest extends AppModel {
	public $useTable = 'capsule_requests';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);
	
	// Save Capsule data request
	public function saveRequest ( $idul, $dataObject, $md5Hash = '' ) {
		// Find similar request in DB
		$request = $this->find( 'first', array(
			'conditions'	=>	array( 'CacheRequest.idul' => CakeSession::read( 'User.idul' ), 'CacheRequest.name' => $dataObject )
		) );

		if ( !isset( $request[ 'CacheRequest' ] ) or empty( $request[ 'CacheRequest' ] ) ) {
			$request = array( 'CacheRequest' => array(
				'idul'			=>	$idul,
				'name'			=>	$dataObject,
				'timestamp'		=>	time()
			) );
		} else {
			$request[ 'CacheRequest' ][ 'timestamp' ] = time();
		}

		if ( !empty( $md5Hash ) )
			$request[ 'CacheRequest' ][ 'md5' ] = $md5Hash;

		$this->create();
		$this->set( $request );

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
			'conditions'	=>	array( 'CacheRequest.idul' => CakeSession::read( 'User.idul' ), 'CacheRequest.name' => $dataObject )
		) );

		if ( !empty( $request[ 'CacheRequest' ] ) ) {
			// If MD5 hash is provided, check data request hash against it
			if ( !empty( $md5Hash ) ) {
				// Check if MD5 hash is the same
				if ( $request[ 'CacheRequest' ][ 'md5' ] == $md5Hash ) {
	                return $request[ 'CacheRequest' ];
	            }
	        } else {
	        	return $request[ 'CacheRequest' ];
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
				'CacheRequest.idul'			=>	CakeSession::read( 'User.idul' ),
				'CacheRequest.name' 		=> 	$dataObject,
				'CacheRequest.timestamp >='	=>	$timestamp
			)
		) );

		if ( !empty( $request ) ) {
			return true;
		} else {
			return false;
		}
    }
}
<?php

class User extends Model {
	public $primaryKey = 'idul';

	public $hasOne = array(
		'Report'	=>	'StudentReport'
	);

	public $hasMany = array(
		'Program'	=>	array(
			'className'		=>	'StudentProgram'
		)
	);

	public function edit ( $data ) {
		$request = array( 'Request' => array(
			'id'			=>	$request[ 'Request' ][ 'id' ],
			'idul'			=>	$this->Session->read( 'idul' ),
			'name'			=>	$dataObject,
			'timestamp'		=>	time()
		) );

		if ( !empty( $md5Hash ) )
			$request[ 'Request' ][ 'md5' ] = $md5Hash;

		// Update data request timestamp
		$this->Request->set( $request );
		if ( $this->Request->save() ) {
			return true;
		} else {
			return false;
		}
	}
}
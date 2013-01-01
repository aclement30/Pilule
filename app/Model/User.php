<?php

class User extends AppModel {
	public $primaryKey = 'idul';

	public $hasOne = array(
		'Report'	=>	array(
			'className'		=>	'StudentReport',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'TuitionAccount'	=>	array(
			'className'		=>	'StudentTuitionAccount',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		)
	);

	public $hasMany = array(
		'CacheRequest'	=>	array(
			'className'		=>	'CacheRequest',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'Param'	=>	array(
			'className'		=>	'Param',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'Program'	=>	array(
			'className'		=>	'StudentProgram',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'Section'	=>	array(
			'className'		=>	'StudentProgramSection',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'SelectedCourse'	=>	array(
			'className'		=>	'StudentSelectedCourse',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'Report'	=>	array(
			'className'		=>	'StudentReport',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		),
		'ScheduleSemester'	=>	array(
			'className'		=>	'StudentScheduleSemester',
			'foreignKey'	=>	'idul',
			'dependent'		=>	true
		)
	);

	public $hasAndBelongsToMany = array(
        'Module' =>
            array(
                'className'              =>	'Module',
                'joinTable'              => 'users_modules_map',
                'foreignKey'             => 'idul',
                'associationForeignKey'  => 'module_id',
                'unique'                 => 'keepExisting'
            )
    );

	public function edit ( $data ) {
		$request = array( 'Request' => array(
			'id'			=>	$request[ 'Request' ][ 'id' ],
			'idul'			=>	$this->Session->read( 'User.idul' ),
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
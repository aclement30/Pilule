<?php

class StudentProgram extends AppModel {
	public $useTable = 'stu_programs';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);

	public $hasMany = array(
		'Section'	=>	array(
			'className'		=>	'StudentProgramSection',
			'foreignKey'	=>	'program_id',
			'dependent'		=>	true
		)
	);

	public function beforeSave () {
		parent::beforeSave();

	    if ( !empty( $this->data[ $this->alias ][ 'concentrations' ] ) && is_array( $this->data[ $this->alias ][ 'concentrations' ] ) )
	    	$this->data[ $this->alias ][ 'concentrations' ] = serialize( $this->data[ $this->alias ][ 'concentrations' ] );

	    return true;
	}
	
	public function afterFind( $results, $primary = false ) {
		parent::afterFind( $results, $primary );

		foreach( $results as $key => $val ) {
			if ( isset( $val[ $this->alias ][ 'concentrations' ] ) ) {    
				$results[ $key ][ $this->alias ][ 'concentrations' ] = unserialize( $val[ $this->alias ][ 'concentrations' ] );
			}
		}

		return $results;
	}
}
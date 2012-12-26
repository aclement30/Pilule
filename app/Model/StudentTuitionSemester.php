<?php

class StudentTuitionSemester extends AppModel {
	public $useTable = 'stu_tuitions_semesters';
	public $displayField = 'semester';
	
	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'StudentTuitionAccount'	=>	array(
			'foreignKey'	=>	'account_id',
			'dependent'		=>	true
		)
	);


	public function afterFind( $results, $primary = false ) {
		parent::afterFind( $results, $primary );

		foreach( $results as $key => $val ) {
			if ( isset( $val[ $this->alias ][ 'fees' ] ) ) {    
				$results[ $key ][ $this->alias ][ 'fees' ] = unserialize( $val[ $this->alias ][ 'fees' ] );
			}
		}

		return $results;
	}
}
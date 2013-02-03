<?php

class CourseClass extends AppModel {
	public $useTable = 'classes';
	//public $order = 'nrc';

	public $belongsTo = array(
		'UniversityCourse'	=>	array(
			'className'		=>	'UniversityCourse',
			'foreignKey'	=>	'course_id'
		)
	);

	public $hasOne = array(
		'Spot'	=>	array(
			'className'		=>	'ClassSpot',
			'foreignKey'	=>	'class_id',
			'dependent'		=>	true
		)
	);

	public function beforeSave () {
		parent::beforeSave();

	    if ( !empty( $this->data[ $this->alias ][ 'timetable' ] ) && is_array( $this->data[ $this->alias ][ 'timetable' ] ) )
	    	$this->data[ $this->alias ][ 'timetable' ] = serialize( $this->data[ $this->alias ][ 'timetable' ] );

	    return true;
	}
	
	public function afterFind( $results, $primary = false ) {
		parent::afterFind( $results, $primary );

		foreach( $results as $key => $val ) {
			if ( isset( $val[ $this->alias ][ 'timetable' ] ) ) {    
				$results[ $key ][ $this->alias ][ 'timetable' ] = unserialize( $val[ $this->alias ][ 'timetable' ] );
			}
		}

		return $results;
	}
}
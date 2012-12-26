<?php

class UniversityCourse extends AppModel {
	public $useTable = 'courses';

	public $hasMany = array(
		'Class'	=>	array(
			'className'		=>	'Class',
			'foreignKey'	=>	'course_id',
			'dependent'		=>	true
		)
	);
}
<?php

class StudentScheduleSemester extends AppModel {
	public $useTable = 'stu_schedule_semesters';
	public $displayField = 'semester';
	
	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
	);

	public $hasMany = array(
		'Course'		=>	array(
			'className'		=>	'StudentScheduleCourse',
			'foreignKey'	=>	'semester_id',
			'dependent'		=>	true
		)
	);
}
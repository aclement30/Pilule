<?php

class StudentScheduleCourse extends AppModel {
	public $useTable = 'stu_schedule_courses';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'StudentScheduleSemester'	=>	array(
			'foreignKey'	=>	'semester_id'
		)
	);

	public $hasMany = array(
		'Class'		=>	array(
			'className'		=>	'StudentScheduleClass',
			'foreignKey'	=>	'course_id',
			'dependent'		=>	true
		)
	);
}
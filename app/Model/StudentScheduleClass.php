<?php

class StudentScheduleClass extends AppModel {
	public $useTable = 'stu_schedule_classes';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'StudentScheduleCourse'	=>	array(
			'foreignKey'	=>	'course_id'
		)
	);
}
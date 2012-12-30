<?php

class StudentSelectedCourse extends AppModel {
	public $useTable = 'stu_selected_courses';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'UniversityCourse'	=>	array(
			'foreignKey'	=>	'course_id'
		)
	);
}
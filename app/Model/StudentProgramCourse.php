<?php

class StudentProgramCourse extends AppModel {
	public $useTable = 'stu_programs_courses';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'Program'	=>	array(
			'className'		=>	'StudentProgram',
			'foreignKey'	=>	'program_id',
			'dependent'		=>	true
		),
		'Section'	=>	array(
			'className'		=>	'StudentProgramSection',
			'foreignKey'	=>	'section_id'
		)
	);
}
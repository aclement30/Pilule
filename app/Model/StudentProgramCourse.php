<?php

class StudentProgramCourse extends Model {
	public $useTable = 'stu_programs_courses';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'Program'	=>	array(
			'foreignKey'	=>	'program_id'
		),
		'Section'	=>	array(
			'foreignKey'	=>	'section_id'
		)
	);
}
<?php

class StudentProgramSection extends Model {
	public $useTable = 'stu_programs_sections';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'Program'	=>	array(
			'foreignKey'	=>	'program_id'
		)
	);

	public $hasMany = array(
		'Course'	=>	array(
			'className'		=>	'StudentProgramCourse'
		)
	);
}
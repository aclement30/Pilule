<?php

class StudentProgram extends Model {
	public $useTable = 'stu_programs';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);

	public $hasMany = array(
		'Section'	=>	array(
			'className'		=>	'StudentProgramSection',
			'foreignKey'	=>	'program_id',
			'dependent'		=>	true
		)
	);
}
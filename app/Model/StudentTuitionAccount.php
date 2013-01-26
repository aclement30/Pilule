<?php

class StudentTuitionAccount extends AppModel {
	public $useTable = 'stu_tuitions_accounts';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		)
	);

	public $hasMany = array(
		'Semester'		=>	array(
			'className'		=>	'StudentTuitionSemester',
			'foreignKey'	=>	'account_id',
			'dependent'		=>	true
		)
	);
}
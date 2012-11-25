<?php

class StudentReport extends Model {
	public $useTable = 'stu_reports';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);

	public $hasMany = array(
		'AdmittedSection'	=>	array(
			'className'		=>	'StudentReportAdmittedSection',
			'foreignKey'	=>	'report_id'
		),
		'Semester'			=>	array(
			'className'		=>	'StudentReportSemester',
			'foreignKey'	=>	'report_id'
		)
	);
}
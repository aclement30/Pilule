<?php

class StudentReport extends AppModel {
	public $useTable = 'stu_reports';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);

	public $hasMany = array(
		'AdmittedSection'	=>	array(
			'className'		=>	'StudentReportAdmittedSection',
			'foreignKey'	=>	'report_id',
			'dependent'		=>	true
		),
		'Semester'			=>	array(
			'className'		=>	'StudentReportSemester',
			'foreignKey'	=>	'report_id',
			'dependent'		=>	true
		)
	);
}
<?php

class StudentReportSemester extends AppModel {
	public $useTable = 'stu_reports_semesters';
	public $displayField = 'semester';
	
	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'Report'
	);

	public $hasMany = array(
		'Course'		=>	array(
			'className'		=>	'StudentReportCourse',
			'foreignKey'	=>	'semester_id',
			'dependent'		=>	true
		)
	);
}
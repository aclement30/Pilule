<?php

class StudentReportSemester extends Model {
	public $useTable = 'stu_reports_semesters';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		)
		'Report'
	);

	public $hasMany = array(
		'Course'		=>	array(
			'className'		=>	'StudentReportCourse',
			'foreignKey'	=>	'semester_id'
		)
	);
}
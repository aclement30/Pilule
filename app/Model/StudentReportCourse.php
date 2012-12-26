<?php

class StudentReportCourse extends AppModel {
	public $useTable = 'stu_reports_courses';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'StudentReportSemester'		=>	array(
			'foreignKey'	=>	'semester_id'
		),
		'StudentReportAdmittedSection'		=>	array(
			'foreignKey'	=>	'section_id'
		),
	);
}
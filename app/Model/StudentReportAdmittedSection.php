<?php

class StudentReportAdmittedSection extends AppModel {
	public $useTable = 'stu_reports_admitted_sections';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'Report'	=>	array(
			'foreignKey'	=>	'report_id'
		)
	);

	public $hasMany = array(
		'Course'		=>	array(
			'className'		=>	'StudentReportCourse',
			'foreignKey'	=>	'section_id',
			'dependent'		=>	true
		)
	);
}
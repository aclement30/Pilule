<?php

class StudentReportAdmittedSection extends AppModel {
	public $useTable = 'stu_reports_admitted_sections';

	public $belongsTo = array(
		'User'		=>	array(
			'foreignKey'	=>	'idul'
		),
		'Report'
	);

	public $hasMany = array(
		'Course'		=>	array(
			'className'		=>	'StudentReportCourse',
			'foreignKey'	=>	'section_id',
			'dependent'		=>	true
		)
	);
}
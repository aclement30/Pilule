<?php

class Program extends AppModel {
	public $hasMany = array(
		'Section'	=>	array(
			'className'		=>	'ProgramSection',
			'foreignKey'	=>	'program_id',
			'dependent'		=>	true
		)
	);
}
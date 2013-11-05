<?php

class RegistrationLog extends AppModel {
	public $useTable = 'registration_logs';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);

	public $hasMany = array(
		'Data'	=>	array(
			'className'		=>	'RegistrationLogData',
			'foreignKey'	=>	'registration_log_id',
			'dependent'		=>	true
		)
	);
}
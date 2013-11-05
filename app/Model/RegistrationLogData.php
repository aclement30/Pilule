<?php

class RegistrationLogData extends AppModel {
	public $useTable = 'registration_logs_data';

	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		),
		'RegistrationLog'	=>	array(
			'foreignKey'	=>	'registration_log_id'
		)
	);
}
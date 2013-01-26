<?php

class Module extends AppModel {
	public $belongsTo = array(
		'User'	=>	array(
			'foreignKey'	=>	'idul'
		)
	);
}
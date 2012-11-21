<?php
class CacheController extends AppController {
	public $name = 'Cache';

	public function reloadData() {
		return new CakeResponse( array(
        	'body' => json_encode( array(
        		
        	) )
        ) );
	}
}

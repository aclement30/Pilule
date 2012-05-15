<?php

class lProxy
{
	/**** Public variables ****/
	
	/* user definable vars */

	var $port			=	80;					// port we are connecting to
	var $agent			=	"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; fr; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6";	// agent we masquerade as
	var	$referer		=	"";					// referer info to pass
	var $cookies		=	array();			// array of cookies to pass
	var $result;
	
	function fetch ($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		## Below two option will enable the HTTPS option.
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$this->result = curl_exec($ch);
		curl_close($ch);
		
		return $this->result;
	}
	
	function submit ($url, $vars) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		
		## Below two option will enable the HTTPS option.
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_POST, true);
		
		$this->result = curl_exec($ch);
		curl_close($ch);
		
		return $this->result;
	}
}

?>

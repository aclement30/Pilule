<?php
class lMobile {
	var $mobile = 0;
	var $mobile_browser = 0;
	
	function lMobile () {
		if (!isset($_SESSION)) session_start();
		
		$this->CI =& get_instance();
		
		if ($this->CI->uri->segment(1) == 'api') {
			return (true);
		}
		
		// Détection des navigateurs mobiles
		if (isset($_SERVER['HTTP_USER_AGENT']) and preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$this->mobile++;
		}
		
		if ((isset($_SERVER['HTTP_ACCEPT']) and strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
			$this->mobile++;
		}    
		 
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
			$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda ','xda-');
			 
			if (in_array($mobile_ua,$mobile_agents)) {
				$this->mobile++;
			}
			 
			//if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
			//	$this->mobile++;
			//}
			 
			if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
				$this->mobile = 0;
			}
		}
		
		if ($this->mobile > 0) {
			$this->mobile = 1;
			$this->mobile_browser = 1;
		} else {
			$this->mobile = 0;
			$this->mobile_browser = 0;
		}

		if (isset($_SESSION['display_mode'])) {
			if ($_SESSION['display_mode']=='normal') {
				$this->mobile = 0;
			} elseif ($_SESSION['display_mode']=='mobile') {
				$this->mobile = 1;
			}
		}
		
		//$this->mobile = 0;
		//$this->mobile_browser = 0;
	}
	
	function isMobile() {
		return ($this->mobile);
	}
	
	function isMobileBrowser() {
		return ($this->mobile_browser);
	}
}
?>
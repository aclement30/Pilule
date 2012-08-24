<?php
function currentDate ($date, $format) {
	$date = str_replace(".", "-", str_replace(" ", "-", str_replace("/", "-", $date)));
	// Détection du format d'entrée de la date
	if (strpos($date, "-")>=1) {
		if (strpos($date, "-")==2) {
			// Date au format JJ-MM-AAAA
			$year = substr($date, 6, 4);
			$month = substr($date, 3, 2);
			$day = substr($date, 0, 2);
		} else {
			// Date au format JJ-MM-AAAA
			$year = substr($date, 0, 4);
			$month = substr($date, 5, 2);
			$day = substr($date, 8, 2);
		}
	} else {
		// Date au format AAAAMMJJ
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
		$day = substr($date, 6, 2);
	}
	
	// Changement du format, s'il y a lieu
	/*
	switch ($format) {
		case 'F j, Y':
			$format = 'j F Y';
		break;
	}
	*/
	
	// Sélection du mois de l'année
	$text_months = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
	
	$month++;$month--;
	
	$text_month = ($text_months[($month-1)]);
	
	// Sélection du jour de la semaine
	$text_day  = ucfirst(date("l", mktime(0, 0, 0, $month, $day, $year)));		
	
	// Formatage de la date au format désiré
	$date2 = "";
	for ($n=0; $n<strlen($format); $n++) {
		$symbol = substr($format, $n, 1);
		switch ($symbol) {
			case "d":
				$symbol = str_replace("d", $day, $symbol);
			break;
			case "F":
				$symbol = str_replace("F", $text_month, $symbol);
			break;
			case "M":
				$month++;$month--;
				switch ($month) {
					case 3:
					case 4:
					case 5:
					case 6:
					case 8:
						$symbol = str_replace("M", $text_month, $symbol);
					break;
					case 2:
						$symbol = str_replace("M", substr($text_month, 0, 4), $symbol).".";
					break;
					case 1:
					case 9:
					case 10:
					case 11:
						$symbol = str_replace("M", substr($text_month, 0, 3), $symbol).".";
					break;
					case 7:
					case 12:
						$symbol = str_replace("M", substr($text_month, 0, 4), $symbol).".";
					break;
				}
			break;
			case "y":
				$symbol = str_replace("y", substr($year, 2, 2), $symbol);
			break;
			case "Y":
				$symbol = str_replace("Y", $year, $symbol);
			break;
			case "m":
				$symbol = str_replace("m", $month, $symbol);
			break;
			case "l":
				$symbol = str_replace("l", $text_day, $symbol);
			break;
			case "j":
				$symbol = str_replace("j", ($day+1)-1, $symbol);
			break;
			default:
				$symbol = $symbol;
			break;
		}
		$date2 .= $symbol;
	}
	
	return ($date2);
}

function relativeDate ($timestamp) {
	/*
	$date = str_replace(".", "-", str_replace(" ", "-", str_replace("/", "-", $date)));	
	// Détection du format d'entrée de la date
	if (strpos($date, "-")>=1) {
		if (strpos($date, "-")==2) {
			// Date au format JJ-MM-AAAA
			$year = substr($date, 6, 4);
			$month = substr($date, 3, 2);
			$day = substr($date, 0, 2);
			$timestamp = mktime(0, 0, 0, $month, $day, $year);
		} else {
			// Date au format JJ-MM-AAAA
			$year = substr($date, 0, 4);
			$month = substr($date, 5, 2);
			$day = substr($date, 8, 2);
			$timestamp = mktime(0, 0, 0, $month, $day, $year);
		}
	} elseif (strlen($date)>8) {
		// Date au format UNIX
		$year = date('Y', $date);
		$month = date('m', $date);
		$date = date('d', $date);
		$timestamp = $date;
	} else {
		// Date au format AAAAMMJJ
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
		$day = substr($date, 6, 2);
		$timestamp = mktime(0, 0, 0, $month, $day, $year);
	}
	*/
	$period = time()-$timestamp;
	/*
	$years = floor($period/3600/24/31/12);
	$months = floor($years-($period/3600/24/31));
	$days = floor($months-($period/3600/24));
	$hours = floor($days-($period/3600));
	$minutes = floor($hours-($period/60));
	
	if ($years>1) {
		return ($years." years ago");
	} elseif (($years==1 and $months>1) or ($years<1 and $months>1)) {
		return ((($years*12)+$months))." months ago";
	} elseif ($years==1 and $months==1) {
		return ("last year");
	} elseif ($years==1 and $months<1) {
		return ($years." year ago");
	} elseif ($years<1 and $months==1 and $days<=2) {
		return ("last month");
	} elseif ($years<1 and $months==1 and $days>27) {
		return ("2 months ago");
	} elseif ($years<1 and $months<1 and $days>6 and $days<8) {
		return ("last week");
	} elseif ($years<1 and $months<1 and $days>7) {
		return (floor($days/7)." weeks ago");
	} elseif ($years<1 and $months<1 and $days<7 and $days>1) {
		return ($days." days ago");
	} elseif ($years<1 and $months<1 and $days<=1 and $hours>1) {
		return ($hours." hours ago");
	} elseif ($hours==1 and $minutes<5) {
		return ("1 hour ago");
	} elseif (($hours==1 and $minutes>5) or ($hours<1 and $minutes>5)) {
		return ($minutes." minutes ago");
	} elseif ($minutes<5 and $minutes>=1) {
		return ("a few minutes ago");
	} else {
		return ("a few seconds ago");
	}
	*/
	
	$second = 1;
	$minute = 60;
	$hour = 60*60;
	$day = 60*60*24;
	$week = 60*60*7*24;
	$month = 60*60*24*30;
	$year = 60*60*24*30*365;
	
	if ($period <= 0) { $output = "now";
	}elseif ($period > $second && $period < $minute) { $output = round($period/$second)." second";
	}elseif ($period >= $minute && $period < $hour) { $output = round($period/$minute)." minute";
	}elseif ($period >= $hour && $period < $day) { $output = round($period/$hour)." hour";
	}elseif ($period >= $day && $period < $week) { $output = round($period/$day)." day";
	}elseif ($period >= $week && $period < $month) { $output = round($period/$week)." week";
	}elseif ($period >= $month && $period < $year) { $output = round($period/$month)." month";
	}elseif ($period >= $year && $period < $year*10) { $output = round($period/$year)." year";
	}else{ $output = " more than a decade ago"; }
	
	if ($output <> "now"){
		$output = (substr($output,0,2)<>"1 ") ? $output."s" : $output;
	}
	
	if ($output!=' more than a decade ago' and $output!='now') {
		return $output." ago";
	} else {
		return $output;
	}
}

function respond ( $data, $callback = '' ) {
	if (isset($data['modal'])) $data['modal'] = str_replace("\r", '', str_replace("\n", '', $data['modal']));
	if (isset($data['content'])) $data['content'] = str_replace("\r", '', str_replace("\n", '', $data['content']));
	if (isset($data['sidebar'])) $data['sidebar'] = str_replace("\r", '', str_replace("\n", '', $data['sidebar']));
	
	$CI = & get_instance();
	switch ($CI->_source) {
		case 'ajax':
			if (isset($data['statusCode'])) {
				$CI->output->set_status_header($data['statusCode']);
				if (isset($data['message'])) $CI->output->set_output( json_encode($data['message']) );
			} else {
				if (!empty($callback)) {
					$CI->output->set_output( $callback."(".json_encode($data).");" );
				} else {
					$CI->output->set_output( json_encode($data) );
				}
			}
			$CI->output->_display();
		break;
		case 'iframe':
			if (!empty($callback)) {
				$CI->output->set_output( "<script language='javascript'>top.".$callback."(".json_encode($data).");</script>" );
			} else {
				$CI->output->set_output( "<script language='javascript'>top.".$data."</script>" );
			}
			$CI->output->_display();
		break;
		default:
			$CI->output->set_output( $data );
			$CI->output->_display();
		break;
	}
	
	exit();
}
?>
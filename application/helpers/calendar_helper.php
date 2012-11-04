<?php
function time_ago ( $time, $now = '', $number = 1) {
    if (empty($now)) $now = time();

    $timespan = explode(',', timespan((int)$time, $now));
    $short_timespan = array();
    for ($n=0; $n<$number; $n++) {
        if (isset($timespan[$n])) $short_timespan[] = $timespan[$n];
    }
    $short_timespan = implode(',', $short_timespan);

    return str_replace("second", "seconde", str_replace("hour", "heure", str_replace("day", "jour", str_replace("week", "semaine", str_replace("month", "mois", str_replace("months", "mois", str_replace("year", "an", strtolower($short_timespan))))))));
}

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


function pluralize ($count, $singular, $plural = false) {
    if (!$plural) $plural = $singular . 's';

    return ($count < 2 ? $singular : $plural) ;
}

function generate_xml_from_array($array, $node_name) {
    $xml = '';

    if (is_array($array) || is_object($array)) {
        foreach ($array as $key=>$value) {
            if (is_numeric($key)) {
                $key = $node_name;
            }

            $xml .= '<' . $key . '>' . "\n" . generate_xml_from_array($value, $node_name) . '</' . $key . '>' . "\n";
        }
    } else {
        $xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
    }

    return $xml;
}

function _unlink ($file) {
    if (rename ($file, dirname($file).'/.'.basename($file))) {
        return (true);
    } else {
        return (false);
    }
}

/**
 * Generatting CSV formatted string from an array.
 * By Sergey Gurevich.
 */
function array2csv ($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"') {
    if (!is_array($array) or !is_array($array[0])) return false;

    $output = '';

    //Header row.
    if ($header_row)
    {
        foreach ($array[0] as $key => $val)
        {
            //Escaping quotes.
            $key = str_replace($qut, "$qut$qut", $key);
            $output .= "$col_sep$qut$key$qut";
        }
        $output = substr($output, 1)."\n";
    }
    //Data rows.
    foreach ($array as $key => $val)
    {
        $tmp = '';
        foreach ($val as $cell_key => $cell_val)
        {
            //Escaping quotes.
            $cell_val = str_replace($qut, "$qut$qut", $cell_val);
            $tmp .= "$col_sep$qut$cell_val$qut";
        }
        $output .= substr($tmp, 1).$row_sep;
    }

    return $output;
}


function getRequestSource () {
    $CI = & get_instance();
    if ($CI->input->post('_source')) {
        $CI->_source = $CI->input->post('_source');
    } elseif ($CI->input->get('_source')) {
        $CI->_source = $CI->input->get('_source');
    } else {
        $params = $CI->uri->uri_to_assoc(4);
        if (isset($params['_source'])) {
            $CI->_source = $params['_source'];
        } else {
            $params = $CI->uri->uri_to_assoc(3);

            if (isset($params['_source'])) {
                $CI->_source = $params['_source'];
            }
        }
    }
}

function detect_encoding ( $string ) {
    $encodings = array("ASCII", "UTF-8", "ISO-8859-1");
    $encoding = mb_detect_encoding($string, $encodings);
    if ($encoding == 'ISO-8859-1') {
        $macos = array("\0x8E", "\0x8F", "\0x9A", "\0xA1", "\0xA5", "\0xA8", "\0xD0", "\0xD1", "\0xD5", "\0xE1");

        // Check if encoding is not a similar one
        foreach ($macos as $char) {
            if (strpos($string, $char) >= 0) {
                $encoding = "MACINTOSH";
                break;
            }
        }
    }

    return ( $encoding );
}

function convertSemester($semester, $small_format = false) {
    if (is_numeric($semester) and strlen($semester) == 6) {
        // Le semestre est au format YYYYMM
        switch (substr($semester, 5, 2)) {
            case '09';
                if ($small_format) {
                    $semester = 'A-' . substr($semester, 2, 2);
                } else {
                    $semester = 'Automne '.substr($semester, 0, 4);
                }
                break;
            case '01';
                if ($small_format) {
                    $semester = 'H-' . substr($semester, 2, 2);
                } else {
                    $semester = 'Hiver '.substr($semester, 0, 4);
                }
                break;
            case '05';
                if ($small_format) {
                    $semester = 'E-' . substr($semester, 2, 2);
                } else {
                    $semester = 'Été '.substr($semester, 0, 4);
                }
                break;
        }

        return ($semester);
    } else {
        // Le semestre est au format textuel
        $text_semester = '';
        $semester = explode(' ', $semester);
        $text_semester = $semester[1];
        if ($semester[0] == 'Automne') $text_semester .= '09';
        elseif ($semester[0] == 'Hiver') $text_semester .= '01';
        elseif ($semester[0] == 'Été') $text_semester .= '05';

        return ($text_semester);
    }
}
?>
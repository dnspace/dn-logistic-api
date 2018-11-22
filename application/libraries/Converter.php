<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Converter
{
	function objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			return array_map(null, $d);
		}else {
			return $d;
		}
	}

	
	function arrayToObject($d) {
		if (is_array($d)) {
			return (object) array_map(null, $d);
		}else{
			return $d;
		}
	}
	
}
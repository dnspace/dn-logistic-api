<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Enums
{
	public function enumsUserLevel($intId){
		$arrLevel = array(1 => "System Administrator", 2 => "Supervisor", 3 => "Operator");
		
		if ($intId == 0){
			return $arrLevel;		
		}else{
			return $arrLevel[$intId];		
		}
	}

	public function enumsClientLevel($intId){
		$arrLevel = array(1 => "Administrator", 2 => "Manager", 3 => "Staff");
		
		if ($intId == 0){
			return $arrLevel;		
		}else{
			return $arrLevel[$intId];		
		}
	}

	public function enumsGender($intId){
		$arrGender = array(0 => "Male", 1 => "Female", 2 => "unknown");
		
		// if ((int)$intId != 0 || (int)$intId != 1 || (int)$intId != 2 ){
		// 	return $arrGender;
		// }else{
			return $arrGender[$intId];		
		// }
	}
	
	public function enumsMonthName($intId){
		$arrMonth = array(1 => "Januari", 
							2 => "Februari", 
							3 => "Maret", 
							4 => "April", 
							5 => "Mei", 
							6 => "Juni", 
							7 => "Juli", 
							8 => "Agustus", 
							9 => "September", 
							10 => "Oktober", 
							11 => "November", 
							12 => "Desember");
		if ($intId == 0){
			return $arrMonth;		
		}else{
			return $arrMonth[$intId];		
		}
	}
}


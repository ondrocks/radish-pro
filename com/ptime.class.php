<?php
/*

project:	radish-pro
author:		pieter
date: 		27 mei 2011

*/

class PTime
 {
	function sqlTimeToMicrotime($time)
	{
	 	$year = preg_replace("/^(\d\d\d\d).*$/", "$1", $time);
		$month = preg_replace("/^\d\d\d\d-(\d\d).*$/", "$1", $time);
		$day = preg_replace('/^\d\d\d\d-\d\d-(\d\d).*$/', "$1", $time);

		$year = $year - 1970;
		$micro = $year * 365 * 24 * 60 * 60 * 1000;
		$micro += self::daysOfMonth($month) * 24 * 60 * 60 * 1000;
		$micro += $day * 24 * 60 * 60 * 1000;
		return $micro;
	}
	private function daysOfMonth($month)
	{
		switch ($month) 
		{
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				return 31;
			break;
			case 2:
				return 28;
			default:
				return 30;
			break;
		};
	}
}

?>
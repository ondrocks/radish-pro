<?php
/*

project :	radish-pro
author 	:	pieter
date 	: 	26 mei 2011

*/

class PLanguage
{
	public function are_alike($str1, $str2)
	{
		if (self::are_alike_score($str1, $str2, MATCH_LEFT_TO_RIGHT) > 66) 
		{
			return true;
		}
		return false;
	}
	public function is_same_person($str1, $str2)
	{
	// prepare for double lastnames
	
	// Prepare for what may be between brackets	

		$str1 = preg_replace('/\\(.*\\)/', "", trim($str1));
		$str2 = preg_replace('/\\(.*\\)/', "", trim($str2));
		
		if((self::are_alike_score($str1, $str2, MATCH_RIGHT_TO_LEFT) > 66) &&
			self::is_same_firstname($str1, $str2))
		{
			return true;
		}
		return false;
	}
	private function is_same_firstname($str1, $str2)
	{

	// First character match as firstname?
		if(strcmp(substr(strtolower($str1), 0, 1), substr(strtolower($str2), 0, 1)) == 0)
			return true;
		return false;
	}
	public function are_alike_score( $str1,  $str2, $match_dir)
	{

		$str1 = preg_replace('/\\./', ' ', trim($str1));
		$str2 = preg_replace("/\\./", ' ', trim($str2));
		if (self::_are_alike_lowercase($str1, $str2))	 
		{
			return 100;
		}
		if (self::_are_alike_nospace(strtolower($str1), strtolower($str2))) 
		{
			return 100;
		}
		if(!empty($str1))
			return (self::_number_of_common_words($str1, $str2, $match_dir) / self::number_of_words($str1) * 100);
		else
			return false;
	}
	private function _are_alike_lowercase( $str1,  $str2)
	{
		$str1 = strtolower($str1);
		$str2 = strtolower($str2);
		return $str1 == $str2;
	}
	private function _are_alike_nospace( $str1,  $str2)
	{
		$str1 = str_replace(' ', '', $str1);
		$str2 = str_replace(' ', '', $str2);
		return $str1 == $str2;
	}
	private function _number_of_common_words( $str1,  $str2, $match_dir = MATCH_LEFT_TO_RIGHT)
	{
		$arr1 = explode(' ', $str1);
		$arr2 = explode(' ', $str2);
		$c = 0;
		
		foreach ($arr1 as $str3)
		{
			foreach ($arr2 as $str4)
			{
				if(self::_are_alike_lowercase($str3, $str4))
				{
					if ($c == 0 && $match_dir == MATCH_LEFT_TO_RIGHT) 
					{
						// boost if string equals from the lefthandside
						$c += 5;
					}
					elseif ($c == ((count($arr1) * count($arr2))) &&
						$match_dir == MATCH_RIGHT_TO_LEFT)
					{
						$c += 5;
					}
					$c++;
				}
			}
		}
		return $c / 2;
	}
	public function number_of_words( $str1)
	{
		$c = count(explode(' ', $str1));
		return $c;
	}
}
?>
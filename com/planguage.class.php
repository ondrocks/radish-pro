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
		if (self::are_alike_score($str1, $str2) > 66) 
		{
			return true;
		}
		
		return false;
	}
	public function are_alike_score( $str1,  $str2)
	{
		if (self::_are_alike_lowercase($str1, $str2))	 
		{
			return 100;
		}
		if (self::_are_alike_nospace(strtolower($str1), strtolower($str2))) 
		{
			return 100;
		}
		return self::_number_of_common_words($str1, $str2) / self::number_of_words($str1) * 100;
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
	private function _number_of_common_words( $str1,  $str2)
	{
		$arr1 = explode(' ', $str1);
		$arr2 = explode(' ', $str2);
		$c = 0;
		foreach ($arr1 as $str3)
		{
			foreach ($arr2 as $str4)
			{
				if($str3 == $str4)
				{
					if ($c == 0) 
					{
						// boost is string equals from the lefthandside
						$c++;
					}
					$c++;
				}
			}
		}
		return $c;
	}
	public function number_of_words( $str1)
	{
		$c = count(explode(' ', $str1));
		return $c;
	}
}
?>
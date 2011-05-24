<?php
class PText
{
	function getString($var, $values = array())
	{
		global $globalIniArray;

		if(isset($globalIniArray[$var]))
		{
			$str = $globalIniArray[$var];
			$str = PText::prepareString($str);
			foreach($values as $value)
			{
				$value = PText::prepareValue($value);
				$str = preg_replace("/#/", $value, $str, 1);
			}
			return $str;
		}
		return false;
	}
	private function prepareString($string)
	{
		$ifCommand = substr($string, strpos($string, '/'), 4);
		if($ifCommand == '/IF{')
		{
			$command = substr($string, strpos($string, '/')+4, strpos($string, '}') - strpos($string, '/') -4);
			$res = eval("return $command;");
			if($res == false)
				return preg_replace('/\\/.*\\//', '', $string);
			else
				return preg_replace(array('/IF{.*}/', '/\\//'), array('', ''), $string);
		}
		return $string;
	}
	private function prepareValue($value)
	{
		return preg_replace(array(
				"/\__url__/", 
				"/__url_with_protocol__/"), 
				array(
				substr(PUtil::baseUrl(), 7), 
				PUtil::baseUrl()), 
				$value);
	}
}
?>

<?php
class PUtil
{
	function getVersion()
	{
		global $version;
		
		$debug = DEBUG ? '_DEBUG' : '';
		$memcache = USE_MEMCACHE ? '_MEMC' : '';
		return $version . $debug . $memcache;
	}
	function docRoot()
	{
		return $_SERVER['DOCUMENT_ROOT'] . preg_replace('/^(.*)(\\/.*$)/', '$1', $_SERVER['PHP_SELF']);
	}
	function baseUrl()
	{
		return 	(isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . 
			$_SERVER['SERVER_NAME'] . preg_replace('/^(.*)(\\/.*$)/', '$1', $_SERVER['PHP_SELF']);
	}
	function getAction()
	{
		$action = isset($_GET['action']) ? $_GET['action'] : 'default';
		return $action;
	}
	function getPageTitle()
	{
		if(isset($_GET['page']))
			return $_GET['page'];
		return 'radish-pro';
	}
}
?>

<?php
class PUtil
{
	function docRoot()
	{
		return $_SERVER['DOCUMENT_ROOT'] . DS;
	}
	function baseUrl()
	{
		return 	(isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . 
			$_SERVER['SERVER_NAME'] . preg_replace('/^(.*)(\\/.*$)/', '$1', $_SERVER['PHP_SELF']) . DS;
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

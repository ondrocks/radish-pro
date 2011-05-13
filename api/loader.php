<?php

define('PATH_TO_COM', 'com/');

function getController()
{
	$controller = isset($_GET['controller']) ? $_GET['controller'] : 'index';
	return $controller;
}
function __autoload($name)
{
	$controller = getController();
	if(is_file(PATH_TO_COM . strtolower($name) . '.class.php'))
		require_once(PATH_TO_COM . strtolower($name) . '.class.php');
	else if(is_file('api/' . PATH_TO_COM . strtolower($name) . '.class.php')) 
		require_once('api/' . PATH_TO_COM . strtolower($name) . '.class.php');
	else if(is_file('controller/' . $controller . '/' . PATH_TO_COM . strtolower($name) . '.class.php'))
		require_once('controller/' . $controller . '/' . PATH_TO_COM . strtolower($name) . '.class.php');
}

?>

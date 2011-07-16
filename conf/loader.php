<?php

define('PATH_TO_COM', 'com/');

function __autoload($name)
{
	if(is_file(PATH_TO_COM . strtolower($name) . '.class.php'))
		require_once(PATH_TO_COM . strtolower($name) . '.class.php');
	else if(is_file('controller/' . controllerFront::getController() . DS . PATH_TO_COM . strtolower($name) . '.class.php'))
		require_once('controller/' . controllerFront::getController() . DS . PATH_TO_COM . strtolower($name) . '.class.php');

}

?>

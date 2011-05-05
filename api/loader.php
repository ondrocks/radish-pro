<?php

define('PATH_TO_COM', 'com/');

function __autoload($name)
{
	if(is_file(PATH_TO_COM . strtolower($name) . '.class.php'))
		require_once(PATH_TO_COM . strtolower($name) . '.class.php');
	else if(is_file('../admin/' . PATH_TO_COM . strtolower($name) . '.class.php'))
		require_once('../admin/' . PATH_TO_COM . strtolower($name) . '.class.php');
	else 
		require_once('../api/' . PATH_TO_COM . strtolower($name) . '.class.php');
}

$globalIniArray = parse_ini_file( PUtil::docRoot() . "en-GB.language.ini");


?>

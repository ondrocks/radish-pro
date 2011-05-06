<?php

require_once 'api/credentials.php';
include 'api/globals.php';

function __autoload($name){
	require_once('api/com/'.strtolower($name).'.class.php');
}

$globalIniArray = parse_ini_file( PUtil::docRoot() . "/language/en-GB.language.ini");

new controllerFront();
?>

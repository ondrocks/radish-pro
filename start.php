<?php

require_once 'api/credentials.php';
include_once "login/linkedin.php";
include 'api/globals.php';

function __autoload($name){
	require_once('api/com/'.strtolower($name).'.class.php');
}

$globalIniArray = parse_ini_file( PUtil::docRoot() . "en-GB.language.ini");

if(isset($_GET['controlller']))
	new controllerFront($_GET['controller']);
else
	new controllerFront('index');
?>

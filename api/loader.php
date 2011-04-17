<?php

define('PATH_TO_COM', 'com/');

function __autoload($name)
{
	require_once(PATH_TO_COM . strtolower($name) . '.class.php');
}

?>

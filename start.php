<?php

require_once 'conf/credentials.php';
include 'conf/globals.php';
include 'conf/loader.php';

$globalIniArray = parse_ini_file( PUtil::docRoot() . "/language/en-GB.language.ini");

new controllerFront();
?>

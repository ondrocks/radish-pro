<?php
define('DEBUG', true);
if(DEBUG)
{
	error_reporting(E_ALL);
	ini_set('display_errors', true);
}

define('DS', '/');

define('TABLE_PREFIX', 'crm_');

define('LEVEL_0', 0);	// Security levels Users
define('LEVEL_1', 1);   // Acount Administrator
define('LEVEL_2', 8);	// System Administrator
define('LEVEL_3', 2); 	// Set Administrator 

define('USER_RIGHT_EMAIL', 1);
define('USER_RIGHT_LINKEDIN', 2);
define('USER_RIGHT_TWITTER', 4);
define('USER_RIGHT_FACEBOOK', 8);

define('GOOGLE_API_KEY', 'AIzaSyC82gCzYgIqiUo6_zCd_R_-4EBTn_4HvSw');

function getVarIs($var, $string)
{
	if(isset($_GET[$var]) && $_GET[$var] == $string)
		return true;
	return false;
}

function postVarIs($var, $string)
{
        if(isset($_POST[$var]) && $POST[$var] == $string)
                return true;
        return false;
}

?>

<?php
/*

project:	radish-pro
author:		pieter
date: 		8 jun. 2011

*/

define('PAGES_DIR', 'pages' . DS);

if(isset($_GET['action']) && $_GET['action'] == 'getEmailMsg' && !empty($_GET['id']))
{
 	include PAGES_DIR . 'email.php';
}
else if(!empty($_GET['step']))
{
	switch($_GET['step'])
	{
	default:
			include PAGES_DIR . 'start.php';
			break;
		
	}
}
else
	include PAGES_DIR . 'start.php';
?>


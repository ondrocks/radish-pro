<?php

define('PAGES_DIR', 'pages' . DS);

if(!empty($_GET['page']))
{
	switch($_GET['page'])
	{
		case 'gmail':
			include PAGES_DIR . 'gmail.html';
			break;
		default:
			include PAGES_DIR . $_GET['page'] . ".html";
			break;		
	}
}
else
{
	include "controller/people/" . PAGES_DIR . 'start.php';
}
?>


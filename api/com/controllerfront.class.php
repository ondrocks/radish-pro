<?php
class controllerFront
{
	function __construct($controller='index')
	{
		isset($_GET['controller']) ? $controller = $_GET['controller'] : '';
		include 'templates/header.php';
		include 'templates/' . $controller . '/main.php';
		include 'templates/' . $controller . '/footer.php';
	}
	function getController()
	{
		if(isset($_GET['controller']))
			return $_GET['controller'];
		else
			return 'index';
	}
}
?>

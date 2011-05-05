<?php
class controllerFront
{
	function __construct($controller='index')
	{
		isset($_GET['controller']) ? $controller = $_GET['controller'] : '';
		include 'view/header.php';
		include 'controller/' . $controller . '/main.php';
		include 'controller/' . $controller . '/footer.php';
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

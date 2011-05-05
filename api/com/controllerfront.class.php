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
}
?>

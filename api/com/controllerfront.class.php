<?php
class controllerFront
{
	function __construct($controller='index')
	{
		isset($_GET['controller']) ? $controller = $_GET['controller'] : '';

		$user = new User();
		if($user->isValid())
		{
			include 'view/header.php';
			include 'controller/menu/main.php';
			include 'controller/' . $controller . '/main.php';
			include 'view/footer.php';
		}
		else
		{
			include 'login/linkedin_login.php';
		}
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

<?php
class controllerFront
{
	function __construct($controller='index')
	{
		global $user;
		$controller = controllerFront::getController();

		$user = new User();
		if($user->isValid())
		{
			if($controller != 'search' && $controller != 'api')
			{
				include 'view/header.php';
				include 'controller/menu/main.php';
			}
			include 'controller/' . $controller . '/main.php';

			if($controller != 'search' && $controller != 'api')
				include 'view/footer.php';
		}
		else
		{
			include 'controller/login/main.php';
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

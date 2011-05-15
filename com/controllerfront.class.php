<?php
class controllerFront
{
	function __construct()
	{
		global $user;
		$controller = controllerFront::getController();

		$user = new User();
		if($user->isValid())
		{
			if(! self::isSystemController())
			{
				include 'view/index.php';
			}
			include 'controller/' . $controller . '/main.php';

			if(! self::isSystemController())
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

	function isSystemController()
	{
		if(self::getController() == 'search' || self::getController() == 'api')
			return true;
		return false;
	}
}
?>

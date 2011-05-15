<?php
class controllerFront
{
	function __construct()
	{
		global $user;

		$user = new User();
		if($user->isValid())
		{
			layoutController::dispatch();
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
		if(	self::getController() == 'search' || 
			self::getController() == 'api')
			return true;
		return false;
	}
}
?>

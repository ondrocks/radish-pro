<?php
class layoutController
{
	function dispatch()
	{
		if(! controllerFront::isSystemController())
		{
			include 'view/index.php';
		}

		include 'controller/' . controllerFront::getController() . '/main.php';

		if(! controllerFront::isSystemController())
		{
			include 'view/footer.php';
		}
	}
}
?>

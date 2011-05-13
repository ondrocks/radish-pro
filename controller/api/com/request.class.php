<?php
class Request
{
	var $commands = null;

	function __construct()
	{
		switch($_SERVER['REQUEST_METHOD'])
		{
        		case 'POST':
                		include PUtil::docRoot() . DS . 'controller' . DS . 'api' . DS . 'postcommands.php';
                		break;
        		default :
                		include PUtil::docRoot() . DS . 'controller' . DS . 'api' . DS . 'listcommands.php';
		}
		$this->commands = $qs;
	}
}
?>

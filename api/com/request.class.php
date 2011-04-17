<?php
class Request
{
	var $commands = null;

	function __construct()
	{
		switch($_SERVER['REQUEST_METHOD'])
		{
        		case 'POST':
                		include 'postcommands.php';
                		break;
        		default :
                		include 'listcommands.php';
		}
		$this->commands = $qs;
	}
}
?>

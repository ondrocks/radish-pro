<body>


<?php

define('PAGES_DIR', 'pages' . DS);

$user = new User();

if($user->isValid() && !empty($_GET['step']))
{
	switch($_GET['step'])
	{
		case 'step0':
			include PAGES_DIR . 'step0.html';
			break;
		
	}
}
else if($user->isValid())
	include PAGES_DIR . 'start.php';
else 
	include PAGES_DIR . '403.html';
?>

</body>

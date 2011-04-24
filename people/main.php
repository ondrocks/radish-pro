<body>


<?php

define('PAGES_DIR', 'pages/');

$user = new User();

if($user->isValid() && !empty($_GET['page']))
{
	switch($_GET['page'])
	{
		case 'gmail':
			include PAGES_DIR . 'gmail.html';
			break;
		
	}
}
else if($user->isValid())
{
	echo "Welcome " . $user->getName();
	include PAGES_DIR . 'start.html';
}
else
	include PAGES_DIR . '403.html';
?>

</body>

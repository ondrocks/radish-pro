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
		case 'linkedin':
			include PAGES_DIR . 'linkedin.phtml';
			break;
		
	}
}
else if($user->isValid())
{
	include PAGES_DIR . 'start.php';
}
else
	include PAGES_DIR . '403.html';
?>

</body>

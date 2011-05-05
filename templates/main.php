<body>


<?php

define('PAGES_DIR', 'pages' . DS);

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
	echo "<img src='/radish.gif' class='radish'/><p class='welcome'>Welcome " . $user->getName() . "</p>";
	include PAGES_DIR . 'start.php';
}
else
	include PAGES_DIR . '403.html';
?>

</body>

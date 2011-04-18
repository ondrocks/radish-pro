<body>


<?php

define(PAGES_DIR, 'pages/');

if(!empty($_GET['page']))
{
	switch($_GET['page'])
	{
		case 'gmail':
			include PAGES_DIR . 'gmail.html';
			break;
		
	}
}
else
	include PAGES_DIR . 'start.html';
?>

</body>

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
		case 'linkedin':
			include PAGES_DIR . 'linkedin.phtml';
			break;
		
	}
}
else
	include PAGES_DIR . 'start.html';
?>

</body>

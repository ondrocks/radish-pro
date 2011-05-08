<?php

define('PAGES_DIR', 'pages' . DS);

if(!empty($_GET['fbId']))
{
        $url = 'http://graph.facebook.com/' . $_GET['fbId'];
        $jdata = file_get_contents($url);
        $data = json_decode($jdata);
echo "OK";
        header('Location:' . $data->link);
}
else if(!empty($_GET['step']))
{
	switch($_GET['step'])
	{
		case 'step0':
			include PAGES_DIR . 'step0.html';
			break;
		case 'step1':
			include PAGES_DIR . 'step1.html';
			break;
		case 'step2':
			include PAGES_DIR . 'step2.html';
			break;
		case 'step3':
			include PAGES_DIR . 'step3.html';
			break;
		case 'step4':
			include PAGES_DIR . 'step4.html';
			break;
		
	}
}
else
	include PAGES_DIR . 'start.html';
?>

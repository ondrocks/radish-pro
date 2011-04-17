<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!empty($_GET['fbId']))
{
	$url = 'http://graph.facebook.com/' . $_GET['fbId'];
	$jdata = file_get_contents($url);
        $data = json_decode($jdata);
	header('Location:' . $data->link);
}

?>

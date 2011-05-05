<?php

include '../api/globals.php';
include '../api/loader.php';
include '../api/credentials.php';

	session_start();

if(isset($_POST['q']) && isset($_POST['select']))
{
	if($_POST['select'] == 'linkedin' && !empty($_POST['country']))
	{
		new LinkedInData($_POST['q'], '', $_POST['country']);
	}
	else if($_POST['select'] == 'linkediniscompany')
	{
		new LinkedInData('', $_POST['q'], '');
	}
	else if($_POST['select'] == 'linkedin')
	{
		new LinkedInData($_POST['q'], '', '');
	}
	else if($_POST['select'] == 'google' && isset($_POST['country']))
	{
		new GoogleData($_POST['q'], $_POST['country']);
	}
	else if($_POST['select'] == 'google')
	{
		new GoogleData($_POST['q']);
	}
	else
	{
		new FacebookData($_POST['q']);
	}
}

?>

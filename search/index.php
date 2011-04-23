<?php

include '../api/globals.php';
include '../api/loader.php';
include '../api/credentials.php';

include '../login/linkedin.php';

	session_start();

if(isset($_POST['q']))
{
	if($_POST['select'] == 'linkediniscompany')
	{
		new LinkedInData('', $_POST['q']);
	}
	else if($_POST['select'] == 'linkedin')
	{
		new LinkedInData($_POST['q'], '');
	}
	else if($_POST['select'] == 'local')
	{
		new FacebookData($_POST['q']);
	}
}
?>

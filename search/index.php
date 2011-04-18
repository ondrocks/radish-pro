<?php

include '../api/globals.php';
include '../api/loader.php';
include '../api/credentials.php';

include '../login/linkedin.php';

	session_start();

    $config['base_url']             =   'http://radish-pro.com/admin/login/auth.php';
    $config['callback_url']         =   'http://radish-pro.com/admin/login/demo.php';
    $config['linkedin_access']      =   LINKEDIN_APP_ID;
    $config['linkedin_secret']      =   LINKEDIN_APP_SECRET;

    # Init with consumer information
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);

$xml_response = $linkedin->getConnections();
//var_dump($xml_response);

if(isset($_POST['q']))
{
	new FacebookData($_POST['q']);
	die('OK');
}
?>

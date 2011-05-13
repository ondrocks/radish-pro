<?php
include '../api/globals.php';
include '../api/loader.php';

require_once DIRNAME(__FILE__) . '/../api/credentials.php';
 
    $config['base_url'] = 'http://radish-pro.com/admin/login/auth.php';
    $config['callback_url'] = PUtil::baseUrl() . 'people/';
    $config['linkedin_access'] = LINKEDIN_APP_ACCESS;
    $config['linkedin_secret'] = LINKEDIN_APP_SECRET;
 
//    include_once "linkedin.php";
 
    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
    //$linkedin->debug = true;
 
    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);
 
    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
    //echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
    header("Location: " . $linkedin->generateAuthorizeUrl());
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../api/credentials.php';

    session_start();
 
    $config['base_url']             =   'http://radish-pro.com/admin/login/auth.php';
    $config['callback_url']         =   'http://radish-pro.com/people/get_linkedin_connections.php';
    $config['linkedin_access']      =   LINKEDIN_APP_ACCESS;
    $config['linkedin_secret']      =   LINKEDIN_APP_SECRET;
 
    include_once "../login/linkedin.php";
 
    # Init with consumer information
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
   
   if (isset($_REQUEST['oauth_verifier'])){
        $_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];
 
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);
 
        $_SESSION['oauth_access_token'] = serialize($linkedin->access_token);
        header("Location: " . $config['callback_url']);
        exit;
   }
   else if(isset($_SESSION['oauth_verifier'])){
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
   }
   else
   {
  	header("HTTP/1.0 403 Forbidden");
   }
 
    # Result is a $linkedin->access_token, which can make calls.
 //	$xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url)");

	//$xml_response = $linkedin->getProfile("~:(id,first-name,last-name, headline, picture-url)")
	
 	$xml_response = $linkedin->getConnections();
	echo $xml_response;
/*i    echo '<pre>';
    echo 'My Profile Info';
    echo $xml_response;
    echo '<br />';
    echo '</pre>';

    $search_response = $linkedin->search("?company-name=facebook&count=10");
 
    //echo $search_response;
    $xml = simplexml_load_string($search_response);
 
    echo '<pre>';
    echo 'Look people who worked in facebook';
    print_r($xml);
    echo '</pre>';
*/

?>

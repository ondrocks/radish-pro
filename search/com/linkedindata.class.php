<?php


class LinkedinData
{
        function __construct($person, $company)
        {

        $config['base_url']             =   'http://radish-pro.com/admin/login/auth.php';
        $config['callback_url']         =   'http://radish-pro.com/admin/login/demo.php';
        $config['linkedin_access']      =   LINKEDIN_APP_ACCESS;
        $config['linkedin_secret']      =   LINKEDIN_APP_SECRET;



        # Init with consumer information
	$linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
		if(isset($_SESSION['requestToken']))
                {
                        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
                        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
                        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
                        $xml_response = $linkedin->search($person, $company);
                	echo $xml_response;
		}
        }
}


?>

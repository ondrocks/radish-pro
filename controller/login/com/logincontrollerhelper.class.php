<?php
class loginControllerHelper
{
	function gotoLoginUrl()
	{
 		$linkedin = new LinkedIn(LINKEDIN_APP_ACCESS, LINKEDIN_APP_SECRET, PUtil::baseUrl() . 'people/');
 		$linkedin->getRequestToken();

		$_SESSION['requestToken'] = serialize($linkedin->request_token);
    		header("Location: " . $linkedin->generateAuthorizeUrl());
	}
}
?>

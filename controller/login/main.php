<?php

if(PUtil::getAction() == 'linkedin')
{
	$h = new loginControllerHelper();
	$h->gotoLoginUrl();
}

include 'view/header.php';

?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script type='text/javascript'>
google.load("gdata", "2.x") ;
var contactsService = nul
</script>

<div id="fb-root"></div>

<script>
window.fbAsyncInit = function() {
FB.init({appId: '201316349891020', status: true, cookie: true,
xfbml: true});
FB.Event.subscribe('auth.sessionChange', function(response) {
if (response.session) {
document.location.href = '/login/facebook_login.php';
// A user has logged in, and a new cookie has been saved
} else {
// The user has logged out, and the cookie has been cleared
}
})
};
(function() {
var e = document.createElement('script');
e.type = 'text/javascript';
e.src = document.location.protocol +
'//connect.facebook.net/nl_NL/all.js';
e.async = true;
//document.getElementById('fb-root').appendChild(e);
}());
</script>

login w/ LinkedIn : <a href='/login/linkedin/'><img class='button' src='../logo_linkedin.png'/></a>
<!--<fb:login-button></fb:login-button>-->

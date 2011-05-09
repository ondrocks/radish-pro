<?php
// Awesome Facebook Application
//
// Name: radish-pro
//

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../facebook-php-sdk/src/facebook.php';

// Create our Application instance.
$facebook = new Facebook(array(
  'appId' => '201316349891020',
  'secret' => '7093be77873477f6a32f603e20583c17',
  'cookie' => true,
));

    $app_id = 201316349891020;
    $app_secret = "7093be77873477f6a32f603e20583c17";
    $my_url = "http://www.radish-pro.com/login/facebook_login.php";

    $code = $_REQUEST["code"];

    if(empty($code) && empty($_GET['token'])) {
        $dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
            . $app_id . "&redirect_uri=" . urlencode($my_url) . "&scope=read_friendlists,user_likes,manage_pages,offline_access";

        echo("<script> top.location.href='" . $dialog_url . "'</script>");
    }

if(!isset($_GET['token']))
{
    $token_url = "https://graph.facebook.com/oauth/access_token?client_id="
        . $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret="
        . $app_secret . "&code=" . $code;

    $access_token = file_get_contents($token_url);

	$graph_url = "https://graph.facebook.com/me/accounts?" . $access_token;

//    $graph_url = "https://graph.facebook.com/radishpro/feed?" . $access_token;

    $datas = json_decode(file_get_contents($graph_url));
	//var_dump($datas);
//    echo("Hello " . $access_token . $user->name);

?>
	<h1>Which page's fans would you like to import?</h1>
<?php
	$html = '<table>';
	foreach($datas->data as $data)
	{
		if(isset($data->name))
		{
			$html .= "<tr><td><a href='?id=" . $data->id . "&token=" . $data->access_token . "'>" . $data->name . "</a>(" . ")</td><td>" . $data->category . "</td></tr>";
		}
	}
	echo $html . "</table>";
}
else
{
        $graph_url = "https://graph.facebook.com/" . $_GET['id'] . "?access_token=" . $_GET['token'];
	$datas = json_decode(file_get_contents($graph_url));
	var_dump($datas);
}
?>

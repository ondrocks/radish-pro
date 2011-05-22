<?php

session_start();

class User
{
	public $name;
	private $linkedin;
	private $profileId;
	private $logedin;

	function __construct()
	{
	//	$config['base_url']             =   'http://radish-pro.com/admin/login/auth.php';
		$config['callback_url']         =   PUtil::baseUrl() . '/people/';
		$config['linkedin_access']      =   LINKEDIN_APP_ACCESS;
		$config['linkedin_secret']      =   LINKEDIN_APP_SECRET;

		$this->linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );

		if (isset($_REQUEST['oauth_verifier']))
		{
			$_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];
			$this->linkedin->request_token    =   unserialize($_SESSION['requestToken']);
			$this->linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
			$this->linkedin->getAccessToken($_REQUEST['oauth_verifier']);
			$_SESSION['oauth_access_token'] = serialize($this->linkedin->access_token);
			header("Location: " . $config['callback_url']);
			exit;
		}
   		else if(isset($_SESSION['oauth_verifier'])){
			$this->linkedin->request_token    =   unserialize($_SESSION['requestToken']);
			$this->linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
			$this->linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
		}
		else
		{
			header("HTTP/1.0 403 Forbidden");
		}
		$this->logedin = $this->_isValid();
	}

	function isValid()
	{
		if($this->logedin)	
			return true;
		//header('Location:/login/');
		return false;
	}
	private function _isValid()
	{
		$profile = $this->_getLinkedInProfileId();
		//var_dump($profile);
		if($this->_isValidProfile($profile))
			return true;
		return false;
	}
	private function _getLinkedInProfileId()
	{
		$xml = $this->linkedin->getProfile("~:(id,first-name,last-name,picture-url)");
		$xml = simplexml_load_string($xml); 
		return $xml->id;
	}
	private function setProfileId($profileId)
	{
		$this->profileId = $profileId;
	}
	public function getProfileId()
	{
		return $this->profileId;
	}
	private function _isValidProfile($profile)
	{
		$dtb = new Dtb();
		$query = "select id, login_id, name from " . TABLE_PREFIX . 'users where login_id = :profile';
		$values = array('profile' => $profile);
		$dtb->prepareAndExecute($query, $values);
		$name = $dtb->getAllRows(false);
		if(isset($name[0]->name) && $name[0]->name)
		{
			$this->_setId($name[0]->id);
			$this->setProfileId($name[0]->login_id);
			$this->_setName($name[0]->name);
			return true;
		}
		return false;
	}
	private function _setId($id)
	{
		$this->id = $id;
	}
	public function getId()
	{
		return $this->id;
	}
	private function _setName($name)
	{
		if($name)
		{	
			$this->name = $name;
		}
	}
	function getName()
	{
		return $this->name;
	}
}


?>

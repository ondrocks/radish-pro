<?php

class APICommand
{
	var $command = '';
	var $query = null;
	var $level = LEVEL_2;
	var $lastId = 0;
	var $dtb;

	function __construct($command, $query, $level)
	{
                $this->dtb = new Dtb();
		$this->command = $command;
		$this->query = $query;
		if(is_int($level))
			$this->level = $level;
	}	


	function initLinkedInApp()
	{
		require_once '../api/credentials.php';
		require_once '../login/linkedin.php';

   // 		session_start();
		$config['base_url']             =   'http://radish-pro.com/admin/login/auth.php';
		$config['callback_url']         =   'http://radish-pro.com/people/get_linkedin_connections.php';
		$config['linkedin_access']      =   LINKEDIN_APP_ACCESS;
		$config['linkedin_secret']      =   LINKEDIN_APP_SECRET;

		# Init with consumer information
		$linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
		$linkedin->request_token    =   unserialize($_SESSION['requestToken']);
		$linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
		$linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
		return $linkedin;
	}

	private function saveLinkedInConnection($linkedin, $name, $user, $headline,$place, $country, $pictureUrl, $profileUrl )
	{
		$query = "insert into " . TABLE_PREFIX . "linkedin_connections (linkedin) values (:linkedin)";
		$values = array("linkedin" => $linkedin);
		$this->dtb->prepareAndExecute($query, $values);
		if($this->dtb->dtb->lastInsertId())
		{
			$query = "insert into " . TABLE_PREFIX . "people (name, user, place, country, linkedIn, headline, ip, pictureUrl, profileUrl)
				values(:name, :user, :place, :country, :linkedIn, :headline, :ip, :pictureUrl, :profileUrl)";
			$values = array('name' => $name,
					'user' => $user,
					'place' => $place,
					'country' => $country,
					'linkedIn' => $this->dtb->dtb->lastInsertId(),
					'headline' => $headline,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'pictureUrl' => $pictureUrl,
					'profileUrl' => $profileUrl);
			$this->dtb->prepareAndExecute($query, $values);
		}
	}

	private function saveEmailConnection($name, $email)
	{
		global $user;
		$query = "insert into " . TABLE_PREFIX . "email_connections (email) values(:email)";
		$values = array('email' => $email);
		$this->dtb->prepareAndExecute($query, $values);
		if($this->dtb->dtb->lastInsertId())
		{
			$query = "insert into " . TABLE_PREFIX . "people (name, email, user) values (:name, :email, :user)";
			$values = array('name' => $name, 'email' => $this->dtb->dtb->lastInsertId(), 'user' => (int)$user->getId());
			$this->dtb->prepareAndExecute($query, $values);
		}
	}

	function execute($dtb)
	{
		global $user;
		$this->dtb = $dtb;

		if($this->command == 'post_email_connections')
		{
			$json = json_decode($_POST['data']);
			foreach($json as $email)
			{
				$name = $email->name;
				$email = $email->email;
				$this->saveEmailConnection($name, $email);
			}
		}
		else if($this->command == 'import_companies')
		{
			$dtb = new Dtb();
			$query = "select ";	
		}
		else if($this->command == 'import_connections')
		{
			$linkedin = $this->initLinkedInApp();	
			if($linkedin)
			{
				$data = $linkedin->getConnections();
				$xml = simplexml_load_string($data);
				foreach($xml->person as $person)
				{
					$name = (string)$person->{'first-name'} . ' ' . $person->{'last-name'};
					$linkedinid = (string)$person->id;
					$headline = $person->headline;
					$_user = (int)$user->getId();
					$place = (string)$person->location->name;
					$country = (string)$person->location->country->code;
					$profileUrl = (string)$person->url;
					$pictureUrl = (string)$person->{'picture-url'};
					$this->saveLinkedInConnection($linkedinid, $name, $_user, $headline, $place, $country, $pictureUrl, $profileUrl);
				}
			}
		}
		else if($this->command == 'list_emails')
		{
			$mb = new ImapController();
			$mb->listEmails();
		}
	}

	function generateValues()
	{
		if(isset($_GET['mode']))
			switch($_GET['mode'])
			{
				case 'list_name_profile':
					$this->query->values = array('profile' => $_GET['id']);
					break;
				case 'list_profiles':
				case 'list_users':
					$this->query->values = array('account' => 1);
					break;
				case 'list_keywords':
					if(isset($_GET['id']))
						$this->query->values = array('profile' => $_GET['id']);
					break;
				case 'list_keyword':
					if(isset($_GET['id']))
						$this->query->values = array('keyword' => $_GET['id']);
					break;
				case 'get_set_messages':
					if(isset($_GET['id']))
						$this->query->values = array('profileId' => $_GET['id']);
					break;
			}
	}

	function onBeforeClose($command, $dtb)
	{
		if($command->query->output)
			echo $dtb->getAllRows();
		else
			if($dtb->dtb->lastInsertId() != 0)
				echo json_encode(array('lastInsertId' => $dtb->dtb->lastInsertId()));
			else 
				echo json_encode(array('lastId' => $this->lastId));
	}

	function hasRights()
	{
		if($this->level == LEVEL_0)
			return true;
		return false;
	}
}

?>

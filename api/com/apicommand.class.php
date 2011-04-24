<?php

class APICommand
{
	var $command = '';
	var $query = null;
	var $level = LEVEL_2;
	var $lastId = 0;

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

    		session_start();
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


	function execute()
	{
		if($this->command == 'getLinkedInProfile')
		{
			$linkedin = $this->initLinkedInApp();	
			if(isset($_GET['id']))
			{
				$data = $linkedin->getProfile('id=' . $_GET['id'] . ":(headline)");
				$xml = simplexml_load_string($data);
				if($xml[0]->headline)
					echo $xml[0]->headline;
				else
					echo $_GET['id'];
			}
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

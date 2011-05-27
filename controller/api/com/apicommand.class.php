<?php

class APICommand
{
	var $command = '';
	var $query = null;
	var $level = LEVEL_2;
	var $cacheable = false;
	var $lastId = 0;
	var $dtb;
	var $linkedIn;

	function __construct($command, $query, $level, $cacheable)
	{
		$this->linkedIn = $this->initLinkedInApp();
		$this->dtb = new Dtb();
		$this->command = $command;
		$this->query = $query;
		$this->cacheable = $cacheable;
		if(is_int($level))
			$this->level = $level;
	}	


	function initLinkedInApp()
	{
		//$config['base_url']             =   'http://radish-pro.com/admin/login/auth.php';
		$config['callback_url']         =   PUtil::baseUrl() . '/people/get_linkedin_connections.php';
		$config['linkedin_access']      =   LINKEDIN_APP_ACCESS;
		$config['linkedin_secret']      =   LINKEDIN_APP_SECRET;

		# Init with consumer information
		$linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
		$linkedin->request_token    =   unserialize($_SESSION['requestToken']);
		$linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
		$linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
		return $linkedin;
	}

	private function saveEmailConnection($name, $email)
	{
		global $user;
		$query = "insert into " . TABLE_PREFIX . "email_connections (email) values(:email)";
		$values = array('email' => $email);
		$this->dtb->prepareAndExecute($query, $values);
		if($this->dtb->dtb->lastInsertId())
		{
			$query = "insert into " . TABLE_PREFIX . "people (lastName, email, user) values (:name, :email, :user)";
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
			if(USE_MEMCACHE)
				cache::getCache()->delete('list_people');
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
		else if ($this->command == 'list_companies_to_merge') 
		{
			$dtb = new Dtb();
			$query = "select name, id, ticker from #_companies";
			$dtb->prepareAndExecute($query, null);
			$rows = $dtb->getAllRows(false);
			$fnd = array();
			$largest = '';
			for($c = 0; $c < count($rows) - 1; $c++)
			{
				for($cc = 0; $cc < count($rows) - 1; $cc++)
				{
					if(	isset($rows[$c]->name) && isset($rows[$cc]->name) && 
						strcmp($rows[$c]->name, $rows[$cc]->name) != 0 && 
						PLanguage::are_alike($rows[$c]->name, $rows[$cc]->name))
					{
						{
							$fnd['alt'][] = array(	'name' => $rows[$c]->name, 
													'id' => $rows[$c]->id,
													'ticker' => $rows[$c]->ticker);
						}
					} 
				}
			}
			$largest = '';
			$largest_with_ticker = false;
			$largest_ticker = '';
			$fnd2 = array();
			foreach ($fnd as $item)
			{
				foreach ($item as $alt)
				{
					if(PLanguage::number_of_words($alt['name']) > PLanguage::number_of_words($largest) || 
						(PLanguage::number_of_words($alt['name']) == PLanguage::number_of_words($largest) &&
						strlen($alt['name']) > strlen($largest)) &&
						!($largest_with_ticker && empty($alt['ticker'])))
					{
						$largest = $alt['name'];
						$id_largest = $alt['id'];
						$largest_with_ticker = !empty($alt['ticker'])? true : false;
						$largest_ticker = $alt['ticker'];
					}
				}
				$fnd2[] = array('comp' => $largest, 'item' => $item);
			}
			$fnd3 = array();
			foreach ($fnd2 as $ar)
			{
				$items = array();
				$alts = array();
				foreach ($ar['item'] as $alt)
				{
					if($alt['name'] != $largest && ! in_array($alt['name'], $alts))
					{
						$alts[] = $alt['name'];
						$items[] = array('name' => $alt['name'], 'id' => $alt['id']);
					}
				}
				$fnd3[] = array('comp' => $largest, 'id' => $id_largest, 'ticker' => $largest_ticker, 'item' => $items);
			}
			//var_dump($fnd2);
			echo json_encode($fnd3);
		}
		else if($this->command == 'get_comp_info')
		{
			if(!empty($_GET['comp']))
			{
				$helper = new Kvk();
				$address = $helper->getKvkAddress($_GET['comp']);
				$helper = new UndocLinkedIn();
				$info = $helper->getInformation($_GET['comp']);
				if($info)
				{
					$address['size'] = $info[0]['size'];
					$address['foundName'] = $info[0]['name'];
				}
				else
				{
					$address['size'] = '';
					$address['foundName'] = '';
				}
				echo json_encode($address);
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
		if(isset($_GET['action']))
			switch($_GET['action'])
			{
				case 'lookuppeople':
					$this->query->values = array('company' => $_GET['company']);
					break;
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
		{
			$data = $dtb->getAllRows();
			echo $data;
			if($command->cacheable && USE_MEMCACHE)
				cache::getCache()->set($command->command, $data, false, 12000);
		}
		else
			if($dtb->dtb->lastInsertId() != 0)
				echo json_encode(array('lastInsertId' => $dtb->dtb->lastInsertId()));
			//else 
			//	echo json_encode(array('lastId' => $this->lastId));
			else
				echo json_encode(false);
	}

	function hasRights()
	{
		if($this->level == LEVEL_0)
			return true;
		return false;
	}
}

?>

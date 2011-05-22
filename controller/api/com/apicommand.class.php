<?php

class APICommand
{
	var $command = '';
	var $query = null;
	var $level = LEVEL_2;
	var $cacheable = false;
	var $lastId = 0;
	var $dtb;

	function __construct($command, $query, $level, $cacheable)
	{
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

	private function saveLinkedInConnection($linkedin, $firstName, $lastName,  $user, $headline,$place, $country, $pictureUrl, $profileUrl, $company )
	{
		$query = "insert into " . TABLE_PREFIX . "linkedin_connections (linkedin) values (:linkedin)";
		$values = array("linkedin" => $linkedin);
		$this->dtb->prepareAndExecute($query, $values);
		if($this->dtb->dtb->lastInsertId())
		{
			$query = "insert into " . TABLE_PREFIX . "people (firstName, lastName, user, place, country, linkedIn, headline, ip, pictureUrl, profileUrl)
				values(:firstName, :lastName, :user, :place, :country, :linkedIn, :headline, :ip, :pictureUrl, :profileUrl)";
			$values = array('firstName' => $firstName,
					'lastName' => $lastName,
					'user' => $user,
					'place' => $place,
					'country' => $country,
					'linkedIn' => $this->dtb->dtb->lastInsertId(),
					'headline' => $headline,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'pictureUrl' => $pictureUrl,
					'profileUrl' => $profileUrl);
			$this->dtb->prepareAndExecute($query, $values);

			if($this->dtb->dtb->lastInsertId())
			{
				$peopleId = $this->dtb->dtb->lastInsertId();
				$query = "select count(*) as count, id from #_companies where name = :name";
				$values = array('name' => $company['name']);
				$this->dtb->prepareAndExecute($query, $values);
				$result = $this->dtb->getAllRows(false);
				if($result[0]->count == 0)
				{
					$query = "insert into #_companies(name, size, ticker, industry) 
						values(:name, :size, :ticker, :industry)";
					$values = array(
						'name' => $company['name'],
						'industry' => $company['industry'],
						'size' => $company['size'],
						'ticker' => $company['ticker']);
					$this->dtb->prepareAndExecute($query, $values);
					$companyId = $this->dtb->dtb->lastInsertId();
				}
				else if(isset($result[0]->id))
				{
					$companyId = $result[0]->id;
				}
				else 
					$companyId =0;
				$query = "select count(*) as count from #_positions where company_id = :companyId and people_id = :peopleId";
				$values = array('companyId' => $companyId, 
						'peopleId' => $peopleId);
				$this->dtb->prepareAndExecute($query, $values);
				$result = $this->dtb->getAllRows(false);
				if($result[0]->count == 0)
				{
					$query = "insert into #_positions(company_id, people_id) values(:companyId, :peopleId)";
					$values = array('companyId' => $companyId,
							'peopleId' => $peopleId);
					$this->dtb->prepareAndExecute($query, $values);
				}
			}
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
					$address['size'] = 0;
					$address['foundName'] = '';
				}
				echo json_encode($address);
			}
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
					$firstName = (string)$person->{'first-name'};
					$lastName = (string)$person->{'last-name'};
					$linkedinid = (string)$person->id;
					$headline = $person->headline;
					$_user = (int)$user->getId();
					$place = (string)$person->location->name;;
					$country = (string)$person->location->country->code;
					$address = (string)$person->{'main-address'};
					$profileUrl = (string)$person->{'public-profile-url'};
					$pictureUrl = (string)$person->{'picture-url'};
					$companyId = $person->{'positions'};
					$company = array(
						"name" => $companyId->position[0]->company->name,
						"industry" => $companyId->position[0]->company->industry,
						"size" => preg_replace('/^(.*)(employees)$/', '$1', $companyId->position[0]->company->size),
						"ticker" => $companyId->position[0]->company->ticker);		

					$this->saveLinkedInConnection($linkedinid, $firstName, $lastName, $_user, $headline, $place, $country, $pictureUrl, $profileUrl, $company);
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

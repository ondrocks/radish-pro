<?php
/*

project:	radish-pro
author:		pieter
date: 		27 mei 2011

*/

class APICommandImportConnections extends APICommand
{
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
	
	function execute()
	{
		global $user;
		$dtb = $this->dtb;
			if($this->linkedIn)
			{
				$query = 'select * from #_people_insert_report where user_id = :user';
				$values = array('user' => $user->getId());
				$dtb->prepareAndExecute($query, $values);
				$res = $dtb->getAllRows(false);
				if (!isset($res[0]))
				{
					$data = $this->linkedIn->getConnections();
				}
				else 
				{
					$data = $this->linkedIn->getUpdates();
					die('UPDATE');
				}
				$xml = simplexml_load_string($data);
				foreach($xml->person as $person)
				{
					if((string)$person->id == 'private')
						continue;
					$companyId = $person->{'positions'};
					$this->saveLinkedInConnection(
						(string)$person->id,					
						(string)$person->{'first-name'}, 
						(string)$person->{'last-name'},
						(int)$user->getId(),
						(string)$person->headline, 
						(string)$person->location->name,
						(string)$person->location->country->code, 
						(string)$person->{'picture-url'}, 
						(string)$person->{'public-profile-url'},
						$company = array(
							"name" => $companyId->position[0]->company->name,
							"industry" => $companyId->position[0]->company->industry,
							"size" => preg_replace('/^(.*)(employees)$/', '$1', $companyId->position[0]->company->size),
							"ticker" => $companyId->position[0]->company->ticker));
				}
			}
	}
}

?>
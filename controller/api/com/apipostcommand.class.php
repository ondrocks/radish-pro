<?php
class APIPostCommand extends APICommand
{
	function postVarsByXMLForm($xmlFileName)
	{
		$retar = array();
		$file = simplexml_load_file('forms/' . $xmlFileName);
		foreach ($file->formelements as $el)
		{
			foreach ($el as $_el)
			{
				foreach ($_el->attributes() as $attr => $value)
				{
					$value = (string)$value;
					if((string)$attr == 'for' && array_key_exists($value, $_POST))
						$retar[$value] = $_POST[$value]; 
				}
			}
		}
		return $retar;
	}

	function generateValues()
	{	
		if(isset($_POST['command']))
		{
			switch($_POST['command'])
			{
				case 'post_email_connections':
					if(!empty($_POST['data']))
					{
						$this->query->values = array('email' => $_POST['email']);
					}
					break;
				case 'post_connection':
					if(!empty($_POST['id']))
					{
						$values= $this->postVarsByXMLForm('editPeople.xml');
						$this->query->values = $values;
					}
					break;
				case 'post_keywords':
					if(isset($_POST['keywords']) && isset($_POST['profileId']))
					{
						$query = "select count(a.id) as count, b.set_id as setId 
							from " . TABLE_PREFIX . "sets a, " . TABLE_PREFIX . "profiles b 
							where b.id=:profileId";
						$this->dtb->prepareAndExecute($query, array(
							'profileId' => $_POST['profileId']));
						$res = $this->dtb->getAllRows(false);
						if($res[0]->count == 0)
						{
							$this->query->values = array( 
								'keywords' => $_POST['keywords']);
						}
						else
						{
							$this->lastId = $res[0]->setId;
							$this->query->raw_query = "update " . TABLE_PREFIX . "sets 
										set keywords=:keywords 
										where id=:setId";
							$this->query->values = array(
								"keywords" => $_POST['keywords'], 
								"setId" => $res[0]->setId);
						}
					}
					break;
			
				case 'post_profile':
					if(isset($_POST['name']) && isset($_POST['profileId']))
					{
						$query = 	"select count(name) as count, set_id as setId 
								from " . TABLE_PREFIX . "profiles a, " . TABLE_PREFIX . "sets b 
								where b.id=a.set_id and a.id=" . $_POST['profileId'];
						$this->dtb->prepareAndExecute($query, null);
						$res = $this->dtb->getAllRows(false);
						if($res[0]->count == 0)
						{
							$this->query->values = array(
								'name' => $_POST['name'], 
								'account' => 1, 
								'active' => 1, 
								'set_id' => $_POST['setId']);
						}
						else
						{
							$this->lastId = $_POST['profileId'];
							$this->query->raw_query = 	"update " . TABLE_PREFIX . "profiles 
											set name=:name 
											where set_id=" . $res[0]->setId;
							$this->query->values = array('name' => $_POST['name']);
						}
					}
					break;
				case 'post_set_messages':
					if(isset($_POST['pos_message']) && isset($_POST['neg_message']) && isset($_POST['special_message']) && isset($_POST['profileId']))
					{
						$this->lastId = $_POST['profileId'];
						$query = "select b.id as setId
							from " . TABLE_PREFIX . "profiles a, " . TABLE_PREFIX . "sets b
							where a.set_id = b.id and a.id = :profileId";
						$values = array( "profileId" => $_POST['profileId']);
						$this->dtb->prepareAndExecute($query, $values);
						$ret = $this->dtb->getAllRows(false);
						$this->query->values = array(
							"pos_message" => $_POST['pos_message'],
							"neg_message" => $_POST['neg_message'],
							"special_message" => $_POST['special_message'],
							"setId" => $ret[0]->setId
						);
					}
					break;
			}
		}	
	}

	function onBeforeClose($command, $dtb)
	{
		global $user;
		switch($command->command)
		{
			case 'post_linkedin_connection':
				if(!empty($_POST['name']) && !empty($_POST['headline']) && $dtb->dtb->lastInsertId())
				{
					$query = "insert into " . TABLE_PREFIX . "people 
					(name, user, place, country, linkedIn, headline, ip, pictureUrl, profileUrl, currentCompany)
					values(:name, :user, :place, :country, :linkedIn, :headline, :ip, :pictureUrl, :profileUrl)";
					$dtb->prepareAndExecute($query, array(
						"name" => $_POST['name'],
						"place" => $_POST['place'],
						"country" => $_POST['country'],
						"user" => $user->getId(),
						"ip" => $_SERVER['REMOTE_ADDR'],
						"headline" => $_POST['headline'],
						"profileUrl" => $_POST['profileUrl'],
						"pictureUrl" => $_POST['pictureUrl'],
						"linkedIn" => $dtb->dtb->lastInsertId())
					);
				}
				break;
			case 'post_email_connection':
				if(!empty($_POST['name']) && $dtb->dtb->lastInsertId())
				{
					$query = "insert into " . TABLE_PREFIX . "people 
					(name, user, email, ip)values(:name, :user, :email_connection, :ip)";
					$dtb->prepareAndExecute($query, array(
						'name' => $_POST['name'], 
						'user' => $user->getId(),
						'ip' => $_SERVER['REMOTE_ADDR'],
						'email_connection' => $dtb->dtb->lastInsertId())
					);
				}
				break;
			case 'post_keywords':
				$query = "select count(name) as count 
					from " . TABLE_PREFIX . "profiles 
					where set_id=:setId";
				$setId = $this->lastId ? $this->lastId : $dtb->dtb->lastInsertId();
				$dtb->prepareAndExecute($query, array(
						'setId' => $setId)
				);
				$res = $dtb->getAllRows(false);
				if($res[0]->count == 0)
				{
					$query = "insert into " . TABLE_PREFIX . 'profiles 
						(name, active, account, set_id) 
						values("Unnamed profile", false, 1, :setId)';
					$dtb->prepareAndExecute($query, array(
							'setId' => $dtb->dtb->lastInsertId())
					);
				}
				break;
		}	
		parent::onBeforeClose($command, $dtb);
	}
}
?>

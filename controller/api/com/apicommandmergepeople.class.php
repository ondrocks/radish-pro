<?php
/*

project:	radish-pro
author:		pieter
date: 		28 mei 2011

*/

class APICommandMergePeople extends APICommand
 {
	function execute()
	{
		$query = 'select a.id, a.linkedin, a.firstName, a.lastName, b.email 
				from #_people a left join
				#_email_connections b on a.email = b.id';
		$values = array();
		$this->dtb->prepareAndExecute($query, $values);
		$result = $this->dtb->getAllRows(false);
	
		$arr1 = $result;
		$arr2 = $result;
		
		$retArr = array();
		for($c = 0; $c < count($arr1); $c++)
		{
			$pers1 = $arr1[$c];
			for($cc = $c; $cc < count($arr2); $cc++)
			{
				$pers2 = $arr2[$cc];
				if(PLanguage::is_same_person(
					$pers1->firstName . ' ' . $pers1->lastName, 
					$pers2->firstName . ' ' . $pers2->lastName) &&
					$pers2->id != $pers1->id)
				{
					if($pers1->lastName != 'No __name__')
						$retArr[] = array(
							'pers1' => array(
								'name' => $pers1->firstName . ' ' . $pers1->lastName,
								'id' => $pers1->id,
								'linkedin' => $pers1->linkedin,
								'email' => $pers1->email),
							'pers2' => array(
								'name' => $pers2->firstName . ' ' . $pers2->lastName,
								'id' => $pers2->id,
								'linkedin' => $pers2->linkedin,
								'email' => $pers2->email)
						);
				}
			}
		}
		$retArr2 = array();
		
	// Get array of people with linkedin id		
		
		foreach ($retArr as $item)
		{
			if(!empty($item['pers1']['linkedin']))
				$retArr2[$item['pers1']['id']][] = $item;
			else if (!empty($item['pers2']['linkedin']))
				$retArr2[$item['pers2']['id']][] = $item;
		}
		$had = array();
		foreach ($retArr as $item1)
		{
			foreach ($retArr as $item2)
			{
				if ($item1['pers1']['id'] == $item2['pers1']['id'] ||
					$item1['pers2']['id'] == $item2['pers2']['id'])
				{
					if(isset($retArr2[$item1['pers1']['id']])) 
					{
						$retArr2[$item1['pers1']['id']][] = $item2;
						$had[] = $item2['pers1']['id'];
						$had[] = $item2['pers2']['id'];
					}
					elseif (isset($retArr2[$item1['pers2']['id']]))
					{	
						$retArr2[$item1['pers2']['id']][] = $item2;
						$had[] = $item2['pers1']['id'];
						$had[] = $item2['pers2']['id'];
					}
					else if(isset($retArr2[$item2['pers1']['id']]))
					{
						$retArr2[$item2['pers1']['id']][] = $item2;
						$had[] = $item2['pers1']['id'];
						$had[] = $item2['pers2']['id'];
					}
					else if(isset($retArr2[$item2['pers2']['id']]))
					{
						$retArr2[$item2['pers2']['id']][] = $item2;
						$had[] = $item2['pers2']['id'];
						$had[] = $item2['pers1']['id'];
					}
					else if (! in_array($item1['pers1']['id'], $had))
					{
						$retArr[$item1['pers1']['id']] = $item2;
						$had[] = $item2['pers1']['id'];
						$had[] = $item2['pers2']['id'];
					}
					else if(! in_array($item1['pers2']['id'], $had))
					{
						$retArr2[$item2['pers1']['id']][] = $item2;
						$had[] = $item2['pers1']['id'];
						$had[] = $item2['pers2']['id'];
					}
				}
				else if(! in_array($item1['pers1']['id'], $had)) 
				{
					$retArr2[$item1['pers1']['id']][] = $item1;
					$had[] = $item1['pers1']['id'];
					$had[] = $item1['pers2']['id'];
				}
			}
		}
		$retArr3 = array();
		foreach ($retArr2 as $k => $v)
		{
			$retArr3[] = array('id' => $k, 'items' => $v);
		}
		echo json_encode($retArr3);
		//var_dump($retArr2);
	}
}

?>
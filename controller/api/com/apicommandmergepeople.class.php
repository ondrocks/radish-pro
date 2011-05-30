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
				#_email_connections b on a.email = b.id 
				order by a.linkedin = "" desc, a.lastName asc';
		$values = array();
		$this->dtb->prepareAndExecute($query, $values);
		$result = $this->dtb->getAllRows(false);
	
		$arr1 = $result;
		$arr2 = $result;
		$ll = count($result);
		$retArr = array();
		$had = array();
		
		for($c = 0; $c < $ll; $c++)
		{
			$pers1 = $arr1[$c];
			if(strcmp($pers1->lastName, 'No __name__') == 0)
			{	
				continue; 			
			}			
			
			for($cc = $c; $cc < $ll; $cc++)
			{
				$pers2 = $arr2[$cc];
			
				if(strcmp($pers2->lastName, 'No __name__') == 0)
				{	
					$pers2->lastName = str_replace('.', ' ', 
						preg_replace('/([@].*)/', '', $pers2->email));
					$pers2->firstName = '';
				}
			
				if(	$pers2->id != $pers1->id && 
					!preg_match("/info/i", $pers1->lastName) && 
					PLanguage::is_same_person(
						strtolower($pers1->firstName . ' ' . $pers1->lastName), 
						strtolower($pers2->firstName . ' ' . $pers2->lastName)))
				{	
					if(! in_array($pers2->id, $had))
					$retArr[$pers1->id][] = 
						array(
							'pers2' => array(
								'name' => $pers1->firstName . ' ' . $pers1->lastName,
								'id' => $pers2->id,
								'linkedin' => $pers2->linkedin,
								'email' => $pers2->email)
						);				
					$had[] = $pers2->id;
				}
			}
		}
		$retArr3 = array();
		
		foreach ($retArr as $k => $v)
		{
				$retArr3[] = array('id' => $k, 'name' => $v[0]['pers2']['name'], 'items' => $v);
		}
		echo json_encode($retArr3);
		//var_dump($retArr3);
	}
}

?>
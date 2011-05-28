<?php
/*

project:	radish-pro
author:		pieter
date: 		27 mei 2011

*/

class APICommandMergeCompanies extends APICommand
 {
	function execute($dtb)
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
			echo json_encode($fnd3);		;
	}
}

?>
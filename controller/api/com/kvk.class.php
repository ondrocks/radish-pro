<?php
class Kvk{
	
	public function getKvkAddress($comp)
	{
		$curl = new PCurl();
		$page = $curl->getUrlData('http://www.kvk.nl/zoek/?q=' . urlencode($comp));
		$addr = $this->parseKvkPage($page, $comp);
		return $addr;
	}
	
	public function parseKvkPage($page, $company)
	{
		$start = '<div class="searchpage">';
		$end = '</ul></div>';
		$add = substr($page, strpos($page, $start));
		$add = substr($add, 0, strpos($add, $end));

		$start = '<li class="type1">';
		$end = '<p class="section">';
		$add = substr($add, strpos($add, $start));
		$add = substr($add, 0, strpos($add, $end));

		$start = '<a';
		$end = '</a>';
		$addcomp = $add;
		$addcomp = substr($addcomp, strpos($addcomp, $start));
		$addcomp = substr($addcomp, 0, strpos($addcomp, $end));
		$fnd_comp = preg_replace('/.*>([a-zA-Z-]*)/', '$1', $addcomp);
		
		if(! PLanguage::are_alike($company, $fnd_comp))
		{
			return array('kvk' => false);
		}
		
		$start = '<p class="content">';
		$end = '</p>';
		$add = substr($add, strpos($add, $start) + 18);
		$add = substr($add, 1, strpos($add, $end)-1);
		$add = explode("<br>", $add);
		if(isset($add[1]))
		{
			$retVal['kvk'] = $add[0];
			$retVal['businessAddress'] = substr($add[2], 0, strpos($add[2], ','));
			//$retVal['Huisnummer'] = 
			$retVal['businessPostalcode'] = preg_replace("/(.*)(\d\d\d\d\w\w)(.*)/", "$2", $add[2]);
			$retVal['businessPlace'] = preg_replace("/(.*)(\d\d\d\d\w\w)(.*)/", "$3", $add[2]);
	
			return $retVal;
		}
		return array('kvk' => FALSE);
	}	
}
?>
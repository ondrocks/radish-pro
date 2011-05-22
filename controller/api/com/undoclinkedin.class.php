<?php
class UndocLinkedIn
{
	function getInformation($comp)
	{
		$curl = new PCurl();
		$page = $curl->getUrlData("http://www.linkedin.com/ta/federator?query=" . urlencode($comp) . "&types=company");
		if($page)
		{
			$info = $this->getInfo($page, $comp);
			return $info;
		}
		else 
		{
			return false;
		}
	}
	private function getInfo($page, $comp)
	{
		$retAr = array();
		$page = json_decode($page);
		foreach ($page->company->resultList as $res)
			$retAr[] = array(
						"name" => $res->displayName, 
						"size" => preg_replace('/^([a-zA-Z ]*[;]\s+)(\d*[,-]\d*)(\W{0,}\semployees)$/', '$2', $res->subLine)
						);
		return $retAr;
	}
}
?>
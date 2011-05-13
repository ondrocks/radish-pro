<?php

interface ExternalDataInterface
{
	public function saveData();
	public function getData($for, $page = null, $country=null);
	public function parseData();
}
?>


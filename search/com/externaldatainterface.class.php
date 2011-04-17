<?php

interface ExternalDataInterface
{
	public function saveData();
	public function getData($for);
	public function parseData();
}
?>


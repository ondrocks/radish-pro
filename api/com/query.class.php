<?php

class Query
{
	var $raw_query = '';
	var $values = array();
	var $output = true;

	function __construct($raw_query, $values = null, $output = true)
	{
		$this->raw_query = $raw_query;
		$this->values = $values;
		$this->output = $output;
	}
}

?>

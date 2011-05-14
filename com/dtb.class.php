<?php

class Dtb
{
	var $raw_query;
	var $dtb = null;
	var $query = null;

	function __construct()
	{
		$this->dtb = new PDO(DSN, USER, PASS);
		$this->dtb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set Errorhandling to Exception
	}	

	function exception($excep)
	{
		if(DEBUG)
		{
			$error_message = $this->dtb->errorInfo();
			echo $excep->getMessage() . "\n";
		}
	}

	function prepareAndExecute($raw_query, $values)
	{
		try	
		{
			$this->query = $this->dtb->prepare(str_replace('#_', TABLE_PREFIX, $raw_query));
			$this->query->execute($values);
		}
		catch(PDOException $excep)
		{
			$this->exception($excep);
		}
	}

	function getAllRows($toJson = true)
	{
		try
		{
			if($toJson)
				return json_encode($this->query->fetchAll(PDO::FETCH_CLASS));
			else
				return $this->query->fetchAll(PDO::FETCH_CLASS);
		}
		catch(PDOException $excep)
		{
                        $this->exception($excep);
		}
	}
}

?>

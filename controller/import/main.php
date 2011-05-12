<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
	{
		//move_uploaded_file($_FILES['csvfile']['tmp_name'], 'import/import.csv');
		$hndl = fopen($_FILES['csvfile']['tmp_name'], 'r');
		if($hndl)
		{
			$c = 0;
			$fields = array();
			$retVal = array();
			while(($data = fgetcsv($hndl, 10000, ";")) !== false)
			{
				if($c == 0)
				{
					foreach($data as $key => $value)
					{
						$fields[$value] = '';
					}
				}
				else 
				{
					$d = 0;
					foreach($fields as $key => $field)
					{
						if($key == 'Web Page')
						{
							continue;
						}
						else if($key == 'First Name' && preg_match("/\\s/", $data[$d]))
						{
							$retVal[$c-1]['Web Page'] = getLinkedIn($data[$d]);
							$retVal[$c-1]['First Name'] = $data[$d];
						}
						else if($key == 'Last Name')
						{
							$retVal[$c-1]['Web Page'] = getLinkedIn($data[$d]);
							$retVal[$c-1]['Job Title'] = getHeadline($data[$d]);
							$retVal[$c-1]['Last Name'] = $data[$d];
						}
						else if($key == 'Job Title')
						{
							if(!empty($data[$d]))
								$retVal[$c-1]['Job Title'] = $data[$d];
						}
						else
						{
							$retVal[$c-1][$key] = $data[$d];
						}
						$d++;
					}
				}
				$c++;
				
			}
			fclose($hndl);
		$c = 0;
		foreach($retVal as $test)
			if(!empty($test['Web Page']))
			{
				$c++;
		//		echo " " . $test['Web Page'] . "<br/>";
			
			}
		}
		echo "Found LinkedIn profiles : " . $c;
		$csv = ';';
		foreach($fields as $field => $val)
		{
			if(!empty($field))
				$csv .= $field . ";";
		}
		$csv .= "\n";
		foreach($retVal as $value)
		{
			foreach($fields as $key => $val)
				$csv .= preg_replace("/\n/", "", $value[$key]) . ";";
			$csv .= "\n";

		}
		file_put_contents('/import/import/result_import.csv', $csv);
		echo " <a href='/import/import/result_import.csv'>download</a>";

	}
}

function getLinkedIn($name)
{
// get last name
	//var_dump($name);
	$name = preg_replace("/^(\w*)(\s+)(\w*)$/", "$3", $name);
	//echo $name;
	if(empty($name))
		return;
	$dtb = new Dtb();
	$query = "select profileUrl from #_people where lastName like :name";
	$values = array('name' => $name);
	$dtb->prepareAndExecute($query, $values);
	$res = $dtb->getAllRows(false);
	if(isset($res[0]->profileUrl))
		return $res[0]->profileUrl;
	return '';
}

function getHeadline($name)
{
	$dtb = new Dtb();
	$query = "select headline from #_people where lastName like :name";
	$values = array('name' => $name);
	$dtb->prepareAndExecute($query, $values);
	$res = $dtb->getAllRows(false);
	if(isset($res[0]->headline))
		return $res[0]->headline;
	return '';
}

?>

Are you READY?
<form action='/import/' method='POST' enctype='multipart/form-data'>
<table>
<tr><td>Basenet CSV-file:</td><td><input type='file' name='csvfile'/></td><tr>
<tr><td></td><td><input type='submit' value='Upload file'/></td></tr>
</table>
</form>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
	{
		$c = 0;
		$cc = 0;
		$ccc = 0;
		$fields = array();
		$retVal = array();
		$hndl = fopen($_FILES['csvfile']['tmp_name'], 'r');
		while(($data = fgetcsv($hndl, 10000, ';')) !== false)
		{
			$e = 0;
			if($c == 0)
                        {
                                        foreach($data as $key => $value)
                                        {
                                                $fields[$value] = $e++;
                                        }
                        }
                        else
                        {
                                        $d = 0;
                                        foreach($fields as $key => $field)
					{
						if(empty($data[$fields['Bedrijfsnaam']]))
							continue;
						if($key == 'Bedrijfsnaam')
						{
							$retVal[$c-1]['Kvk'] = '';
							$retVal[$c-1]['Woonplaats (vestigingsadres)'] = '';
							$retVal[$c-1]['Postcode (vestigingsadres)'] = '';
							$retVal[$c-1]['Straatnaam (vestigingsadres)'] = '';
							if(preg_match("/netherlands|nederland/i", $data[$fields['Land (vestigingsadres)']]) || empty($data[$fields['Land (vestigingsadres)']]))
							{
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, 'http://www.kvk.nl/zoek/?q=' . urlencode($data[$d]));
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								$add = curl_exec($ch);
								$addr = parseKvkPage($add, $data[$d]);
								if($addr)
								{
									$cc++;
									$retVal[$c-1]['Kvk'] = $addr['Kvk'];
									$retVal[$c-1]['Straatnaam (vestigingsadres)'] = $addr['Straatnaam'];
									$retVal[$c-1]['Postcode (vestigingsadres)'] = $addr['Postcode'];
									$retVal[$c-1]['Woonplaats (vestigingsadres)'] = $addr['Plaats'];
								}
								else
									$ccc++;
								curl_close($ch);
								$retVal[$c-1][$key] =  $data[$d];
							}
							else
								$retVal[$c-1][$key] = $data[$d];
						}
						else if($key == 'Woonplaats (vestigingsadres)' || $key == 'Postcode (vestigingsadres)' || $key == 'Straatnaam (vestigingsadres)')
							;
						else
							$retVal[$c-1][$key] = $data[$d];
					$d++;
					}				
			}
			$c++;
		}
                fclose($hndl);
             //   $c = 0;
	
                foreach($retVal as $test)
		{
                        if(!empty($test['Web Page']))
                        {
                                $c++;
                //              echo " " . $test['Web Page'] . "<br/>";

                        }
                }
                echo "Found KvKAddresses : " . $cc . " out of " . ($ccc + $cc);
                $csv = ';';
                foreach($fields as $field => $val)
                {
                        if(!empty($field))
                                $csv .= $field . ";";
                }
                $csv .= "\n";
                foreach($retVal as $value)
                {
			$csv .= ";";
                        foreach($fields as $key => $val)
                                $csv .= preg_replace("/\n/", "", $value[$key]) . ";";
                        $csv .= "\n";

                }
                file_put_contents(PUtil::docRoot() . '/controller/import/import/result_import_comps.csv', $csv);
                echo " <a href='/controller/import/import/result_import_comps.csv'>download</a>";

	}
}
function parseKvkPage($page, $company)
{
	$start = '<div class="searchpage">';
	$end = '</ul></div>';
	$add = substr($page, strpos($page, $start));
	$add = substr($add, 0, strpos($add, $end));

	$start = '<li class="type1">';
	$end = '<p class="section">';
	$add = substr($add, strpos($add, $start));
	$add = substr($add, 0, strpos($add, $end));

//	echo "Found " . $company . " as " . $add . "<br/>";

	$start = '<p class="content">';
	$end = '</p>';
	$add = substr($add, strpos($add, $start) + 18);
	$add = substr($add, 0, strpos($add, $end));
	$add = explode("<br>", $add);
	if(isset($add[1]))
	{
		$retVal['Kvk'] = $add[0];
		$retVal['Straatnaam'] = substr($add[2], 0, strpos($add[2], ','));
		//$retVal['Huisnummer'] = 
		$retVal['Postcode'] = preg_replace("/(.*)(\d\d\d\d\w\w)(.*)/", "$2", $add[2]);
		$retVal['Plaats'] = preg_replace("/(.*)(\d\d\d\d\w\w)(.*)/", "$3", $add[2]);
	
		return $retVal;
	}
	return false;
}
?>

<form action='/importcomp/' method='POST' enctype='multipart/form-data'>
<table>
<tr><td>Basenet csv file companies:</td><td><input type='file' name='csvfile'/></td></tr>
<tr><td></td><td><input type='submit' value='upload'/></td></tr>
</table>
</form>


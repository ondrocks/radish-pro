<?php
class GoogleData extends ExternalData implements ExternalDataInterface
{
	var $raw_data;
	function __construct($q, $country=null)
	{
		for($page = 0; $page < 4; $page++)
		{
			$this->getData($q, $page, $country);
			$this->parseData();
		}
		$this->echoData();
	}

	function getData($for, $page=0, $country='nl')
	{
		$page++;
		$url = 	'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=8&q=' . urlencode($for) . '&gl=' . $country . '&start=' . $page . 
			'&key=' . GOOGLE_API_KEY ;
		$this->raw_data = json_decode(file_get_contents($url));
		return true;
	}

	function parseData()
	{
		foreach($this->raw_data->responseData->results as $data)
		{
			$this->data[] = new ExternalDataItem(
				md5($data->url),
				1,			// Instant Search
				5,			// Google
				1,			// Just met
				$data->titleNoFormatting . ' ' . $this->translate(trim($data->content)),
				$data->url,
				$data->url,
				null,
				null
			);
		}
	}
	function translate($string)
	{
		$urlTrans = "https://www.googleapis.com/language/translate/v2?key=". GOOGLE_API_KEY . '&q=' . urlencode($string) . "&target=en";

		if(isset($_SESSION['doTranslate']) && $data = file_get_contents($urlTrans))
		{
			$data = json_decode($data);	
			return $data->data->translations[0]->translatedText;
		}
		else
			return $string;
	}
}

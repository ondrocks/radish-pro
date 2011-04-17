<?php

class FacebookData extends ExternalData implements ExternalDataInterface
{

	var $raw_data;

	public function getData($for)
	{
        	if(!empty($for))
        	{
                	$json_data = file_get_contents(
                        	'https://graph.facebook.com/search?since=yesterday&limit=500&q=' .
                        	urlencode($for) . '&type=post');
                	$data = json_decode($json_data);
                	$this->raw_data = $data;
			return true;
        	}
        	return false;
	}

	function parseData()
	{
	        if(is_array($this->raw_data->data))
        	{
                	foreach($this->raw_data->data as $data)
                	{
				if(isset($data->message))
				{
					$time = explode('T', $data->updated_time);
					$this->data[] = new ExternalDataItem(	
						md5($data->message),
						1, 				// Instant search
						2,				// Facebook
						1,				// Just met
                        			$data->message,
                        			$data->from->id,
						$data->from->name,
                        			null,
						substr($time[1], 0, 8)
					);
				}
               		}
       	 	}
        	return false;
	}
}

?>

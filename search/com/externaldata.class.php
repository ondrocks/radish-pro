<?php
class ExternalData
{
	var $data = array();
	var $dtb = null;

	public function __construct($q)
	{
		$this->dtb = new Dtb();
                if($this->getData($q))
                {
                        $this->parseData();
                        $this->saveData();
                }
        }

	public function saveData()
	{
		if(is_array($this->data))
		{
			foreach($this->data as $data)
			{
				$query = "insert into " . TABLE_PREFIX . "events 
					(md5, profile, type, status, content, client, client_name, date, time)
					values(:md5, :profileId, :type, :status, :content, :client, :client_name, curdate(), :time)";
				$this->dtb->prepareAndExecute($query, array(
					"md5" => $data->md5,
					"profileId" => $data->profileId,
					"type" => $data->type,
					"status" => $data->status,
					"content" => $data->content,
					"client" => $data->client,
					"client_name" => $data->client_name,
					"time" => $data->time)
				);
			}
		}
	}

}

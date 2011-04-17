<?php
class ExternalDataItem
{
        var $md5 = '';
        var $profileId = '';
        var $type = '';
        var $status = '';
        var $content = '';
	var $client = '';
	var $client_name = '';
        var $date = '';
        var $time = '';

	function __construct($md5, $profileId, $type, $status, $content, $client, $name, $date, $time)
	{
		$this->md5 = $md5;
		$this->profileId = $profileId;
		$this->type = $type;
		$this->status = $status;
		$this->content = $content;
		$this->client = $client;
		$this->client_name = $name;
		$this->date = $date;
		$this->time = $time;
	}
}
?>

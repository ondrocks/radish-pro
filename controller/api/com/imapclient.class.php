<?php

class ImapClient
{
	private $mbox = '';
	private $emails = array();

	public function __construct($type = 'gmail', $userName, $passWord)
	{
		$this->mbox = imap_open("{imap.gmail.com:993/imap/ssl/novalidate-cert}", $userName, $passWord);		
	}
	
	public function getUnseenEmails()
	{
		$MC = imap_check($this->mbox);
		$result = imap_fetch_overview($this->mbox, "1:{$MC->Nmsgs}");
		foreach($result as $overview)
		{
			if(! $overview->seen)
			{
				$this->emails[] = $overview;
			}
		}
		return $this->emails;
	}
	public function getEmails()
	{
		$MC = imap_check($this->mbox);
		$numM = min(array(10, $MC->Nmsgs));
		$start = $MC->Nmsgs - $numM;
		$seq = $start . ":" . $MC->Nmsgs;
		$result = imap_fetch_overview($this->mbox, $seq);
		$result = array_reverse($result);
		return $result;
	}
	
	private function getMultipartMsgText($body)
	{
		echo $body;
		die();
	}
	
	public function getEmail($id)
	{
		$text = '';
		$structure = imap_fetchstructure($this->mbox, $id, FT_UID);
		$mime = $this->get_mime_type($structure);
		if($mime['type'] == 'MULTIPART')
		{
			$info = imap_fetchstructure($this->mbox, $id, FT_UID);
			$d = count($info->parts);
			for($c = 0; $c < $d; $c++)
			{
				//if($c == 0)
				//	continue;
				$text .= imap_fetchbody($this->mbox, $id, $c, FT_UID);
			}
		}
		var_dump($structure, $text);
		//return $text;	
		switch ($structure->encoding) 
		{
			default:
			//case '3':
				return imap_base64($text);
			//break;
			//case '4':
			//	return imap_qprint($text);
			//break;	
			default:
				return ($text);
			break;
		}
	}
	public function closeMBox()
	{
		imap_close($this->mbox);
	}
	
	private function get_mime_type(&$structure) 
	{
		$primary_mime_type = array('TEXT', 'MULTIPART','MESSAGE', 'APPLICATION', 'AUDIO','IMAGE', 'VIDEO', 'OTHER');
		if($structure->subtype) 
		{
			return array(	'type' => $primary_mime_type[(int) $structure->type], 
							'subtype' => $structure->subtype);
		}
		return array('type' => 'TEXT', 'subtype' => 'PLAIN');
	}
	
	private function setEmailsFlags($sequence, $seen, $flagged)
	{
		$flags = '';
		switch($flagged)
		{
			case $seen && $flagged:
				$flags = "\\Seen \\Flagged";
				break;
			case $seen:
				$flags = "\\Seen";
				break;
			case $flagged:
				$flags = "\\Flagged";
				break;

		}
		$status = imap_setflag_full($this->mbox, $sequence, $flags);
		return $status;
	}
}

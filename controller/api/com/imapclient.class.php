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
		
	public function getEmail($msgId)
	{
		$structure = imap_fetchstructure($this->mbox, $msgId, FT_UID);
		$parts = self::getParts($structure);
		$textA = array();
		foreach ($parts as $part)
		{
			$raw = imap_fetchbody($this->mbox, $msgId, $part['KEY'], FT_UID);
			$textA[] = Mime::getUTF8Body($raw, $part['CHARSET']);
			$c = $part['KEY'];
			while($next = Mime::nextPart($raw))
			{
				$raw = imap_fetchbody($this->mbox, $msgId, ++$c, FT_UID);
				$textA[] = Mime::getUTF8Body($raw, $part['CHARSET']);
			}
		}

		$body = implode(' ', $textA);
		echo($body);
		die();
		return $body;
	}
	private function getParts($structure)
	{
		$parts = array();
		foreach ($structure->parts as $kk => $part)
		{
			if(isset($part->parts))
			{
				foreach ($part->parts as $key => $pp)
				{
					$params = $pp->parameters;
					$charset = 'utf-8';
					foreach ($params as $params2)
					{
						if($params2->attribute == 'CHARSET')
						{
							$charset = $params2->value;
						}
					}					
					if($pp->subtype == 'HTML')
					{
						$parts[] = array(
							'KEY' => $key,
							'CHARSET' => $charset
						);
					}
				}
			}
			else 
			{
				if($part->subtype == 'HTML')
				{
					$parts[] = $kk;
				}
			}
		}
		return $parts;
	}
	
	public function closeMBox()
	{
		imap_close($this->mbox);
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

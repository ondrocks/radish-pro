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
                $result = imap_fetch_overview($this->mbox, "1:{$MC->Nmsgs}");
		return $result;
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

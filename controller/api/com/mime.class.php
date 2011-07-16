<?php
/*

project:	radish-pro
author:		pieter
date: 		16 jul. 2011

*/

class Mime{
	function getUTF8Body($msg, $charset)
	{
		$body = self::stripHTTPHeader($msg);
		$body = self::getHTMLBody($body);
		return $body;
	}
	public function nextPart($raw)
	{
		if(!empty($raw))
			return true;	
		return false;
	}
	private function getHTMLBody($msg)
	{
		$bodies = array();
		$res = array();
		$lines = explode("\n", $msg);
		$boundary = trim($lines[0]);
		if(!empty($boundary))
		{
			$bodies = explode($boundary, $msg);
		}
		foreach ($bodies as $body)
		{
			if(trim($body) != '')
				$res[] = array(
					'info' => self::parseHeader(trim($body)),
					'body' => self::stripHTTPHeader(trim($body))
				);
		}
		foreach ($res as $body)
		{
			if($body['info']['type'] == 'text/html')
			{
				switch ($body['info']['enc'])
				{
					case 'base64':
							$ret = base64_decode(trim($body['body']));
							break;
					case 'quoted-printable':
							$ret = self::quoted_printable_decode($body['body']);
				}
				return $ret;
			}
		}
		return false;	
	}
	private function parseHeader($msg)
	{
		$res = array();
		$header = '';
		$body = '';
		$lines = explode("\n", $msg);

		$inHeader = true;
		foreach ($lines as $line)
		{
			$line = trim($line);
			if(!$inHeader)
			{
				$body .= $line;
			}
			if (strcmp($line, "") == 0)
			{
				$inHeader = false;
			}
			if($inHeader)
			{
				$header .= $line . "\n";
			}
		}
		$res['type'] = self::getContentType($header);
		$res['enc'] = self::getEncoding($header);
		$res['charset'] = self::getCharset($header);
		return $res;
	}
	private function getContentType($header)
	{
		if(preg_match("/Content-Type:\s+text\/html/", $header))
			return 'text/html';
		return 'text/plain';
	}
	private function getCharset($header)
	{
		if(preg_match("/charset=\\\"|\\\'iso-8859-1\\\"|\\\'/", $header));
			return "iso-8859-1";
	}
	private function getEncoding($header)
	{
		if(preg_match("/Content-Transfer-Encoding:\s+base64/", $header))
			return "base64";
		if(preg_match("/Content-Transfer-Encoding:\s+quoted-printable/", $header))
			return "quoted-printable";
	}
	private function stripHTTPHeader($msg)
	{
		$body = '';
		$lines = explode("\n", $msg);
		$inHeader = true;
		foreach ($lines as $line)
		{
			if(!$inHeader)
			{
				$body .= $line . "\n";
			}
			if(strcmp(trim($line), "") == 0)
			{
				$inHeader = false;
			}
		}
		return $body;
	}
	private function quoted_printable_decode($str)
	{
		$out = '';
		$inQPChar = false;
		$qpchrs = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
		$frstQQ = '';
		$scndQP = '';		
		foreach ($qpchrs as $ch)
		{
			if(!$inQPChar && preg_match('/=/', $ch))
			{
				$inQPChar = true;
			}
			else if($inQPChar && empty($frstQQ) && preg_match("/[A-Fa-f0-9]/", $ch))
			{
				$frstQQ = $ch;
			}
			else if($inQPChar && empty($scndQP) && preg_match("/[A-Fa-f0-9]/", $ch))
			{
				$scndQP = $ch;
				$out .= utf8_encode(chr(hexdec($frstQQ.$scndQP)));
				$frstQQ = '';
				$scndQP = '';
				$inQPChar = FALSE;
			}
			else 
			{
				$inQPChar = false;
				$frstQQ = '';
				$scndQP = '';				
				$out .= $ch;	
			}
		}
		return $out;
	}	
}

?>
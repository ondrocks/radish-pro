<?php

class cache extends Memcache
{
	static private $obj = '';

	function getCache()
	{
		if(self::$obj == null)
		{
			self::$obj = new Memcache();
			self::$obj->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		}		
		return self::$obj;
	}
}

?>

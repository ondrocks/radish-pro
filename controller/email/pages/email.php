<?php
/*

project:	radish-pro
author:		pieter
date: 		8 jun. 2011

*/


?>
<div id='email'></div>
<script type="text/javascript">

var xhrArgs = {
		url: 'api/get_msg/?id=' + getUrlVars()['id'],
		handleAs: 'json',
		load: function(data)
		{
			alert(data);
		},
		error: function(error)
		{
			error_alert(error)
		}
}
showProgress('email', 'Fetching email data ...')
dojo.xhrGet(xhrArgs)

</script>
<?php
/*

project:	radish-pro
author:		pieter
date: 		8 jun. 2011

*/



?>

<div id='emails'></div>
<script type='text/javascript'>
function listEmails()
{
	var xhrArgs = {
			url: 'api/list_emails/',
			handleAs: 'json',
			load: function(data)
			{
				var html = '<table>'
				for(var c = 0; c < data.length; c++)
				{
					html += '<tr><td>' + data[c].from + '</td><td><a href="email/getEmailMsg/?id=' + data[c].uid + '">' + data[c].subject  + "</a>" + 
						'</td><td>' + data[c].date + '</td><td>' + (typeof(data[c].in_reply_to) == 'undefined' ? '' : 'in_reply') + 
						'</td><td>' + data[c].answered + '</td><td>' + data[c].seen + '</td></tr>'
				}
				dojo.byId('emails').innerHTML = html + '</table>'
			},
			error: function(error)
			{
				error_alert(error)
			}
	}
	showProgress('emails', 'Loading emails ...')	
	dojo.xhrGet(xhrArgs)
}
listEmails()
</script>
<?php
/*

project:	radish-pro
author:		pieter
date: 		28 mei 2011

*/



?>
<div id='people'></div>
<script type="text/javascript">
var xhrArgs = {
	url:'api/list_people_to_merge/',
	handleAs: 'json',
	load: function(data)
	{
		var html = '<tr><th>Sugggested name:</th><th>For</th></tr>'
		for(var c = 0; c < data.length; c++)
		{
			html += '<tr><td>' + data[c].name + '</td><td>'
			for(var cc = 0 ; cc < data[c].items.length; cc++)
			{
				if(data[c].items[cc].pers2.email)
					html += data[c].items[cc].pers2.email + '<br/>'
				//if(data[c].items[cc].pers2.email)
				//	html += data[c].items[cc].pers2.email + '<br/>'
					
			}
			html += '</td></tr>'
		}
		dojo.byId('people').innerHTML = '<table>' + html + '</table>'
	},
	error: function(error)
	{
		error_alert(error)
	}
}
showProgress('people', 'Analysing data, this can take a minute or two ...')
dojo.xhrGet(xhrArgs)
</script>
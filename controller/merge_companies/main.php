<?php
/*

project:	radish-pro
author:		pieter
date: 		26 mei 2011

*/



?>
<div id='companies_to_merge'></div>
<script type='text/javascript'>
	var xhrArgs = {
		url: 'api/list_companies_to_merge/',
		handleAs: 'json',
		load: function(data)
		{
			var html = '<tr><th>Suggested name:</th><th>For:</th></tr>';
			for(var c = 0; c < data.length; c++)
			{
				html += '<tr>'
				html += '<td><input type="checkbox" checked/>' + data[c].id + ' ' + data[c].comp + '</td><td>'
				for(var cc = 0; cc < data[c].item.length; cc++)
				{
					html += data[c].item[cc]['id'] + ' ' + data[c].item[cc]['name'] + '<br/>'
				}
				html += '</td></tr>'
			}
			dojo.byId('companies_to_merge').innerHTML = '<table>' + html + '</table>'
		},
		error: function(error)
		{
			error_alert(error)
		}	
	}
	dojo.xhrGet(xhrArgs)
</script>
<script type='text/javascript'>
var xhrArgs = {
	url: '/api/list_companies/',
	handleAs: 'json',
	load: function(data)
	{
		var html = '<table><tr><th>contact</th><th>company</th><th>industry</th></tr>'
		for(var c = 0; c < data.length; c++)
		{
			html += '<tr><td>' + data[c].name + '</td><td>' + data[c].company + '</td><td>' + data[c].industry + '</td></tr>'
		}
		dojo.byId('companies').innerHTML = html + '</table>'
	},
	error: function(error)
	{
		error_alert(error)
	}	
}
dojo.xhrGet(xhrArgs)
</script>

<div id='companies'></div>

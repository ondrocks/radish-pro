<script type='text/javascript'>
var dataCached

function showItem(i)
{
	listMode = 'item'
	listCompaniesAsItem(dataCached, 'companies', i)
}

function showList()
{
	listMode = 'list'
	listCompaniesAsList(dataCached, 'companies')
}

function listCompaniesAsList(data, htmlId)
{
	var html = '<table><tr><th>contact</th><th>company</th><th>industry</th></tr>'
	for(var c = 0; c < data.length; c++)
	{
		html += '<tr><td>' + data[c].name + '</td><td><a href="javascript:showItem(' + c + ')">' + data[c].company + '</a></td><td>' + data[c].industry + '</td></tr>'
	}
	dojo.byId(htmlId).innerHTML = html + '</table>'
}

function listCompaniesAsItem(data, htmlId, itemId)
{
	var frame = document.createElement('div')
	displayFormAsItem('editCompany', frame, itemId)
	dojo.place(createAnchor("javascript:editCompany(" + itemId + ")", "Edit"), frame)
	dojo.byId(htmlId).innerHTML = ''
	dojo.place(frame, dojo.byId(htmlId))
}

var xhrArgs = {
	url: '/api/list_companies/',
	handleAs: 'json',
	load: function(data)
	{
		dataCached = data
		if(listMode == 'item')
			listComapniesAsItem(data, 'companies', 0)
		else
			listCompaniesAsList(data, 'companies')
	},
	error: function(error)
	{
		error_alert(error)
	}	
}
dojo.xhrGet(xhrArgs)
</script>
<div id='popup'></div>
<div id='companies'></div>

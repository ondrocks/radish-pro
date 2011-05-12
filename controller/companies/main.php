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
	var frame = document.createElement('table')
	var html = document.createElement('form')
	dojo.place(createInputTextElementAsRow(data[itemId].company), frame) 
	dojo.place(createInputTextElementAsRow(data[itemId].industry), frame)
	dojo.place(createInputTextElementAsRow(data[itemId].size), frame)
	dojo.byId(htmlId).innerHTML = ''
	dojo.place(frame, html)
	dojo.place(html, dojo.byId(htmlId))
	dojo.place(createAnchorAsRow("javascript:searchForCompanyProfile('" + encodeURIComponent(data[itemId].company) + "')", 'get profile info'), htmlId)

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

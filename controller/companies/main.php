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
	var html = '<table>'
	html += '<tr><td> :</td><td>' + data[itemId].company + '</td></tr>'
	html += '<tr><td> :</td> <td>' + data[itemId].industry + '</td></tr>'
	html += '<tr><td> :</td><td>' + data[itemId].size + '</td></tr>'
	html += '<tr><td></td><td><a href="javascript:searchForCompanyProfile(\'' + encodeURIComponent(data[itemId].company) + "')\">update profile</a>"	
	dojo.byId(htmlId).innerHTML = html + '</table>'
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

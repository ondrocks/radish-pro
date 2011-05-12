<script type='text/javascript'>
var listMode = 'list'
var dataCached

function showItem(itemId)
{
	listMode = 'item'
	listConnectionsAsItem(dataCached, 'connections', itemId)
}

function showList()
{
	listMode = 'list'
	listConnectionsAsList(dataCached, 'connections')
}

function submitSearchForm()
{
        dojo.connect(
                dojo.byId('form01'),
                'onsubmit',
                function(event)
                {
			dojo.byId('form01').q.value = encodeURIComponent(dojo.byId('form01').qq.value)
                        dojo.stopEvent(event)
			dojo.require('dojox.xml.parser')
                        var xhrArgs = {
                                form: dojo.byId('form01'),
				handleAs:'text',
                                load:function(data)
                                {
					var html = '<table><tr><th>picture</th><th>name</th><th>professional headline</th></tr>'
					var linkedInId = ''
					var firstname = ''
					var lastname = ''
					var headline = ''
					var profileUrl = 'profileUrl'
					var pictureUrl = ''
					var companyId = ''
					var xmldata = dojox.xml.parser.parse(data)
					var root = xmldata.documentElement
					for(var c = 0; c < root.getElementsByTagName('person').length; c++)	
					{
						for(var cc = 0; cc < root.getElementsByTagName('person')[c].childNodes.length; cc++)
						{
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'current-company')
							{
								companyId = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc])
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'picture-url' && document.location.protocol == 'http:')
							{
								pictureUrl = "<img class='profilePicture' src='" + dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]) + "'/>"
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'public-profile-url')
							{
								profileUrl = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc])
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'headline')
							{
								headline = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'id')
							{
								linkedInId = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);	
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'first-name')
							{
                                                                firstname = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);  
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'last-name')
							{
                                                                lastname = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);  
							}
					}
                                                html += '<tr><td class="picture">' + pictureUrl + "</td><td>" + firstname + ' ' + lastname + '</td><td><a class="black" target="_blank" href="' + profileUrl  +'">' + headline + '</a></td></tr>'
					}
					dojo.byId('connections').innerHTML = html + '</table>'
                                },
                                error: function(error)
                                {
                                        error_alert(error)
                                }
                        }
                        if(dojo.byId('q').value.match(/^\w/))
                        {
                                dojo.xhrPost(xhrArgs)
                        }
                }
        )
}

dojo.addOnLoad(submitSearchForm)

function listConnectionsAsList(data, htmlId)
{
	var html = '<table><tr><th>from</th><th>picture</th><th>name</th><th>email</th><th>place</th><th>country</th><th>company</th><th>linkedIn</th><th>twitter</th><th>facebook</th></tr>'
	for(var c = 0; c < data.length; c++)
	{
		html += "<tr><td>" +    data[c].userName + "</td><td class='picture'>" +
			(data[c].pictureUrl ? '<img class="profilePicture" src="' + data[c].pictureUrl + '"/>' : '') + '</td><td>' +
			"<a href='javascript:showItem(" + c + ")'>" + data[c].firstName + ' ' + data[c].lastName  + "</a></td><td>" + 
			(data[c].email ? data[c].email : '')  + "</td><td>" + 
			(data[c].place ? data[c].place : '') + "</td><td>" + 
			(data[c].country ? data[c].country : '') + '</td><td>' +
			(data[c].company ? data[c].company : '') + ' ' + (data[c].ticker ? '(' + data[c].ticker + ')' : '') + '</td><td>' +
			(data[c].headline ? data[c].headline : '') + "</td><td>"  + "</td><td>" + "</td><td>" + 
			"</td></tr>"
	}
	dojo.byId(htmlId).innerHTML = html + '</table>'
}

function listConnectionsAsItem(data, htmlId, itemId)
{
	var html = '<table>'
	html += "<tr><td>" + data[itemId].name + "</td></tr>"
	dojo.byId(htmlId).innerHTML = html + '</table>'
}

function listConnections()
{
	var xhrArgs = {
		url: '/api/list_connections/',
		handleAs: 'json',
		load: function(data)
		{
			dataCached = data
			if(listMode == 'item')
				listConnectionsAsItem(data, 'connections', 0)
			else
				listConnectionsAsList(data, 'connections')
		},
		error: function(error)
		{
			error_alert(error)
		}
	}
	dojo.xhrGet(xhrArgs)
}
dojo.addOnLoad(listConnections)

function listEmail()
{
	var xhrArgs = {
		url: '/api/list_emails/',
		handleAs: 'json',
		load: function(data)
		{
			var html = '<table><tr><th><?php echo PText::getString("Subject");?></th></tr>'
			for(var c = 0; c < data.length; c++)
			{
				var class = ''
				data[c].seen ? class='seen' : class=''; 
				html += '<tr><td class="' + class + '">' + data[c].subject + '</td</tr>'
			}
			dojo.byId('connections').innerHTML = html + '</table>'
		},
		error:function(error)
		{
			error_alert(error)
		}
	}
	dojo.xhrGet(xhrArgs)
}
</script>

<div id='connections'></div>


	dojo.require("dojo.window");

	var listMode = 'list'
	var popup 
	var form
	var popupTop = 85
	var popupHeight = 400
	var popupWidth = 500

	function error_alert(error)
	{
		alert('Error => ' + error)
	}

        function getUrlVars()
        {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

                for(var i = 0; i < hashes.length; i++)
                {
                        hash = hashes[i].split('=');
                        vars.push(hash[0]);
                        vars[hash[0]] = hash[1];
                }

                return vars;
        }
	function addAnchors(content)
	{
		var cont_arr = new String(content).split(" ")
		var returns = ''
		for(var cc = 0; cc < cont_arr.length; cc++)
		{
			if(cont_arr[cc].match(/^http:\/\//i))
			{
				var domain = new String(cont_arr[cc]).split("/")
				returns += "<a target='_blank' href='" + cont_arr[cc] + "'>" + domain[2] + "/...</a> " 
			}
			else
			{
				returns += cont_arr[cc] + " "
			}
		}
		return returns
	}

	function loginGmailContacts(){
		google.accounts.user.login('http://www.google.com/m8/feeds')
		document.location.href = '/config/?page=gmail'
	}

	function logoffGmail()
	{
		google.accounts.user.logout();
	}

	function Connection(name, place, country, gender, email, email2, email3, telephone, fb, tw, lkin, headline, picture, profile)
	{
		this.name = name
		this.place = place
		this.gender = gender
		this.country = country
		this.email = email
		this.email2 = email2
		this.email3 =  email3
		this.telephone = telephone
		this.fb = fb
		this.tw = tw
		this.lkin = lkin
		this.headline = headline
		this.pictureUrl = picture
		this.profileUrl = profile
	}

	function saveLinkedInConnectionsToDtb(connections)
	{
		for(var c = 0; c < connections.length; c++)
		{
			var pictureUrl = ''
			var profileUrl = ''
			var name = connections[c].name != 'Private' ? name = connections[c].name: name = 'Private'
			connections[c].profileUrl ? profileUrl = connections[c].profileUrl : profileUrl = '';
			connections[c].pictureUrl ? pictureUrl = connections[c].pictureUrl : pictureUrl = '';
			var xhrArgs = {
				url: '/api/post_linkedin_connection/',
				content:{ command:'post_linkedin_connection', name: name, linkedin: connections[c].lkin, headline: connections[c].headline, place: connections[c].place, country: connections[c].country, profileUrl: profileUrl, pictureUrl: pictureUrl},
				load:function(data)
				{
//alert('Retrun '+data)
				},
				error: function(error)
				{
					error_alert(error)
				}
			}
			if(name != 'Private')
			{
//			alert('Add ' + xhrArgs.content.name + ' to addressbook?')
				dojo.xhrPost(xhrArgs);
			}
		}
	}

	function saveConnectionsToDtb(connections)
	{
		var data = '[{'
		for(var c = 0; c < connections.length; c++)
		{
			var name = connections[c].name ? connections[c].name : 'No __name__'
			data += '"name":"' + name + '", "email":"' + connections[c].email + '"}'
			if(c < connections.length - 1)
				data += ',{'
		}
		data += ']'
		var xhrArgs = {
			url: '/api/post_email_connections/',
			content: { command:'post_email_connection', data: data},
			handleAs: 'text',
			load: function(data)
			{
			//	alert(data)
			},
			error: function(error)
			{
				error_alert(error)
			}
		}
		dojo.xhrPost(xhrArgs)
		alert('Records read: ' + connections.length)
	}

	function toggleMode()
	{
	        if(listMode == 'list')
        	{
        	        showItem(0)
        	}
		else
		{
                	showList();
        	}
	}


	function createForm(xmlFile)
	{
		var xhrArgs = {
			url : '/forms/' + xmlFile + '.xml',
			handleAs : 'xml',
			load : function(data)
			{
				var _form = document.createElement('form')
				var _els = data.getElementsByTagName('formelement')
				for(var c = 0; c < _els.length; c++)
				{
					switch(_els[c].getAttribute('type'))
					{
						case 'submit':
							dojo.place(createSubmitElement(_els[c].getAttribute('label')), _form)
							break;
					}
				}
				form = _form
				attachForm(form)
			},
			error: function(error)
			{
				error_alert(error)
			}
		}
		dojo.xhrGet(xhrArgs)
	}


	function createText(text)
	{
		var _el = document.createElement('p')
		_el.innerHTML = text
		return _el
	}

	function createTextAsRow(text)
	{
		return createAsRow(createText(text), 0, 0)
	}

	function createSubmitElement(label)
	{
		var _el = document.createElement('input')
		_el.setAttribute('type', 'submit')
		_el.value = label
		return _el
	}

	function createInputTextElementAsRow(value, form, label)
	{
		return createAsRow(createInputTextElement(value), label, 0)
	}

	function createAnchorAsRow(anchor, label, prnt)
	{
		return createAsRow(createAnchor(anchor, label), 0, 0)
	}

	function createAsRow(el, label, start)
	{
		var _el = document.createElement('tr')
		var _el2 = document.createElement('td')
		dojo.place(_el2, _el)
		dojo.place(el, _el2)
		return _el
	}

	function createAnchor(anchor, label)
	{
		var _el = document.createElement('a')
		_el.href = anchor
		_el.innerHTML = label
		return _el
	}

	function createInputTextElement(value)
	{
		var _el = document.createElement('input')
		_el.type = 'text'
		_el.value = value
		return _el
	}

	function searchForCompanyProfile()
	{
		createPopup()
		createForm('searchCompany')
	}

	function destroyPopup(o)
	{
		dojo.destroy(o)
		dojo.destroy(popup)
		dojo.destroy(dojo.byId('bgOverlay'))
		popup = void(0)
	}

	function attachForm(cnt)
	{
		dojo.place(cnt, dojo.byId('popupInner'))
	}

	function createPopup()
	{
		if(!popup)
		{
			var cnt = document.createElement('div')
			cnt.id = 'bgOverlay'
			cnt.style.width = '99%'
			cnt.style.height = (dojo.window.getBox().h - 20) + 'px'
			dojo.body().appendChild(cnt)

			var cnt = document.createElement('div')
			cnt.id = 'popupInner'
			cnt.style.position = 'absolute'
			cnt.style.top = popupTop + 'px'
			cnt.style.width = popupWidth + 'px'
			cnt.style.height = popupHeight + 'px'
			cnt.style.left = dojo.window.getBox().w / 2 - popupWidth / 2 + 'px'
			cnt.style.border = '1px solid #ff0000'
			cnt.style.background = 'white'
			popup = dojo.place(cnt, dojo.byId('popup'))
	
			var cnt = document.createElement('div')
			cnt.innerHTML = '[X]'
			cnt.style.background = 'white'
			cnt.style.cursor = 'pointer'
			cnt.style.position = 'absolute'
			cnt.style.top = popupTop - 22 + 'px'
			cnt.style.left = dojo.window.getBox().w / 2 + popupWidth / 2 - 18 +'px' 
			dojo.connect(cnt, 'onclick', function(){
				destroyPopup(this)
				})
			dojo.place(cnt, dojo.byId('popup'))
		}
	}


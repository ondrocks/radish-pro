	dojo.require("dojo.window");

	var listMode = 'list'
	var popup 
	var form
	var popupTop = 85
	var popupHeight = 400
	var popupWidth = 570

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
		document.location.href = 'config/?page=gmail'
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
                                url: 'api/post_linkedin_connection/',
				content:{ command:'post_linkedin_connection', name: name, linkedin: connections[c].lkin, headline: connections[c].headline, place: connections[c].place, country: connections[c].country, profileUrl: profileUrl, pictureUrl: pictureUrl},
				load:function(data)
				{
					alert('Read ' + data.length + ' rows')
				},
				error: function(error)
				{
					error_alert(error)
				}
			}
			if(name != 'Private')
			{
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
                        url: 'api/post_email_connections/',
			content: { command:'post_email_connections', data: data},
			handleAs: 'text',
			load: function(data)
			{
				//alert(data)
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

	function displayFormAsItem(xmlFile, object, index)
	{
		var xhrArgs = {
                        url: 'forms/' + xmlFile + '.xml',
			handleAs: 'xml',
			load: function(data)
			{
				var _el = document.createElement('table')
				var _els = data.getElementsByTagName('formelement')
				for(var c = 0; c < _els.length; c++)
				{
					switch(_els[c].getAttribute('type'))
					{
						case 'submit':
						case 'hidden':
							break;
						case 'image':
							if(dataCached[index][_els[c].getAttribute('for')].match(/^http:/i))
								dojo.place(createImageAsRow(
									dataCached[index][_els[c].getAttribute('for')], 
									_els[c].getAttribute('label')), _el)
							break;
						case 'anchor':
							dojo.place(createAnchorAsRow(dataCached[index][_els[c].getAttribute('for')], _els[c].getAttribute('label')), _el)
							break;
						case 'telephone':
							dojo.place(createTelephoneNumberAsRow(dataCached[index][_els[c].getAttribute('for')], _els[c].getAttribute('label')), _el)
							break;
						case 'textarea':
							dojo.place(createTextParaAsRow(_els[c].getAttribute('for'), dataCached[index][_els[c].getAttribute('for')], _els[c].getAttribute('label')), _el)
							break;							
						case 'text' :
						case 'lookup':
							dojo.place(createTextAsRow(dataCached[index][_els[c].getAttribute('for')], _els[c].getAttribute('label')), _el) 
							break;
					}
				}	
				dojo.place(_el, object, 0)
			},
			error: function(error)
			{
				error_alert(error)
			}
		}
		dojo.xhrGet(xhrArgs)
	}

	function createForm(xmlFile, index)
	{
		var xhrArgs = {
                        url : 'forms/' + xmlFile + '.xml',
			handleAs : 'xml',
			load : function(data)
			{
				var _form = document.createElement('form')
				var _tab = document.createElement('table')
				_form.setAttribute('method', 'POST')
				var _f = data.getElementsByTagName('form')[0]
				_form.setAttribute('action', _f.getAttribute('action'))
				_form.setAttribute('id', _f.getAttribute('formid'))
				dojo.place(_tab, _form)
				
				var _els = data.getElementsByTagName('formelement')
				for(var c = 0; c < _els.length; c++)
				{
					switch(_els[c].getAttribute('type'))
					{
						case 'separator':
							
							var _tab2 = document.createElement('table')
							dojo.place(_tab2, _form)
							_tab = _tab2
							break;
						case 'button':
							dojo.place(createButtonElementAsRow(_els[c].getAttribute('label'), _els[c].getAttribute('onclick')), _tab)
							break;
						case 'submit':
							dojo.place(createSubmitElementAsRow(_els[c].getAttribute('label')), _tab)
							break;
						case 'hidden':
							dojo.place(createHiddenElement(_els[c].getAttribute('name'), _els[c].getAttribute('value')), _tab)
							break;
						case 'id':
							dojo.place(createHiddenElement(_els[c].getAttribute('for'), dataCached[index].id), _tab)
							break;
						case 'lookup':
							dojo.place(createLookupAsRow(
								_els[c].getAttribute('into'), 
								_els[c].getAttribute('label'), 
								_els[c].getAttribute('for'),
								_els[c].getAttribute('where'),
								dataCached[index][_els[c].getAttribute('where')],
								dataCached[index][_els[c].getAttribute('for')],
								_els[c].getAttribute('editable')), _tab)
							break;
						case 'textarea':
							dojo.place(createTextareaAsRow(
							_els[c].getAttribute('for'),
							dataCached[index][_els[c].getAttribute('for')],
							_els[c].getAttribute('label'),
							_els[c].getAttribute('editable')), _tab)
							break;
						case 'telephone':
						case 'text':
						case 'image':
						case 'anchor':
							dojo.place(createInputTextElementAsRow(
								_els[c].getAttribute('label'), 
								_els[c].getAttribute('for'), 
								dataCached[index][_els[c].getAttribute('for')],
								_els[c].getAttribute('editable')), _tab)
							break;
					}
				}
				form = _form 
				attachForm(form)
				dojo.connect(
					dojo.byId(_f.getAttribute('formid')),
					'onsubmit',
					function(event){
						dojo.stopEvent(event);
						var xhrArgs = {
							form: dojo.byId(_f.getAttribute('formid')),
							handleAs: 'text',
							load: function(data)
							{
								alert(data);
							},
							error: function(error)
							{
								error_alert(error);
							}
						}
						dojo.xhrPost(xhrArgs)
					})
			},
			error: function(error)
			{
				error_alert(error)
			}
		}
		dojo.xhrGet(xhrArgs)
	}
	
	function createLookupAsRow(into, label, name, where, whereValue, value, editable)
	{
		return createAsRow(createLookup(into, name, where, whereValue, value, editable), label, 0)
	}

	function createLookup(into, name, where, whereValue, value, editable)
	{
		var _el = document.createElement('select')
		_el.name = name
		if(editable && editable == 'false')
			_el.setAttribute('disabled', 'disabled')
		_el.id = 'lookup' + into
		var xhrArgs = {
                        url: 'api/lookup'  + into + '/?' + where + '=' + encodeURIComponent(whereValue),
			handleAs: 'json',
			load: function(data)
			{
				for(var c = 0; c < data.length; c++)
				{
					var _el = document.createElement('option')
					_el.value = data[c].id
					_el.innerHTML = data[c].name
					if(data[c].name == value)
						_el.selected = 'selected'
					dojo.place(_el, dojo.byId('lookup'+into))
				}
			},
			error: function(error)
			{
				error_alert(error)
			}
		}
		dojo.xhrGet(xhrArgs)
		return _el
	}

	function createImageAsRow(url, label)
	{
		return createAsRow(createImage(url), label, 0)
	}

	function createImage(url)
	{
		var _el = document.createElement('img')
		_el.setAttribute('src', url)
		return _el
	}

	function createHiddenElement(name, value)
	{
		var _el = document.createElement('input')
		_el.setAttribute('type', 'hidden')
		_el.setAttribute('value', value)
		_el.setAttribute('name', name)
		return _el
	}
	
	function createTextPara(name, value)
	{
		var _el = document.createElement('p')
		_el.innerHTML = value
		return _el
	}
	
	function createTextParaAsRow(name, value, label)
	{
		return createAsRow(createTextPara(name, value), label, 0)
	}

	function createTextarea(name, value, editable)
	{
		var _el = document.createElement('textarea')
		if(editable && editable == 'false')
			_el.setAttribute('readonly', 'readonly')
		_el.setAttribute('name', name)
		_el.innerHTML = value
		return _el
	}
	
	function createTextareaAsRow(name, value, label, editable)
	{
		return createAsRow(createTextarea(name, value, editable), label, 0)
	}
	
	function createTelephoneNumber(text)
	{
		var _el = document.createElement('a')
		_el.setAttribute('href', 'skype:'+text)
		_el.innerHTML = text
		return _el
	}
	
	function createTelephoneNumberAsRow(text, label)
	{
		return createAsRow(createTelephoneNumber(text), label, 0)
	}
	
	function createText(text)
	{
		var _el = document.createElement('p')
		_el.innerHTML = text
		return _el
	}

	function createTextAsRow(text, label)
	{
		return createAsRow(createText(text), label, 0)
	}
	
	function createButtonElement(label, value)
	{
		var _el = document.createElement('input')
		_el.setAttribute('type', 'button')
		_el.setAttribute('onclick', value)
		_el.setAttribute('value', label)
		return _el
	}

	function createButtonElementAsRow(label, value)
	{
		return createAsRow(createButtonElement(label, value))
	}
	
	function createSubmitElement(label)
	{
		var _el = document.createElement('input')
		_el.setAttribute('type', 'submit')
		_el.value = label
		return _el
	}

	function createSubmitElementAsRow(label)
	{
		return createAsRow(createSubmitElement(label))
	}

	function createInputTextElementAsRow(label, name, value, editable)
	{
		return createAsRow(createInputTextElement(name, value, editable), label, 0)
	}

	function createAnchorAsRow(anchor, label, prnt)
	{
		return createAsRow(createAnchor(anchor, label), label, 0)
	}

	function createAsRow(el, label, start)
	{
		var _el = document.createElement('tr')
		var _el2 = document.createElement('td')
		var _el3 = document.createElement('td')
		if(!label)
			_el3.setAttribute('colspan', 2)
		var _l = document.createElement('label')
		_l.innerHTML = label + ' : '
		if(label)
		{
			dojo.place(_l, _el2)
			dojo.place(_el2, _el)
		}
		dojo.place(el, _el3)
		dojo.place(_el3, _el)
		return _el
	}

	function createAnchor(anchor, label)
	{
		var _el = document.createElement('a')
		if(anchor)
		{
			_el.href = anchor
			_el.innerHTML = label
		}
		//_el.setAttribute('target', '_blank')
		return _el
	}

	function createInputTextElement(name, value, editable)
	{
		var _el = document.createElement('input')
		_el.type = 'text'
		if(editable && editable == 'false')
			_el.setAttribute('readonly', 'readonly')
		_el.value = value
		_el.name = name
		return _el
	}

	function searchForCompanyProfile()
	{
		createPopup()
		createForm('searchCompany')
	}

	function editCompany(index)
	{
		createPopup()
		createForm('editCompany', index)
	}

	function editConnection(index)
	{
		createPopup()
		createForm('editPeople', index)
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
	function getCompInfo(comp, fields)
	{
		var _comp = dojo.query("[name^="+comp+"]")[0].value
		var xhrArgs = {
			url: 'api/get_comp_info/?comp=' + encodeURIComponent(_comp),
			handleAs: 'json',
			load: function(data)
			{
				if(data.kvk == false)
				{
					alert('No match found')
					return
				}
				else
				{
					for(var c = 0; c < fields.length; c++)
					{
						if(data[fields[c]] != '')
							dojo.query("[name^=" + fields[c] + "]")[0].value = data[fields[c]]
					}
				}
			}
		}
		dojo.xhrGet(xhrArgs)
	}
	function showProgress(strId, text)
	{
		var el = document.createElement('div')
		el.setAttribute('style', 'height:200px;');
		el.innerHTML = '<br/><br/><br/><br/>' + text
		dojo.place(el, dojo.byId(strId))
	}
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

	function Connection(name, place, gender, email, email2, email3, telephone, fb, tw, lkin)
	{
		this.name = name
		this.place = place
		this.gender = gender
		this.email = email
		this.email2 = email2
		this.email3 =  email3
		this.telephone = telephone
		this.fb = fb
		this.tw = tw
		this.lkin = lkin
	}

	function saveConnectionsToDtb(connections)
	{
		for(var c = 0; c < connections.length; c++)
		{
			var xhrArgs = {
				url: '/api/post_email_connection/',
				content: { command:'post_email_connection', name: connections[c].name, email: connections[c].email},
				//handleAs: 'text',
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
		}
		alert('Records read: ' + connections.length)
	}

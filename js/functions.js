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
		for(var c = 0; c < connections.length; c++)
		{
			var name = connections[c].name ? connections[c].name : 'No __name__'
			var xhrArgs = {
				url: '/api/post_email_connection/',
				content: { command:'post_email_connection', name: name, email: connections[c].email},
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

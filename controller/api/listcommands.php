<?php

$qs = array(	
	new APICommand(
		'list_connections',
		new Query(
			'select distinct a.id, a.firstName as firstName, a.lastName as lastName, 
				a.place, a.chatinfo, a.country, a.profileUrl, a.pictureUrl, b.email, 
				bb.email as email2, bbb.email as email3, a.telephone, a.mobile, a.gender, a.status, 
				c.twitter, d.linkedin, a.headline, f.name as userName, 
				g.name as company, g.ticker 
				from #_people a left join 
				#_email_connections b  on a.email = b.id left join 
				#_email_connections bb on a.email2 = bb.id left join
				#_email_connections bbb on a.email3 = bbb.id left join 
				#_twitter_connections c on c.id = a.twitter left join 
				#_linkedin_connections d on d.id = a.linkedin left join 
				#_fb_connections e on d.id = a.facebook left join 
				#_users f on f.id = a.user left join 
				#_positions h on h.people_id = a.id left join #_companies g on g.id = h.company_id 
				where b.email != "" or c.twitter != "" or d.linkedin != ""
				order by a.lastName '
		),
		LEVEL_0,
		true
	),
	new APICommand(
		'list_companies',
		new Query(
			'select c.lastName as name, a.id, a.industry, a.name as company,
				a.postalcode, a.url, a.businessAddress, a.businessPostalcode, a.businessPlace, 
				a.kvk, a.address, a.place, a.size, a.ticker, a.telephone 
				from #_companies a left join #_positions b on a.id = b.company_id 
				left join #_people c on b.people_id = c.id order by a.name',
			null,
			true),
		LEVEL_0,
		true
	),
	new APICommandMergeCompanies(
		'list_companies_to_merge',
		null,
		LEVEL_0,
		true
	),
	new APICommand(
		'lookuppeople',
		new Query('select a.lastName as name, a.id from #_people a 
				left join #_positions b on b.people_id = a.id
				left join #_companies c on b.company_id = c.id  
				where c.name like :company', 
			null, 
			true),
		LEVEL_0,
		false
	),
	new APICommand(
		'list_roles',
		new Query(
			'select id, level, name 
			from ' . TABLE_PREFIX .'roles', null, true),
		LEVEL_0,
		true
	),
	new APICommand(
		'list_profiles',
		new Query(
			'select id, name
			from ' . TABLE_PREFIX . 'profiles 
			where account=:account', null, true),
		LEVEL_0,
		false
	),
	new APICommand(
		'list_name_profile',
		new Query(
			'select name 
			from ' . TABLE_PREFIX . 'profiles
			where id=:profile', null, true),
		LEVEL_0,
		false
	),
	new APICommand(
		'list_keywords',
		new Query(
			'select a.id, a.keywords, b.set_id from ' . TABLE_PREFIX . 'sets a, ' . TABLE_PREFIX . 'profiles b 
			where a.id=b.set_id and b.id=:profile'),
		LEVEL_0,
		false
	),
	new APICommand(
		'list_lead_item_types',
		new Query(
			'select id, name 
			from ' . TABLE_PREFIX . 'lead_item_types', null, true),
		LEVEL_0,
		true
	),
	new APICommand(
		'list_users',
		new Query(
			'select a.id, a.name as name, b.name as account 
			from ' . TABLE_PREFIX . 'users a, ' . TABLE_PREFIX .  'accounts b 
			where a.account=:account and a.id=b.id', null, true),
		LEVEL_0,
		true
	),	
	new APICommand(
		'list_accounts',
		new Query(
			'select id,  name 
			from ' . TABLE_PREFIX .'accounts', null, true),
		null,
		true
	),
	new APICommand(
		'get_set_messages',
		new Query(
			'select pos_message, neg_message, special_message 
			from ' . TABLE_PREFIX . 'sets a, ' . TABLE_PREFIX . 'profiles b 
			where a.id=b.set_id and b.id=:profileId', null, true),
		LEVEL_0,
		false
	),
	new APICommand(
		'list_events',
		new Query(
			'select a.id, a.date, a.time, profile, b.name, status, client, client_name, content, date, time
			from ' . TABLE_PREFIX . 'events a,' . TABLE_PREFIX . 'types b
			where a.type = b.id',
			null, true),
		LEVEL_0,
		true
	),
	new APICommand(
		'list_emails',
		null,
		LEVEL_0,
		false
	),
	new APICommand(
		'import_companies',
		null,
		LEVEL_0,
		false
	),
	new APICommand(
		'get_comp_info',
		null,
		LEVEL_0,
		false
	),
	new APICommandImportConnections(
		'import_connections',
		null,
		LEVEL_0,
		false)
	
);

?>

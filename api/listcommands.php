<?php

$qs = array(	
	new APICommand(
		'list_roles',
		new Query(
			'select id, level, name 
			from ' . TABLE_PREFIX .'roles', null, true),
		LEVEL_0
	),
	new APICommand(
		'list_profiles',
		new Query(
			'select id, name
			from ' . TABLE_PREFIX . 'profiles 
			where account=:account', null, true),
		LEVEL_0
	),
	new APICommand(
		'list_name_profile',
		new Query(
			'select name 
			from ' . TABLE_PREFIX . 'profiles
			where id=:profile', null, true),
		LEVEL_0
	),
	new APICommand(
		'list_keywords',
		new Query(
			'select a.id, a.keywords, b.set_id from ' . TABLE_PREFIX . 'sets a, ' . TABLE_PREFIX . 'profiles b 
			where a.id=b.set_id and b.id=:profile'),
		LEVEL_0
	),
	new APICommand(
		'list_lead_item_types',
		new Query(
			'select id, name 
			from ' . TABLE_PREFIX . 'lead_item_types', null, true),
		LEVEL_0
	),
	new APICommand(
		'list_users',
		new Query(
			'select a.id, a.name as name, b.name as account 
			from ' . TABLE_PREFIX . 'users a, ' . TABLE_PREFIX .  'accounts b 
			where a.account=:account and a.id=b.id', null, true),
		LEVEL_0
	),	
	new APICommand(
		'list_accounts',
		new Query(
			'select id,  name 
			from ' . TABLE_PREFIX .'accounts', null, true),
		null
	),
	new APICommand(
		'get_set_messages',
		new Query(
			'select pos_message, neg_message, special_message 
			from ' . TABLE_PREFIX . 'sets a, ' . TABLE_PREFIX . 'profiles b 
			where a.id=b.set_id and b.id=:profileId', null, true),
		LEVEL_0
	),
	new APICommand(
		'list_events',
		new Query(
			'select a.id, a.date, a.time, profile, b.name, status, client, client_name, content, date, time
			from ' . TABLE_PREFIX . 'events a,' . TABLE_PREFIX . 'types b
			where a.type = b.id',
			null, true),
		LEVEL_0
	)
	
);

?>

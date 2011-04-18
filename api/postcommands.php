<?php

$qs = array(
        new APIPostCommand(
                'post_keywords',
                new Query(
                        'insert into ' . TABLE_PREFIX . 'sets
			(keywords) 
			values(:keywords)', 
			null, 
			false
		),
                LEVEL_0
        ),
	new APIPostCommand(
		'post_profile',
		new Query(
			'insert into ' . TABLE_PREFIX . 'profiles
			(name, active, account, set_id) 
			values(:name, :active, :account, :set_id)',
			null, 
			false
		),
		LEVEL_0
	),
	new APIPostCommand(
		'post_set_messages',
		new Query(
			'update ' . TABLE_PREFIX . 'sets
			set pos_message = :pos_message, neg_message = :neg_message, special_message=:special_message  
			where id=:setId',
			null,
			false
		),
		LEVEL_0
	),
	new APIPostCommand(
		'post_email_connection',
		new Query(
			'insert into ' . TABLE_PREFIX . 'email_connections
			(email)
			values(:email)',
			null,
			false
		),
		LEVEL_0
	)
)

?>

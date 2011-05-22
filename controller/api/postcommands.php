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
                LEVEL_0,
		false
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
		LEVEL_0,
		false
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
		LEVEL_0,
		false
	),
	new APIPostCommand(
		'post_connection',
		new Query(
			'update #_people
			set status=:status, firstName=:firstName, lastName=:lastName, pictureUrl=:pictureUrl, headline=:headline, 
			chatinfo=:chatinfo, place=:place, country=:country, gender=:gender, email=:email, email2=:email2, 
			email3=:email3, telephone=:telephone, mobile=:mobile, profileUrl=:profileUrl where id=:id',
			null,
			false
		),
		LEVEL_0,
		false
	),
	new APIPostCommand(
		'post_company',
		new Query(
			'update #_companies
			set name=:company, businessAddress=:businessAddress, businessPostalcode=:businessPostalcode, 
			businessPlace=:businessPlace, kvk=:kvk, telephone=:telephone, place=:place, 
			address=:address, postalcode=:postalcode, url=:url, size=:size, ticker=:ticker, industry=:industry
			where id=:id',
			null,
			false
		),
		LEVEL_0,
		false
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
		LEVEL_0,
		false
	),
	new APICommand(
		'post_email_connections',
		null,
		LEVEL_0,
		false
	)
)

?>

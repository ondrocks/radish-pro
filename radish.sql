create table crm_accounts(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(32) NOT NULL,
PRIMARY KEY(id)
);

create table crm_users(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(32) NOT NULL,
login_id VARCHAR(128) NOT NULL,
login_type INT(32),
role INT(32) NOT NULL,
account INT(32) NOT NULL,
PRIMARY KEY(id)
);

create table crm_types(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(32) NOT NULL,
PRIMARY KEY(id)
);

create table crm_events(
id INT(32) NOT NULL AUTO_INCREMENT,
md5 VARCHAR(32) NOT NULL,
locked INT(32),
ip VARCHAR(64),
profile INT(32) NOT NULL,
type INT(32) NOT NULL,
status INT(4) NOT NULL,
client VARCHAR(128) NOT NULL,
client_name VARCHAR(128) NOT NULL,
content TEXT NOT NULL,
date DATE NOT NULL,
time VARCHAR(32) NOT NULL,
keyword VARCHAR(128),
relevancy INT(4),
PRIMARY KEY(id),
UNIQUE (md5)
);

create table crm_people(
id INT(32) NOT NULL AUTO_INCREMENT,
account INT(32),
user INT(32),
status INT(32) NOT NULL,
type INT(32),
locked INT(32),
ip VARCHAR(64),
name VARCHAR(128) NOT NULL,
headline VARCHAR(128),
chatinfo TEXT,
place VARCHAR(128),
country VARCHAR(32),
gender INT(2),
email INT(32),
email2 INT(32),
email3 INT(32),
telephone VARCHAR(32),
facebook INT(32),
twitter INT(32),
linkedIn INT(32),
PRIMARY KEY(id)
);

create table crm_email_connections(
id INT(32) NOT NULL AUTO_INCREMENT,
email VARCHAR(128),
PRIMARY KEY(id),
UNIQUE (email)
);

create table crm_fb_connections(
id INT(32) NOT NULL AUTO_INCREMENT,
profile VARCHAR(128),
PRIMARY KEY(id),
UNIQUE (profile)
);

create table crm_linkedin_connections(
id INT(32) NOT NULL AUTO_INCREMENT,
linkedin VARCHAR(128),
PRIMARY KEY(id),
UNIQUE (linkedin)
);

create table crm_twitter_connections(
id INT(32) NOT NULL AUTO_INCREMENT,
twitter VARCHAR(128),
PRIMARY KEY(id),
UNIQUE (twitter)
);

create table crm_leads(
id INT(32) NOT NULL AUTO_INCREMENT,
account INT(32) NOT NULL,
url VARCHAR(128) NOT NULL,
name VARCHAR(128) NOT NULL,
status INT(32) NOT NULL,
type INT(32) NOT NULL,
PRIMARY KEY(id)
);

create table crm_lead_status(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
PRIMARY KEY(id)
);

create table crm_lead_item(
id INT(32) NOT NULL AUTO_INCREMENT,
user INT(32) NOT NULL,
description TEXT NOT NULL,
telephone VARCHAR(12),
email VARCHAR(128),
item_type INT(32) NOT NULL,
lead INT(32) NOT NULL,
date DATE NOT NULL,
time TIME NOT NULL,
PRIMARY KEY(id)
);

create table crm_lead_item_types(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(32) NOT NULL,
PRIMARY KEY(id)
);

create table crm_roles(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
level INT(2) NOT NULL,
PRIMARY KEY(id)
);

create table crm_actions(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
level INT(2) NOT NULL,
PRIMARY KEY(id)
);

create table crm_actions_taken(
id INT(32) NOT NULL AUTO_INCREMENT,
event INT(32) NOT NULL,
lead INT(32) NOT NULL,
action INT(32) NOT NULL,
description TEXT NOT NULL,
date DATE NOT NULL,
time TIME NOT NULL,
PRIMARY KEY(id)
);

create table crm_messages(
id INT(32) NOT NULL AUTO_INCREMENT,
event INT(32) NOT NULL,
caption VARCHAR(128) NOT NULL,
body TEXT NOT NULL,
status INT(2) NOT NULL,
PRIMARY KEY(id)
);

create table crm_sets(
id INT(32) NOT NULL AUTO_INCREMENT,
pos_message TEXT,
neg_message TEXT,
special_message TEXT,
keywords TEXT NOT NULL,
PRIMARY KEY(id)
);

create table crm_profiles(
id INT(32) NOT NULL AUTO_INCREMENT,
name VARCHAR(24) NOT NULL,
active BOOL NOT NULL,
account INT(32) NOT NULL,
set_id INT(32) NOT NULL,
PRIMARY KEY(id)
);

insert into crm_accounts(name)values('demo');
insert into crm_lead_item_types(name)values('Facebook message'),('tweet'),('email'),('telephone'),('walk-in');
insert into crm_types(name)values('Twitter'),('Facebook'), ('LinkedIn'), ('e-mail');
insert into crm_roles(name, level)values('administrator', 1),('user', 0);
insert into crm_actions(name, level)values('sent message', 0),('', 1);
insert into crm_lead_status(name)values('unknown person'),('prospect'), ('action required'), ('connection'), ('dead');
insert into crm_users(name, login_id, role, account)values('Nadia Gombra', 'DHqzIsP_64', 1, 1);
insert into crm_users(name, login_id, role, account)values('Pieter Hoekstra', 'R8tdWFiRkq', 1, 1);
insert into crm_users(name, login_id, role, account)values('Thorgal Nicasia', 'Ct46VXpcsx', 1, 1);
insert into crm_users(name, login_id, role, account)values('Nathalie Voorn', 'a_evdcLKIr', 1, 1);

insert into crm_profiles(name, active, account) values('instant Search', true, 1);


#
# Table structure for table 'tx_rkworder_domain_model_order'
#
CREATE TABLE tx_rkworder_domain_model_order (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	page_title varchar(255) DEFAULT '' NOT NULL,
	page_subtitle varchar(255) DEFAULT '' NOT NULL,
	series_title varchar(255) DEFAULT '' NOT NULL,
	send_series int(11) DEFAULT '0' NOT NULL,
	subscribe int(11) DEFAULT '0' NOT NULL,
	gender tinyint(4) DEFAULT '0' NOT NULL,
	first_name varchar(255) DEFAULT '' NOT NULL,
	last_name varchar(255) DEFAULT '' NOT NULL,
	company varchar(255) DEFAULT '' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	zip int(11) DEFAULT '0' NOT NULL,
	city varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	amount int(11) DEFAULT '0' NOT NULL,
	frontend_user int(11) unsigned DEFAULT '0',
	pages int(11) unsigned DEFAULT '0',
	remark text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	status tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

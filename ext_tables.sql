#
# Table structure for table 'tx_rkworder_domain_model_order'
#
CREATE TABLE tx_rkworder_domain_model_order (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	email varchar(255) DEFAULT '' NOT NULL,
	amount int(11) DEFAULT '0' NOT NULL,
	frontend_user int(11) unsigned DEFAULT '0',
	shipping_address int(11) DEFAULT '0' NOT NULL,
	pages int(11) unsigned DEFAULT '0',
	product text NOT NULL,
	send_series int(11) DEFAULT '0' NOT NULL,
	subscribe int(11) DEFAULT '0' NOT NULL,
	remark text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	status tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);


#
# Table structure for table 'tx_rkworder_domain_model_product'
#
CREATE TABLE tx_rkworder_domain_model_product (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	subtitle varchar(255) DEFAULT '' NOT NULL,
	stock int(11) unsigned DEFAULT '0',
	bundle_only tinyint(4) unsigned DEFAULT '0' NOT NULL,
	allow_subscription tinyint(4) unsigned DEFAULT '0' NOT NULL,
	backend_user varchar(255) DEFAULT '' NOT NULL,
	admin_email varchar(255) DEFAULT '' NOT NULL,
	ordered_external int(11) unsigned DEFAULT '0' NOT NULL,
	page varchar(255) DEFAULT '' NOT NULL,
	product_parent int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);



CREATE TABLE tx_datahandlerqueue_domain_model_entry (
	uid int(11) NOT NULL auto_increment,
	tablename varchar(255) DEFAULT '' NOT NULL,
	fieldname varchar(255) DEFAULT '' NOT NULL,
	record_uid int(11) DEFAULT '0' NOT NULL,
	command varchar(255) DEFAULT '' NOT NULL,
	value blob,
	executed tinyint(4) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);

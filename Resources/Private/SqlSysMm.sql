#############################################################
# Table structure for table 'sys_mm'
# Sql file: SqlSysMm.sql
#############################################################
CREATE TABLE sys_mm (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
###COLUMNS###
	KEY parent (pid),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign),
	PRIMARY KEY (uid)
);

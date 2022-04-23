#############################################################
# Table structure for table '###TABLE###'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE ###TABLE### (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
###COLUMNS###
	PRIMARY KEY (uid),
	KEY parent (pid)
);

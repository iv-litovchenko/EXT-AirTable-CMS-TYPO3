#############################################################
# Table structure for table '###TABLE###'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE ###TABLE### (
###COLUMNS###
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);

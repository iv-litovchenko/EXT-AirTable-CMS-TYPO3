#############################################################
# Table structure for table 'tx_data'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_data (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	alt_title varchar(255) DEFAULT '' NOT NULL,
	slug varchar(2048) DEFAULT '' NOT NULL,
	date_create int(11) DEFAULT '0' NOT NULL,
	prop_flexform text,
	prop_input varchar(255) DEFAULT '' NOT NULL,
	prop_text text,
	prop_text_rte text,
	prop_text_code_html text,
	prop_text_code_ts text,
	propmedia_media_1 int(11) DEFAULT '0' NOT NULL,
	propmedia_media_m int(11) DEFAULT '0' NOT NULL,
	date_update int(11) DEFAULT '0' NOT NULL,
	service_note text,
	propref_beauthor int(11) DEFAULT '0' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	date_start int(11) DEFAULT '0' NOT NULL,
	date_end int(11) DEFAULT '0' NOT NULL,
	status varchar(255) DEFAULT '' NOT NULL,
	bodytext_preview text,
	propmedia_pic_preview int(11) DEFAULT '0' NOT NULL,
	bodytext_detail text,
	propmedia_pic_detail int(11) DEFAULT '0' NOT NULL,
	keywords text,
	description text,
	propref_content int(11) DEFAULT '0' NOT NULL,
	propmedia_files int(11) DEFAULT '0' NOT NULL,
	propmedia_thumbnail int(11) DEFAULT '0' NOT NULL,
	propref_categories int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_data_category'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_data_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	alt_title varchar(255) DEFAULT '' NOT NULL,
	slug varchar(2048) DEFAULT '' NOT NULL,
	date_create int(11) DEFAULT '0' NOT NULL,
	date_update int(11) DEFAULT '0' NOT NULL,
	service_note text,
	propref_beauthor int(11) DEFAULT '0' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	date_start int(11) DEFAULT '0' NOT NULL,
	date_end int(11) DEFAULT '0' NOT NULL,
	status varchar(255) DEFAULT '' NOT NULL,
	bodytext_preview text,
	propmedia_pic_preview int(11) DEFAULT '0' NOT NULL,
	bodytext_detail text,
	propmedia_pic_detail int(11) DEFAULT '0' NOT NULL,
	keywords text,
	description text,
	propmedia_files int(11) DEFAULT '0' NOT NULL,
	propmedia_thumbnail int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_data_type'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_data_type (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	uidkey varchar(255) DEFAULT '' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	service_note text,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	props_default text,
	props_default_cat text,
	prop_add_new_button int(11) DEFAULT '0' NOT NULL,
	propref_group int(11) DEFAULT '0' NOT NULL,
	propref_subtypes int(11) DEFAULT '0' NOT NULL,
	propref_subtypescat int(11) DEFAULT '0' NOT NULL,
	prop_data_type_controller varchar(255) DEFAULT '' NOT NULL,
	prop_base_pages_row_id varchar(255) DEFAULT '' NOT NULL,
	prop_urls varchar(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_data_type_group'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_data_type_group (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	proprefinv_datatypes int(11) DEFAULT '0' NOT NULL,
	uidkey varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_data_type_sub'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_data_type_sub (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	proprefinv_datatype int(11) DEFAULT '0' NOT NULL,
	proprefinv_datatypeforcat int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'sys_file_category'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE sys_file_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'sys_flux_setting'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE sys_flux_setting (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	source_table varchar(255) DEFAULT '' NOT NULL,
	source_field varchar(255) DEFAULT '' NOT NULL,
	source_signature varchar(255) DEFAULT '' NOT NULL,
	source_record tinyint(4) unsigned DEFAULT '0' NOT NULL,
	source_sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	settingkey varchar(255) DEFAULT '' NOT NULL,
	settingvalue text,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



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
	tablename varchar(255) DEFAULT '' NOT NULL,
	fieldname varchar(255) DEFAULT '' NOT NULL,
	uid_local tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	uid_foreign tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ident text,
	KEY parent (pid),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign),
	PRIMARY KEY (uid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	alt_title varchar(255) DEFAULT '' NOT NULL,
	slug varchar(2048) DEFAULT '' NOT NULL,
	date_create int(11) DEFAULT '0' NOT NULL,
	date_update int(11) DEFAULT '0' NOT NULL,
	service_note text,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	date_start int(11) DEFAULT '0' NOT NULL,
	date_end int(11) DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	status varchar(255) DEFAULT '' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	bodytext_preview text,
	propmedia_pic_preview int(11) DEFAULT '0' NOT NULL,
	bodytext_detail text,
	propmedia_pic_detail int(11) DEFAULT '0' NOT NULL,
	keywords text,
	description text,
	propmedia_files int(11) DEFAULT '0' NOT NULL,
	propmedia_thumbnail int(11) DEFAULT '0' NOT NULL,
	prop_input varchar(255) DEFAULT '' NOT NULL,
	prop_text text,
	prop_folder varchar(2048) DEFAULT '' NOT NULL,
	prop_flag int(11) DEFAULT '0' NOT NULL,
	prop_switch int(11) DEFAULT '0' NOT NULL,
	prop_enum text,
	prop_date int(11) DEFAULT '0' NOT NULL,
	prop_test_flag int(11) DEFAULT '0' NOT NULL,
	prop_test_field_1 varchar(255) DEFAULT '' NOT NULL,
	prop_test_field_2 varchar(255) DEFAULT '' NOT NULL,
	prop_test_field_3 varchar(255) DEFAULT '' NOT NULL,
	propmedia_media int(11) DEFAULT '0' NOT NULL,
	propref_exampletable1 int(11) DEFAULT '0' NOT NULL,
	propref_exampletable1b int(11) DEFAULT '0' NOT NULL,
	propref_exampletable1c int(11) DEFAULT '0' NOT NULL,
	propref_exampletable2 int(11) DEFAULT '0' NOT NULL,
	propref_exampletable2b int(11) DEFAULT '0' NOT NULL,
	propref_exampletable2c int(11) DEFAULT '0' NOT NULL,
	propref_exampletable2d int(11) DEFAULT '0' NOT NULL,
	propref_exampletable3 int(11) DEFAULT '0' NOT NULL,
	propref_exampletable3b int(11) DEFAULT '0' NOT NULL,
	propref_exampletable3c int(11) DEFAULT '0' NOT NULL,
	propref_exampletable4 int(11) DEFAULT '0' NOT NULL,
	propref_exampletable4b int(11) DEFAULT '0' NOT NULL,
	propref_exampletable4c int(11) DEFAULT '0' NOT NULL,
	propref_exampletable5 int(11) DEFAULT '0' NOT NULL,
	propref_exampletable6 int(11) DEFAULT '0' NOT NULL,
	propref_categories int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable1'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable1 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletable int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletableb int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletablec int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable2'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable2 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletable int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletableb int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletablec int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletabled int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable3'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable3 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletable int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletableb int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletablec int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	alt_title varchar(255) DEFAULT '' NOT NULL,
	date_update int(11) DEFAULT '0' NOT NULL,
	date_start int(11) DEFAULT '0' NOT NULL,
	date_end int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable4'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable4 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletable int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletableb int(11) DEFAULT '0' NOT NULL,
	proprefinv_exampletablec int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable5'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable5 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	foreign_table varchar(255) DEFAULT '' NOT NULL,
	foreign_field varchar(255) DEFAULT '' NOT NULL,
	foreign_uid tinyint(4) unsigned DEFAULT '0' NOT NULL,
	foreign_sortby tinyint(4) unsigned DEFAULT '0' NOT NULL,
	proprefinv_exampletable int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable6'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable6 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	foreign_table varchar(255) DEFAULT '' NOT NULL,
	foreign_field varchar(255) DEFAULT '' NOT NULL,
	foreign_uid tinyint(4) unsigned DEFAULT '0' NOT NULL,
	foreign_sortby tinyint(4) unsigned DEFAULT '0' NOT NULL,
	proprefinv_exampletable int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable7'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable7 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_exampletable_category'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_exampletable_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_testmodel1'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_testmodel1 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_testmodel4'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_testmodel4 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	propref_category int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_testmodel4_category'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_testmodel4_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_testmodel5'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_testmodel5 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	propref_categories int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'tx_airtable_dm_testmodel5_category'
# Sql file: SqlModelCrud.sql
#############################################################
CREATE TABLE tx_airtable_dm_testmodel5_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL,
	RType varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	sorting tinyint(4) unsigned DEFAULT '0' NOT NULL,
	disabled int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	propref_parent int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#############################################################
# Table structure for table 'pages'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE pages (
	prop_tx_airtable_field_1 varchar(255) DEFAULT '' NOT NULL,
	prop_tx_airtable_field_2 varchar(255) DEFAULT '' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'tt_content'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE tt_content (
	foreign_table varchar(255) DEFAULT '' NOT NULL,
	foreign_field varchar(255) DEFAULT '' NOT NULL,
	foreign_uid tinyint(4) unsigned DEFAULT '0' NOT NULL,
	foreign_sortby tinyint(4) unsigned DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_file'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_file (
	propref_categories int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_file_metadata'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_file_metadata (
	prop_tx_airtable_field_test varchar(255) DEFAULT '' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_file_reference'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_file_reference (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_file_storage'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_file_storage (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_filemounts'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_filemounts (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_note'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_note (
	prop_tx_airtable_modelname varchar(255) DEFAULT '' NOT NULL,
	propmedia_tx_airtable_files int(11) DEFAULT '0' NOT NULL,
	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'sys_redirect'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE sys_redirect (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'be_groups'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE be_groups (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'be_users'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE be_users (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'fe_groups'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE fe_groups (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);



#############################################################
# Table structure for table 'fe_users'
# Sql file: SqlModelCrudOverride.sql
#############################################################
CREATE TABLE fe_users (

	importprocess int(11) DEFAULT '0' NOT NULL,
	importolduid int(11) DEFAULT '0' NOT NULL,
	insertuidshash int(11) DEFAULT '0' NOT NULL
);




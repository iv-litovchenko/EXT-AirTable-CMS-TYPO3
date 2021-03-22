```
tx_data								propref_beauthor		foreign_table_where = ORDER BY uid ASC 
									propref_categories		foreign_table_where = AND tx_data_category.RType=###REC_FIELD_RType### ORDER BY RType ASC, title ASC 
									propref_parent			foreign_table_where = AND tx_data.RType=###REC_FIELD_RType### 
									
tx_data_category					propref_beauthor		foreign_table_where = ORDER BY uid ASC 
									propref_parent			foreign_table_where = AND tx_data.RType=###REC_FIELD_RType### 
									
tx_data_type						config					foreign_table_where = ORDER BY uid ASC 
									propref_group			foreign_table_where = ORDER BY title ASC 

tx_data_type_group					foreign_table_where = ORDER BY uid ASC 
									
sys_value 							foreign_table_where = ORDER BY RType ASC, uid ASC
tx_airtableexamples_dm_exampletable foreign_table_where = ORDER BY RType ASC, title ASC
```

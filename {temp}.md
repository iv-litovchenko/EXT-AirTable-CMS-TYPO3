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


```
SysAttribute.php
	
	# public function builderRefCustomValues(){
	#  	return $this->refProvider('sys_value_rows');
	# }
	


```

```

	
    /**
     * Custom value set (user func)
     * It is possible to use a selection from the database
     * return $config
     */
    public static function doItemsEntityType($config)
    {
        $itemList = [];
		foreach($GLOBALS['ENTITY_TYPES'] as $eK => $eConf){
			foreach($eConf['items'] as $k => $v){
				$config['items'][] = [$eConf['label'].' | '.$GLOBALS['LANG']->sL($v[0]), $eK.'___'.$v[1]];
			}
		}
        return $config;
    }
```

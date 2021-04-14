```
В доку
Свойства это внешние, атрибуты внутренние
->whereRowValues(['column1', 'column2'], '=', ['foo', 'bar']); // orWhereRowValues()
->where created_at between (new DateTime("2021-01-13"))->getTimestamp() and (new DateTime("2021-01-14"))->getTimestamp()
{f:if(condition: file.properties.title, then: file.properties.title, else: file.properties.name)}
{f:if(condition: item.current, then: ' active')}
Понравилась идея делать для upload files - прямоугольник
forPage $page, $perPage;
->whereRowValues(['column1', 'column2'], '=', ['foo', 'bar']);



Array
(
    [class] => 
    [attributes.attr_hellow1] => Element
    [attributes.attr_hellow2] => 
    [attributes.attr_hellow3] => 
    [TV.tv_hellow1] => Sheet 1
    [TV.tv_hellow2] => 
    [TV.tv_hellow3] => Sheet 2
    [TV.tvmedia_image] => 4
    [bild] => 1
    [settings.falimage] => 1
    [settings.sectionObjectAsClass2.60774705990f4289921309.mobile.TV.tv_hellow4] => Hellow (1) [TV.tv_hellow4] Mobile
    [settings.sectionObjectAsClass2.60774705990f4289921309.mobile.TV.tv_hellow5] => Hellow (1) [TV.tv_hellow4] Mobile 3
    [settings.sectionObjectAsClass2.6077470f67fd4317382563.landline.TV.tv_hellow4] => Hellow (1) [TV.tv_hellow4]
    [settings.sectionObjectAsClass2.6077470f67fd4317382563.landline.TV.tv_hellow5] => 
    [field1] => 
    [field2] => 
    [field3] => 
)



#
# Table structure for table 'tx_fluxcapacitor_domain_model_sheet'
#
CREATE TABLE tx_fluxcapacitor_domain_model_sheet (
      
		name varchar(255),
        sheet_label mediumtext,
		source_table varchar(255),
		source_field varchar(255),
        source_uid int(11) DEFAULT '0' NOT NULL,
        form_fields int(11) DEFAULT '0' NOT NULL,
        json_data text,

        PRIMARY KEY (uid),
        KEY parent (pid)
);

#
# Table structure for table 'tx_fluxcapacitor_domain_model_field'
#
CREATE TABLE tx_fluxcapacitor_domain_model_field (
        
        parent_field int(11) DEFAULT '0' NOT NULL,
        sheet int(11) DEFAULT '0' NOT NULL,
        field_name varchar(255),
        field_label mediumtext,
        field_type varchar(32),
        field_value text,
        field_options text,

        PRIMARY KEY (uid),
        KEY parent (pid),
        KEY parent_field (parent_field),
        KEY sheet (sheet),
        KEY field_value (field_value(32))
);

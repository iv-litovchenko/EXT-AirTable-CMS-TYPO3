## https://codebeautify.org/php-beautifier
```
1) 
SELECT backend_layout FROM `pages` GROUP BY backend_layout;
#update pages set backend_layout = REPLACE(backend_layout, 'pagets__ext_projiv_pagedefaultcontroller', 'pagets__tx_projiv_pagedefaultcontroller');
#update pages set backend_layout = REPLACE(backend_layout, 'pagets__ext_projiv_pagedefaultcontroller', 'pagets__tx_projiv_pagedefaultcontroller');

Подумать насчет названия propmedia, propref...
Название таблиц и ключей страниц и элементов контента TYPO3 правильное tx_airtable_domainmodel_...
Переименовать MySQL-ключи (убрать пробелы) по аналогии с "tx_typo3dummyextension_domain_model_typo3dummyextension"
Также переименуются! protected $prop_ext_air_table_modelname;

0 Нужно ли убрать постфикс "CONTROLLER"???? И Откуда он вообще береться?

1 Убрать:
$signatureLink!
tt_content.list.20.' . $signature . ' < tt_content.list.20.'.$signatureLink.'

3 Проверить префикс: ext_

4 БЕ-модули Signature AnnotationRegistrationExtTables.php
5 Проверить BE-модули где было в ручную генерировалось!

6 Ajax-поправить и проверить, проверить также смену действия для Ajax

7 Проверить TYPOSCRIPT

8 Посмотреть ключи плагинова как они пишутся


2) BaseUtility
BaseUtility.php - оптимизировать, убрать рефликсию класслов, передалть названия таблиц!
https://stackoverflow.com/questions/3014254/how-to-get-the-path-of-a-derived-class-from-an-inherited-method/3014344

3) Досравнить оставшиеся TCA
typo3conf/ext/air_table/Classes/Domain/Model/Content/_TCA-OLD
```

### ВАЖНО - КЛАССЫ НЕ НАСЛЕДУЮТСЯ ОТ LITOVCHENKO!!!!
```php
1) Убрать абстрактыне классы 
2) Сделать Fix-для классов моделей, виджетов и хелперов (sql_autoload_register)
Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?

			
						|| in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)
							| in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)
									Отдельная песьня...
									|| in_array('Litovchenko\AirTable\Domain\Model\Fields\AbstractField',$class_parents)){
									
									
```
### $TYPO3 - наслоедование переменной?
### Проверь ForeignWhere и другие аналогичные параметры - работают не првильно!



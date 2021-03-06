## https://codebeautify.org/php-beautifier
```
1) Подумать насчет названия propmedia, propref...
Также переименуются! protected $prop_ext_air_table_modelname;

1) Ключи для расширения моделей tx_....
2 Проверить префикс: ext_
3 БЕ-модули Signature AnnotationRegistrationExtTables.php
4 Проверить BE-модули где было в ручную генерировалось для Путей перекидки на экспорт/импорт!
5 Ajax-поправить и проверить, проверить также смену действия для Ajax
6 Проверить TYPOSCRIPT
7 Проверить в других версиях тайпы...

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



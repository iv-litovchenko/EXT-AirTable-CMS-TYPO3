## https://codebeautify.org/php-beautifier
```
1.1 Подумать насчет названия propmedia, propref... Также переименуются! protected $prop_ext_air_table_modelname;
1.2 Ключи для расширения моделей tx_....
1.3 Проверить префикс: ext_ (везде)
2 Проверить TYPOSCRIPT settings... (сделать combine - параметров для Ajax)

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
### Задокументировать!



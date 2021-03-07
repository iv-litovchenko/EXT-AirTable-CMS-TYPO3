## https://codebeautify.org/php-beautifier
```
Интересуют формы и роутер API
https://demo.silverstripe.org/?_ga=2.144332469.1839754345.1615063527-1118461346.1615063527
https://demo.silverstripe.org/admin/pages/edit/show/7205
https://docs.silverstripe.org/en/4/developer_guides/forms/
Посмотри отдельной Validator для Laravel Без таких сложностей - отдельной пакет!


Можно ли как-то преобразовать атрибут в объект FAL при выборке?
Куда деть "propmedia_"

Validators
	Usage
	Built-in Validators
	Custom Validator

1.1 Подумать насчет названия propmedia, propref... Также переименуются! protected $prop_ext_air_table_modelname;
1.2 Ключи для расширения моделей tx_....
1.3 Проверить префикс: ext_ (везде) - где он используется! Протестить модели EXT-расширений

3) Проверить TYPOSCRIPT settings... (сделать combine - параметров для Ajax)

4) BaseUtility
BaseUtility.php - оптимизировать, убрать рефликсию класслов, передалть названия таблиц!
https://stackoverflow.com/questions/3014254/how-to-get-the-path-of-a-derived-class-from-an-inherited-method/3014344

5) Досравнить оставшиеся TCA
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



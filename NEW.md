## https://codebeautify.org/php-beautifier
```
0) Посмотри отдельной Validator для Laravel Без таких сложностей - отдельной пакет!
Validators
	Usage
	Built-in Validators
	Custom Validator

1.1 Куда деть "propmedia_" Подумать насчет названия propmedia, propref... Также переименуются! protected $prop_ext_air_table_modelname;
1.2 Ключи для расширения моделей tx_....
1.3 Проверить префикс: ext_ (везде) - где он используется! Протестить модели EXT-расширений
```

### ВАЖНО - КЛАССЫ НЕ НАСЛЕДУЮТСЯ ОТ LITOVCHENKO!!!!
```php
1) Убрать абстрактыне классы 
2) Сделать Fix-для классов моделей, виджетов и хелперов (sql_autoload_register)
Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?

|| in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)
|| in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)

```
### $TYPO3 - наслоедование переменной?
### Проверь ForeignWhere и другие аналогичные параметры - работают не првильно!
### Задокументировать!


```
5) Проверить TYPOSCRIPT settings... (сделать combine - параметров для Ajax)

https://kronova.net/tutorials/typo3/extbase-fluid/get-all-constants-with-extbase-extension.html
Constants: 	plugin.myext.settings.detailPid = 123
Setup: 		plugin.myext.settings.detailPid = {$plugin.myext.settings.detailPid}
			$this->settings['detailPid']
			{settings.detailPid}
			
	
vhs:		{v:variable.typoscript(path: 'settings.pageUid')}
<v:variable.set name="userLoginPID" value="{v:variable.typoscript(path: 'site.pid.userLogin')}"/>
<v:variable.set name="userRegisterPID" value="{v:variable.typoscript(path: 'site.pid.userRegister')}"/>

plugin.tx_yourplugin {
  ...
  settings{
    somePage = {$PID_SOME_PAGE}
  }
  ...
}
--------------------

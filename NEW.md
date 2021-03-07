```
0) Посмотри отдельной Validator для Laravel Без таких сложностей - отдельной пакет!
Validators
0.1 Стандартные валидаторы
0.2 Кастомные валидаторы

1.1 Куда деть "propmedia_" Подумать насчет названия propmedia, propref... Также переименуются! protected $prop_ext_air_table_modelname;
1.2 Ключи для расширения моделей tx_....
1.3 Проверить префикс: ext_ (везде) - где он используется! Протестить модели EXT-расширений


use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int                $id               {primary}
 * @property OneHasMany|Book[]  $books            {1:m Book::$author}
 * @property OneHasMany|Book[]  $translatedBooks  {1:m Book::$translator}
 */
class Author extends Nextras\Orm\Entity\Entity
{}

/**
 * @property int     $id          {primary}
 * @property Author  $author      {m:1 Author::$books}
 * @property Author  $translator  {m:1 Author::$translatedBooks}
 */
class Book extends Nextras\Orm\Entity\Entity
{}


'realTableField' => [
    'relationType' => '\Namespace\ClassName',
],
'virtualField' => [
    'relationType' => ['\Namespace\ClassName','foreignKey'],
],
```

### ВАЖНО - КЛАССЫ НЕ НАСЛЕДУЮТСЯ ОТ LITOVCHENKO!!!!
```php
Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
|| in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)
|| in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)
```
### $TYPO3 - наслоедование переменной?
### Проверь ForeignWhere и другие аналогичные параметры - работают не првильно!
### Задокументировать!




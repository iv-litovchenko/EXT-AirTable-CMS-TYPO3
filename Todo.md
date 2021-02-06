``` 
<?php

Проверить и перенести это в документацию - новый шаблон страницы, новый элемент содержимого...
<f:debug>{_all}</f:debug>
<h2>Woohoo! My First TYPO3 Custom Element</h2>
<div class=”container”>
{data.bodytext -> f:format.html()}
</div>
```



		#Исходный текст
		#• $this->arguments is an associative array where you will find the values for all arguments you registered previously.
		#• $this->renderChildren() renders everything between the opening and closing tag of the view
		#helper and returns the rendered result (as string).
		#• $this->templateVariableContainer is an instance of TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer,
		#with which you have access to all variables currently available in the template, and can modify the variables
		#currently available in the template.


180, 210, 253 (aspect), 257 auth

plugin - то что расширяет, 
module это составляющее (не отделимое). по моему очевидно
виджет это видимый элемент, на сколько я понимаю, с внутренним функционалом, с точки зрения использования маленькая программка., 
а хелпер это просто вспомогательная функуция strtoupper к примеру




```
Атрибуты относятся к дополнительной информации об объекте. 
Свойства описывают характеристики объекта. 

Изменение атрибутов в TCA как?
Точка в названии атриботов ???

Атрибуты или свойства props, attrs, tbl_ field....??
Когда делаем две связи на одну и туже таблицу нужно создавать разные FK-ключи... на примере tx_marker_rows

+ Образец колонки будетм собирать в таблице sys_eav_attr_value и перекидывать в sys_eav_attr что бы правильно формировались FK-ключи

		
!!! Может писать EAV-характеристику TCA прямо в таблицу pages как колонку или в соответствующую сущность??
!!! Разбить на сущности сейчас пока только 1 сущность pages и как писать EXT-принадлежность к расширению?!

Попробовать все это выбирать
https://overcoder.net/q/403360/laravel-%D1%84%D0%B8%D0%BB%D1%8C%D1%82%D1%80-%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE-%D0%BA%D0%BE-%D0%BC%D0%BD%D0%BE%D0%B3%D0%B8%D0%BC-%D0%BF%D0%BE-%D0%BD%D0%B5%D1%81%D0%BA%D0%BE%D0%BB%D1%8C%D0%BA%D0%B8%D0%BC-%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%BD%D0%B8%D1%8F%D0%BC
https://www.reddit.com/r/laravel/comments/cmnvn0/eav_with_new_morph/
https://github.com/laravel/ideas/issues/1755
https://laravel.io/forum/05-14-2014-entity-attribute-value-database-schema-with-laravel
$categories = Category::whereHas('CategoryAttributes', function ($query) {
    $query->where('key', '=', 'color');
    $query->where('value','=', 'blue');
})->get();
$entities = Entity::with('attributes.values')->get()
https://www.digitalocean.com/community/tutorials/working-with-json-in-mysql
```

```
<?php
return [
	/*
	'page@ext-air-table-example@prop-room-count' => [
		'@AirTable\Field' => 'Input',
		'@AirTable\Field\Label' => 'Количество комнат',
		'@AirTable\Field\ReadOnly' => 0
	],
	'page@ext-air-table-example@prop-type_house' => [
		'@AirTable\Field' => 'Input',
		'@AirTable\Field\Label' => 'Серия дома',
		'@AirTable\Field\ReadOnly' => 0
	],
	'page@ext-air-table-example@prop-additionally' => [
		'@AirTable\Field' => 'Input',
		'@AirTable\Field\Label' => 'Дополнительно',
		'@AirTable\Field\ReadOnly' => 0
	],
	'node@ext-air-table-example@prop-1' => [
		'@AirTable\Field' => 'Input',
		'@irTable\Field\Label' => 'Название №1 (EXT ATE)',
			'@AirTable\Field\ReadOnly' => 0
	],
	'node@ext-air-table-example@prop-2' => [
		'@AirTable\Field' => 'Input',
		'@AirTable\Field\Label' => 'Название №2 (EXT ATE)',
		'@AirTable\Field\ReadOnly' => 0
	],
	'node@ext-air-table-example@prop-3' => [
		'@AirTable\Field' => 'Input',
		'@AirTable\Field\Label' => 'Название №3 (EXT ATE)',
		'@AirTable\Field\ReadOnly' => 0
	]
	*/
];
```

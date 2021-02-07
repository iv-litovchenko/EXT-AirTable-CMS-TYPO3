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
0) Посмотри почему не работает расширение модели связи префикс?
1) Названия колонок - props, attrs, tbl_, field....??
      Атрибуты относятся к дополнительной информации об объекте. 
      Свойства описывают характеристики объекта. 

+2) Ключ атрибута: точка в названии атриботов ???
+3) Ключ материала

4)!!! Изменение атрибутов в TCA как? Может писать EAV-характеристику TCA прямо в таблицу pages как колонку или в соответствующую сущность??

5) Выборка + keyBy???
https://progi.pro/laravel-eloquent-relationship-keyby-3889587
https://github.com/hulkur/laravel-hasmany-keyby

// searches cameras by user provided specifications
public function search(Request $request){
    $cameras = \App\Product::where([
        ['attributes->processor', 'like', $request->processor],
        ['attributes->sensor_type', 'like', $request->sensor_type],
        ['attributes->monitor_type', 'like', $request->monitor_type],
        ['attributes->scanning_system', 'like', $request->scanning_system]
    ])->get();
    return view('product.camera.search', ['cameras' => $cameras]);
}


6) Когда делаем две связи на одну и туже таблицу нужно создавать разные FK-ключи... на примере tx_marker_rows
7) TYPO3 8
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

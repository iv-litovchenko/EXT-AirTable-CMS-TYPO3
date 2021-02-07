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



```
1) Названия колонок - props, attrs, tbl_, field....??
      Атрибуты относятся к дополнительной информации об объекте. 
      Свойства описывают характеристики объекта. 
          Свойства (внутреннее). 
	Атрибуты (внешние). 
2) Выборка + keyBy???
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
```

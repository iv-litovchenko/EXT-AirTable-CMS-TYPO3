```
1) Названия колонок - prop (+), attrs, tbl_(+), field(-)....??
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

1. -> Добавил LIKE // Проверить работу связи EAV когда убрал where (будул ли свойства правильно выводится если у сущностей одинаковые ID)
2. Выборка Data + атрибуты + фильтрация
3. Поля (атрибуты) в контроллерах...
4. // Todo - общие поля для всех типов сущности На примере страниц - будут показываться во всех шаблонах

// ForeignWhere (сделать только для tx_data!)
// ForeignDefaults (сделать только для tx_data!)




<?php

Проверить и перенести это в документацию - новый шаблон страницы, новый элемент содержимого...
<f:debug>{_all} {data.uid}</f:debug>
<h2>Woohoo! My First TYPO3 Custom Element</h2>
<div class=”container”>
{data.bodytext -> f:format.html()}
</div>

<f:content colPos="2" /> <!--Page content-->
<f:content gridContainerId="{gridId}" gridColumn="1" /> <!--Gridelements content-->
<f:content model="Mynamespace\Myext\Domain\Model\NewTable" uid="2" /> <!--Record content-->

<f:object setup="lib.tx_myext_key.test" />
<f:marker uid="3" />

<f:vhsExtAirTable.AdminPanel />
<f:vhsExtAirTable.AdminBlockInfo text="12332" />

<f:vhsExtAirTable.EditWrapStart />
	-- content	
<f:vhsExtAirTable.EditWrapEnd />

<f:vhsExtAirTable.EditIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />
<f:vhsExtAirTable.EditIconInline model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />
<f:vhsExtAirTable.EditIconCenter model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />
<f:vhsExtAirTable.EditIconAbs model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit />

	// 'defaultFieldsForNewRecord'=>['title'=>'New record']
	// 'copyFieldsForNewRecord'=>['header']
	// 'editFieldsOnly'=>['header','hidden','CType']
	// 'hideNewIcon'=>1
	// 'hideDisableIcon'=>1
	// 'hideDeletedIcon'=>1
	// 'hideBufferIcon'=>1
	// 'styleLeft'=>10
	// 'styleTop'=>10
	// 'styleRight'=>10
	// 'styleBottom'=>10

<f:vhsExtAirTable.NewIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="186" title="195" />
<f:vhsExtAirTable.NewIconInline model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="186" title="195" />
<f:vhsExtAirTable.NewIconCenter model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="186" title="195" />
<f:vhsExtAirTable.NewIconAbs model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="186" title="195" />" />

<f:vhsExtAirTable.AdminPanel addToFooter="1" />
<f:vhsExtAirTable.AdminPanelNotepad />
<f:vhsExtAirTable.VhsInfo msg="v:menu" />
<f:vhsExtAirTable.VhsInfo msg="v:page.breadCrumb" />
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
\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName() 
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath()
if (TYPO3_MODE === 'BE')

\TYPO3\CMS\Core\Configuration\ExtensionConfiguration
$temporaryDirectory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
   ->get('my_extension_key', 'myVariable');
   
   \TYPO3\CMS\Core\Authentication\BackendUserAuthentication 
   \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
   
$GLOBALS['TSFE']->sL(); // Зависит от языка выбранного пользователем на переключателе языков во Frontend.
$GLOBALS['LANG']->sL(); // Зависит от выбранного языка у пользователя в административной панели (и если Backend-пользователь авторизован).

https://somethingphp.com/debugging-typo3/
\TYPO3\CMS\Core\Utility\DebugUtility::debug('VAR','HEADER','Debug');
\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('VAR', 'FormObject:');
   ```

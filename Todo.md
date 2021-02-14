```
1) shortCode // Snippet (chunk) // insert varibles (magento)
2) insert widget  (magento)
3) asside (include Aria)
4) tx_data vs tx_page?

https://ckeditor.com/docs/ckeditor4/latest/guide/widget_sdk_tutorial_2.html



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
<f:markerFal uid="42" as="row">
	
	<a href="<f:uri.image src='{row.uid_local}' />">
		One image: <f:image src="{row.uid_local}" alt="alt text" width="100" />
	</a>
	
</f:markerFal>

<hr />

<f:markerFal uid="45" as="rows">
	
	<f:for each="{rows}" as="row" key="itemkey">
		<a href="<f:uri.image src='{row.uid_local}' />">
			{itemkey+1}.<f:image src="{row.uid_local}" alt="alt text" width="100" /><br />
		</a>
	</f:for>
	
</f:markerFal>

<f:vhsExtAirTable.AdminPanel />
<f:vhsExtAirTable.AdminInfobox type="warning" title="#" message="#" />
<f:vhsExtAirTable.AdminInfobox type="info" title="#" message="#" />
<f:vhsExtAirTable.AdminInfobox type="error" title="#" message="#" />

<f:vhsExtAirTable.EditWrapStart />
	-- content	
<f:vhsExtAirTable.EditWrapEnd />

<f:vhsExtAirTable.EditIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />
<f:vhsExtAirTable.EditIconInline model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />
<f:vhsExtAirTable.EditIconCenter model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />
<f:vhsExtAirTable.EditIconAbs model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="#" title="Edit" />

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
<f:vhsExtAirTable.NewIconAbs model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="186" title="195" />

<f:vhsExtAirTable.AdminPanel addToFooter="1" />
<f:vhsExtAirTable.AdminPanelTools />
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

// Debug
https://somethingphp.com/debugging-typo3/
\TYPO3\CMS\Core\Utility\DebugUtility::debug('VAR','HEADER','Debug');
\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('VAR', 'FormObject:');

// Backend (CSS, JAVSCRIPT)
if (TYPO3_MODE === 'BE') {
   $renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
   $renderer->addCssFile('EXT:my_ext_key/Resources/Public/Backend/Css/.css');
   $renderer->addJsFile('EXT:my_ext_key/Resources/Public/Backend/Js/.js', 'text/javascript');
}
   ```


## Зарезервированные переменные 
Данные переменные регистрируются автоматически при каждом создании (выполнении) объекта Smarty_Cobj и поставляются в шаблон – другие любые данные можно извлечь, к примеру, через 1) {mysql_exec - поддержка исключена}, 2) создание и подключение мини-контроллера, 3) разработку дополнительного самостоятельного плагина под Smarty, 4) указание рабочей области контроллера ({controllerload} {dataload}).

Примечание! Данные переменные всегда поставляются в шаблон (не зависимо от того, кэширован он или нет). Даже если у нас user_INT-плагин (условно говоря) – то данные переменные все равно будут доступны.

Постоянные переменные:

```
// Дата страницы 
{$t3_page.uid} – содержит данные о текущей странице (оставлено – и есть альтернатива {data source="page:title"}) {$t3_page.title}... {$t3_page.nav_title}... 

// Дата элемента контента 
{$t3_data.header} – содержит данные текущей выборки  
{$t3_data.bodytext|format:"lib.myParseFunc"} 

// Если вывод идет в режиме "eIdAjax" if (TYPO3_MODE_eIdAjax == 1) { return 1; } else { return 0; } 
{$t3_mode_eIdAjax} - 1 - да, 0 - нет (в основном нужно для создания <div>-оберток, хотя лучше это делать на основе jQuery  
```

## Получить значения доступные во Fronend - аналог .data в TS 
```
{data source="page:title"} // – получить название страницы     
{data source="DB:tt_content_gallery:1:title"} // – получиь из таблицы     
{data source="DB:TSFE:lang"} // – получить из масива 
```
## TYPO3-константы
```
{env name="TYPO3_SITE_URL"} // Получить значение TYPO3-константы - (в примере обычно для используется для <base href="">)
```

##2. smarty.configLoad для Smarty (нужен ли он?);
```
	$("div.sc_UserComment_wrap").wrap( "<div class='sc_UserComment_wrap_Ajax' style='background: url(ajax-loader.gif) no-repeat;'></div>" );
	$('form#sc_UserComment').live('submit', function(){
		$("input#sc_UserComment_Submit").attr("disabled", true); // input submit
		$("div.sc_UserComment_wrap_Ajax").fadeTo( "fast" , 0.5 );
			$.ajax({  
				type: "POST",
				data: $("form#sc_UserComment").serializeArray(), // data: ({username : 123, password : 123}),
				url: window.location.href + ((window.location.href.indexOf('?') == -1) ? '?' : '&') + "eIdAjax=100", // что добавить "?" или "&"
				success: function(html){ 
                    $("div.sc_UserComment_wrap").replaceWith(html);   // wrapper form
					$("div.sc_UserComment_wrap_Ajax").stop(true,true).fadeTo( "fast" , 1 );
                }  
            });
		return false;
	}); 
```

```
shortCode // Snippet (chunk) ???
insert varibles, insert widget
asside (include Aria)
tx_data vs tx_page?
https://ckeditor.com/docs/ckeditor4/latest/guide/widget_sdk_tutorial_2.html
https://stackoverflow.com/questions/26393581/ckeditor-4-dropdown-button-to-insert-placeholder
https://www.abidibo.net/blog/2017/06/14/add-apps-contents-inside-ckeditor-django-resckeditor/
https://ckeditor.com/docs/ckeditor4/latest/examples/placeholder.html#!/guide/dev_placeholder
https://ckeditor.com/cke4/addon/placeholder_select

https://sunel.github.io/eav/master/er.html#eav
entities
attributes
default_int
attribute_options


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
   $renderer->addCssFile('EXT:my_ext_key/Resources/Public/Backend/Css/.css?'.time());
   $renderer->addJsFile('EXT:my_ext_key/Resources/Public/Backend/Js/.js?'.time(), 'text/javascript');
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
## Подгрузка данных в шаблон
```
Подгрузка данных в шаблон
[поддержка исключена] Запуск контроллера с поставкой готовых данных (рекомендуется не более 1 контроллера на шаблон)
 
						<!--Подключаем контроллер-->
						{controllerload action="form_thisSitePoworedTypo3->main" assign="result"}
						<form id="form_thisSitePoworedTypo3" class="box style fp-form clearfix" method="post">
							<h3 class="module-title">Проверить, работает ли этот сайт на TYPO3?</h3>
							{if $result.powered_Yes == 1}
								<span style="color: green;">Сайт работает на<br /> CMS TYPO3!</span>
								<p><input id="elem_thisSitePoworedTypo3" type="submit" value="Проверить еще..."></p>
							{elseif $result.powered_Yes == 2}
								<span style="color: red;">Признаков использования CMS TYPO3 не найдено (возможно сайт не доступен)!</span>
								<p><input id="elem_thisSitePoworedTypo3" type="submit" value="Проверить еще..."></p>
							{else}
								{if $result.error.urlAdress == 1} 
									<p><input id="elem_thisSitePoworedTypo3_value" name="urlAdress" type="text" style="border-color: red;" placeholder="http://site-name.com/"></p>
								{else}
									<p><input id="elem_thisSitePoworedTypo3_value" name="urlAdress" type="text" placeholder="http://site-name.com/"></p>
								{/if}
								<p><input id="elem_thisSitePoworedTypo3" type="submit" value="Проверить"></p>
							{/if}
						</form>
						
						
						
<?php
/**
 *
 * Контроллеры (по мере необходимости)
 *
 */

class form_thisSitePoworedTypo3 {

	/**
	 * Проверка работы данного сайта на TYPO3?
	 * Для Ajax
	*/
	function main($content, $conf){
		if (isset($GLOBALS['_POST']['urlAdress'])){
			if ($GLOBALS['_POST']['urlAdress'] == null){
				$assignContent['error']['urlAdress'] = 1;
			} else {
				////////////////////////////////////////////////
				// Делаем запрос к сайту по указанному адресу
				////////////////////////////////////////////////
				$urlExternal = $GLOBALS['_POST']['urlAdress'];
				$pageContent = DBTOOLS::file_get_content($urlExternal, 3);
				if (strstr($pageContent, "This website is powered by TYPO3 - inspiring people to share!")){
					$assignContent['powered_Yes'] = 1;
					
					////////////////////////////////////////////////
					// заносим в БД
					////////////////////////////////////////////////
					$insertId = DB::run()->table("tt_infoblock_content_powored_by_typo3")->insert(array(
						"pid"		=> 44,
						"title"		=> $urlExternal,
						"crdate"	=> time(),
					))->exec();
					
					////////////////////////////////////////////////
					// отпраляем письмо мне на почту
					////////////////////////////////////////////////
					$emailAdmin = DB::run()->table("tx_web_settings")->select("email_admin")->whereIdRecord(1)->exec();
					$emailAdmin = $emailAdmin[0]['email_admin'];
					
						$mail = DBTOOLS::mail("THIS_WEB_SITE_POWERED_BY_TYPO3");
						$mail->setMarker(array("urlAdress" => $GLOBALS['_POST']['urlAdress']));
						$mail->setTo($emailAdmin);
						$mail->send();
					
				} else {
						// Если сайт не удалось получить
					$assignContent['powered_Yes'] = 2;
				}
			}
		}
		return $assignContent;
	}
	
} 
?> 
[поддержка исключена] Подгрузка чистых данных
 
				<!--Подгружка поставщика (мои расширения)-->
				{dataload model="tt_infoblock_content_myext_projects->getAllExt" assign="dbArray"}
				{foreach from=$dbArray item=myExt key=i}
						
						{$myExt.title}
						
				{/foreach}


class tt_infoblock_content_myext_projects {

		// Получить список всех проектов
	function getAllExt($content, $conf){
		return DB::run()->table("tt_infoblock_content_myext_projects")
						->select('*')
						->orderASC("sorting")
						->exec();
	}

} 
```

##2. smarty.configLoad для Smarty (нужен ли он?);

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

# EXT: AirTable (CMS TYPO3)

## CMS TYPO3 #1. Демо-сайт. Начало разработки
[![Watch the video](https://img.youtube.com/vi/5LqxpCbRNXw/maxresdefault.jpg)](https://youtu.be/5LqxpCbRNXw)

## CMS TYPO3 #2. Настройка сайта
[![Watch the video](https://img.youtube.com/vi/CZAhgC9YXsM/maxresdefault.jpg)](https://youtu.be/CZAhgC9YXsM)

A set of tools for creating your site based on class annotations (nowadays magic variable $ TYPO3 = []). Works in versions TYPO3 v10 (not tested in other versions for a long time). The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway. Основная задача данного расширения - одинаково струрированный контент на проекте. [RU]

## Table of content

* 01 [Demo site online](#01-demo-site-online)
* 02 [How to install?](#02-how-to-install)
* 03 [Extension structure](#03-extension-structure)
* 04 [Register a new admin module](#04-register-a-new-admin-module)
* 05 [Register a new page template](#05-register-a-new-page-template)
* 06 [Register a new content element](#06-register-a-new-content-element)
* 07 [Additional View Helper](#07-additional-view-helper)
* 08 [Register View Helper](#08-register-view-helper)
* 09 [Register Widget (Component - View Helper with controller and template)](#09-register-widget-component---view-helper-with-controller-and-template)
* 10 [Register a new model (CRUD)](#10-register-a-new-model-crud)
* 11 [Standard CRUD-models registered in the system for working with records](#11-standard-crud-models-registered-in-the-system-for-working-with-records)
* 12 [Extending an existing model](#12-extending-an-existing-model)
* 13 [List records (module)](#13-list-records-module)
* 14 [Export records Xls|Csv (module)](#14-export-records-xlscsv-module)
* 15 [Import records Xls|Csv (module)](#15-import-records-xlscsv-module)
* 16 [Useful functions (Extbase, Fluid, TS)](#16-useful-functions-extbase-fluid-ts)
* 17 [Database queries: SELECT, INSERT, UPDATE, DELETE, RELATIONSHIPS (Eloquent ORM)](#17-database-queries-select-insert-update-delete-relationships-eloquent-orm)
* 17.2 [VALIDATION (Laravel)](#validation-laravel)
* 18 [Frontend editing](#18-frontend-editing)
* 19 [Useful settings in "typo3conf/LocalConfiguration.php"](#19-useful-settings-in-typo3conflocalconfigurationphp)
* 20 [Extbase Frontend AJAX (http://your-site.com/?eIdAjax=1)](#20-extbase-frontend-ajax-httpyour-sitecomeidajax1)
* 21 [Extbase Example of working with forms](#21-extbase-example-of-working-with-forms)
* [Functional development plans](#functional-development-plans)

## 07 Additional View Helper

### Get content (Xclass)
```
<f:vhsExtAirTable.content.page column="2" /> <!-- Page content -->
<f:vhsExtAirTable.content.record model="Mynamespace\Myext\Domain\Model\NewTable" uid="2" /> <!-- Record content // Todo -->
<f:vhsExtAirTable.content.grid area="2" /> <!-- Gridelements content -->
```

### Marker
```
<!--Input, Text, Text.Rte, Text.Code.Html, Text.Code.TypoScript-->
<f:vhsExtAirTable.marker uid="3" />

<!--Media_1, Media_M-->
<f:vhsExtAirTable.markerMedia uid="45" as="row || rows">
  <f:for each="{rows}" as="row" key="itemkey">
    <a href="<f:uri.image src='{row.file.uid}' />">
      {itemkey+1}.<f:image src="{row.file.uid}" alt="alt text" width="100" /><br />
    </a>
  </f:for>
</f:vhsExtAirTable.markerMedia>
```

### For administrator 
```
<f:vhsExtAirTable.adminPanel isFooter="0 || 1" />
<f:vhsExtAirTable.adminPanelTools />

<f:vhsExtAirTable.adminInfobox title="Infobox" type="warning || info || error"> --- site admin content  --- </f:vhsExtAirTable.adminInfobox>
<f:vhsExtAirTable.editWrap> --- site content --- </f:vhsExtAirTable.editWrap>

<f:vhsExtAirTable.editIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="100" title="Edit" />
<f:vhsExtAirTable.editIconInline model="Pages || TtContent || Data || Model" recordId="100" title="Edit" />
<f:vhsExtAirTable.editIconCenter model="Pages || TtContent || Data || Model" recordId="100" title="Edit" />
<f:vhsExtAirTable.editIconAbs model="Pages || TtContent || Data || Model" recordId="100" title="Edit" />

<f:vhsExtAirTable.newIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="200" title="New" />
<f:vhsExtAirTable.newIconInline model="Pages || TtContent || Data || Model" pid="200" title="New" />
<f:vhsExtAirTable.newIconCenter model="Pages || TtContent || Data || Model" pid="200" title="New" />
<f:vhsExtAirTable.newIconAbs model="Pages || TtContent || Data || Model" pid="200" title="New" />

<!--editIcon & newIcon options-->
<f:vhsExtAirTable.editIcon ...
  defaultFieldsForNewRecord="{title:'New record',nav_title:'New record'}"
  copyFieldsForNewRecord="header,CType"
  editFieldsOnly="header,CType"
  hideNewIcon="1"
  hideDisableIcon="1"
  hideDeletedIcon="1"
  hideBufferIcon="1"
  styleLeft="10" <!--only for newIconAbs-->
  styleTop="10" <!--only for newIconAbs-->
  styleRight="10" <!--only for newIconAbs-->
  styleBottom="10" <!--only for newIconAbs-->
/>

<f:vhsExtAirTable.editIconsForMenu uidPattern="elem_*" styleDirection="left">
  <v:page.menu expandAll="0" levels="2" substElementUid="1" /> <!-- <li id="elem_id(page id)"><!-- INSERT EDIT ICON--><a href=""></a></li> -->
</f:vhsExtAirTable.editIconsForMenu>
```

## 16 Useful functions (Extbase, Fluid, TS) 

### Useful notes - Hooks
```php

```

### Useful notes - Extbase Controller
```php
// Просмотреть все функции Extbase и расширений core в папке typo3/sysext/
class ExtbaseApi{}

+ mail()
xml,
typoscript
runHelper
+ getSiteConfig, getTsConfig, getExtConfig
+ getData, getPage
utility (ar, other)
file
cache
+ render, assign
+ requst, respone
+ log
+ debug
auth
cookie
forms
+ flashmessage
+ context (controllerName, actionName, extenstionName)
+ link (Frontend, Backend), redirect, forward

$this->database->;
$this->crud->query = $this->crud->query->withoutGlobalScopes();
$this->crud->model->clearGlobalScopes();

// if ajax mode
if(TYPO3_AJAX_MODE === true) {
    // ...
}

// if edit mode
if(TYPO3_EDIT_MODE === true) {
    // ...
}

// Виртуальные страницы
// $titleProvider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Litovchenko\AirTable\PageRender\ExtensionsPageTitleProvider::class);
// $titleProvider->setTitle("Title from controller action");
$GLOBALS['TSFE']->pageRenderer->setTitle('Hellow word!'); // "Title from controller action" // $this->objectManager->get(\TYPO3\CMS\Core\Page\PageRenderer::class)->setTitle('My title');
$GLOBALS['TSFE']->pageRenderer->setMetaTag('name','description','content description');
$GLOBALS['TSFE']->pageRenderer->setMetaTag('name','keywords','content keywords');
$GLOBALS['TSFE']->pageRenderer->addHeaderData('<script>alert(1);</script>'); // addFooterData
$GLOBALS['TSFE']->pageRenderer->addJsFile('/_js.js', 'text/javascript', false); // addJsFile // addJsFooterFile // addJsInlineCode // addJsFooterInlineCode
$GLOBALS['TSFE']->pageRenderer->addCssFile('/_css.css'); // addCssInlineBlock
		
// set_cache() нужно устанавливать после проверки нужных параметров, в противном случае это может привести к затоплению БД
// http://site.com/router/page/(1-10000....)
// таким способом генерируются кэшированные виртуальные страницы
$GLOBALS['TSFE']->set_cache(); // Проверили все параметры - разрешаем кэширование страниц (+1 экземпляр) - Альтернатива "cHash"
$GLOBALS['TSFE']->set_cache_timeout_default(300);
$GLOBALS['TSFE']->addCacheTags(['myTag_travelsAction']); // If you need to manually reset the cache 
$GLOBALS['TSFE']->addBreadcrumbItem(); // Todo 
// $GLOBALS['TSFE']->setPageNotFoundAndExit(' Msg // Todo '); // throw new \Exception('Invalid data'); // $this->throwStatus(404, 'FE', 'Msg');
```

### Useful notes - Fluid
```html
*********************
* Assets
*********************
<f:section name="HeaderAssets">
    <!-- zusätzliche Inhalte im <head> -->
</f:section>

<v:page.header name="defaultHeader">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</v:page.header>
<v:page.header.meta name="keywords" content="{page.keywords}" />
<v:page.header.meta name="description" content="{page.description}" />
<v:page.header.meta name="og:title" content="{page.title}" />
<v:page.header.meta name="og:type" content="article" />
<v:page.header.meta name="og:url" content="{v:page.absoluteUrl()}" />
<v:page.header.meta name="og:description" content="{page.description}" />
<v:page.header.meta name="apple-mobile-web-app-capable" content="yes" />
<v:asset.style path="{f:uri.resource(path: 'CSS/style.css')}" group="fluidcontentyoutube" name="style" />
<v:asset.style path="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" name="bootstrap-css" external="1" />
<v:asset.style path="EXT:rico_provider/Resources/Public/Fonts/font-awesome-4.7.0/css/font-awesome.css" name="font-awesome" />
<v:asset.style path="EXT:rico_provider_oxid/Resources/Public/Css/custom.css" name="custom-css" />
<v:asset.script standalone="1" external="1" path="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" name="jquery" />
<v:asset.script standalone="0" dependencies="jquery" name="searchbox-js">
$(function() {
    $(window).scroll(function() { });
});	
</v:asset.script>

<f:section name="FooterAssets">
    <!-- zusätzliche Inhalte vor </body> -->
</f:section>

*********************
* Info
*********************

<f:if condition="{v:page.info(field: 'uid')} == '21'">
   <f:then>
      Shows only if page ID equals 21.
   </f:then>
</f:if>

{f:if(condition: file.properties.title, then: file.properties.title, else: file.properties.name)}
{f:if(condition: item.current, then: ' active')}

*********************
* Partial
*********************

<f:render partial="Pages/Missing.html" optional="1" default="Partial 1 not found" />
<f:render partial="Pages/AlsoMissing.html" optional="1">
   Partial 2 not found
</f:render>

*********************
* If-elseif-else
*********************

<f:if condition="{var} > 100">
   <f:then> Overflow! </f:then>
   <f:else if="{var} > 90"> Danger! </f:else>
   <f:else if="{var} > 50"> Careful now. </f:else>
   <f:else> All good! </f:else>
</f:if>

*********************
* Case
*********************

<f:switch expression="{person.gender}">
   <f:case value="male">Mr.</f:case>
   <f:case value="female">Mrs.</f:case>
   <f:defaultCase>Mr. / Mrs.</f:defaultCase>
</f:switch>

*********************
* For
*********************

<f:for each="{rows}" as="row" key="itemkey">
   <a href="<f:uri.image src='{row.file.uid}' />">
      {itemkey+1}.
      <f:image src="{row.file.uid}" alt="alt text" width="100" />
      <br />
   </a>
</f:for>

*********************
* Comment
*********************

<f:comment>
   ---
</f:comment>

*********************
* Links
*********************

<!-- Backend module-->
<f:be.link route="web_ts || Ext.Myext.Modules.NewModule2.index" parameters="{id: 92}">Go to module</f:be.link>

<!-- Page -->
<f:link.page pageUid="123" additionalParams="{foo:bar}">Klick me!</f:link.page>

<!-- Action -->
<f:link.action action="index" arguments="{page:1}">-- Link --</f:link.action>
<f:link.action action="edit" class="btn btn-default btn-sm">-- Link --</f:link.action>

<!-- 
   Tested only in TYPO3 v10!
   Switching to another controller is not easy!
   Route name: Ext.[***extension***].[***subfolder***].[***controller***].[***action***]
   Имя роутера - это pageId.controller.action. urlConverter преобразует в ЧПУ (как со страницами,
   что бы сделать ссылку, мы ищем ID-страницы, а не маршрут. Здесь же ищем контроллер и действие.
   // Todo: RouterList (продумать вывод списка маршрутов для отдалки)
-->
<f:link.action pageUid="1 || self" route="Ext.Myext.Pages.Default.index"> --TEXT-- </f:link.action>
<f:link.action pageUid="1 || self" route="Ext.Myext.Pages.Default.travels"> --TEXT-- </f:link.action>
<f:uri.action route="Ext.Myext.Pages.Default.travelView" arguments="{uid:5}" />

*********************
* Image, resource
*********************

<f:uri.image src="{row.propmedia_thumbnail.file.uid}" />
<a href="{f:uri.image(src:image.file.uid)}" data-fancybox="gallery"> </a>
<img src="{f:uri.image(src:image.file.uid, width:272, height:'300c')}">

*********************
* Form
*********************
<f:form.checkboxproperty="cms" multiple="1" value="TYPO3" />
<f:form.checkboxproperty="cms" multiple="1" value="Word Press" />
<f:form.checkboxproperty="cms" multiple="1" value="Drupal" />

*********************
* Other
*********************
<f:format.html parseFuncTSPath="lib.parseFunc">{bodytext}</f:format.html>
<f:format.date format="d.m.Y H:i">1265798455</f:format.date>
<f:variablename="myvariable">My variable's content</f:variable>
<f:variablename="myvariable" value="My variable's content"/>
{f:variable(name: 'myvariable', value: 'My variable\'s content')}
{myoriginalvariable -> f:variable.set(name: 'mynewvariable')}


*********************
* Flux forms
*********************

<f:layout name="Default" />

<f:section name="Configuration">

   <flux:form id="mycontentelement" label="My Content Element" description=" -- -- "  extensionName="Vendor.Extension">

      <!-- Настройки формы -->	
      <flux:form.option name="static" value="0" /> <!--1 когда форма польностью статичкаа и будет работать при кэшировании-->
      <flux:form.option.icon value="EXT:myext/Resources/Public/Icons/Module-Icon-Backup.png" />
      <flux:form.option.group value="special" /> <!-- Wizard group -->
      <flux:form.option.sorting value="1" /> <!-- Wizard sort ??? -->

      <!-- Вкладки -->		
      <flux:form.sheet name="options2" label="Twe">
         <!-- Поля -->
      </flux:form.sheet>

      <!-- Поля -->
      <flux:field type="input" exclude="0" config="{size: 3, eval: 'trim, int', default: 1}" /><!-- Можно конфигурацию передавать так-->
      <flux:field.DateTime name="DateTime" label="DateTime" required="0" clear="1" />
      <flux:field.input name="url" required="0" />
      <flux:field.text name="text" label="text" required="0" clear="1" />
      <flux:field.none name="none" label="none" required="0" clear="1" />
      <flux:field.checkbox name="settings.checkbox" label="checkbox" default="0" />
      <flux:field.select name="settings.select" label="select" items="left,right" default="left" emptyOption="2"/>
      <flux:field.radio name="settings.radio" label="radio" items="left,right" default="left" emptyOption="2"/>
      <flux:field.file name="file" label="file" useFalRelation="1" />
      <flux:field.inline.fal name="inline.fal" label="inline.fal" collapseAll="1" expandSingle="1" allowedExtensions="jpg,jpeg,png,svg" />
      <flux:field.inline name="inline" table="tt_content" />
      <flux:field.relation name="relation" table="tt_content" />
      <flux:field.MultiRelation name="MultiRelation" table="tt_content" />
      <flux:field.tree.category name="tree.category" label="tree.category" showThumbs="0" expandAll="1" />
      <flux:field.custom name="custom" label="" requestUpdate="1" userFunc="FluidTYPO3\\Flux\\UserFunction\\HtmlOutput->renderField}" />
      <flux:field.custom displayCond="REC:NEW:true" name="custom"> <!-- displayCond="FIELD:parentRec.uid:>:1" -->
         <div class="alert alert-info" role="alert">
            <h2>Hellow Word.</h2>
            <p>--- TEXT ---</p>
         </div>
      </flux:field.custom>
      <flux:field.userFunc name="" label="" extensionName="" userFunc="" />
      <flux:field.controllerActions name="" label="" extensionName="" controllerExtensionName="" pluginName="" controllerName="" actions="{foo: 'bar'}" />

      <!-- Поддержка исключена!!! -->
      <flux:form.container name="settings.name" label="Name">
         <!-- Поля -->
      </flux:form.container>

      <!-- Секции -->
      <flux:form.section name="settings.sectionObjectAsClass2" label="Telephone numbers 2">
         <flux:form.object name="custom">
            <!-- Поля -->
         </flux:form.object>
         <flux:form.object name="mobile" label="Mobile">
            <!-- Поля -->
         </flux:form.object>
         <flux:form.object name="landline" label="Landline">
            <!-- Поля -->
         </flux:form.object>
      </flux:form.section>
	  
      <!-- Примеры табов/аккордионов (бесконечное количество секций) -->
      <flux:form.sheet name="tabs">
         <flux:form.section name="tabs">
            <flux:form.object name="tab">
               <flux:field.input name="title" />
               <flux:field.input name="class" />
               <flux:field.checkbox name="active" />
            </flux:form.object>
         </flux:form.section>
      </flux:form.sheet>
      <f:if condition="{tabs}">
         <f:for each="{tabs}" as="tab" iteration="iteration">
            <flux:form.content name="content.{iteration.index}" label="Tab {iteration.cycle}" />
         </f:for>
      </f:if>
	  
   </flux:form>
   
   <!-- 1 Строчная сетка (использовать либо это, либо <flux:grid>) -->
   <flux:form.content name="content.{iteration.index}" label="Tab {iteration.cycle}" />
   <flux:form.content name="mycontent.1" label="mycontent1" />
   <flux:form.content name="mycontent.2" label="mycontent2" />
   <flux:form.content name="mycontent.3" label="mycontent3" />
   
   <!-- Произвольные сетки -->
   <flux:grid>
      <flux:grid.row>
         <flux:grid.column name="mycontentA" label="mycontentA" colPos="0">
            <flux:form.variable name="allowedContentTypes" value="textmedia"/>
            <flux:form.variable name="Fluidcontent" value="{allowedContentTypes: 'Vendor.ExtensionName:HeroImage.html'}" />
         </flux:grid.column>
         <flux:grid.column name="mycontentB" label="mycontentB" colPos="1" />
      </flux:grid.row>
      <flux:grid.row>
         <flux:grid.column name="mycontentC" label="mycontentC" colPos="2" colspan="2" rowspan="1" style="width: 300px; height: 300px;" />
      </flux:grid.row>
   </flux:grid>

   <!-- Не задокументированное (дополнительные варианты создания колонок) -->
   <flux:form.section name="columns" gridMode="rows || cols">
      <flux:form.object name="column" label="Column" contentContainer="1" />
   </flux:form.section>
   <flux:form.section name="columns">
      <flux:form.object name="column" label="Column">
         <flux:form.object.columnPosition />
      </flux:form.object>
   </flux:form.section>

</f:section>

<f:section name="Preview">

   <f:debug title="Debug" inline="true">{_all}</f:debug>
   <p>YouTube: {url}</p>

</f:section>

<f:section name="Main">

   <!-- Варианты как получить контент-->
   <v:content.render column="1" /> <!-- PAGE -->
   <flux:content.render area="mycontentB" /> <!-- CONTENT -->

   <!-- Примеры табов/аккордионов -->
   <div class="flux grid01Tabs">
      <f:render section="Tabs" arguments="{_all}" />
      <div class="tabs-content" data-tabs-content="tabs-{record.uid}">
         <f:if condition="{tabs}">
            <f:for each="{tabs}" as="tab" iteration="iteration">
               <div class="tabs-panel {f:if(condition: '{tab.tab.active} == 1', then: 'is-active')}" id="panel-{record.uid}-{iteration.index}">
                  <flux:content.render area="content.{iteration.index}" />
               </div>
            </f:for>
         </f:if>
      </div>
   </div>
   <!-- / tabWrap -->

</f:section>

<f:section name="Tabs">
   <f:if condition="{tabs}">
      <ul class="tabs" data-tabs id="tabs-{record.uid}">
         <f:for each="{tabs}" as="tab" iteration="iteration">
            <li class="tabs-title {f:if(condition: '{tab.tab.active} == 1', then: 'is-active')}">
               <a href="#panel-{record.uid}-{iteration.index}" aria-selected="true">{tab.tab.title}</a>
            </li>
         </f:for>
      </ul>
   </f:if>
</f:section>

```

### Useful notes - TypoScript
```

page = PAGE
page {
	typeNum = 0

	config {
		noPageTitle = 1
	}

	headTag (
    	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<script type="text/javascript" src="/{$plugin.tx_easytemplate.view.resPathPublic}Javascripts/jquery-2.0.3.min.js"></script>
		<script src="/{$plugin.tx_easytemplate.view.resPathPublic}Javascripts/modernizr.js"></script>
		<!--[if lte IE 9]>
		<script type="text/javascript" src="/{$plugin.tx_easytemplate.view.resPathPublic}Javascripts/html5shiv.js"></script>
		<![endif]-->
	)
	
	headerData.10 = TEXT
	headerData.10.wrap = <title>-- TEXT --</title>
	headerData.20 = TEXT 
	headerData.20.value ( 
		
	)
	
	meta { 
		X-UA-Compatible = IE=edge
		X-UA-Compatible.attribute = http-equiv
		og:site_name = TYPO3
		og:site_name.attribute = property
		description = -- TEXT --
		keywords = -- TEXT --
		page-topic = -- TEXT --
		dc.title = -- TEXT --
		dc.description = -- TEXT --
		abstract = -- TEXT --
		author = -- TEXT --
		robots = index,follow
	}

	bodyTag = <body>
	bodyTagAdd = class="example"

	includeCSS {
		main = EXT:myext/Resources/Public/Css/print.css
		print = EXT:myext/Resources/Public/Css/print.css
		print.media = print
	}

	includeJS {}
	includeJSFooter {
		main = EXT:myext/Resources/Public/Javascript/main.js
		bootstrap = EXT:myext/Resources/Public/Javascript/...js
	}

	footerData.10 = TEXT
	footerData.10.value = <script src="...."></script>
	footerData.20 = TEXT
	footerData.20.value = <!-- Hello from the comment! -->
}

# CONDITIONS
https://www.koller-webprogramming.ch/tipps-tricks/typoscript/bedingungen/

plugin.tx_cssstyledcontent._CSS_DEFAULT_STYLE >

lib.stdheader {
	10 {
		1.dataWrap = <h1>|</h1>
		1.dataWrap.insertData = 1
	}

	stdWrap {
		dataWrap >
	}
}

tt_content {
	stdWrap {
		innerWrap >
		dataWrap >
		prefixComment >
	}

	default {
		prefixComment >
	}

	text {
		stdWrap.outerWrap = <div class="text">|</div>
	}

	textpic { }

	image { }

	media { }

	stdWrap {
		innerWrap {
		}

		outerWrap {
		}
	}

}

```

## 17 Database queries: SELECT, INSERT, UPDATE, DELETE, RELATIONSHIPS (Eloquent ORM)

### VALIDATION (Laravel)

```php
<?php
////////////////////////////////////////////////////////////////////////////////////////
// VALIDATION
// v1) ModelCrud::validator($data=[], $context='default', $params=[], called_class()); // see "public static function validationRules($params=[])"
// v2) ModelForm::validator($data=[], $context='default', $params=[], called_class()); // see "public static function validationRules($params=[])"
// v3) \Litovchenko\AirTable\Validation\Validator::validator($data = [], $rules=[]);
// v4) MyValidatorName extends \Litovchenko\AirTable\Validation\Validator
////////////////////////////////////////////////////////////////////////////////////////

// ----------------
// v1, v2
// ----------------

$data = [];
$data['title'] = 'My Title';

$validator = NewTable::validator($data, 'checkPreInsert'); // class NewTable extends \Litovchenko\AirTable\Domain\Model\ModelCrud
$validator = NewForm::validator($data, 'checkPreInsert'); // class NewForm extends \Litovchenko\AirTable\Domain\Form\ModelForm
if ($validator->passes()) { } //ok
if ($validator->fails()) // error
{
    if ($validator->errors()->has('email')) {} // Check has error field
    $validator->errors()->add('field', 'Something is wrong with this field!'); // Add error
    $validator->errors()->toArray();
    $validDataAr = $validator->valid(); // Valid data
    $invalidDataAr = $validator->invalid(); // Invalid data
}

print '<hr >';

// ----------------
// v3, v4
// ----------------

// $data = $this->request->getArgument('form');
$data = []; 
$data['title'] = 'My Title';
$rules = [];
$rules = [
    'title' => [
        'name' => '--- NAME ---',
        'bail' => true, // Stop on first error 
        'required' => 'MSG ERROR - required',
        'min:1' => 'MSG ERROR - min', 
        'max:5' => 'MSG ERROR - max',
        'custom_rule:p1,p2,p3..' => 'MSG ERROR - my rule'
    ],
    // <f:form.upload property="image" />
    'image' => [
        'name' => '--- ONE IMAGE ---',
        'required' => 'MSG ERROR - required',
        // 'file' => 'MSG ERROR - only file', // new \Symfony\Component\HttpFoundation\File\UploadedFile();
        'image' => 'MSG ERROR - only image', // new \Symfony\Component\HttpFoundation\File\UploadedFile();
        'max:100' => 'MSG ERROR - max size', // max:10240 = max 10 MB. three zero "000"
        'mimes:png,jpg,jpeg,gif' => 'MSG ERROR - png,jpg,jpeg,gif'
    ],
    // <f:form.upload property="images" multiple="true" />
    'images' => [
        'name' => '--- MANY IMAGES ---',
        'required' => 'MSG ERROR - required',
        'min:3' => 'MSG ERROR - min', 
        'max:5' => 'MSG ERROR - max',
    ],
    'images.*' => [
        'required' => 'MSG ERROR - required',
        // 'file' => 'MSG ERROR - only file', // new \Symfony\Component\HttpFoundation\File\UploadedFile();
        'image' => 'MSG ERROR - only image', // new \Symfony\Component\HttpFoundation\File\UploadedFile();
        'max:100' => 'MSG ERROR - max size', // max:10240 = max 10 MB. three zero "000"
        'mimes:png,jpg,jpeg,gif' => 'MSG ERROR - png,jpg,jpeg,gif',
        'dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000' => 'MSG ERROR - dimensions'
    ]
    // ... ... ...
];

$validator = \Litovchenko\AirTable\Validation\Validator::validator($data, $rules);
$validator->addExtension('custom_rule', function($attribute, $value, $parameters) // or "public function custom_rule_name($attribute, $value, $parameters, $validator) {}
{
    // return $value == $parameters[0];
    // return true; // or false
    if ($value == 'TYPO3') {
        return true; // Good
    } else {
        return false;
    }
});

// ??? addRule() ??? // Todo
// ??? validate() ??? // Todo
$v->sometimes('VAT', 'required|max:50', function ($input) {
    return $input->account_type == 'business';
});

if ($validator->passes()) { } //ok
if ($validator->fails()) // error
{
    if ($validator->errors()->has('email')) {} // Check has error field
    $validator->errors()->add('field', 'Something is wrong with this field!'); // Add error
    $validator->errors()->toArray();
    $validDataAr = $validator->valid(); // Valid data
    $invalidDataAr = $validator->invalid(); // Invalid data
}
```

## 18 Frontend editing

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/Iv-Litovchenko.Ru-Edit-Buttons.png)

## 19 Useful settings in "typo3conf/LocalConfiguration.php"

## 20 Extbase Frontend AJAX (http://your-site.com/?eIdAjax=1)

### eIdAjax: _GET

```js script
$(function() {

    //*****************************************************************//
    // _GET Ajax (refresh random photo block)
    //*****************************************************************//
    // <f:link.action 
    //    class="tx-myext-randphotocontroller"
    //    additionalParams="{eIdAjax:1, eIdAjaxPath:'Ext.Myext.Widgets.RandPhoto.index', eIdAjaxSettings: {imgWidthBig:640,imgWidthSmall:300}}"
    // >
    //    Ajax link
    // </f:link.action>
    // <f:uri.action ... />
    //*****************************************************************//
    $('body').on('click', 'a.tx-myext-randphotocontroller', function() {
        $('div.tx-myext-randphotocontroller-wrap').fadeTo("fast", 0.5);
        $.ajax({
            url: "/?eIdAjax=1&eIdAjaxPath=Ext.Myext.Widgets.RandPhoto.index", //  EXT:myext | Classes/Controllers/... | indexAction()
            type: 'GET',
            data: {
                eIdAjaxSettings: {
                    imgWidthBig: 640,
                    imgWidthSmall: 300
                }
            },
            success: function(html) {
                $('div.tx-myext-randphotocontroller-wrap').replaceWith(html);
            }
        });
        return false;
    });

});
```

### eIdAjax: _POST

```js script
$(function() {

    //*****************************************************************//
    // _POST Ajax (feedback form)
    //*****************************************************************//
    // <f:form 
    //    class="tx-myext-feedbackformcontroller" 
    //    name="form" 
    //    object="{form}" 
    //    additionalParams="{eIdAjax:1, eIdAjaxPath:'Ext.Myext.Widgets.FeedBackForm.index', eIdAjaxSettings:{}}"
    // >
    //    <f:form.hidden name="eIdAjaxSettings[settingsOne]" value="1" />
    //    <f:form.hidden name="eIdAjaxSettings[settingsTwo]" value="2" />
    //    <f:form.hidden name="eIdAjaxSettings[settings...]" value="..." />
    //    ...
    // </f:form>
    //*****************************************************************//
    $('body').on('submit', 'form.tx-myext-feedbackformcontroller', function() {
        $(this).find(':submit').attr("disabled", true); // input submit
        $.ajax({
            url: "/?eIdAjax=1&eIdAjaxPath=Ext.Myext.Widgets.FeedBackForm.index", //  EXT:myext | Classes/Controllers/... | indexAction()
            type: 'POST',
            data: new FormData( this ), // $(this).serializeArray(), // <-- <f:form enctype="multipart/form-data" ...>
            processData: false,
            contentType: false,
            success: function(html) {
                $('div.tx-myext-feedbackformcontroller-wrap').replaceWith(html);
            }
        });
        return false;
    });

});
```

## 21 Extbase Example of working with forms

### Controller (EXT:projiv/Classes/Controller/Widgets/FeedBackFormController.php)

```php
<?php
namespace Litovchenko\Projiv\Controller\Widgets;

class FeedBackFormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendWidget',
        'name' => 'Форма обратной связи',
        'nonCachedActions' => 'index',
        'ajaxActions' => 'index',
    ];

    public function indexAction()
    {
        #*************************************************************
        # Form
        #*************************************************************
        // if(\TYPO3\CMS\Core\Utility\GeneralUtility::_POST()) {
        if ($this->request->hasArgument('form')) {
            $postArgs = $this->request->getArguments()['form'];
            $validator = \Litovchenko\Projiv\Domain\Form\FeedBackForm::validator($postArgs, 'default');
            if ($validator->fails()) {
                unset($postArgs['agree']);
                $this->view->assign('form', $postArgs);
                $this->view->assign('formErrors', $validator->errors()->toArray());
                // $this->addFlashMessage('Форма содержит ошибки!', 'Ошибки в форме', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR); // ERROR
            } else {
                $fileUploadedId = \Litovchenko\AirTable\Domain\Model\Fal\SysFile::cmdUpload($postArgs['file'],'fileadmin/ftpupload/FeedBackForm');
                $this->view->setTemplatePathAndFilename('EXT:projiv/Resources/Private/Templates/Widgets/FeedBackForm/Thanks.html');
                $this->sendMail($postArgs, $fileUploadedId);
                // $this->addFlashMessage('Форма прошла проверку', 'Спасибо за обращение', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK); // Cool
            }
        }
        $this->view->assign('q', \Litovchenko\Projiv\Domain\Form\FeedBackForm::$q);
    }

    public function sendMail($postArgs, $fileUploadedId)
    {
        #*************************************************************
        # Mail
        #*************************************************************
        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\FluidEmail::class);
        $mail->from(new \Symfony\Component\Mime\Address('robot@iv-litovchenko.ru', 'Robot Iv-Litovchenko.Ru'));
        $mail->to(new \Symfony\Component\Mime\Address('iv-litovchenko@mail.ru', 'Ivan Litovchenko'));
        $mail->subject('На сайте оставлена заявка');
        $mail->format('plain');
        $mail->setTemplate('FeedBackForm'); // EXT:projiv/Resources/Private/Templates/Email/FeedBackForm.txt
        $mail->assignMultiple($postArgs);
        $mail->assign('filepath', \Litovchenko\AirTable\Domain\Model\Fal\SysFile::getPathById($fileUploadedId));
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\Mailer::class)->send($mail);
        // $mail->attachFromPath('/path/to/my-document.pdf');
    }
}
```

### Model (EXT:projiv/Classes/Domain/Form/FeedBackForm.php)

```php
<?php
namespace Litovchenko\Projiv\Domain\Form;

class FeedBackForm extends \Litovchenko\AirTable\Domain\Form\ModelForm
{
    public static $q = [
        0 => '-- Выберите тему вопроса --',
        'web' => 'Работы по сайту, создание сайта',
        'design' => 'Графический дизайн',
        'other' => 'Другие вопросы',
    ];

    /**
     * A set of rules for context-aware validation
     * @return array
     */
    public static function validationRules($params = [])
    {
        $rules = [
            'default' => [
                'name' => [
                    'name' => '-- NAME --',
                    'required' => 'Как вас зовут?',
                    'min:2' => 'Имя не менее 2 символов!',
                    'max:5' => 'Имя не более 5 символов!',
                    // 'myrule_name:parameter' => 'Ошибка (кастомный валидатор)!',
                ],
                'email' => [
                    'name' => '-- NAME --',
                    'required' => 'Поле обязательно к заполнению',
                    'email' => 'Не правильно указан Email-адрес',
                ],
                'phone' => [
                    'name' => '-- NAME --',
                    'required' => 'Укажите телефон',
                ],
                'q' => [
                    'name' => '-- NAME --',
                    'not_in:0' => 'Выберите вопрос',
                ],
                'message' => [
                    'name' => '-- NAME --',
                    'required' => 'Введите сообщение',
                    'min:10' => 'Сообщение из мене чем 10 символов малоинформативно!',
                ],
                'file' => [
                    'name' => '-- NAME --',
                    // 'required' => 'Поле не заполнено',
                    'file' => 'К загрузке допускаются только файлы',
                    'max:25480' => 'Максимальный размер файла 10Мб', // max:10240 = max 10 MB. + три нуля "000"
                    // 'mimes:png,jpg,jpeg,gif' => 'должно быть форматом jpg,jpeg,png,gif', // doc,pdf,docx,txt,zip,jpeg,jpg,png
                ],
                'agree' => [
                    'required' => 'Необходимо принять условия',
                ],
            ],
        ];
        return $rules;
    }

    public function myrule_name($attribute, $value, $parameters, $validator) {
        if ($value == 'TYPO3') {
            return true; // Good
        } else {
            return false;
        }
    }
}
```

### Template (EXT:projiv/Resources/Private/Templates/Widgets/FeedBackForm/*.html)

```html
<!-- Index.html -->
<!-- All submissions are recommended to be done via Typoscript  -->
<f:asset.script identifier="Widgets.FeedBackForm" src="EXT:projiv/Resources/Public/Js/Widgets.FeedBackForm.js" />
<f:asset.css identifier="Widgets.FeedBackForm" href="EXT:projiv/Resources/Public/Css/Widgets.FeedBackForm.css" />
<div class="tx-projiv-feedbackformcontroller-wrap">
   <div class="alert alert-info" role="alert">
      <h3 align="center">Форма обратной связи</h3>
      <f:form 
         enctype="multipart/form-data"
         class="tx-projiv-feedbackformcontroller" 
         name="form" 
         object="{form}" 
         additionalParams="{eIdAjax:1,eIdAjaxPath:'Ext.Myext.Widgets.FeedBackForm.index'}"
         >
         <div class="form-group">
            <label class="col-form-label">Ваше имя*</label>
            <f:if condition="{errors.name}">
               <span class="error text-danger">{errors.name.0}</span>
            </f:if>
            <f:form.textfield property="name" class="form-control" />
         </div>
         <div class="form-group">
            <label>Email*</label>
            <f:if condition="{errors.email}">
               <span class="error text-danger">{errors.email.0}</span>
            </f:if>
            <f:form.textfield property="email" class="form-control" placeholder="name@example.com" />
         </div>
         <div class="form-group">
            <label>Контактный телефон*</label>
            <f:if condition="{errors.phone}">
               <span class="error text-danger">{errors.phone.0}</span>
            </f:if>
            <f:form.textfield property="phone" class="form-control" />
         </div>
         <div class="form-group">
            <label>Вопрос*</label>
            <f:if condition="{errors.q}">
               <span class="error text-danger">{errors.q.0}</span>
            </f:if>
            <f:form.select property="q" class="form-control" options="{q}" />
         </div>
         <div class="form-group">
            <label>Сообщение*</label>
            <f:if condition="{errors.message}">
               <span class="error text-danger">{errors.message.0}</span>
            </f:if>
            <f:form.textarea property="message" class="form-control" rows="3" />
         </div>
         <div class="form-group">
            <label>Прикрепить файл (до 25 Мб.)</label>
            <f:if condition="{errors.file}">
               <span class="error text-danger">{errors.file.0}</span>
            </f:if>
            <f:form.upload property="file" class="form-control" />
         </div>
         <div class="form-check">
            <label class="form-check-label">
               <f:form.checkbox property="agree" class="form-check-input" value="1" />
               Согласен на обработку персональных данных*...
            </label>
            <f:if condition="{errors.agree}">
               <br /><span class="error text-danger">{errors.agree.0}</span>
            </f:if>
         </div>
         <div class="form-group">
            <f:form.submit class="form-control btn btn-primary" value="Отправить" />
         </div>
      </f:form>
   </div>
</div>

<!-- Thanks.html -->
<div class="tx-projiv-feedbackformcontroller-wrap">
   <div class="alert alert-success" role="alert">
      <h3>Спасибо за Ваше обращение</h3>
      <p>В ближайшее время мы свяжемся с вами!</p>
   </div>
</div>
```

### Email template (EXT:projiv/Resources/Private/Templates/Email/FeedBackForm.txt)

```
Добрый день!

На сайте оставили сообщение.

Имя: {name}
Email: {email}
Телефон: {phone}
Вопрос: {q}
Сообщение: {message}
Прикрепленный файл: {filepath}

--
С уважением, робот сайта
http://iv-litovchenko.ru
```

### How to use?

```
...
...
...
<f:wgsExtProjiv.FeedBackForm />
...
...
...
```

## Functional development plans 

* Создать 2 TV-поля для денормализованных связей TV.tvref_ M-1, M-M... на базе двух вариантов конфигов (по существу это денормализованные типы связей - продумать как их можно выбирать через Laravel). Очень актуально особенно для выбора страниц. Сейчас есть field.tree.category (выбор категории) & field.tree (выбор страницы).
* Найти ту настройку для моделей TCA, где пишется ЧПУ ассоциирующееся с таблицей (моделью)
* [идея] - staticPage <f:router="namePageStatic">, уникальная страница в дереве страниц, либо без дерева страниц
* ~~pageIdContent (подхват контроллера для конкретной страницы, "может это содержит дополнение?") Многостраничники плагины VS pagecontent~~
* Конфигурация сайта: https://t3terminal.com/blog/typo3-site-configuration/
  * Блоки настроек (Craf Settings) - ExtConf -> SiteConf -> ThemesConf
* ~~RTE https://akilli.github.io/ckeditor4-build-classic/demo/~~
  * ~~(Mag) InsertVaribles (он же плайсхолдер, чанк, сниппет, шорткод [bb], [bb] [/bb], [bb arg=””])~~
  * ~~(Mag) InsertWidgets~~
  * ~~InsertBlockTemplate (template ID)~~
  * https://codecanyon.net/item/slavlee-shortcodes/20172526
  * TinyMCE
  * Linkhandler
* Регионы в шаблоны (показ определенных блоков по условиям)
* Oops an error occurred. Code: 2021033121411590d9c6a5 - упростить работу с ошибками, контест "ДевелопменТ" (config.contentObjectExceptionHandler = 1)
* Хлебные крошки, <v:page.breadCrumb>, меню <v:menu expandAll="0" levels="2" /> для 
  * а) таблицы категорий (tx_data_category), произвольных таблиц
  * б) для массива данных
  * Аналоги: https://extensions.typo3.org/extension/nsb_cat2menu/, https://stackoverflow.com/questions/40706825/typo3-sys-category-menu)
  * Во многих CMS меню создается отдельно
  * ItemsProcFunc MENU special = userfunction special.userFunc = Vendor\MyExtension\Userfuncs\CustomMenu->makeMenuArray
  * https://gist.github.com/mawo/f3a49058c3f4fb666c5162d8b77f1ceb
* (Wrapper PageElements/Wrapper/Wrap1Controller.php) Обертка-контроллер для элементов содержимого (styles.templates.layoutRootPath = EXT:/Resources/Private/Layouts/), tt_content.stdWrap.outerWrap.cObject, также смотреть расширение: https://extensions.typo3.org/extension/view/
* (Overriding) Переопределение шаблонов стандартных элементов содержимого, дополнительные шаблоны (Overriding templates of standard content elements (using the "layout" field) - EXT:fluidcontent_core, https://kronova.net/tutorials/typo3/extbase-fluid/additional-headers-in-fluid-styled-content.html) $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['paths']['test_provider_extension'] = [
 'templateRootPaths' => ['EXT:test_provider_extension/Resources/Private/OverrideTemplates'],
];
     * - `plugin.tx_yourext.view.templateRootPath` and siblings.
     * - `plugin.tx_yourext.view.templateRootPaths` and siblings.
     * - `plugin.tx_yourext.view.overlays.otherextension.templateRootPath` and siblings.
     * https://jweiland.net/video-anleitungen/typo3/typo3-projekte-verwalten/fluid-styled-content-templates-anpassen.html
     * https://github.com/sebkln/basetemplate8

* Permissions backend user (non admin!) for root page id (pid)=0;
* Create new content element "WizardItems" for root page id (pid)=0;
* Splitting records into storages (analogous to folders in the tree of pages and EXT:tt_news)
* FAL: Категоризация файлов (коллекции) - идея добавить в D+ модуль фильтрации по тэгам - мои файлы, общие файлы, файлы таблиц
* getFileByHash () для загрузки файлов (что бы файл не пропадал!). Понравилась идея делать для upload files - прямоугольник
* Шоркоды - доработать алгоритм замены (1) перед заменой делать предварительно проверку есть ли плейсхолдеры [f: 2) если нет USER_INT-объектов - записывать в кэш страницы).]
* Flux
  * Создать связь (->with('fluxSettings') + keyBy)
  * Визуально вывести в списках, что бы можно было просмотреть значения атрибутов
  * Сделать загрузку items@ для select-ов из файла /typo3conf/ext/ext/Configuration/Items/Item.txt [Список значений]
  * Laravel enum table (выборка денормализованных связей M-M, без промежуточной таблицы)
  * Разобраться как работает: <flux:field.controllerActions name="switchableControllerActions"> - очень актуально что бы не плодить большое кол-во плагинов одного типа группы (как новости). Способ ограничения полей смотреть в расширении News (файл "public_html\typo3conf\ext\news\Classes\Backend\FormDataProvider\NewsFlexFormManipulation.php") getSwitchableControllerActions ($extensionName, $pluginName) http://man.hubwiz.com/docset/TYPO3.docset/Contents/Resources/Documents/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_configuration_1_1_frontend_configuration_manager.html
* Product provider (EAV):
1) Product provider (Опыт магазинов - продукты проанализировать)...
2) Выборка with + keyBy (вместо select)
3) Eav-связи
4) Общие атрибуты
5) Ограничения для категорий
6) PostBuilConfiguration
7) FlexForm insert, update + mutator (setPiFlexForm).

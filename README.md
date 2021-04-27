# EXT: AirTable (CMS TYPO3)

## CMS TYPO3 #1. Демо-сайт. Начало разработки
[![Watch the video](https://img.youtube.com/vi/5LqxpCbRNXw/maxresdefault.jpg)](https://youtu.be/5LqxpCbRNXw)

## CMS TYPO3 #2. Настройка сайта
[![Watch the video](https://img.youtube.com/vi/CZAhgC9YXsM/maxresdefault.jpg)](https://youtu.be/CZAhgC9YXsM)

A set of tools for creating your site based on class annotations (nowadays magic variable $ TYPO3 = []). Works in versions TYPO3 v10 (not tested in other versions for a long time). The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway. Основная задача данного расширения - одинаково струрированный контент на проекте. [RU]

## 07 Additional View Helper

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
  allowedIcons=Edit,New,Deleted,Disabled" <!--todo-->
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
<f:be.link route="web_ts" parameters="{id: 92}">Go to module</f:be.link>

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
<f:link.action pageUid="1 || self" action="Ext.Myext.Pages.Default.index"> --TEXT-- </f:link.action>
<f:link.action pageUid="1 || self" action="Ext.Myext.Plugins.Default.travels"> --TEXT-- </f:link.action>
<f:link.action action="Ext.AirTable.Modules.Import.step2">444 Go to Module</f:link.action><br />
<f:uri.action action="Ext.Myext.Pages.Default.travelView" arguments="{uid:5}" />

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

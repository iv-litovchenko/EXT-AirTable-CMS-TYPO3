# EXT: AirTable (CMS TYPO3)

## CMS TYPO3 #1. Демо-сайт. Начало разработки
[![Watch the video](https://img.youtube.com/vi/5LqxpCbRNXw/maxresdefault.jpg)](https://youtu.be/5LqxpCbRNXw)

## CMS TYPO3 #2. Настройка сайта
[![Watch the video](https://img.youtube.com/vi/CZAhgC9YXsM/maxresdefault.jpg)](https://youtu.be/CZAhgC9YXsM)

A set of tools for creating your site based on class annotations (nowadays magic variable $ TYPO3 = []). Works in versions TYPO3 v10 (not tested in other versions for a long time). The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway. Основная задача данного расширения - одинаково струрированный контент на проекте. [RU]

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

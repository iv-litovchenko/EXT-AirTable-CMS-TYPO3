# EXT: AirTable (CMS TYPO3)

A set of tools for creating your site based on class annotations (nowadays magic variable $ TYPO3 = []). Works in versions TYPO3 v10 (not tested in other versions for a long time). The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway. Основная задача данного расширения - одинаково струрированный контент на проекте. [RU]

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/Iv-Litovchenko.Ru-Edit-Buttons.png)

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
* [Fluid: Alternative template syntax](#fluid-alternative-template-syntax)
* [Functional development plans](#functional-development-plans)

## 01 Demo site online

Coming soon! // Todo

## 02 How to install?

Step 1) If, when installing a new version of the CMS TYPO3 system, the error "@mysqli.reconnect=1@" pops up - comment out this message in the file: "typo3/sysext/install/Classes/SystemEnvironment/DatabaseCheck/Driver/Mysqli.php". Then proceed with the installation.

Step 2) Install extension "air_table" via extension manager (https://extensions.typo3.org/extension/air_table/). The current version is under development!

~~Step 3) Update the composer in the folder "typo3conf/ext/air_table/"~~\
You can skip this step - everything is already in the "typo3conf/ext/air_table/Vendor/" folder 

```yaml
// command /usr/local/php-cgi/7.0/bin/php -d memory_limit=-1 /usr/local/bin/composer update --ignore-platform-reqs
"require-dev": {
    "illuminate/database": "^5.8.36",
    "illuminate/hashing": "^5.7",
    "rap2hpoutre/fast-excel": "^1.5.0"
}
```

Step 4) Go to the module "Admin Tools" > "Maintenance" > "Rebuild PHP Autoload Information". Click the button "Dump autoload".

Step 5) Go to the module "Admin Tools" > "Maintenance" > "Analyze Database Structure". Click the button "Analyze database".

If you are working in versions 7, 8 and you need "typo3/install.php" - for this you need to create a file "typo3conf/ENABLE_INSTALL_TOOL" with the content "KEEP_FILE".

## 03 Extension structure

```
EXT:myext/Classes/Controller/
EXT:myext/Classes/Controller/Modules/_.txt // Name section
EXT:myext/Classes/Controller/Modules/[*]Controller.php
EXT:myext/Classes/Controller/Pages/[*]Controller.php
EXT:myext/Classes/Controller/PagesElements/Elements/[*]Controller.php
EXT:myext/Classes/Controller/PagesElements/Gridelements/[*]Controller.php
EXT:myext/Classes/Controller/PagesElements/Plugins/[*]Controller.php
EXT:myext/Classes/Controller/Widgets/[*]Controller.php
EXT:myext/Classes/ViewHelpers/[*]ViewHelper.php

EXT:myext/Classes/Domain/Form/[*]Form.php
EXT:myext/Classes/Domain/Model/
EXT:myext/Classes/Domain/Model/OneModel.php
EXT:myext/Classes/Domain/Model/OneModelCategory.php // Model categorization
EXT:myext/Classes/Domain/Model/[SubFolder]/_.txt // Name subfolder
EXT:myext/Classes/Domain/Model/[SubFolder]/ModelInSubfolder.php // Name subfolder

EXT:myext/Classes/Domain/Model/Ext/
EXT:myext/Classes/Domain/Model/Ext/ExtPages.php // Мodify model
EXT:myext/Classes/Domain/Model/Ext/ExtTtContent.php // Мodify model
EXT:myext/Classes/Domain/Model/Ext/ExtOneModel.php // Мodify model

EXT:myext/Configuration/TypoScript/IncBackend/PageConfig.ts
EXT:myext/Configuration/TypoScript/IncBackend/UserConfig.ts
EXT:myext/Configuration/TypoScript/IncFrontend/constants.ts
EXT:myext/Configuration/TypoScript/IncFrontend/setup.ts

EXT:myext/Resources/Private/

// Todo
// Rename template path to -> 
// EXT:myext/Resources/Private/Templates/Pages.Page404.Index.html
// EXT:myext/Resources/Private/Templates/Pages.PageDefault.Index.html
EXT:myext/Resources/Private/Layouts/
EXT:myext/Resources/Private/Partials/
EXT:myext/Resources/Private/Templates/Modules/[*]/Index.html
EXT:myext/Resources/Private/Templates/Pages/[*]/Index.html
EXT:myext/Resources/Private/Templates/PagesElements/Elements/[*]/Index.html
EXT:myext/Resources/Private/Templates/PagesElements/Gridelements/[*]/Index.html
EXT:myext/Resources/Private/Templates/PagesElements/Plugins/[*]/Index.html
EXT:myext/Resources/Private/Templates/Widgets/[*]/Index.html

EXT:myext/Resources/Public/
EXT:myext/Resources/Public/Css/Pages.Main.css
EXT:myext/Resources/Public/Css/Widgets.FeedBackForm.css
EXT:myext/Resources/Public/Js/Pages.Main.js
EXT:myext/Resources/Public/Js/Widgets.FeedBackForm.js
EXT:myext/Resources/Public/Img/Modules/
EXT:myext/Resources/Public/Img/Pages/
EXT:myext/Resources/Public/Img/PagesElements/
EXT:myext/Resources/Public/Img/Widgets/

EXT:myext/ext_emconf.php
```

```php
<?php

/***************************************************************
 * Web project Iv-Litovchenko.Ru (ext_emconf.php)
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
  'title' => 'Myext',
  'author' => 'Ivan Litovchenko',
  'author_company' => '',
  'constraints' => [
    'depends' => [
      'typo3' => '10.0.0-10.9.99',
    ],
    'conflicts' => [],
    'suggests' => [],
  ],
  'autoload' => [
    'psr-4' => [
      'Mynamespace\\Myext\\' => 'Classes'
    ]
  ]
];
```

## 04 Register a new admin module
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-admin-module.png)

Step 1) Create a class EXT:myext/Classes/Controller/Modules/NewModule1Controller.php

```php
<?php
namespace Mynamespace\Myext\Controller\Modules;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

class NewModule1Controller extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModule',
        'name' => 'Module 1',
        'description' => 'Module 1 description',
        'access' => 'user,group || admin || systemMaintainer',
        'accessCustomPermOptions' => [
            // Todo -> $GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']
            'key1' => 'Name One',
            'key2' => 'Name Two',
            'key3' => 'Name Three',
        ],
        'ajaxActions' => 'index', // Todo
        'section' => 'web || file || user || help || content || tools || ext || unseen || sec_ext_myext',
        'position' => '100'
    ];

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Main action for administration
     */
    public function indexAction()
    {
        // $id = (int) (GeneralUtility::_GET('id') ?? 0);
        $this->view->assign('var', rand(1, 1000));
    }

    /**
     * Edit action for administration
     */
    public function editAction()
    {
        // $id = (int) (GeneralUtility::_GET('id') ?? 0);
        $this->view->assign('var', rand(1, 1000));
    }
}
```

Step 2) Create template EXT:myext/Resources/Private/Templates/Modules/NewModule1/Index.html

```html
<f:debug title="Debug" inline="true">{_all}</f:debug>
<h1>Module 1</h1>

<h2>Hellow word {var}!</h2>

<f:be.link route="web_ts" parameters="{id: 92}">Go to web_ts</f:be.link><br />

<!--Tested only in TYPO3 v10!-->
<!--Switching to another controller is not easy!-->
<!--Ext.[***extension***].Modules.[***controller***].[***action***]-->
<f:be.link route="Ext.Myext.Modules.NewModule2.index" parameters="{arg: 1}">Go to Module 2</f:be.link><br />
<f:be.link route="Ext.Myext.Modules.NewModule3.index" parameters="{arg: 1}">Go to Module 3</f:be.link><br />

<f:link.action action="edit" class="btn btn-default btn-sm">
	Module 1 (action "Edit")
</f:link.action>
```

Step 3) Go to the module "Admin Tools" > "Maintenance" > "Flush TYPO3 and PHP Cache". Click the button "Flush cache" (if changed or added new actions in the controller!).

## 05 Register a new page template
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-page-template-1.png)

Step 1) Create a class EXT:myext/Classes/Controller/Pages/NewPageController.php

```php
<?php
namespace Mynamespace\Myext\Controller\Pages;

class NewPageController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendPage',
        'name' => 'Page name',
        'description' => 'Page description',
        'disableAllHeaderCode' => '0 || 1',
        'includeTyposcriptSourceTemplate' => '', // Todo (file with typoscript.ts - settings, t1.ts, t2.ts...)
        'nonCachedActions' => 'index', // USER_INT
        'ajaxActions' => 'index', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=Ext.***.***.***.*** - See "Ajax-Frontend"
        'urlManagerActions' => [
            // [RU] На 1 действие может быть несколько вариантов
            // [ENG] There can be several options for 1 action
            '/travels' => 'travelsAction',
            '/travels/page-{page}' => 'travelsAction',

            // public function travelViewAction(float $num = null, int $star = null)
            '/travels/{num}' => 'travelViewAction',
            '/travels/star/{star}' => 'travelViewAction',

            // public function sendFormAction(array $form = [])
            '/form' => 'sendFormAction'
        ],
        'fieldsExcludeList' => 'subtitle,nav_title',
        'fieldsAddList' => 'subtitle,nav_title',
        'cols' => '0,1|2,3,4|5'
    ];

    public function preview()
    {
        // Todo - \Litovchenko\AirTable\Hooks\PageLayoutView\PageLayoutHeaderHook
        $itemContent .= '
        <p class="text-center">
        <span title="'.$row['title'] . '" class="btn btn-default">' . $row['title'] .'</span>
        </p>';
    }

    public function indexAction()
    {
        // typo3conf/ext/myext/Configuration/TypoScript/IncFrontend/constants.ts
        // typo3conf/ext/myext/Configuration/TypoScript/IncFrontend/setup.ts
        // plugin.tx_myext.settings.myOneSetting = 100
        // plugin.tx_myext_newpagecontroller.settings.myTwoSetting = 100
        // print_r($this->settings);
        $this->view->assign('var', rand(1, 1000));
    }

    public function detailAction()
    {
        $this->view->assign('var', rand(1, 1000));
    }
}
```

Step 2) Create template EXT:myext/Resources/Private/Templates/Pages/NewPage/Index.html

```html
<f:debug title="Debug" inline="true">{_all}</f:debug>

<!--Include header page template-->
<f:vhsExtAirTable.adminPanel />
<f:comment><!--<f:render partial="Header" arguments="{_all}" />--></f:comment>

	<!--Get page content-->
	<table border="1">
	<tr>
		<td>Hellow word {var}!</td>
	</tr>
	<tr>
		<td><f:vhsExtAirTable.content colPos="0" /></td>
		<td><f:vhsExtAirTable.content colPos="1" /></td>
	</tr>
	<tr>
		<td><f:vhsExtAirTable.content colPos="2" /></td>
		<td><f:vhsExtAirTable.content colPos="3" /></td>
		<td><f:vhsExtAirTable.content colPos="4" /></td>
	</tr>
	<tr>
		<td><f:vhsExtAirTable.content colPos="5" /></td>
		<td><f:vhsExtAirTable.content colPos="6" /></td>
	</tr>
	</table>

	<!--Switching between actions -->
	<f:link.action action="detail">Show me what's there!</f:link.action>
	<f:link.action pageUid="1" route="Ext.Myext.Pages.Default.travels">--TEXT--</f:link.action>
	<f:uri.action pageUid="1" route="Ext.Myext.Pages.Default.travelView" arguments="{uid:5}" />
	<f:be.link route="Ext.Myext.Modules.ModuleName.action" ....><br />

<!--Include footer page template-->
<f:comment><!--<f:render partial="Footer" arguments="{_all}" />--></f:comment>
<f:vhsExtAirTable.adminPanelTools />
```

## 06 Register a new content element
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-content-element.png)

Step 1) Create a class EXT:myext/Classes/Controller/PagesElements/Elements/NewElementController.php

```php
<?php
namespace Mynamespace\Myext\Controller\PagesElements\Elements;
namespace Mynamespace\Myext\Controller\PagesElements\Gridelements;
namespace Mynamespace\Myext\Controller\PagesElements\Plugins;

class NewElementController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendContentElement || FrontendContentGridelement || FrontendContentPlugin',
        'name' => 'Content element name',
        'description' => 'Content element description',
        'nonCachedActions' => 'index', // USER_INT
        'ajaxActions' => 'index', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=Ext.***.***.***.*** - See "Ajax-Frontend"
	//////////////////////////////////////////////
        // If type "FrontendContentPlugin (start)"
	//////////////////////////////////////////////
	'urlManagerActions' => [
            // [RU] На 1 действие может быть несколько вариантов
            // [ENG] There can be several options for 1 action
            '/travels' => 'travelsAction',
            '/travels/page-{page}' => 'travelsAction',

            // public function travelViewAction(float $num = null, int $star = null)
            '/travels/{num}' => 'travelViewAction',
            '/travels/star/{star}' => 'travelViewAction',

            // public function sendFormAction(array $form = [])
            '/form' => 'sendFormAction'
        ],
	//////////////////////////////////////////////
        // If type "FrontendContentPlugin (end)"
	//////////////////////////////////////////////
        'fieldsExcludeList' => 'header_position,date',
        'fieldsAddList' => 'imageorient',
        'cols' => '1,2,3|4,5 || container', // If type "FrontendContentGridelement" // EXT:gridelements
    ];

    public function preview()
    {
        // Todo - Litovchenko\AirTable\Hooks\PageLayoutView\NewContentElementPreviewRenderer;
        $itemContent .= '
        <p class="text-center">
        <span title="'.$row['header'] . '" class="btn btn-default">' . $row['header'] .'</span>
        </p>';
    }

    public function indexAction()
    {
        // typo3conf/ext/myext/Configuration/TypoScript/IncFrontend/constants.ts
        // typo3conf/ext/myext/Configuration/TypoScript/IncFrontend/setup.ts
        // plugin.tx_myext.settings.myOneSetting = 100
        // plugin.tx_myext_newelementcontroller.settings.myTwoSetting = 100
        // print_r($this->settings);
        $this->view->assign('var', rand(1, 1000));
        $this->view->assign(
            'gridId', // If type "FrontendContentGridelement" // EXT:gridelements
            $this->configurationManager->getContentObject()->data['uid']
        );
    }

    // If type "FrontendContentPlugin"
    public function detailAction()
    {
        $this->view->assign('var', rand(1, 1000));
    }
}
```

Step 2) Create template EXT:myext/Resources/Private/Templates/PagesElements/Elements/NewElement/Index.html

```html
<f:debug title="Debug" inline="true">{_all}</f:debug>
<div style="padding: 25px; background: wheat; text-align: center;">
	<h3>Hellow word "Element || Gridelement || Plugin" {var}!</h3>
	
	<!--If type "FrontendContentGridelement" // EXT:gridelements-->
	<table border="1" width="100%">
	<tr>
		<td><f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="1" /></td>
		<td><f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="2" /></td>
		<td><f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="3" /></td>
		<td><f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="4" /></td>
		<td><f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="5" /></td>
	</tr>
	</table>
	
	<!--If type "Plugin"-->
	<f:link.action action="detail">Show me what's there!</f:link.action>
	<f:link.action pageUid="1" route="Ext.Myext.Pages.Default.travels">--TEXT--</f:link.action>
	<f:uri.action pageUid="1" route="Ext.Myext.Pages.Default.travelView" arguments="{uid:5}" />
</div>
```

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

## 08 Register View Helper

Step 1) Create a class EXT:myext/Classes/ViewHelpers/HelloWorldViewHelper.php

```php
<?php
namespace Mynamespace\Myext\ViewHelpers;

class HelloWorldViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendViewHelper',
        'name' => 'Test Helper',
        'description' => 'String output based on arguments',
        'registerArguments' => [
            'testArg1*' => ['string','Default value','Description'], // integer || string || mixed || boolean || array
            'testArg2' => ['string']
        ]
    ];

    public function render()
    {
        $testArg1 = $this->arguments['testArg1'];
        $testArg2 = $this->arguments['testArg2'];
        return 'Hello world - ' . $testArg1 . ',' . $testArg2;
    }
}
```

Step 2) How to use?

```html
<f:debug title="Debug" inline="true">{_all}</f:debug>
...
...
...
<h3>My View Helper</h3>
<h4>String:</h4>
<u>
   <f:vhsExtMyext.HelloWorld testArg1='100' testArg2='200' />
</u>
<h4>Condition:</h4>
<f:if condition="{f:vhsExtMyext.HelloWorld(testArg1:'100', testArg2:'200')}">
   <f:then>YES</f:then>
   <f:else>NO</f:else>
</f:if>
...
...
...
```

## 09 Register Widget (Component - View Helper with controller and template)

Step 1) Create a class EXT:myext/Classes/Controller/Widgets/TestController.php

```php
<?php
namespace Mynamespace\Myext\Controller\Widgets;

class TestController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendWidget',
        'name' => 'Test widget',
        'description' => 'The widget has a controller and a template',
        'nonCachedActions' => 'index', // USER_INT
        'ajaxActions' => 'index', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=Ext.***.***.***.*** - See "Ajax-Frontend"
        'registerArguments' => [
            'testArg1*' => ['string','Default value','Description'], // integer || string || mixed || boolean || array
            'testArg2*' => ['string',640],
            'testArg3' => ['string',480]
        ]
    ];

    public function indexAction()
    {
        $this->view->assign('testArg1', $this->settings['testArg1']);
        $this->view->assign('testArg2', $this->settings['testArg2']);
        $this->view->assign('testArg3', $this->settings['testArg3']);
    }
}
```

Step 2) Create template EXT:myext/Resources/Private/Templates/Widgets/Test/Index.html

```html
<f:debug title="Debug" inline="true">{_all}</f:debug>

My Widget (Result): <br />
{testArg1}, {testArg2}, {testArg3}
```

Step 3) How to use?

```html
...
...
...
<h3>My Widget (Initialization):</h3>
<u>
   <f:wgsExtMyext.Test testArg1="100" testArg2="200" testArg3="300" />
</u>
...
...
...
```

## 10 Register a new model (CRUD)

Step 1) Create a model EXT:myext/Classes/Domain/Model/[SubFolder]/NewTable.php

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-crud.png)

```php
<?php
namespace Mynamespace\Myext\Domain\Model\[SubFolder];

class NewTable extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModelCrud',
        'name' => 'New table name',
        'description' => 'New table description',
        'defaultListTypeRender' => '0 || 1 || 2 || 3',
        'formSettings' => [
            // Turn on the setting in "LocalConfiguration.php" to see the names of columns and tabs "BE -> debug -> true"
            'tabs' => [
                'mytab' => 'My Tab (###COUNT###)',
                'tabKeyOne' => 'Tab 1 (###COUNT###)',
                'tabKeyTwo' => 'Tab 2 (###COUNT###)',
                'tabKeyTtree' => 'Tab 3 (###COUNT###)',
            ],
        ],
        //////////////////////////////////////
        // Special table fields
        //////////////////////////////////////
        'baseFields' => [
            'uidkey',
            'pid', // A record can exist in any part of the site page tree
            'RType' => [
                // Record types similar to "doktype (pages)" and "CType (tt_content)"
                'items' => [
                    '0' => 'Default',
                    'news' => 'News',
                    'article' => 'Article',
                    'link' => 'External link',
                    'type-1' => 'Type 1',
                    'type-2' => 'Type 2',
                    'type-3' => 'Type 3',
                    // ...
                ],
            ],
            #'RTypeSub', // Todo
            'title' => [
                'required' => 1,
            ],
            'alt_title' => [
                'required' => 1,
            ],
            'service_note',
            'date_create',
            'date_update',
            'date_start',
            'date_end',
            'sorting',
            'status', // Or 'deleted' && 'disabled',
            'bodytext_preview',
            'bodytext_detail',
            'keywords',
            'description',
            'slug',

            'propmedia_pic_preview',
            'propmedia_pic_detail',
            'propmedia_files',
            'propmedia_thumbnail', // Image associated with the recording

            'propref_beauthor', // M-1
            'propref_content', // <f:vhsExtAirTable.content model="Mynamespace\Myext\Domain\Model\NewTable" uid="2" />
            'propref_attributes',

            // Categorization
            // For this to work, you need:
            // 1) create a category model (NewTableCategory.php) in the current directory
            // 2) add trait "\Litovchenko\AirTable\Domain\Model\Traits\ParentRow" to model "NewTableCategory.php"
            'propref_parent', // M-1
            'propref_category', // Categorization M-1
            // or 'propref_categories', //  Categorization M-M

            'foreign_table', // For polymorphic relations
            'foreign_field', // For polymorphic relations
            'foreign_uid', // For polymorphic relations
            'foreign_sortby', // For polymorphic relations
        ],
        //////////////////////////////////////
        // prop_*
        //////////////////////////////////////
        'dataFields' => [
            // Input
            'prop_nameinput' => [
                'type' => 'Input', // || Input.Int || Input.Number || Input.Float || Input.Link || Input.Color || Input.Email || Input.Password || Input.InvisibleInt || Input.Invisible
                'name' => 'Field Input',
                'max' => 100,
                'size' => 24,
                'liveSearch' => 1,

                //////////////////////////////////////
                // General settings for all types of fields
                //////////////////////////////////////
                'description' => '-------Description-------',
                'placeholder' => '-------Placeholder-------',
                'default' => '-------Default value-------',
                'show' => 1, // Show field in lists
                'required' => 1, // Required to fill
                'readOnly' => 1,
                'validationRules' => [
                    'required' => 1, // Todo Validator
                    'ruleName' => 1, // Todo Validator
                ],
                'onChangeReload' => 1, // Reload the form when the field value changes
                'displayCond' => 'USER:Litovchenko\AirTable\Domain\Model\Content\Data->isVisibleDisplayConditionMatcher:tx_data', // Example
                'exclude' => 1, // Todo

                // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                // Example 'position' => ['fields']['prop_***']['position'][][type|tab|position'] add to type "1" (RType)
                // Example 'position' => ['fields']['prop_***']['position'][]['*|mytab|100'] "*" adding to all types
                // Example 'position' => ['fields']['prop_***']['position'][]['1|mytab|100'] add to type "1" (RType)
                'position' => '*|props|10', // '<RType>|<Tab>|<Num>'
                // 'position' => [
                    // '*|props|10',
                    // 'news|props|10',
                    // 'article|props|10',
                    // ...
                // ],
            ],
            // Text
            'prop_nametext' => [
                'type' => 'Text', // || Text.Rte || Text.Code || Text.Table || Text.Invisible
                'name' => 'Field Text',
                'show' => 1,
                'liveSearch' => 1,
                'format' => 'css || html || javascript || php || typoscript || xml', // Text.Rte
                'preset' => 'default || full || default || ext_myext_preset', // Text.Code
            ],
            // Date
            'prop_namedate' => [
                'type' => 'Date', // || Date.DateTime || Date.Time || Date.TimeSec || Date.Year
                'name' => 'Field Date',
                'show' => 1,
            ],
            // Flag
            'prop_nameflag' => [
                'type' => 'Flag',
                'name' => 'Field Flag',
                'show' => 1,
                'items' => [
                    1 => 'Checked',
                ],
            ],
            // Switcher
            'prop_nameswitcher' => [
                'type' => 'Switcher', // || Switcher.Int
                'name' => 'Field Switcher',
                'show' => 1,
                'itemsProcFunc' => 'Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems',
                'itemsModel' => 'Mynamespace\Myext\Domain\Model\###',
                'itemsWhere' => [
                    'where' => [
                        0 => 'table.field > 5'
                    ],
                    'orderBy' => [
                        0 => 'table.field desc'
                    ]
                ],
                'items' => [
                    0 => 'Zero',
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ],
            ],
            // Enum
            'prop_nameenum' => [
                'type' => 'Enum',
                'name' => 'Field Enum',
                'show' => 1,
                'itemsProcFunc' => 'Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems',
                'itemsModel' => 'Mynamespace\Myext\Domain\Model\###',
                'itemsWhere' => [
                    'where' => [
                        0 => 'table.field > 5'
                    ],
                    'orderBy' => [
                        0 => 'table.field desc'
                    ]
                ],
                'items' => [
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ],
            ],
            // ...
        ],
        //////////////////////////////////////
        // propmedia_*
        //////////////////////////////////////
        'mediaFields' => [
            // Media
            'propmedia_pics' => [
                // 'type' => 'Media_1 || Media_1.Image || Media_1.Mix', // One
                'type' => 'Media_M || Media_M.Image || Media_M.Mix', // Many
                'name' => 'Field Media_1',
                'position' => '*|media|10', // '<RType>|<Tab>|<Num>' ("RType")
                'show' => 1,
                'maxItems' => 10,
            ],
            // ...
        ],
        //////////////////////////////////////
        // propref_*
        //////////////////////////////////////
        'relationalFields' => [
            'propref_tags' => [
                'type' => 'Rel_1To1', // || Rel_1ToM || Rel_MTo1(.Large) || Rel_MToM(.Large) || Rel_Poly_1To1 || Rel_Poly_1ToM
                'name' => 'Field Rel_1To1',
                'position' => '*|rels|10', // '<RType>|<Tab>|<Num>' ("RType")
                'show' => 1,
                'foreignModel' => 'Mynamespace\Myext\Domain\Model\Tag', // tx_myext_dm_tag
                'foreignKey' => 'propref_newtable',
                'foreignParentKey' => 'parent_id', // Only Rel_MTo1.Tree || Rel_MToM.Tree
                'foreignWhere' => [ // See $TCA "foreign_table_where
                    'where' => [
                        0 => 'table.RType=###REC_FIELD_RType### '
                    ],
                    'orderBy' => [
                        0 => 'table.field desc'
                    ]
                ],
                'foreignDefaults' => [
                    'CType' => 'image', // See $TCA "foreign_record_defaults"
                ],
            ],
            // ...
        ],
    ];

    /**
     * A set of rules for context-aware validation
     * @return array
     */
    public static function validationRules($params = [])
    {
        $rules = [
            'checkPreInsert' => [
                'title' => [
                    'required' => 'MSG "required"',
                    'string' => 'MSG "string"',
                    'max:2' => 'MSG "max"',
                    #function ($attribute, $value, $fail) {
                    #	if ($value === 'foo') {
                    #		$fail('The '.$attribute.' is invalid.');
                    #	}
                    #}
                ],
            ],
            'checkPreUpdate' => [
                // context update...
            ],
            'checkPreDelete' => [
                // context delete...
            ],
            'checkOther' => [
                // ...
            ],
        ];
        return $rules;
    }

    /**
     * Changing the $TCA settings array
     * @configuration (TCA array)
     * @return &configuration
     */
    public static function postBuildConfiguration(&$configuration = [])
    {
        //$configuration['ctrl']['...'] = ...;
        //$configuration['columns']['field']['config'] = ...;
    }

    /**
     * Custom value set (user func)
     * It is possible to use a selection from the database
     * return $config
     */
    public static function doItems($config)
    {
        $itemList = []; // If database
        $config['items'][] = [100, 'New item 100'];
        $config['items'][] = [200, 'New item 200'];
        $config['items'][] = [300, 'New item 300'];
        return $config;
    }

    /**
     * Debug content
     * @return string
     */
    public static function userDebugСontent()
    {
        $content = 'User Debug Content';
        return $content;
    }

    /**
     * // Todo ext_tables_static+adt.sql
     * @return '';
     */
    public static function cmdDatabaseSeeder()
    {
    }

    /**
     * Record event (before / after) - // Todo https://laravel.ru/posts/338
     * @return '';
     */
    public static function cmdEvent($command, $when, &$table, $id, &$fieldArray)
    {
        $command = 'insert || update || delete';
        if ($when == 'before') {
            //
        } else {
            //
        }
    }
}
```

Step 2) If you need categorization, create a category model EXT:myext/Classes/Domain/Model/[SubFolder]/NewTableCategory.php

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-crud-with-category.png)

```php
<?php
namespace Mynamespace\Myext\Domain\Model\[SubFolder];

class NewTableCategory extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModelCrud',
        'name' => 'New category table name ',
        'description' => 'New category table description',
        'defaultListTypeRender' => '0 || 1 || 2 || 3',
        'baseFields' => [
            'title',
            'deleted',
            'disabled',
            'sorting',
            'parent_row_id',
        ],
    ];
}
```

Step 3) Go to the module "Admin Tools" > "Maintenance" > "Rebuild PHP Autoload Information". Click the button "Dump autoload".

Step 4) Go to the module "Admin Tools" > "Maintenance" > "Analyze Database Structure". Click the button "Analyze database".

## 11 Standard CRUD-models registered in the system for working with records

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-standard-crud-models.png)

```php
\Litovchenko\AirTable\Domain\Model\Content\Data;
\Litovchenko\AirTable\Domain\Model\Content\DataCategory;
\Litovchenko\AirTable\Domain\Model\Content\Pages;
\Litovchenko\AirTable\Domain\Model\Content\TtContent;

\Litovchenko\AirTable\Domain\Model\Fal\SysFile;
\Litovchenko\AirTable\Domain\Model\Fal\SysFileMetadata;
\Litovchenko\AirTable\Domain\Model\Fal\SysFileReference;
\Litovchenko\AirTable\Domain\Model\Fal\SysFileStorage;
\Litovchenko\AirTable\Domain\Model\Fal\SysFilemounts;

$recordId = 1774; // or path: "fileadmin/ftpupload/6/look.com.ua-74892.jpg"
$image = $this->request->getArgument('form')['image']; // <f:form.upload property="image" />
SysFile::cmdAddToIndex('fileadmin/ftpupload/6/look.com.ua-74892.jpg'); // return $id; ! Registering a file if the file was added via FTP 
SysFile::cmdUpload($image,'fileadmin/ftpupload/8/', 'rename || replace || cancel'); // return $id;
SysFile::cmdCreate('', '-- CONTENT --'); // Todo...
SysFile::cmdUpdate('', '-- NEW CONTENT --', 'overwrite'); // Todo...
SysFile::cmdExists($recordId); // return true || false;
SysFile::cmdRename($recordId,'new-name5.jpg');
SysFile::cmdCopy($recordId,'fileadmin/new-name5.jpg', 'rename || replace || cancel');
SysFile::cmdMove($recordId, 'fileadmin/ftpupload/7/', 'rename || replace || cancel');
SysFile::cmdReplace($recordId); // Todo...
SysFile::cmdDownload($recordId); // Todo...
SysFile::cmdDelete($recordId);
SysFile::getPathById(1774);
SysFile::getIdByPath('fileadmin/ftpupload/6/look.com.ua-74892.jpg');

// Todo - https://laravel.su/docs/5.0/filesystem (Working with directories "SysFileStorage")
SysFileStorage::cmdDirCreate;
SysFileStorage::cmdDirRename;
SysFileStorage::cmdDirDelete;
...

\Litovchenko\AirTable\Domain\Model\SysMm; // All links of type "Rel_MToM" are stored here
\Litovchenko\AirTable\Domain\Model\SysNote;
\Litovchenko\AirTable\Domain\Model\SysRedirect;

\Litovchenko\AirTable\Domain\Model\Users\BeGroups;
\Litovchenko\AirTable\Domain\Model\Users\BeUsers;
\Litovchenko\AirTable\Domain\Model\Users\FeGroups;
\Litovchenko\AirTable\Domain\Model\Users\FeUsers;

// Other table
$filter = [];
$filter['select'] = ['id','identifier','tag'];
$filter['from'] = ['cache_pages_tags'];
$rows = \Litovchenko\AirTable\Domain\Model\DynamicModelCrud::recSelect('get',$filter); // Any tables 
// $rows = DB::table('cache_pages_tags'); // use Illuminate\Database\Capsule\Manager as DB; 
```

## 12 Extending an existing model

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/extending-an-existing-model.png)

Step 1) Create a file EXT:myext/Classes/Domain/Model/Ext/ExtSysFile.php

Step 2) Create class inherited from base model

```php
<?php
namespace Mynamespace\Myext\Domain\Model\Ext;

class ExtSysFile extends \Litovchenko\AirTable\Domain\Model\Fal\SysFile
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModelExtending',
        'description' => 'From EXT:myext - adding fields to the page model',
        // 'formSettings' => [
        //     'tabs' => [
        //         'newtab' => 'EXT:Myext New Tab (###COUNT###)'
        //     ],
        // ],
        // 'baseFields' => [
        //     'RType' => [
        //         'items' => [
        //             '100' => 'New Type',
        //         ]
        //     ],
        // ],
        'dataFields' => [
            'prop_tx_myext_incrandphoto' => [
                'type' => 'Flag',
                'name' => 'New field',
                // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                // 'position' => '*|props|10', // '<RType>|<Tab>|<Num>'
                // 'position' => [
                    // '*|props|10',
                    // 'news|props|10',
                    // 'article|props|10',
                    // ...
                // ],
            ]
        ],
        'mediaFields' => [], // ...
        'relationalFields' => [] // ...
    ];

    /**
     * Changing the $TCA settings array
     * @configuration (TCA array)
     * @return &configuration
     */
    public static function postBuildConfiguration(&$configuration = [])
    {
        //$configuration['ctrl']['...'] = ...;
        //$configuration['columns']['field']['config'] = ...;
    }
}

```

Step 3) Go to the module "Admin Tools" > "Maintenance" > "Rebuild PHP Autoload Information". Click the button "Dump autoload".

Step 4) Go to the module "Admin Tools" > "Maintenance" > "Analyze Database Structure". Click the button "Analyze database".

## 13 List records (module)

- // todo

## 14 Export records Xls|Csv (module)

- // todo

## 15 Import records Xls|Csv (module)

- // todo

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
$GLOBALS['TSFE']->AddBreadcrumbItem(); // Todo 
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
	  
      <!-- Примеры табов/аккордионов -->
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

```php
<?php
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// SELECT
// NewTable::recSelect('medthod', $id || $filter || $callback, $pluck = null); // return result
////////////////////////////////////////////////////////////////////////////////////////

Model::find(numeric); returns a object
Model::whereId(numeric)->first(); returns a object
Model::whereId(numeric)->get(); - returns a collection
Model::whereId(numeric); - returns a builder

$recordId = 7;
$rowFirst = NewTable::recSelect('first', $recordId);
$rowExists = NewTable::recSelect('exists', $recordId); // ->exists() (if), ->doesntExist() (if)
$rowsCount = NewTable::recSelect('count'); // count
$rowsGet = NewTable::recSelect('get'); // all
$obj = NewTable::recSelect('object')->...->get(); // return obj (to create subqueries)
$sql = NewTable::recSelect('object')->toSql();
$dd = NewTable::recSelect('object')->dd(); // debugging
$dump = NewTable::recSelect('object')->dump(); // debugging

$limit = 10;
$rowsResult = NewTable::recSelect('count,get', function ($q) use ($limit) { 
    $q->limit($limit); 
});

print "Count: " . $rowsResult['count'] . "<hr />";
foreach ($rowsResult['get'] as $row) {
    print $row['title'] . " // ";
    print $row['propref_NAME']['title'] . "<br />";
}

$filter = [];
$filter['withoutGlobalScopes'] = true; // or false
$filter['withoutGlobalScopes'] = ['FlagDeleted','FlagDeleted','DateStart', 'DateEnd', 'SwitcherStatus'];
$filter['withoutGlobalScope'] = 'FlagDeleted';

$filter['distinct'] = 'title';
$filter['select'] = ['uid','title', 'uid as aliasID'];
$filter['addSelect'] = ['pid','date_create']; // selectRaw()

$filter['whereUid'] = 1; // dynamic field name
$filter['wherePid'] = 1; // dynamic field name
$filter['whereTitle'] = []; // dynamic field name
$filter['whereFieldName'] = []; // dynamic field name

// Allowed operator parameter
// '=', '<', '>', '<=', '>=', '<>', '!=',
// 'like', 'like binary', 'not like', 'between', 'ilike',
// '&', '|', '^', '<<', '>>',
// 'rlike', 'regexp', 'not regexp',
// '~', '~*', '!~', '!~*', 'similar to',
// 'not similar to'
$filter['where.10'] = []; // ...
$filter['where.20'] = []; // ...
$filter['where'] = ['uid','>=',1]; // ->orWhere()
$filter['where'] = function($q) { 
    $q->where('pid','>=',0); 
    $q->orWhere('pid','<=',0);
};

$values = [
    ['type_a', 1],
    ['type_a', 2],
    ['type_b', 1],
    ['type_c', 1],
];
$filter['whereInMultiple'] = ['morphable_type', 'morphable_id'], $values]; // ->whereNotInMultiple()
$filter['whereIn'] = ['uid',[1,2,3,4,5,6,7,8,9,10]]; // ->orWhereIn(), ->whereNotIn(), ->orWhereNotIn()
$filter['whereNull'] = 'keywords'; // ->orWhereNull(), ->whereNotNull(), ->orWhereNotNull()
$filter['whereBetween'] = ['uid',[1,1000]]; // ->whereNotBetween()
$filter['whereColumn'] = ['uid','!=','title'];

$filter['whereRaw'] = ['(uid > ? and uid < ?)', [1,1000]]; // DB::raw(1)
$filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%j') = ?", 11]; // %d -> with zero
$filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%n') = ?", 1]; // %m -> with zero
$filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%Y') = ?", 2021];

$filter['whereExists'] = function($q) { // ->orWhereExists(), ->whereNotExists(), ->orWhereNotExists()
    $q->select(DB::raw(1))->from('tablename')->whereRaw('uid > 0'); 
};

$filter['whereNested'] = function($query){
    $query->where('title', 'like', '1');
};

// Todo
// whereDate() // ->whereDate('created_at', date('Y-m-d'))
// whereMonth() // ->whereMonth('created_at', '05')
// whereDay() // ->whereDay('created_at', '05')
// whereYear() // ->whereYear('created_at', '05')
// whereTime() // ->whereTime('created_at', '=', '1:20:45')
// protected $dates = [‘edited_at’];
// The following are the comparison functions of Carbon.
eq() - equals
ne() - not equals
gt() - greater than
gte() - greater than or equals
lt() - less than
lte() - less than or equals
Example:
if($model->edited_at->gt($model->created_at)){
// edited at is newer than created at
}

$filter['inRandomOrder'] = false; // true
$filter['orderBy.10'] = ['uid','desc'];
$filter['orderBy.20'] = ['title','desc'];
$filter['groupBy'] = 'title';

$filter['limit'] = 3;
$filter['offset'] = 0;
$filter['having'] = ['aliasID', '>', 0]; // orHaving, havingRaw

// ->with(), ->has(), ->whereHas(), orWhereHas()
// ->doesntHave(), ->whereDoesntHave(), ->withCount()
// ->wherePivot(), wherePivotIn()
$filter['with.10']  = [
    'propref_NAMEA' => function($q) {
        $q->with('propref_INNERNAME');
        $q->where('uid','>',0);
        $q->where('pid','>',0);
    }
];
$filter['with.20'] = 'propref_NAMEB.propref_INNERNAME';
$filter['with.30'] = 'propref_NAMEC';
$filter['with.40'] = 'propref_NAMED';

// Todo -> https://github.com/Waavi/model
// $posts = Post::whereNotRelated('author', 'name', '=', 'John')->get();
// $comments = Comment::whereRelated('post.author', 'name', '=', 'John')->get();
// WaaviModel::whereRelated($relationshipName, $column, $operator, $value);
// WaaviModel::orWhereRelated($relationshipName, $column, $operator, $value);
// WaaviModel::whereNotRelated($relationshipName, $column, $operator, $value);
// WaaviModel::orWhereNotRelated($relationshipName, $column, $operator, $value);

// ->unionAll() // $subQ = NewTable::recSelect('object', $filter);
$filter['union'] = ...;
$filter['join'] = ...;
$filter['leftJoin'] = ...;
$filter['crossJoin'] = ...;

$filter['mySelectMinimize'] = true; // or false
$filter['myWhereFlagDeletedIn'] = [0,1]; // 0, 1, [0,1]
$filter['myWhereFlagDisabledIn'] = [0,1]; // 0, 1, [0,1]
$filter['myPagination'] = [1,30]; // $pagePosition, $pageLimit

$count = NewTable::recSelect('count', $filter);
$rows = NewTable::recSelect('get', $filter);

print "Count: " . $count . "<hr />";
foreach ($rows as $row) {
    print $row['title'] . " // ";
    print $row['propref_NAME']['title'] . "<br />";
}

...->encrypt('id')->get();
...->exclude('id', 'email')->get();
...->addScope('active')->get();
...->addScope('formatDate', 'd-m-Y')->get();

////////////////////////////////////////////////////////////////////////////////////////
// SELECT EAV 
// Entity–attribute–value
////////////////////////////////////////////////////////////////////////////////////////

$filter = [];
$filter['select'] = ['title','RType'];
$filter['selectAttributeAll'] = true; // attr.*' // Todo
$filter['selectAttribute.10'] = ['attr_color'];
$filter['selectAttribute.20'] = ['attr_email'];
$filter['where'] = ['RType',6]; // tx_data type
$filter['whereAttribute'] = ['attr_color', '#000000'];
$filter['orWhereAttribute'] = ['attr_color', '#eeeeee'];
$filter['orWhereAttribute'] = ['attr_color', '#3d0d0d'];
$filter['orderByAttribute'] = ['attr_color', 'desc'];

$rows = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('get', $filter);
$count = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('count', $filter);

////////////////////////////////////////////////////////////////////////////////////////
// INSERT
// ModelName::recInsert($data); // return last insert id
////////////////////////////////////////////////////////////////////////////////////////

$data = [];
$data['uid'] = uniqid('NEW_'); // :)
$data['title'] = '-- TITLE --';
$insertId = NewTable::recInsert($data);

$data = [];
$data[]['title'] = '-- TITLE №1 --';
$data[]['title'] = '-- TITLE №2 --';
$data[]['title'] = '-- TITLE №3 --';
$insertIds = NewTable::recInsertMultiple($data); // Inserted ID 99,9% authenticity :)

////////////////////////////////////////////////////////////////////////////////////////
// UPDATE
// ModelName::recUpdate($id || $filter || $callback, $data); // return affectedCount
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$data = [];
$data['title'] = '-- TITLE --';
$affectedCount = NewTable::recUpdate($recordId, $data);
if ($affectedCount > 0) {
    echo 'Successfully ' . $affectedCount;
}

$data = [];
$data['title'] = '-- TITLE --';
$affectedCount = NewTable::recUpdate('full'); // Update all

////////////////////////////////////////////////////////////////////////////////////////
// DELETE 
// ModelName::recDelete($id || $filter || $callback, $destroy); // return affectedCount
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$destroy = true; // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
$affectedCount = NewTable::recDelete($recordId, $destroy);
if ($affectedCount > 0) {
    echo 'Successfully ' . $affectedCount;
}

$destroy = true; // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
$affectedCount = NewTable::recDelete('full',$destroy); // Truncate
$affectedCount = NewTable::recDelete('full');

////////////////////////////////////////////////////////////////////////////////////////
// RELATIONSHIPS ->withoutGLobalScopes() always!!!
// Create and remove links between table records
////////////////////////////////////////////////////////////////////////////////////////

// !!! WARNING !!! ->withoutGLobalScopes() always !!! - sys_file_reference
// $relationship, $recordId, $idsToAttach(Detach) - sys_file
NewTable::mediaAttach('propmedia_NAME', 1, [1, 2, 3]);
NewTable::mediaAttach('propmedia_NAME', 1, 'fileadmin/ftpupload/1.jpg'); // or 1:/ftpupload/1.jpg
NewTable::mediaDetach('propmedia_NAME', 1, [1, 2, 3]);
NewTable::mediaDetach('propmedia_NAME', 1, null || 'all'); // detach all
NewTable::mediaDetach('propmedia_NAME', 1, 'fileadmin/ftpupload/1.jpg');

// !!! WARNING !!! ->withoutGLobalScopes() always !!!
// $relationship, $recordId, $idsToAttach(Detach)
NewTable::refAttach('propref_NAME', 1, [3, 4]);
NewTable::refDetach('propref_NAME', 1, 4);
NewTable::refDetach('propref_NAME', 1, null || 'all'); // detach all
NewTable::refCollection('propref_NAME', 1);
NewTable::refUpdatePivot(); // todo
NewTable::refSort(); // todo

 * Rel_1To1 / hasOne()
 * Rel_1ToM / hasMany()
   -------------------------------------------------------------------------------------------

 * Rel_MTo1 / belongsTo()
 * Rel_1To1_Inverse / belongsTo()
 * Rel_1ToM_Inverse / belongsTo()
   -------------------------------------------------------------------------------------------

 * Rel_MToM / belongsToMany()
 * Rel_MToM_Inverse / belongsToMany()
 * Pivot model: [Litovchenko\AirTable\Domain\Model\SysMm], pivot table: [sys_mm]
   -------------------------------------------------------------------------------------------

 * Rel_Poly_1To1 / morphOne()
 * Rel_Poly_1ToM / morphMany()
 * Rel_Poly_MToM // todo
 * Rel_Poly_1To1_Inverse // todo
 * Rel_Poly_1ToM_Inverse // todo
 * Rel_Poly_MToM_Inverse // todo
   -------------------------------------------------------------------------------------------

////////////////////////////////////////////////////////////////////////////////////////
// ADDING CUSTOM FUNCTIONS TO THE MODEL
////////////////////////////////////////////////////////////////////////////////////////

// A) Global scope (user function global scope register)
// See example: globalScopeFlagDeleted();
// $rows = NewTable::get(); // Results sorted by default by uid field
// $rows = NewTable::withoutGlobalScope('mySorting')->get(); // No sorting by default
public function globalScopeMySorting($builder) {
    $builder->orderBy('uid','desc');
}

// B) Local scope (user function local scope register)
// See example: scopeMyPagination();
// $rows = NewTable::myWhereActive(1,2)->get();
public function scopeMyWhereActive($query, $agr1 = 5, $arg2 = 4){
    return $query->where('uid','>',$agr1)->where('uid','<',$arg2);
}

// C) Relationship (user function register)
// $rows = NewTable::with('myref_relationship')->get();
public function myref_relationship() {
    return $this->refProvider('propref_***'); // Rel_1To1, Rel_1ToM, Rel_MTo1...
}

// D) Repository Pattern (getBy***)
// Todo
// Лучше использовать recSelect() + ...->addScope('active');
// --- $result_1 = NewTable::getById(230,'title'); // $id, $fields
// --- $result_2 = NewTable::getByList('title'); // $fields...

// E) Nested Set
// Todo 

////////////////////////////////////////////////////////////////////////////////////////
// OTHER
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 18;
$is = NewTable::recIsDeleted($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
if($is === true) {
    echo 'Yes';
}

$recordId = 18;
$is = NewTable::recIsDisabled($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Disabled;
if($is === true) {
    echo 'Yes';
}

$recordId = 18;
$is = NewTable::recIsPublished($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\DateStart and DateEnd;
if($is === true) {
    echo 'Yes';
}
```

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

--

## 19 Useful settings in "typo3conf/LocalConfiguration.php"

```php
<?php
return [
    'BE' => [
        'debug' => false
    ],
    'SYS' => [
        'displayErrors' => 0,
        'belogErrorReporting' => 0,
        'errorHandlerErrors' => 0,
        'exceptionalErrors' => 0,
        'syslogErrorReporting' => 0,
        'systemMaintainers' => [
            1, // User ID Administrator "Admin Tools"
            3, // User ID Administrator "Admin Tools"
        ]
    ]
];
```

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

## Fluid: Alternative template syntax 

```html
{fluidanotherdelimiter}

<div class="tx-myext-gallery-wrap">

   <!--Ignore processing [[f: ... =``]] {{{$ var }}}-->
   [[f:for each=`{{{ $images }}}` as=`image`]]
   {{{ $image }}}
   [[/f:for]]

   <!--Processing-->
   [f:for each=`{{ $images }}` as=`image`]
   <div class="tx-myext-gallery-item">
      <a href="[f:uri.image src=`{{ $image.file.uid }}` /]" data-fancybox="gallery">
      <img src="[f:uri.image src=`{{ $image.file.uid }}` width=`272` height=`300c` /]">
      </a>
   </div>
   [/f:for]

</div>
```

## Functional development plans 

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
* Permissions backend user (non admin!) for root page id (pid)=0;
* Create new content element "WizardItems" for root page id (pid)=0;
* Splitting records into storages (analogous to folders in the tree of pages and EXT:tt_news)
* [Оптимизация] Синяя молния (пересмотреть в SqlController.php затирку всей таблицы ::truncate() на альтернативный алгоритм)
  * Оптимизировать генерацию TCA (сохранять сгенерированную TCA-в кэш), всегда будут вызываться только функции postBuildConfiguration() всех моделей
  * Генерацию ext_tables.sql делать всегда при изменении файлов моделей 
* Сопоставление полей модели и Flux-полей (за основу взять config - flux-полей)
* FAL: Категоризация файлов (коллекции) - идея добавить в D+ модуль фильтрации по тэгам - мои файлы, общие файлы, файлы таблиц
* getFileByHash () для загрузки файлов (что бы файл не пропадал!)
* ~~Default Assign (t3page, t3data, ...)~~
* Валидация аргументов роутера
* Ajax link helper - <f:link.action route="Ext.Pages.Widgets.RandPhoto.index" eIdAjax="true" eIdAjaxSettings(или eIdAjaxParams?)="{imgWidthBig:640,imgWidthSmall:300}">Ajax link</f:link.action>

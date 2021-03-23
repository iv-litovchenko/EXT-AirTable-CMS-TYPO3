# EXT: AirTable (CMS TYPO3)

A set of tools for creating your site based on class annotations. Works in versions TYPO3 v9, v10. The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway.

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
* 17 [Database queries: SELECT, INSERT, UPDATE, DELETE, RELATIONSHIPS, VALIDATION (Eloquent ORM)](#17-database-queries-select-insert-update-delete-relationships-validation-eloquent-orm)
* 18 [Frontend editing](#18-frontend-editing)
* 19 [Useful settings in "typo3conf/LocalConfiguration.php"](#19-useful-settings-in-typo3conflocalconfigurationphp)
* 20 [Extbase Frontend AJAX (http://your-site.com/?eIdAjax=1)](#20-extbase-frontend-ajax-httpyour-sitecomeidajax1)
* 21 [Extbase Example of working with forms](#21-extbase-example-of-working-with-forms)
* 22 [Functional development plans](#22-functional-development-plans)

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
    "javoscript/laravel-macroable-models": "1.0.4",
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
EXT:myext/Resources/Private/Templates/_Layouts/
EXT:myext/Resources/Private/Templates/_Partial/
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
        'ajaxActions' => 'indexAction', // Todo
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
<f:be.link route="routeExtMyext.NewModule2.Index" parameters="{test: 92}">Go to Module 2</f:be.link><br />
<f:be.link route="routeExtMyext.NewModule3.Index" parameters="{test: 92}">Go to Module 3</f:be.link><br />

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
        'nonСachedActions' => 'indexAction', // USER_INT
        'ajaxActions' => 'indexAction', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=***|***|*** - See "Ajax-Frontend"
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
        'thisIs' => 'FrontendContentElement || FrontendContentGridElement || FrontendContentPlugin',
        'name' => 'Content element name',
        'description' => 'Content element description',
        'nonСachedActions' => 'indexAction', // USER_INT
        'ajaxActions' => 'indexAction', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=***|***|*** - See "Ajax-Frontend"
        'fieldsExcludeList' => 'header_position,date',
        'fieldsAddList' => 'imageorient',
        'type' => 'Element || GridElement || Plugin', // Todo "Plugin routing support"
        'cols' => '1,2,3|4,5 || container', // If type "GridElement" // EXT:gridelements
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
            'gridId', // If type "GridElement" // EXT:gridelements
            $this->configurationManager->getContentObject()->data['uid']
        );
    }

    // If type "Plugin"
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
	<h3>Hellow word "Element || GridElement || Plugin" {var}!</h3>
	
	<!--If type "GridElement" // EXT:gridelements-->
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
</div>
```

## 07 Additional View Helper

### Content
```
<f:vhsExtAirTable.content colPos="2" /> <!--Page content-->
<f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="1" /> <!--Gridelements content-->
<f:vhsExtAirTable.content model="Mynamespace\Myext\Domain\Model\NewTable" uid="2" /> <!--Record content-->
```

### Object
```
<f:vhsExtAirTable.object setup="lib.tx_myext.menu" />
<f:vhsExtAirTable.object setup="lib.tx_myext.breadCrumb" />
```

### Marker
```
<!--Input, Text, Text.Rte, Text.Code.Html, Text.Code.TypoScript-->
<f:vhsExtAirTable.marker uid="3" />

<!--Media_1, Media_M-->
<f:vhsExtAirTable.markerMedia uid="45" as="row || rows">
  <f:for each="{rows}" as="row" key="itemkey">
    <a href="<f:uri.image src='{row.uid_local}' />">
      {itemkey+1}.<f:image src="{row.uid_local}" alt="alt text" width="100" /><br />
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
        'nonСachedActions' => 'indexAction', // USER_INT
        'ajaxActions' => 'indexAction', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=***|***|*** - See "Ajax-Frontend"
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
        // Turn on the setting in "LocalConfiguration.php" to see the names of columns and tabs "BE -> debug -> true"
        // Example 'position' => ['fields']['prop_***']['position']['type' => 'tab,position number'] add to type "1" (RType)
        // Example 'position' => ['fields']['prop_***']['position']['*' => 'mytab,100'] "*" adding to all types
        // Example 'position' => ['fields']['prop_***']['position']['1' => 'mytab,100'] add to type "1" (RType)
        'tabs' => [
            'mytab' => 'My Tab (###COUNT###)',
            'tabKeyOne' => 'Tab 1 (###COUNT###)',
            'tabKeyTwo' => 'Tab 2 (###COUNT###)',
            'tabKeyTtree' => 'Tab 3 (###COUNT###)',
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
                'position' => [
                    // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                    '*' => 'media,10',
                ],
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
                'show' => 1,
                'maxItems' => 10,
                'position' => [
                    // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                    '*' => 'media,10',
                ],
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
                'position' => [
                    // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                    '*' => 'rels,10',
                ],
            ],
            // ...
        ],
    ];

    /**
     * A set of rules for context-aware validation
     * @return array
     */
    public static function validationRules()
    {
        $rules = [
            'checkInsert' => [
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
            'checkUpdate' => [
                // context update...
            ],
            'checkDelete' => [
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

\Litovchenko\AirTable\Domain\Model\SysMm; // All links of type "Rel_MToM" are stored here
\Litovchenko\AirTable\Domain\Model\SysNote;
\Litovchenko\AirTable\Domain\Model\SysRedirect;

\Litovchenko\AirTable\Domain\Model\Users\BeGroups;
\Litovchenko\AirTable\Domain\Model\Users\BeUsers;
\Litovchenko\AirTable\Domain\Model\Users\FeGroups;
\Litovchenko\AirTable\Domain\Model\Users\FeUsers;
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
        'tabs' => [
            'newtab' => 'EXT:Myext New Tab (###COUNT###)'
        ],
        'baseFields' => [
            'RType' => [
                'items' => [
                    '100' => 'New Type',
                ]
            ],
        ],
        'dataFields' => [
            'prop_tx_myext_incrandphoto' => [
                'type' => 'Flag',
                'name' => 'New field',
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
+ link, redirect, forward

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
```

### Useful notes - Fluid
```html
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

<f:render partial="Missing" optional="1" default="Partial 1 not found" />
<f:render partial="AlsoMissing" optional="1">
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
   <a href="<f:uri.image src='{row.uid_local}' />">
      {itemkey+1}.
      <f:image src="{row.uid_local}" alt="alt text" width="100" />
      <br />
   </a>
</f:for>

*********************
* Comment
*********************

<f:comment>
   ---
</f:comment>

```

### Useful notes - TypoScript
```

--

```

## 17 Database queries: SELECT, INSERT, UPDATE, DELETE, RELATIONSHIPS, VALIDATION (Eloquent ORM)

```php
<?php
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// SELECT
// NewTable::recSelect('medthod', $id || $filter || $callback); // return result
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$rowFirst = NewTable::recSelect('first', $recordId);
$rowExists = NewTable::recSelect('exists', $recordId); // ->exists() (if), ->doesntExist() (if)
$rowsCount = NewTable::recSelect('count'); // count
$rowsGet = NewTable::recSelect('get'); // all
$obj = NewTable::recSelect('obj',[])->...->get(); // return obj (to create subqueries)
$sql = NewTable::recSelect('obj',[])->toSql();
$dd = NewTable::recSelect('obj',[])->dd(); // debugging
$dump = NewTable::recSelect('obj',[])->dump(); // debugging

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
$filter['addSelect'] = ['pid','date_create'];

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

$filter['inRandomOrder'] = false; // true
$filter['orderBy.10'] = ['uid','desc'];
$filter['orderBy.20'] = ['title','desc'];
$filter['groupBy'] = 'title';

$filter['limit'] = 3;
$filter['offset'] = 0;
$filter['having'] = ['aliasID', '>', 0]; // orHaving, havingRaw

// ->with(), ->has(), ->whereHas(), ->doesntHave(), ->whereDoesntHave(), ->withCount()
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

// ->unionAll() // $subQ = NewTable::recSelect('obj', $filter);
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

$rows = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('get',$filter);

////////////////////////////////////////////////////////////////////////////////////////
// INSERT
// ModelName::recInsert($data); // return last insert id
////////////////////////////////////////////////////////////////////////////////////////

$data = [];
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

NewTable::refAttach('propref_NAME', 1, [3, 4]); // $relationship, $parentId, $idsToAttach ->withoutGLobalScopes() always!!!
NewTable::refDetach('propref_NAME', 1, 4); // $relationship, $parentId, $idsToDetach ->withoutGLobalScopes() always!!!
NewTable::refDetach('propref_NAME', 1, []); // detach all ->withoutGLobalScopes() always!!!
NewTable::refCollection('propref_NAME', 1); // $relationship, $parentId ->withoutGLobalScopes() always!!!
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
// $result_1 = NewTable::getById(230,'title'); // $id, $fields
// $result_2 = NewTable::getByList('title'); // $fields...

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

////////////////////////////////////////////////////////////////////////////////////////
// VALIDATION
// v1) ModelCrud::validation($context,$data); // see "public static function validationRules()"
// v2) ModelForm::validation($context,$data); // see "public static function validationRules()"
// v3) ModelDynamic::validation($rules,$data); // Todo
////////////////////////////////////////////////////////////////////////////////////////

$context = 'checkInsert';
$data = [];
$data['title'] = 'My Title';

$validator = NewTable::validation($context, $data); // class NewTable extends \Litovchenko\AirTable\Domain\Model\ModelCrud
// $validator = NewForm::validation($context, $data); // class NewForm extends \Litovchenko\AirTable\Domain\Form\ModelForm
if ($validator->fails())
{
    $messages = $validator->messages()->toArray();
    $errors = $validator->errors();
    $errorsAll = $validator->errors()->all();
    print "<pre>";
    print_r($messages);
    print_r($errorsAll);
    print_r($errors);
    print "</pre>";
}

print '<hr >';

$data = [];
$data['title'] = 'My Title';

$rules = [];
$rules = [
    'title' => [
        'required' => 'MSG ERROR - required',
        'min:1' => 'MSG ERROR - min', 
        'max:5' => 'MSG ERROR - max'
    ]
];

// Todo
$validator = \Litovchenko\AirTable\Domain\Model\ModelDynamic::validation($rules, $data);
if ($validator->fails())
{
    $messages = $validator->messages()->toArray();
    $errors = $validator->errors();
    $errorsAll = $validator->errors()->all();
    print "<pre>";
    print_r($messages);
    print_r($errorsAll);
    print_r($errors);
    print "</pre>";
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
    //    noCacheHash="true" 
    //    additionalParams="{eIdAjax:1, eIdAjaxPath:'myext|RandPhotoController|index', eIdAjaxSettings: {imgWidthBig:640,imgWidthSmall:300}}"
    // >
    //    Ajax link
    // </f:link.action>
    // <f:uri.action ... />
    //*****************************************************************//
    $('body').on('click', 'a.tx-myext-randphotocontroller', function() {
        $('div.tx-myext-randphotocontroller-wrap').fadeTo("fast", 0.5);
        $.ajax({
            type: 'GET',
            url: "/?eIdAjax=1&eIdAjaxPath=myext|RandPhotoController|index", //  EXT:myext | Classes/Controllers/... | indexAction()
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
    //    noCacheHash="true" 
    //    additionalParams="{eIdAjax:1, eIdAjaxPath:'myext|FeedBackFormController|index', eIdAjaxSettings:{}}"
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
            type: 'POST',
            url: "/?eIdAjax=1&eIdAjaxPath=myext|FeedBackFormController|index", //  EXT:myext | Classes/Controllers/... | indexAction()
            data: $(this).serializeArray(),
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
        'nonСachedActions' => 'indexAction',
        'ajaxActions' => 'indexAction',
    ];

    public function indexAction()
    {
        #*************************************************************
        # Form
        #*************************************************************
        // if(\TYPO3\CMS\Core\Utility\GeneralUtility::_POST()) {
        if ($this->request->hasArgument('form')) {
            $postArgs = $this->request->getArguments()['form'];
            $validator = \Litovchenko\Projiv\Domain\Form\FeedBackForm::validation('default', $postArgs);
            if ($validator->fails()) {
                unset($postArgs['agree']);
                $this->view->assign('form', $postArgs);
                $this->view->assign('formValidator', $validator);
                // $this->addFlashMessage('Форма содержит ошибки!', 'Ошибки в форме', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR); // ERROR
            } else {
                $this->view->setTemplatePathAndFilename('EXT:projiv/Resources/Private/Templates/Widgets/FeedBackForm/Thanks.html');
                $this->sendMail($postArgs);
                // $this->addFlashMessage('Форма прошла проверку', 'Спасибо за обращение', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK); // Cool
            }
        }
        $this->view->assign('q', \Litovchenko\Projiv\Domain\Form\FeedBackForm::$q);
    }

    public function sendMail($postArgs)
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
    public static function validationRules()
    {
        $rules = [
            'default' => [
                'name' => [
                    'required' => 'Как вас зовут?',
                    'min:2' => 'Имя не менее 2 символов!',
                    'max:5' => 'Имя не более 5 символов!',
                    // 'myrule_name:parameter' => 'Ошибка (кастомный валидатор)!',
                ],
                'email' => [
                    'required' => 'Поле обязательно к заполнению',
                    'email' => 'Не правильно указан Email-адрес',
                ],
                'q' => [
                    'not_in:0' => 'Выберите вопрос',
                ],
                'message' => [
                    'required' => 'Введите сообщение',
                    'min:10' => 'Сообщение из мене чем 10 символов малоинформативно!',
                ],
                'phone' => [
                    'required' => 'Укажите телефон',
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
         noCacheHash="true" 
         additionalParams="{eIdAjax:1,eIdAjaxPath:'projiv|FeedBackFormController|index'}"
         asErrors="propertyErrors"
         >
         <div class="form-group">
            <label class="col-form-label">Ваше имя*</label>
            <f:if condition="{propertyErrors.name}">
               <span class="error text-danger">{propertyErrors.name.0}</span>
            </f:if>
            <f:form.textfield property="name" class="form-control" />
         </div>
         <div class="form-group">
            <label>Email*</label>
            <f:if condition="{propertyErrors.email}">
               <span class="error text-danger">{propertyErrors.email.0}</span>
            </f:if>
            <f:form.textfield property="email" class="form-control" placeholder="name@example.com" />
         </div>
         <div class="form-group">
            <label>Контактный телефон*</label>
            <f:if condition="{propertyErrors.phone}">
               <span class="error text-danger">{propertyErrors.phone.0}</span>
            </f:if>
            <f:form.textfield property="phone" class="form-control" />
         </div>
         <div class="form-group">
            <label>Вопрос*</label>
            <f:if condition="{propertyErrors.q}">
               <span class="error text-danger">{propertyErrors.q.0}</span>
            </f:if>
            <f:form.select property="q" class="form-control" options="{q}" />
         </div>
         <div class="form-group">
            <label>Сообщение*</label>
            <f:if condition="{propertyErrors.message}">
               <span class="error text-danger">{propertyErrors.message.0}</span>
            </f:if>
            <f:form.textarea property="message" class="form-control" rows="3" />
         </div>
         <div class="form-check">
            <label class="form-check-label">
               <f:form.checkbox property="agree" class="form-check-input" value="1" />
               Согласен на обработку персональных данных*...
            </label>
            <f:if condition="{propertyErrors.agree}">
               <br /><span class="error text-danger">{propertyErrors.agree.0}</span>
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

## 22 Functional development plans 

* Маршрутизация на основе аннотаций (WW)
* pageIdContent (подхват контроллера для конкретной страницы)
* Конфигурация сайта: https://t3terminal.com/blog/typo3-site-configuration/
* Блоки настроек (Craf Settings)
* RTE https://akilli.github.io/ckeditor4-build-classic/demo/
  * (Mag) InsertVaribles (он же плайсхолдер, чанк, сниппет, шорткод [bb], [bb] [/bb], [bb arg=””])
  * (Mag) InsertWidgets
  * InsertBlockTemplate (template ID) 
* Регионы в шаблоны (показ определенных блоков по условиям)
* <v:page.breadCrumb>, <v:menu expandAll="0" levels="2" /> для а) таблицы категорий (tx_data_category), для массива данных (аналоги: https://extensions.typo3.org/extension/nsb_cat2menu/, https://stackoverflow.com/questions/40706825/typo3-sys-category-menu
* (Wrapper) Обертка-контроллер для элементов содержимого (styles.templates.layoutRootPath = EXT:/Resources/Private/Layouts/)
* (Overriding) Переопределение шаблонов стандартных элементов содержимого, дополнительные шаблоны (Overriding templates of standard content elements (using the "layout" field) - EXT:fluidcontent_core, https://kronova.net/tutorials/typo3/extbase-fluid/additional-headers-in-fluid-styled-content.html)
* Permissions backend user (non admin!) for root page id (pid)=0;
* Create new content element "WizardItems" for root page id (pid)=0;
* Splitting records into storages (analogous to folders in the tree of pages and EXT:tt_news)
* Page template (with controller), url-path (LinkHandler) for tx_data (Similar to WW post templates)
* Pages VS TxData (Maybe it's kindred spirits like in WW)???
* Синяя молния (пересмотреть в SqlController.php затирку всей таблицы ::truncate() на альтернативный алгоритм)
* Категоризация файлов (коллекции) - идея добавить в D+ модуль фильтрации по тэгам - мои файлы, общие файлы, файлы таблиц
* Flexform (может пригодится для атрибутов)
https://www.medienreaktor.de/blog/dynamische-backend-formulare-in-typo3-mit-flux
https://www.medienreaktor.de/blog/dynamic-backend-forms-in-typo3-using-flux
* EditIcons для меню "lib.custommenu = HMENU" (будет еще 1 класс <f:EditIconOnlyHover(Abs)> - показать кнопки только при наведении на объект)
  * https://gist.github.com/mawo/f3a49058c3f4fb666c5162d8b77f1ceb
```
# ************************
# CUSTOM MENU
# ************************
lib.custommenu = HMENU
lib.custommenu {
   # special = userfunction
   # special.userFunc = Vendor\MyExtension\Userfuncs\CustomMenu->makeMenuArray

   1 = TMENU
   1.wrap = <ul class="level-1">|</ul>
   1.NO = 1
   1.NO {
      wrapItemAndSub = <li>|</li>
	  wrapItemAndSub.stdWrap.editPanel = 1
	  wrapItemAndSub.stdWrap.editPanel.tableName = pages
   }
```

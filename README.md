# EXT: AirTable (CMS TYPO3)

A set of tools for creating your site based on class annotations. Works in versions TYPO3 v7, v8, v9, v10 and v11. The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway.

## Table of content

* [Demo site online](#demo-site-online)
* [How to install?](#how-to-install)
* [Extension structure](#extension-structure)
* [Register a new admin module](#register-a-new-admin-module)
* [Register a new page template](#register-a-new-page-template)
* [Register a new content element](#register-a-new-content-element)
* [Register View Helper](#register-view-helper)
* [Register Widget (View Helper with controller and template)](#register-widget-view-helper-with-controller-and-template)
* [Register a new model (CRUD)](#register-a-new-model-crud)
* [Standard CRUD-models registered in the system for working with records](#standard-crud-models-registered-in-the-system-for-working-with-records)
* [Extending an existing model](#extending-an-existing-model)
* [List records (module)](#list-records-module)
* [Export records Xls|Csv (module)](#export-records-xlscsv-module)
* [Import records Xls|Csv (module)](#import-records-xlscsv-module)
* [Methods inside a class (extbase)](#methods-inside-a-class-extbase)
* [Database queries: SELECT, INSERT, UPDATE, DELETE, RELATIONSHIPS (Eloquent ORM)](#database-queries-select-insert-update-delete-relationships-eloquent-orm)
* [Frontend editing](#frontend-editing)
* [Useful settings in "typo3conf/LocalConfiguration.php"](#useful-settings-in-typo3conflocalconfigurationphp)
* [Functional development plans](#functional-development-plans)

## Demo site online

Coming soon! // Todo

## How to install?

Step 1) If, when installing a new version of the CMS TYPO3 system, the error "@mysqli.reconnect=1@" pops up - comment out this message in the file: "typo3/sysext/install/Classes/SystemEnvironment/DatabaseCheck/Driver/Mysqli.php". Then proceed with the installation.

Step 2) Install extension "air_table" via extension manager (https://extensions.typo3.org/extension/air_table/). The current version is under development!

Step 3) Update the composer in the folder "typo3conf/ext/air_table/"

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

If you are working in versions 7, 8 and you need "typo3/install.php" - for this you need to create a file "typo3conf/ENABLE_INSTALL_TOOL" with the content "KEEP_FILE".

## Extension structure

```
EXT:myext/Classes/Controller/
EXT:myext/Classes/Controller/Modules/[*]Controller.php
EXT:myext/Classes/Controller/Pages/[*]Controller.php
EXT:myext/Classes/Controller/PagesElements/[*]Controller.php
EXT:myext/Classes/Controller/Widgets/[*]WidgetController.php
EXT:myext/Classes/ViewHelpers/[*]ViewHelper.php

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
EXT:myext/Resources/Private/Language/Localling.Module.[Name].xlf
EXT:myext/Resources/Private/Language/Localling.Module-Section.[Name].xlf

EXT:myext/Resources/Private/Partials/[*].html
EXT:myext/Resources/Private/Partials/Footer.html
EXT:myext/Resources/Private/Partials/Header.html
EXT:myext/Resources/Private/Templates/Modules/[*]/Index.html
EXT:myext/Resources/Private/Templates/Pages/[*]/Index.html
EXT:myext/Resources/Private/Templates/PagesElements/[*]/Index.html
EXT:myext/Resources/Private/Templates/Widgets/[*]Widget/Index.html

EXT:myext/Resources/Public/
EXT:myext/Resources/Public/Css/
EXT:myext/Resources/Public/Js/
EXT:myext/Resources/Public/Img/

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

## Register a new admin module
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-admin-module.png)

Step 1) Create a class EXT:myext/Classes/Controller/[SubFolder - Modules]/NewModule1Controller.php

```php
<?php
namespace Mynamespace\Myext\Controller\[SubFolder - Modules];

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
use Litovchenko\AirTable\Controller\AbstractModuleController;
use Litovchenko\AirTable\Utility\BaseUtility;

/**
 * @AirTable\Label:<Module 1>
 * @AirTable\Description:<Module 1 description>
 * @AirTable\AjaxActions:<indexAction> // Todo
 * @AirTable\Group:<web || file || user || help || content || tools || ext> // Todo "invisible"
 * @AirTable\Position:<100>
 */
class NewModule1Controller extends AbstractModuleController
{
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

## Register a new page template
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-page-template-1.png)

Step 1) Create a class EXT:myext/Classes/Controller/[SubFolder - Pages]/NewPageController.php

```php
<?php
namespace Mynamespace\Myext\Controller\[SubFolder - Pages];
use Litovchenko\AirTable\Controller\AbstractPageController;
/**
 * @AirTable\Label:<Page name>
 * @AirTable\Description:<Page description>
 * @AirTable\DisableAllHeaderCode:<0 || 1>
 * @AirTable\NonСachedActions:<indexAction> // USER_INT
 * @AirTable\AjaxActions:<indexAction> // Todo http://your-site.com/ajax/[ext]/[controller]/[action]/ (type?=888)
 * @AirTable\FieldsExcludeList:<subtitle,nav_title>
 * @AirTable\FieldsAddList:<subtitle,nav_title>
 * @AirTable\Cols:<0,1|2,3,4|5>
 */
class NewPageController extends AbstractPageController
{
    /**
     * @AirTable\Field:<Input>
     * @AirTable\Field\Label:<The field for this page element>
     * @AirTable\Field\Max:<100>
     * @AirTable\Field\Size:<24>
     */
    protected $attr_input;
	
    public function indexAction()
    {
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
<f:asset.script identifier="jQuery" src="https://code.jquery.com/jquery-3.1.1.min.js" />
<f:asset.script identifier="Bootstrap" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" />
<f:asset.css identifier="Bootstrap" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  />
<f:asset.css identifier="Main" href="EXT:myext/Resources/Public/Css/Main.css"  />

<!--Include header page template-->
<f:comment><!--<f:render partial="Header" arguments="{_all}" />--></f:comment>

	<!--Get page content-->
	<table border="1">
	<tr>
		<td>Hellow word {var}!</td>
	</tr>
	<tr>
		<td><f:content colPos="0" /></td>
		<td><f:content colPos="1" /></td>
	</tr>
	<tr>
		<td><f:content colPos="2" /></td>
		<td><f:content colPos="3" /></td>
		<td><f:content colPos="4" /></td>
	</tr>
	<tr>
		<td><f:content colPos="5" /></td>
		<td><f:content colPos="6" /></td>
	</tr>
	</table>

<!--Include footer page template-->
<f:comment><!--<f:render partial="Footer" arguments="{_all}" />--></f:comment>
```

## Register a new content element
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-content-element.png)

Step 1) Create a class EXT:myext/Classes/Controller/[SubFolder - PagesElements]/NewElementController.php

```php
<?php
namespace Mynamespace\Myext\Controller\[SubFolder - PagesElements];
use Litovchenko\AirTable\Controller\AbstractPageElementController;
/**
 * @AirTable\Label:<Content element name>
 * @AirTable\Description:<Content element description>
 * @AirTable\NonСachedActions:<indexAction> // USER_INT
 * @AirTable\AjaxActions:<indexAction> // Todo http://your-site.com/ajax/[ext]/[controller]/[action]/ (type?=888)
 * @AirTable\FieldsExcludeList:<header_position,date>
 * @AirTable\FieldsAddList:<imageorient>
 * @AirTable\Type:<Element || GridElement || Plugin> // Todo "Plugin routing support")
 * @AirTable\Cols:<1,2,3|4,5> // If @AirTable\Type:<GridElement> // EXT:gridelements
 */
class NewElementController extends AbstractPageElementController
{
    /**
     * @AirTable\Field:<Input>
     * @AirTable\Field\Label:<The field for this content element>
     * @AirTable\Field\Max:<100>
     * @AirTable\Field\Size:<24>
     */
    protected $attr_input;
	
    public function indexAction()
    {
        $this->view->assign('var', rand(1, 1000));
        $this->view->assign('gridId', $this->configurationManager->getContentObject()->data['uid']); // If @AirTable\Type:<GridElement> // EXT:gridelements
    }
    
    // If @AirTable\Type:<Plugin>
    public function detailAction()
    {
        $this->view->assign('var', rand(1, 1000));
    }
}
```

Step 2) Create template EXT:myext/Resources/Private/Templates/PagesElements/NewElement/Index.html

```html
<div style="padding: 25px; background: wheat; text-align: center;">
	<h3>Hellow word "Element || GridElement || Plugin" {var}!</h3>
	
	<!--If @AirTable\Type:<GridElement> // EXT:gridelements-->
	<table border="1" width="100%">
	<tr>
		<td><f:content gridContainerId="{gridId}" gridColumn="1" /></td>
		<td><f:content gridContainerId="{gridId}" gridColumn="2" /></td>
		<td><f:content gridContainerId="{gridId}" gridColumn="3" /></td>
		<td><f:content gridContainerId="{gridId}" gridColumn="4" /></td>
		<td><f:content gridContainerId="{gridId}" gridColumn="5" /></td>
	</tr>
	</table>
	
	<!--If @AirTable\Type:<Plugin>-->
	<f:link.action action="detail">Show me what's there!</f:link.action>
</div>
```

## Register View Helper

Step 1) Create a class EXT:myext/Classes/ViewHelpers/HelloWorldViewHelper.php

```php
<?php
namespace Mynamespace\Myext\ViewHelpers;
use Litovchenko\AirTable\ViewHelpers\AbstractViewHelper;
/**
 * @AirTable\Label:<Test Helper>
 * @AirTable\Description:<String output based on arguments >
 * @AirTable\RegisterArguments\testArg1:<integer || string || mixed || boolean || array>
 * @AirTable\RegisterArguments\testArg2:<integer,req>
 */
class HelloWorldViewHelper extends AbstractViewHelper
{
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


## Register Widget (View Helper with controller and template)

Step 1) Create a class EXT:myext/Classes/Controller/Widgets/TestWidgetController.php

```php
<?php
namespace Mynamespace\Myext\Controller\Widgets;
use Litovchenko\AirTable\Controller\AbstractWidgetController;
/**
 * @AirTable\Label:<Test widget>
 * @AirTable\Description:<The widget has a controller and a template>
 * @AirTable\NonСachedActions:<indexAction> // USER_INT
 * @AirTable\AjaxActions:<indexAction> // Todo http://your-site.com/ajax/[ext]/[controller]/[action]/ (type?=888)
 * @AirTable\RegisterArguments\testArg1:<integer || string || mixed || boolean || array>
 * @AirTable\RegisterArguments\testArg2:<string,req>
 * @AirTable\RegisterArguments\testArg3:<string,req>
 */
class TestWidgetController extends AbstractWidgetController
{
    public function indexAction()
    {
        $this->view->assign('testArg1', $this->settings['testArg1']);
        $this->view->assign('testArg2', $this->settings['testArg2']);
        $this->view->assign('testArg3', $this->settings['testArg3']);
    }
}
```

Step 2) Create template EXT:myext/Resources/Private/Templates/Widgets/TestWidget/Index.html

```html
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

## Register a new model (CRUD)

Step 1) Create a model EXT:myext/Classes/Domain/Model/[SubFolder]/NewTable.php

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-crud.png)

```php
<?php
namespace Mynamespace\Myext\Domain\Model\[SubFolder];
use Litovchenko\AirTable\Domain\Model\AbstractModelCrud;
/**
 * @AirTable\Label:<New table name>
 * @AirTable\Description:<New table description>
 * @AirTable\DefaultListTypeRender:<0 || 1 || 2 || 3>
 */
class NewTable extends AbstractModelCrud
{
    // use \Litovchenko\AirTable\Domain\Model\Traits\Pid; // A record can exist in any part of the site page tree
    // use \Litovchenko\AirTable\Domain\Model\Traits\RType; // See the function "baseRTypes ()"
    use \Litovchenko\AirTable\Domain\Model\Traits\Slug;
    use \Litovchenko\AirTable\Domain\Model\Traits\Title;
    use \Litovchenko\AirTable\Domain\Model\Traits\AltTitle;
    use \Litovchenko\AirTable\Domain\Model\Traits\Disabled;
    use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
    use \Litovchenko\AirTable\Domain\Model\Traits\Sorting;
    use \Litovchenko\AirTable\Domain\Model\Traits\DateCreate;
    use \Litovchenko\AirTable\Domain\Model\Traits\DateUpdate;
    use \Litovchenko\AirTable\Domain\Model\Traits\DateStart;
    use \Litovchenko\AirTable\Domain\Model\Traits\DateEnd;
    use \Litovchenko\AirTable\Domain\Model\Traits\ServiceNote;
    use \Litovchenko\AirTable\Domain\Model\Traits\BeUserRow;
    use \Litovchenko\AirTable\Domain\Model\Traits\TextAndPicPreview;
    use \Litovchenko\AirTable\Domain\Model\Traits\TextAndPicDetail;
    use \Litovchenko\AirTable\Domain\Model\Traits\TtContentRows; // <f:content table="tx_myext_newtable" uid="1" />
    use \Litovchenko\AirTable\Domain\Model\Traits\Thumbnail; // Image associated with the recording
    use \Litovchenko\AirTable\Domain\Model\Traits\Files;
    // use \Litovchenko\AirTable\Domain\Model\Traits\RelPolyDisplayForeignFields;
    // use \Litovchenko\AirTable\Domain\Model\Traits\EavRows;
    
	// Categorization
    // For this to work
    // 1) create a category model (TestTableCategory.php) in the current directory
    // 2) add trait "\Litovchenko\AirTable\Domain\Model\Traits\ParentRow" to model "NewTableCategory.php"
    use \Litovchenko\AirTable\Domain\Model\Traits\CategoryRows; // OR \CategoryRow
    
    /**
     * This is an optional feature.
     * Record types similar to "doktype (pages)" and "CType (tt_content)"
     * @return array
     */
    public static function baseRTypes()
    {
        // Example * @AirTable\Field\Position\*:<props,0> "*" adding to all types
        // Example * @AirTable\Field\Position\1:<props,0> add to type "1"
        // Example * @AirTable\Field\Position\2:<props,0> add to type "2"
        return [
            1 => 'Type 1',
            2 => 'Type 2',
            3 => 'Type 3'
        ];
    }

    /**
     * This is an optional feature.
     * Tabs for the edit form
     * @return array
     */
    public static function baseTabs()
    {
        // Turn on the setting in "LocalConfiguration.php" to see the names of columns and tabs
        // BE -> debug -> true
        // Example * @AirTable\Field\Position\<type>:<mytab,position>
        // Example * @AirTable\Field\Position\*:<props,0> "*" adding to all types
        // Example * @AirTable\Field\Position\1:<mytab,0> add to type "1"
        $tabs = parent::baseTabs();
        $tabs['mytab'] = 'MyTab (###COUNT###)';
        return $tabs;
    }

    /**
     * @AirTable\Field:<Input> || Input.Int || Input.Number || Input.Float || Input.Link || Input.Color || Input.Email || Input.Password || Input.InvisibleInt || Input.Invisible
     * @AirTable\Field\Label:<Input>
     * @AirTable\Field\LiveSearch:<1>
     * @AirTable\Field\Max:<100>
     * @AirTable\Field\Size:<24>
     */
    protected $prop_example_field_input;

    /**
     * @AirTable\Field:<Text> || Text.Rte || Text.Code || Text.Table || Text.Invisible
     * @AirTable\Field\Label:<Field text>
     * @AirTable\Field\Format:<css || html || javascript || php || typoscript || xml> // Text.Rte
     * @AirTable\Field\Preset:<default || full || default || ext_myext_preset> // Text.Code
     */
    protected $prop_example_field_text;

    /**
     * @AirTable\Field:<Date> || Date.DateTime || Date.Time || Date.TimeSec || Date.Year
     * @AirTable\Field\Label:<Field date>
     */
    protected $prop_example_field_date;

    /**
     * @AirTable\Field:<Media_1> || Media_1.Image || Media_1.Mix || Media_M...
     * @AirTable\Field:<Fal_1> || Fal_1.Image || Fal_1.Mix || Fal_M... // Todo rename 
     * @AirTable\Field\Label:<Media>
     * @AirTable\Field\MaxItems:<10>
     */
    protected $propmedia_example_field_media;
    protected $propfal_example_field_media; // Todo rename 

    /**
     * @AirTable\Field:<Flag>
     * @AirTable\Field\Label:<Flag>
     * @AirTable\Field\Items\1:<Checked>
     */
    protected $prop_example_field_flag;

    /**
     * @AirTable\Field:<Switcher> || Switcher.Int
     * @AirTable\Field\Label:<Switcher>
     * @AirTable\Field\ItemsProcFunc:<Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems>
     * @AirTable\Field\ItemsModel:<Litovchenko\AirTable\Domain\Model\Eav\SysAttributeOption>
     * @AirTable\Field\ItemsWhere:< AND sys_attribute_option.sys_attribute_row_id=5 >
     * @AirTable\Field\Items\0:<Zero>
     * @AirTable\Field\Items\1:<One>
     * @AirTable\Field\Items\2:<Two>
     * @AirTable\Field\Items\3:<Three>
     */
    protected $prop_example_field_switcher;

    /**
     * @AirTable\Field:<Enum>
     * @AirTable\Field\Label:<Enum>
     * @AirTable\Field\ItemsProcFunc:<Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems>
     * @AirTable\Field\ItemsModel:<Litovchenko\AirTable\Domain\Model\Eav\SysAttributeOption>
     * @AirTable\Field\ItemsWhere:< AND sys_attribute_option.sys_attribute_row_id=5 >
     * @AirTable\Field\Items\1:<One>
     * @AirTable\Field\Items\2:<Two>
     * @AirTable\Field\Items\3:<Three>
     */
    protected $prop_example_field_enum;

    /**
     * @AirTable\Field:<Rel_1To1>
     * @AirTable\Field\Label:<Rel_1To1>
     * @AirTable\Field\ForeignModel:<Mynamespace\Myext\Domain\Model\[SubFolder]\***>
     * @AirTable\Field\ForeignKey:<***>
     * @AirTable\Field\ForeignParentKey:<parent_id> // Only (Rel_MToM.Tree || Rel_MTo1.Tree)
     * @AirTable\Field\ForeignWhere:< AND tx_data_category.RType=###REC_FIELD_RType### > // See "foreign_table_where"
     * @AirTable\Field\ForeignDefaults\CType:<image> // See "foreign_record_defaults"
     * @AirTable\Field\Show:<1>
     */
    protected $proptblref_[prefix]_tablename_row; // Rel_1To1, "ForeignKey": proptblref_exampletable_row_id
    protected $proptblref_[prefix]_tablename_rows; // Rel_1ToM, "ForeignKey": proptblref_exampletable_row_id
    protected $proptblref_[prefix]_tablename_row_id; // Rel_MTo1 || Rel_MTo1.Large || Rel_MTo1.Tree, "ForeignKey": proptblref_exampletable_rows
    protected $proptblref_[prefix]_tablename_rows; // Rel_MToM || Rel_MToM.Large || Rel_MToM.Tree, "ForeignKey": proptblref_exampletable_rows
    protected $proptblref_[prefix]_tablename_row; // Rel_Poly_1To1, "ForeignKey": proptblref_exampletable_row
    protected $proptblref_[prefix]_tablename_rows; // Rel_Poly_1ToM, "ForeignKey": proptblref_exampletable_row
    
    /**
     * @AirTable\Field:<Input>
     * @AirTable\Field\Label:<Additional options for customizing the field>
     * @AirTable\Field\Description:<-------Description------->
     * @AirTable\Field\Placeholder:<-------Placeholder------->
     * @AirTable\Field\Default:<-------Default value------->
     * @AirTable\Field\Required:<1> // Required to fill
     * @AirTable\Field\ValidationRules\Required:<1> // Todo Validator
     * @AirTable\Field\ValidationRules\[Rule Name]:<1> // Todo Validator
     * @AirTable\Field\Show:<1> // Show field in lists
     * @AirTable\Field\OnChangeReload:<1> // Reload the form when the field value changes
     * @AirTable\Field\DisplayCond:<USER:Litovchenko\AirTable\Domain\Model\Content\Data->isVisibleDisplayConditionMatcher:tx_data> // Example
     * @AirTable\Field\DisplayCondAnd\1:<FIELD:disabled:=:1>
     * @AirTable\Field\DisplayCondAnd\2:<FIELD:prop_example_field_flag:=:1>
     * @AirTable\Field\DisplayCondOr\1:<FIELD:disabled:=:1>
     * @AirTable\Field\DisplayCondOr\2:<FIELD:prop_example_field_flag:=:1>
     * @AirTable\Field\Exclude:<1> // Todo
     */
    protected $prop_example_field_additional_options;

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
        } else  {
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
use Litovchenko\AirTable\Domain\Model\AbstractModelCrud;
/**
 * @AirTable\Label:<New table category name>
 * @AirTable\Description:<New table category description>
 */
class NewTableCategory extends AbstractModelCrud
{
    use \Litovchenko\AirTable\Domain\Model\Traits\Title;
    use \Litovchenko\AirTable\Domain\Model\Traits\Disabled;
    use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
    use \Litovchenko\AirTable\Domain\Model\Traits\Sorting;
    use \Litovchenko\AirTable\Domain\Model\Traits\ParentRow;
}
```

Step 3) Go to the module "Admin Tools" > "Maintenance" > "Rebuild PHP Autoload Information". Click the button "Dump autoload".

Step 4) Go to the module "Admin Tools" > "Maintenance" > "Analyze Database Structure". Click the button "Analyze database".

## Standard CRUD-models registered in the system for working with records

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

## Extending an existing model

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/extending-an-existing-model.png)

Step 1) Create a file EXT:myext/Classes/Domain/Model/Ext/ExtPages.php

Step 2) Create class inherited from base model

```php
<?php
namespace Mynamespace\Myext\Domain\Model\Ext;
use \Litovchenko\AirTable\Domain\Model\Content\Pages;

/**
 * @AirTable\Label:<From EXT:myext>
 * @AirTable\Description:<Adding fields to the page model>
 */

class ExtPages extends Pages
{
    /**
     * This is an optional feature.
     * Record types similar to "doktype (pages)" and "CType (tt_content)"
     * @return array
     */
    #public static function baseRTypes()
    #{
    #    // This function is not supported for standard models!
    #    // * @AirTable\Field\Position\*:<newtab,0>
    #    $types = parent::baseRTypes();
    #    $types[100] = 'New type 100';
    #    return $types;
    #}

    /**
     * This is an optional feature.
     * Tabs for the edit form
     * @return array
     */
    #public static function baseTabs()
    #{
    #    // This function is not supported for standard models!
    #    // * @AirTable\Field\Position\*:<newtab,0>
    #    $tabs = parent::baseTabs();
    #    $tabs['newtab'] = 'NewTab (###COUNT###)';
    #    return $tabs;
    #}

    /**
     * @AirTable\Field:<Text>
     * @AirTable\Field\Position\1:<extended,0>
     * @AirTable\Field\Label:<New field>
     */
    protected $prop_ext_myext_new_field;

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

## List records (module)

- // todo

## Export records Xls|Csv (module)

- // todo

## Import records Xls|Csv (module)

- // todo

## Methods inside a class (extbase)

```php

// todo
cache
render / assign
requst respone
log
debug
auth
cookie
flashmessage
context
controllerName
link, redirect, forward

$this->crud->query = $this->crud->query->withoutGlobalScopes();
$this->crud->model->clearGlobalScopes();

```

## Database queries: SELECT, INSERT, UPDATE, DELETE, RELATIONSHIPS (Eloquent ORM)

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
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

$filter = [];
$filter['withoutGlobalScopes'] = true; // or false
$filter['withoutGlobalScopes'] = ['FlagDeleted','FlagDeleted','DateStart', 'DateEnd'];
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
    'proptblref_exampletable1_row_func' => function($q) {
        $q->with('proptblref_exampletable_row_id_func');
        $q->where('uid','>',0);
        $q->where('pid','>',0);
    }
];
$filter['with.20'] = 'proptblref_exampletable2_rows_func.proptblref_exampletable_row_id_func';
$filter['with.30'] = 'proptblref_exampletable3_row_id_func';
$filter['with.40'] = 'proptblref_exampletable4_rows_func';

// ->unionAll() // $subQ = NewTable::recSelect('obj', $filter);
$filter['union'] = ...;
$filter['join'] = ...;
$filter['leftJoin'] = ...;
$filter['crossJoin'] = ...;

$filter['customSelectMinimize'] = true; // or false
$filter['customWhereFlagDeletedIn'] = [0,1]; // 0, 1, [0,1]
$filter['customWhereFlagDisabledIn'] = [0,1]; // 0, 1, [0,1]
$filter['customPagination'] = [30,1]; // $pageLimit, $pageNumber

$count = NewTable::recSelect('count', $filter);
$rows = NewTable::recSelect('get', $filter);

print "Count: " . $count . "<hr />";
foreach ($rows as $row) {
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
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
// Create and remove links between table records "[relname]_row(s)_func"
////////////////////////////////////////////////////////////////////////////////////////

NewTable::refAttach('category_rows_func', 1, [3, 4]); // $relationship, $parentId, $idsToAttach ->withoutGLobalScopes() always!!!
NewTable::refDetach('category_rows_func', 1, 4); // $relationship, $parentId, $idsToDetach ->withoutGLobalScopes() always!!!
NewTable::refDetach('category_rows_func', 1, []); // detach all ->withoutGLobalScopes() always!!!
NewTable::refCollection('category_rows_func', 1); // $relationship, $parentId ->withoutGLobalScopes() always!!!
NewTable::refUpdatePivot(); // todo
NewTable::refSort(); // todo

 * Rel_1To1 / hasOne() / $proptblref_[prefix]_tablename_row;
 * Rel_1ToM / hasMany() / $proptblref_[prefix]_tablename_rows;
   -------------------------------------------------------------------------------------------

 * Rel_MTo1 / belongsTo() / $proptblref_[prefix]_tablename_row_id;
 * Rel_1To1_Inverse / belongsTo() / $proptblref_[prefix]_tablename_row_id;
 * Rel_1ToM_Inverse / belongsTo() / $proptblref_[prefix]_tablename_row_id;
   -------------------------------------------------------------------------------------------

 * Rel_MToM / belongsToMany() / $proptblref_[prefix]_tablename_rows;
 * Rel_MToM_Inverse / belongsToMany() / $proptblref_[prefix]_tablename_rows;
 * Pivot model: [Litovchenko\AirTable\Domain\Model\SysMm], pivot table: [sys_mm]
   -------------------------------------------------------------------------------------------

 * Rel_Poly_1To1 / morphOne() / $proptblref_[prefix]_tablename_row;
 * Rel_Poly_1ToM / morphMany() / $proptblref_[prefix]_tablename_rows;
 * Rel_Poly_MToM // todo
 * Rel_Poly_1To1_Inverse // todo
 * Rel_Poly_1ToM_Inverse // todo
 * Rel_Poly_MToM_Inverse // todo
   -------------------------------------------------------------------------------------------

////////////////////////////////////////////////////////////////////////////////////////
// ADDING CUSTOM FUNCTIONS TO THE MODEL
////////////////////////////////////////////////////////////////////////////////////////

// A) Global scope (user function global scope register)
// See example: builderGsCustomFlagDeleted();
// $rows = NewTable::get(); // Results sorted by default by uid field
// $rows = NewTable::withoutGlobalScope('customNameGlobalCondition')->get(); // No sorting by default
public function builderGsCustomNameGlobalCondition($builder) { // builderUserGlobalScope[Name]()
    $builder->orderBy('uid','Desc');
}

// B) Local scope (user function local scope register)
// See example: builderLsCustomPagination();
// $rows = NewTable::customNameCondition(1,2)->get();
public function builderLsCustomNameCondition($agr1 = 5, $arg2 = 4){ // builderUserLocalScope[Name]()
    return $this->where('uid','>',$agr1)->where('uid','<',$arg2);
}

// C) Relationship (user function register)
// $rows = NewTable::with('customNameRelationship')->get();
public function builderRefCustomNameRelationship() { // builderUserRef[Name]()
    return $this->refProvider('proptblref_exampletable4_rows'); // Rel_1To1, Rel_1ToM, Rel_MTo1...
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
```

## Frontend editing

--

## Useful settings in "typo3conf/LocalConfiguration.php"

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

## Functional development plans 

• Маршрутизация на основе аннотаций (WW)
• Маршрутизация для Ajax на основе аннотаций
• Блоки настроек (Craf Settings)
• RTE
o (Mag) InsertVaribles (он же плайсхолдер, чанк, сниппет, шорткод [bb], [bb] [/bb], [bb arg=””])
o (Mag) InsertWidgets
o InsertBlockTemplate (template ID)
• Регионы в шаблоны (показ определенных блоков по условиям)
• Параграфы (Dr) – текст, картинка, файлы (матричные поля Craf), либо более удобный Wizzard
• <v:page.breadCrumb>, <v:menu expandAll="0" levels="2" /> для а) таблицы категорий (tx_data_category), для массива данных (аналоги: https://extensions.typo3.org/extension/nsb_cat2menu/, https://stackoverflow.com/questions/40706825/typo3-sys-category-menu
• (Wrapper) Обертка-контроллер для элементов содержимого (styles.templates.layoutRootPath = EXT:/Resources/Private/Layouts/)
• (Overriding) Переопределение шаблонов стандартных элементов содержимого (Overriding templates of standard content elements (using the "layout" field) - EXT:fluidcontent_core)
• Добавить поле "Статус" для записи вместо "Deleted, Disabled" (cart, inactive, on - active, review, draft) 
• Permissions backend user (non admin!) for root page id (pid)=0;
•  Create new content element "WizardItems" for root page id (pid)=0;
• Splitting records into storages (analogous to folders in the tree of pages and EXT:tt_news)
• Page template (with controller), url-path (LinkHandler) for tx_data (Similar to WW post templates)
• Pages VS TxData (Maybe it's kindred spirits like in WW)???
• Modify attributes postBuildConfiguration() TxData fields TCA)

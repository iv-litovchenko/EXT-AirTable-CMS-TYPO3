# EXT: AirTable (CMS TYPO3)

A set of tools for creating your site based on class annotations. Works in versions TYPO3 v7, v8, v9, v10 and v11. The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway.

## Contents

* [Demo site online](#demo-site-online)
* [How to install?](#how-to-install)
* [Extension structure](#extension-structure)
* [Register a new admin module](#register-a-new-admin-module)
* [Register a new page template](#register-a-new-page-template)
* [Register a new content element](#register-a-new-content-element)
* [Register a new model (CRUD)](#register-a-new-model-crud)
* [Standard CRUD-models registered in the system for working with records](#standard-crud-models-registered-in-the-system-for-working-with-records)
* [Extending an existing model](#extending-an-existing-model)
* [List records (module)](#export-records-module)
* [Export records Xls|Csv (module)](#export-records-module)
* [Import records Xls|Csv (module)](#import-records-module)
* [Methods inside a class (extbase)](#methods-inside-a-class-extbase)
* [Database queries SELECT, INSERT, UPDATE, DELETE, relationships between tables - Eloquent ORM (Laravel)](#database-queries-select-insert-update-delete-relationships-between-tables---eloquent-orm-laravel)
* [Useful settings in "typo3conf/LocalConfiguration.php"](#useful-settings-in-typo3conflocalconfigurationphp)

## Demo site online

Coming soon! // Todo

## How to install?

Step 1) If, when installing a new version of the CMS TYPO3 system, the error "@mysqli.reconnect=1@" pops up - comment out this message in the file: "typo3/sysext/install/Classes/SystemEnvironment/DatabaseCheck/Driver/Mysqli.php". Then proceed with the installation.

Step 2) Install extension "air_table" via extension manager (https://extensions.typo3.org/extension/air_table/).

Step 3) Update the composer in the folder "typo3conf/ext/air_table/"

```yaml
// command /usr/local/php-cgi/7.0/bin/php -d memory_limit=-1 /usr/local/bin/composer update --ignore-platform-reqs
"require-dev": {
    "illuminate/database": "^5.0",
    "rap2hpoutre/fast-excel": "^1.5.0"
}
```

Step 4) Go to the module "Admin Tools" > "Maintenance" > "Rebuild PHP Autoload Information". Click the button "Dump autoload".

Step 5) Add ":: IncTypoScript :: (air_table)" as the last item to the page template.

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-add-template.png)

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

EXT:myext/Classes/Domain/Model/Ex/
EXT:myext/Classes/Domain/Model/Ex/PagesEx.php // Мodify model
EXT:myext/Classes/Domain/Model/Ex/TtContentEx.php // Мodify model
EXT:myext/Classes/Domain/Model/Ex/OneModelEx.php // Мodify model

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

Step 1) Create controller EXT:myext/Classes/Controller/[SubFolder - Modules]/NewModule1Controller.php

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
 * @AirTable\Group:<tabmyext || web || file || user || help || content || extension || tools> // Todo "invisible"
 * @AirTable\Position:<100>
 */
class NewModule1Controller extends AbstractModuleController
{
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

<!--Pay attention to the route - not "tabmyext_MyextNewModule2", but "tabmyext_MyextNewmodule2"-->
<f:be.link route="tabmyext_MyextNewmodule2" parameters="{arg: 'val'}">Go to Module 2</f:be.link><br />

<!--Pay attention to the route - not "tabmyext_MyextNewModule3", but "tabmyext_MyextNewmodule3"-->
<f:be.link route="tabmyext_MyextNewmodule3" parameters="{arg: 'val'}">Go to Module 3</f:be.link><br />

<f:link.action action="edit" class="btn btn-default btn-sm">
	Module 1 (action "Edit")
</f:link.action>
```

Step 3) Create localling file:

EXT:myext/Resources/Private/Language/Localling.Module-Section.Tabmyext.xlf\
EXT:myext/Resources/Private/Language/Localling.Module.NewModule1.xlf

```xml
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<xliff version="1.0">
    <file source-language="en" datatype="plaintext" original="messages" product-name="myext">
        <header/>
        <body>
            <trans-unit id="mlang_tabs_tab" xml:space="preserve">
                <source>New section title</source>
            </trans-unit>
        </body>
    </file>
</xliff>

------

<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<xliff version="1.0">
    <file source-language="en" datatype="plaintext" original="messages" product-name="myext">
        <header/>
        <body>
            <trans-unit id="mlang_tabs_tab" xml:space="preserve">
                <source>New module 1 title</source>
            </trans-unit>
            <trans-unit id="mlang_labels_tablabel" xml:space="preserve">
                <source>New module 1 subtitle</source>
            </trans-unit>
            <trans-unit id="mlang_labels_tabdescr" xml:space="preserve">
                <source>New module 1 description</source>
            </trans-unit>
        </body>
    </file>
</xliff>
```

## Register a new page template
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Img/typo3-register-a-new-page-template-1.png)

Step 1) Create controller EXT:myext/Classes/Controller/[SubFolder - Pages]/NewPageController.php

```php
<?php
namespace Mynamespace\Myext\Controller\[SubFolder - Pages];
use Litovchenko\AirTable\Controller\AbstractPageController;
/**
 * @AirTable\Label:<Page name>
 * @AirTable\Description:<Page description>
 * @AirTable\DisableAllHeaderCode:<0 || 1>
 * @AirTable\NonСachedActions:<indexAction> // USER_INT
 * @AirTable\EidAjaxActions:<indexAction> // Todo http://your-site.com/ajax/ext/[controller]/[action]/
 * @AirTable\SubtypesExcludelist:<field1,field2,field3...> // Todo
 * @AirTable\SubtypesAddlist:<field1,field2,field3...> // Todo
 * @AirTable\Cols:<0,1|2,3,4|5>
 */
class NewPageController extends AbstractPageController
{
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

Step 1) Create controller EXT:myext/Classes/Controller/[SubFolder - PagesElements]/NewElementController.php

```php
<?php
namespace Mynamespace\Myext\Controller\[SubFolder - PagesElements];
use Litovchenko\AirTable\Controller\AbstractPageElementController;
/**
 * @AirTable\Label:<Content element name>
 * @AirTable\Description:<Content element description>
 * @AirTable\NonСachedActions:<indexAction> // USER_INT
 * @AirTable\EidAjaxActions:<indexAction> // Todo http://your-site.com/ajax/ext/[controller]/[action]/
 * @AirTable\Type:<Element || GridElement || Plugin> // Todo "Plugin routing support")
 * @AirTable\SubtypesExcludelist:<field1,field2,field3...> // Todo
 * @AirTable\SubtypesAddlist:<field1,field2,field3...> // Todo
 * @AirTable\Cols:<1,2,3|4,5> // If @AirTable\Type:<GridElement> // EXT:gridelements
 */
class NewElementController extends AbstractPageElementController
{
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
    use \Litovchenko\AirTable\Domain\Model\Traits\TtContentRows; // <f:content recordModel="Mynamespace\Myext\Domain\Model\NewTable" recordId="1" />
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
    protected $example_field_input;

    /**
     * @AirTable\Field:<Text> || Text.Rte || Text.Code || Text.Table || Text.Invisible
     * @AirTable\Field\Label:<Field text>
     * @AirTable\Field\Format:<css || html || javascript || php || typoscript || xml> // Text.Code
     */
    protected $example_field_text;

    /**
     * @AirTable\Field:<Date> || Date.DateTime || Date.Time || Date.TimeSec || Date.Year
     * @AirTable\Field\Label:<Field date>
     */
    protected $example_field_date;

    /**
     * @AirTable\Field:<Media> || Media.Image || Media.Mix
     * @AirTable\Field\Label:<Media>
     * @AirTable\Field\MaxItems:<10>
     */
    protected $example_field_media;

    /**
     * @AirTable\Field:<Flag>
     * @AirTable\Field\Label:<Flag>
     * @AirTable\Field\Items\1:<Checked>
     */
    protected $example_field_flag;

    /**
     * @AirTable\Field:<Switcher> || Switcher.Int
     * @AirTable\Field\Label:<Switcher>
     * @AirTable\Field\ItemsProcFunc:<Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems>
     * @AirTable\Field\Items\0:<Zero>
     * @AirTable\Field\Items\1:<One>
     * @AirTable\Field\Items\2:<Two>
     * @AirTable\Field\Items\3:<Three>
     */
    protected $example_field_switcher;

    /**
     * @AirTable\Field:<Enum>
     * @AirTable\Field\Label:<Enum>
     * @AirTable\Field\ItemsProcFunc:<Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems>
     * @AirTable\Field\Items\1:<One>
     * @AirTable\Field\Items\2:<Two>
     * @AirTable\Field\Items\3:<Three>
     */
    protected $example_field_enum;

    /**
     * @AirTable\Field:<Rel_1To1>
     * @AirTable\Field\Label:<Rel_1To1>
     * @AirTable\Field\ForeignModel:<Mynamespace\Myext\Domain\Model\[SubFolder]\***>
     * @AirTable\Field\ForeignKey:<***>
     * @AirTable\Field\ForeignParentKey:<parent_id> // Only (Rel_MToM.Tree || Rel_MTo1.Tree)
     * @AirTable\Field\Show:<1>
     */
    #protected $[prefix]_tablename_row; // Rel_1To1, "ForeignKey": exampletable_row_id
    #protected $[prefix]_tablename_rows; // Rel_1ToM, "ForeignKey": exampletable_row_id
    #protected $[prefix]_tablename_row_id; // Rel_MTo1 || Rel_MTo1.Large || Rel_MTo1.Tree, "ForeignKey": exampletable_rows
    #protected $[prefix]_tablename_rows; // Rel_MToM || Rel_MToM.Large || Rel_MToM.Tree, "ForeignKey": exampletable_rows
    #protected $[prefix]_tablename_row; // Rel_Poly_1To1, "ForeignKey": exampletable_row
    #protected $[prefix]_tablename_rows; // Rel_Poly_1ToM, "ForeignKey": exampletable_row
    
    /**
     * @AirTable\Field:<Input>
     * @AirTable\Field\Label:<Additional options for customizing the field>
     * @AirTable\Field\Description:<-------Description------->
     * @AirTable\Field\Placeholder:<-------Placeholder------->
     * @AirTable\Field\Default:<-------Default value------->
     * @AirTable\Field\Required:<1> // Required to fill
     * @AirTable\Field\Show:<1> // Show field in lists
     * @AirTable\Field\OnChangeReload:<1> // Reload the form when the field value changes
     * @AirTable\Field\DisplayCond\1:<FIELD:disabled:=:1>
     * @AirTable\Field\DisplayCond\2:<FIELD:example_field_flag:=:1>
     * @AirTable\Field\Exclude:<1> // Todo
     */
    protected $example_field_additional_options;

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
     * // Todo
     * @return '';
     */
    public static function cmdDatabaseSeeder()
    {
    }

    /**
     * Insert record event (before / after) - // Todo https://laravel.ru/posts/338
     * @return '';
     */
    public static function cmdInsert($when, &$table, $id, &$fieldArray)
    {
        if ($when == 'before') {
            //
        } else  {
            //
        }
    }

    /**
     * Record update event (before / after) - // Todo https://laravel.ru/posts/338
     * @return '';
     */
    public static function cmdUpdate($when, &$table, $id, &$fieldArray)
    {
        if ($when == 'before') {
            //
        }  else {
            //
        }
    }

    /**
     * Record deletion event (before / after) - // Todo https://laravel.ru/posts/338
     * @return '';
     */
    public static function cmdDelete($when, &$table, $id, &$fieldArray)
    {
        if ($when == 'before')  {
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
// A static block of content on a page with the ability to edit
// Step 1) Create an entry in the model "Marker"
// Step 2) Display the created entry in the desired place in the template
\Litovchenko\AirTable\Domain\Model\Content\Marker; // <f:marker id="5" /> 
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

Step 1) Create a file EXT:myext/Classes/Domain/Model/Ex/PagesEx.php

Step 2) Create class inherited from base model

```php
<?php
namespace Mynamespace\Myext\Domain\Model\Ex;
use \Litovchenko\AirTable\Domain\Model\Content\Pages;

/**
 * @AirTable\Label:<From EXT:myext>
 * @AirTable\Description:<Adding fields to the page model>
 */

class PagesEx extends Pages
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
    protected $ex_myext_new_field;

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

## Database queries SELECT, INSERT, UPDATE, DELETE, relationships between tables - Eloquent ORM (Laravel)

```php
<?php
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Litovchenko\AirTableExamples\Domain\Model\ExampleTable;
use Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// SELECT
// NewTable::recSelect('medthod', $id || $filter || $callback); // return result
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$rowsFirst = NewTable::recSelect('first', $recordId);
$rowsCount = NewTable::recSelect('count'); // count
$rowsGet = NewTable::recSelect('get'); // all
$method = NewTable::recSelect('exists', $recordId); // count, max, min, avg, sum, exists (if), doesntExist (if)
$obj = NewTable::recSelect('obj', [])->...->get(); // return obj (to create subqueries)

$limit = 10;
$rowsResult = NewTable::recSelect('count,get', function ($q) use ($limit) { 
    $q->limit($limit); 
});

print "Count: " . $rowsResult['count'] . "<hr />";
foreach ($rowsResult['get'] as $row) {
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

$recordId = 18;
$is = NewTable::recIsDeleted($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
if($is === true) {
    echo 'Yes';
} else {
    echo 'No';
}

$recordId = 18;
$is = NewTable::recIsDisabled($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Disabled;
if($is === true) {
    echo 'Yes';
} else {
    echo 'No';
}

$recordId = 18;
$is = NewTable::recIsPublished($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\DateStart and DateEnd;
if($is === true) {
    echo 'Yes';
} else {
    echo 'No';
}

$filter = [];
$filter['distinct'] = ['title'];
$filter['select'] = ['uid','title', 'uid as aliasID'];
$filter['addSelect'] = ['pid','date_create'];

$filter['where'] = []; // orWhere, =, <, >, <=, >=, <>, !=, LIKE, NOT LIKE, BETWEEN, ILIKE
$filter['where'][] = ['uid','>=',1];
$filter['where'][] = ['uid','<=',10000];

$filter['where'] = function($q) { 
    $q->where('pid','>=',0); 
    $q->orWhere('pid','<=',0);
};

$filter['whereIn'] = ['uid',[1,2,3,4,5,6,7,8,9,10]]; // orWhereIn, whereNotIn, orWhereNotIn
$filter['whereNull'] = ['keywords']; // orWhereNull, whereNotNull, orWhereNotNull 
$filter['whereBetween'] = ['uid',[1,1000]]; // whereNotBetween
$filter['whereColumn'] = ['uid','!=','title'];
$filter['whereRaw'] = ['(uid > ? and uid < ?)', [1,1000]]; // DB::raw(1)
$filter['whereRaw'] = [];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%d') = 11"];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%m') = 01"];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%Y') = 2021"];
$filter['whereExists'] = function($q) { // ->orWhereExists(), ->whereNotExists(), ->orWhereNotExists()
    $q->select(DB::raw(1))->from('pages')->whereRaw('uid > 0'); 
};

$filter['inRandomOrder'] = false; // true
$filter['orderBy'] = [];
$filter['orderBy'][] = ['uid','desc'];
$filter['orderBy'][] = ['title','desc'];
$filter['groupBy'] = 'title';

$filter['limit'] = 3;
$filter['offset'] = 0;
$filter['having'] = ['aliasID', '>', 0]; // orHaving, havingRaw

$filter['with'] = []; // has, whereHas
$filter['with'][]  = [
    'exampletable1_row_func' => function($q) {
        $q->with('exampletable_row_id_func');
        $q->where('uid','>',0);
        $q->where('pid','>',0);
    }
];

$filter['with'][] = ['exampletable2_rows_func.exampletable_row_id_func'];
$filter['with'][] = ['exampletable3_row_id_func'];
$filter['with'][] = ['exampletable4_rows_func'];

#$filter['union'] = ['']; // unionAll // $subQ = NewTable::recSelect('obj', $filter);
#$filter['join'] = ['contacts', 'users.id', '=', 'contacts.user_id'];
#$filter['leftJoin'] = ['posts', 'users.id', '=', 'posts.user_id'];
#$filter['crossJoin'] = ['posts'];

$filter['withoutGlobalScopes'] = true;
$filter['withoutGlobalScope'] = ['FlagDeleted','FlagDeleted','DateStart', 'DateEnd'];

$filter['userWherePid'] = 10;
$filter['userWhereUid'] = 4;
$filter['userWhereFlagDeleted'] = [0,1]; // 0, 1, [0,1]
$filter['userWhereFlagDisabled'] = [0,1]; // 0, 1, [0,1]

$sql = NewTable::recSelect('toSql', $filter);
$count = NewTable::recSelect('count', $filter);
$rows = NewTable::recSelect('get', $filter);

print "Sql: " . $sql . "<hr />";
print "Count: " . $count . "<hr />";
foreach ($rows as $row) {
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

////////////////////////////////////////////////////////////////////////////////////////
// INSERT
// ModelName::recInsert($data); // return last insert id
////////////////////////////////////////////////////////////////////////////////////////
$data = [];
$data['title'] = '-- TITLE --';
$insertId = NewTable::recInsert($data);

////////////////////////////////////////////////////////////////////////////////////////
// UPDATE
// ModelName::recUpdate($recordId, $data); // return flag
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$data = [];
$data['title'] = '-- TITLE --';
$result = NewTable::recUpdate($recordId, $data);
if ($result) {
    echo 'Successfully';
} else {
    echo 'Not successful';
}

////////////////////////////////////////////////////////////////////////////////////////
// DELETE 
// ModelName::recDelete($recordId, $destroy); // return flag
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$destroy = true; // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
$result = NewTable::recDelete($recordId, $destroy);
if ($result) {
    echo 'Successfully';
} else {
    echo 'Not successful';
}

////////////////////////////////////////////////////////////////////////////////////////
// RELATIONSHIPS
// Working with relationships between tables "[relname]_row(s)_func"
////////////////////////////////////////////////////////////////////////////////////////
NewTable::refAttach('category_rows_func', 1, [3, 4]); // $relationship, $parentId, $idsToAttach
NewTable::refDetach('category_rows_func', 1, 4); // $relationship, $parentId, $idsToDetach
$countRecord = NewTable::recSelect('count', function ($q) use () {
    $q->with('category_rows_func');
    $q->where('uid', 1);
});

// Todo
// 1 relation function getCounty( -> refProvider() )
// 2 static function GetById (recSelect('userFunc'))
// 3 user function global scope register
// 4 user function local scope register
// 5 sub $filter where & with (without callback function "function ($q) use ()")
// 6 userLimit (userPaginator)
```

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

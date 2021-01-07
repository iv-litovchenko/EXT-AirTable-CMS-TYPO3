# EXT: AirTable (CMS TYPO3)

A set of tools for creating your site based on class annotations. Works in versions 9 +, 10 +. The design for this extension is presented in a minimum viable product format (MVP). Rather, it is a concept for developing websites based on a single standard. Some ideas are still underway. It does not work in versions 7 and 8 due to the termination of support for the "PATH_site" constants.

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

Step 5) Add ":: IncTypoScript (typo3temp/) :: (air_table)" as the last item to the page template.

![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Imgtypo3-add-template.png)

If you are working in versions 7, 8 and you need "typo3/install.php" - for this you need to create a file "typo3conf/ENABLE_INSTALL_TOOL" with the content "KEEP_FILE".

## CMS TYPO3 Extension structure

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
```

## CMS TYPO3 Register a new admin module
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Imgtypo3-register-a-new-admin-module.png)

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

EXT:myext/Resources/Private/Language/Localling.Module-Section.Tabmyext.xlf
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

## CMS TYPO3 Register a new page template
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Imgtypo3-register-a-new-page-template-1.png)

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

## CMS TYPO3 Register a new content element
![Image alt](https://github.com/iv-litovchenko/EXT-AirTable-CMS-TYPO3/raw/main/Imgtypo3-register-a-new-content-element.png)

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
            1, // Users Administrators "Admin Tools"
            3, // Users Administrators "Admin Tools"
        ]
    ]
];
```

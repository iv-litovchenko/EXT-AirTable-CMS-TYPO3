```
+ https://www.mittwald.de/fileadmin/pdf/dokus/Extbase_Fluid_Dokumentation.pdf

https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/9-CrosscuttingConcerns/2-validating-domain-objects.html#validating-in-the-domain-model-with-an-own-validator-class
https://various.at/news/typo3-9-custom-content-elements/
https://various.at/news/typo3-data-processor
https://various.at/news/typo3-tipps-und-tricks-psr-15-mideelware-am-beispiel-mailchimp-webhook
https://various.at/news/grideditor-fuer-inhaltselemente
https://various.at/news/typo3-indexed-search

https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-standartkonfig/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-standartkonfig-kurz/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-textstyle-und-blockstyle/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-blockformat/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-extension-pibase/arbeiten-mit-sessions/

https://api.typo3.org/9.5/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_mvc_1_1_controller_1_1_action_controller.html
https://api.typo3.org/master/class_t_y_p_o3_1_1_c_m_s_1_1_backend_1_1_utility_1_1_backend_utility.html
https://extensions.typo3.org/extension/div2007
https://extensions.typo3.org/extension/migration_core
Поковыряй https://nextras.org/orm/docs/4.0/ (рекурсивные функции очень интересно удаление рекурсивно)

## Любой объект можно переделать!
config {
    tx_extbase {
        objects {
            TYPO3\CMS\Extbase\Mvc\View\NotFoundView.className = TYPO3\CMS\FrontendEditing\Mvc\View\NotFoundView
        }
    }
```

## Задокументировать

```
<?php
namespace Litovchenko\Projiv\Controller\PagesElements\Elements;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ElementSubPagesController extends ActionController 
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'FrontendContentElement',
		'name' 			=> 'Дочерние страницы (красивый список)',
		'description' 	=> '',
		'dataFields' 	=> [
            'attr_field1' => [
                'type' => 'Input',
                'name' => 'Field Input 1',
				'items' => [ // Только для Enum, Switcher
					'S' => '(37-38)',
					'L' => 'L (41-42)',
					'XL' => 'XL (43-44)',
					'XXL' => 'XXL (45-46)',
					'XXXL' => '(47-48)'
				]
            ],
            'attr_field2' => [
                'type' => 'Input',
                'name' => 'Field Input 2'
            ],
            'attr_field3' => [
                'type' => 'Input',
                'name' => 'Field Input 3'
            ]
		]
	];
	
    public function indexAction()
    {
    }

}

```

## FAL DRAG ZONE
```
https://www.dropzonejs.com/
page.includeJSFooter.tx_vablog_dropzone = EXT:va_blog/Resources/Public/JavaScript/dropzone.js
<div class="dropzone" data-url="{f:uri.action(action: 'imageupload', controller: 'News', pluginName: 'vablog', pageType:'{settings.ajax.upload}')}"></div>


public function imageuploadAction() {
        try {
            $imageUploader = new \ImageUploader();
            $uniqueFilename = $this->generateUniqueFilename();
            $imageUploader->setPath($this->settings['newsImageUploadFolderFileadmin']);
            $imageUploader->upload($_FILES['file'], $uniqueFilename);
            $ext = \Chilischarf\ChiliNews\Utility\ImageUtility::getExtension($_FILES['file']["name"]);
        }
        catch (\Exception $e) {
            return json_encode(['success' => FALSE]);
        }
        return json_encode(
            [
                'success' => TRUE,
                'filename' => $uniqueFilename . '.' . $ext,
                'path' => $this->settings['newsImageUploadFolderFileadmin']
            ]
        );
    }

var initDropzone = function() {
    if(jQuery('.dropzone').length) {
        Dropzone.autoDiscover = false;
 
        jQuery('.dropzone').each(function () {
            var $this = jQuery(this);
 
            $this.dropzone({
                url: $this.attr('data-url'),
                acceptedFiles: 'image/*',
                dictDefaultMessage: 'Bilder hochladen',
                dictFallbackMessage: 'Ihr Browser wird leider nicht unterstützt',
                dictFileTooBig: 'Die Datei ist zu groß',
                dictInvalidFileType: 'Es werden nur Bilder unterstützt',
                dictResponseError: 'Es werden leider ein Fehler aufgetreten. Code: {{statusCode}}',
                createImageThumbnails: false,
                previewTemplate: '........',
                success: function (file, responseText) {
                    var response = JSON.parse(responseText);
 
                    if (response['success'] == true) {
                        .............
                    }
                }
            });
        });
    }
};
```

```
lib.contentElement = COA
lib.contentElement {
	10 =< lib.stdheader
}

tt_content.myce =< lib.contentElement
tt_content.myce {
	templateName = Generic
	20 =< plugin.myContent
}

<f:cObjecttyposcriptObjectPath="tt_content.{data.CType}.20" data="ãÑ{data}" table="tt_content" />

tt_content.stdWrap.innerWrap.cObject.key.field = frame_class
tt_content.stdWrap.innerWrap.cObject.ruler-before =< tt_content.stdWrap.innerWrap.cObject.defaul
tt_content.stdWrap.innerWrap.cObject.ruler-before.20.10.value = csc-frame csc-frame-ruler-before

• CSS Styled Content= EXT:css_styled_content/Configuration/TypoScript/
• Fluid Styled Content= EXT:fluid_styled_content/Configuration/TypoScript/

- Configuration/TypoScript
| - ContentElement
| | - Bullets.txt
| | - Div.txt
| | - Header.txt
| | - Html.txt
| | - Image.txt
| | - List.txt
| | - MenuAbstract.txt
| | - MenuCategorizedContent.txt
| | - MenuCategorizedPages.txt
| | - MenuPages.txt
| | - Shortcut.txt
| | - Table.txt
| | - Text.txt
| | - Textmedia.txt
| | - Textpic.txt
| | - Uploads.txt
| - ContentElementPartials
| | - Menu.txt
| - Helper (v8+)
| | - FluidContent.txt
| | - ParseFunc.txt
| - Helper
| | - ParseFunc.txt
| | - StandardHeader.txt
| | - StylesContent.txt
| - Styling
| | - setup.txt
| - constants.txt
| - setup.txt

mod.web_layout.tt_content.preview.list.example = EXT:site_mysite/Resources/Private/ãÑTemplates/Preview/ExamplePlugin.html

• BackendUtility::getFlexFormDS()
• GeneralUtility::resolveSheetDefInDS()
• GeneralUtility::resolveAllSheetsInDS()
• GeneralUtility::flushOutputBuffers()

extbase.controllerExtensionName
extbase.pluginName
extbase.controllerName
extbase.controllerActionName

lib.flexformContent = CONTENT
lib.flexformContent {
	table = tt_content
	select {
		pidInList = this
	}
	renderObj = COA 
	renderObj {
		10 = TEXT
		10 {
			data = flexform: pi_flexform:settings.categories
		}
	}
}

RecyclerUtility::getRecordPath
ExtensionManagementUtility::siteRelPath()
GeneralUtility::getFileAbsFileName()
PathUtility::getAbsoluteWebPath()
GeneralUtility::getFileAbsFileName())
ExtensionManagementUtility::extPath()

linkHandler
t3://page?uid=13
t3://page?alias=myfunkyalias
t3://page?uid=13&type=3
t3://page?uid=1313&my=param&will=get&added=here
t3://page?alias=myfunkyalias#c123
t3://page?uid=13&type=3#c123
t3://page?uid=13&type3?my=param&will=get&added=here#c123
t3://file?identifier=folder/myfile.jpg
t3://file?uid=13
t3://folder?identifier=fileadmin
t3://folder?storage=1&identifier=myfolder
t3://file?uid=134&renderAs=png

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['expressionNodeTypes'][]
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['preProcessors'][]
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['interceptors']

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Frontend\Plugin\AbstractPlugin::class]
['pi_list_browseresults'][1463475262] =\Vendor\ExtensionKey\Hook\ResultBrowserHook::class

$uriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
$path = $uriBuilder->buildUriFromRoute('ajax_myroute || myRouteIdentifier', array('foo' => 'bar')); // Генерация ссылок в контроллере в Backend

$view->getRenderingContext()->setLegacyMode(false);
$view->getRenderingContext()->setExpressionNodeTypes(array('Class\Number\One','Class\Number\Two'));

$resolver = $view->getRenderingContext()->getViewHelperResolver();
$resolver->registerNamespace('news', 'GeorgRinger\News\ViewHelpers');
$resolver->extendNamespace('f', 'My\Extension\ViewHelpers')
$resolver->setNamespaces(array('f' =>array('TYPO3Fluid\\Fluid\\ViewHelpers',

$formToken = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->getFormProtection()->generateToken('news', 'edit', $uid);
if($dataHasBeenSubmitted && \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->
validateToken(\TYPO3\CMS\Core\Utility\GeneralUtility::_POST('formToken'),'User setup','edit')) 
{

// Processes the data.}
else{

// Create a flash message for the invalid token or just discard thisrequest.
}

if(TYPO3_MODE === 'BE')
{
	//  implement the\TYPO3\CMS\Recordlist\Browser\ElementBrowserInterface
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ElementBrowsers'][<identifier>] = \Vendor\Ext\TheClass::class;
}


$this->moduleTemplate->addFlashMessage('I am a message body', 'Title',ãÑ\TYPO3\CMS\Core\Messaging\AbstractMessage::OK,true);

// Place this in your ext_localconf.php file$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['urlProcessing']['urlHandlers']['myext_myidentifier']['handler'] =\Company\MyExt\MyUrlHandler::class;
class MyUrlHandler implements\TYPO3\CMS\Frontend\Http\UrlHandlerInterface {}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['urlProcessing']['urlProcessors']['myext_myidentifier']['processor']= \Company\MyExt\MyUrlProcessor::class;
class MyUrlProcessor implements\TYPO3\CMS\Frontend\Http\UrlProcessorInterface {}

\TYPO3\CMS\Core\Resource\ResourceStorage::dumpFileContents()



$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(\TYPO3\CMS\Linkvalidator\LinkAnalyzer::class,'beforeAnalyzeRecord',\Vendor\Package\Slots\RecordAnalyzerSlot::class,'beforeAnalyzeRecord');


namespaceVendor\Package\Slots;
use TYPO3\CMS\Linkvalidator\LinkAnalyzer;
class RecordAnalyzerSlot{
	/***Receives a signal before the record is analyzed**
	@param array $results Array of broken links*@param array $record Record to analyse
	*@param string $table Table name of the record*@param array $fields Array of fields
	to analyze*@param LinkAnalyzer $parentObject Parent object*@return array*/
	public functionbeforeAnalyzeRecord($results, $record, $table, $fields,
	LinkAnalyzer $parentObject) 
	{// Processing herereturn array($results,$record);
	}
}

FlashMessageQueue::getAllMessages($severity);
FlashMessageQueue::getAllMessagesAndFlush($severity);
FlashMessageQueue::removeAllFlashMessagesFromSession($severity);
FlashMessageQueue::clear($severity);
$this->controllerContext->getFlashMessageQueue($queueIdentifier);
<f:flashMessagesqueueIdentifier="myQueue" />


$resourceStorage->copyFile($file, $targetFolder, 'target-file-name',DuplicationBehavior::RENAME);

###
BackendUserAuthentication->checkCLIuser()
constTYPO3_cliKey
constTYPO3_cliInclude
$GLOBALS['MCONF']['name']
$GLOBALS['temp_cliScriptPath']
$GLOBALS['temp_cliKey']
BackendUserAuthentication->checkCLIuser()

•IconUtility::skinImg()
•IconUtility::getIcon()
•IconUtility::getSpriteIcon()
•IconUtility::getSpriteIconForFile()
•IconUtility::getSpriteIconForRecord()
•IconUtility::getSpriteIconForResource()
•IconUtility::getSpriteIconClasses()

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap_cacheStore']

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap_cacheStore']
\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('new_content_element')


tt_content.image.20 = FLUIDTEMPLATE
tt_content.image.20 {
	file = EXT:myextension/Resources/Private/Templates/ContentObjects/Image.html
	dataProcessing.10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
	dataProcessing.10 {
		references.fieldName = image
		references.table = tt_content
		files = 21,42
		collections = 13,14
		folders = 1:introduction/images/,1:introduction/posters/
		folders.recursive = 1
		sorting = description
		sorting.direction = descending
		as = myfiles
		
	dataProcessing.20 = TYPO3\CMS\Frontend\DataProcessing\GalleryProcessor
	dataProcessing.20 {
		filesProcessedDataKey = files
		numberOfColumns.field = imagecols
		equalMediaWidth.field = imagewidth
		maxGalleryWidth = 1000
		maxGalleryWidthInText = 1000
		columnSpacing = 0
		borderEnabled.field = imageborder
		borderWidth = 0
		borderPadding = 10
		equalMediaHeight.field = imageheight
		mediaOrientation.field = imageorient
		as = gallery
	}
		
<ul>
	<f:for each="{myfiles}" as="file">
	<li><ahref="{file.publicUrl}">
		{file.name}</a>
	</li>
	</f:for>
</ul>

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile('extension_name', 
'Configuration/PageTS/myPageTSconfigFile.txt', 
'My specialãÑconfig');
```

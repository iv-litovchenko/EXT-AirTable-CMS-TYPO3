<?php
namespace Litovchenko\AirTable\Controller\Modules;

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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Utility\BaseUtility;

define("LAST_COLUMN", 'width: auto; border-top: 0; padding: 0; margin: 0;');
define("HIDE_CHECKBOX", 'display: none;');
define("HIDE_CHECKBOX_TD_NEXT", 'border-left: 0;');
define("STYLE_EMPTY_FIELD", 'background: #f0f0f0; color: #ccc;');

class ListController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Записи таблиц',
		'description' 	=> 'Управление записями (создание, просмотр, редактирование, удаление) таблиц зарегистрированных в системе',
		'access' 		=> 'user,group',
		'section'		=> 'content',
		'position'		=> '20'
	];
	
    /**
     *
     * @var int
     */
    protected $defaultListTypeRender = 0;
	
    /**
     * Page uid
     *
     * @var int
     */
    protected $extNameDir = ''; // $pageExtUid = 0;

    /**
     * TsConfig configuration
     *
     * @var array
     */
    protected $tsConfiguration = [];
	
    /**
     * @var array
     */
    protected $pageInformation = [];
	
    /**
     * Page broken
     *
     * @var int
     */
	protected $pageBrokenSelect = false;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Function will be called before every other action
     *
     */
    public function initializeAction()
    {
		$extnameFromRoute = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('route');
		$extnameFromRoute = explode('/',$extnameFromRoute);
		$extnameFromRoute = str_replace('AirTableList','',$extnameFromRoute[2]);
		
		$path_Typo3Ext = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/';
		$path_Typo3CoreExt = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3/sysext/';
		$extPathList = array_merge(
			glob($path_Typo3Ext.'*/'), // typo3conf/ext/
			['--'],
			glob($path_Typo3CoreExt.'*/') // typo3/sysext/
		);
		
		foreach($extPathList as $k => $extPath){
			if($k == $extnameFromRoute){
				$this->extNameDir = basename($extPath);
				break;
			}
		}
		
		$pageUid = 0;
		$this->pageInformation = BackendUtilityCore::readPageAccess($pageUid, '');

        parent::initializeAction();
    }

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var IconFactory
     */
    public $iconFactory;

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
		
        /** @var BackendTemplateView $view */
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);

        #$pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
		#$pageRenderer->addJsFile('EXT:air_table/Resources/Public/Js/List.js');
		#$pageRenderer->addCssFile('EXT:air_table/Resources/Public/Css/List.css');
		
		// Выводим кнопки только при условии, что это не выбор записей
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection') == 0){
			if($this->actionMethodName == 'listAction'){
				$this->createMenu();
			}
		}
		$this->createButtons();
		if(is_array($this->pageInformation)){
			$this->view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation($this->pageInformation);
		}
    }

    /**
     * Create menu
     *
     */
    protected function createMenu()
    {
    }

    /**
     * Create the panel of buttons
     *
     */
    protected function createButtons()
    {
		$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$table = BaseUtility::getTableNameFromClass($paramClass);
	
		$buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();
		$buttons = array();
		
		// (+) Кнопки "Создать запись". Находимся в категории
		if(BaseUtility::isModelCategoryForAnotherModel($paramClass) && BaseUtility::BeUserAccessTableModify($table) == true) {
			if($this->actionMethodName == 'listAction'){
				$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
				if($this->TxDataPropsDefaultCheck('tx_data','propref_categories',$dataType) == 0){
					$buttons[] = array(
						'title' => 'Новая категория',
						'table' => $table,
						'icon' => $GLOBALS['TCA'][$table]['ctrl']['typeicon_classes']['default'],
						//'columnsOnly' => 'RType,title,propref_parent,hidden',
						'defVals' => [
							$table => [
								// Fix Typo3 bug (про создании записей для "renderType->selectTree" не работает defVals)
								'propref_parent' => current(explode(',',BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_parent','')))
							]
						]
					);
					#$buttons[] = array(
					#	'title' => 'Создать новый элемент',
					#	'table' => preg_replace('/_category$/is','',$table),
					#	'icon' => $GLOBALS['TCA'][preg_replace('/_category$/is','',$table)]['ctrl']['typeicon_classes']['default'],
					#	'columnsOnly' => 'uid,RType,title,propref_category,propref_categories,propref_parent,hidden',
					#);
				}
			}
			
		// (+) Кнопки "Создать запись". Находимся в элементе категории
		} elseif(BaseUtility::isModelSupportRowCategory($paramClass) || BaseUtility::isModelSupportRowsCategories($paramClass)) {
			if($this->actionMethodName == 'listAction'){
				if(BaseUtility::isModelSupportRowCategory($paramClass) && BaseUtility::BeUserAccessTableModify($table.'_category') == true){
					$buttons[] = array(
						'title' => 'Новая категория',
						'table' => $table.'_category',
						'icon' => $GLOBALS['TCA'][$table.'_category']['ctrl']['typeicon_classes']['default'],
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						//'columnsOnly' => 'RType,title,propref_parent,hidden',
						'defVals' => [
							$table.'_category' => [
								// Fix Typo3 bug (про создании записей для "renderType->selectTree" не работает defVals)
								'propref_parent' => current(explode(',',BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_category',''))),
								'RType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
								'entity_type' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected')
							]
						]
					);
				}elseif(BaseUtility::isModelSupportRowsCategories($paramClass) && BaseUtility::BeUserAccessTableModify($table.'_category') == true){
					$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
					if($this->TxDataPropsDefaultCheck('tx_data','propref_categories',$dataType) == 0){
						$buttons[] = array(
							'title' => 'Новая категория',
							'table' => $table.'_category',
							'icon' => $GLOBALS['TCA'][$table.'_category']['ctrl']['typeicon_classes']['default'],
							'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
							//'columnsOnly' => 'RType,title,propref_parent,hidden',
							'defVals' => [
								$table.'_category' => [
									// Fix Typo3 bug (про создании записей для "renderType->selectTree" не работает defVals)
									'propref_parent' => current(explode(',',BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_categories',''))),
									'RType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
									'entity_type' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected')
								]
							]
						);
					}
				}
				if(BaseUtility::BeUserAccessTableModify($table) == true){
					$buttons[] = array(
						'title' => 'Новая запись',
						'table' => $table,
						'icon' => $GLOBALS['TCA'][$table]['ctrl']['typeicon_classes']['default'],
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						//'columnsOnly' => 'RType,title,propref_category,propref_categories,propref_parent,hidden',
						'defVals' => [
							$table => [
								// Fix Typo3 bug (про создании записей для "renderType->selectTree" не работает defVals)
								'propref_category' => current(explode(',',BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_category',''))),
								'propref_categories' => BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_categories',''),
								'propref_parent' => current(explode(',',BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_parent',''))),
								'RType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
								'entity_type' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected')
							]
						]
					);
				}
			}
			
		// (+) Кнопки "Создать запись". Обычная запись
		} else {
			if($this->actionMethodName == 'listAction' && BaseUtility::BeUserAccessTableModify($table) == true){
				$fieldLabel = $GLOBALS['TCA'][$table]['ctrl']['label'];
				$fieldDisabled = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
				$buttons[] = array(
					'title' => 'Новая запись',
					'table' => $table,
					'icon' => $GLOBALS['TCA'][$table]['ctrl']['typeicon_classes']['default'],
					'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
					//'columnsOnly' => 'RType,'.$fieldLabel.',propref_parent,'.$fieldDisabled,
					'defVals' => [
						$table => [
							// Fix Typo3 bug (про создании записей для "renderType->selectTree" не работает defVals)
							'propref_parent' => current(explode(',',BaseUtility::_POSTuc('form1Apply','form1FieldValue_propref_parent',''))),
							'RType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
							'entity_type' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected')
						]
					]
				);
			}
		}

		// Выводим кнопки только при условии, что это не выбор записей
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection') == 0 && class_exists($paramClass)){
			$k = 1;
			foreach ($buttons as $key => $tableConfiguration) {
				$backendLinkConf = [
					'edit['.$tableConfiguration['table'].'][0]' => 'new',
					'columnsOnly' => $tableConfiguration['columnsOnly'],
					'defVals' => $tableConfiguration['defVals'],
					'dataType' => $tableConfiguration['dataType'],
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				];
					
				/*
				switch(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('countNewRecords')){
					default: // +1
					break;
					case 3: // +3
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0b]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0c]']='new';
					break;
					case 5: // +5
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0b]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0c]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0d]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0e]']='new';
					break;
					case 10: // +10
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0b]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0c]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0d]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0e]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0f]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0j]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0h]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0i]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0j]']='new';
						$backendLinkConf['edit['.$tableConfiguration['table'].'][0k]']='new';
					break;
				}
				*/
				
				$viewButton = $buttonBar->makeLinkButton()
					->setShowLabelText(false)
					->setClasses('') // btn btn-default btn-sm
					->setHref('#')
					->setDataAttributes([
						'toggle' => 'modal',
						'target' => '#modalNewRecords'
					])
					->setTitle('1')
					->setIcon($this->iconFactory->getIcon($tableConfiguration['icon'], Icon::SIZE_SMALL, 'overlay-advanced'));
				$buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_RIGHT, $k);
					
				$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
					'record_edit',
					$backendLinkConf
				);
				$viewButton = $buttonBar->makeLinkButton()
					->setShowLabelText(true)
					->setClasses('') // btn btn-default btn-sm
					->setHref($backendLink)
					->setDataAttributes([
						'toggle' => 'tooltip',
						'placement' => 'bottom',
						'title' => $tableConfiguration['title']
					])
					->setTitle($tableConfiguration['title'])
					->setIcon($this->iconFactory->getIcon($tableConfiguration['icon'], Icon::SIZE_SMALL, 'overlay-new'));
				$buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_RIGHT, $k);
					
				$k = 2;
			}
		}

        // Tree
		#if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection') == 0){
		#	if(@in_array("propref_parent", $GLOBALS['TCA'][$storage_table]['ctrl']['fieldsDefault'])
		#	|| @in_array("propref_category", $GLOBALS['TCA'][$storage_table]['ctrl']['fieldsDefault']) 
		#	|| @in_array("propref_categories", $GLOBALS['TCA'][$storage_table]['ctrl']['fieldsDefault']))
		#	{
		#		if($GLOBALS['BE_USER']->uc['air_table']['ListControllerTreeSwitcher'][$pid] == 1){
		#			$treeCssClass = 'btn btn-success btn-sm';
		#		} else {
		#			$treeCssClass = 'btn btn-primary btn-sm';
		#		}
		#		$getArgs = [
		#			'pid' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('pid'),
		#			'tab_active' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tab_active'),
		#			'tree' => 'show_or_hide',
		#		];
		#		$uriBuilder = $this->controllerContext->getUriBuilder();
		#		$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
		#		$viewButton = $buttonBar->makeLinkButton()
		#			->setShowLabelText(true)
		#			->setClasses($treeCssClass) // btn btn-default btn-sm
		#			->setHref($url)
		#			->setDataAttributes([
		#				'toggle' => 'tooltip',
		#				'placement' => 'bottom',
		#				'title' => '-'
		#			])
		#			->setTitle('Дерево записей')
		#			->setIcon($this->iconFactory->getIcon('actions-pagetree', Icon::SIZE_SMALL));
		#		$buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 1);
		#	}
		#}
        
		// Выводим кнопки только при условии, что это не выбор записей
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection') == 0 && class_exists($paramClass)){
		if($this->actionMethodName == 'listAction'){
			// (+) Экспорт
			if(BaseUtility::BeUserAccessModule('AirTableExport')){
				$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
					'content_AirTableModulesExport',
					[
						'paramExt' => $paramExt,
						'paramSubDomain' => $paramSubDomain,
						'paramClass' => $paramClass,
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
						
						// в дальнейшем найти как создается данный алиас...
						'tx_airtable_content_airtablemodulesexport' => [
							'action' => 'step2'
						]
					]
				);
				$exportButton = $buttonBar->makeLinkButton()
					->setHref($backendLink)
					->setShowLabelText(true)
					->setTitle('Экспорт записей')
					->setClasses('')
					->setOnclick('window.open(this.href,\'_blank\'); return false;')
					->setIcon($this->iconFactory->getIcon('actions-database-export', Icon::SIZE_SMALL));
				$buttonBar->addButton($exportButton, ButtonBar::BUTTON_POSITION_RIGHT, 5);
			}
			
			// (+) Импорт
			if(BaseUtility::BeUserAccessModule('AirTableImport')){
				$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
					'content_AirTableModulesImport',
					[
						'paramExt' => $paramExt,
						'paramSubDomain' => $paramSubDomain,
						'paramClass' => $paramClass,
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
						
						// в дальнейшем найти как создается данный алиас...
						'tx_airtable_content_airtablemodulesimport' => [
							'action' => 'step2'
						]
					]
				);
				$importButton = $buttonBar->makeLinkButton()
					->setHref($backendLink)
					->setShowLabelText(true)
					->setTitle('Импорт записей')
					->setClasses('')
					->setOnclick('window.open(this.href,\'_blank\'); return false;')
					->setIcon($this->iconFactory->getIcon('actions-database-import', Icon::SIZE_SMALL));
				$buttonBar->addButton($importButton, ButtonBar::BUTTON_POSITION_RIGHT, 5);
			}
		}
		}
		
		// (+) Обратно, распечатать, в новом окне (из просмотра)	
		if($this->actionMethodName == 'showAction'){
			
			$getArgs = [
				'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
				'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
				'paramClass' => BaseUtility::_GETuc('paramClass',''),
			];
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
			$backButton = $buttonBar->makeLinkButton()
				->setHref($url)
				->setTitle('Обратно')
				->setShowLabelText(true)
				->setIcon($this->iconFactory->getIcon('actions-view-go-back', Icon::SIZE_SMALL));
			$buttonBar->addButton($backButton, ButtonBar::BUTTON_POSITION_LEFT,1);
			$printButton = $buttonBar->makeLinkButton()
				->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
				->setTitle('Открыть в новом окне')
				->setShowLabelText(true)
				->setOnclick('window.open(this.href,\'_blank\'); return false;')
				->setIcon($this->iconFactory->getIcon('actions-window-open', Icon::SIZE_SMALL));
			$buttonBar->addButton($printButton, ButtonBar::BUTTON_POSITION_LEFT,1);
		}
		
		// (+) Создать заметку для таблицы
		if(($this->actionMethodName == 'listAction' || $this->actionMethodName == 'listAction') && class_exists($paramClass)){
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit',
				array(
					'edit[sys_note][0]' => 'new',
					'defVals' => [
						'sys_note' => [
							'prop_tx_airtable_modelname' => $paramClass,
							'cruser' => $GLOBALS['BE_USER']->user['uid']
						]
					],
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				)
			);
			$viewButton = $buttonBar->makeLinkButton()
				->setHref($backendLink)
				->setShowLabelText(true)
				->setTitle('Заметка')
				->setIcon($this->iconFactory->getIcon('actions-localize', Icon::SIZE_SMALL));
			$buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_RIGHT, 10);
		}
		
		// (+) Кнопка Debug
		if($this->actionMethodName == 'listAction' || $this->actionMethodName == 'listAction'){
			if(BaseUtility::_GETuc('debug',0,'ListController')){
				$GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = true;
				$trashCssClass = 'btn btn-warning btn-sm';
				$getArgs = [
					'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
					'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
					'tabCategorizationActive'=> \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tabCategorizationActive'),
					'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
					'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
					'paramClass' => BaseUtility::_GETuc('paramClass',''),
					'recordSelection' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection'),
					'recordSelectionFieldname' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelectionFieldname'),
					'debug' => 0
				];
			} else {
				$GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = false;
				$trashCssClass = 'btn btn-default btn-sm';
				$getArgs = [
					'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
					'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
					'tabCategorizationActive'=> \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tabCategorizationActive'),
					'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
					'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
					'paramClass' => BaseUtility::_GETuc('paramClass',''),
					'recordSelection' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection'),
					'recordSelectionFieldname' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelectionFieldname'),
					'debug' => 1
				];
			}
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$url = $uriBuilder->setArguments($getArgs)->uriFor(str_replace('Action','',$this->actionMethodName), []);
			$viewButton = $buttonBar->makeLinkButton()
				->setShowLabelText(true)
				->setClasses($trashCssClass)
				->setHref($url)
				->setDataAttributes([
					'toggle' => 'tooltip',
					'placement' => 'bottom',
					'title' => '-'
				])
				->setTitle('Отладка')
				->setIcon($this->iconFactory->getIcon('actions-debug', Icon::SIZE_SMALL));
			$buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_RIGHT, 10);
		}
		
        // (+) Кнопка корзина
		// if(BaseUtility::hasSpecialField($paramClass,'deleted') == true) {
			if($this->actionMethodName == 'listAction' || $this->actionMethodName == 'listAction'){
				if(BaseUtility::_GETuc('restrictionDeleted',0,'ListController')){
					$trashCssClass = 'btn btn-success btn-sm';
					$getArgs = [
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
						'tabCategorizationActive'=> \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tabCategorizationActive'),
						'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
						'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
						'paramClass' => BaseUtility::_GETuc('paramClass',''),
						'recordSelection' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection'),
						'recordSelectionFieldname' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelectionFieldname'),
						'restrictionDeleted' => 0
					];
				} else {
					$trashCssClass = 'btn btn-default btn-sm';
					$getArgs = [
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
						'tabCategorizationActive'=> \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tabCategorizationActive'),
						'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
						'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
						'paramClass' => BaseUtility::_GETuc('paramClass',''),
						'recordSelection' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection'),
						'recordSelectionFieldname' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelectionFieldname'),
						'restrictionDeleted' => 1
					];
				}
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$url = $uriBuilder->setArguments($getArgs)->uriFor(str_replace('Action','',$this->actionMethodName), []);
				$viewButton = $buttonBar->makeLinkButton()
					->setShowLabelText(true)
					->setClasses($trashCssClass)
					->setHref($url)
					->setDataAttributes([
						'toggle' => 'tooltip',
						'placement' => 'bottom',
						'title' => '-'
					])
					->setTitle('Корзина')
					->setIcon($this->iconFactory->getIcon('actions-edit-delete', Icon::SIZE_SMALL));
				$buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_RIGHT, 10);
			}
		// }
	
			// (+) Кнопка перехода между таблицами
			#
			#$refreshButton = $buttonBar->makeLinkButton()
			#	->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
			#	->setTitle('Таблицы модели')
			#	->setShowLabelText(true)
			#	->setIcon($this->iconFactory->getIcon('actions-database', Icon::SIZE_SMALL));
			#$buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_LEFT, 3);
		
        // (+) Кнопка обновить
        $refreshButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle('Обновить')
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT, 10);
    }
	
    /**
     * Main action for administration
     */
    public function indexAction()
    {
		if($this->request->getControllerName() == 'Modules\Data') {
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'content_AirTableModulesData',
				[
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' =>'',
					
					// в дальнейшем найти как создается данный алиас...
					'tx_airtable_content_airtablemodulesdata' => [
						'action' => 'list'
					]
				]
			);
		} elseif($this->request->getControllerName() == 'Modules\Attributes') {
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'content_AirTableModulesAttributes',
				[
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' =>'',
					
					// в дальнейшем найти как создается данный алиас...
					'tx_airtable_content_airtablemodulesattributes' => [
						'action' => 'list'
					]
				]
			);
		} else {
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'content_AirTableModulesList',
				[
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' =>'',
					
					// в дальнейшем найти как создается данный алиас...
					'tx_airtable_content_airtablemoduleslist' => [
						'action' => 'list'
					]
				]
			);
		}
		
		\TYPO3\CMS\Core\Utility\HttpUtility::redirect($backendLink);
	}
	
    /**
     * Main action for administration
     */
    public function listAction()
    {
		// Параметры _GET
		$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
		
		// Если не выбрана таблица...
		if(!class_exists($paramClass)){
			$assignedValues = [
				'tab1_Icon' => $this->iconFactory->getIcon('actions-database', Icon::SIZE_SMALL),
				'recordContentTable' => $this->rowContentTable(),
				'emptyParamClass' => 1,
			];
			$this->view->assignMultiple($assignedValues);
		} else {
			$this->_listAction();
		}
	}
	
    /**
     * 
     */
    public function _listAction()
    {
		// Параметры _GET
		$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
		
		$dLT = BaseUtility::getClassAnnotationValueNew($paramClass,'AirTable\DefaultListTypeRender');
		if($dLT > 0){
			$this->defaultListTypeRender = $dLT;
		} else {
			$this->defaultListTypeRender = 0;
		}
	
		// Создаем модель
		$sqlBuilderSelect = $paramClass::getModel();
		$sqlBuilderCount = $paramClass::getModel();
		$table = BaseUtility::getTableNameFromClass($paramClass);
		
		// Если идет выполнить с записью
		$this->perform($table);
		
		// Дебаг
		if(method_exists($paramClass, 'userDebugСontent')){
			$debugInController = $paramClass::userDebugСontent();
		}
		
		// Если отправлены формы фильтра, сортировки или настроек, ставим страницу №1
		#$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		#if($_POST['form1Apply'] != 0 or $_POST['form2Apply'] != 0 or $_POST['form3Apply'] != 0 ){
		#	$firstKey = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('route').'_POST';
		#	$GLOBALS['BE_USER']->uc['air_table']['_POSTuc'][$firstKey]['form0Apply']['form0Page'] = 1;
		#	$GLOBALS['BE_USER']->writeUC();
		#}
		
		// 0 // livesearch
		$searchFields = explode(',',$GLOBALS['TCA'][$table]['ctrl']['searchFields']);
		$liveSearch = BaseUtility::_POSTuc('form6Apply','form6Value','');
		if(trim($liveSearch) != ''){
			$sqlBuilderSelect = $sqlBuilderSelect->where(function ($query) use ($searchFields, $liveSearch) {
				foreach($searchFields as $k => $v){
					if(trim($v) != null){
						if($v == 'uid'){
							$query->orWhere($v, '=', $liveSearch);
						} else {
							$query->orWhere(trim($v), 'LIKE', '%'.str_replace(' ','%',$liveSearch).'%');
						}
					}
				}
			});
		}
		
		// 1 // select
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
		
			// Оставляем только выбранные колонки
			#if((in_array($k, BaseUtility::_POSTuc('form3Apply','tab3_columns','all')) && BaseUtility::_POSTuc('form3Apply','tab3_columns','all') != 'all') 
			#	|| ($k == 'uid' && BaseUtility::_POSTuc('form3Apply','tab3_columns','all') != 'all')){
			#		$sqlBuilderSelect = $sqlBuilderSelect->addSelect($table.'.'.$k);
			#}
			$AirTableFieldClass = $v['AirTable.Class'];
			if(method_exists($AirTableFieldClass, 'listControllerSqlBuilder')){ // Фильтр
				$sqlBuilderSelect = $AirTableFieldClass::listControllerSqlBuilder($this, $sqlBuilderSelect, $table, $k, $v);
			}
			if(method_exists($AirTableFieldClass, 'listControllerSqlBuilderWith')){ // Присоединение связей
				$sqlBuilderSelect = $AirTableFieldClass::listControllerSqlBuilderWith($this, $sqlBuilderSelect, $table, $k, $v);
			}
		}
		
		// 3 // order
		$paramOrder = BaseUtility::_POSTuc('form2Apply','form2Order');
		foreach($paramOrder as $k => $v){
			if($v != null){
				if($v == 'ASC' || $v == 'DESC') {
					$sqlBuilderSelect = $sqlBuilderSelect->orderBy($table.'.'.$k,$v);
					
				} elseif ($v == 'RAND') {
					$sqlBuilderSelect = $sqlBuilderSelect->inRandomOrder($table.'.'.$k);
				}
			}
		}
		
		// 4 // limit
		$page = BaseUtility::_POSTuc('form0Apply','form0Page',1);
		$lim = BaseUtility::_POSTuc('form3Apply','form3Limit',30); // 30
		if($lim>=0){
			$rowsCount = $sqlBuilderSelect->count(); // Кол-во строк всего в данном запросе
			if($page == 1){
				$sqlBuilderSelect = $sqlBuilderSelect->limit($lim)->offset(0);
				$SqlPageGo = 1;
			} else {
				$sqlBuilderSelect = $sqlBuilderSelect->limit($lim)->offset($lim*($page-1));
				$SqlPageGo = 1;
			}
		}
		
			// 4 // limit // rand
			if($lim == -2){
				#$sqlBuilderSelect = $sqlBuilderSelect->inRandomOrder();
				#$sqlBuilderSelect = $sqlBuilderSelect->limit(1)->offset(0);
				#$SqlPageGo = 1;
				#$page = 1;
			}
		
		$sql = $sqlBuilderSelect->toSql();
		$rows = $sqlBuilderSelect->get()->toArray();
		$rowsCountDisplay = count($rows); // Кол-во выбранных строк 
		$rowsCountAllInDb = $sqlBuilderCount->count(); // Кол-во записей в базе всего
		
		$SqlPagePrev = $page-1;
		if($page < ceil($rowsCount/$lim)){
			$SqlPageNext = $page+1;
		} else {
			$SqlPageNext = 0;
		}
		
		#if($GLOBALS['BE_USER']->uc['air_table']['ListControllerTreeSwitcher'][$pid] == 1){
		#	$content = $this->getContent($pid);
		#	$scenario = 1;
		#} else {
			$content = '
			<style>
			table.tdlastwith100 { width: 100%; }
			table.tdlastwith100 td { width: auto; padding: 5px; } /* min width, actually: this causes the width to fit the content */
			table.tdlastwith100 td:last-child { width: 100%; } /* well, it\'s less than 100% in the end, but this still works for me */

			table.remove-border { }
			table.remove-border > tbody > tr > th:first-child { border-left: none !important; }
			table.remove-border > tbody > tr > th:nth-last-child(-n+2) { border-right: none; }
			/* table.remove-border > tbody > tr > th:last-child { border-left: none; border-right: none; } */
			table.remove-border > tbody > tr > td:first-child { border-left: none !important; }
			table.remove-border > tbody > tr > td:nth-last-child(-n+2) { border-right: none; }
			/* table.remove-border > tbody > tr > td:last-child { border-left: none; border-right: none; } */
			</style>
			<div style="overflow-x: auto; border-left: #ccc 1px solid; border-right: #ccc 1px solid;">
			<table class="table table-bordered table-hover remove-border tdlastwith100" style="width: 100%; margin-bottom: 0; border-left: none; border-right: none; background: white;">
			<tbody>
				'.$this->rowContent($rows, $table).'
			</tbody>
			</table>
			</div>';
			$scenario = 0;
		#}
		
		// Наличие полей корзины, вкл/выкл
		$fieldDelete = 0;
		if(isset($GLOBALS['TCA'][$table]['ctrl']['delete'])) {
			$fieldDelete = 1;
		}
		$fieldDisabled = 0;
		if(isset($GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'])) {
			$fieldDisabled = 1;
		}
		
		// Экспорт/импорт (ccs
		/* Ссылки перенес на верх */
		
		// Litovchenko\AirTable\Domain\Model\SysNote
		$rowsNote = \Litovchenko\AirTable\Domain\Model\SysNote::getModel()
			->where('prop_tx_airtable_modelname','=',$paramClass)
			->with('cruser')
			->orderBy('crdate','desc')
			->get()
			->toArray();
			
		// Добавляем ссылку на редактирование
		foreach($rowsNote as $kNote => $vNote){
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit',
				array(
					'edit[sys_note]['.$vNote['uid'].']' => 'edit',
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				)
			);
			$rowsNote[$kNote]['backendLink'] = $backendLink;
		}
			
		// Если пусто
		$recordContentNoteEmpty = '';
		if(count($rowsNote) == 0){
			$icon = $this->iconFactory->getIcon('mimetypes-x-sys_note', Icon::SIZE_DEFAULT);
			$recordContentNoteEmpty = '<div style="margin: 50px;"><h3>'.$icon.' Без заметок!</h3></div>';
		}
		
		// Если есть категории
		// Если выбран материал...
		$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
		#if(BaseUtility::isModelSupportRowCategory($paramClass)&&BaseUtility::isModelSupportRowsCategories($paramClass)){
		#		$showTreeInfobox = 'В модели используется два поля для выбора категорий ("Категория" и "Категории (возможно несколько))';
		#		$showTreeInfoboxState = 2;
		
		
		//  && $this->TxDataPropsDefaultCheck('tx_data','propref_categories',$dataType) == 0
		
		// Если выбран материал без категорий...
		$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
		if($table == 'tx_data'){
			if($this->TxDataPropsDefaultCheck('tx_data','propref_categories',$dataType) == 0){
				$showTreeInfobox = 'Один элемент может состоять в нескольких категориях // Rel_MtoM';
				$showTreeInfoboxState = -1;
				$contentCategory = $this->rowContentCategory();
				$showTree = 1;
			
			}elseif($this->TxDataPropsDefaultCheck('tx_data','propref_parent',$dataType) == 0){
				$showTreeInfobox = 'Построение дерева на базе одной таблицы по полю "Родителя"';
				$showTreeInfoboxState = -1;
				$contentCategory = $this->rowContentElement();
				$showTree = 1;
				
			} else {
				$showTree = 0;
			}
			
		} elseif($table == 'tx_data_category') {
			if($this->TxDataPropsDefaultCheck('tx_data_category','propref_parent',$dataType) == 0){
				$showTreeInfobox = 'Построение дерева на базе одной таблицы по полю "Родителя"';
				$showTreeInfoboxState = -1;
				$contentCategory = $this->rowContentElement();
				$showTree = 1;
				
			} else {
				$showTree = 0;
			}
			
		} else {

			// Множество категорий
			if(BaseUtility::isModelSupportRowsCategories($paramClass) && BaseUtility::isModelSupportRowCategory($paramClass)) {
				$showTreeInfobox = 'В модели определено два типа категоризации. Необходимо выбрать один вариант категоризации [propref_category || propref_categories]!';
				$showTreeInfoboxState = 2;
				$contentCategory = '';
				$showTree = 1;
				
			// Множество категорий
			} elseif(BaseUtility::isModelSupportRowsCategories($paramClass)) {
				$showTreeInfobox = 'Один элемент может состоять в нескольких категориях // Rel_MtoM';
				$showTreeInfoboxState = -1;
				$contentCategory = $this->rowContentCategory();
				$showTree = 1;
			
			// 1 Категория
			} elseif(BaseUtility::isModelSupportRowCategory($paramClass)) {
				$showTreeInfobox = 'Один элемент может состоять только в 1 категории // Rel_Mto1';
				$showTreeInfoboxState = -1;
				$contentCategory = $this->rowContentCategory();
				$showTree = 1;
			
			// Если есть родитель
			} elseif(BaseUtility::hasSpecialField($paramClass,'propref_parent')) {
				$showTreeInfobox = 'Построение дерева на базе одной таблицы по полю "Родителя"';
				$showTreeInfoboxState = -1;
				$contentCategory = $this->rowContentElement();
				$showTree = 1;
				
			} else {
				$showTree = 0;
				#$showTreeReset = 0;
			}
		}
		
		// Список колонок на редактирование
		#$columnsOnly = [];
		#$columnsOnlyAll = [];
		#$columns = $GLOBALS['TCA'][$table]['columns'];
		#foreach($columns as $k => $v){
		#	$columnsOnlyAll[] = $k;
		#	if(in_array($k,BaseUtility::_POSTuc('form3Apply','form3Columns'))){
		#		$columnsOnly[] = $k;
		#	}
		#}
		
		// Выводим кнопки только при условии, что это не выбор записей
		$recordContentTable = '';
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection') == 0){
			$recordContentTable = $this->rowContentTable();
		}
		
		// // // // // // // // // // // // // // // // // // // // // // // // // // // // 
		// Modal - Мастер создания новых записей
		// // // // // // // // // // // // // // // // // // // // // // // // // // // // 
		$backendLinkConfNewRecord = [];
		$backendLinkConf = [
			'edit['.$table.'][0]' => 'new',
			'columnsOnly' => 'uid,pid,title',
			'defVals' => '...',
			'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
		];
					
			// Кол-во новых записей
			$alf = ['b','c','d','e','f','g','h','i','j','k'];
			$j = 0;
			for($j = 0; $j < count($alf); $j++){
				for($i = 0; $i <= $j; $i++){
					$backendLinkConf['edit['.$table.'][0'.$alf[$j].']']='new';
				}
				$backendLinkConfNewRecord[$j] = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
					'record_edit',
					$backendLinkConf
				);
			}
		
        $assignedValues = [
			'h1Content' => BaseUtility::getClassAnnotationValueNew($paramClass,'AirTable\Label'),
			'icon' => $this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableRecord', Icon::SIZE_SMALL),
			'iconCategory' => $this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableRecord', Icon::SIZE_SMALL),
			
			#'pre' => print_r($GLOBALS['_GET'],true), // Служебное
			#'pre' => print_r($GLOBALS['_POST'],true), // Служебное
			#'pre' => print_r($GLOBALS['BE_USER']->uc['air_table']['_POSTuc'],true), // Служебное
			
			'recordContentTable' => $recordContentTable,
			'recordContent' => $content,
			'recordContentCategory' => $contentCategory,
			'recordContentNote' => $rowsNote,
			'recordContentNoteEmpty' => $recordContentNoteEmpty,
			'recordContentNoteCount' => count($rowsNote),
			'scenario' => 0,
			'showTree' => $showTree,
			'showTreeInfobox' => $showTreeInfobox,
			'showTreeInfoboxState' => $showTreeInfoboxState,
			
			'fieldDelete' => $fieldDelete,
			'fieldDisabled' => $fieldDisabled,
			'columnsOnly' => count($columnsOnly)>0?implode(',',$columnsOnly):implode(',',$columnsOnlyAll),
			'accessTableModify' => BaseUtility::BeUserAccessTableModify($table),
			
			'modelName' => $paramClass,
			'tableName' => $table,
			
			'sql' => $sql,
			'debugInController' => $debugInController,
			'debug' => $GLOBALS['TYPO3_CONF_VARS']['BE']['debug'],
			'tab1_Icon' => $this->iconFactory->getIcon('actions-database', Icon::SIZE_SMALL),
			
			'rowsCount' => intval($rowsCount),
			'rowsCountDisplay' => intval($rowsCountDisplay),
			'rowsCountAllInDb' => intval($rowsCountAllInDb),

			'SqlPage' => $page,
			'SqlPageGo' => $SqlPageGo,
			'SqlPagePrev' => $SqlPagePrev,
			'SqlPageNext' => $SqlPageNext,
			
			'form' => $this->formHtmlContent($table, $rowsCount, $lim),
			'pageBrokenSelect' => $this->pageBrokenSelect,
			'form0Apply' => intval(BaseUtility::_POSTuc('form0Apply','form0Apply',0)),
			'form1Apply' => intval(BaseUtility::_POSTuc('form1Apply','form1Apply',0)),
			'form2Apply' => intval(BaseUtility::_POSTuc('form2Apply','form2Apply',0)),
			'form3Apply' => intval(BaseUtility::_POSTuc('form3Apply','form3Apply',0)),
			#'form4Apply' => BaseUtility::_POSTuc('form4Apply','form4Apply',0),
			#'form5Apply' => BaseUtility::_POSTuc('form5Apply','form5Apply',0),
			'form6Apply' => intval(BaseUtility::_POSTuc('form6Apply','form6Apply',0)),
			'form6Value' => stripslashes(htmlspecialchars(BaseUtility::_POSTuc('form6Apply','form6Value',''))),
			
			#'backendLinkExport' => $backendLinkExport,
			#'backendLinkImport' => $backendLinkImport,
			'backendLinkConfNewRecord' => $backendLinkConfNewRecord,
			
			// Если идет выбор записей
			'recordSelection' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelection'),
			'recordSelectionFieldname' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('recordSelectionFieldname'),
			
			// Если открыли таблицу с категориями
			'tabCategorizationActive' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tabCategorizationActive')
        ];
        $this->view->assignMultiple($assignedValues);
    }
	
	public function rowContentData($groupId)
	{
		$content = '';
		
		$filter = [];
		$filter['orderBy'] = ['sorting','asc'];
		$filter['where'] = ['propref_group','=',$groupId];
		$rowsGet = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('get',$filter);
		foreach($rowsGet as $k => $v){
			
			// Active
			if (\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected') == 'tx_data___'.$v['uid']){
				$active = 'padding: 3px 8px 3px 8px; background: #a54848;';
			
			// Active
			} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected') == 'tx_data_category___'.$v['uid']){
				$active = 'padding: 3px 8px 3px 8px; background: #a54848;';
			
			} elseif(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType') == $v['uidkey']) {
				$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
				#if($GLOBALS['_GET']['paramClass'] == 'Litovchenko\AirTable\Domain\Model\Content\DataCategory'){
				#	$active2 = 'padding: 3px 8px 3px 8px; background: #a54848;';
				#}
			} else {
				$active = 'padding: 3px 8px 3px 0px; ';
			}
			
			// Icon
			$ic = 'content-text';
			
			// Db count
			$filter = [];
			$filter['where'] = ['RType','=',$v['uid']];
			$filter['withoutGlobalScope'] = ['DataTypeDefault'];
			$dbCount = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('count',$filter);
				
			// Access
			$aDataSettings = '';
			if(BaseUtility::BeUserAccessTableSelect('tx_data_type')){
				
				// Url (settings)
				$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
					'record_edit', [
						'edit[tx_data_type]['.$v['uid'].']' => 'edit',
						'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
					]
				);
				$aDataSettings = '<a href="'.$backendLink.'">'.$this->iconFactory->getIcon('module-install-maintenance', Icon::SIZE_SMALL).'</a>';
			}
				
			// Access
			$aDataAttribute = '';
			if(BaseUtility::BeUserAccessTableSelect('sys_attribute')){
				
				// Url (attribute)
				$getArgs = [
					'sysEavAttr' => 'open',
					'sysEavAttrSelected' => 'tx_data___'.$v['uid'],
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' => 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute',
				];
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$urlDataAttribute = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
				$aDataAttribute = '<a href="'.$urlDataAttribute.'">'.$this->iconFactory->getIcon('module-install-settings', Icon::SIZE_SMALL).'</a>';
			}
				
			// Access
			$aDataCatAttribute = '';
			if(BaseUtility::BeUserAccessTableSelect('sys_attribute')){
				
				// Url (attribute)
				$getArgs = [
					'sysEavAttr' => 'open',
					'sysEavAttrSelected' => 'tx_data_category___'.$v['uid'],
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' => 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute',
				];
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$urlDataCatAttribute = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
				$aDataCatAttribute = '<a href="'.$urlDataCatAttribute.'">'.$this->iconFactory->getIcon('module-install', Icon::SIZE_SMALL).'</a>';
			}
			
			// TxData Props Def (пропускаем колонки, которые не существуют в типе материала)
			if($this->TxDataPropsDefaultCheck('tx_data','propref_categories',$v['uid']) == 0){
			
				// Url
				$getArgs = [
					'data' => 'open',
					'dataType' => $v['uidkey'],
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\Data',
					'tabCategorizationActive'=>1
				];
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
				
				// Access
				$accessOverlay = null;
				if(BaseUtility::BeUserAccessTableSelect('tx_data') == false){
					$url = '#';
					$accessOverlay = 'overlay-locked';
				}
				
				// Db count
				$filter = [];
				$filter['where'] = ['RType','=',$v['uid']];
				$filter['withoutGlobalScope'] = ['DataTypeDefault'];
				$dbCount2 = \Litovchenko\AirTable\Domain\Model\Content\DataCategory::recSelect('count',$filter);
				
				// Url 2
				if($this->TxDataPropsDefaultCheck('tx_data_category','propref_parent',$v['uid']) == 0){
					$getArgs = [
						'data' => 'open',
						'dataType' => $v['uidkey'],
						'paramExt' => '',
						'paramSubDomain' => '',
						'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\DataCategory',
						'tabCategorizationActive'=>1
					];
				} else {
					$getArgs = [
						'data' => 'open',
						'dataType' => $v['uidkey'],
						'paramExt' => '',
						'paramSubDomain' => '',
						'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\DataCategory',
						'tabCategorizationActive'=>0
					];

				}
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$url2 = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
				
				// Access 2
				$accessOverlay2 = null;
				if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass('tx_data_category')) == false){
					$url2 = '#';
					$accessOverlay2 = 'overlay-locked';
				}
						
				$content .= '
					<table width="100%;" style="border-spacing: 0px 0px;">
					<tr>
					<td>
						<a href="'.$url.'" class="" style="display: inline-block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active.'"
						onclick="filterDataTypeZzz(\''.$v['uid'].'\',\''.$url.'\'); alert(1);">
							'.$this->iconFactory->getIcon('apps-filetree-folder-news', Icon::SIZE_SMALL, $accessOverlay2).'&nbsp;
							'.$v['title'].'
							<span style="color: #535353;">['.intval($dbCount2).'/'.intval($dbCount).']</span>
						</a>
						<!--
						<a href="'.$url.'" class="" style="display: inline-block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active.'"
						onclick="filterDataTypeZzz(\''.$v['uid'].'\',\''.$url.'\'); alert(1);">
							'.$this->iconFactory->getIcon($ic, Icon::SIZE_SMALL, $accessOverlay).'&nbsp;
							'.$v['title'].'
							['.intval($dbCount).']
						</a>
						<a href="'.$url2.'" class="" style="display: inline-block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active2.'">
							'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableCategoryRecord', Icon::SIZE_SMALL, $accessOverlay2).'&nbsp;
							['.intval($dbCount2).']
						</a>
						-->
					</td>
					<td style="text-align: right;">
						'.$aDataSettings.'&nbsp;
						<span class="aDataCatAttributeHover">
							'.$aDataAttribute.'&nbsp;
							<span>'.$aDataCatAttribute.'</span>
						</span>
						<span style="color: #535353;">[#'.$v['uidkey'].']</span>&nbsp;
						<!--'.$this->rowContentDataAttr('tx_data',$v['uid']).'-->
						<!--'.$this->rowContentDataAttr('tx_data_category',$v['uid']).'-->
					</td>
					</tr>
					</table>
				';
			} else {
				
				if($this->TxDataPropsDefaultCheck('tx_data','propref_parent',$v['uid'])){
					// Url
					$getArgs = [
						'data' => 'open',
						'dataType' => $v['uidkey'],
						'paramExt' => '',
						'paramSubDomain' => '',
						'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\Data',
					];
				} else {
					// Url
					$getArgs = [
						'data' => 'open',
						'dataType' => $v['uidkey'],
						'paramExt' => '',
						'paramSubDomain' => '',
						'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\Data',
						'tabCategorizationActive'=>1
					];
				}
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
				
				// Access
				$accessOverlay = null;
				if(BaseUtility::BeUserAccessTableSelect('tx_data') == false){
					$url = '#';
					$accessOverlay = 'overlay-locked';
				}
				
				$content .= '
					<table width="100%;" style="border-spacing: 0px 0px;">
					<tr>
					<td>
						<a href="'.$url.'" class="" style="display: block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active.'"
						onclick="filterDataTypeZzz(\''.$v['uid'].'\',\''.$url.'\'); alert(1);">
							'.$this->iconFactory->getIcon('content-news', Icon::SIZE_SMALL, $accessOverlay).'&nbsp;
							'.$v['title'].'
							<span style="color: #535353;">['.intval($dbCount).']</span>
						</a>
					</td>
					<td style="text-align: right;">
						'.$aDataSettings.'&nbsp;
						'.$aDataAttribute.'&nbsp;
						<span style="color: #535353;">[#'.$v['uidkey'].']</span>&nbsp;
						<!--'.$this->rowContentDataAttr('tx_data',$v['uid']).'-->
					</td>
					</tr>
					</table>
				';
			}
		}
		
		return $content;
	}
	
	public function rowContentDataAttr($key, $id)
	{
		$filter = [];
		$filter['where'] = ['propref_entity','=',$id];
		$filter['withoutGlobalScope'] = ['EntityTypeDefault'];
		# $rowsCount = \Litovchenko\AirTable\Domain\Model\Eav\SysAttribute::recSelect('count',$filter);
		
		// Url
		$getArgs = [
			'sysEavAttr' => 'open',
			'sysEavAttrSelected' => $key.'___'.$id,
			'paramExt' => '',
			'paramSubDomain' => '',
			'paramClass' => 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute',
		];
		
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
			
		// Access
		$accessOverlay = null;
		if(BaseUtility::BeUserAccessTableSelect('sys_attribute') == false){
			$backendLink = '#';
			$accessOverlay = 'overlay-locked';
		}
		
		$cssColorCount = '#535353';
		if(intval($rowsCount)>0){
			$cssColorCount = 'red';
		}
		
		return '<a href="'.$url.'" class="" style="">
			<span style="color: '.$cssColorCount.';">['.intval($rowsCount).']</span>
		</a>';
	}
	
	public function rowContentEavAttr($key)
	{
		$content = '';
		$filter = [];
		$filter['orderBy'] = ['sorting','asc'];
		$filter['where'] = ['RType','=',$key];
		$items = \Litovchenko\AirTable\Domain\Model\Eav\SysEntity::recSelect('get',$filter);
		foreach($items as $k => $v){
			
			// Active
			if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected') == $v['uid']){
				$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
			} else {
				$active = 'padding: 3px 8px 3px 0px; ';
			}
			
			$filter = [];
			$filter['where'] = ['propref_entity','=',$v['uid']];
			$filter['orderBy'] = ['sorting','asc'];
			$filter['withoutGlobalScope'] = ['EntityTypeDefault'];
			# $rowsCount = \Litovchenko\AirTable\Domain\Model\Eav\SysAttribute::recSelect('count',$filter);
			
			// Icon
			$ic = 'mimetypes-other-other';
			
			// Url
			$getArgs = [
				'sysEavAttr' => 'open',
				'sysEavAttrSelected' => $v['uid'],
				'paramExt' => '',
				'paramSubDomain' => '',
				'paramClass' => 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute',
			];
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
			
			// Access
			$accessOverlay = null;
			if(BaseUtility::BeUserAccessTableSelect('sys_attribute') == false){
				$backendLink = '#';
				$accessOverlay = 'overlay-locked';
			}
		
			$cssColorCount = '#535353';
			if(intval($rowsCount)>0){
				$cssColorCount = 'red';
			}
			
			$extPrefix = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$extPrefix = 'EXT: '.$v['prop_ext'].' | ';
			}
			
			$content .= '

				<table width="100%;" style="border-spacing: 0px 0px;">
				<tr>
				<td>
					<a href="'.$url.'" class="" style="display: block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active.'">
						'.$this->iconFactory->getIcon($ic, Icon::SIZE_SMALL, $accessOverlay).'&nbsp;
						'.$extPrefix.htmlspecialchars($GLOBALS['LANG']->sL($v['title'])).'
						[id='.$v['uid'].']
					</a>
				</td>
				<td style="text-align: right;">
					<span style="color: '.$cssColorCount.';">['.intval($rowsCount).']</span>
				</td>
				</tr>
				</table>
			';
		}
		
		return $content;
	}
	
	public function rowContentTable()
	{
		// Контент
		$content = '';
		
		// Материалы (типы)
		if($this->request->getControllerName() == 'Modules\Data')
		{
			// Информация для шаблона
			$this->view->assign('tab1_Name', 'Материалы');
			$this->view->assign('tab1_Show_Settings', true);
			$this->view->assign('tab1_Show_Search', true);
			
			// Переключатель веточек
			$icon = 'fa-angle-down';
			$blockStyleA = 'background: #313131;';
			$blockStyle = 'display: block;';
			$iStyle = 'padding: 3px 7px 3px 7px;';
			$urlBrunch = 'close';
				
			// Url
			# $getArgs = [
			# 	'data' => $urlBrunch,
			# 	'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
			# 	'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
			# ];
			# $uriBuilder = $this->controllerContext->getUriBuilder();
			# $url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
			
			$content .= '
				<!--
				<div style="position: relative; '.$blockStyleA.' padding: 14px; border-bottom: gold 1px solid;">
					<a href="'.$url.'">
						<span class="" style="display: block; color: #f6f8f4;">
						<span style="background: white; padding-bottom: 1px;">'.$this->iconFactory->getIcon('actions-menu', Icon::SIZE_SMALL).'</span>&nbsp;
							Материалы (tx_data)
						</span>
						<span style="display: block; position: absolute; right: 10px; top: 10px; background: #595959; border: white 1px solid; border-radius: 3px;">
							<i class="fa '.$icon.'" aria-hidden="true" 
							style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>
						</span>
					</a>
				</div>
				-->
				<div style="'.$blockStyle.' padding: 25px 15px 15px 25px; border: #cccccc 0px solid; background: linear-gradient(to bottom, #08131f, #08131f 10px, #363f49 100px, #363f49 100%);">
			';
			
			$dataNotFound = 1;
			$filter = [];
			$filter['orderBy'] = ['sorting','desc'];
			$rowsGet = \Litovchenko\AirTable\Domain\Model\Content\DataTypeGroup::recSelect('get',$filter);
			foreach($rowsGet as $k => $v){
				$dataNotFound = 0;
				$temp = $this->rowContentData($v['uid']);
				$content .= '<div style="border-bottom: white 1px solid; margin-top: 10px; margin-bottom: 10px;color: wheat;font-weight: bold;">
					<!--<i class="fa '.$icon.'" aria-hidden="true" style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>-->'.$v['title'].'
				</div>';
				if($temp != ''){
					$content .= $temp;
				} else {
					$iconNot = $this->iconFactory->getIcon('content-news', Icon::SIZE_SMALL, 'overlay-missing');
					$content .= '<div style="margin: 15px 0 15px 0; color: #f6f8f4;">'.$iconNot.'&nbsp; Типы материалов не найдены!</div>';
				}
			}
			
			// Без группы
			$temp = $this->rowContentData(0);
			if($temp != ''){
					$content .= '<div style="border-bottom: white 1px solid; margin-top: 10px; margin-bottom: 10px;color: wheat;font-weight: bold;">
						<!--<i class="fa '.$icon.'" aria-hidden="true" style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>--> Без группы
					</div>
					';
				$dataNotFound = 0;
				$content .= $temp;
			}
			
			if($dataNotFound == 1){
				$icon = $this->iconFactory->getIcon('apps-pagetree-page-default', Icon::SIZE_SMALL, 'overlay-missing');
				$content .= '<div style="margin: 15px 0 15px 0; color: #f6f8f4;">'.$icon.'&nbsp; Материалы не найдены!</div>';
			}
			
			// Маркеры для шаблонов
			$content .= '<div style="border-bottom: white 1px solid; margin-top: 10px; margin-bottom: 10px;color: wheat;font-weight: bold;">
				<!--<i class="fa '.$icon.'" aria-hidden="true" style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>-->Маркеры для шаблонов
			</div>';
			
			foreach($GLOBALS['TCA']['tx_data']['columns']['RType']['config']['items'] as $k => $v){
				if(preg_match('/^Marker./is',$v[1])){
					
					// Active
					if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType') == $v[1]){
						$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
					} else {
						$active = 'padding: 3px 8px 3px 0px; ';
					}
					
					// Db count
					$filter = [];
					$filter['where'] = ['RType','=',$v[1]];
					$filter['withoutGlobalScope'] = ['DataTypeDefault'];
					$dbCount = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('count',$filter);
					
					// Url
					$getArgs = [
						'data' => 'open',
						'dataType' => $v[1],
						'paramExt' => '',
						'paramSubDomain' => '',
						'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\Data',
						'tabCategorizationActive'=>0
					];
					$uriBuilder = $this->controllerContext->getUriBuilder();
					$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
					
					// Access
					$accessOverlay = null;
					if(BaseUtility::BeUserAccessTableSelect('tx_data') == false){
						$url = '#';
						$accessOverlay = 'overlay-locked';
					}
					
					$content .= '
						<table width="100%;" style="border-spacing: 0px 0px;">
						<tr>
						<td>
							<a href="'.$url.'" class="" style="display: block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active.'"
							onclick="filterDataTypeZzz(\''.$v['uid'].'\',\''.$url.'\'); alert(1);">
								'.$this->iconFactory->getIcon('form-content-element', Icon::SIZE_SMALL, $accessOverlay).'&nbsp;
								'.$v[0].'
								<span style="color: #535353;">['.intval($dbCount).']</span>
							</a>
						</td>
						<!--
						<td style="text-align: right;">
							<span style="color: #535353;">[#'.preg_replace('/Marker./is','',$v[1]).']</span>&nbsp;
						</td>
						-->
						</tr>
						</table>
					';
				}
			}
			
			$content .= '</div>';
			
			// Добавить группу
			// Добавить материал
			// $content .= '<a class="btn btn-success btn-sm" style="margin-top: 0px;">Добавить группу</a><br />';
			// $content .= '<a class="btn btn-success btn-sm" style="margin-top: 0px;">Добавить тип материала</a><br />';
			if(BaseUtility::BeUserAccessTableSelect('tx_data_type_group')){
				$this->view->assign('showAddDataTypeGroup', 1);
			}
			if(BaseUtility::BeUserAccessTableSelect('tx_data_type')){
				$this->view->assign('showAddDataType', 1);
			}
		}
		
		// Характеристики
		if($this->request->getControllerName() == 'Modules\Attributes')
		{
			// Информация для шаблона
			$this->view->assign('tab1_Name', 'Атрибуты');
			$this->view->assign('tab1_Show_Settings', false);
			$this->view->assign('tab1_Show_Search', false);
			
			// Переключатель веточек
			#if(BaseUtility::_GETuc('sysEavAttr','close') == 'open'){
				$icon = 'fa-angle-down';
				$blockStyleA = 'background: #313131;';
				$blockStyle = 'display: block;';
				$iStyle = 'padding: 3px 7px 3px 7px;';
				$urlBrunch = 'close';
					
			#} else {
				#$icon = 'fa-angle-left';
				#$blockStyleA = 'background: #282c30;';
				#$blockStyle = 'display: none;';
				#$iStyle = 'padding: 3px 10px 3px 9px;';
				#$urlBrunch = 'open';
			#}
				
			// Url
			#$getArgs = [
			#	'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
			#	'sysEavAttr' => $urlBrunch
			#];
			#$uriBuilder = $this->controllerContext->getUriBuilder();
			#$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
			
			$content .= '
				<!--
				<div style="position: relative; '.$blockStyleA.' padding: 14px; border-bottom: gold 1px solid;">
					<a href="'.$url.'">
						<span class="" style="display: block; color: #f6f8f4;">
						<span style="background: white; padding-bottom: 1px;">'.$this->iconFactory->getIcon('actions-viewmode-tiles', Icon::SIZE_SMALL).'</span>&nbsp;
							Характеристики (sys_attribute)
						</span>
						<span style="display: block; position: absolute; right: 10px; top: 10px; background: #595959; border: white 1px solid; border-radius: 3px;">
							<i class="fa '.$icon.'" aria-hidden="true" 
							style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>
						</span>
					</a>
				</div>
				-->
				<div style="'.$blockStyle.' padding: 25px 15px 15px 25px; border: #cccccc 0px solid; background: linear-gradient(to bottom, #08131f, #08131f 10px, #363f49 100px, #363f49 100%);">
			';
			
			// Список сущностей
			$eavAttrNotFound = 1;
			$entityAnnotations = \Litovchenko\AirTable\Utility\BaseUtility::$entityAnnotations;
			foreach($entityAnnotations as $entityKey => $entityConf) {
				$_isEntity = $entityConf['_isEntity'];
				$temp = $this->rowContentEavAttr($entityKey);
				if($temp != ''){
					$_isEntityName = $entityConf['_isEntityName'];
					$content .= '<div style="border-bottom: white 1px solid; margin-top: 10px; margin-bottom: 10px;color: wheat; font-weight: bold;">
						<!--<i class="fa '.$icon.'" aria-hidden="true" style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>-->Сущность: '.$_isEntityName.'
					</div>
					';
					$eavAttrNotFound = 0;
					$content .= $temp;
				}
			}
			
			if($eavAttrNotFound == 1){
				$icon = $this->iconFactory->getIcon('apps-pagetree-page-default', Icon::SIZE_DEFAULT, 'overlay-missing');
				$content .= '<div style="margin: 15px 0 15px 0; color: #f6f8f4;">'.$icon.'&nbsp; Характеристики не найдены!</div>';
			}
			
			$content .= '</div>';
		}
			
		// Список таблиц
		if($this->request->getControllerName() == 'Modules\List')
		{
			// Информация для шаблона
			$this->view->assign('tab1_Name', 'Записи');
			$this->view->assign('tab1_Show_Settings', true);
			$this->view->assign('tab1_Show_Search', true);
			
			// 1
			$classList = [];
			$allClasses = BaseUtility::getLoaderClasses2();
			$models = array_merge((array)$allClasses['BackendModelCrud'],(array)$allClasses['BackendModelCrudOverride'],(array)$allClasses['BackendModelExtending']);
			foreach($models as $class) {
				
				// Регистрация новых пользовательских моделей
				if($class::$TYPO3['thisIs'] == 'BackendModelCrud'){
					$extName = BaseUtility::getExtNameFromClassPath($class);
					$subDomain = BaseUtility::getSubDomainNameFromClassPath($class);
					if(empty($subDomain)){
						$classList[$extName]['ZZZ_Root'][] = $class;
					} else {
						$classList[$extName][$subDomain][] = $class;
					}
				}
				
				// Регистрация моделей на основе существующей таблицы TYPO3
				if($class::$TYPO3['thisIs'] == 'BackendModelCrudOverride'){
					$extName = BaseUtility::getExtNameFromClassPath($class);
					$subDomain = BaseUtility::getSubDomainNameFromClassPath($class);
					if(empty($subDomain)){
						$classList[$extName]['ZZZ_Root'][] = $class;
					} else {
						$classList[$extName][$subDomain][] = $class;
					}
				}
				
				// Расширение моделей
				if($class::$TYPO3['thisIs'] == 'BackendModelExtending'){
					$extName = BaseUtility::getExtNameFromClassPath($class);
					$classList[$extName]['Ext'][] = $class;
				}
				
				// Регистрация моделей на основе существующей таблицы TYPO3
				#if(in_array('TYPO3\CMS\Extbase\DomainObject\AbstractEntity',$class_parents)){
				#	$r = new \ReflectionClass($class);
				#	if(!$r->isAbstract()){
				#		$extName = BaseUtility::getExtNameFromClassPath($class);
				#		$subDomain = BaseUtility::getSubDomainNameFromClassPath($class);
				#		$classList[$extName][$subDomain][] = $class;
				#	}
				#}
			}
			
			// Сортировка
			ksort($classList);
			foreach($classList as $extName => $subDomain){
				ksort($classList[$extName]);
				// sort($classList[$extName][$subDomain]);
			}
			
			$paramExt = BaseUtility::_GETuc('paramExt','');
			$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','');
			$paramClass = BaseUtility::_GETuc('paramClass','');
			
			foreach($classList as $kExt => $SubFolders){
				
				// Переключатель веточек
				if(BaseUtility::_GETuc('tableListExt'.$kExt,'close') == 'open'){
					$icon = 'fa-minus-square';
					$blockStyleA = 'background: #313131;';
					$blockStyle = 'display: block;';
					$iStyle = 'padding: 3px 7px 3px 7px;';
					$urlBrunch = 'close';
					
				} else {
					$icon = 'fa-plus-square';
					$blockStyleA = 'background: #282c30;';
					$blockStyle = 'display: none;';
					$iStyle = 'padding: 3px 7px 3px 7px;';
					$urlBrunch = 'open';
				}
				
				// Url
				$getArgs = [
					'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
					'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
					'paramExt' => $paramExt,
					'paramSubDomain' => $paramSubDomain,
					'paramClass' => $paramClass,
					'tableListExt'.$kExt => $urlBrunch
				];
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
				
				$content .= '
						<div style="position: relative; '.$blockStyleA.' padding: 14px; border-bottom: gold 1px solid;">
							<a href="'.$url.'">
							<span class="" style="display: block; color: #f6f8f4;">
								<!--<span style="background: white; padding-bottom: 1px;">'.$this->iconFactory->getIcon('actions-viewmode-photos', Icon::SIZE_SMALL).'</span>&nbsp;-->
								DB: '.ucfirst(preg_replace_callback('/_([a-z]){1}/is',function($matches){return strtoupper($matches[1]);},$kExt)).' <!--(таблицы)-->
							</span>
							<span style="display: block; position: absolute; right: 10px; top: 10px; background: #595959; border: white 1px solid; border-radius: 3px;">
									<i class="fa '.$icon.'" aria-hidden="true" 
									style="color: #f6f8f4; font-size: 18px; '.$iStyle.'"></i>
							</span>
							</a>
						</div>
						<div style="'.$blockStyle.' padding: 25px 15px 15px 25px; border: #cccccc 0px solid; background: linear-gradient(to bottom, #08131f, #08131f 10px, #363f49 100px, #363f49 100%);">
				';
				
				foreach($SubFolders as $kSubFolder => $vSubFolder){
					if($kSubFolder != 'ZZZ_Root'){
						$extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($kExt);
						if($kSubFolder == 'Ext'){
							$titleSubDomain = 'Расширение моделей';
						}elseif(file_exists($extRelPath.'Classes/Domain/Model/'.$kSubFolder.'/_.txt')){
							$titleSubDomain = file_get_contents($extRelPath.'Classes/Domain/Model/'.$kSubFolder.'/_.txt');
						} else {
							$titleSubDomain = '';
						}
						
						// Переключатель веточек
						if(BaseUtility::_GETuc('tableListExt'.$kExt.'_'.$kSubFolder,'close') == 'open'){
							$icon = 'fa-minus-square';
							$blockStyle = 'display: block;';
							$iStyle = 'padding-right: 3px; padding-left: 2px;';
							$urlBrunch = 'close';
						} else {
							$icon = 'fa-plus-square';
							$blockStyle = 'display: none;';
							$iStyle = 'padding-right: 3px; padding-left: 2px;';
							$urlBrunch = 'open';
						}
						
						// Url
						$getArgs = [
							'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
							'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
							'paramExt' => $paramExt,
							'paramSubDomain' => $paramSubDomain,
							'paramClass' => $paramClass,
							'tableListExt'.$kExt.'_'.$kSubFolder => $urlBrunch
						];
						$uriBuilder = $this->controllerContext->getUriBuilder();
						$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
						
						$content .= '
								
							<a href="'.$url.'" class="" style="display: block; margin-bottom: 8px; color: #f6f8f4;">
								<i class="fa '.$icon.'" aria-hidden="true" style="color: #f6f8f4; '.$iStyle.'"></i>
								'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageSubdomain', Icon::SIZE_SMALL).'&nbsp;
								'.$titleSubDomain.'
							</a>
							<div style="'.$blockStyle.' padding: 0px 15px 12px 20px; border: #cccccc 0px solid;">
									
						';
					}
					foreach($vSubFolder as $k => $v){
						
						// Если категория...
						if(BaseUtility::isModelCategoryForAnotherModel($v)){
							continue;
						}
						
						// Title
						if($kSubFolder == 'Ext'){
							$title = BaseUtility::getClassAnnotationValueNew(get_parent_class($v),'AirTable\Label');
						} else {
							$title = BaseUtility::getClassAnnotationValueNew($v,'AirTable\Label');
						}
						
						// Db count
						if(BaseUtility::getTableNameFromClass($v) == 'tx_data'){
							$dbCount = $v::withoutGlobalScope('DataTypeDefault')->count();
						} else {
							$dbCount = $v::count();
						}
							
						// Active
						if($paramClass == $v || $paramClass == $v."Category"){
							$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
						} else {
							$active = 'padding: 3px 8px 3px 0px; ';
						}
						
						// Icon
						if($kSubFolder == 'Ext'){
							$ic = 'extensions-tx-airtable-resources-public-icons-StorageTableRecordExt';
						// } elseif($GLOBALS['TCA'][BaseUtility::getTableNameFromClass($v)]['ctrl']['rootLevel'] == -1) {
							// $ic = 'extensions-tx-airtable-resources-public-icons-StorageTableRecord_pidAny';
						} else {
							$ic = 'extensions-tx-airtable-resources-public-icons-StorageTableRecord';
						}
						
						// Если категория
						if(BaseUtility::isModelSupportRowCategory($v) || BaseUtility::isModelSupportRowsCategories($v)){
							
							// Db count 2
							$vTable2 = $v."Category";
							if(BaseUtility::getTableNameFromClass($vTable2) == 'tx_data_category'){
								$dbCount2 = $vTable2::withoutGlobalScope('DataTypeDefault')->count();
							} else {
								$dbCount2 = $vTable2::count();
							}
							
							// Url
							$getArgs = [
								'paramClass' => $v, // table
								'tabCategorizationActive'=>1
							];
							$uriBuilder = $this->controllerContext->getUriBuilder();
							$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
							
							// Access
							$accessOverlay = null;
							if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($v)) == false){
								$url = '#';
								$accessOverlay = 'overlay-locked';
							}
							
							// Url 2
							if(BaseUtility::hasSpecialField($vTable2,'propref_parent')){
								$getArgs = [
									'paramClass' => $vTable2, // table
									'tabCategorizationActive'=>1
								];
							} else {
								$getArgs = [
									'paramClass' => $vTable2, // table
									'tabCategorizationActive'=>0
								];
							}
							$uriBuilder = $this->controllerContext->getUriBuilder();
							$url2 = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
							
							// Access 2
							$accessOverlay2 = null;
							if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($vTable2)) == false){
								$url2 = '#';
								$accessOverlay2 = 'overlay-locked';
							}
							
							$content .= '
								<div style="margin-bottom: 2px; color: #f6f8f4; white-space: nowrap; '.$active.'">
									<a href="'.$url.'" class="" style="color: #f6f8f4; text-align: left;">
										'.$this->iconFactory->getIcon($ic, Icon::SIZE_SMALL, $accessOverlay).'&nbsp;
										'.$title.' <span style="color: #535353;">['.$dbCount.']</span>
									</a>&nbsp;
									<a href="'.$url2.'" class="" style="color: #f6f8f4; text-align: left;">
										'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableCategoryRecord', Icon::SIZE_SMALL, $accessOverlay2).'&nbsp;
										<span style="color: #535353;">['.$dbCount2.']</span>
									</a>
									<!--
									&nbsp;|&nbsp;
									<a href="'.$url2.'" class="" style="color: #f6f8f4; text-align: left;">
										'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableCategoryRecord', Icon::SIZE_SMALL, $accessOverlay2).' 
										Категории 
										['.$dbCount2.']
									</a>
									-->
								</div>
							';
						} else {
							
							// Url
							if(BaseUtility::hasSpecialField($v,'propref_parent')){
								$getArgs = [
									'paramClass' => $v, // table
									'tabCategorizationActive'=>1
								];
							} else {
								$getArgs = [
									'paramClass' => $v, // table
									'tabCategorizationActive'=>0
								];
							}
							$uriBuilder = $this->controllerContext->getUriBuilder();
							$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
							
							// Access
							$accessOverlay = null;
							if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($v)) == false){
								$url = '#';
								$accessOverlay = 'overlay-locked';
							}
							
							$content .= '
								<a href="'.$url.'" class="" style="display: block; margin-bottom: 2px; color: #f6f8f4; text-align: left; white-space: nowrap; '.$active.'">
									'.$this->iconFactory->getIcon($ic, Icon::SIZE_SMALL, $accessOverlay).'&nbsp;
									'.$title.'
									<span style="color: #535353;">['.$dbCount.']</span>
								</a>
							';
						}
					}
					if($kSubFolder != 'ZZZ_Root'){
						$content .= '</div>';
					}
				}
				
				$content .= '</div>';
			}
		}
		
		return $content;
	}
	
	public function rowContentCategory($parentId = 0){
		$content = '';
		
		$paramClass = BaseUtility::_GETuc('paramClass','').'Category';
		$table = BaseUtility::getTableNameFromClass($paramClass);
		$sqlBuilderSelect = $paramClass::getModel();
		
		// Приоритет отдаеться категориям "List",
		// если определено два трейта
		if(BaseUtility::isModelSupportRowsCategories(BaseUtility::_GETuc('paramClass',''))){
			$field = 'propref_categories';
		} else {
			$field = 'propref_category';
		}
		if(BaseUtility::hasSpecialField(BaseUtility::_GETuc('paramClass',''),'propref_parent')){
			$field2 = 'propref_parent';
		} else {
			$field2 = ''; 
		}
		
		if(BaseUtility::hasSpecialField($paramClass,'propref_parent')){
			$rows = $sqlBuilderSelect
				->where('propref_parent','=',$parentId)
				->orderBy($GLOBALS['TCA'][$table]['ctrl']['label'])
				->get()
				->toArray();
		} else {
			$rows = $sqlBuilderSelect
				->orderBy($GLOBALS['TCA'][$table]['ctrl']['label'])
				->get()
				->toArray();
		}
		
		foreach($rows as $k => $v){
			if($v[$GLOBALS['TCA'][$table]['ctrl']['delete']] == 1) {
				$icon = $this->getIconForRecord($table, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			} else {
				$icon = $this->iconFactory->getIconForRecord($table, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			}
			
			// Sub 1 (папки)
			$treeLink = '';
			$contentSubCategory = '';
			$contentSubCategoryElement = '';
			if(BaseUtility::hasSpecialField($paramClass,'propref_parent')){
				
				// Sub 2 (элементы)
				$rowsCount2 = 0;
				if(BaseUtility::hasSpecialField(BaseUtility::_GETuc('paramClass',''),'propref_parent')){
					// contentSubCategoryElement
					// TxData Props Def (пропускаем колонки, которые не существуют в типе материала)
					$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
					if($this->TxDataPropsDefaultCheck('tx_data','propref_parent',$dataType) == 0){
						$rowsCount2 = $this->rowContentCategoryElementCount(0,$v['uid']);
					}
				}
				
				$rowsCount = $sqlBuilderSelect->where('propref_parent','=',$v['uid'])->count(); // Кол-во подпапок
				if($rowsCount+$rowsCount2 > 0){
					// Переключатель веточек
					BaseUtility::_POSTuc('form4Apply_'.$v['uid'],'form4Apply_'.$v['uid'],0); // Активация формы
					if(BaseUtility::_POSTuc('form4Apply_'.$v['uid'],'form4Apply_'.$v['uid'].'_branch','') == 'open'){
						$classIcon = 'fa-minus-square';
						$blockStyle = 'display: block;';
						$iStyle = 'padding-right: 3px; padding-left: 2px;';
						$urlBrunch = 'close';
						$js_value = 'closed';
						$contentSubCategory = $this->rowContentCategory($v['uid']);
						if($rowsCount2>0){
							$contentSubCategoryElement = $this->rowContentCategoryElement(0,$v['uid']);
						}
					} else {
						$classIcon = 'fa-plus-square';
						$blockStyle = 'display: none;';
						$iStyle = 'padding-right: 3px; padding-left: 2px;';
						$urlBrunch = 'open';
						$js_value = 'open';
						$contentSubCategory = '';
					}
					// Веточка
					$treeLink = '
					<a href="#" onclick="branchSwitch(\'form4\','.$v['uid'].',\''.$js_value.'\'); return false;">
						<i class="fa '.$classIcon.'" aria-hidden="true" style="color: #f6f8f4; '.$iStyle.'"></i>
					</a>';
				} else {
					// Без веточки
					$iStyle = 'padding-right: 3px; padding-left: 17px;';
					$treeLink = '<i style="color: #f6f8f4; '.$iStyle.'"></i>';
				}
			}

			// Соединяем две ветки в 1 убирая между ними дублирование "</ul><ul class="list-tree text-monospace">"
			# if(trim($contentSubCategory) != '' && trim($contentSubCategoryElement) != ''){
			# 	$contentSubCategory = preg_replace('/<\/ul>$/is','',trim($contentSubCategory));
			# 	$contentSubCategoryElement = preg_replace('/^<ul class="list-tree text-monospace">/is','',trim($contentSubCategoryElement));
			# }
			
			// Активность выбранной категории
			$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			if(in_array($v['uid'],explode(',',$paramFilter))){
				$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
			} else {
				$active = '';
			}
			
			// Название записи
			$title = '';
			if(BaseUtility::hasSpecialField($paramClass,'RType')){
				if($table != 'tx_data' && $table != 'tx_data_category'){
					$title .= '['.BaseUtility::getTcaFieldItem($table,'RType',$v['RType'])[0].'] ';
				}
			}
			if(BaseUtility::hasSpecialField($paramClass,'title')){
				$title .= $v['title'];
			} else {
				$title .= $v['uid'];
			}
			
			// Редактирование
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit', [
					'edit['.$table.']['.$v['uid'].']' => 'edit',
					// 'columnsOnly' => 'RType,title,propref_parent',
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				]
			);
			
			$content .= '
				<div>
					<style>
					.hoverEditIconWrap .hoverEditIcon { opacity: 0; }
					.hoverEditIconWrap:hover .hoverEditIcon { opacity: 1; }
					</style>
					<span class="hoverEditIconWrap" style="display: block; margin-bottom: 10px; color: #f6f8f4;">
						'.$treeLink.'
						<a href="#" class="" style="'.$active.' color: #f6f8f4;" onClick="filterCategory(\''.$field.'\',\''.$field2.'\','.$v['uid'].',\'\');">'.$icon.'&nbsp; '.$title.'</a>
						<a href="'.$backendLink.'" class="hoverEditIcon" style="background: #6daae0; color: #f6f8f4; padding: 3px;"><span class="t3js-icon icon icon-size-small icon-state-default"><span class="icon-markup"><i class="fa fa-edit" aria-hidden="true"></i></span></span> ['.$v['uid'].']</a>
					</span>
					<div style="padding-left: 21px;">
						'.$contentSubCategory.'
						'.$contentSubCategoryElement.'
					</div>
				</div>
			';
		}
		
		if(count($rows)!=0){
			if($parentId == 0){
				$icon = $this->iconFactory->getIcon('mimetypes-x-content-domain', Icon::SIZE_SMALL);
				// Активность выбранной категории
				$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
				if($paramFilter == ''){
					$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
				} else {
					$active = '';
				}
				return '
				<div style="">
				<div style="margin-bottom: 10px; color: #f6f8f4;">
					<a href="#" class="" style="'.$active.' color: #f6f8f4;" onClick="filterCategoryReset(\''.$field.'\',\''.$field2.'\');">
						'.$icon.'&nbsp;
						Все категории
					</a>
				</div>
				<div>
					'.$content.'
				</div>
				</div>';
			} else {
				return '
				<div style="">
					'.$content.'
				</div>';
			}
		} else {
			if(count($rows) == 0 && $parentId == 0){
				$icon = $this->iconFactory->getIcon('apps-filetree-folder-default', Icon::SIZE_SMALL, 'overlay-missing');
				return '<div style="margin: 15px 0 15px 0; color: #f6f8f4;">'.$icon.'&nbsp; Категории не найдены!</div>';
			} else {
				return '';
			}
		}
	}
	
	public function rowContentCategoryElementCount($parentId = 0, $categoryId = 0){
		// Кол-во подпапок
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$sqlBuilderSelect = $paramClass::getModel();
		
		if(BaseUtility::isModelSupportRowCategory($paramClass)){
			$rowsCount = $sqlBuilderSelect->where('propref_parent','=',$parentId)->where('propref_category','=',$categoryId)->count();
		} elseif(BaseUtility::isModelSupportRowsCategories($paramClass)){
			$rowsCount = $sqlBuilderSelect->where('propref_parent','=',$parentId)->whereHas('propref_categories', function($q) use ($categoryId) {
				$q->whereIn('uid_foreign', [$categoryId]);
			})->count();
		}
		return $rowsCount;
	}
	
	public function rowContentCategoryElement($parentId = 0, $categoryId = 0){
		$content = '';
		
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$table = BaseUtility::getTableNameFromClass($paramClass);
		$sqlBuilderSelect = $paramClass::getModel();
		
		$field = 'propref_parent';
		
		if(BaseUtility::isModelSupportRowCategory($paramClass)){
			$field2 = 'propref_category';
			$rows = $sqlBuilderSelect
				->where('propref_parent','=',$parentId)
				->where('propref_category','=',$categoryId)
				->orderBy($GLOBALS['TCA'][$table]['ctrl']['label'])
				->get()
				->toArray();
			
		} elseif(BaseUtility::isModelSupportRowsCategories($paramClass)){
			$field2 = 'propref_categories';
			$rows = $sqlBuilderSelect
				->where('propref_parent','=',$parentId)
				->whereHas('propref_categories', function($q) use ($categoryId) {
					$q->whereIn('uid_foreign', [$categoryId]);
				})
				->orderBy($GLOBALS['TCA'][$table]['ctrl']['label'])
				->get()
				->toArray();
		}
		
		foreach($rows as $k => $v){
			if($v[$GLOBALS['TCA'][$table]['ctrl']['delete']] == 1) {
				$icon = $this->getIconForRecord($table, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			} else {
				$icon = $this->iconFactory->getIconForRecord($table, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			}
			$treeLink = '';
			$contentSubCategory = '';
			#if(BaseUtility::hasSpecialField($paramClass,'propref_parent')){
				$rowsCount = $this->rowContentCategoryElementCount($v['uid'], $categoryId);
				if($rowsCount > 0){
					// Переключатель веточек
					BaseUtility::_POSTuc('form5Apply_'.$v['uid'],'form5Apply_'.$v['uid'],0); // Активация формы
					if(BaseUtility::_POSTuc('form5Apply_'.$v['uid'],'form5Apply_'.$v['uid'].'_branch','') == 'open'){
						$classIcon = 'fa-minus-square';
						$blockStyle = 'display: block;';
						$iStyle = 'padding-right: 3px; padding-left: 2px;';
						$urlBrunch = 'close';
						$js_value = 'closed';
						$contentSubCategory = $this->rowContentCategoryElement($v['uid'], $categoryId);
					} else {
						$classIcon = 'fa-plus-square';
						$blockStyle = 'display: none;';
						$iStyle = 'padding-right: 3px; padding-left: 2px;';
						$urlBrunch = 'open';
						$js_value = 'open';
						$contentSubCategory = '';
					}
					// Веточка
					$treeLink = '
					<a href="#" onclick="branchSwitch(\'form5\','.$v['uid'].',\''.$js_value.'\'); return false;">
						<i class="fa '.$classIcon.'" aria-hidden="true" style="color: #f6f8f4; '.$iStyle.'"></i>
					</a>';
				} else {
					// Без веточки
					$iStyle = 'padding-right: 3px; padding-left: 17px;';
					$treeLink = '<i style="color: #f6f8f4; '.$iStyle.'"></i>';
				}
			#}
			
			// Активность выбранной категории
			$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			if(in_array($v['uid'],explode(',',$paramFilter))){
				$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
			} else {
				$active = '';
			}
			
			// Название записи
			$title = '';
			if(BaseUtility::hasSpecialField($paramClass,'RType')){
				if($table != 'tx_data' && $table != 'tx_data_category'){
					$title .= '['.BaseUtility::getTcaFieldItem($table,'RType',$v['RType'])[0].'] ';
				}
			}
			if(BaseUtility::hasSpecialField($paramClass,'title')){
				$title .= $v['title'];
			} else {
				$title .= $v['uid'];
			}
			
			// Редактирование
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit', [
					'edit['.$table.']['.$v['uid'].']' => 'edit',
					// 'columnsOnly' => 'RType,title,propref_category,propref_categories,propref_parent',
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				]
			);
			
			$content .= '
				<div>
					<style>
					.hoverEditIconWrap .hoverEditIcon { opacity: 0; }
					.hoverEditIconWrap:hover .hoverEditIcon { opacity: 1; }
					</style>
					<span class="hoverEditIconWrap" style="display: block; margin-bottom: 10px; color: #f6f8f4;">
						'.$treeLink.'
						<a href="#" class="" style="'.$active.' color: #f6f8f4;" onClick="filterCategory(\''.$field.'\',\''.$field2.'\','.$v['uid'].',\''.$categoryId.'\');">'.$icon.'&nbsp; '.$title.'</a>
						<a href="'.$backendLink.'" class="hoverEditIcon" style="background: #6daae0; color: #f6f8f4; padding: 3px;"><span class="t3js-icon icon icon-size-small icon-state-default"><span class="icon-markup"><i class="fa fa-edit" aria-hidden="true"></i></span></span> ['.$v['uid'].']</a>
					</span>
					<div style="padding-left: 21px;">
						'.$contentSubCategory.'
					</div>
				</div>
			';
		}
		
		if(count($rows)!=0){
			if($parentId == 0){
				$icon = $this->iconFactory->getIcon('apps-filetree-root', Icon::SIZE_SMALL);
				// Активность выбранной категории
				$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
				if($paramFilter == ''){
					$active = 'badge badge-info';
				} else {
					$active = '';
				}
				return '
				<div>'.$content.'</div>';
			} else {
				return '<div>'.$content.'</div>';
			}
		} else {
			return '';
		}
	}
	
	public function rowContentElement($parentId = 0){
		$content = '';
		
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$table = BaseUtility::getTableNameFromClass($paramClass);
		$sqlBuilderSelect = $paramClass::getModel();
		$field = 'propref_parent';
		
		$rows = $sqlBuilderSelect
				->where('propref_parent','=',$parentId)
				->orderBy($GLOBALS['TCA'][$table]['ctrl']['label'])
				->get()
				->toArray();
		
		foreach($rows as $k => $v){
			if($v[$GLOBALS['TCA'][$table]['ctrl']['delete']] == 1) {
				$icon = $this->getIconForRecord($table, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			} else {
				$icon = $this->iconFactory->getIconForRecord($table, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			}
			$treeLink = '';
			$contentSubCategory = '';
			if(BaseUtility::hasSpecialField($paramClass,'propref_parent')){
				$rowsCount = $sqlBuilderSelect->where('propref_parent','=',$v['uid'])->count(); // Кол-во подпапок
				if($rowsCount > 0){
					// Переключатель веточек
					BaseUtility::_POSTuc('form5Apply_'.$v['uid'],'form5Apply_'.$v['uid'],0); // Активация формы
					if(BaseUtility::_POSTuc('form5Apply_'.$v['uid'],'form5Apply_'.$v['uid'].'_branch','') == 'open'){
						$classIcon = 'fa-minus-square';
						$blockStyle = 'display: block;';
						$iStyle = 'padding-right: 3px; padding-left: 2px;';
						$urlBrunch = 'close';
						$js_value = 'closed';
						$contentSubCategory = $this->rowContentElement($v['uid']);
					} else {
						$classIcon = 'fa-plus-square';
						$blockStyle = 'display: none;';
						$iStyle = 'padding-right: 3px; padding-left: 2px;';
						$urlBrunch = 'open';
						$js_value = 'open';
						$contentSubCategory = '';
					}
					// Веточка
					$treeLink = '
					<a href="#" onclick="branchSwitch(\'form5\','.$v['uid'].',\''.$js_value.'\'); return false;">
						<i class="fa '.$classIcon.'" aria-hidden="true" style="color: #f6f8f4; '.$iStyle.'"></i>
					</a>';
				} else {
					// Без веточки
					$iStyle = 'padding-right: 3px; padding-left: 17px;';
					$treeLink = '<i style="color: #f6f8f4; '.$iStyle.'"></i>';
				}
			}
			
			// Активность выбранной категории
			$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			if(in_array($v['uid'],explode(',',$paramFilter))){
				$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
			} else {
				$active = '';
			}
			
			// Название записи
			$title = '';
			if(BaseUtility::hasSpecialField($paramClass,'RType')){
				if($table != 'tx_data' && $table != 'tx_data_category'){
					$title .= '['.BaseUtility::getTcaFieldItem($table,'RType',$v['RType'])[0].'] ';
				}
			}
			if(BaseUtility::hasSpecialField($paramClass,'title')){
				$title .= $v['title'];
			} else {
				$title .= $v['uid'];
			}
			
			// Редактирование
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit', [
					'edit['.$table.']['.$v['uid'].']' => 'edit',
					// 'columnsOnly' => 'RType,title,propref_parent',
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				]
			);
			
			$content .= '
				<div>
					<style>
					.hoverEditIconWrap .hoverEditIcon { opacity: 0; }
					.hoverEditIconWrap:hover .hoverEditIcon { opacity: 1; }
					</style>
					<span class="hoverEditIconWrap" style="display: block; margin-bottom: 10px; color: #f6f8f4;">
						'.$treeLink.'
						<a href="#" class="" style="'.$active.' color: #f6f8f4;" onClick="filterCategory(\''.$field.'\',\'\','.$v['uid'].',\'\');">'.$icon.'&nbsp; '.$title.'</a>
						<a href="'.$backendLink.'" class="hoverEditIcon" style="background: #6daae0; color: #f6f8f4; padding: 3px;"><span class="t3js-icon icon icon-size-small icon-state-default"><span class="icon-markup"><i class="fa fa-edit" aria-hidden="true"></i></span></span> ['.$v['uid'].']</a>
					</span>
					<div style="padding-left: 21px;">
						'.$contentSubCategory.'
					</div>
				</div>
			';
		}
		
		if(count($rows)!=0){
			if($parentId == 0){
				$iconAll = $this->iconFactory->getIcon('mimetypes-x-content-domain', Icon::SIZE_SMALL);
				$iconRoot = $this->iconFactory->getIcon('apps-pagetree-folder-root', Icon::SIZE_SMALL);
					
				// Активность 1
				$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
				if($paramFilter == ''){
					$active = 'padding: 3px 8px 3px 8px; background: #79a548;';
				} else {
					$active = '';
				}
				
				// Активность 2
				#$paramFilter = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
				#if($paramFilter === 0){
				#	$activeRoot = 'badge badge-info';
				#} else {
				#	$activeRoot = '';
				#}
				
				return '
				<div style="">
				<div style="margin-bottom: 10px; color: #f6f8f4;">
					<a href="#" class="" style="'.$active.' color: #f6f8f4;" onClick="filterCategoryReset(\''.$field.'\');">'.$iconAll.'&nbsp; Все записи</a>
				</div>
				</div>
				<!--
				<div style="line-height: 21px;">
					<a href="#" class="" style="margin-bottom: 2px;" onClick="filterCategory(\''.$field.'\',\'\',\'0\',\'\');">'.$iconRoot.' Записи в корне</a>
				</div>
				-->
				<div>
					'.$content.'
				</div>
				';
			} else {
				return '<div>'.$content.'</div>';
			}
		} else {
			if(count($rows) == 0 && $parentId == 0){
				$icon = $this->iconFactory->getIcon('apps-pagetree-page-default', Icon::SIZE_SMALL, 'overlay-missing');
				return '<div style="margin: 15px 0 15px 0; color: #f6f8f4;">'.$icon.'&nbsp; Записи не найдены!</div>';
			} else {
				return '';
			}
		}
	}
	
	public function perform($table = ''){
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		$perform = $_POST['perform'];
		$idList = $_POST['idList'];
		if(!empty($idList)){
			switch($perform){
				case 'edit':
					$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
						'record_edit', [
							'edit['.$table.']['.$idList.']' => 'edit',
							'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
						]
					);
					header('Location: ' . $backendLink);
				break;
				case 'edit_columnsOnly':
					$columnsOnly = $_POST['columnsList'];
					$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
						'record_edit', [
							'columnsOnly' => $columnsOnly,
							'edit['.$table.']['.$idList.']' => 'edit',
							'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
						]
					);
					header('Location: ' . $backendLink);
				break;
				case 'hide':
					#$data = [];
					#$data[$table][$v] = [$fieldDisabled => 1];
					#$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
					#$dataHandler->start($data, []);
					#$dataHandler->process_datamap();
					$fieldDisabled = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
					foreach(explode(',',$idList) as $k => $v){
						DB::table($table)->where('uid', $v)->update([$fieldDisabled => 1]);
					}
				break;
				case 'unhide':
					#$data = [];
					#$data[$table][$v] = [$fieldDisabled => 0];
					#$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
					#$dataHandler->start($data, []);
					#$dataHandler->process_datamap();
					$fieldDisabled = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
					foreach(explode(',',$idList) as $k => $v){
						DB::table($table)->where('uid', $v)->update([$fieldDisabled => 0]);
					}
				break;
				case 'trash':
					#$data = [];
					#$data[$table][$v] = [$fieldDelete => 1];
					#$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
					#$dataHandler->start($data, []);
					#$dataHandler->process_datamap();
					$fieldDelete = $GLOBALS['TCA'][$table]['ctrl']['delete'];
					foreach(explode(',',$idList) as $k => $v){
						DB::table($table)->where('uid', $v)->update([$fieldDelete => 1]);
					}
				break;
				case 'untrash':
					#$cmd = [];
					#$cmd[$table][$v]['undelete'] = 1;
					#$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
					#$dataHandler->start([], $cmd);
					#$dataHandler->process_cmdmap();
					$fieldDelete = $GLOBALS['TCA'][$table]['ctrl']['delete'];
					foreach(explode(',',$idList) as $k => $v){
						DB::table($table)->where('uid', $v)->update([$fieldDelete => 0]);
					}
				break;
				case 'replicate':
					$paramClass = BaseUtility::_GETuc('paramClass','');
					foreach(explode(',',$idList) as $k => $v){
						$model = $paramClass::find($v);
						$cloneModel = $model->replicate();
						$cloneModel->title = '[NEW] ' . $cloneModel->title;
						$cloneModel->save();
					}
				break;
				case 'read':
					$getArgs = [
						'dataType' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType'),
						'sysEavAttrSelected' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected'),
						'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
						'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
						'paramClass' => BaseUtility::_GETuc('paramClass',''),
						'idList' => $idList
					];
					$uriBuilder = $this->controllerContext->getUriBuilder();
					$url = $uriBuilder->setArguments($getArgs)->uriFor('show', []);
					header('Location: ' . $url);
				break;
				#case 'read_columnsOnly':
				#	$getArgs = [
				#		'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
				#		'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
				#		'paramClass' => BaseUtility::_GETuc('paramClass',''),
				#		'idList' => $idList,
				#		'columnsOnly' => $_POST['columnsList'],
				#	];
				#	$uriBuilder = $this->controllerContext->getUriBuilder();
				#	$url = $uriBuilder->setArguments($getArgs)->uriFor('show', []);
				#	header('Location: ' . $url);
				#break;
			}
		}
	}
	
	public function formHtmlContent($table='', $rowsCount, $lim){
		$ar = [];
		
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		#print "<pre>";
		#print_r($_POST);
		#print "</pre>";
		
		// tab 0 (list)
		$pageListAr = [];
		$pageList = ceil($rowsCount/$lim);
		for($i = 1; $i <= $pageList; $i++){
			$selected = '';
			if($i == BaseUtility::_POSTuc('form0Apply','form0Page',1)){
				$selected = 'selected';
			}
			$ar['tab0']['page'] .= '<option value="'.$i.'" '.$selected.'>Страница '.$i.'</option>';
			$pageListAr[] = $i;
		}
		if(!in_array(BaseUtility::_POSTuc('form0Apply','form0Page',1),$pageListAr)){
			$this->pageBrokenSelect = true;
			$ar['tab0']['page'] .= '<option selected style="background: red; color: white;">Страница не существует</option>';
			$ar['tab0']['pageBrokenSelectStyle'] = 'border: red 1px solid;';
		}

		// (+) tab 1 (filter)
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
			
			// TxData Props Def (пропускаем колонки, которые не существуют в типе материала)
			$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
			if($this->TxDataPropsDefaultCheck($table,$k,$dataType) == 1){
				continue;
			}
			
			// Pid Пропускаем, если записи всегда в корне
			if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k == 'pid'){
				continue;
			}
			
			$filterContent = '';
			$AirTableFieldClass = $v['AirTable.Class'];
			if(method_exists($AirTableFieldClass, 'listControllerHtmlFilter')){
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$filterContent = $AirTableFieldClass::listControllerHtmlFilter($this, $table, $k, $v, $uriBuilder);
			} else {
				$filterContent = '<input class="form-control btn-sm" name="" value="" disabled>';
			}
			$noConfig = '';
			if(!$v['AirTable.Class']){
				$noConfig = 'color: red';
				continue; // Не выводим их - New!!!
			}
			$paramFilterTemp = BaseUtility::_POSTuc('form1Apply','form1Field_'.$k,'');
			$debugStr = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$debugStr = '<br /><code style="color: #b4b2b2;">'.$v['AirTable.Class'].'</code>';
			}
			$ar['tab1']['columns'] .= '
			<tr class="'.(!empty($paramFilterTemp)?'success':'').' filteTag1">
				<td align="right">
					<span style="'.$noConfig.'">
						'.htmlspecialchars($GLOBALS['LANG']->sL($v['label'])).'
						'.$debugStr.'
					</span>
				</td>
				<td>'.$filterContent.'</td>
				<td>
					<span style="'.$noConfig.'">'.$k.'</span>
				</td>
			</tr>';
		}
		
		// (+) tab 2 (order)
		$paramOrder = BaseUtility::_POSTuc('form2Apply','form2Order');
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
			
			// TxData Props Def (пропускаем колонки, которые не существуют в типе материала)
			$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
			if($this->TxDataPropsDefaultCheck($table,$k,$dataType) == 1){
				continue;
			}
			
			// Pid Пропускаем, если записи всегда в корне
			if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k == 'pid'){
				continue;
			}
			
			$disabled = '';
			$AirTableFieldClass = $v['AirTable.Class'];
			if(method_exists($AirTableFieldClass, 'listControllerSqlBuilderOrder')){
				if($AirTableFieldClass::listControllerSqlBuilderOrder($this, $table, $k, $v)!=true){
					$disabled = 'disabled';
				}
			} else {
				$disabled = 'disabled';
			}
			$noConfig = '';
			if(!$v['AirTable.Class']){
				$noConfig = 'color: red';
				continue; // Не выводим их - New!!!
			}
			$debugStr = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$debugStr = '<br /><code style="color: #b4b2b2;">'.$v['AirTable.Class'].'</code>';
			}
			$ar['tab2']['columns'] .= '
			<tr class="'.($paramOrder[$k] == 'ASC' || $paramOrder[$k] == 'DESC' || $paramOrder[$k] == 'RAND'?'success':'').' filteTag2">
			<td align="right">
				<span style="'.$noConfig.'">
					'.htmlspecialchars($GLOBALS['LANG']->sL($v['label'])).'
					'.$debugStr.'
				</span>
			</td>
			<td>
				<select name="form2Order['.$k.']" class="form-control btn-sm" style="" '.$disabled.'>
					<option value="">По умолчанию</option>
					<option value="ASC" '.($paramOrder[$k] == 'ASC'?'selected':'').'>ASC (по возрастанию 1,2,3)</option>
					<option value="DESC" '.($paramOrder[$k] == 'DESC'?'selected':'').'>DESC (по убыванию 3,2,1)</option>
					<option value="RAND" '.($paramOrder[$k] == 'RAND'?'selected':'').'>RAND (случайно)</option>
				</select>
			</td>
			<td>
				<span style="'.$noConfig.'">'.$k.'</span>
			</td>
			</tr>';
		}

		// (+) tab 3 (columns)
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
			
			// TxData Props Def (пропускаем колонки, которые не существуют в типе материала)
			$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
			if($this->TxDataPropsDefaultCheck($table,$k,$dataType) == 1){
				continue;
			}
			
			// Pid Пропускаем, если записи всегда в корне
			if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k == 'pid'){
				continue;
			}
			
			$checked = '';
			$disabled = '';
			if($k == 'uid' || $k == 'pid' || $k == 'RType'){
				$checked = 'checked';
				$disabled = 'disabled';
			}
			if(in_array($k,BaseUtility::_POSTuc('form3Apply','form3Columns'))){ // || BaseUtility::_POSTuc('form3Apply','form3Columns','all') == 'all'
				$checked = 'checked';
			}
			
			// Если по умолчанию НЕ показывать!
			if(BaseUtility::_POSTuc('form3Apply','form3Columns','all') == 'all' 
			&& BaseUtility::getClassFieldAnnotationValueNew($paramClass,$k,'AirTable\Field\Show') == 1){
				$checked = 'checked';
			}
			
			$noConfig = '';
			if(!$v['AirTable.Class']){
				$noConfig = 'color: red';
				// continue; // Не выводим их - New!!!
			}
			$debugStr = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$debugStr = '<br /><code style="color: #b4b2b2;">'.$v['AirTable.Class'].'</code>';
			}
			$ar['tab3']['columns'] .= '
			<tr class="'.($checked == 'checked'?'success':'').' filteTag3">
			<td align="right">
				<span style="'.$noConfig.'">
					'.htmlspecialchars($GLOBALS['LANG']->sL($v['label'])).'
					'.$debugStr.'
				</span>
			</td>
			<td>
				<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 0px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
					<input type="checkbox" name="form3Columns[]" class="btn btn-default btn-sm" style="margin-top: 0px;" value='.$k.' '.$disabled.' '.$checked.'> 
					Включить в список
				</label>
			</td>
			<td>
				<span style="'.$noConfig.'">'.$k.'</span>
			</td>
			</tr>';
		}
		
		// New Record
		$ar['NewRecords']['columns'] = str_replace('filteTag3','filteTagNewRecords',$ar['tab3']['columns']);
		
		$limit = [1=>1,2=>2,3=>3,4=>4,5=>5,10=>10,15=>15,30=>30,100=>100,1000=>1000,-1=>'Все']; // ,-2=>'Случайная запись'
		foreach($limit as $k => $v){
			$selected = '';
			if($k == BaseUtility::_POSTuc('form3Apply','form3Limit',30)){
				$selected = 'selected';
			}
			$ar['tab3']['limit'] .= '
				<option value="'.$k.'" '.$selected.'>'.$v.'</option>
			';
		}
		
		$th_duplicate = [0=>'Не дублировать',1,2,3,4,5,10,50,100];
		foreach($th_duplicate as $k => $v){
			$selected = '';
			if($k == BaseUtility::_POSTuc('form3Apply','form3ThDuplicate',0)){
				$selected = 'selected';
			}
			$ar['tab3']['th_duplicate'] .= '
				<option value="'.$k.'" '.$selected.'>'.$v.'</option>
			';
		}
		
		$rows_render = [
			0=>'Расширенный список (горизонтально)',
			1=>'Расширенный список (вертикально)',
			2=>'Простой список на основе названия записи',
			3=>'Список в виде плитки',
		];
		foreach($rows_render as $k => $v){
			$selected = '';
			if($k == BaseUtility::_POSTuc('form3Apply','form3RowsRender',$this->defaultListTypeRender)){
				$selected = 'selected';
			}
			$ar['tab3']['rows_render'] .= '
				<option value="'.$k.'" '.$selected.'>'.$v.'</option>
			';
		}
		
		$td_realvalue = [0=>'Нет',1=>'Да'];
		foreach($td_realvalue as $k => $v){
			$selected = '';
			if($k == BaseUtility::_POSTuc('form3Apply','form3TdRealvalue',0)){
				$selected = 'selected';
			}
			$ar['tab3']['td_realvalue'] .= '
				<option value="'.$k.'" '.$selected.'>'.$v.'</option>
			';
		}
		
		return $ar;
	}
	
	public function rowContent($rows, $table=''){
		// Собираем контент
		$jCount = 1;
		$content = '';
		$contentTh = [];
		$contentTd = [];
		
		$contentTh_0 = '<th nowrap style="padding: 2px; border-top: none; vertical-align: top;">
			<div style="position: relative;">
			<label class="btn btn-default btn-sm" style="width: 100%; margin: 0; padding: 0 5px 0 5px; text-align: left; cursor: pointer;">
				<input type="checkbox" class="btn btn-default btn-sm" style="margin-top: 0px;" name="foo_uid" value="0" onClick="toggleCheckboxes(this,\'foo_uid\'); countCheckboxes(this,\'foo_uid\');" disabled_ZZZ>
			</label>
			</div>
		</th>';
		
		$contentTd_0 = '<td nowrap style="padding: 2px; border-top: none; vertical-align: top;">
			<div style="position: relative;">
			<label class="btn btn-default btn-sm" style="width: 100%; margin: 0; padding: 0 5px 0 5px; text-align: left; cursor: pointer;">
				<input type="checkbox" class="btn btn-default btn-sm" style="margin-top: 0px;" name="foo_uid" onClick="countCheckboxes(this,\'foo_uid\');" value="###UID###" disabled_ZZZ>
			</label>
			</div>
		</td>';
		
		// Собираем название колонок (в зависимости от настройки дублируются)
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
			
			// Оставляем только выбранные колонки
			if(!in_array($k, BaseUtility::_POSTuc('form3Apply','form3Columns','all'))
				&& BaseUtility::_POSTuc('form3Apply','form3Columns','all') != 'all'
				&& $k != 'uid' && $k != 'pid' && $k != 'RType' && BaseUtility::_POSTuc('form3Apply','form3RowsRender',$this->defaultListTypeRender) < 2){
				continue;
			}
			
			// Pid Пропускаем, если записи всегда в корне
			if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k == 'pid'){
				continue;
			}
			
			// Если по умолчанию НЕ показывать!
			if(BaseUtility::_POSTuc('form3Apply','form3Columns','all') == 'all'
			&& BaseUtility::getClassFieldAnnotationValueNew($paramClass,$k,'AirTable\Field\Show') == 0)
			{
				continue;
			}
				
			$AirTableFieldClass = $v['AirTable.Class'];
			if(method_exists($AirTableFieldClass, 'listControllerHtmlTh')){
				$contentTh[$k] = $AirTableFieldClass::listControllerHtmlTh($this, $table, $k, $v);
				$jCount++;
			} else {
				// Название поля
				$debugStr = '';
				if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
					$debugStr = '<br /><code style="color: red;">'.$k.'</code>';
				}
				$contentTh[$k] = '<th nowrap style="border-top: none;">
					<div style="position: relative; color: red;">
						<!--
						<label class="btn btn-default btn-sm" style="position: absolute; top: -4px; left: -30px; margin: 0; padding: 0 5px 0 5px; cursor: pointer;">
							<input type="checkbox" class="btn btn-default btn-sm" style="margin-top: 0px;" name="field_uid" value="'.$k.'" onClick="toggleFields(this,\'field_uid\');" disabled_ZZZ>
						</label>
						-->
						'.htmlspecialchars($GLOBALS['LANG']->sL($v['label'])).'
						'.$debugStr.'
					</div>
				</th>';
				$jCount++;
			}
		}
		
		// Собираем строки
		$paramClass = BaseUtility::_GETuc('paramClass','');
		foreach($rows as $k => $v){
			// $content .= '<tr>';
			$columns = $GLOBALS['TCA'][$table]['columns'];
			foreach($columns as $k2 => $v2){
				
				// Оставляем только выбранные колонки
				if(!in_array($k2, BaseUtility::_POSTuc('form3Apply','form3Columns')) 
					&& BaseUtility::_POSTuc('form3Apply','form3Columns','all') != 'all'
					&& $k2 != 'uid' && $k2 != 'pid' && $k2 != 'RType' && BaseUtility::_POSTuc('form3Apply','form3RowsRender',$this->defaultListTypeRender) < 2){
					continue;
				}
			
				// Pid Пропускаем, если записи всегда в корне
				if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k2 == 'pid'){
					continue;
				}

				// Если по умолчанию НЕ показывать!
				if(BaseUtility::_POSTuc('form3Apply','form3Columns','all') == 'all' 
				&& BaseUtility::getClassFieldAnnotationValueNew($paramClass,$k2,'AirTable\Field\Show') == 0)
				{
					continue;
				}
			
				// [RType] ->->-> Проверяем есть ли эта колонка в структуре?
				$fieldsInType = BaseUtility::getAllColumnsFromType($table, $v[$GLOBALS['TCA'][$table]['ctrl']['type']]);
				if(isset($GLOBALS['TCA'][$table]['ctrl']['type']) 
						&& isset($GLOBALS['TCA'][$table]['columns'][$GLOBALS['TCA'][$table]['ctrl']['type']]) 
							&& !in_array($k2,$fieldsInType) 
								&& $k2 != 'uid' 
									&& $k2 != 'pid'
										&& $table != 'sys_file' // Исключение (TCA оформленна не полностью)
				){
					$contentTd[$v['uid']][$k2] = '<td style="background: #ededed; color: #b4b2b2; vertical-align: top;" nowrap><i>Не существует</i></td>';
				} else {
					$AirTableFieldClass = $v2['AirTable.Class'];
					if(method_exists($AirTableFieldClass, 'listControllerHtmlTd')){
						$editIcon = $this->iconFactory->getIcon('actions-page-open', Icon::SIZE_SMALL).' ';
						$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
							'record_edit', [
								'columnsOnly' => $k2,
								'edit['.$table.']['.$v['uid'].']' => 'edit',
								'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
							]
						);
						if(BaseUtility::_POSTuc('form3Apply','form3TdRealvalue',0) == 1){
							$contentTd[$v['uid']][$k2] = '<td style="vertical-align: top; color: blue;" nowrap>'.nl2br(htmlspecialchars($v[$k2])).'</td>';
						} else {
							$contentTd[$v['uid']][$k2] = $AirTableFieldClass::listControllerHtmlTd($this, $table, $k2, $v2, $v, $backendLink, $editIcon);
						}
					} else {
						$contentTd[$v['uid']][$k2] = '<td style="vertical-align: top;" nowrap><span style="color: red;">'.nl2br(htmlspecialchars($v[$k2])).'</span></td>';
					}
				}
			}
			// $content .= '<td style="'.LAST_COLUMN.'"><!--для растягивания колонок когда их всего 2 шт.--></td>';
			// $content .= '</tr>';
		}
		
		// Если пусто
		if(count($rows) == 0){
			$icon = $this->iconFactory->getIcon('apps-pagetree-page-default', Icon::SIZE_DEFAULT, 'overlay-missing');
			#$content .= '<tr class="active">';
			#$content .= $contentTh_0;
			#$content .= implode('',$contentTh);
			#$content .= '</tr>';
			$content .= '<tr>';
			$content .= '<td style="border-top: none;" colspan="'.($jCount+10).'" align="left">
							<div style="margin: 50px;">
								<h3>'.$icon.' Записи не найдены!</h3>
							</div>
						</td>';
			$content .= '</tr>';
		
		// Простой список
		} elseif(BaseUtility::_POSTuc('form3Apply','form3RowsRender',$this->defaultListTypeRender)==2) {
			$fieldType = $GLOBALS['TCA'][$table]['ctrl']['type'];
			$fieldDisabled = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
			$fieldLabel = $GLOBALS['TCA'][$table]['ctrl']['label'];
			$fieldSort = $GLOBALS['TCA'][$table]['ctrl']['sortby'];
			$content .= '<tr class="">';
			$content .= $contentTh_0;
			$content .= '<th>ID</th>';
			if(isset($GLOBALS['TCA'][$table]['columns']['propmedia_thumbnail']) || $table == 'sys_file' || $table == 'sys_file_metadata'){
				$content .= '<th>Миниатюра</th>';
			}
			$content .= '<th>Название записи</th>';
			if($fieldDisabled != ''){
				# $content .= '<th>Активность</th>';
			}
			if($fieldSort != '' && isset($GLOBALS['TCA'][$table]['columns'][$fieldSort])){
				# $content .= '<th>Сортировка</th>';
			}
			if($fieldType != '' && isset($GLOBALS['TCA'][$table]['columns'][$fieldType])){
				# $content .= '<th>Тип записи</th>';
			}
			$content .= '</tr>';
			foreach($contentTd as $kTd => $vTd){
				$recordIdCurrent = strip_tags($vTd['uid']);
				$content .= '<tr class="">';
				$content .= str_replace('###UID###',$kTd,$contentTd_0);
				$content .= $vTd['uid'];
				
				if(isset($GLOBALS['TCA'][$table]['columns']['propmedia_thumbnail']) || $table == 'sys_file' || $table == 'sys_file_metadata'){
					if($table == 'sys_file' || $table == 'sys_file_metadata'){
						preg_match_all('@href="([^"]+)"@',$vTd['fileinfo'],$matches);
						$thumbnail = $matches[1][0];
					} else {
						preg_match_all('@href="([^"]+)"@',$vTd['propmedia_thumbnail'],$matches);
						$thumbnail = $matches[1][2];
					}
					
					//image_not_found.png
					if(!file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$thumbnail) || $thumbnail == ''){
						$thumbnail = '/typo3conf/ext/air_table/Resources/Public/Img/ImageNotFound.png';
					}
					
					$size = getimagesize($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$thumbnail); 
					$pathinfo = pathinfo($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$thumbnail);
					if(in_array(strtolower($pathinfo['extension']),['svg','png','jpg','jpeg','gif','bmp'])) {
						$content .= '<td style="padding: 0; text-align: center; vertical-align: top; position: relative;">';
						$content .= '<a href="'.$thumbnail.'" target="_blank"><img src="'.$thumbnail.'" style="max-width: 100%; height: 30px; border: #ccc 0px solid;"></a>';
						$content .= '</td>';
					} else {
						// typo3/sysext/frontend/Resources/Public/Icons/FileIcons/
						if(file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3/sysext/frontend/Resources/Public/Icons/FileIcons/'.strtolower($pathinfo['extension']).'.gif')){
							$fileIcon = '/typo3/sysext/frontend/Resources/Public/Icons/FileIcons/'.strtolower($pathinfo['extension']).'.gif';
						} else {
							$fileIcon = '/typo3conf/ext/air_table/Resources/Public/Img/ImageNotFound.png';
						}
						$content .= '<td style="padding: 0; text-align: center; vertical-align: center;">';
						$content .= '<a href="'.$thumbnail.'" target="_blank"><img src="'.$fileIcon.'" style="width: auto; height: 16px; border: #ccc 0px solid;"></a>';
						$content .= '</td>';
					}
				}
				
				if($fieldLabel != ''){
					$content .= '<td style="padding: 0; vertical-align: top;" nowrap>';
					$content .= '<div class="panel-group panel-group-rst" role="tablist" aria-multiselectable="true" style="margin: 0;">
					<div class="panel panel-default panel-version t3js-version-changes" data-version="9.5.x" style="margin: 0; border: 0;">
					<div class="panel-heading" role="tab" id="heading-0" style="padding: 6px 10px 6px 10px; background: none; color: #000;">
						<a href="#version-'.md5($vTd[$fieldLabel]).'" class="collapsed" data-bs-toggle="collapse" data-toggle="collapse" aria-expanded="true" style="font-weight: normal;">
							<span class="caret" style="margin-top: -2px; margin-left: 0;"></span> 
							'.strip_tags($vTd[$fieldLabel]).'
						</a>
						<br />
					</div>
					<div class="panel-collapse collapse" id="version-'.md5($vTd[$fieldLabel]).'" role="tabpanel" data-group-version="9.5.x" aria-expanded="true">
					<div class="panel-body t3js-changelog-list" role="tablist" aria-multiselectable="false" style="padding: 0px;  border: #cccccc 0px solid;">
					<div style="overflow: hidden; border: #cccccc 0px solid;">
					<table class="table table-bordered table-hover" style="width: 100%; border: #cccccc 0px solid; background: #fff;">';
					
					$columns = $GLOBALS['TCA'][$table]['columns'];
					foreach($columns as $k2 => $v2){
						// Оставляем только выбранные колонки
						if(!in_array($k2, BaseUtility::_POSTuc('form3Apply','form3Columns')) 
							&& BaseUtility::_POSTuc('form3Apply','form3Columns','all') != 'all'
							&& $k2 != 'uid' && $k2 != 'pid' && $k2 != 'RType'){
							continue;
						}
						if(strip_tags($contentTh[$k2]) != ''){
							$content .= '<tr>';
							$content .= '<td style="align: right; border-left: 0;">'.strip_tags($contentTh[$k2]).'</td>';
							foreach($contentTd as $kTd => $vTd){
								foreach($vTd as $tdKey => $tdContent){
									if(strip_tags($vTd['uid']) != $recordIdCurrent){
										continue;
									}
									if($tdKey != $k2){
										continue;
									}
									$content .= $tdContent;
								}
							}
							$content .= '</tr>';
						}
					}
					
					$content .= '
					</table>
					</div>
					</div>
					</div>
					</div>';
					$content .= '</td>';
				} else {
					$content .= '-';
				}
				
				if($fieldDisabled != ''){
					# $content .= $vTd[$fieldDisabled];
				}
				
				if($fieldSort != '' && isset($GLOBALS['TCA'][$table]['columns'][$fieldSort])){
					# $content .= $vTd[$fieldSort];
				}
				
				if($fieldType != '' && isset($GLOBALS['TCA'][$table]['columns'][$fieldType])){
					# $content .= $vTd[$fieldType];
				}

				// if($fieldDisabled != ''){
				// 	$content .= $vTd[$fieldDisabled];
				// }
				// $content .= $contentTd_0;
				$content .= '</tr>';
			}
		
		// В виде плитки
		} elseif(BaseUtility::_POSTuc('form3Apply','form3RowsRender',$this->defaultListTypeRender)==3) {
			$fieldType = $GLOBALS['TCA'][$table]['ctrl']['type'];
			$fieldDisabled = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
			$fieldLabel = $GLOBALS['TCA'][$table]['ctrl']['label'];
			$fieldSort = $GLOBALS['TCA'][$table]['ctrl']['sortby'];
			$content .= '<tr class="active">';
			$content .= '<td style="background: white; padding: 0;">';
			$content .= '<div class="distribution-holder card-container" style="margin: 0;">';
			foreach($contentTd as $kTd => $vTd){
				$recordIdCurrent = strip_tags($vTd['uid']);
				
				$rDisabled = '';
				if($fieldDisabled != ''){
					$rDisabled = $vTd[$fieldDisabled];
					$rDisabled = '<br />'.strip_tags($rDisabled,'<div><span>');
				}
				
				$rSort = '';
				if($fieldSort != '' && isset($GLOBALS['TCA'][$table]['columns'][$fieldSort])){
					$rSort = $vTd[$fieldSort];
					$rSort = '<br />Сортировка: '.strip_tags($rSort);
				}
				
				$rType = '';
				if($fieldType != '' && isset($GLOBALS['TCA'][$table]['columns'][$fieldType])){
					$rType = $vTd[$fieldType];
				}
				
				$rTitle = '';
				if($fieldLabel != ''){
					$rTitle = strip_tags($vTd[$fieldLabel]);
				} else {
					$rTitle = '-';
				}
				
				preg_match_all('@href="([^"]+)"@',$vTd['uid'],$matches);
				$editLink = $matches[1][0];
				
				if($table == 'sys_file' || $table == 'sys_file_metadata'){
					preg_match_all('@href="([^"]+)"@',$vTd['fileinfo'],$matches);
					$thumbnail = $matches[1][0];
				} else {
					preg_match_all('@href="([^"]+)"@',$vTd['propmedia_thumbnail'],$matches);
					$thumbnail = $matches[1][2];
				}
				
				//image_not_found.png
				if(!file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$thumbnail) || $thumbnail == ''){
					$thumbnail = '/typo3conf/ext/air_table/Resources/Public/Img/ImageNotFound.png';
				}
					
				$size = getimagesize($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$thumbnail); 
				$pathinfo = pathinfo($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$thumbnail);
				if(in_array(strtolower($pathinfo['extension']),['svg','png','jpg','jpeg','gif','bmp'])) {
					
				} else {
					// typo3/sysext/frontend/Resources/Public/Icons/FileIcons/
					if(file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3/sysext/frontend/Resources/Public/Icons/FileIcons/'.strtolower($pathinfo['extension']).'.gif')){
						$thumbnail = '/typo3/sysext/frontend/Resources/Public/Icons/FileIcons/'.strtolower($pathinfo['extension']).'.gif';
					} else {
						$thumbnail = '/typo3/sysext/frontend/Resources/Public/Icons/FileIcons/default.gif';
					}
				}
				
				// Show info
				$getArgs = [
						'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
						'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
						'paramClass' => BaseUtility::_GETuc('paramClass',''),
						'idList' => $recordIdCurrent
				];
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$url = $uriBuilder->setArguments($getArgs)->uriFor('show', []);
				
				$content .= '
   <div class="distribution card card-size-fixed-small" style="width: 25%; margin: 0px; border: 0; box-shadow: none !important; ">
   <div style="padding: 15px; border: 0; box-shadow: none;">
   <div style="background: #eee; border: #c8c8c8 1px solid;">
      <div class="distribution-image card-image" style="box-shadow: none; overflow: hidden;">
		<div style="position: absolute; top: 8px; left: 8px; z-index: 50;"><table><tr>'.str_replace('###UID###',$kTd,$contentTd_0).'</tr></table></div>
         <a href="'.$thumbnail.'" target="_blank">
			<img src="'.$thumbnail.'" style="width: auto; height: 150px; border: black 0px dotted;">
         </a>
         <div class="typo3-dependency-version card-image-badge">
            <!--<span class="label label-success">ID:'.strip_tags($vTd['uid']).'</span>-->
			<a href="'.$url.'" class="" target="_blank" style="">
            <span class="label label-success">
				<i class="fa fa-reorder" aria-hidden="true"></i>
				INFO [id='.trim($recordIdCurrent).']
			</span>
			</a>
         </div>
      </div>
      <div class="distribution-meta card-content" style="padding: 0px; text-align: center;">
         <div style="background: #c8c8c8; padding: 8px;">'.strip_tags($rType,'<div><span>').'</div>
		 <div style="padding: 8px;">
		 <b>
		 <a href="'.$editLink.'">
			'.$rTitle.'
         </a>
		 </b>
		 '.$rSort.'
		 '.$rDisabled.'
		 </div>
      </div>
   </div>
   </div>
   </div>
				';
			}
			$content .= '</div>';
			$content .= '</td>';
			$content .= '</tr>';
			
		// Вертикальный вывод
		} elseif(BaseUtility::_POSTuc('form3Apply','form3RowsRender',$this->defaultListTypeRender)==1) {
			$content .= '<tr class="active">';
			$content .= $contentTh_0;
			$foreachCounter = 0;
			$th_duplicate = BaseUtility::_POSTuc('form3Apply','form3ThDuplicate',0);
			foreach($contentTd as $kTd => $vTd){
				if($foreachCounter >= 1 && is_int($foreachCounter/$th_duplicate)){
					$content .= str_replace('disabled_ZZZ','disabled',$contentTh_0);
				}
				$content .= str_replace('###UID###',$kTd,$contentTd_0);
				$foreachCounter++;
			}
			$content .= '</tr>';
			$columns = $GLOBALS['TCA'][$table]['columns'];
			foreach($columns as $k2 => $v2){
				// Оставляем только выбранные колонки
				if(!in_array($k2, BaseUtility::_POSTuc('form3Apply','form3Columns')) 
					&& BaseUtility::_POSTuc('form3Apply','form3Columns','all') != 'all'
					&& $k2 != 'uid' && $k2 != 'pid' && $k2 != 'RType'){
					continue;
				}
				$foreachCounter = 0;
				$content .= '<tr>';
				$content .= $contentTh[$k2];
				foreach($contentTd as $kTd => $vTd){
					foreach($vTd as $tdKey => $tdContent){
						if($tdKey != $k2){
							continue;
						}
						if($foreachCounter >= 1 && is_int($foreachCounter/$th_duplicate)){
							$content .= str_replace('disabled_ZZZ','disabled',$contentTh[$tdKey]);
						}
						$content .= $tdContent;
						$foreachCounter++;
					}
				}
				$content .= '</tr>';
			}
		
		// Горизонтальный вывод (по умолчанию)
		} else {
			$content .= '<tr class="active">';
			$content .= $contentTh_0;
			$content .= implode('',$contentTh);
			$content .= '</tr>';
			$foreachCounter = 0;
			$th_duplicate = BaseUtility::_POSTuc('form3Apply','form3ThDuplicate',0);
			foreach($contentTd as $kTd => $vTd){
				if($foreachCounter >= 1 && is_int($foreachCounter/$th_duplicate)){
					$content .= '<tr class="active">';
					$content .= str_replace('disabled_ZZZ','disabled',$contentTh_0);
					$content .= str_replace('disabled_ZZZ','disabled',implode('',$contentTh));
					$content .= '</tr>';
				}
				$content .= '<tr>';
				$content .= str_replace('###UID###',$kTd,$contentTd_0);
				$content .= implode('',$vTd);
				$content .= '</tr>';
				$foreachCounter++;
			}
		}
		
		// Нижняя часть ("thFooter")
		/*
		$content .= '<tr class="active">';
		$content .= '<th></th>';
		$content .= '<th></th>';
		
		$columns = [];
		$columns['uid']['label'] = 'ID';
		$columns['uid']['AirTableConfig'] = 'INPUT_NUMBER';
		if(@in_array("RType", $GLOBALS['TCA'][$table]['ctrl']['fieldsDefault'])){
			$columns['RType'] = $GLOBALS['TCA'][$table]['columns']['RType'];
			$columns['RType']['label'] = 'Тип записи';
			$columns['RType']['AirTableConfig'] = 'SWITCH';
		}
		$columns += $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
			if($k == 'uid'){ continue; }
			if($k != 'RType' && !in_array($k, $this->getParam($table,'tab3','columns','all')) && $this->getParam($table,'tab3','columns','all') != 'all'){
				continue;
			}
				
			$AirTableConfig = $v['AirTableConfig'];
			$class = 'ColumnLibraryConfiguration_'.$AirTableConfig;
			if(class_exists($class) && method_exists($class, 'thFooter')){
				$obj = new $class;
				$content .= $obj->thFooter($this, $table, $k, $v);
			} else {
				$content .= '<th></th>';
			}
		}
		$content .= '</tr>';
		*/
			
		return $content;
	}
	
    /**
     * Main action for administration
     */
    public function showAction()
    {
		// Параметры _GET
		$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
	
		// Создаем модель
		$sqlBuilderSelect = $paramClass::getModel();
		$table = BaseUtility::getTableNameFromClass($paramClass);
		
		// 0 // livesearch
		$searchFields = explode(',',$GLOBALS['TCA'][$table]['ctrl']['searchFields']);
		$liveSearch = BaseUtility::_POSTuc('form6Apply','form6Value','');
		if(trim($liveSearch) != ''){
			$sqlBuilderSelect = $sqlBuilderSelect->where(function ($query) use ($searchFields, $liveSearch) {
				foreach($searchFields as $k => $v){
					if(trim($v) != null){
						if($v == 'uid'){
							$query->orWhere($v, '=', $liveSearch);
						} else {
							$query->orWhere(trim($v), 'LIKE', '%'.str_replace(' ','%',$liveSearch).'%');
						}
					}
				}
			});
		}
		
		// 1 // select
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
		
			// Оставляем только выбранные колонки
			#if((in_array($k, BaseUtility::_POSTuc('form3Apply','tab3_columns','all')) && BaseUtility::_POSTuc('form3Apply','tab3_columns','all') != 'all') 
			#	|| ($k == 'uid' && BaseUtility::_POSTuc('form3Apply','tab3_columns','all') != 'all')){
			#		$sqlBuilderSelect = $sqlBuilderSelect->addSelect($table.'.'.$k);
			#}
			$AirTableFieldClass = $v['AirTable.Class'];
			if(method_exists($AirTableFieldClass, 'listControllerSqlBuilder')){
				$sqlBuilderSelect = $AirTableFieldClass::listControllerSqlBuilder($this, $sqlBuilderSelect, $table, $k, $v);
			}
			if(method_exists($AirTableFieldClass, 'listControllerSqlBuilderWith')){ // Присоединение связей
				$sqlBuilderSelect = $AirTableFieldClass::listControllerSqlBuilderWith($this, $sqlBuilderSelect, $table, $k, $v);
			}
		}
		
		// 3 // order
		$paramOrder = BaseUtility::_POSTuc('form2Apply','form2Order');
		foreach($paramOrder as $k => $v){
			if($v != null){
				if($v == 'ASC' || $v == 'DESC') {
					$sqlBuilderSelect = $sqlBuilderSelect->orderBy($table.'.'.$k,$v);
					
				} elseif ($v == 'RAND') {
					$sqlBuilderSelect = $sqlBuilderSelect->inRandomOrder($table.'.'.$k);
				}
			}
		}
		
		// 4 // limit
		$page = BaseUtility::_POSTuc('form0Apply','form0Page',1);
		$lim = BaseUtility::_POSTuc('form3Apply','form3Limit',30); // 30
		if($lim>=0){
			if($page == 1){
				$sqlBuilderSelect = $sqlBuilderSelect->limit($lim)->offset(0);
			} else {
				$sqlBuilderSelect = $sqlBuilderSelect->limit($lim)->offset($lim*($page-1));
			}
		}
		
			// 4 // limit // rand
			if($lim == -2){
				#$sqlBuilderSelect = $sqlBuilderSelect->inRandomOrder();
				#$sqlBuilderSelect = $sqlBuilderSelect->limit(1)->offset(0);
				#$SqlPageGo = 1;
				#$page = 1;
			}
		
		// Выполняем запрос
		$rows = $sqlBuilderSelect->get()->toArray();
		
		// Собираем контент
		$content = '
		<table class="table table-bordered table-hover" style="width: 100%; background: #fff;">
		<tbody>
			'.$this->rowContentShow($rows, $table).'
		</tbody>
		</table>';
		
		// H1-ссылка
		$getArgs = [
			'paramExt' => BaseUtility::_GETuc('paramExt',$this->extNameDir),
			'paramSubDomain' => BaseUtility::_GETuc('paramSubDomain','Root'),
			'paramClass' => BaseUtility::_GETuc('paramClass',''),
			#'idList' => $idList
		];
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
		$h1Content = "<a href='".$url."'>".BaseUtility::getClassAnnotationValueNew($paramClass,'AirTable\Label')."</a>";
		
        $assignedValues = [
			'modelName' => $paramClass,
			'h1Content' => $h1Content,
			'recordContent' => $content
        ];
        $this->view->assignMultiple($assignedValues);
	}
	
	public function rowContentShow($rows, $table=''){
		// Собираем контент
		$content = '';
		$jCount = 1;
		
		// Выводим строки
		foreach($rows as $k => $v){
			
			// Если даннаый ID не выбран, пропускаем
			if(!in_array($v['uid'],explode(',',$GLOBALS['_GET']['idList']))){
				continue;
			}
		
			$content .= '<tr>';
			$content .= '<td colspan="2" align="center" style="background: #ffa947;"><h2 style="margin: 10px;"><b>Запись №'.$v['uid'].'</b></h2></td>';
			$content .= '</tr>';
			$columns = $GLOBALS['TCA'][$table]['columns'];
			foreach($columns as $k2 => $v2){
				$content .= '<tr>';
			
				// Оставляем только выбранные колонки
				if(!in_array($k2, BaseUtility::_POSTuc('form3Apply','form3Columns')) 
					&& BaseUtility::_POSTuc('form3Apply','form3Columns','all') != 'all'){
					continue;
				}
				
				$AirTableFieldClass = $v2['AirTable.Class'];
				if(method_exists($AirTableFieldClass, 'listControllerHtmlTh')){
					// Название поля
					$debugStr = '';
					if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
						$debugStr = '<br /><code style="color: #b4b2b2;">'.BaseUtility::getClassNameWithoutNamespace($v2['AirTable.Class']).'.'.$k2.'</code>';
					}
					$content .= '
					<td width="50%" align="right">'
						.$GLOBALS['LANG']->sL($v2['label'])
						.$debugStr.'
					</td>';
					$jCount++;
				} else {
					// Название поля
					$debugStr = '';
					if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
						$debugStr = '<br /><code style="color: red;">'.$k2.'</code>';
					}
					$content .= '
					<td width="50%" align="right" style="color: red;">'
						.htmlspecialchars($GLOBALS['LANG']->sL($v2['label']))
						.$debugStr.'
					</td>';
					$jCount++;
				}
				
				// [RType] ->->-> Проверяем есть ли эта колонка в структуре?
				$fieldsInType = BaseUtility::getAllColumnsFromType($table, $v[$GLOBALS['TCA'][$table]['ctrl']['type']]);
				if(isset($GLOBALS['TCA'][$table]['ctrl']['type']) 
							&& isset($GLOBALS['TCA'][$table]['columns'][$GLOBALS['TCA'][$table]['ctrl']['type']]) 
								&& !in_array($k2,$fieldsInType) 
									&& $k2 != 'uid' 
										&& $k2 != 'pid'
											&& $table != 'sys_file' // Исключение (TCA оформленна не полностью)
				){
					$content .= '<td style="background: #ededed; color: #b4b2b2; vertical-align: top;" nowrap><i>Не существует</i></td>';
				} else {
					$AirTableFieldClass = $v2['AirTable.Class'];
					if(method_exists($AirTableFieldClass, 'listControllerHtmlTd')){
						$editIcon = $this->iconFactory->getIcon('actions-page-open', Icon::SIZE_SMALL).' ';
						$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
							'record_edit', [
								'columnsOnly' => $k2,
								'edit['.$table.']['.$v['uid'].']' => 'edit',
								'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
							]
						);
						$content .= $AirTableFieldClass::listControllerHtmlTd($this, $table, $k2, $v2, $v, $backendLink, $editIcon);
					} else {
						$content .= '<td style="vertical-align: top;" nowrap><span style="color: red;">'.nl2br(htmlspecialchars($v[$k2])).'</span></td>';
					}
				}
				$content .= '</tr>';
			}
		}
		
		// Если пусто
		if(count($rows) == 0){
			$icon = $this->iconFactory->getIcon('apps-pagetree-page-default', Icon::SIZE_DEFAULT, 'overlay-missing');
			$content .= '<tr>';
			$content .= '<td style="border-top: none;" colspan="'.($jCount+10).'" align="left">
							<div style="margin: 50px;">
								<h3>'.$icon.' Записи не найдены!</h3>
							</div>
						</td>';
			$content .= '</tr>';
		} else {
			$content = $content;
		}
		
		// Нижняя часть ("thFooter")
		/*
		$content .= '<tr class="active">';
		$content .= '<th></th>';
		$content .= '<th></th>';
		
		$columns = [];
		$columns['uid']['label'] = 'ID';
		$columns['uid']['AirTableConfig'] = 'INPUT_NUMBER';
		if(@in_array("RType", $GLOBALS['TCA'][$table]['ctrl']['fieldsDefault'])){
			$columns['RType'] = $GLOBALS['TCA'][$table]['columns']['RType'];
			$columns['RType']['label'] = 'Тип записи';
			$columns['RType']['AirTableConfig'] = 'SWITCH';
		}
		$columns += $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
			if($k == 'uid'){ continue; }
			if($k != 'RType' && !in_array($k, $this->getParam($table,'tab3','columns','all')) && $this->getParam($table,'tab3','columns','all') != 'all'){
				continue;
			}
				
			$AirTableConfig = $v['AirTableConfig'];
			$class = 'ColumnLibraryConfiguration_'.$AirTableConfig;
			if(class_exists($class) && method_exists($class, 'thFooter')){
				$obj = new $class;
				$content .= $obj->thFooter($this, $table, $k, $v);
			} else {
				$content .= '<th></th>';
			}
		}
		$content .= '</tr>';
		*/
			
		return $content;
	}
	
	public function TxDataPropsDefaultCheck($table, $column, $dataType)
	{
		// Cache
		if(isset($GLOBALS['Litovchenko.AirTable.VarCache.TxDataPropsDefaultCheck'][$table][$column][$dataType])){
			return $GLOBALS['Litovchenko.AirTable.VarCache.TxDataPropsDefaultCheck'][$table][$column][$dataType];
		}
		
		// TxData
		$paramClass = 'Litovchenko\AirTable\Domain\Model\Content\Data';
		if(!empty($dataType)){
			$filter = [];
			$filter['where'] = ['uid','=',$dataType];
			$filter['orWhere'] = ['uidkey','=',$dataType];
			$filter['withoutGlobalScopes'] = true;
			$row = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('first',$filter);
			$annotationDataTypeConditionUse = BaseUtility::getClassFieldAnnotationValueNew($paramClass,$column,'AirTable\Field\DataTypeConditionUse');
			if(!empty($annotationDataTypeConditionUse)){
				$tDTCU = explode(',',$annotationDataTypeConditionUse);
				if(in_array($table,$tDTCU)){
					if($table == 'tx_data_category'){
						$props_default = explode(',',$row['props_default_cat']);
					} else {
						$props_default = explode(',',$row['props_default']);
					}
					if(!in_array($column,$props_default)){
						$GLOBALS['Litovchenko.AirTable.VarCache.TxDataPropsDefaultCheck'][$table][$column][$dataType] = 1;
						return true;
					}
				}
				#print "<pre>";
				#print_r($row['props_default']);
				#exit();
			}
		}
		
		$GLOBALS['Litovchenko.AirTable.VarCache.TxDataPropsDefaultCheck'][$table][$column][$dataType] = 0;
		return false;
	}
	
    /**
     * This method is used throughout the TYPO3 Backend to show icons for a DB record
     *
     * @param string $table The TCA table name
     * @param array $row The DB record of the TCA table
     * @param string $size "large" "small" or "default", see the constants of the Icon class
     * @return Icon
     */
    public function getIconForRecord($table, array $row, $size = Icon::SIZE_DEFAULT)
    {
        $iconIdentifier = $this->iconFactory->mapRecordTypeToIconIdentifier($table, $row);
        return $this->iconFactory->getIcon($iconIdentifier, $size, 'overlay-deleted');
    }

}
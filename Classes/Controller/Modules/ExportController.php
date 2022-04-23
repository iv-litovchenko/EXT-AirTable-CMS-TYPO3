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

// Принудительно отменяем добавление условия
// define("restrictionDeletedIgnore", true);

class ExportController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Экспорт записей',
		'description' 	=> 'Экспорт данных в формате XLS, CSV',
		'access' 		=> 'admin',
		'section'		=> 'content',
		'position'		=> '30'
	];
	
    /**
     * TsConfig configuration
     *
     * @var array
     */
    protected $tsConfiguration = [];

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
    protected $iconFactory;

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

        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
		$pageRenderer->addJsFile('EXT:air_table/Resources/Public/Js/List.js');
		$pageRenderer->addCssFile('EXT:air_table/Resources/Public/Css/List.css');
		
		$this->createMenu();
		$this->createButtons();
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
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();
		
        // (+) Кнопка обновить
        
        $refreshButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle('Обновить')
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }


    /**
     * Main action for administration
     */
    public function indexAction()
    {
		// 2
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
		
		#print "<pre>";
		#print_r($classList);
		#exit();
		
		// Чтение расширений
		$content = '';
		foreach($classList as $ext => $subDomains){
			$content .= '
				<h2 style="border-bottom: #eeeeee 3px solid; padding: 5px; font-size: 18px;">'.$this->iconFactory->getIcon('actions-database', Icon::SIZE_SMALL).' EXT:'.$ext.'</h2>
				<ul class="list-tree text-monospace">
			';
			
			// Чтение подпапок...
			foreach($subDomains as $subDomain => $tables){
			
				// Подпапка...
				if($subDomain != 'ZZZ_Root'){
					$extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext);
					if($subDomain == 'Ext'){
						$titleSubDomain = 'Расширение моделей';
					}elseif(file_exists($extRelPath.'Classes/Domain/Model/'.$subDomain.'/_.txt')){
						$titleSubDomain = file_get_contents($extRelPath.'Classes/Domain/Model/'.$subDomain.'/_.txt');
					} else {
						$titleSubDomain = '';
					}
					$content .= '
					<li>
						<span class="list-tree-group">
						<span class="list-tree-label" style="white-space: nowrap;">
						
									<div class="panel-group panel-group-rst" role="tablist" aria-multiselectable="true" style="margin-bottom: -1px;">
										<div class="panel panel-default panel-version t3js-version-changes" data-version="9.5.x">
											<div class="panel-heading" role="tab" id="heading-0" style="padding: 8px; background-color: #eee; color: #000;">
												<h2 class="panel-title">
													<a href="#version-'.$ext.'-'.$subDomain.'" class="collapsed" data-bs-toggle="collapse" data-toggle="collapse" aria-expanded="true">
														<span class="caret" style="margin-top: -3px; margin-left: 0;"></span>
														'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageSubdomain', Icon::SIZE_SMALL).'
														'.$titleSubDomain.'
														
													</a>
												</h2>
											</div>
											<div class="panel-collapse collapse" id="version-'.$ext.'-'.$subDomain.'" role="tabpanel" data-group-version="9.5.x" aria-expanded="true">
												<div class="panel-body t3js-changelog-list" role="tablist" aria-multiselectable="false" style="padding: 0px; padding-left: 8px;">
													<div style="overflow: hidden; border: #cccccc 0px solid;">
														<div style="margin: 0px; margin-top: -1px;">
														<ul class="list-tree text-monospace">
															';
				
				// Дубликат кода...
				foreach($tables as $kTable => $vTable){
					
					// Если категория...
					if(BaseUtility::isModelCategoryForAnotherModel($vTable)){
						continue;
					}
					
					// Access
					$accessOverlay = null;
					if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($vTable)) == false){
						$url = '#';
						$accessOverlay = 'overlay-locked';
					}
					// Db count
					$dbCount = $vTable::count();
					
					// Url
					$getArgs = [
						'paramExt' => $paramExt,
						'paramSubDomain' => $paramSubDomain,
						'paramClass' => $vTable, // table
					];
					$uriBuilder = $this->controllerContext->getUriBuilder();
					$url = $uriBuilder->setArguments($getArgs)->uriFor('step2', []);
					
					// Если категория
					if(BaseUtility::isModelSupportRowCategory($vTable) || BaseUtility::isModelSupportRowsCategories($vTable)){
						
						// Access
						$vTable2 = $vTable."Category";
						$accessOverlay2 = null;
						if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($vTable2)) == false){
							$url2 = '#';
							$accessOverlay2 = 'overlay-locked';
						}
						// Db count
						$dbCount2 = $vTable2::count();
						
						// Url
						$getArgs = [
							'paramExt' => $paramExt,
							'paramSubDomain' => $paramSubDomain,
							'paramClass' => $vTable2, // table
						];
						$uriBuilder = $this->controllerContext->getUriBuilder();
						$url2 = $uriBuilder->setArguments($getArgs)->uriFor('step2', []);
						
						$content .= '
						<li>
							<span class="list-tree-group">
							<span class="list-tree-label" style="white-space: nowrap;">
							<table width="100%" class="table table-bordered table-hover" style="margin-bottom: -1px; background: white !important;">
								<tr>
									<td width="50%">
										<a href="'.$url.'" class="" style="margin-bottom: 2px;">
											'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableRecord', Icon::SIZE_SMALL, $accessOverlay).'
											'.BaseUtility::getClassAnnotationValueNew($vTable,'AirTable\Label').'
										</a>
										(<a href="'.$url2.'" class="" style="margin-bottom: 2px;">'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableCategoryRecord', Icon::SIZE_SMALL, $accessOverlay).' Категории</a>)
									</td>
									<td>
										<!--<code>'.$vTable.'</code><br />-->
										<code>'.$vTable.'</code>
									</td>
									<td width="15%" align="right">
										<!--<span>Записей: '.intval($dbCount).' шт.</span><br />-->
										<span>Записей: '.intval($dbCount).', '.intval($dbCount2).' шт.</span>
									</td>
								</tr>
							</table>
							</span>
							</span>
						</li>';
					} else {
						$icon = $this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableRecord', Icon::SIZE_SMALL, $accessOverlay);
						$content .= '
						<li>
							<span class="list-tree-group">
							<span class="list-tree-label" style="white-space: nowrap;">
							<table width="100%" class="table table-bordered table-hover" style="margin-bottom: -1px; background: white !important;">
								<tr>
									<td width="50%">
										<a href="'.$url.'" class="" style="margin-bottom: 2px;">
											'.$icon.'
											'.BaseUtility::getClassAnnotationValueNew($vTable,'AirTable\Label').'
										</a>
									</td>
									<td><code>'.$vTable.'</code></td>
									<td width="15%" align="right"><span>Записей: '.intval($dbCount).' шт.</span></td>
								</tr>
							</table>
							</span>
							</span>
						</li>';
					}
				}
															
					$content .= '
														</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
							
						</span>
						</span>
					</li>
						<!--<ul class="list-tree text-monospace">-->';
				}
			
				if($subDomain == 'ZZZ_Root'){
				foreach($tables as $kTable => $vTable){
					
					// Если категория...
					if(BaseUtility::isModelCategoryForAnotherModel($vTable)){
						continue;
					}
					
					// Access
					$accessOverlay = null;
					if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($vTable)) == false){
						$url = '#';
						$accessOverlay = 'overlay-locked';
					}
					// Db count
					$dbCount = $vTable::count();
					
					// Url
					$getArgs = [
						'paramExt' => $paramExt,
						'paramSubDomain' => $paramSubDomain,
						'paramClass' => $vTable, // table
					];
					$uriBuilder = $this->controllerContext->getUriBuilder();
					$url = $uriBuilder->setArguments($getArgs)->uriFor('step2', []);
					
					// Если категория
					if(BaseUtility::isModelSupportRowCategory($vTable) || BaseUtility::isModelSupportRowsCategories($vTable)){
						
						// Access
						$vTable2 = $vTable."Category";
						$accessOverlay2 = null;
						if(BaseUtility::BeUserAccessTableSelect(BaseUtility::getTableNameFromClass($vTable2)) == false){
							$url2 = '#';
							$accessOverlay2 = 'overlay-locked';
						}
						// Db count
						$dbCount2 = $vTable2::count();
						
						// Url
						$getArgs = [
							'paramExt' => $paramExt,
							'paramSubDomain' => $paramSubDomain,
							'paramClass' => $vTable2, // table
						];
						$uriBuilder = $this->controllerContext->getUriBuilder();
						$url2 = $uriBuilder->setArguments($getArgs)->uriFor('step2', []);
						
						$content .= '
						<li>
							<span class="list-tree-group">
							<span class="list-tree-label" style="white-space: nowrap;">
							<table width="100%" class="table table-bordered table-hover" style="margin-bottom: -1px; background: white !important;">
								<tr>
									<td width="50%">
										<a href="'.$url.'" class="" style="margin-bottom: 2px;">
											'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableRecord', Icon::SIZE_SMALL, $accessOverlay).'
											'.BaseUtility::getClassAnnotationValueNew($vTable,'AirTable\Label').'
										</a>
										(<a href="'.$url2.'" class="" style="margin-bottom: 2px;">'.$this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableCategoryRecord', Icon::SIZE_SMALL, $accessOverlay).' Категории</a>)
									</td>
									<td>
										<!--<code>'.$vTable.'</code><br />-->
										<code>'.$vTable.'</code>
									</td>
									<td width="15%" align="right">
										<!--<span>Записей: '.intval($dbCount).' шт.</span><br />-->
										<span>Записей: '.intval($dbCount).', '.intval($dbCount2).' шт.</span>
									</td>
								</tr>
							</table>
							</span>
							</span>
						</li>';
					} else {
						$icon = $this->iconFactory->getIcon('extensions-tx-airtable-resources-public-icons-StorageTableRecord', Icon::SIZE_SMALL, $accessOverlay);
						$content .= '
						<li>
							<span class="list-tree-group">
							<span class="list-tree-label" style="white-space: nowrap;">
							<table width="100%" class="table table-bordered table-hover" style="margin-bottom: -1px; background: white !important;">
								<tr>
									<td width="50%">
										<a href="'.$url.'" class="" style="margin-bottom: 2px;">
											'.$icon.'
											'.BaseUtility::getClassAnnotationValueNew($vTable,'AirTable\Label').'
										</a>
									</td>
									<td><code>'.$vTable.'</code></td>
									<td width="15%" align="right"><span>Записей: '.intval($dbCount).' шт.</span></td>
								</tr>
							</table>
							</span>
							</span>
						</li>';
					}
				}
				}
			
				// Подпапка...
				if($subDomain != 'ZZZ_Root'){
					#$content .= '
					#	</ul>
					#	</span>
					#	</span>
					#</li>';
				}
				
			}
			
			$content .= '</ul>';
		}
		
        $assignedValues = [
            'content' => $content
        ];
        $this->view->assignMultiple($assignedValues);
	}

    /**
     * Main action for administration
     */
    public function step2Action()
    {
		$content = '';
		$paramExt = BaseUtility::_GETuc('paramExt','Root');
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
		
		// Создаем модель
		// $sqlBuilderSelect = $paramClass::getModel();
		$sqlBuilderCount = $paramClass::getModel();
		$table = BaseUtility::getTableNameFromClass($paramClass);
		
		// Колонки
		// tab 3 (columns)
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($columns as $k => $v){
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
			$AirTableFieldClass = $v['AirTable.Class'];
			if(method_exists($AirTableFieldClass, 'exportControllerRow') && $AirTableFieldClass::EXPORT == false){
				$disabled = 'disabled';
			}
			if(in_array($k,BaseUtility::_POSTuc('form3Apply','form3Columns'))){ // || BaseUtility::_POSTuc('form3Apply','form3Columns','all') == 'all'
				$checked = 'checked';
			}
			$noConfig = '';
			if(!$v['AirTable.Class']){
				$disabled = 'disabled';
				$noConfig = 'color: red';
			}
			$debugStr = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$debugStr = '<br /><code style="color: #b4b2b2;">'.$v['AirTable.Class'].'</code>';
			}
			$content .= "
			<tr class='filteTagExport'>
				<td align='right'>
					<span style='".$noConfig."'>
						".htmlspecialchars($GLOBALS['LANG']->sL($v['label']))."
						".$debugStr."
					</span>
				</td>
				<td>
					<label class='btn btn-default btn-sm ".$disabled."' style='margin: 0; margin-bottom: 0px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;'>
						<input type='checkbox' name='columns[]' class='btn btn-default btn-sm' style='margin-top: 0px;' value='".$k."' ".$disabled." ".$checked."> 
						Включить в список
					</label>
				</label>
				</td>
				<td>
					<span style='".$noConfig."'>".$k."</span>
				</td>
			</tr>
			 ";
		}
		
        $assignedValues = [
			'model' => $paramClass,
			'table' => $table,
			'tableCount' => intval($sqlBuilderCount->count()),
            'content' => $content,
			'recycler' => BaseUtility::_GETuc('restrictionDeleted',0,'ListController') // Значение корзины
        ];
        $this->view->assignMultiple($assignedValues);
    }
	
    /**
     * Main action for administration
     */
    public function step2ProcessAction()
    {
		// Параметры _GET
		$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
		
		// Параметры _POST
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		
		// Создаем модель
		$sqlBuilderSelect = $_POST['model']::getModel();
		$sqlBuilderCount = $_POST['model']::getModel();
		$table = BaseUtility::getTableNameFromClass($_POST['model']);
		
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
			if(in_array('filter',$_POST['params'])){
				if(method_exists($AirTableFieldClass, 'listControllerSqlBuilder')){ // Фильтр
					$sqlBuilderSelect = $AirTableFieldClass::listControllerSqlBuilder($this, $sqlBuilderSelect, $table, $k, $v);
				}
			}
			if(method_exists($AirTableFieldClass, 'listControllerSqlBuilderWith')){ // Присоединение связей
				$sqlBuilderSelect = $AirTableFieldClass::listControllerSqlBuilderWith($this, $sqlBuilderSelect, $table, $k, $v);
			}
		}
		
		// 3 // order
		if(in_array('order',$_POST['params'])){
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
		}
		
		// 4 // limit
		if(in_array('limit',$_POST['params'])){
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
		}
		
		$rows = $sqlBuilderSelect->get()->toArray();
		$rowsCountDisplay = count($rows); // Кол-во выбранных строк 
		#$rowsCountAllInDb = $sqlBuilderCount->count(); // Кол-во записей в базе всего
		
		// Делаем выгрузку файла
		if($_POST['format'] == ".xlsx"){
			$pathFolder = '/fileadmin/_xlsx-export_/';
			$fileExportName = $table.'-'.date("d.m.Y-H.i.s").'.xlsx';
		
		} elseif($_POST['format'] == ".csv"){
			$pathFolder = '/fileadmin/_csv-export_/';
			$fileExportName = $table.'-'.date("d.m.Y-H.i.s").'.csv';
			
		}
		
		$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
		\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($pathSite.$pathFolder);
		
		(new \Rap2hpoutre\FastExcel\FastExcel($rows))->export($pathSite.$pathFolder.$fileExportName, function ($line) {
			$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
			$table = BaseUtility::getTableNameFromClass($_POST['model']);
			$rowData = [];
			
			$columns = $GLOBALS['TCA'][$table]['columns'];
			foreach($columns as $k => $v){
				// Pid Пропускаем, если записи всегда в корне
				if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k == 'pid'){
					continue;
				}
			
				if(in_array($k,$_POST['columns']) || $k == 'uid' || $k == 'pid' || $k == 'RType'){
					$AirTableFieldClass = $v['AirTable.Class'];
					if(method_exists($AirTableFieldClass, 'exportControllerRow') && $AirTableFieldClass::EXPORT == true){
						$AirTableFieldClass::exportControllerRow($rowData, $_POST['model'], $table, $k, $v, $line);
					}
				}
			}
			
			return $rowData;
		});
		
		$getArgs = [
			'pathFolder' => $pathFolder,
			'format' => $_POST['format'],
			'file' => $fileExportName,
			'count' => count($rows),
		];
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$redirectUrl = $uriBuilder->setArguments($getArgs)->uriFor('step3', []);
		\TYPO3\CMS\Core\Utility\HttpUtility::redirect($redirectUrl);
	}

    /**
     * Main action for administration
     */
    public function step3Action()
    {
		$_GET = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
		
		$fileContent = '';
		if($_GET['format'] == '.csv'){
			$fContent = file_get_contents($pathSite.$_GET['pathFolder'].$_GET['file']);
		}
		
        $assignedValues = [
			'fileContent' => $fContent,
			'pathFolder' => $_GET['pathFolder'],
			'file' => $_GET['file'],
			'count' => intval($_GET['count'])
        ];
        $this->view->assignMultiple($assignedValues);
	}

}

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
// define("withoutGlobalScopes", true);

class ImportController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Импорт записей',
		'description' 	=> 'Импорт данных в формате XLS, CSV',
		'access' 		=> 'admin',
		'section'		=> 'content',
		'position'		=> '40'
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
		$paramExt = BaseUtility::_GETuc('paramExt','Root');
		$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		$paramClass = BaseUtility::_GETuc('paramClass','');
		$table = BaseUtility::getTableNameFromClass($paramClass);
		
		// Параметры  _POST
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();

		// Проверяем загружен ли файл
		$contentError = '';
		if($GLOBALS['_POST']['format'] == ".xlsx"){
			$format = '.xlsx';
			if(is_uploaded_file($GLOBALS['_FILES']["filename"]["tmp_name"])){
				if(end(explode('.',$GLOBALS['_FILES']['filename']['name']))!='xlsx'){
					$contentError = 'К загрузке допускаются файлы только с расширением ".xlsx".';
					
				} else {
					// Если файл загружен успешно, перемещаем его
					// из временной директории в конечную
					$fileImportName = $table.'-'.date("d.m.Y-H.i.s").'.xlsx';
					$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
					$pathFolder = '/fileadmin/_xlsx-import_/';
					\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($pathSite.$pathFolder);
					
					if(move_uploaded_file($GLOBALS['_FILES']["filename"]["tmp_name"], $pathSite.$pathFolder.$fileImportName)){
						$getArgs = [
							'model' => $_POST['model'],
							'table' => $_POST['table'],
							'format' => '.xlsx',
							'file' => $fileImportName
						];
						$uriBuilder = $this->controllerContext->getUriBuilder();
						$redirectUrl = $uriBuilder->setArguments($getArgs)->uriFor('step3', []);
						\TYPO3\CMS\Core\Utility\HttpUtility::redirect($redirectUrl);
					 }else {
						 $contentError = 'Не удалось загрузить файл.';
					 }
				}
			} elseif(!empty($GLOBALS['_POST'])){
				 $contentError = 'Не выбран файл для загрузки на сервер.';
			}
		
		} elseif($GLOBALS['_POST']['format'] == ".csv"){
			$format = '.csv';
			if(!empty($_POST)){
				if(empty($_POST['csv_content'])){
					$contentError = 'Поле с данными для импорта не заполнено.';
					
				}elseif(!empty($_POST['csv_content'])){
					$fileImportName = $table.'-'.date("d.m.Y-H.i.s").'.csv';
					$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
					$pathFolder = '/fileadmin/_csv-import_/';
					\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($pathSite.$pathFolder);
					BaseUtility::fileWrite($pathSite.$pathFolder.$fileImportName,$_POST['csv_content']);
					$getArgs = [
						'model' => $_POST['model'],
						'table' => $_POST['table'],
						'format' => '.csv',
						'file' => $fileImportName
					];
					$uriBuilder = $this->controllerContext->getUriBuilder();
					$redirectUrl = $uriBuilder->setArguments($getArgs)->uriFor('step3', []);
					\TYPO3\CMS\Core\Utility\HttpUtility::redirect($redirectUrl);
				}
			}
		}
		
        $assignedValues = [
			'model' => $paramClass,
			'table' => $table,
            'contentError' => $contentError,
			'format' => $format,
			'recycler' => BaseUtility::_GETuc('restrictionDeleted',0,'ListController') // Значение корзины
        ];
        $this->view->assignMultiple($assignedValues);
    }
	
    /**
     * Main action for administration
     */
    public function step3Action()
    {	
		#$paramExt = BaseUtility::_GETuc('paramExt','Root');
		#$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		#$paramClass = BaseUtility::_GETuc('paramClass','');
		
		// Параметры _GET, _POST
		$_GET = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		
		$fileImportName = $_GET['file'];
		$model = $_GET['model'];
		$table = $_GET['table'];
		$format = $_GET['format'];
		
		// Список дублирующих ошибок
		$contentError = [];
		
		// Работу с таблицами которых нет в TCA пишем как ошибка
		if(!isset($GLOBALS['TCA'][$table])){
			$contentError[] = "Конфигурация таблицы не определена";
		
		// Не надена таблица
		#} elseif(!\Illuminate\Support\Facades\Schema::hasTable($table)){
		#	$contentError[] = "Таблица не существует в БД";
		
		} else {
			
			$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
			if($format == '.xlsx'){
				$pathFolder = '/fileadmin/_xlsx-import_/';
				
			} elseif($format == '.csv'){
				$pathFolder = '/fileadmin/_csv-import_/';
			}
			
			// Не наден файл
			if(@!file_exists($pathSite.$pathFolder.$fileImportName)){
				$contentError[] = "На сервере не найден загруженный файл 
				<code>".$pathFolder.$fileImportName."</code> с данными для импорта";
			
			} else {
				
				// Получаем данные импорта
				$collection = (new \Rap2hpoutre\FastExcel\FastExcel)->import($pathSite.$pathFolder.$fileImportName);
				
				// Отстутсвуют данные для импорта!
				#if($collection)==0){
				#	$contentError[] = 'В файле отсутствуют данные для импорта';
				
				#} else {
					
					// Не найден столбец "uid"
					if(!isset($collection[0]['uid'])){
						$contentError[] = 'В файле не найден столбец "uid", необходимый для импорта данных';
					
					// Не найден столбец "pid"
					// Pid Пропускаем, если записи всегда в корне
					} elseif(!isset($collection[0]['pid']) && $GLOBALS['TCA'][$table]['ctrl']['rootLevel'] != 1){
						$contentError[] = 'В файле не найден столбец "pid", необходимый для импорта данных';
					
					// Разрешено сопоставление полей
					} else {
						
						// Колонки
						// tab 3 (columns)
						$columns = $GLOBALS['TCA'][$table]['columns'];
						foreach($columns as $k => $v){
							// Pid Пропускаем, если записи всегда в корне
							if($GLOBALS['TCA'][$table]['ctrl']['rootLevel'] == 1 && $k == 'pid'){
								continue;
							}
							
							$status = '';
							$checked = '';
							$disabled = '';
							$AirTableFieldClass = $v['AirTable.Class'];
							if(method_exists($AirTableFieldClass, 'importControllerRow') && $AirTableFieldClass::IMPORT == false){
								$status = 'warning';
								$checked = '';
								$disabled = 'disabled';
							} else {
								if(isset($collection[0][$k])){
									$status = 'success';
									if($k == 'uid' || $k == 'pid'){
										$checked = 'checked';
										$disabled = 'disabled';
									} else {
										$checked = '';
										$disabled = '';
									}
								} else {
									$disabled = 'disabled';
									$status = 'warning';
								}
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
							<tr class='".$status." filteTagImport'>
								<td align='right'>
									<span style='".$noConfig."'>
										".htmlspecialchars($GLOBALS['LANG']->sL($v['label']))."
										".$debugStr."
									</span>
								</td>
								<td>
									<label class='btn btn-default btn-sm ".$disabled."' style='margin: 0; margin-bottom: 0px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;'>
										<input type='checkbox' name='columns[]' class='btn btn-default btn-sm' style='margin-top: 0px;' value='".$k."' ".$disabled." ".$checked."> 
										Включить в обработку
									</label>
								</label>
								</td>
								<td>
									<span style='".$noConfig."'>".$k."</span>
								</td>
							</tr>
							 ";
						}
						
						// Колонки которые не существуют в БД
						foreach($collection[0] as $kFile => $vFile){
							if(!isset($GLOBALS['TCA'][$table]['columns'][$kFile])){
								$content .= "
								<tr class='danger filteTagImport'>
									<td align='right'>
										<b>Поле отсутствует в базе</b>
									</td>
									<td>
										<label class='btn btn-default btn-sm' style='margin: 0; margin-bottom: 0px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;'>
											<input type='checkbox' name='columns[]' class='btn btn-default btn-sm' style='margin-top: 0px;' disabled> 
											Не поддерживается
										</label>
									</label>
									</td>
									<td>
										<span style='".$noConfig."'>".$kFile."</span>
									</td>
								</tr>
								 ";
							}
						}
						
					}
					
				#}
				
				# print "<Pre>";
				# print_r($collection);
				# $collection[0];
			}
			
		}
		
        $assignedValues = [
            'model' => $model,
            'table' => $table,
            'format' => $_POST['format'],
            'file' => $fileImportName,
            'contentError' => implode('<br />',$contentError),
            'countError' => count($contentError),
			'content' => $content,
			'countRows' => intval(count($collection))
        ];
        $this->view->assignMultiple($assignedValues);
	}
	
    /**
     * Main action for administration
     */
    public function step3ProcessAction()
    {
		// Параметры
		#$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		#$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		#$paramClass = BaseUtility::_GETuc('paramClass','');
		
		// Параметры _GET,_POST
		$_GET = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		$model = $_GET['model'];
		$table = $_GET['table'];
		$format = $_GET['format'];
		$fileImportName = $_GET['file'];
		
		// Сбрасываем поле importprocess
		// Поле понадобилось пока что бы происходило обновление записи производилось каждый раз
		DB::table($table)->update(['importprocess'=>0]);
		
		// Получаем данные импорта
		$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
		if($format == ".xlsx"){
			$pathFolder = '/fileadmin/_xlsx-import_/';
			
		} elseif($format == ".csv"){
			$pathFolder = '/fileadmin/_csv-import_/';
		}
		$collection = (new \Rap2hpoutre\FastExcel\FastExcel)->import($pathSite.$pathFolder.$fileImportName);

		// Кол-во выполненных операций
		$processLineResult = [];
		$countProcessLine = 0;
		$countEmpty = 0;
		$countInsert = 0;
		$countUpdate = 0;
		$countNoUpdate = 0; // не известный Uid
		$countErrorTransaction = 0;
		
		// Читаем строки
		$columns = $GLOBALS['TCA'][$table]['columns'];
		foreach($collection as $k => $line){
			
			// Нет данных для обновления
			if(count($line) == 0){
				$processLineResult[$k] = 4;
				$countEmpty++;
				$countProcessLine++;
				
			} else {
				DB::beginTransaction();
				try 
				{
					// Удаление записи
					if(preg_match('/^\[D\](.*)/is',$line['uid'],$match)){
						$result = $model::where('uid','=',$match[1])->delete();
						if($result > 0){
							$processLineResult[$k] = 6;
							$countDelete++;
							$countProcessLine++;
						} else {
							$processLineResult[$k] = 3;
							$countNoDelete++;
							$countProcessLine++;
						}
					
					// Создание записи
					} elseif($line['uid'] < 1){
						// Сначала создаем (из-за связей, иначе не сделаем связь, например Mto1)
						// т.к. ID-пока еще не знаем! После обновляем
						$id = $model::insertGetId(['importprocess' => 1]);
						$rowData = $this->rowData($model, $table, $line, $id);
						$result = $model::where('uid','=',$id)->update($rowData);
						
						$processLineResult[$k] = 1;
						$countInsert++;
						$countProcessLine++;
					
					// Обновление записи
					} elseif($line['uid'] > 0){
						$rowData = $this->rowData($model, $table, $line, $line['uid']);
						$result = $model::where('uid','=',$line['uid'])->update($rowData);
						if($result > 0){
							$processLineResult[$k] = 2;
							$countUpdate++;
							$countProcessLine++;
						} else {
							$processLineResult[$k] = 3;
							$countNoUpdate++;
							$countProcessLine++;
						}
					}
					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
					$processLineResult[$k] = 5;
					$countErrorTransaction++;
					$countProcessLine++;
				}
			}
		}
		
		$getArgs = [
			'model' => $model,
			'table' => $table,
            'format' => $format,
			'file' => $fileImportName,
			'countEmpty' => $countEmpty,
			'countInsert' => $countInsert, // Insert
			'countUpdate' => $countUpdate, // Update
			'countNoUpdate' => $countNoUpdate, // Update (no)
			'countDelete' => $countDelete, // Delete
			'countNoDelete' => $countNoDelete, // Delete (no)
			'countErrorTransaction' => $countErrorTransaction,
			'countProcessLine' => $countProcessLine,
			'processLineResult' => $processLineResult
		];
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$redirectUrl = $uriBuilder->setArguments($getArgs)->uriFor('step4', []);
		\TYPO3\CMS\Core\Utility\HttpUtility::redirect($redirectUrl);
	}
	
	// Собираем выдает обработанные данные
	public function rowData($model, $table, $line, $recordId){
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		$line['uid'] = $recordId; // Устанавливаем Id
		$rowData = [];
		foreach($line as $kFileField => $vFileField){
			if((isset($GLOBALS['TCA'][$table]['columns'][$kFileField]) && $kFileField != 'uid') || $kFileField == 'pid' || $kFileField == 'RType'){
				if(in_array($kFileField,$_POST['columns'])){ // При условии, что мы выбрали колонку
					$AirTableFieldClass = $GLOBALS['TCA'][$table]['columns'][$kFileField]['AirTable.Class'];
					if(method_exists($AirTableFieldClass, 'importControllerRow') && $AirTableFieldClass::IMPORT == true){
						$AirTableFieldClass::importControllerRow($rowData, $model, $table, $kFileField, $GLOBALS['TCA'][$table]['columns'][$kFileField], $line);
					}
				}
			}
		}
		$rowData['importprocess'] = 1;
		unset($rowData['uid']); // Удаляем, Id
		
		return $rowData;
	}
	
    /**
     * Main action for administration
     */
    public function step4Action()
    {
		// Параметры
		#$paramExt = BaseUtility::_GETuc('paramExt',$this->extNameDir);
		#$paramSubDomain = BaseUtility::_GETuc('paramSubDomain','Root');
		#$paramClass = BaseUtility::_GETuc('paramClass','');
		
		$model = $_GET['model'];
		$table = $_GET['table'];
		$format = $_GET['format'];
		$fileImportName = $_GET['file'];
		
		$countEmpty = $_GET['countEmpty'];
		$countInsert = $_GET['countInsert'];
		$countUpdate = $_GET['countUpdate'];
		$countNoUpdate = $_GET['countNoUpdate'];
		$countDelete = $_GET['countDelete'];
		$countNoDelete = $_GET['countNoDelete'];
		$countErrorTransaction = $_GET['countErrorTransaction'];
		$countProcessLine = $_GET['countProcessLine'];
		$processLineResult = $_GET['processLineResult'];
		
		// Получаем данные импорта
		$pathSite = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
		if($format == '.xlsx'){
			$pathFolder = '/fileadmin/_xlsx-import_/';
			
		} elseif($format == '.csv'){
			$pathFolder = '/fileadmin/_csv-import_/';
		}
		$collection = (new \Rap2hpoutre\FastExcel\FastExcel)->import($pathSite.$pathFolder.$fileImportName);
		
		$content = '';
		foreach($collection as $k => $line){
			switch($processLineResult[$k]){
				case 5: $comment = 'Транзакция - откат изменений'; break;
				case 4: $comment = 'Нет данных для обновления'; break;
				case 1: $comment = 'Выполнено - создание записи'; break;
				case 2: $comment = 'Выполнено - обновление записи'; break;
				case 6: $comment = 'Выполнено - удаление записи'; break;
				case 3: $comment = 'Проигнорировано ("uid" не найден в базе данных)'; break;
			}
			
			$content .= "<tr>
				<td width='10' style='white-space: nowrap;'>№ строки ".($k+1)."</td>
				<td width='10' style='white-space: nowrap;'>[ID: ".$line['uid']."]</td>
				<td nowrap>".$comment."</td>
			</tr>";
		}
		
        $assignedValues = [
            'table' => $table,
            'strCount' => intval($countProcessLine),
			'strEmpty' => intval($countEmpty),
            'strInsert' => intval($countInsert),
			'strUpdate' => intval($countUpdate),
			'strNoUpdate' => intval($countNoUpdate),
			'strDelete' => intval($countDelete),
			'strNoDelete' => intval($countNoDelete),
			'strErrorTransaction' => intval($countErrorTransaction),
			'content' => $content
        ];
        $this->view->assignMultiple($assignedValues);
	}

}

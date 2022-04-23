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

class ElementsController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Элементы системы',
		'description' 	=> 'Просмотр зарегестрированных элементов в системе (шаблонов страниц, шаблонов элементов содержимого, моделей и других элементов)',
		'access' 		=> 'admin',
		'section'		=> 'content',
		'position'		=> '50'
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

        // Refresh
        
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
		// Собираем конструктор
		$tabActive = 'Extension';
		$entity = \Litovchenko\AirTable\Utility\BaseUtility::$entityAnnotations;
		
		// Список расширений
		$extList = [];
		$extListPsr4 = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			if(basename($filename) == 'air_table'){
				continue;
			}
			// Смотрим EM_CONF.php должно быть psr4
			$_EXTKEY = basename($filename);
			$ext_conf = $filename.'/ext_emconf.php';
			if(file_exists($ext_conf)){
				require($ext_conf);
				$em_conf_ar = $EM_CONF[$_EXTKEY];
				if($em_conf_ar['constructor'] == false){
					continue;
				}
				if(isset($em_conf_ar['autoload']['psr-4'])){
					$namespace = key($em_conf_ar['autoload']['psr-4']);
					$namespace = explode('\\',$namespace);
					// .'.'.$namespace[0].'.'.$namespace[1]
					if(!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded(basename($filename))){
						$extList[basename($filename)] = "EXT:".basename($filename).' // not loaded';
					} else {
						$extList[basename($filename)] = "EXT:".basename($filename);
					}
					$extListPsr4[basename($filename)][0] = $namespace[0]; 
					$extListPsr4[basename($filename)][1] = $namespace[1];
				}
			}
		}
		
		// if(\TYPO3\CMS\Core\Utility\GeneralUtility::_POST()) {
		$successfulList = [];
        if(\TYPO3\CMS\Core\Utility\GeneralUtility::_POST()) {
            $postArgs = end($this->request->getArguments());
			$rules = [];
			foreach($entity[$postArgs['_type']]['_constuctor']['formelements'] as $k => $v){
				$rules[$k] = ['required' => 1];
			}
            $validator = \Litovchenko\AirTable\Validation\Validator::validator($postArgs, $rules);
			if ($validator->fails()) {
				$this->view->assign('form_'.$postArgs['_type'], $postArgs);
				$this->view->assign('form_'.$postArgs['_type'].'Errors', $validator->errors()->toArray());
			} else {
				
				// Получаем информацию о путях
				$airTablePath = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/air_table/Resources/Private/Constructor/'.$postArgs['_type'].'/'; // typo3conf/ext path
				$tempContent = file_get_contents($airTablePath.'Paths.txt');
				$tempContent = explode(chr(10),$tempContent);
				
				if($postArgs['_type'] == 'Extension'){
					$keyExt = $postArgs['extkey'];
					$ext_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'.$keyExt.'/'; // typo3conf/ext path
				} else {
					$keyExt = $postArgs['extkey'];
					$ext_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'.$keyExt.'/'; // typo3conf/ext path
					$postArgs['namespace'] = implode("\\",$extListPsr4[$keyExt]);
					
					// BackendModelExtending
					if($postArgs['_type'] == 'BackendModelExtending'){
						$postArgs['key'] = end(explode('\\',$postArgs['basemodel']));
					}
				}
				if(!file_exists($ext_path)) {
					mkdir($ext_path);
				}
				foreach($tempContent as $k => $v){
					// Директрории 
					if(!strstr($v,'.')){
						$path = $this->replace($ext_path.trim($v), $postArgs);
						if(!file_exists($path)) {
							mkdir($path);
						}
					}
					// Копирование файлов
					if(strstr($v,'.')){
						$arex = explode(" ", $v);
						$file1 = $airTablePath.trim($arex[0]);
						$file2 = $ext_path.trim($arex[1]);
						$path = $this->replace($file2, $postArgs);
						$readPath = 'EXT:'.$keyExt.'/'.$this->replace(trim($arex[1]), $postArgs);
						if(!file_exists($path)) {
							copy($file1, $path);
							file_put_contents($path,$this->replace(file_get_contents($path), $postArgs, $readPath));
							$successfulList[] = '[ok] ' . $readPath;
						} else {
							$successfulList[] = '[cancel] ' . $readPath .' -> файл существует';
						}
					}
				}
				
				$temp = [];
				$temp['_type'] = $postArgs['_type'];
				$temp['extkey'] = $postArgs['extkey'];
				$this->view->assign('form_'.$postArgs['_type'], $temp);
				$this->view->assign('successful',true);
				$this->view->assign('successfulList',$successfulList);
				
				#mkdir($ext_path.'Classes/');
				#mkdir($ext_path.'Classes/Controller/');
				#mkdir($ext_path.'Classes/Controller/Pages/');
			}
			$tabActive = $postArgs['_type'];
		}
		
        $assignedValues = [
			'entity' => $entity,
			'active' => $tabActive,
			'extensions' => $extList,
			'content_Ext' => $this->getContent($extListPsr4)
        ];
        $this->view->assignMultiple($assignedValues);
    }
	
	protected function replace($str, $replace, $path = null){
		$temp = explode('\\',$replace['namespace']);
		$replace['namespace_1'] = $temp[0];
		$replace['namespace_2'] = $temp[1];
		$replace['namespace_1.strtolower'] = strtolower($temp[0]);
		$replace['namespace_2.strtolower'] = strtolower($temp[1]);
		$replace['extkey2'] = str_replace('_','',$replace['extkey']);
		$replace['lcfirst_key'] = lcfirst($replace['key']);
		// $replace['signature'] = '###NAMESPACE_1###\###NAMESPACE_2###\Controller\Pages\###KEY###Controller';
		switch($replace['_type']){
			case 'FrontendPage': 					$subClass = 'Pages'; 						break;
			case 'FrontendContentElement': 			$subClass = 'PagesElements\Elements'; 		break;
			case 'FrontendContentGridelement':		$subClass = 'PagesElements\Gridelements'; 	break;
			case 'FrontendContentPlugin':			$subClass = 'PagesElements\Plugins'; 		break;
		}
		$replace['signature'] = $replace['namespace_1'].'\\'.$replace['namespace_2'].'\Controller\\'.$subClass.'\\'.$replace['key'].'Controller';
		$replace['time'] = date('d-m-Y H:i',time());
		if($path != null){
			$replace['path'] = $path;
		}
		$replace['signature'] = BaseUtility::getTableNameFromClass($replace['signature'],0,$replace['_type']);
		foreach($replace as $k => $v){
			$str = str_replace('###'.strtoupper($k).'###',trim($v),$str);
		}
		return $str;
	}

    /**
     * Get content with Tree
     *
     * @return string
     */
    protected function getContent($extListPsr4)
    {
		$extListPsr4['air_table'][0] = 'Litovchenko';
		$extListPsr4['air_table'][1] = 'AirTable';
		
		$contentExtStr = '';
		$contentExt = [];
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes as $thisIs => $listClasses) 
		{
			foreach($listClasses as $k => $class)
			{
				// Проверяем, соответствует ли название класса названию модели?
				$classNameWithoutNamespace = end(explode('\\',$class));
				$ref = new \ReflectionClass($class);
				$fpath = $ref->getFileName();
				$fname = basename($fpath);
				$fname = preg_replace("/.php$/", "", $fname);
						
				$error = '';
				if($classNameWithoutNamespace != $fname){
					$error = ' <span style="color: red;">[! Разное название класса и PHP-файла, см.: \\'.$class.']</span>';
				}
								
				$contentExt[BaseUtility::getExtNameFromClassPath($class)][$class] = 
				'\\'.$class.
				' <span style="font-size: 11px; color: #ceb73b;">['.BaseUtility::getTableNameFromClass($class).']</span>'.
				' <span style="font-size: 11px; color: #8bc34a;">// '.BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label').'. '.
				BaseUtility::getClassAnnotationValueNew($class,'AirTable\Description').'</span>'. 
				$error;
			}
		}
		foreach($contentExt as $kExt => $vExt){
			ksort($vExt);
			$contentExtStr .= '
			<h2 style="margin-bottom: 0px;">EXT:'.$kExt.'</h2>
			<h3 style="margin-top: 0px; color: gray">Основная Namespace: \\'.$extListPsr4[$kExt][0].'\\'.$extListPsr4[$kExt][1].'</h3>
			<div style="overflow-x: scroll; white-space: nowrap; word-wrap: normal; background: #212121; color: #eff; border: 0; 
				border-top-left-radius: 0px; padding: 15px !important; font-size: 11px;">'.implode('<br />',$vExt).'
			</div>';
		}
		return $contentExtStr;
	}

}

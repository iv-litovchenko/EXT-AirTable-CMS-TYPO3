<?php
namespace Litovchenko\AirTable\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use FluidTYPO3\Flux\Provider\Provider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use Litovchenko\AirTable\Provider\PagesConfigurationProvider as PagesProvider;
use Litovchenko\AirTable\Provider\PagesSubConfigurationProvider as PagesSubProvider;
use Litovchenko\AirTable\Provider\TtContentConfigurationProvider as TtContentProvider;
use Litovchenko\AirTable\Utility\BaseUtility;

class AnnotationRegistrationExtTables {

	// Регистрация модулей
	public static function main()
	{
		// EXT:flux_capitator
		#\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Litovchenko.Projiv', 'Test', 'Test plugin');
		#\FluidTYPO3\FluxCapacitor\Implementation\FlexFormImplementation::registerForTableAndField('tx_data', 'prop_flexform');
		
		self::moduleController(); // if(TYPO3_MODE === 'BE') { self::moduleRoutes(); }
		self::addStaticFile();
		self::registerIcon();
		// self::registerPageTSConfigFile();
		
		// [Отказался, есть разделитель] Контейнер для меню // Add new page type:
		#$GLOBALS['PAGES_TYPES'][277] = [ 
		#	'type' => 'sys',
		#	'allowedTables' => 'pages',
		#	'onlyAllowedTables' => '0'
		#];
		   
		// [Отказался, есть разделитель] Контейнер для меню // Allow backend users to drag and drop the new page type:
		#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
		#	'options.pageTree.doktypesToShowInNewPageDragArea := addToList(277)'
		#);
					
		#print "<pre>";
		#print_r($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['AirTable']);
		#exit();
	}
	
	// add Icon
	public static function registerIcon(){
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/*';
		foreach (glob($typo3conf_path) as $filename) {
			$ext = basename($filename);
			foreach (glob($filename.'/Resources/Public/Icons/*') as $filename) {
				// if(preg_match('/^RegisterIcon/is',basename($filename))){
					if(end(explode('.',basename($filename))) == '.svg'){
						$provider = \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class;
					} else {
						$provider = \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class;
					}
					$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
					$iconRegistry->registerIcon(
						'extensions-tx-'.str_replace('_','',$ext).'-resources-public-icons-'.strstr(basename($filename),'.',true),
						$provider,
						['source' => 'EXT:'.$ext.'/Resources/Public/Icons/'.basename($filename)]
					);
				// }
			}
		}
	}
	
	// addStaticFile
	public static function addStaticFile(){
		$sortFolderExt = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			if(file_exists($filename.'/Configuration/TypoScript/IncFrontend/')){
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
					basename($filename), 
					'Configuration/TypoScript/IncFrontend', 
					':: IncTypoScript ::'
				);
			}
		}
		
		// Поиск файла с TS-настройками (автогенерация)
		#$typo3conf_path = \TYPO3\CMS\Core\Core\Environment::getPublicPath().'/typo3temp/setup.ts';
		#if(file_exists($typo3conf_path)){
		#	// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('air_table', '../../../typo3temp/', ':: IncTypoScript (typo3temp/) ::');
		#}
	}

	// registerPageTSConfigFile
	public static function registerPageTSConfigFile(){
		$sortFolderExt = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			if(file_exists($filename.'/Configuration/TypoScript/IncBackend/Page.tsconfig')){
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
					basename($filename), 
					'Configuration/TypoScript/IncBackend/Page.tsconfig', 
					':: IncTSconfig ::'
				);
			}
		}
	}
	
	// Регистрация маршрутов для модулей
	public static function moduleRoutes(){
		
		/**
		 * Генерация маршрутов для админки
		 */
		$routes = [];
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['BackendModule'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractModuleController',$class_parents)){
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$signature = BaseUtility::getTableNameFromClass($class);
				$r = new \ReflectionClass($class);
				$namespace = $r->getNamespaceName();
				$namespace = str_replace('\\','.',$namespace);
				$namespace_ex = explode('.',$namespace);
				$nameclass = $r->getShortName();
				$nameclass = str_replace('Controller','',$nameclass);
					
				$m = get_class_methods($class);
				foreach($m as $k => $v){
					if($v != "initializeAction"){
						if(preg_match("/Action$/is",$v)){
							// Создаем ссылку что бы сделать прозрачный роутинг для BE-модулей...
							$linkArKey = $pos.'_'.$namespace_ex[1].ucfirst(strtolower($nameclass)); // 'ext_MyextNew(!)module1'
							$linkArKeyNew = 'Ext.'.$namespace_ex[1].$nameclass; // 'routeExtMyext.NewModule1'.
							$linkArKeyRouter = 'Ext.'.$namespace_ex[1].'.Modules.'.$nameclass.'.'.preg_replace("/Action$/is",'',$v); // 'routeExtMyext.NewModule1'.
							
							// в дальнейшем найти как создается данный алиас...
							#'tx_airtable_content_airtableexport' => [
							#	'action' => 'step2'
							#]
										
							$temp = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($extensionKey);
							$forActionKey = 'tx_'.strtolower($temp);
							$forActionKey .=  '_'.strtolower($GLOBALS['TBE_MODULES']['_configuration'][$linkArKeyNew]['name']);
							$routes[$linkArKeyRouter] = [
								/*
								content_AirTableBackup
									options

										access = user,group
										module = 1
										moduleName = content_AirTableBackup
										target = TYPO3\CMS\Extbase\Core\Bootstrap::handleBackendRequest

									path = /module/content/AirTableBackup
								*/
								# 'path' => '/module/extensions/'.$signature.'/'.preg_replace("/Action$/is","",$v),
								# 'target' => $class.'::'.$v,
								# 'access' => 'user,group'
								#'options' => [
									'access' => 'user,group',
									'module' => 1,
									'moduleName' => $GLOBALS['TBE_MODULES']['_configuration'][$linkArKeyNew]['name'], // content_AirTableBackup
									'target' => 'TYPO3\CMS\Extbase\Core\Bootstrap::handleBackendRequest',
									'path' => '/module/extensions/'.$namespace_ex[1].'/'.$nameclass.'/'.preg_replace("/Action$/is","",$v),
									'parameters' => [
										// в дальнейшем найти как создается данный алиас...
										#'tx_airtable_content_airtableexport' => [
										#	'action' => 'step2'
										#]
										#'skipSessionUpdate' => 1
										$forActionKey => [
											'action' => preg_replace("/Action$/is","",$v) 
										]
									]
								#]
							];
						}
					}
				}
			// }
		}
		
		// Запись маршрутов
		BaseUtility::fileReWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/BackendRoutes.php','<?php return '.var_export($routes,true).';');
	}
	
	// Регистрация модулей
	public static function moduleController()
	{
		// if(!file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/AtLocalling/')){
		// 	mkdir($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/AtLocalling/');
		// }

		// Боллее ранних версиях по иконка называется по дргому
		// $GLOBALS['TBE_MODULES'];
		if (version_compare(TYPO3_version, '10.0.0', '<')) {
			$iconIdentifier = 'module-web';
		} else {
			$iconIdentifier = 'modulegroup-web';
		}
	
		// Tab "content"
		if(empty($GLOBALS['TBE_MODULES']['content'])){
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
				'content', // $annotationP[0],
				'',
				'after:web',
				null,
				[
					'iconIdentifier' => $iconIdentifier,
					'labels' => [
						'll_ref' => 'LLL:EXT:air_table/Resources/Private/Language/Localling.Module-Section.Content.xlf'
					],
					'name' => 'content'
				]
			);
		}

		// Tab "ext"
		if(empty($GLOBALS['TBE_MODULES']['ext'])){
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
				'ext', // $annotationP[0],
				'',
				'after:content',
				null,
				[
					'iconIdentifier' => $iconIdentifier,
					'labels' => [
						'll_ref' => 'LLL:EXT:air_table/Resources/Private/Language/Localling.Module-Section.Ext.xlf'
					],
					'name' => 'ext'
				]
			);
		}

		// Tab "unseen"
		if(empty($GLOBALS['TBE_MODULES']['unseen'])){
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
				'unseen', // $annotationP[0],
				'',
				'after:ext',
				null,
				[
					'iconIdentifier' => $iconIdentifier,
					'labels' => [
						'll_ref' => 'LLL:EXT:air_table/Resources/Private/Language/Localling.Module-Section.Unseen.xlf'
					],
					'name' => 'unseen'
				]
			);
		}
		
		$i = 1;
		$sortFolderExt = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			$dirname = basename($filename);
			$sortFolderExt[$dirname] = $i;
			$i++;
		}
	
		$classes = BaseUtility::getLoaderClasses2();
		$classes_sort = [];
		foreach($classes['BackendModule'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractModuleController',$class_parents)){
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$annotationP = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Position');
				if(!isset($classes_sort[$sortFolderExt[$extensionKey].$annotationP])){
					$classes_sort[$sortFolderExt[$extensionKey].$annotationP] = $class;
				} else {
					// print 'The position value is duplicated in the class "'.$class.'" annotation (@AirTable\Position:<'.$annotationP.'>)';
					// exit();
					while(1){
						if(isset($classes_sort[$sortFolderExt[$extensionKey].$annotationP])){
							$annotationP ++ ;
						} else {
							$classes_sort[$sortFolderExt[$extensionKey].$annotationP] = $class;
							break;
						}
					}
				}
			// }
		}
		
		ksort($classes_sort);
		foreach($classes_sort as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\Modules\AbstractModuleController',$class_parents)){
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$signature = BaseUtility::getTableNameFromClass($class);
				$path = BaseUtility::getClassPath($class);
				
				$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
				$annotationDescription = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Description');
				$annotationSection = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Section');
				$annotationAccess = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Access'); // user,group,admin,systemMaintainer
				
				if($annotationSection == "function") {
					
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::insertModuleFunction(
						'web_info',
						$class,
						null,
						"EXT:".$extensionKey.' // ' . $annotationLabel // . " // Проектные"
					);
					
				} else {
				
					$r = new \ReflectionClass($class);
					$namespace = $r->getNamespaceName();
					$namespace = str_replace('\\','.',$namespace);
					$namespace_ex = explode('.',$namespace);
					$nameclass = $r->getShortName();
					$nameclass = str_replace('Controller','',$nameclass);
					
					$actionList = [];
					$m = get_class_methods($class);
					foreach($m as $k => $v){
						if($v != "initializeAction"){
							if(preg_match("/Action$/is",$v)){
								$actionList[] = preg_replace("/Action$/is","",$v);
							}
						}
					}
				
					// Если просят создать вкладку
					$_txt = dirname($path).'/_.txt';
					if(preg_match("/^sec_(.*)/is",$annotationSection) && file_exists($_txt)){
						
						// Боллее ранних версиях по иконка называется по дргому
						// $GLOBALS['TBE_MODULES'];
						if (version_compare(TYPO3_version, '10.0.0', '<')) {
							$iconIdentifier = 'module-web';
						} else {
							$iconIdentifier = 'modulegroup-web';
						}
						
						// Write label
						$labelSection = file_get_contents($_txt).' (EXT-'.$extensionKey.')';
						$xmlFileName = 'Localling.Module-Section.'.md5($extensionKey.$labelSection).'.xlf';
						
						// Tab
						$annotationSectionWithoutUnderscore = str_replace('_','',$annotationSection);
						if(empty($GLOBALS['TBE_MODULES'][$annotationSectionWithoutUnderscore])){
							\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
								$annotationSectionWithoutUnderscore, // $annotationP[0],
								'',
								'after:content',
								null,
								[
									'iconIdentifier' => $iconIdentifier,
									'labels' => [
										// 'll_ref' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/Localling.Module-Section.'.ucfirst($annotationSection).'.xlf'
										'll_ref' => 'LLL:typo3temp/'.$xmlFileName, // Localling.Module.Backup.xlf
									],
									'name' => $annotationSectionWithoutUnderscore
								]
							);
						}
						
						// Write labels xml
						$labelSection = file_get_contents($_txt).' (EXT-'.$extensionKey.')';
						$xmlContent = file_get_contents($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/air_table/Resources/Private/Language/Localling.Module-Section.Template.xlf');
						$xmlContent = str_replace('###MODULTE-TITLE###',$labelSection,$xmlContent);
						$typo3temp_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/'.$xmlFileName;
						BaseUtility::fileReWrite($typo3temp_path,$xmlContent);
						
						$pos = $annotationSectionWithoutUnderscore;
						$navigationComponentId = '';
						$navigationFrameModule = '';
					
					} elseif($extensionKey == "air_table" && $annotationSection == "content"){
						$pos = "content";
						$navigationComponentId = '';
						$navigationFrameModule = '';
						
					} elseif ($annotationSection == "web") {
						$pos = "web";
						$navigationComponentId = 'TYPO3/CMS/Backend/PageTree/PageTreeElement';
						$navigationFrameModule = '';
						
					} elseif ($annotationSection == "user") {
						$pos = "user";
						$navigationComponentId = '';
						$navigationFrameModule = '';
						
					} elseif ($annotationSection == "help") {
						$pos = "help";
						$navigationComponentId = '';
						$navigationFrameModule = '';
						
					} elseif ($annotationSection == "file") {
						$pos = "file";
						$navigationComponentId = '';
						$navigationFrameModule = 'file_navframe';
						
					} elseif ($annotationSection == "tools") {
						$pos = "tools";
						$navigationComponentId = '';
						$navigationFrameModule = '';
						
					} elseif ($annotationSection == "unseen") {
						$pos = "unseen";
						$navigationComponentId = '';
						$navigationFrameModule = '';
					
					} else { // ($annotationSection == "extension")
						$pos = "ext";
						$navigationComponentId = '';
						$navigationFrameModule = '';
					}
					
					// Icon
					if($extensionKey == 'air_table'){
						$icon = 'EXT:air_table/Resources/Public/Icons/Module-Icon-'.$nameclass.'.png';
					} else {
						$icon = 'EXT:air_table/Resources/Public/Icons/Module-Icon-Default.png';
					}
					
					// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
					#if (version_compare(TYPO3_version, '10.0.0', '<')) {
						$tempClassName = end(explode("\Controller\\",$class));
						$tempClassName = str_replace('Controller','',$tempClassName);
						$classFix = $tempClassName; // 'Modules\List';
					#} else {
					#	$classFix = $class;
					#}
					
					// Write label
					$xmlFileName = 'Localling.Module.'.md5($signature.$annotationLabel.$annotationDescription).'.xlf';
					
					// Register module
					$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
					\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
						$namespace_ex[0].'.'.$namespace_ex[1], // 'Litovchenko.AirTable',
						$pos, // $annotationP[0], // tab
						'Modules_'.$nameclassWithoutControllerPostfix, // 'Donation',
						'', // $annotationP[1], // before:<submodulekey> after:<submodulekey>
						[$classFix => implode(",",$actionList)],
						[
							// 'iconIdentifier' => 'provider-bitmap',
							'icon'   => $icon,
							
							// 'labels' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/Localling.Module.'.$nameclass.'.xlf',
							'labels' => 'LLL:typo3temp/'.$xmlFileName, // Localling.Module.Backup.xlf
							
							'access' => $annotationAccess,
							'navigationComponentId' => $navigationComponentId,
							'navigationFrameModule' => $navigationFrameModule, // my_Mod
						]
					);
					
					// Write labels xml
					$xmlContent = file_get_contents($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/air_table/Resources/Private/Language/Localling.Module.Template.xlf');
					$xmlContent = str_replace('###MODULTE-TITLE###',$annotationLabel,$xmlContent);
					$xmlContent = str_replace('###MODULTE-TAB-LABEL###','EXT:'.$extensionKey.' '.$class,$xmlContent);
					$xmlContent = str_replace('###MODULTE-TAB-DESCRIPTION###',$annotationDescription,$xmlContent);
					$typo3temp_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/'.$xmlFileName;
					BaseUtility::fileReWrite($typo3temp_path,$xmlContent);
					
					// Создаем ссылку что бы сделать прозрачный роутинг для BE-модулей...
					// $linkArKey = $pos.'_'.$namespace_ex[1].ucfirst(strtolower($nameclass)); // 'ext_MyextNew(!)module1'
					// $linkArKeyNew = 'Ext.'.$namespace_ex[1].$nameclass; // 'routeExtMyext.NewModule1'
					// $link = $GLOBALS['TBE_MODULES']['_configuration'][$linkArKey];
					// $GLOBALS['TBE_MODULES']['_configuration'][$linkArKeyNew] = $link;
				}
			// }
		}
		
		// Удаляем вкладку "unseen"
		unset($GLOBALS['TBE_MODULES']['_configuration']['unseen']);
	}

}
?>
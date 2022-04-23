<?php
namespace Litovchenko\AirTable\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Litovchenko\AirTable\Utility\BaseUtility;

class AnnotationRegistrationExtLocalconf {

	// Регистрация
	public static function main()
	{
		/************
		* FLUX
		************/
		// \FluidTYPO3\Flux\Core::registerConfigurationProvider(\Litovchenko\AirTable\Provider\ProductConfigurationProvider::class);
		// \FluidTYPO3\Flux\Core::registerProviderExtensionKey('Litovchenko.Projiv', 'Page');
		// \FluidTYPO3\Flux\Core::registerProviderExtensionKey('Litovchenko.Projiv', 'Content');
		// if (TYPO3_MODE == 'FE') {
			// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSources'][] = \FluidTYPO3\Flux\Backend\TableConfigurationPostProcessor::class . '->processData';
		// }

		// \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('Litovchenko.Projiv', 'Test', ['Plugin' => 'test'], ['Plugin' => 'test']);
		
		// Сaching framework
		if (version_compare(TYPO3_version, '10.0.0', '>=')) {
			define('T3_CACHE_TABLE', 'cache_pages');
			define('T3_CACHE_TABLE_TAGS', 'cache_pages_tags');
		} else {
			define('T3_CACHE_TABLE', 'cf_cache_pages');
			define('T3_CACHE_TABLE_TAGS', 'cf_cache_pages_tags');
		}
		
		// Frontend
		$GLOBALS['TYPO3_CONF_VARS']['BE']['interfaces'] = 'backend,frontend'; // Выбор редиректа при авторизации в Backend
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieDomain'] = '/.*/'; // Сохранение Backend-авторизации для различных доменов
		
		if(TYPO3_MODE === 'BE') {
			self::TCEmainHook();
		}
		self::addPageTSConfig();
		self::addUserTSConfig();
		
		// [ext.isLoaded('ext_name') && getTSFE().beUserLogin]
		// ---
		// [END]
		
		self::extConf();
		self::mailPath();
		self::Hooks();
		self::Xclass();
		self::addRTEPreset();
		
		// self::pageType777(); // Не потребовалось!
		// self::pageType888(); // Дерево страниц
		self::pageController();	// +
		self::elementController(); // +
		self::gridElementController(); // +
		self::pluginController(); // +
		self::widgetController(); // +
		self::ajaxPage();
		self::ajaxPageContentById();
		self::viewHelper();
		
		// A) убираем cHash из адресов @eIdAjax@
		$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters']['^eIdAjax'] = '^eIdAjax';
		
		// Fluid -> {anotherdelimiter} [[f:for each=`{images}` as=`image`]] [[/f:for]]
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['preProcessors'][] = 'Litovchenko\AirTable\Parser\TemplateProcessor\AnotherdelimiterModifierTemplateProcessor';
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['preProcessors'][] = 'Litovchenko\AirTable\Parser\TemplateProcessor\DefaultassignModifierTemplateProcessor';
		
		// *** УСТАНАВЛИВАЕМ ДРУГИЕ ПУТИ К ШАБЛОНАМ *** //
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			$extensionKey = basename($filename);
			if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extensionKey)){
			if(file_exists($filename.'/Configuration/TypoScript/IncFrontend/setup.typoscript')){
			// if(file_exists($filename.'/Resources/Private/Templates/_Partials/') || file_exists($filename.'/Resources/Private/Templates/_Layouts/')){
			
				// Add module configuration
				// Add plugin configuration
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('
					module.tx_'.str_replace('_','',$extensionKey).' {
						view {
							# TYPO3 7 ???
							# templateRootPath = EXT:'.$extensionKey.'/Resources/Private/Templates/
							# partialRootPath = EXT:'.$extensionKey.'/Resources/Private/Partials/
							# layoutRootPath = EXT:'.$extensionKey.'/Resources/Private/Layouts/
							
							# TYPO3 8+
							templateRootPaths.getTemplateSourceHook = 1
							templateRootPaths.0 = EXT:'.$extensionKey.'/Resources/Private/Templates/
							partialRootPaths.0 = EXT:'.$extensionKey.'/Resources/Private/Partials/
							layoutRootPaths.0 = EXT:'.$extensionKey.'/Resources/Private/Layouts/
						}
					}
				
					# "skipDefaultArguments" не работает в настоящее время
					# https://question-it.com/questions/685523/typo3-95-problemy-s-novymi-usiliteljami-marshrutizatsii
					# config.tx_extbase.features.skipDefaultArguments = 1
					# config.tx_extbase.features.requireCHashArgumentForActionArguments = 0 // не помогло избавиться от &cHash=***
					
					plugin.tx_'.str_replace('_','',$extensionKey).' {
						view {
							# TYPO3 7 ???
							# templateRootPath = EXT:'.$extensionKey.'/Resources/Private/Templates/
							# partialRootPath = EXT:'.$extensionKey.'/Resources/Private/Partials/
							# layoutRootPath = EXT:'.$extensionKey.'/Resources/Private/Layouts/
							
							# TYPO3 8+
							templateRootPaths.getTemplateSourceHook = 1
							templateRootPaths.0 = EXT:'.$extensionKey.'/Resources/Private/Templates/
							partialRootPaths.0 = EXT:'.$extensionKey.'/Resources/Private/Partials/
							layoutRootPaths.0 = EXT:'.$extensionKey.'/Resources/Private/Layouts/
							# widget.TYPO3\CMS\Fluid\ViewHelpers\Widget\PaginateViewHelper.templateRootPaths.10 = EXT:tx_testproviderextension/Resources/Private/Templates/
						}
						features {
							# "skipDefaultArguments" не работает в настоящее время
							# https://question-it.com/questions/685523/typo3-95-problemy-s-novymi-usiliteljami-marshrutizatsii
							# skipDefaultArguments = 1
							
							# https://mbdev.zone/snippets/typo3/chash-entfernen
							# Не помогло избавиться от &cHash=*** // не помогло избавиться от &cHash=***
							# requireCHashArgumentForActionArguments = 0
						}
					}
					
					# https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Setup/Config/Index.html#contentobjectexceptionhandler
					config.contentObjectExceptionHandler = 0
					config.contentObjectExceptionHandler.errorMessage = Oops an error occurred. Code: %s
				');	
				
			}
			}
		}
					
		#print "<pre>";
		#print_r($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']);
		#exit();
	}
	
	public static function pageType888(){
		$tsCode = trim('
pageTree = PAGE
pageTree {
	typeNum = 888
	config {
		debug = 0
		admPanel = 0
		no_cache = 1
		disableAllHeaderCode = 1
		linkVars = type
	}
	10 = FLUIDTEMPLATE
	10.file = EXT:air_table/Resources/Private/Templates/PageTreeTemplate.html
}
		');
		ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);
	}
	
	public static function pageType777(){
		$tsCode = trim('
pageImitationBackend = PAGE
pageImitationBackend {
	typeNum = 777
	config {
		debug = 0
		admPanel = 0
		no_cache = 0
	}
	10 = TEXT
	10.value = 777
}
		');
		ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);
	}
	
	// mailPath
	public static function mailPath(){
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."*/") as $filename) {
			if(file_exists($filename.'Resources/Private/Templates/Emails/')){
				$extName = basename($filename);
				$extNameHash = hexdec(crc32($extName));
				$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][$extNameHash] = 'EXT:'.$extName.'/Resources/Private/Templates/Emails/';
				$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'][$extNameHash] = 'EXT:'.$extName.'/Resources/Private/Partials/';
				$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][$extNameHash] = 'EXT:'.$extName.'/Resources/Private/Layouts/';
			}
		}
	}
	
	// Hooks
	public static function Hooks(){
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['Hooks'] as $class) {
			if(property_exists($class,'TYPO3')){
				$vars = $class::$TYPO3;
				if(TYPO3_MODE === 'BE') {
					foreach($vars['onlyBackend'] as $k => $v){
						$temp = explode('::',$v);
						$path = explode('|',$temp[0]);
						$appendix = '';
						if($temp[1] != ''){
							$appendix = '->'.$temp[1];
						}
						// TYPO3_CONF_VARS|SC_OPTIONS|Backend\Template\Components\ButtonBar|getButtonsHook
						if(count($path) == 4){
							$GLOBALS['TYPO3_CONF_VARS'][$path[1]][$path[2]][$path[3]][] = $class.$appendix;
						// TYPO3_CONF_VARS|SC_OPTIONS|cms|db_new_content_el|wizardItemsHook
						} elseif(count($path) == 5){
							$GLOBALS['TYPO3_CONF_VARS'][$path[1]][$path[2]][$path[3]][$path[4]][] = $class.$appendix;
						}
					}
				} elseif(TYPO3_MODE === 'FE') {
					foreach($vars['onlyFrontend'] as $k => $v){
						$temp = explode('::',$v);
						$path = explode('|',$temp[0]);
						$appendix = '';
						if($temp[1] != ''){
							$appendix = '->'.$temp[1];
						}
						// TYPO3_CONF_VARS|SC_OPTIONS|Backend\Template\Components\ButtonBar|getButtonsHook
						if(count($path) == 4){
							$GLOBALS['TYPO3_CONF_VARS'][$path[1]][$path[2]][$path[3]][] = $class.$appendix;
						// TYPO3_CONF_VARS|SC_OPTIONS|cms|db_new_content_el|wizardItemsHook
						} elseif(count($path) == 5){
							$GLOBALS['TYPO3_CONF_VARS'][$path[1]][$path[2]][$path[3]][$path[4]][] = $class.$appendix;
						}
					}
				}
				if(isset($vars['all'])){
					foreach($vars['all'] as $k => $v){
						$temp = explode('::',$v);
						$path = explode('|',$temp[0]);
						$appendix = '';
						if($temp[1] != ''){
							$appendix = '->'.$temp[1];
						}
						// TYPO3_CONF_VARS|SC_OPTIONS|Backend\Template\Components\ButtonBar|getButtonsHook
						if(count($path) == 4){
							$GLOBALS['TYPO3_CONF_VARS'][$path[1]][$path[2]][$path[3]][] = $class.$appendix;
						// TYPO3_CONF_VARS|SC_OPTIONS|cms|db_new_content_el|wizardItemsHook
						} elseif(count($path) == 5){
							$GLOBALS['TYPO3_CONF_VARS'][$path[1]][$path[2]][$path[3]][$path[4]][] = $class.$appendix;
						}
					}
				}
			}
		}
	}

	// Xclass
	public static function Xclass(){
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['Xclass'] as $class) {
			if(property_exists($class,'TYPO3')){
				$vars = $class::$TYPO3;
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][$vars['object']] = [
					'className' => $class
				];
			}
		}
	}

	public static function extConf(){
		if(class_exists('TYPO3\CMS\Core\Configuration\ExtensionConfiguration')){
			$backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('air_table');
		} else {
			$backendConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['air_table']);
		}
		
		// Блокировка ведения логов
		if ($backendConfiguration['enablePhpLogErrors'] != 1){
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = '0';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = '';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = '';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = '';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLog'] = '';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['enable_errorDLOG'] = '0';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['enable_exceptionDLOG'] = '0';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['enableDeprecationLog'] = '0';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['syslogErrorReporting'] = '0';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['belogErrorReporting'] = '0';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLogLevel'] = '4';
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['sqlDebug'] = false;
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['no_pconnect'] = '1';
			$GLOBALS['TYPO3_CONF_VARS']['BE']['versionNumberInFilename'] = '1';
		}
		
		// Запись ошибок в файл...
		if ($backendConfiguration['enablePhpLogErrors'] == 1){
			
			/*	
				E_ALL – все ошибки,
				E_ERROR – критические ошибки,
				E_WARNING – предупреждения,
				E_PARSE – ошибки синтаксиса,
				E_NOTICE – замечания,
				E_CORE_ERROR – ошибки обработчика,
				E_CORE_WARNING – предупреждения обработчика,
				E_COMPILE_ERROR – ошибки компилятора,
				E_COMPILE_WARNING – предупреждения компилятора,
				E_USER_ERROR – ошибки пользователей,
				E_USER_WARNING – предупреждения пользователей,
				E_USER_NOTICE – уведомления пользователей.
			*/
			error_reporting(E_ERROR | E_PARSE);
			ini_set('display_errors', 'Off'); 
			ini_set('log_errors', 'On');
			ini_set('error_log', $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/php_errors.log');
		}
		
		// Настройка качества JPG-изображений
		if ($backendConfiguration['jpgQuality'] >= 1 && $backendConfiguration['jpgQuality'] <= 100){
			$GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality'] = $backendConfiguration['jpgQuality'];
			
		}
	}
	
	public static function ajaxPage(){
		
		// eIdAjax
		$eIdAjax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('eIdAjax');
		if(!empty($eIdAjax)){
			
			// Константа, что работает режим Ajax
			define('TYPO3_AJAX_MODE', TRUE);
			
		} else {
			
			// Константа, что не работает режим Ajax
			define('TYPO3_AJAX_MODE', FALSE);
		}
		
		#$_server_request_uri = $GLOBALS['_SERVER']['REQUEST_URI'];
		#if(preg_match('/^\/ajax\/(.*)\/(.*)\/(.*)/is',$_server_request_uri, $matches)){
		#	$pExt = $matches[1];
		#	$pController = $matches[2]; 
		#	$pAction = $matches[3];
		#}
		
		$tsCode = trim('

# // TYPO3 9,10,11+
[request.getPageArguments().get("eIdAjax") > 0 || request.getParsedBody()["eIdAjax"] > 0]

	# Ajax
	page >
	page = PAGE
	page.10 = FLUIDTEMPLATE
	page.10.file = EXT:air_table/Resources/Private/Templates/AjaxTemplate.html
	
	page.config.debug 								= 0
	page.config.admPanel 							= 0
	page.config.no_cache 							= 1
	page.config.disableAllHeaderCode 				= 1
	page.config.disablePrefixComment 				= 1
	page.config.typolinkCheckRootline 				= 1
	page.config.typolinkEnableLinksAcrossDomains 	= 1
	
[global]

# // TYPO3 7,8
[globalVar = GP:eIdAjax > 0]
	
	# Ajax
	page >
	page = PAGE
	page.10 = FLUIDTEMPLATE
	page.10.file = EXT:air_table/Resources/Private/Templates/AjaxTemplate.html
	
	page.config.debug 								= 0
	page.config.admPanel 							= 0
	page.config.no_cache 							= 1
	page.config.disableAllHeaderCode 				= 1
	page.config.disablePrefixComment 				= 1
	page.config.typolinkCheckRootline 				= 1
	page.config.typolinkEnableLinksAcrossDomains 	= 1
	
[global]

		');
		ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);

		#print "<pre>";
		#print_r($_server_request_uri);
		#exit();
	}
	
	public static function ajaxPageContentById(){
		
		#$_server_request_uri = $GLOBALS['_SERVER']['REQUEST_URI'];
		#if(preg_match('/^\/ajax\/(.*)\/(.*)\/(.*)/is',$_server_request_uri, $matches)){
		#	$pExt = $matches[1];
		#	$pController = $matches[2]; 
		#	$pAction = $matches[3];
		#}
		
		$tsCode = trim('

# // TYPO3 9,10,11+
[request.getPageArguments().get("eIdAjaxContentById") > 0 || request.getParsedBody()["eIdAjaxContentById"] > 0]

	# Ajax
	page >
	page = PAGE
	page.10 = FLUIDTEMPLATE
	page.10.file = EXT:air_table/Resources/Private/Templates/AjaxContentByIdTemplate.html
	page.10.variables {
      recordId = TEXT
      recordId.data = GP:eIdAjaxContentById
   }
	
	page.config.debug 								= 0
	page.config.admPanel 							= 0
	page.config.no_cache 							= 1
	page.config.disableAllHeaderCode 				= 1
	page.config.disablePrefixComment 				= 1
	page.config.typolinkCheckRootline 				= 1
	page.config.typolinkEnableLinksAcrossDomains 	= 1
	
[global]

# // TYPO3 7,8
[globalVar = GP:eIdAjaxContentById > 0]
	
	# Ajax
	page >
	page = PAGE
	page.10 = FLUIDTEMPLATE
	page.10.file = EXT:air_table/Resources/Private/Templates/AjaxContentByIdTemplate.html
	page.10.variables {
      recordId = TEXT
      recordId.data = GP:eIdAjaxContentById
   }
	
	page.config.debug 								= 0
	page.config.admPanel 							= 0
	page.config.no_cache 							= 1
	page.config.disableAllHeaderCode 				= 1
	page.config.disablePrefixComment 				= 1
	page.config.typolinkCheckRootline 				= 1
	page.config.typolinkEnableLinksAcrossDomains 	= 1
	
[global]

		');
		ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);

		#print "<pre>";
		#print_r($_server_request_uri);
		#exit();
	}
	
	public function viewHelper(){
		
		// TYPO3 7
		// if (version_compare(TYPO3_version, '8.0.0', '<')) {
			if(class_exists('TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper')){
				// class_alias('TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper', 'TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper');
			}
			if(interface_exists('TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface')){
				// class_alias('TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface', 'TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface');
			}
			// class_alias('Litovchenko\AirTable\ViewHelpers\ContentViewHelper', 'TYPO3\CMS\Fluid\ViewHelpers\ContentViewHelper');
			// class_alias('Litovchenko\AirTable\ViewHelpers\ObjectViewHelper', 'TYPO3\CMS\Fluid\ViewHelpers\ObjectViewHelper');
			// class_alias('Litovchenko\AirTable\ViewHelpers\MarkerViewHelper', 'TYPO3\CMS\Fluid\ViewHelpers\MarkerViewHelper');
			// class_alias('Litovchenko\AirTable\ViewHelpers\MarkerMediaViewHelper', 'TYPO3\CMS\Fluid\ViewHelpers\MarkerMediaViewHelper');
			// class_alias('Litovchenko\AirTable\ViewHelpers\WidgetViewHelper', 'TYPO3\CMS\Fluid\ViewHelpers\WidgetViewHelper');
		// }
		
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendViewHelper'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)){
				$alias = [];
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$extensionKey = explode("_",$extensionKey);
				foreach($extensionKey as $k => $v){
					$alias[] = ucfirst($v);
				}
				#$r = new \ReflectionClass($class);
				#$namespace = $r->getNamespaceName();
				#$nameclass = $r->getShortName();
				#$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['vhsExt'.implode('',$alias)] = [
				#	$namespace
				#];
				// Litovchenko\AirTableExamples\ViewHelpers\HelloWorldViewHelper
				// TYPO3\CMS\Fluid\ViewHelpers\VhsExtAirTableExamples\HellowConditionViewHelper
				// class_alias($class,'TYPO3\CMS\Fluid\ViewHelpers\VhsExt'.implode('',$alias).'\\'.$nameclass);
				$nameclass = 'TYPO3\CMS\Fluid\ViewHelpers\\'.'VhsExt'.implode('',$alias).'\\'.end(explode('\\ViewHelpers\\',$class));
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable']['VhsExt'.implode('',$alias)][$nameclass] = $class;
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable.Vendor']['VhsExt'.implode('',$alias)] = current(explode('\\',$class));
			// }
		}
		
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendWidget'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)){
				$alias = [];
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$extensionKey = explode("_",$extensionKey);
				foreach($extensionKey as $k => $v){
					$alias[] = ucfirst($v);
				}
				#$r = new \ReflectionClass($class);
				#$namespace = $r->getNamespaceName();
				#$nameclass = $r->getShortName();
				#$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['wgsExt'.implode('',$alias)] = [
				#	$namespace
				#];
				// Litovchenko\AirTableExamples\Controller\Widgets\TestWidgetController
				// Litovchenko\AirTableExamples\Controller\Widgets\TestViewHelper
				// TYPO3\CMS\Fluid\ViewHelpers\WgsExtAirTableExamples\TestViewHelper
				// TYPO3\CMS\Fluid\WidgetControllers\WgsExtAirTableExamples\TestWidgetController
				// class_alias('TYPO3\CMS\Fluid\ViewHelpers\WgsExtAirTableExamples\TestViewHelper','TYPO3\CMS\Fluid\ViewHelpers\WgsExt'.implode('',$alias).'\\'.$nameclass);
				$nameclass = 'TYPO3\CMS\Fluid\ViewHelpers\\'.'WgsExt'.implode('',$alias).'\\'.end(explode('\\Controller\\Widgets\\',$class));
				$nameclass = preg_replace('/Controller$/is','ViewHelper',$nameclass);
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable']['WgsExt'.implode('',$alias)][$nameclass] = $class;
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable.Vendor']['WgsExt'.implode('',$alias)] = current(explode('\\',$class));
				
				// Поиск значений по умолчанию
				$signature = BaseUtility::getTableNameFromClass($class);
				if(property_exists($class,'TYPO3') && isset($class::$TYPO3['registerArguments'])){
					foreach($class::$TYPO3['registerArguments'] as $argKey => $argValues){
						if($argValues[1] != null){
							if(is_array($argValues[1])){
								print 'Todo: registerArguments default value array - currently not supported'; // Todo
								exit();
							} else {
								$tsCode = 'plugin.tx_'.$signature.'.settings.'.$argKey.'='.$argValues[1];
							}
							ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43); // ,'defaultContentRendering'
						}
					}
				}
			// }
		}
		
		// 2 хелпера добавляем глабально
		//<f:widget vendor="Litovchenko" extension="AirTable" plugin="tx_air_table_testwidgetcontroller" />
		//<f:marker id="10" />
		//array_unshift($GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['f'], 'Litovchenko\AirTable\ViewHelpers');
		
		#print "<Pre>";
		#print_r($GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']);
		#exit();
		
		// 3 Widgets (виртуальные классы)
		// Делаем alias для класса
		// <wgsExtAirTableExamples:Test>
		// <wgsExtAirTableExamples:Test testArg1='100' testArg2='101' /> 
		spl_autoload_register(function ($classFluidTypo3) {
			// Litovchenko\AirTableExamples\Controller\Widgets\TestViewHelper
			$typo3temp_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/var/cache/code/vhs/';
			if(!file_exists($typo3temp_path)){
				mkdir($typo3temp_path);
			}
			if(strstr($classFluidTypo3,'ViewHelpers\Vhs')){
				$file = $typo3temp_path.'vhs_'.md5($classFluidTypo3).".php";
				if(!file_exists($file)){
					$extName = current(explode('\\',str_replace('TYPO3\CMS\Fluid\ViewHelpers\\','',$classFluidTypo3)));
					$classFullName = $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable'][$extName][$classFluidTypo3];
					$className = end(explode('\\',$classFullName));
					
					$namespacePosftix = explode('\\',$classFluidTypo3);
					unset($namespacePosftix[count($namespacePosftix)-1]);
					$namespacePosftix = implode('\\',$namespacePosftix);
					
					$initializeArgumentsContent = '';
					if(property_exists($classFullName,'TYPO3')){
						$initializeArgumentsContent = file_get_contents($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/air_table/Resources/Private/initializeArguments.txt');
					}
					
					if($classFullName != ''){
						file_put_contents(
							$file,
							"<?php 
namespace ".$namespacePosftix.";
class ".$className." extends \\".$classFullName." 
{

".$initializeArgumentsContent."

}
"
						);
					}
				}
				include_once $file;
				#class_alias('TYPO3\CMS\Fluid\ViewHelpers\VhsExtAirTable\AdminPanelViewHelper','Litovchenko\AirTable\ViewHelpers\AdminPanelViewHelper');
				#print $classFluidTypo3 . '<br />';
				#exit();
			}
			$typo3temp_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/var/cache/code/wgs/';
			if(!file_exists($typo3temp_path)){
				mkdir($typo3temp_path);
			}
			if(strstr($classFluidTypo3,'ViewHelpers\Wgs')){
				$file = $typo3temp_path.'wgs_'.md5($classFluidTypo3).".php";
				if(!file_exists($file)){
					$extName = current(explode('\\',str_replace('TYPO3\CMS\Fluid\ViewHelpers\\','',$classFluidTypo3)));
					$classFullName = $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable'][$extName][$classFluidTypo3];
					$className = end(explode('\\',$classFullName));
					$className = preg_replace('/Controller$/is','ViewHelper',$className);
					
					$namespacePosftix = explode('\\',$classFluidTypo3);
					unset($namespacePosftix[count($namespacePosftix)-1]);
					$namespacePosftix = implode('\\',$namespacePosftix);
					
					$initializeArgumentsContent = '';
					if(property_exists($classFullName,'TYPO3')){
						$initializeArgumentsContent = file_get_contents($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/air_table/Resources/Private/initializeArguments.txt');
					}
					
					if($classFullName != ''){
						file_put_contents(
							$file,
							"<?php 
namespace ".$namespacePosftix.";
class ".$className." extends \Litovchenko\AirTable\ViewHelpers\WidgetViewHelper
{

".$initializeArgumentsContent."

}
"
						);
					}
				}
				include_once $file;
				#class_alias('TYPO3\CMS\Fluid\WidgetControllers\WgsExtAirTableExamples\TestWidgetController','TYPO3\CMS\Fluid\ViewHelpers\WgsExtAirTableExamples\TestViewHelper');
				#print $classFluidTypo3 . '<br />';
				#exit();
			}
		});
	}
	
	// TCEmainHook
	public static function TCEmainHook(){
		#$classList = [];
		#$allClasses = BaseUtility::getLoaderClasses2();
		#$models = array_merge((array)$allClasses['BackendModelCrud'],(array)$allClasses['BackendModelCrudOverride'],(array)$allClasses['BackendModelExtending']);
		#foreach($models as $class) {
			# $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][] = $class; // array parseDataStructureByIdentifierPostProcess (array $dataStructure, array $identifier)
			# $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = $class;
			# $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = $class;
			
			// Modify flexform fields since core 8.5 via formEngine: Inject a data provider between TcaFlexPrepare and TcaFlexProcess
			#$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\GeorgRinger\News\Backend\FormDataProvider\NewsFlexFormManipulation::class] = [
			#	'depends' => [
			#		\TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare::class,
			#	],
			#	'before' => [
			#		\TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess::class,
			#	],
			#];
				
			# $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][$class] = [
			# 	'depends' => [
			# 		\TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew::class,
			# 	]
			# ];
		#}
		
		// Наполнение Flex-форм из таблицы "sys_flux_setting"
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\Litovchenko\AirTable\Hooks\Backend\ProcessDatamapCmdmapClass::class] = [
			'depends' => [
				\TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew::class,
			]
		];
	}
	
	// addPageTSConfig
	public static function addPageTSConfig(){
		$sortFolderExt = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			if(file_exists($filename.'/Configuration/TypoScript/IncBackend/Page.tsconfig')){
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . basename($filename) . '/Configuration/TypoScript/IncBackend/Page.tsconfig">');
			}
		}
	}	
	
	// addUserTSConfig
	public static function addUserTSConfig(){
		$sortFolderExt = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			if(file_exists($filename.'/Configuration/TypoScript/IncBackend/User.tsconfig')){
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . basename($filename) . '/Configuration/TypoScript/IncBackend/User.tsconfig">');
			}
		}
	}	
	
	// addRTEPreset
	public static function addRTEPreset(){
		$sortFolderExt = [];
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."/*") as $filename) {
			if(file_exists($filename.'/Configuration/RTE/MyPreset.yaml')){
				$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['ext_'.basename($filename).'_preset'] = 'EXT:air_table/Configuration/RTE/MyPreset.yaml';
			}
		}
	}	
	
	// Регистрация страниц
	public static function pageController(){
		
		#$setup = [];
		#$setup[] = '# Setting air_table PAGE TypoScript';
		#$setup[] = $pluginContent;
		#ExtensionManagementUtility::addTypoScript('air_table', 'setup', implode(LF, $setup), 'defaultContentRendering');
		
		$tsCode = trim('

page = PAGE
page.typeNum = 0
page.config.admPanel = 0
page.config.debug = 0
page.config.pageTitleFirst = 1
page.config.pageTitleSeparator =  :: 
page.config.pageTitleSeparator.noTrimWrap = | | |
page.config.pageTitleProviders.record.provider = Litovchenko\AirTable\PageRender\ExtensionsPageTitleProvider
page.config.typolinkCheckRootline = 1
page.config.typolinkEnableLinksAcrossDomains = 1
page.5 >
page.10 = FLUIDTEMPLATE
page.10.file = EXT:air_table/Resources/Private/Templates/NoTemplate.html

');
		ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);
		
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendPage'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractPageController',$class_parents)){
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$signature = BaseUtility::getTableNameFromClass($class);
				
				$r = new \ReflectionClass($class);
				$namespace = $r->getNamespaceName();
				$namespace = str_replace('\\','.',$namespace);
				$namespace_ex = explode('.',$namespace);
				$nameclass = $r->getShortName();
				
				$actionList = [];
				$m = get_class_methods($class);
				foreach($m as $k => $v){
					if($v != "initializeAction"){
						if(preg_match("/Action$/is",$v)){
							$actionList[] = preg_replace("/Action$/is","",$v);
						}
					}
				}
				$actionList[] = 'error';
				
				// NonCachedActions
				$actionListNonCached = [];
				$annotationNonCachedActions = BaseUtility::getClassAnnotationValueNew($class,'AirTable\NonCachedActions');
				if(!empty($annotationNonCachedActions)){
					$annotationNonCachedActions = explode(",",$annotationNonCachedActions);
					foreach($annotationNonCachedActions as $kA => $vA){
						$actionListNonCached[] = str_replace("Action","",$vA);
					}
				}
					
				// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
				#if (version_compare(TYPO3_version, '10.0.0', '<')) {
					$tempClassName = end(explode("\Controller\\",$class));
					$tempClassName = str_replace('Controller','',$tempClassName);
					$classFix = $tempClassName; // 'Modules\List';
				#} else {
				#	$classFix = $class;
				#}
				
				$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
				\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
					$namespace_ex[0].'.'.$namespace_ex[1], // 'Litovchenko.AirTable',
					'Pages_'.$nameclassWithoutControllerPostfix, // 'Plugin1',
					[$classFix => implode(",",$actionList)],
					[$classFix => implode(",",$actionListNonCached)], // non-cacheable actions
					'list_type' // \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
				);
				
				$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
				$annotationDisableAllHeaderCode = BaseUtility::getClassAnnotationValueNew($class,'AirTable\DisableAllHeaderCode');
				$annotationHeadConfiguration = BaseUtility::getClassAnnotationValueNew($class,'AirTable\HeadConfiguration');
				
				$tsContent = '';
				$templatePath = GeneralUtility::getFileAbsFileName($annotationHeadConfiguration);
				if(file_exists($templatePath))
				{
					// $pattern = '/<f:section name="HeaderTyposcript">([\s\S]*)<\/f:section>/msU';
					// preg_match($pattern, file_get_contents($templatePath), $matches);
					$tsContent = file_get_contents($templatePath);
				}
				
				$tsCode = trim('
#[backend.user.isLoggedIn]
# // До следующих времен
# // Нужно будет как-то связать с роутором и кэшированием
# tt_content.list.20.' . $signature . '.switchableControllerActions.'.$nameclass.'.'.$kA.' = '.$vA.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper = 1
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.name = Page #[###] - '.$annotationLabel.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.controller = '.$extensionKey.'/Classes/Controller/Pages/'.$nameclass.'.php
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.template = '.$extensionKey.'/Resources/Private/Templates/Pages/'.preg_replace('/Controller$/is','',$nameclass).'/'.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.key = '.$signature.'
#[END]

# // TYPO3 7,8,9
[globalVar = TSFE:page|tx_fed_page_controller_action = '.$signature.']
	page = PAGE
	page.typeNum = 0
	page.config.disableAllHeaderCode = '.intval($annotationDisableAllHeaderCode).'
	page.config.admPanel = 1
	page.config.debug = 1
	page.config.cache_period = 2678400
	page.config.cache_clearAtMidnight = 1
	page.config.typolinkCheckRootline = 1
	page.config.typolinkEnableLinksAcrossDomains = 1
	# page.config.pageRendererTemplateFile = EXT:air_table/Resources/Private/Templates/PageRenderer.html
	# page.config.disableBodyTag = 1
	# page {
	# 	'.$tsContent.'
	# }
	page.bodyTagCObject = TEXT
	page.bodyTagCObject {
		field = uid
		wrap = <body class="p|">
	}
	page.5 >
	page.10 = USER
	page.10 < tt_content.list.20.'.$signature.'
	page.10.view.templateFile = EXT:'.$extensionKey.'/Resources/Private/Templates/Pages.'.preg_replace('/Controller$/is','',$nameclass).'.Index.html
[global]

# // TYPO3 10,11+
[page["tx_fed_page_controller_action"] == "'.$signature.'"]
	page = PAGE
	page.typeNum = 0
	page.config.disableAllHeaderCode = '.intval($annotationDisableAllHeaderCode).'
	page.config.admPanel = 1
	page.config.debug = 1
	page.config.cache_period = 2678400
	page.config.cache_clearAtMidnight = 1
	page.config.typolinkCheckRootline = 1
	page.config.typolinkEnableLinksAcrossDomains = 1
	# page.config.pageRendererTemplateFile = EXT:air_table/Resources/Private/Templates/PageRenderer.html
	# page.config.disableBodyTag = 1
	# page {
	# 	'.$tsContent.'
	# }
	page.bodyTagCObject = TEXT
	page.bodyTagCObject {
		field = uid
		wrap = <body class="p|">
	}
	page.5 >
	page.10 = USER
	page.10 < tt_content.list.20.'.$signature.'
	page.10.view.templateFile = EXT:'.$extensionKey.'/Resources/Private/Templates/Pages.'.preg_replace('/Controller$/is','',$nameclass).'.Index.html
[global]
');
				ExtensionManagementUtility::addTypoScript($extensionKey, 'setup', $tsCode, 43);
				self::forExtbasePlugins($extensionKey, $signature, $namespace_ex[1], $nameclass); // Extbase-манипуляции (только для страниц и для плагинов)
			
			// }
		}
		
		#print "<pre>";
		#print_r($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']);
		#exit();
	}
	
	// Extbase-манипуляции (только для страниц и для плагинов)
	public static function forExtbasePlugins($extensionKey, $signature, $namespace_ex_1, $nameclass)
	{
		// A) убираем cHash из адресов
		$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters']['^tx_'.$signature] = '^tx_'.$signature; // .'['
		
		// Б) для <f:link.action controller="" extensionName="" pluginName="" action="show">action link</f:link.action>
		// делаем копию в массиве с более понятными именами "projiv" "tx_projiv_pagecontroller"
		// $extensionKey = str_replace('_','',$extensionKey);
		// $temp = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$namespace_ex_1]['plugins'][$nameclass];
		// $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['tx_'.$extensionKey]['plugins'][$signature] = $temp;
	}
	
	// Регистрация элементов контента
	public static function elementController(){
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendContentElement'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractPageElementController',$class_parents)){
			//	$annotationType = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Type');
			//	if($annotationType == 'Element'){
					$extensionKey = BaseUtility::getExtNameFromClassPath($class);
					$signature = BaseUtility::getTableNameFromClass($class);
					
					$r = new \ReflectionClass($class);
					$namespace = $r->getNamespaceName();
					$namespace = str_replace('\\','.',$namespace);
					$namespace_ex = explode('.',$namespace);
					$nameclass = $r->getShortName();
				
					// NonCachedActions
					$actionListNonCached = [];
					$annotationNonCachedActions = BaseUtility::getClassAnnotationValueNew($class,'AirTable\NonCachedActions');
					if(!empty($annotationNonCachedActions)){
						$annotationNonCachedActions = explode(",",$annotationNonCachedActions);
						foreach($annotationNonCachedActions as $kA => $vA){
							$actionListNonCached[] = str_replace("Action","",$vA);
						}
					}
					
					// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
					#if (version_compare(TYPO3_version, '10.0.0', '<')) {
						$tempClassName = end(explode("\Controller\\",$class));
						$tempClassName = str_replace('Controller','',$tempClassName);
						$classFix = $tempClassName; // 'Modules\List';
					#} else {
					#	$classFix = $class;
					#}
					
					/*
					\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
						'Litovchenko.Projiv', // 'Litovchenko.AirTable',
						'ElementGallery', // 'Plugin1',
						['PagesElements\Elements\ElementGallery' => "index,default"], // Litovchenko\Projiv\Controller\PagesElements\Elements\ElementGalleryController
						[],
						'CType'
					);
					*/
					
					$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
					\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
						$namespace_ex[0].'.'.$namespace_ex[1], // 'Litovchenko.AirTable',
						'Elements_'.$nameclassWithoutControllerPostfix, // 'Plugin1',
						[$classFix => "index,default,render,error,outlet"],
						[$classFix => implode(",",$actionListNonCached)], // non-cacheable actions
						'CType' // \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
					);
					
					$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
					$tsCode = trim('
#[backend.user.isLoggedIn]
tt_content.' . $signature . '.stdWrap.editWrapper = 1
tt_content.' . $signature . '.stdWrap.editWrapper.name = Content element #[###] - '.$annotationLabel.'
tt_content.' . $signature . '.stdWrap.editWrapper.controller = '.$extensionKey.'/Classes/Controller/PagesElements/Elements/'.$nameclass.'.php
tt_content.' . $signature . '.stdWrap.editWrapper.template = '.$extensionKey.'/Resources/Private/Templates/PagesElements/Elements/'.preg_replace('/Controller$/is','',$nameclass).'/'.'
tt_content.' . $signature . '.stdWrap.editWrapper.key = '.$signature.'
#[END]
');
					ExtensionManagementUtility::addTypoScript($extensionKey, 'setup', $tsCode, 43);
					
					/*
					 * Register a template directly for use as a custom CType. Once registered
					 * the CType will appear in the "Flux content" tab in the new content
					 * wizard, and will be driven by either a custom controller if one is
					 * specified or detected by convention; or render through the vanilla
					 * ContentController provided with Flux.
					 *
					 * @param string $providerExtensionName Vendor.ExtensionName format of extension scope of the template file
					 * @param string $templateFilename Absolute path to template file containing Flux definition, EXT:... allowed
					 * @param string|null $contentTypeName Optional override for the CType value this template will use
					 * @param string|null $providerClassName Optional custom class implementing ProviderInterface from Flux
					 * @param string|null $pluginName Optional plugin name used when registering the Extbase plugin for the template
					
					public static function registerTemplateAsContentType(
						$providerExtensionName,
						$templateFilename,
						$contentTypeName = null,
						$providerClassName = Provider::class,
						$pluginName = null
					) {
						if (!PathUtility::isAbsolutePath($templateFilename)) {
							$templateFilename = GeneralUtility::getFileAbsFileName($templateFilename);
						}

						static::$queuedContentTypeRegistrations[] = [
							$providerExtensionName,
							$templateFilename,
							$providerClassName,
							$contentTypeName,
							$pluginName
						];
					}

					Array
						(
							[0] => Array
								(
									[0] => Litovchenko.Projiv
									[1] => /home/i/ilitovfa/iv-litovchenko.ru/public_html/typo3conf/ext/projiv/Resources/Private/Templates/Content/YouTube.html
									[2] => FluidTYPO3\Flux\Provider\Provider
									[3] => projiv_youtube
									[4] => 
								)

						)
					*/
					
					#\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
					#	'Litovchenko.Projiv', // 'Litovchenko.AirTable',
					#	'Gallery', // 'Plugin1',
					#	['Sub\Gallery' => "index,default"], // Litovchenko\Projiv\Controller\PagesElements\Elements\ElementGalleryController
					#	[],
					#	'CType'
					#);
					// -- Не нужно --
					#\FluidTYPO3\Flux\Core::registerTemplateAsContentType(
					#	'Litovchenko.Projiv', 
					#	'EXT:projiv/Resources/Private/Templates/PagesElements/Elements/ElementGallery/Index.html',
					#	'projiv_gallery',
					#	'Litovchenko\AirTable\Provider\TtContentConfigurationProvider',
					#	'Gallery'
					#);
					
			//	}
			// }
		}
	}
	
	// Регистрация элементов контента (сетка)
	public static function gridElementController(){		
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendContentGridelement'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractPageElementController',$class_parents)){
			//	$annotationType = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Type');
			//	if($annotationType == 'GridElement'){
					$extensionKey = BaseUtility::getExtNameFromClassPath($class);
					$signature = BaseUtility::getTableNameFromClass($class);
					
					$r = new \ReflectionClass($class);
					$namespace = $r->getNamespaceName();
					$namespace = str_replace('\\','.',$namespace);
					$namespace_ex = explode('.',$namespace);
					$nameclass = $r->getShortName();
				
					// NonCachedActions
					$actionListNonCached = [];
					$annotationNonCachedActions = BaseUtility::getClassAnnotationValueNew($class,'AirTable\NonCachedActions');
					if(!empty($annotationNonCachedActions)){
						$annotationNonCachedActions = explode(",",$annotationNonCachedActions);
						foreach($annotationNonCachedActions as $kA => $vA){
							$actionListNonCached[] = str_replace("Action","",$vA);
						}
					}
					
					// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
					#if (version_compare(TYPO3_version, '10.0.0', '<')) {
						$tempClassName = end(explode("\Controller\\",$class));
						$tempClassName = str_replace('Controller','',$tempClassName);
						$classFix = $tempClassName; // 'Modules\List';
					#} else {
					#	$classFix = $class;
					#}
					
					$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
					\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
						$namespace_ex[0].'.'.$namespace_ex[1], // 'Litovchenko.AirTable',
						'Gridelements_'.$nameclassWithoutControllerPostfix, // 'Plugin1',
						[$classFix => "index,default,render,error,outlet"],
						[$classFix => implode(",",$actionListNonCached)], // non-cacheable actions
						'CType' // \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
					);
					
					$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
					$tsCode = trim('
#[backend.user.isLoggedIn]
tt_content.' . $signature . '.stdWrap.editWrapper = 1
tt_content.' . $signature . '.stdWrap.editWrapper.name = Content element #[###] - '.$annotationLabel.'
tt_content.' . $signature . '.stdWrap.editWrapper.controller = '.$extensionKey.'/Classes/Controller/PagesElements/Gridelements/'.$nameclass.'.php
tt_content.' . $signature . '.stdWrap.editWrapper.template = '.$extensionKey.'/Resources/Private/Templates/PagesElements/Gridelements/'.preg_replace('/Controller$/is','',$nameclass).'/'.'
tt_content.' . $signature . '.stdWrap.editWrapper.key = '.$signature.'
#[END]
');
					ExtensionManagementUtility::addTypoScript($extensionKey, 'setup', $tsCode, 43);
			//	}
			// }
		}
	}
	
	// Регистрация плагинов
	public static function pluginController(){
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendContentPlugin'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractPageElementController',$class_parents)){
			//	$annotationType = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Type');
			//	if($annotationType == 'Plugin'){
					$extensionKey = BaseUtility::getExtNameFromClassPath($class);
					$signature = BaseUtility::getTableNameFromClass($class);
					
					$r = new \ReflectionClass($class);
					$namespace = $r->getNamespaceName();
					$namespace = str_replace('\\','.',$namespace);
					$namespace_ex = explode('.',$namespace);
					$nameclass = $r->getShortName();
					
					$actionList = [];
					$m = get_class_methods($class);
					foreach($m as $k => $v){
						if($v != "initializeAction"){
							if(preg_match("/Action$/is",$v)){
								$actionList[] = preg_replace("/Action$/is","",$v);
							}
						}
					}
					$actionList[] = 'error';
				
					// NonCachedActions
					$actionListNonCached = [];
					$annotationNonCachedActions = BaseUtility::getClassAnnotationValueNew($class,'AirTable\NonCachedActions');
					if(!empty($annotationNonCachedActions)){
						$annotationNonCachedActions = explode(",",$annotationNonCachedActions);
						foreach($annotationNonCachedActions as $kA => $vA){
							$actionListNonCached[] = str_replace("Action","",$vA);
						}
					}
					
					// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
					#if (version_compare(TYPO3_version, '10.0.0', '<')) {
						$tempClassName = end(explode("\Controller\\",$class));
						$tempClassName = str_replace('Controller','',$tempClassName);
						$classFix = $tempClassName; // 'Modules\List';
					#} else {
					#	$classFix = $class;
					#}
					
					// \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($namespace_ex[0].'.'.$namespace_ex[1],'Plugins',''); // -> ВОЗМОЖНО ЭТО И НЕ НУЖНО!!!
					$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
					\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
						$namespace_ex[0].'.'.$namespace_ex[1], // 'Litovchenko.AirTable',
						'Plugins_'.$nameclassWithoutControllerPostfix, // 'Plugin1',
						[$classFix => implode(",",$actionList)],
						[$classFix => implode(",",$actionListNonCached)], // non-cacheable actions
						'list_type' // \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
					);
					
					$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
					$tsCode = trim('
#[backend.user.isLoggedIn]
# // До следующих времен
# // Нужно будет как-то связать с роутором и кэшированием
# tt_content.list.20.' . $signature . '.switchableControllerActions.'.$nameclass.'.'.$kA.' = '.$vA.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper = 1
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.name = Content plugin #[###] - '.$annotationLabel.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.controller = '.$extensionKey.'/Classes/Controller/PagesElements/Plugins/'.$nameclass.'.php
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.template = '.$extensionKey.'/Resources/Private/Templates/PagesElements/Plugins/'.preg_replace('/Controller$/is','',$nameclass).'/'.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.key = '.$signature.'
#[END]
');
					ExtensionManagementUtility::addTypoScript($extensionKey, 'setup', $tsCode, 43);
					self::forExtbasePlugins($extensionKey, $signature, $namespace_ex[1], $nameclass); // Extbase-манипуляции (только для страниц и для плагинов)
					
					#\FluidTYPO3\Flux\Core::registerFluidFlexFormPlugin(
					#    'Litovchenko.AirTable',
					#    'air_table_plugin1',
					#    'EXT:air_table/Resources/Private/Templates/Plugin1/Index.html',
					#    [],
					#    'Configuration'
					#);

					// Перенести в TCA/Overrides/
					#$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['air_table_plugin1'] = 'pi_flexform';
					#);
			//	}
			// }
		}
	}
	
	// Регистрация виджетов
	public static function widgetController(){
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendWidget'] as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)){
				#$annotationType = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Type');
				#if($annotationType == 'Block'){
					$extensionKey = BaseUtility::getExtNameFromClassPath($class);
					$signature = BaseUtility::getTableNameFromClass($class);
					
					$r = new \ReflectionClass($class);
					$namespace = $r->getNamespaceName();
					$namespace = str_replace('\\','.',$namespace);
					$namespace_ex = explode('.',$namespace);
					$nameclass = $r->getShortName();
					
					$actionList = [];
					$m = get_class_methods($class);
					foreach($m as $k => $v){
						if($v != "initializeAction"){
							if(preg_match("/Action$/is",$v)){
								$actionList[] = preg_replace("/Action$/is","",$v);
							}
						}
					}
				
					// NonCachedActions
					$actionListNonCached = [];
					$annotationNonCachedActions = BaseUtility::getClassAnnotationValueNew($class,'AirTable\NonCachedActions');
					if(!empty($annotationNonCachedActions)){
						$annotationNonCachedActions = explode(",",$annotationNonCachedActions);
						foreach($annotationNonCachedActions as $kA => $vA){
							$actionListNonCached[] = str_replace("Action","",$vA);
						}
					}
					
					// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
					#if (version_compare(TYPO3_version, '10.0.0', '<')) {
						$tempClassName = end(explode("\Controller\\",$class));
						$tempClassName = str_replace('Controller','',$tempClassName);
						$classFix = $tempClassName; // 'Modules\List';
					#} else {
					#	$classFix = $class;
					#}
					
					// \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($namespace_ex[0].'.'.$namespace_ex[1],'Plugins',''); // -> ВОЗМОЖНО ЭТО И НЕ НУЖНО!!!
					$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
					\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
						$namespace_ex[0].'.'.$namespace_ex[1], // 'Litovchenko.AirTable',
						'Widgets_'.$nameclassWithoutControllerPostfix, // 'Plugin1',
						[$classFix => implode(",",$actionList)],
						[$classFix => implode(",",$actionListNonCached)], // non-cacheable actions
						'list_type' // \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
					);
					
					$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
					$tsCode = trim('
#[backend.user.isLoggedIn]
tt_content.list.20.' . $signature . '.stdWrap.editWrapper = 1
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.name = Widget - '.$annotationLabel.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.controller = '.$extensionKey.'/Classes/Controller/Widgets/'.$nameclass.'.php
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.template = '.$extensionKey.'/Resources/Private/Templates/Widgets/'.preg_replace('/Controller$/is','',$nameclass).'/'.'
tt_content.list.20.' . $signature . '.stdWrap.editWrapper.key = '.$signature.'
#[END]
');
					ExtensionManagementUtility::addTypoScript($extensionKey, 'setup', $tsCode, 43);
					// self::forExtbasePlugins($extensionKey, $signature, $namespace_ex[1], $nameclass); // Extbase-манипуляции (только для страниц и для плагинов)
					
					#\FluidTYPO3\Flux\Core::registerFluidFlexFormPlugin(
					#    'Litovchenko.AirTable',
					#    'air_table_plugin1',
					#    'EXT:air_table/Resources/Private/Templates/Plugin1/Index.html',
					#    [],
					#    'Configuration'
					#);
				#}
			// }
		}
	}

}
?>
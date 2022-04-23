<?php
namespace Litovchenko\AirTable\Xclass;

use Litovchenko\AirTable\Utility\BaseUtility;

/**
 * @see https://forge.typo3.org/issues/92778
 * The original SiteConfiguration has no possibility for extensions to modify the configuration.
 * That's why we need to xclass it.
 */
class SiteConfiguration extends \TYPO3\CMS\Core\Configuration\SiteConfiguration
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Xclass',
		'name' => '',
		'description' => '',
		'object' => 'TYPO3\CMS\Core\Configuration\SiteConfiguration'
	];

    /**
     * Identifier to store all configuration data in cache_core cache.
     *
     * @internal
     * @var string
     */
    protected $cacheIdentifier = 'sites-configuration';

    /**
     * @param string $configPath
     */
    public function __construct(string $configPath)
    {
		parent::__construct($configPath);
		
		// Controllers (добавляем в cacheIdentifier)
		// При изменении... (добавляем в cacheIdentifier)
		$addCacheIdentifier = '';
		
		// MD5 (1) для public static $TYPO3 = ['urlManagerActions'];
		$allClasses = BaseUtility::getLoaderClasses2();
		$controllers = array_merge((array)$allClasses['FrontendPage'],(array)$allClasses['FrontendContentPlugin']);
		foreach($controllers as $k => $v){
			$temp[] = $v::$TYPO3['urlManagerActions'];
		}
		$addCacheIdentifier .= md5(serialize($temp));
		
		// MD5 (2) при изменении кол-ва страниц с "tx_fed_page_controller_action"
		$filter = [];
		$filter['withoutGlobalScopes'] = true;
		$filter['select'] = ['uid','pid','doktype','tx_fed_page_controller_action'];
		$filter['where.10'] = ['doktype','=',1];
		$filter['where.20'] = ['tx_fed_page_controller_action','like','%_pages_%'];
		$countPages = \Litovchenko\AirTable\Domain\Model\Content\Pages::recSelect('count',$filter);
		$rowsPages = \Litovchenko\AirTable\Domain\Model\Content\Pages::recSelect('get',$filter);
		$addCacheIdentifier .= $countPages;
		$addCacheIdentifier .= serialize($rowsPages);
		
		// MD5 (3) при изменении кол-ва плагинов
		$filter = [];
		$filter['withoutGlobalScopes'] = true;
		$filter['select'] = ['uid','pid','CType','list_type'];
		$filter['where.10'] = ['CType','=','list'];
		$filter['where.20'] = ['list_type','like','%_plugins_%'];
		$countTtContentPlugins = \Litovchenko\AirTable\Domain\Model\Content\TtContent::recSelect('count',$filter);
		$rowsTtContentPlugins = \Litovchenko\AirTable\Domain\Model\Content\TtContent::recSelect('get',$filter);
		$addCacheIdentifier .= $countTtContentPlugins;
		$addCacheIdentifier .= serialize($rowsTtContentPlugins);
		
		// Собираем идентификатор кэша
		$this->cacheIdentifier = md5($this->cacheIdentifier.'-'.$addCacheIdentifier);
    }
	
	protected function getAllSiteConfigurationFromFiles(bool $useCache = true): array
	{
		$siteConfiguration = $useCache ? $this->getCache()->require($this->cacheIdentifier) : false;
		if ($siteConfiguration !== false) {
			return $siteConfiguration;
		}
		
		$siteConfiguration = parent::getAllSiteConfigurationFromFiles($useCache);
		$siteConfiguration = $this->modifyConfiguration($siteConfiguration);
		$this->getCache()->set($this->cacheIdentifier, 'return ' . var_export($siteConfiguration, true) . ';');
		
        return $siteConfiguration;
    }
	
	protected function modifyConfiguration(array $configuration): array
	{
		foreach ($configuration as $siteKey => $siteConfiguration) {
			$configuration[$siteKey] = $this->modifySiteConfiguration($siteConfiguration);
		}
		return $configuration;
	}
	
	protected function modifySiteConfiguration(array $siteConfiguration): array
	{
		// Ajax
		###
		### 1. Simple Enhancer (type: Simple) 
		### https://t3terminal.com/blog/typo3-routing/
		###
		# Config.yaml
		#routeEnhancers:
		# Unique name for the enhancers, used internally for referencing
		#  CategoryListing:
		#	type: Simple
		#	limitToPages: [13]
		#	routePath: '/show-by-category/{category_id}/{tag}'
		#	defaults:
		#	  tag: ''
		#	requirements:
		#	_arguments:
		#	  category_id: 'category'
		// $siteConfiguration['routeEnhancers']['Ajax'] = [];
		// $siteConfiguration['routeEnhancers']['Ajax']['type'] = 'Simple';
		// http://ivan-litovchenko.ru/ajaxExtProjiv/Widgets/FeedBackFormController/twoAction
		// $siteConfiguration['routeEnhancers']['Ajax']['routePath'] = '/ajaxExt{eIdAjax_ext}.{eIdAjax_folder}.{eIdAjax_controller}.{eIdAjax_action}';
		
		// Controllers
		$allClasses = BaseUtility::getLoaderClasses2();
		$controllers = array_merge((array)$allClasses['FrontendPage'],(array)$allClasses['FrontendContentPlugin']);
		foreach($controllers as $class)
		{
			$annotationUrlManagerActions = BaseUtility::getClassAnnotationValueNew($class,'AirTable\UrlManagerActions');
			if(!empty($annotationUrlManagerActions))
			{
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				
				$limitToPages = [];
				$thisIs = $class::$TYPO3['thisIs']; // FrontendContentPlugin
				switch($thisIs){
					case 'FrontendPage':
						$type = 'Pages';
						$signature = BaseUtility::getTableNameFromClass($class);
						$signature = $signature;
						$filter = [];
						$filter['withoutGlobalScopes'] = true;
						$filter['select'] = ['uid'];
						$filter['where'] = ['tx_fed_page_controller_action','=',$signature];
						$limitToPages = \Litovchenko\AirTable\Domain\Model\Content\Pages::recSelect('get',$filter,'uid');
						foreach($rows as $k => $v){
							$limitToPages[] = $v['uid'];
						}
					break;
					case 'FrontendContentPlugin':
						$type = 'Plugins';
						$signature = BaseUtility::getTableNameFromClass($class);
						$filter = [];
						$filter['withoutGlobalScopes'] = true;
						$filter['select'] = ['pid'];
						$filter['where.10'] = ['CType','=','list'];
						$filter['where.20'] = ['list_type','=',$signature];
						$limitToPages = \Litovchenko\AirTable\Domain\Model\Content\TtContent::recSelect('get',$filter,'pid');
						foreach($rows as $k => $v){
							$limitToPages[] = $v['pid'];
						}
					break;
				}
				
				$r = new \ReflectionClass($class);
				$namespace = $r->getNamespaceName();
				$namespace = str_replace('\\','.',$namespace);
				$namespace_ex = explode('.',$namespace);
				$nameclass = $r->getShortName();
				
				// Боллее ранних версиях по другому рег. (что бы работали Subfolder/папки для контроллеров)
				#if (version_compare(TYPO3_version, '10.0.0', '<')) {
					$tempClassName = end(explode("\Controller\\",$class));
					$tempClassName = str_replace('Controller','',$tempClassName);
					$classFix = $tempClassName; // 'Modules\List';
				#} else {
				#	$classFix = $class;
				#}
				
				/*
				###
				### 1. Simple Enhancer (type: Simple) 
				### https://t3terminal.com/blog/typo3-routing/
				###
				# Config.yaml
				routeEnhancers:
				# Unique name for the enhancers, used internally for referencing
				  CategoryListing:
					type: Simple
					limitToPages: [13]
					routePath: '/show-by-category/{category_id}/{tag}'
					defaults:
					  tag: ''
					requirements:
					_arguments:
					  category_id: 'category'
				*/
				// $siteConfiguration['routeEnhancers']['CategoryListing'] = [];
				// $siteConfiguration['routeEnhancers']['CategoryListing']['type'] = 'Simple';
				// $siteConfiguration['routeEnhancers']['CategoryListing']['limitToPages'] = [228];
				// $siteConfiguration['routeEnhancers']['CategoryListing']['routePath'] = '/api/show-by-category/{category_id}/{tag}';
				// $siteConfiguration['routeEnhancers']['CategoryListing']['defaults']['tag'] = '';
				// $siteConfiguration['routeEnhancers']['CategoryListing']['_arguments']['category_id'] = 'category';
				
				/*
				###
				### 2. Plugin Enhancer (type: Plugin) - Pi-Based Plugins
				### https://t3terminal.com/blog/typo3-routing/
				###
				# Config.yaml
				routeEnhancers:
				  ForgotPassword:
					type: Plugin
					limitToPages: [13]
					routePath: '/forgot-password/{user}/{hash}'
					namespace: 'tx_felogin_pi1'						 // -> Доп. свойство!
					defaults:
					  forgot: "1"
					requirements:
					  user: '[0-9]{1..3}'
					  hash: '^[a-zA-Z0-9]{32}$'
				*/
				#$j = 1;
				#foreach($annotationUrlManagerActions as $routerPath => $routerSettings){
					#$routeEnhancer = str_replace('_','',$signature).$j;
					#$siteConfiguration['routeEnhancers'][$routeEnhancer]['type'] = 'Plugin';
					#$siteConfiguration['routeEnhancers'][$routeEnhancer]['limitToPages'] = [1,2,18];
					#$siteConfiguration['routeEnhancers'][$routeEnhancer]['routePath'] = $routerPath;
					#$siteConfiguration['routeEnhancers'][$routeEnhancer]['namespace'] = $signature;
					#$siteConfiguration['routeEnhancers'][$routeEnhancer]['_arguments']['uid'] = 'uid';
					#$siteConfiguration['routeEnhancers'][$routeEnhancer]['aspects']['uid'] = [
					#	'type' => 'PersistedPatternMapper',
					#	'tableName' => 'tx_projiv_dm_service',
					#	'routeFieldPattern' => '^(?P<uid>\d+)',
					#	'routeFieldResult' => '{uid}',
					#];
					#$j++;
				#}
				
				/*
				###
				### 3. Extbase Plugin Enhancer (type: Extbase)
				### https://t3terminal.com/blog/typo3-routing/
				###
				# Config.yaml
				routeEnhancers:
				  NewsPlugin:
					type: Extbase
					limitToPages: [13]
					extension: News
					plugin: Pi1
					routes:
					  - { routePath: '/list/{page}', _controller: 'News::list', _arguments: {'page': '@widget_0/currentPage'} }
					  - { routePath: '/tag/{tag_name}', _controller: 'News::list', _arguments: {'tag_name': 'overwriteDemand/tags'}}
					  - { routePath: '/blog/{news_title}', _controller: 'News::detail', _arguments: {'news_title': 'news'} }
					  - { routePath: '/archive/{year}/{month}', _controller: 'News::archive' }
					defaultController: 'News::list'
					defaults:
					  page: '0'
					requirements:
					  page: '\d+'
				*/
				
				#print $signature."<br />";
				#print $namespace_ex[0].'.'.$namespace_ex[3]."<br />"; // 'Litovchenko.AirTable',
				#print $nameclass."<br />"; // 'Plugin1',
				#print $classFix."<br />";
				#exit();
				
				$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
				$routeEnhancer = $namespace_ex[1].'_'.$type.'_'.$nameclassWithoutControllerPostfix; // str_replace('_','',$signature);
				$siteConfiguration['routeEnhancers'][$routeEnhancer]['type'] = 'Extbase';
				$siteConfiguration['routeEnhancers'][$routeEnhancer]['limitToPages'] = $limitToPages;
				$siteConfiguration['routeEnhancers'][$routeEnhancer]['extension'] = $namespace_ex[1]; // Projiv
				$siteConfiguration['routeEnhancers'][$routeEnhancer]['plugin'] = $type.'_'.$nameclassWithoutControllerPostfix; // PageDefaultController
				$siteConfiguration['routeEnhancers'][$routeEnhancer]['routes'] = [];
				foreach($annotationUrlManagerActions as $routerPath => $routerSettings){
					if(is_array($routerSettings)){
						// Todo - можно создать настройки...
					} else {
						$action = preg_replace('/Action$/is','',$routerSettings);
					}
					$siteConfiguration['routeEnhancers'][$routeEnhancer]['routes'][] = [
						'routePath' => $routerPath,
						'_controller' => $classFix.'::'.$action // Pages\PageDefault::travelView
					];
				}
				
				#print "<pre>";
				#print_r($siteConfiguration);
				#exit();
				
				/*
				###
				### 4. Page Type Decorator (type: PageType)
				### https://t3terminal.com/blog/typo3-routing/
				###
				# Setup.typoscript
				rssfeed = PAGE
				rssfeed.typeNum = 13
				rssfeed.10 < plugin.tx_myplugin
				rssfeed.config.disableAllHeaderCode = 1
				rssfeed.config.additionalHeaders.10.header = Content-Type: xml/rss
				# Config.yaml
				routeEnhancers:
				   PageTypeSuffix:
					  type: PageType
					  default: '.json'
					  index: 'index'
					  map:
						 'rss.feed': 13
						 '.json': 26
				*/
				#$siteConfiguration['routeEnhancers']['PageTypeSuffix'] = [];
				#$siteConfiguration['routeEnhancers']['PageTypeSuffix']['type'] = 'PageType';
				
				/*
				###
				### 5. Develop Custom TYPO3 Enhancers
				### https://t3terminal.com/blog/typo3-routing/
				###
				# ext_localconf.php
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers']['MyCustomEnhancerAsUsedInYaml'] = \MyVendor\MyExtension\Routing\Enhancer\MyCustomEnhancer::class;
				*/
			}
		}
		
		return $siteConfiguration;
    }
}
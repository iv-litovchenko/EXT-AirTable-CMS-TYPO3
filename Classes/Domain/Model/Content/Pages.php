<?php
namespace Litovchenko\AirTable\Domain\Model\Content;

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

class Pages extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrudOverride',
		'keyInDatabase' => 'pages',
		'name' => 'Страница',
		'description' => 'Регистрация модели в системе',
		'defaultListTypeRender' => 2,
		'dataFields' => [
			'pid' => [ // -- NEW FIELD --
				'type' => 'Input.InvisibleInt',
				'name' => 'PID',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'doNotCheck' => 1
			],
			'sorting' => [ // -- NEW FIELD --
				'type' => 'Input.InvisibleInt',
				'name' => 'Sorting',
				'show' => 1,
				'doNotCheck' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'doktype' => [
				'type' => 'Switcher.Int',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'slug' => [
				'type' => 'Input.Slug',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'title' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'nav_title' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'subtitle' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'abstract' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'keywords' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'description' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'tx_fed_page_controller_action' => [
				'type' => 'Switcher',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'hidden' => [
				'type' => 'FlagDisabled',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'starttime' => [
				'type' => 'Date.DateTime',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'endtime' => [
				'type' => 'Date.DateTime',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			]
		],
		'mediaFields' => [
			'media' => [
				'type' => 'Media.Mix',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			]
		],
		'relationalFields' => [
			#'propref_attributes' => [
			#	'type' => 'Rel_Eav',
			#	'name' => 'Характеристики',
			#	'position' => '*|extended|0',
			#	'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute',
			#	'selectMinimizeInc' => 1,
			#	'doNotCheck' => 1,
			#	'show' => 1,
			#],
		]
	];
	
	// Relationship (user function register)
	public function media() {
		return $this->refProvider('media');
	}
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		/*
		// \FluidTYPO3\Flux\Core::registerProviderExtensionKey('Litovchenko.Projiv', 'Gallery');
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$provider = $objectManager->get(\Litovchenko\AirTable\Provider\PagesConfigurationProvider::class);
		
		$configuration['columns']['tx_fed_page_controller_action']['config']['items'][] = [
			0 => 'TEST', // .' ('.'EXT:'.$extensionKey.')'
			1 => 'Litovchenko.Projiv->index',
			2 => 'extensions-tx-airtable-resources-public-icons-PagesTemplate',
			3 => 'div_project_'.$extensionKey // группа
		];
					 
		$provider->setExtensionKey('Litovchenko.Projiv');
		$provider->setTableName('pages');
		$provider->setControllerName('Page');
		$provider->setControllerAction('index');
		$provider->setFieldName('tx_fed_page_flexform');
		$provider->setFullControllerName('Litovchenko\Projiv\Controller\PageController');
		\FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
		*/
		
		// Добавляем контроллеры страниц
		$classes = BaseUtility::getLoaderClasses2();
		foreach($classes['FrontendPage'] as $class) {
			
			// Пустой шаблон
			if($class == 'Litovchenko\AirTable\Controller\PageEmptyController'){
				continue;
			}
			
			// Убираем два поля - пока посчитал их не нужными...
			$configuration['columns']['backend_layout']['config']['readOnly'] = 1;
			unset($configuration['columns']['backend_layout_next_level']);
			unset($configuration['columns']['tx_fed_page_controller_action_sub']);
			unset($configuration['columns']['tx_fed_page_flexform_sub']);
			
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractPageController',$class_parents)){
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$extensionKeyLabel = explode('\\',$class);
				$extensionKeyLabel = /* $extensionKeyLabel[0].'\\'.*/ $extensionKeyLabel[1];
				$signature = BaseUtility::getTableNameFromClass($class);
				$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
				$annotationDescription = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Description');
				$annotationFluxFields = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxFields');
				$annotationFluxConfiguration = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxConfiguration');
				
				$r = new \ReflectionClass($class);
				$namespace = $r->getNamespaceName();
				$namespace = str_replace('\\','.',$namespace);
				$namespace_ex = explode('.',$namespace);
				$nameclass = $r->getShortName();
				$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
					
				// Регистрация группы
				// 0 => '(todo) Проектные EXT:'.$extensionKey,
				$configuration['columns']['tx_fed_page_controller_action']['config']['itemGroups']['div_project_'.$extensionKey] = 'Проектные EXT:'.$extensionKey;
				$configuration['columns']['tx_fed_page_controller_action']['config']['items'][$signature] = [
					0 => $extensionKeyLabel.' | '.$annotationLabel, // .' ('.'EXT:'.$extensionKey.')'
					1 => $signature,
					2 => 'extensions-tx-airtable-resources-public-icons-PagesTemplate',
					3 => 'div_project_'.$extensionKey // группа
				];
		
				// subtype_value_field 
				// subtypes_excludelist
				// subtypes_addlist
				// * @AirTable\FieldsExcludeList:<field1,field2,field3...> // Todo
				// * @AirTable\FieldsAddList:<field1,field2,field3...> // Todo
				$annotationFex = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FieldsExcludeList');
				$annotationFin = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FieldsAddList');
				$configuration['types'][1]['subtype_value_field'] = 'tx_fed_page_controller_action';
				$configuration['types'][1]['subtypes_excludelist'][$signature] = $annotationFex;
				$configuration['types'][1]['subtypes_addlist'][$signature] = $annotationFin;
				
					// EXT:flux
					// Добавляем провайдеры
					// BaseUtility::registerFluidFlexForm($namespace_ex[0].'.'.$namespace_ex[1], $class, 'pages', $signature);
					if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('flux') && isset($annotationFluxConfiguration))
					{
						/*
						\FluidTYPO3\Flux\Core::registerFluidFlexFormContentObject(
							$namespace_ex[0].'.'.$namespace_ex[1],
							$signature,
							'EXT:'.$extensionKey.'/Resources/Private/Templates/PagesElements/Elements/'.$nameclass.'/Default.html',
							[],
							'Configuration',
							# array(
							# 	'layoutRootPath' => 'EXT:'.$extensionKey.'/Resources/Private/Layouts/',
							# 	'partialRootPath' => 'EXT:'.$extensionKey.'/Resources/Private/Partials/',
							# 	'templateRootPath' => 'EXT:'.$extensionKey.'/Resources/Private/Templates/',
							# )
						);
						*/
						/** @var $objectManager ObjectManagerInterface */
						/** @var $provider ProviderInterface */
						$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
						$provider = $objectManager->get(PagesProvider::class);
						$provider->setTableName('pages');
						$provider->setFieldName('tx_fed_page_flexform');
						$provider->setExtensionKey($namespace_ex[0].'.'.$namespace_ex[1]); // .'.'.$nameclassWithoutControllerPostfix
						$provider->setTemplatePathAndFilename('EXT:'.$extensionKey.'/Resources/Private/Templates/Pages/'.$nameclassWithoutControllerPostfix.'/Index.html');
						$provider->setTemplateVariables([]);
						$provider->setTemplatePaths(null);
						$provider->setConfigurationSectionName('Configuration');
						$provider->setControllerName('Pages/'.$nameclassWithoutControllerPostfix);
						$provider->setControllerAction('index');
						$provider->setPageObjectType($signature);
						\FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
						
						/** @var $objectManager ObjectManagerInterface */
						/** @var $provider ProviderInterface */
						/*
						$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
						$provider = $objectManager->get(PagesSubProvider::class);
						$provider->setTableName('pages');
						$provider->setFieldName('tx_fed_page_flexform_sub');
						$provider->setExtensionKey($namespace_ex[0].'.'.$namespace_ex[1]); // .'.'.$nameclassWithoutControllerPostfix
						$provider->setTemplatePathAndFilename('EXT:'.$extensionKey.'/Resources/Private/Templates/Pages/'.$nameclassWithoutControllerPostfix.'/Index.html');
						$provider->setTemplateVariables([]);
						$provider->setTemplatePaths(null);
						$provider->setConfigurationSectionName('Configuration');
						$provider->setControllerName('Pages/'.$nameclassWithoutControllerPostfix);
						$provider->setControllerAction('index');
						$provider->setPageObjectType($signature);
						\FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
						*/
					}
			// }
		}
		
		
		#print "<pre>";
		#print_r($configuration['columns']['media']);
		#exit();
		
		// [Отказался, есть разделитель] Контейнер для меню // Add new page type as possible select item:
		#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
		#	'pages',
		#	'doktype',
		#	[ 'Menu container', 277, 'content-target' ],
        #    '199',
        #    'after'
        #);

		// [Отказался, есть разделитель] Контейнер для меню // Add icon for new page type:
		#$configuration['ctrl']['typeicon_classes'][277] = 'content-target';
		#$configuration['types'][277] = $configuration['types'][199];
	}
}
?>
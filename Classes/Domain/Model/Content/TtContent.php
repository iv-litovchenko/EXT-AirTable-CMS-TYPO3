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

class TtContent extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrudOverride',
		'keyInDatabase' => 'tt_content',
		'name' => 'Содержимое страницы',
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
			'CType' => [
				'type' => 'Switcher',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'header' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'subheader' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'bodytext' => [
				'type' => 'Text.Rte',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'list_type' => [
				'type' => 'Switcher',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'layout' => [
				'type' => 'Switcher',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'frame_class' => [
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
			],
			'foreign_table' => [
				'type' => 'Input',
				'name' => 'Poly: 1 Таблица',
				'readOnly' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|extended|-4',
			],
			'foreign_field' => [
				'type' => 'Input',
				'name' => 'Poly: 2 Колонка',
				'readOnly' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|extended|-3',
			],
			'foreign_uid' => [
				'type' => 'Input.Number',
				'name' => 'Poly: 3 Запись (ID)',
				'readOnly' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|extended|-2',
			],
			'foreign_sortby' => [
				'type' => 'Input.Number',
				'name' => 'Poly: 4 Сортировка',
				'readOnly' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|extended|-1',
			],
		],
		'mediaFields' => [
			'image' => [
				'type' => 'Media.Image',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'media' => [
				'type' => 'Media.Mix',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
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
	public function image() {
		return $this->refProvider('image');
	}
	
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
		$provider = $objectManager->get(\Litovchenko\AirTable\Provider\TtContentConfigurationProvider::class);
					 
		$provider->setExtensionKey('Litovchenko.Projiv');
		$provider->setTableName('tt_content');
		$provider->setControllerName('PagesElements\Elements\ElementGallery'); // setControllerAction
		$provider->setFieldName('pi_flexform');
		$provider->setContentObjectType('projiv_elementgallery');
		\FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
		return '';
		*/
		
		// Разрешаем создание записей в корне дерева страниц сайта
		$configuration['ctrl']['rootLevel'] = -1; // Разрешаем создавать содержимое в корне дерева
		$configuration['columns']['colPos']['displayCond'] = [
			'AND' => [
				'FIELD:pid:!=:0', // В корне скрываем выбор колонки
				'FIELD:foreign_uid:<:1', // Для содержимого записей также не отображаем
			]
		];
		
		// Добавляем элементы содержимого
		// Добавляем плагин
		$allClasses = BaseUtility::getLoaderClasses2();
		$classes = array_merge((array)$allClasses['FrontendContentElement'],(array)$allClasses['FrontendContentGridelement'],(array)$allClasses['FrontendContentPlugin']);
		foreach($classes as $class) {
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Controller\AbstractPageElementController',$class_parents)){
				
				$extensionKey = BaseUtility::getExtNameFromClassPath($class);
				$extensionKeyLabel = explode('\\',$class);
				$extensionKeyLabel = /* $extensionKeyLabel[0].'\\'.*/ $extensionKeyLabel[1];
				$signature = BaseUtility::getTableNameFromClass($class);
				$annotationLabel = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
				$annotationDescription = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Description');
				$annotationFluxFields = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxFields');
				$annotationFluxConfiguration = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxConfiguration');
				// $annotationType = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Type');
				
				$r = new \ReflectionClass($class);
				$namespace = $r->getNamespaceName();
				$namespace = str_replace('\\','.',$namespace);
				$namespace_ex = explode('.',$namespace);
				$nameclass = $r->getShortName();
				$nameclassWithoutControllerPostfix = preg_replace('/Controller$/is','',$nameclass);
				
				// Регистрация группы
				if (version_compare(TYPO3_version, '10.4.0', '>=')) {
					$configuration['columns']['CType']['config']['itemGroups']['div_project_'.$extensionKey] = 'EXT:'.$extensionKey;
					$configuration['columns']['list_type']['config']['itemGroups']['div_project_'.$extensionKey] = 'EXT:'.$extensionKey;
				} else {
					$configuration['columns']['CType']['config']['items']['div_project_'.$extensionKey] = ['EXT:'.$extensionKey,'--div--'];
					$configuration['columns']['list_type']['config']['items']['div_project_'.$extensionKey] = ['EXT:'.$extensionKey,'--div--'];
				}
				
				// if($annotationType == 'Element'){
				if($class::$TYPO3['thisIs'] == 'FrontendContentElement' || $class::$TYPO3['thisIs'] == 'FrontendContentGridelement'){
					
					$cssIcon = 'resources-public-icons-TtContentElement';
					$namePrefix = 'EL: ';
					if($class::$TYPO3['thisIs'] == 'FrontendContentGridelement'){
						$cssIcon = 'resources-public-icons-TtContentGridelement';
						$namePrefix = 'GR: ';
					}
					
					// Регистрация элемента
					// \TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons(array('icon_' . $class_vars['element_key'] => '../typo3conf/ext/air_table/Resourses/Public/Images/icon_tt_content_element.svg'), 'air_table');
					$configuration['ctrl']['typeicon_classes'][$signature] = 'extensions-tx-airtable-'.$cssIcon;	
					$configuration['types'][$signature] = $configuration['types']['header'];
					$configuration['columns']['CType']['config']['items'][$signature] = [
						0 => $namePrefix.$extensionKeyLabel.' | '.$annotationLabel, // .' ('.'EXT:'.$extensionKey.')'
						1 => $signature,
						2 => 'extensions-tx-airtable-'.$cssIcon,
						3 => 'div_project_'.$extensionKey // группа
					];
						
					// добавляем на табы при создании нового элемента контента
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
						# mod.web_layout.tt_content.preview.'.$signature.' = EXT:'.$extensionKey.'/Resources/Private/Templates/PagesElements/Preview.html
						mod.wizards.newContentElement.wizardItems {
							ext'.$extensionKey.'.header = EXT:'.$extensionKeyLabel.'
							ext'.$extensionKey.'.show := addToList('.$signature.')
							ext'.$extensionKey.'.elements {
								'.$signature.' {
									title 			= '.$annotationLabel.' 
									description 	= '.$annotationDescription.'
									iconIdentifier  = extensions-tx-airtable-'.$cssIcon.'
									tt_content_defValues {
										CType = '.$signature.'
									}
								}
							}
						}
					');
					
					// subtype_value_field 
					// subtypes_excludelist
					// subtypes_addlist
					// * @AirTable\FieldsExcludeList:<field1,field2,field3...> // Todo
					// * @AirTable\FieldsAddList:<field1,field2,field3...> // Todo
					$annotationFex = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FieldsExcludeList');
					$annotationFin = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FieldsAddList');
					$configuration['types'][$signature]['subtype_value_field'] = 'CType';
					$configuration['types'][$signature]['subtypes_excludelist'][$signature] = $annotationFex;
					$configuration['types'][$signature]['subtypes_addlist'][$signature] = $annotationFin;
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'pi_flexform', $signature, 'after:subheader');
					
					// EXT:flux
					// Добавляем провайдеры
					// BaseUtility::registerFluidFlexForm($namespace_ex[0].'.'.$namespace_ex[1], $class, 'tt_content', $signature);
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
						$provider = $objectManager->get(TtContentProvider::class);
						$provider->setTableName('tt_content');
						$provider->setFieldName('pi_flexform');
						$provider->setExtensionKey($namespace_ex[0].'.'.$namespace_ex[1]);
						
						if($class::$TYPO3['thisIs'] == 'FrontendContentElement'){
							$provider->setTemplatePathAndFilename('EXT:'.$extensionKey.'/Resources/Private/Templates/PagesElements/Elements/'.$nameclassWithoutControllerPostfix.'/Index.html');
						
						} elseif($class::$TYPO3['thisIs'] == 'FrontendContentGridelement'){
							$provider->setTemplatePathAndFilename('EXT:'.$extensionKey.'/Resources/Private/Templates/PagesElements/Gridelements/'.$nameclassWithoutControllerPostfix.'/Index.html');
						}
						
						$provider->setTemplateVariables([]);
						$provider->setTemplatePaths(null);
						$provider->setConfigurationSectionName('Configuration');
						$provider->setContentObjectType($signature);
						\FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
					}
					
				// } elseif($annotationType == 'GridElement'){
				// } elseif($class::$TYPO3['thisIs'] == 'FrontendContentGridelement'){
					
				// } elseif($annotationType == 'Plugin') {
				} elseif($class::$TYPO3['thisIs'] == 'FrontendContentPlugin'){
					
					// Специальное для "FrontendContentPlugin"
					// $annotation___ = BaseUtility::getClassAnnotationValueNew($class,'AirTable\___');
			
					// Регистрация элемента
					// \TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons(array('icon_' . $class_vars['element_key'] => '../typo3conf/ext/air_table/Resourses/Public/Images/icon_tt_content_plugin.svg'), 'air_table');
					$configuration['ctrl']['typeicon_classes'][$signature] = 'extensions-tx-airtable-resources-public-icons-TtContentElement-teaser';	
					$configuration['columns']['list_type']['config']['items'][] = ['','--div--'];
					$configuration['columns']['list_type']['config']['items'][$signature] = [
						0 => 'PL: '.$extensionKeyLabel.' | '.$annotationLabel, // .' ('.'EXT:'.$extensionKey.')'
						1 => $signature,
						2 => 'extensions-tx-airtable-resources-public-icons-TtContentPlugin',
						3 => 'div_project_'.$extensionKey // группа
					];
						
					// добавляем на табы при создании нового элемента контента
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
						mod.wizards.newContentElement.wizardItems {
							ext'.$extensionKey.'.header = EXT:'.$extensionKeyLabel.'
							ext'.$extensionKey.'.show := addToList('.$signature.')
							ext'.$extensionKey.'.elements {
								'.$signature.' {
									title 			= '.$annotationLabel.' 
									description 	= '.$annotationDescription.'
									iconIdentifier  = extensions-tx-airtable-resources-public-icons-TtContentPlugin
									tt_content_defValues {
										CType = list
										list_type = '.$signature.'
									}
								}
							}
						}
					');
					
					// Flexform
					// $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$signature] = 'layout,recursive,select_key,header';
					// $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$signature] = 'pi_flexform';
					// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($signature, 'FILE:EXT:air_table/Configuration/FlexForms/Example.xml');
					
					// subtype_value_field 
					// subtypes_excludelist
					// subtypes_addlist
					// * @AirTable\FieldsExcludeList:<field1,field2,field3...> // Todo
					// * @AirTable\FieldsAddList:<field1,field2,field3...> // Todo
					$annotationFex = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FieldsExcludeList');
					$annotationFin = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FieldsAddList');
					// $configuration['types']['list']['subtype_value_field'] = 'list_type';
					$configuration['types']['list']['subtypes_excludelist'][$signature] = $annotationFex;
					$configuration['types']['list']['subtypes_addlist'][$signature] = $annotationFin;
					$configuration['types']['list']['subtypes_addlist'][$signature] .= ',pi_flexform';
					
					// EXT:flux
					// Добавляем провайдеры
					// BaseUtility::registerFluidFlexForm($namespace_ex[0].'.'.$namespace_ex[1], $class, 'tt_content', $signature);
					if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('flux') && isset($annotationFluxConfiguration))
					{
						/*
						\FluidTYPO3\Flux\Core::registerFluidFlexFormPlugin(
							$namespace_ex[0].'.'.$namespace_ex[1],
							$signature,
							'EXT:'.$extensionKey.'/Resources/Private/Templates/PagesElements/Plugins/'.$nameclass.'/Default.html',
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
						$provider = $objectManager->get(TtContentProvider::class);
						$provider->setTableName('tt_content');
						$provider->setFieldName('pi_flexform');
						$provider->setExtensionKey($namespace_ex[0].'.'.$namespace_ex[1]);
						$provider->setTemplatePathAndFilename('EXT:'.$extensionKey.'/Resources/Private/Templates/PagesElements/Plugins/'.$nameclassWithoutControllerPostfix.'/Index.html');
						$provider->setTemplateVariables([]);
						$provider->setTemplatePaths(null);
						$provider->setConfigurationSectionName('Configuration');
						$provider->setListType($signature);
						\FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
					}
				}
			// }
		}

		/************
		* FLUX
		************/
		#if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('flux')){
		#	if(count($classes)>0){
		#		\FluidTYPO3\Flux\Core::unregisterConfigurationProvider(\FluidTYPO3\Flux\Content\ContentTypeProvider::class);
		#		\FluidTYPO3\Flux\Core::registerConfigurationProvider(\Litovchenko\AirTable\Provider\TtContentConfigurationProvider::class);
		#	}
		#}
	}
}
?>
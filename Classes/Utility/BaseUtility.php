<?php
namespace Litovchenko\AirTable\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class BaseUtility {

	//*********************************************
	// КЛАССЫ
	//*********************************************
	
	public static $entityAnnotations = [
		'Extension' => [
			'_constuctor' => [
				'nametab' => 'Extension',
				'name' => 'Создать расширение (в typo3conf/ext/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'author' => 'Автор',
					'email' => 'Email',
					'extkey' => 'Ключ (sitename1 - важен регистр, только маленькие буквы без пробелов и подчеркиваний), примечание: имя ключа расширения "sitename1" должно совпадать со вторым сегментом рабочей области (Company\Sitename1).',
					'namespace' => 'Рабочая область (Company\Sitename1) - важен регистр символов (каждый сегмент с большой буквы)',
				]
			],
			'name' => 'string',
			'description' => 'string',
		],
		'BackendModule' => [
			#'_constuctor' => [
			#	'nametab' => 'Module Controller',
			#	'name' => 'Создать модуль (в Classes/Controller/Modules/):',
			#	'formelements' => [
			#		'name' => 'Название',
			#		'description' => 'Описание',
			#		'section' => 'Секция (web || file || user || help || content || tools || ext || unseen || sec_ext_myext)',
			#		'key' => 'Название контроллера (с большой буквы)'
			#	]
			#],
			'name' => 'string',
			'description' => 'string',
			'access' => 'string',
			'section' => 'string',
			'position' => 'int'
		],
		'FrontendPage' => [
			'_constuctor' => [
				'nametab' => 'Page',
				'name' => 'Создать шаблон страницы (в Classes/Controller/Pages/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя контроллера (с большой буквы)'
				]
			],
			'_isEntity' => 1,
			'_isEntityName' => 'Страница',
			'name' => 'string',
			'description' => 'string',
			'disableAllHeaderCode' => 'int',
			'nonCachedActions' => 'string',
			'urlManagerActions' => 'array',
			'ajaxActions' => 'string',
			'fieldsExcludeList' => 'string',
			'fieldsAddList' => 'string',
			'headConfiguration' => 'string',
			'fluxConfiguration' => 'string',
			'fluxFields' => 'array',
			'fluxGrids' => 'array',
			'cols' => 'string',
		],
		'FrontendContentElement' => [
			'_constuctor' => [
				'nametab' => 'Element',
				'name' => 'Создать шаблон элемента содержимого (в Classes/Controller/PagesElements/Elements/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя контроллера (с большой буквы)'
				]
			],
			'_isEntity' => 1,
			'_isEntityName' => 'Элемент контента',
			'name' => 'string',
			'description' => 'string',
			'nonCachedActions' => 'string',
			'ajaxActions' => 'string',
			'fieldsExcludeList' => 'string',
			'fieldsAddList' => 'string',
			'fluxConfiguration' => 'string',
			'fluxFields' => 'array',
			'fluxGrids' => 'array',
		],
		'FrontendContentGridelement' => [
			'_constuctor' => [
				'nametab' => 'Gridelement',
				'name' => 'Создать шаблон элемента сеточного содержимого (в Classes/Controller/PagesElements/Gridelements/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя контроллера (с большой буквы)'
				]
			],
			'_isEntity' => 1,
			'_isEntityName' => 'Элемент контента (сетка)',
			'name' => 'string',
			'description' => 'string',
			'nonCachedActions' => 'string',
			'ajaxActions' => 'string',
			'fieldsExcludeList' => 'string',
			'fieldsAddList' => 'string',
			'fluxConfiguration' => 'string',
			'fluxFields' => 'array',
			'fluxGrids' => 'array',
			'cols' => 'string'
		],
		'FrontendContentPlugin' => [
			'_constuctor' => [
				'nametab' => 'Plugin',
				'name' => 'Создать плагин (в Classes/Controller/PagesElements/Plugins/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя контроллера (с большой буквы)'
				]
			],
			'_isEntity' => 1,
			'_isEntityName' => 'Элемент контента (плагин)',
			'name' => 'string',
			'description' => 'string',
			'nonCachedActions' => 'string',
			'urlManagerActions' => 'array',
			'ajaxActions' => 'string',
			'fieldsExcludeList' => 'string',
			'fieldsAddList' => 'string',
			'fluxConfiguration' => 'string',
			'fluxFields' => 'array',
			'fluxGrids' => 'array',
		],
		'BackendModelCrud' => [
			'_constuctor' => [
				'nametab' => 'Model',
				'name' => 'Создать модель (в Classes/Domain/Model/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя модели (с большой буквы)'
				]
			],
			'keyInDatabase' => 'string',
			'name' => 'string',
			'description' => 'string',
			'defaultListTypeRender' => 'int'
		],
		'BackendModelCrudOverride' => [
			'keyInDatabase' => 'string',
			'name' => 'string',
			'description' => 'string',
			'defaultListTypeRender' => 'int'
		],
		'BackendModelExtending' => [
			'_constuctor' => [
				'nametab' => 'ExtModel',
				'name' => 'Создать расширение модели (в Classes/Domain/Model/Ext/):',
				'formelements' => [
					'basemodel' => 'Основная модель (\Litovchenko\AirTable\Domain\Model\Fal\SysFile)',
					'description' => 'Описание расширения',
					// 'key' => 'Имя модели'
				]
			],
			'name' => 'string',
			'description' => 'string',
			'defaultListTypeRender' => 'int'
		],
		'BackendField' => [
			// '_isEntity' => 1,				-> Пока не потребовалось!
			// '_isEntityName' => 'Колонка',	-> Пока не потребовалось!
			'name' => 'string',
			'description' => 'string',
			'subTypes' => 'array',
			'incEav' => 'int',
			'incMarker' => 'int',
			'_fixedFields' => [
				'uid' => [
					'type' => 'SpecialUid',
					'name' => 'ID',
					'show' => 1,
					'readOnly' => 1,
					'liveSearch' => 1,
					'doNotCheck' => 1,
					'doNotSqlAnalyze' => 1,
					'selectMinimizeInc' => 1,
					'position' => '*|main|0',
				],
				'importprocess' => [
					'type' => 'Input.PassthroughInt',
					'name' => '[IMPORT] PROCESS',
					'readOnly' => 1,
					'doNotCheck' => 1,
					'doNotSqlAnalyze' => 1,
					'selectMinimizeInc' => 1,
					'position' => '*|extended|1000',
				],
				'importolduid' => [
					'type' => 'Input.PassthroughInt',
					'name' => '[IMPORT] OLD ID',
					'readOnly' => 1,
					'doNotCheck' => 1,
					'doNotSqlAnalyze' => 1,
					'selectMinimizeInc' => 1,
					'position' => '*|extended|1000',
				],
				'lastinsertuidshash' => [
					'type' => 'Input.PassthroughInt',
					'name' => '[recInsertMultiple()] Hash',
					'readOnly' => 1,
					'doNotCheck' => 1,
					'doNotSqlAnalyze' => 1,
					'selectMinimizeInc' => 1,
					'position' => '*|extended|1000',
				]
			],
			'_propertyAnnotations' => [
				'type' => 'string',
				'name' => 'string',
				'description' => 'string',
				'readOnly' => 'string',
				'required' => 'string',
				'doNotCheck' => 'string',
				'doNotSqlAnalyze' => 'string',
				'default' => 'string',
				'dataTypeConditionUse' => 'string',
				'position' => 'userFunc:position',
				'show' => 'string',
				'onChangeReload' => 'string',
				'displayCond' => 'mixed',
				'exclude' => 'string',
				'selectMinimizeInc' => 'string',
				'eavAttr' => 'string',				// ???
				'eavAttrExt' => 'string',			// ???
				'liveSearch' => 'int',
				'placeholder' => 'string',
				
				// Правильнее было бы конечно назвать options
				'items' => 'userFunc:items', // Ввел для атрибутов "SysAttributeOption"
				
				'foreignModel' => 'userFunc:foreignModel',
				'foreignKey' => 'string',
				'foreignParentKey' => 'string',
				'foreignWhere' => 'array',
				'foreignDefaults' => 'array',
				// 'foreignInverse' => 'int'
			]
		],
		'FrontendWidget' => [
			'_constuctor' => [
				'nametab' => 'Widget',
				'name' => 'Создать виджет (в Classes/Controller/Widgets/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя контроллера (с большой буквы)'
				]
			],
			'name' => 'string',
			'description' => 'string',
			'registerArguments' => 'array',
			'nonCachedActions' => 'string',
			'ajaxActions' => 'string',
		],
		'FrontendViewHelper' => [
			'_constuctor' => [
				'nametab' => 'ViewHelper',
				'name' => 'Создать хелпер (в Classes/Controller/ViewHelpers/):',
				'formelements' => [
					'name' => 'Название',
					'description' => 'Описание',
					'key' => 'Имя класса (с большой буквы)'
				]
			],
			'name' => 'string',
			'description' => 'string',
			'registerArguments' => 'array'
		],
		#'FrontendPlaceholderVariable' => [
		#	'_constuctor' => [
		#		'nametab' => 'Placeholder (Variable) // Todo',
		#		'name' => 'Создать переменную (// Todo):',
		#		'formelements' => [
		#			// 'name' => 'Название',
		#			// 'description' => 'Описание',
		#			// 'key' => 'Имя класса (с большой буквы)'
		#		]
		#	],
		#	'name' => 'string',
		#	'description' => 'string'
		#],
		#'FrontendContentElementOverride' => [
		#	'_constuctor' => [
		#		'nametab' => 'Element (Override) // Todo',
		#		'name' => 'Переопределить стандартный элемент (// Todo):',
		#		'formelements' => [
		#			// 'name' => 'Название',
		#			// 'description' => 'Описание',
		#			// 'key' => 'Имя класса (с большой буквы)'
		#		]
		#	],
		#	'name' => 'string',
		#	'description' => 'string'
		#],
		'Hooks' => [ // Todo
			'name' => 'string',
			'description' => 'string',
			'all' => 'array',
			'onlyBackend' => 'array',
			'onlyFrontend' => 'array'
		],
		'Xclass' => [
			'name' => 'string',
			'description' => 'string',
			'object' => 'string' // baseClass
		],
	];
	
    public static function getLoaderClasses2()
    {
		// Cache
		if(isset($GLOBALS['Litovchenko.AirTable.VarCache.getLoaderClasses2'])){
			return $GLOBALS['Litovchenko.AirTable.VarCache.getLoaderClasses2'];
		}
		
		if(file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/vendor/autoload.php')){
			$composer = require $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/vendor/autoload.php';
			if (false === empty($composer)) {
			   $classes = $composer->getClassMap();
			}
		}
		
		$allClasses = [];
		$path_Typo3Ext = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext/
		$path_Typo3CoreExt = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3/sysext/'; // typo3/sysext
		
		foreach($classes as $className => $classPath){
			if(strstr($className,"Abstract")){
				continue;
			}
			$extName = $classPath;
			$extName = str_replace($path_Typo3Ext,'',$extName);
			$extName = str_replace($path_Typo3CoreExt,'',$extName);
			$extName = current(explode('/',$extName));
			if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extName)){					
				// SysRedirect-появился только в 9 версиях!
				// Для ранних версиях используется константы PATH_site, и т.д.
				if (version_compare(TYPO3_version, '9.0.0', '<') && $className == "Litovchenko\AirTable\Domain\Model\SysRedirect") {
					continue;
				}
				if ( class_exists($className) ) {
					if ( isset($className::$TYPO3) ) {
						$thisIs = $className::$TYPO3['thisIs'];
						if ( isset(self::$entityAnnotations[$thisIs]) ) {
							$allClasses[$thisIs][] = $className;
							$allClassesPath[$className] = $classPath;
						}
					}
				}
			}
		}
		
		$GLOBALS['Litovchenko.AirTable.VarCache.getLoaderClasses2'] = $allClasses;
		$GLOBALS['Litovchenko.AirTable.VarCache.getLoaderClassesPath2'] = $allClassesPath;
		return $allClasses;
    }

	//*********************************************
	// КЛАСС (АННОТАЦИИ)
	//*********************************************

	// Получить значение аннотации класса
	public static function getClassAnnotationValueNew($class, $keyAnnotation = '', $compare = false)
	{
		// Перевод на статическую переменную static $TYPO3 = [];
		if(property_exists($class,'TYPO3') && $compare == false){
			
			if($keyAnnotation == 'AirTable\Label'){
				$keyAnnotationVarible = 'Name';
				
			} else {
				$temp = explode('\\',$keyAnnotation);
				$keyAnnotationVarible = $temp[1];		// * 1 уровень вложенности @AirTable\Label:<--->
				$keyAnnotationVarible2 = $temp[2];		// * 2 уровень вложенности @AirTable\RegisterArguments\---:<--->
				
			}
			
			if(isset($class::$TYPO3['thisIs'])){
				$thisIs = $class::$TYPO3['thisIs']; 
				if(isset(self::$entityAnnotations[$thisIs])){
					$allowedAnnotations = self::$entityAnnotations[$thisIs];
					$keyAnnotationVarible = lcfirst($keyAnnotationVarible);
					if(array_key_exists($keyAnnotationVarible, $allowedAnnotations)){
						if(!isset($class::$TYPO3[$keyAnnotationVarible])){
							return '';
						}
						
						// * 2 уровень вложенности @AirTable\RegisterArguments\---:<--->
						if($keyAnnotationVarible2 != ''){
							$compare_1 = $class::$TYPO3[$keyAnnotationVarible][$keyAnnotationVarible2];
						
						// * 1 уровень вложенности @AirTable\Label:<--->
						} else {
							$compare_1 = $class::$TYPO3[$keyAnnotationVarible];
						}
						
						// Обработчики:
						$handler = self::$entityAnnotations[$thisIs][$keyAnnotationVarible];
						switch($handler)
						{
							#########################
							# string
							#########################
							case 'string': 
								$compare_1 = trim($compare_1); 
							break;
							
							#########################
							# int
							#########################
							case 'int': 
								$compare_1 = intval($compare_1); 
							break;
							
							#########################
							# array
							#########################
							case 'array': 
								if(!is_array($compare_1)){
									$compare_1 = explode(',',$compare_1); 
								}
							break;
						}
						
						return $compare_1;
					} else {
						// throw new \UnexpectedValueException('Invalid data');
						throw new \Exception('Invalid data 1: ['.$class.'] - ['.$keyAnnotationVarible.'] not allowed! '.print_r($allowedAnnotations,true));
					}
				} else {
					// throw new \UnexpectedValueException('Invalid data');
					throw new \Exception('Invalid data 2/2: ['.$class.'] - [thisIs: '.$thisIs.'] not allowed!');
				}
			} else {
				// throw new \UnexpectedValueException('Invalid data');
				throw new \Exception('Invalid data 2/1: ['.$class.'] - argument [thisIs] not found!');
			}
		}
		
		return '';
	}
	
	// Проверить есть ли добавленный в класс треит
	// hasClassTrait
	public static function hasSpecialField($class,$field = ''){
		// Перевод на статическую переменную static $TYPO3 = [];
		if(property_exists($class,'TYPO3')){
			if(in_array($field,$class::$TYPO3['baseFields'])){
				return true;
			}
			if(array_key_exists($field,$class::$TYPO3['baseFields'])){
				return true;
			}
		}
		return false;
	}
	
	//*********************************************
	// ПЕРЕМЕННЫЕ КЛАССА (АННОТАЦИИ)
	//*********************************************
	
	// Получить список всех переменных
	public static function getClassAllFieldsNew($class, $onlyAttr = false)
	{
		$allFields = [];
		
		// Перевод на статическую переменную static $TYPO3 = [];
		foreach(self::$entityAnnotations['BackendField']['_fixedFields'] as $k => $v){
			$allFields[] = $k;
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		if(is_array($class::$TYPO3['baseFields'])){
			foreach($class::$TYPO3['baseFields'] as $k => $v){
				if(is_array($v)){
					$allFields[] = $k;
				} else {
					$allFields[] = $v;
				}
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		if(is_array($class::$TYPO3['dataFields'])){
			foreach($class::$TYPO3['dataFields'] as $k => $v){
				$allFields[] = $k;
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		if(is_array($class::$TYPO3['mediaFields'])){
			foreach($class::$TYPO3['mediaFields'] as $k => $v){
				$allFields[] = $k;
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		if(is_array($class::$TYPO3['relationalFields'])){
			foreach($class::$TYPO3['relationalFields'] as $k => $v){
				$allFields[] = $k;
			}
		}
		
		// Делаем сортировку
		$resultFields = [];
		$resultFieldsPreSort = [];
		if(method_exists($class,'baseTabs')){
			$userTabs = $class::baseTabs();
		} else {
			$userTabs['default'] = 'Main';
		}
		foreach($allFields as $k => $v){
			// Только атрибуты
			if($onlyAttr == true){
				if(!preg_match('/^attr_/is',$v)){
					continue;
				}
			}
			
			$annotationPosition = BaseUtility::getClassFieldAnnotationValueNew($class,$v,'AirTable\Field\Position');
			if(is_array($annotationPosition)){
					// $structure = array_key_first($annotationPosition); // Определяем по первому элементу
					$keys = array_keys($annotationPosition); // <= PHP 7.3
					$structure = $keys[0];
					
					$tab = current(explode(',',$annotationPosition[$structure])); // Определяем по первому элементу
					$num = end(explode(',',$annotationPosition[$structure])); // Определяем по первому элементу
				} else {
					$annotationField = BaseUtility::getClassFieldAnnotationValueNew($class,$v,'AirTable\Field');
					$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
					if(class_exists($fieldClass) && $fieldClass::TABDEFAULT == 8) {
						$tab = 'media';
					} elseif(class_exists($fieldClass) && $fieldClass::TABDEFAULT == 9) {
						$tab = 'rels';
					} else {
						$tab = 'props';
					}
					$num = $fCount;
				}
				$fCount++;
				$tab = array_search($tab,array_keys($userTabs)); // Переводим "таб" в "№-номер"
				$resultFieldsPreSort[intval($tab)][intval($num)][] = $v;
		}
		
		ksort($resultFieldsPreSort);
		foreach($resultFieldsPreSort as $k => $v){
			ksort($resultFieldsPreSort[$k]);
			foreach($resultFieldsPreSort[$k] as $k2 => $v2){
				foreach($resultFieldsPreSort[$k][$k2] as $k3 => $v3){
					$resultFields[] = $v3;
				}
			}
		}
		
		return $resultFields;
	}
	
	// Получить значение аннотации переменной в классе
	public static function getClassFieldAnnotationValueNew($class, $property, $keyAnnotation = '', $subKeyAnnotation = false, $compare = false)
	{
		// Перевод на статическую переменную static $TYPO3 = [];
		// Добавляем фиксированные поля...
		if(array_key_exists($property,self::$entityAnnotations['BackendField']['_fixedFields'])){
			if(!isset($class::$TYPO3['fields'][$property])){
				$conf = self::$entityAnnotations['BackendField']['_fixedFields'][$property];
				$class::$TYPO3['fields'][$property] = $conf;
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		// Добавляем специальные поля...
		// Вариант когда значение массива
		if(property_exists($class,'TYPO3') && in_array($property,$class::$TYPO3['baseFields'])){
			if(!isset($class::$TYPO3['fields'][$property])){
				$specialFieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\Special'.str_replace('_','',$property);
				$specialFieldClass = strtolower($specialFieldClass);
				if(!class_exists($specialFieldClass)){
					return '';
				}
				#if(!class_exists($specialFieldClass)){
				#	throw new \Exception('Invalid data: ['.$specialFieldClass.'] not found!');
				#}
				$conf = $specialFieldClass::$TYPO3['_fields'][$property];
				if(is_array($conf)){
					$class::$TYPO3['fields'][$property] = $conf;
				}
			}
		}

		// Перевод на статическую переменную static $TYPO3 = [];
		// Добавляем специальные поля...
		// Вариант когда ключ массива с доп. настройками
		if(property_exists($class,'TYPO3') && array_key_exists($property,$class::$TYPO3['baseFields'])){
			if(!isset($class::$TYPO3['fields'][$property])){
				$specialFieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\Special'.str_replace('_','',$property);
				$specialFieldClass = strtolower($specialFieldClass);
				if(!class_exists($specialFieldClass)){
					return '';
				}
				#if(!class_exists($specialFieldClass)){
				#	throw new \Exception('Invalid data: ['.$specialFieldClass.'] not found!');
				#}
				$fieldConf = $specialFieldClass::$TYPO3['_fields'][$property];
				$userConf = $class::$TYPO3['baseFields'][$property];
				$conf = array_merge($fieldConf,$userConf);
				if(is_array($conf)){
					$class::$TYPO3['fields'][$property] = $conf;
				}
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		// Добавляем обычные поля...
		if(property_exists($class,'TYPO3') && array_key_exists($property,$class::$TYPO3['dataFields'])){
			if(!isset($class::$TYPO3['fields'][$property])){
				$conf = $class::$TYPO3['dataFields'][$property];
				if(is_array($conf)){
					$class::$TYPO3['fields'][$property] = $conf;
				}
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		// Добавляем обычные поля...
		if(property_exists($class,'TYPO3') && array_key_exists($property,$class::$TYPO3['mediaFields'])){
			if(!isset($class::$TYPO3['fields'][$property])){
				$conf = $class::$TYPO3['mediaFields'][$property];
				if(is_array($conf)){
					$class::$TYPO3['fields'][$property] = $conf;
				}
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		// Добавляем обычные поля...
		if(property_exists($class,'TYPO3') && array_key_exists($property,$class::$TYPO3['relationalFields'])){
			if(!isset($class::$TYPO3['fields'][$property])){
				$conf = $class::$TYPO3['relationalFields'][$property];
				if(is_array($conf)){
					$class::$TYPO3['fields'][$property] = $conf;
				}
			}
		}
		
		// Перевод на статическую переменную static $TYPO3 = [];
		if(property_exists($class,'TYPO3') && isset($class::$TYPO3['fields'][$property]) && $compare == false){
			
			if($keyAnnotation == 'AirTable\Field'){
				$keyAnnotationVarible = 'Type';
				
			}elseif($keyAnnotation == 'AirTable\Field\Label'){
				$keyAnnotationVarible = 'Name';
				
			} else {
				$temp = explode('\\',$keyAnnotation);
				$keyAnnotationVarible = $temp[2];		// * 1 уровень вложенности @AirTable\Field\Label:<--->
				$keyAnnotationVarible2 = $temp[3];		// * 2 уровень вложенности @AirTable\Field\Items\---:<--->
				
			}
			
			$keyAnnotationVarible = lcfirst($keyAnnotationVarible);
			
			// * @AirTable\Field:<Text.Rte>
			if(strstr($class::$TYPO3['fields'][$property]['type'],'.')){
				$temp = explode(".",$class::$TYPO3['fields'][$property]['type']);
				$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$temp[0];
			} else {
				$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$class::$TYPO3['fields'][$property]['type'];
			}
			
			$allowedAnnotationsEntity = self::$entityAnnotations['BackendField']['_propertyAnnotations'];
			$allowedAnnotationsField = $fieldClass::$TYPO3['_propertyAnnotations'];
			if(is_array($allowedAnnotationsField)){
				$allowedAnnotations = $allowedAnnotationsEntity+$allowedAnnotationsField;
			} else {
				$allowedAnnotations = $allowedAnnotationsEntity;
			}
			
			if(array_key_exists($keyAnnotationVarible,$allowedAnnotations)){
				
				if(!isset($class::$TYPO3['fields'][$property][$keyAnnotationVarible])){
					return '';
				}
						
				// * 2 уровень вложенности @AirTable\RegisterArguments\---:<--->
				if($keyAnnotationVarible2 != ''){
					$compare_1 = $class::$TYPO3['fields'][$property][$keyAnnotationVarible][$keyAnnotationVarible2];
				
				// * 1 уровень вложенности @AirTable\Label:<--->
				} else {
					$compare_1 = $class::$TYPO3['fields'][$property][$keyAnnotationVarible];
				}
						
				// Обработчики:
				$handler = $allowedAnnotations[$keyAnnotationVarible];
				switch($handler)
				{
					#########################
					# userFunc:position
					#########################
					case 'userFunc:position':
						if(is_array($compare_1)){
							// 'position' => [
							// 		'Marker.Input|main|5',
							// 		'Marker.Text|main|5',
							// 		'Marker.Media_1|main|5'
							// ],
							$temp = [];
							foreach($compare_1 as $kEl => $vEl){
								// 'position' => '*|local|2'
								$temp2 = explode('|',$vEl);
								$temp[$temp2[0]] = $temp2[1].','.intval($temp2[2]);
							}
							$compare_1 = $temp;
							
						} else {
							// 'position' => '*|local|2'
							$temp = explode('|',$compare_1);
							$compare_1 = [];
							$compare_1[$temp[0]] = $temp[1].','.intval($temp[2]);
						}
					break;
					
					#########################
					# userFunc:foreignModel
					#########################
					case 'userFunc:foreignModel':
						if($compare_1 == 'current') {
							$compare_1 = $class;
						} elseif($compare_1 == 'category') {
							$compare_1 = $class.'Category';
						} else {
							$compare_1 = trim($compare_1); 
						}
					break;
					
					#########################
					# userFunc:items
					#########################
					case 'userFunc:items':
						$tempItems = [];
						foreach($compare_1 as $itemKey => $itemValue){
							$tempItems[$itemKey] = [0=>$itemValue,1=>$itemKey];
						}
						$compare_1 = $tempItems;
					break;
					
					#########################
					# string
					#########################
					case 'string': 
						$compare_1 = trim($compare_1); 
					break;
					
					#########################
					# int
					#########################
					case 'int': 
						$compare_1 = intval($compare_1); 
					break;
					
					#########################
					# array
					#########################
					case 'array': 
						if(!is_array($compare_1)){
							$compare_1 = explode(',',$compare_1); 
						}
					break;
					
					#########################
					# mixed
					#########################
					case 'mixed': 
						// $compare_1 = $compare_1; 
					break;
				}
				
				// * @AirTable\Field:<Text.Rte>
				if(strstr($compare_1,'.') && $subKeyAnnotation == true && $keyAnnotationVarible == 'type'){
					$compare_1 = explode(".",$compare_1);
					$compare_1 = trim($compare_1[1]);
				} elseif(strstr($compare_1,'.') && $keyAnnotationVarible == 'type') {
					$compare_1 = explode(".",$compare_1);
					$compare_1 = trim($compare_1[0]);
				}
				
				return $compare_1;
				
			} else {
				// throw new \UnexpectedValueException('Invalid data');
				throw new \Exception('Invalid data 1: ['.$class.','.$property.'] - ['.$keyAnnotationVarible.'] not allowed! '.print_r($allowedAnnotationsField,true));
			}
		}
		
		return '';
	}
	
	// Получить путь к классу
	public static function getClassPath($class){
		$r = new \ReflectionClass($class);
		return $r->getFileName();
	}
	
	// Получить путь к классу (только папка)
	public static function getClassDirPath($class){
		$r = new \ReflectionClass($class);
		$segments = explode('/',$r->getFileName());
		unset($segments[count($segments)-1]);
		return implode('/',$segments).'/';
	}
	
	// Получить название расширения из пути класса
	public static function getExtNameFromClassPath($class){
		$r = new \ReflectionClass($class);
		$fPath = $r->getFileName();
		$temp = explode('Classes/',$fPath);
		$temp = explode('/',$temp[0]);
		$extName = $temp[count($temp)-2];
		return $extName;
	}
	
	// Получить название папки (подмодели) из пути класса
	public static function getSubDomainNameFromClassPath($class){
		$r = new \ReflectionClass($class);
		$fPath = $r->getFileName();
		$temp = explode('Classes/Domain/Model/',$fPath);
		$temp = explode('/',$temp[1]);
		if(count($temp) > 1){
			$subDomain = $temp[0];
		} else {
			// $subDomain = 'Root';
		}
		return $subDomain;
	}
	
	// Получить значение из _GET, либо установить по умолчанию с сохранением в BE_USER->UC
	public static function _GETuc($key = '', $default = '', $firstKey = ''){
		if($firstKey == ''){
			$firstKey = $GLOBALS['_GET']['route'].'_GET';
		}
		$firstKey = md5($firstKey);
		
		if(isset($GLOBALS['_GET'][$key])) {
			$GLOBALS['BE_USER']->uc['air_table']['_GETuc'][$firstKey][$key] = $GLOBALS['_GET'][$key];
			$GLOBALS['BE_USER']->writeUC();
			$val = $GLOBALS['_GET'][$key];
			
		} elseif ($GLOBALS['BE_USER']->uc['air_table']['_GETuc'][$firstKey][$key] != '') {
			$val = $GLOBALS['BE_USER']->uc['air_table']['_GETuc'][$firstKey][$key];
			
		} else {
			$val = $default;
			
		}
		
		// Fix bug - после удаления расширения, будет ошибка если была
		// открыта папка с таблицами удаленного расширения, которые были записаны в "$GLOBALS['BE_USER']->uc"
		if($key == 'paramExt'){
			// use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
			if(!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($val)){
				return $default;
			}
		}
		
		return $val;
	}

	// Получить значение из _POST, либо установить по умолчанию с сохранением в BE_USER->UC
	// $dependency -> это зависимое значение формы, по которому определяется отправлена ли она?
	public static function _POSTuc($dependency = false, $key = '', $default = ''){
		
		// Зависимость от другого параметра
		if($dependency == false){
			return null;
		}
		
		// Базовый маршрут
		// $firstKey = $GLOBALS['_GET']['route'].'_POST';
		$firstKey  = '';
		$firstKey .= $GLOBALS['_GET']['paramClass'];
		$firstKey .= $GLOBALS['_GET']['recordSelection']; // Если делается выбор
		$firstKey .= $GLOBALS['_GET']['recordSelectionFieldname']; // Если делается выбор
		
		// tx_data
		$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
		if(!empty($dataType) && preg_match('/^form/is',$key)){
			$firstKey .= $dataType;
		}
		
		$firstKey = md5($firstKey);
		
		#if($dependency == 'form0Apply'){
		#	print "<pre>";
		#	print_r($GLOBALS['_POST']);
		#	print "</pre>";
		#}
		
		/////////////////////////////////////////////////////////////////////
		// Новый вариант (с исправленным багом)
		/////////////////////////////////////////////////////////////////////
		if($GLOBALS['_POST'][$dependency] == 1) {
			$GLOBALS['BE_USER']->uc['air_table']['_POSTuc'][$firstKey][$key] = $GLOBALS['_POST'][$key];
			$GLOBALS['BE_USER']->writeUC();
			return $GLOBALS['_POST'][$key];
			
		} elseif($GLOBALS['_POST'][$dependency] == -1) {
			$GLOBALS['BE_USER']->uc['air_table']['_POSTuc'][$firstKey][$key] = [];
			$GLOBALS['BE_USER']->writeUC();
			return $default;
			
		} elseif ($GLOBALS['BE_USER']->uc['air_table']['_POSTuc'][$firstKey][$dependency] == 1) {
			return $GLOBALS['BE_USER']->uc['air_table']['_POSTuc'][$firstKey][$key];
			
		} else {
			return $default;
			
		}
	}
	
	// Сгенерировать название таблицы из модели
	public static function getTableNameFromClass($class, $short = 0, $thisIsArg = null)
	{
		// Cache
		# if(isset($GLOBALS['Litovchenko.AirTable.VarCache.getTableNameFromClass'][$class][$short])){
		# 	return $GLOBALS['Litovchenko.AirTable.VarCache.getTableNameFromClass'][$class][$short];
		# }
		
		#if(class_exists($class) && property_exists($class,'TYPO3')){
		#	$thisIs = $class::$TYPO3['thisIs'];
		#} elseif($thisIsArg != null) {
		#	$thisIs = $thisIsArg;
		#} else {
		#	return '';
		#}
		
		if((class_exists($class) && property_exists($class,'TYPO3')) || $thisIsArg != null){
			
			// Если BackendField
			if($thisIsArg == null && $class::$TYPO3['thisIs'] == 'BackendField') {
				return strtolower(self::getClassNameWithoutNamespace($class));
			
			// Если это расширение модели
			} elseif($thisIsArg == null && strstr($class,'Domain\Model\Ext\Ext')) {
				$class_parents = class_parents($class);
				$table = self::getTableNameFromClass(current($class_parents),$short);
				return $table;
			
			// Если явно задан ключ таблицы
			} elseif($thisIsArg == null && isset($class::$TYPO3['keyInDatabase'])) {
				$table = $class::$TYPO3['keyInDatabase'];
				return $table;
			
			// Контроллеры
			} elseif($thisIsArg == null && preg_match('/^Frontend/is',$class::$TYPO3['thisIs'])){
				
				$temp = explode('\\',$class);
				$extName = $temp[1];
				$controllerName = end($temp);
				$controllerName = preg_replace('/Controller$/is','',$controllerName);
				
				switch($class::$TYPO3['thisIs']){
					case 'FrontendPage': 				$signature = $extName.'_pages_'.$controllerName; break;
					case 'FrontendContentElement': 		$signature = $extName.'_elements_'.$controllerName; break;
					case 'FrontendContentGridelement':	$signature = $extName.'_gridelements_'.$controllerName; break;
					case 'FrontendContentPlugin': 		$signature = $extName.'_plugins_'.$controllerName; break;
					case 'FrontendWidget': 				$signature = $extName.'_widgets_'.$controllerName; break;
				}
				
				$signature = strtolower($signature);
				return $signature;
			
			// Контроллеры (дубликат)
			} elseif($thisIsArg != null){
				
				$temp = explode('\\',$class);
				$extName = $temp[1];
				$controllerName = end($temp);
				$controllerName = preg_replace('/Controller$/is','',$controllerName);
				
				switch($thisIsArg){
					case 'FrontendPage': 				$signature = $extName.'_pages_'.$controllerName; break;
					case 'FrontendContentElement': 		$signature = $extName.'_elements_'.$controllerName; break;
					case 'FrontendContentGridelement':	$signature = $extName.'_gridelements_'.$controllerName; break;
					case 'FrontendContentPlugin': 		$signature = $extName.'_plugins_'.$controllerName; break;
					case 'FrontendWidget': 				$signature = $extName.'_widgets_'.$controllerName; break;
				}
				
				$signature = strtolower($signature);
				return $signature;
			
			// По умолчанию используем DataMap
			} else {
				
				// Если требуется сокращенный вариант
				if($short == 1) {
					return strtolower(self::getClassNameWithoutNamespace($class));
				}	
				
				$temp = explode('\\',$class);
				$extName = $temp[1];
				$modelName = end($temp);
				
				if(self::isModelCategoryForAnotherModel($class)) {
					$table = $extName.'_dm_'.$modelName;
					$table = preg_replace('/category$/is','_category',$table); // меняем "_category" постфикс для категорий
					$table = strtolower($table);
					
				} else {
					$table = $extName.'_dm_'.$modelName;
					$table = strtolower($table);
					
				}
			}
			$table = 'tx_'.$table;
			return $table;
		}
		
		return '';
	}
	
	// Получить название класса без Namespace
	public static function getClassNameWithoutNamespace($class){
		return end(explode('\\',$class));
	}

	// Функция првоеряет является ли модель категорией (категоризация)
	public static function isModelCategoryForAnotherModel($class = ''){
		$modelWithoutPostfixCategory = preg_replace('/(.*?)Category$/s', '\\1', $class);
		if(preg_match('/(.*?)Category$/s',$class) && class_exists($modelWithoutPostfixCategory)){
			return true;
		}
		return false;
	}
	
	// Функция проверяет может ли запись модели состоять в одной категории (категоризация)
	public static function isModelSupportRowCategory($class = ''){
		$modelWithCategory = $class.'Category';
		if(!preg_match('/(.*?)Category$/s', $class) && class_exists($modelWithCategory)){
			if(self::hasSpecialField($class,'propref_category') == true){
				return true;
			}
			#$a = new \ReflectionClass($modelWithCategory);
			#$file_name = basename($a->getFileName());
			#if(!preg_match('/(.*?).mm.php$/s', $file_name)){
			#	return true;
			#}
		}
		return false;
	}

	// Функция проверяет может ли запись модели состоять в нескольких категориях (категоризация)
	public static function isModelSupportRowsCategories($class = ''){
		$modelWithCategory = $class.'Category';
		if(!preg_match('/(.*?)Category$/s', $class) && class_exists($modelWithCategory)){
			if(self::hasSpecialField($class,'propref_categories') == true){
				return true;
			}
			#$a = new \ReflectionClass($modelWithCategory);
			#$file_name = basename($a->getFileName());
			#if(preg_match('/(.*?).mm.php$/s', $file_name)){
			#	return true;
			#}
		}
		return false;
	}

	// Функция получает список всех колонок, которые есть в структуре
	public static function getAllColumnsFromType($table, $type){
		$fields = [];
		#$table = 'pages';
		#$type = 1;
		$list = $GLOBALS['TCA'][$table]['types'][$type]['showitem'];
		$list = explode(',', $list);
		foreach($list as $k => $v){
			$temp = explode(';',$v);
			switch(trim($temp[0])){
				case '--div--': 
					continue;
				break;
				case '--palette--':
					// [0] --palette--
					// [1] LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.visibility
					// [2] visibility
					$tempPalette = explode(',',$GLOBALS['TCA'][$table]['palettes'][$temp[2]]['showitem']);
					foreach($tempPalette as $kPalette => $vPalette){
						$temp2 = explode(';',$vPalette);
						if(trim($temp2[0]) != '--linebreak--'){
							if(trim($temp2[0]) != null){
								$fields[] = trim($temp2[0]);
							}
						}
					}
				break;
				default:
					if(preg_match('/tab_(.*)_start/is',trim($temp[0]))){
						continue;
					}
					if(preg_match('/tab_(.*)_end/is',trim($temp[0]))){
						continue;
					}
					if(trim($temp[0]) != null){
						$fields[] = trim($temp[0]);
					}
				break;
			}
		}
		//LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.editlock_formlabel ???
		// print "<pre>";
		// print_r($fields);
		// exit();
		return $fields;
	}
	
	// Функция создает (либо перезаписывае) содержимое файла
	public static function fileReWrite($path, $content){
		if($path != null){
			$handleConstant = fopen($path, 'w+');
			fwrite($handleConstant, $content);
			fclose($handleConstant);
		}
	}
	
	// Функция создает (либо перезаписывае) содержимое файла
	public static function fileWrite($path, $content){
		if($path != null){
			$handleConstant = fopen($path, 'a+');
			fwrite($handleConstant, $content);
			fclose($handleConstant);
		}
	}
	
	// Получить значение Item
	public static function getTcaFieldItem($table, $field, $item){
		foreach($GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] as $k => $i){
			if($i[1] == $item){
				return $i;
			}
		}
	}
	
	// Функции доступа
	// https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/BackendUserObject/Index.html
	// $calcPerms = $backendUser->calcPerms(BackendUtility::readPageAccess($rec['pid'], $backendUser->getPagePermsClause(Permission::PAGE_SHOW)));
	// $localCalcPerms = $backendUser->calcPerms(BackendUtility::getRecord('pages', $rec['uid']));
	// $backendUser->check('non_exclude_fields', $foreignTable . ':' . $hiddenField));

	public static function BeUserAccessTableSelect($table = 'tt_content'){
		return $GLOBALS['BE_USER']->check('tables_select', $table);
	}
	
	public static function BeUserAccessTableModify($table = 'tt_content'){
		return $GLOBALS['BE_USER']->check('tables_modify', $table);
	}
	
	public static function BeUserAccessModule($module = 'web_list'){
		return $GLOBALS['BE_USER']->check('modules', $module);
	}
	
	// Функция вывода Дебага
	public static function debugInController(){
		// https://somethingphp.com/debugging-typo3/
		#\TYPO3\CMS\Core\Utility\DebugUtility::debug('VAR','HEADER','Debug');
		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array(1=>432));
		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('VAR', 'FormObject:');
		$userDebug = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/UserDebug.php';
		if(file_exists($userDebug)){
			#try {
				ob_start();
					$array = require $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/UserDebug.php';
					$content = ob_get_contents();
				ob_end_clean();
				
				#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content,'1df');
			#} catch (Exception $e) {
			#
			#}
			$result = [];
			#if(!empty($content)){
				 /*
				 \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(
					$variable,
					$title = 'Institutions', 
					$maxDepth = 8,
					$plainText = FALSE,
					$ansiColors = TRUE,
					$return = FALSE,
					$blacklistedClassNames = NULL,
					$blacklistedPropertyNames = NULL
				 );
				 */
				$result[0] = \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(
					$content,
					'Результат вывода (echo())', 
					100,
					FALSE,
					TRUE,
					TRUE
				 );
			#}
			#if(is_array($array) || !empty($array)){
				 /*
				 \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(
					$variable,
					$title = 'Institutions', 
					$maxDepth = 8,
					$plainText = FALSE,
					$ansiColors = TRUE,
					$return = FALSE,
					$blacklistedClassNames = NULL,
					$blacklistedPropertyNames = NULL
				 );
				 */
				$result[1] = \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(
					$array,
					'Результат выполнения кода (return())', 
					100,
					FALSE,
					TRUE,
					TRUE
				 );
			#}
			return implode('',$result);
		} else {
			return '<pre style="background: #212121; color: #eff; border: 0; border-top-left-radius: 0px; padding: 15px !important;">File [typo3conf/UserDebug.php] not found!</pre>';
		}
	}
	
	// Функция получения информации из TCA-массива
	// Todo
	public static function TCAvalue($table, $type = 'label'){
		switch($type){
			case 'label':
				return $GLOBALS['TCA'][$table]['ctrl']['label'];
			break;
		}
	}
	
	// Получить Backend-Url
	public static function getModuleUrl($moduleName, $urlParameters = []){
		
		// Fix bug loader (без этого параметра лоадер постоянно зависал)
		// ???
		if(TYPO3_MODE === 'FE'){
			if($moduleName == 'record_edit' || $moduleName == 'new_content_element'){
				$urlParameters['noView'] = 0; // Fix bug loader (без этого параметра лоадер постоянно зависал)
				$urlParameters['feEdit'] = 1; // Fix bug loader (без этого параметра лоадер постоянно зависал)
			}
		}
		
		// VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= VersionNumberUtility::convertVersionNumberToInteger('8.6') ? 'Resources/Private/Language/' : '';
		// v9
		if (version_compare(TYPO3_version, '9.0.0', '<')) {
			$backendLink = \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl($moduleName, $urlParameters);
			return $backendLink;
		
		// v10
		} else {
			$uriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
			$backendLink = $uriBuilder->buildUriFromRoute($moduleName, $urlParameters);
			return $backendLink;
		}
	}
	
	/**
	 * Return a number only hash
	 * https://stackoverflow.com/a/23679870/175071
	 * @param $str
	 * @param null $len
	 * @return number
	 */
	public function numHash($str, $len=null)
	{
		if(!isset($str)){
			return 0;
		}
		$binhash = md5($str, true);
		$numhash = unpack('N2', $binhash);
		$hash = $numhash[1] . $numhash[2];
		if($len && is_int($len)) {
			$hash = substr($hash, 0, $len);
		}
		return $hash;
	}
	
	/**
	 * @return true || false
	 */
	public function isPageVp()
	{
		#$_GET = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$routeArguments = $GLOBALS['TSFE']->pageArguments->getRouteArguments(); // getPageId
		if(!empty($routeArguments)){
			return true;
		}
		return false;
	}
	
	/**
	 * @return array
			// Проверяем сущестование файла RTypes.php
			$classPath = $GLOBALS['Litovchenko.AirTable.VarCache.getLoaderClassesPath2'][$class];
			$RTypesTxt = preg_replace('/.php$/is','.RTypes.txt',$classPath);
			
			// Пользовательские типы (из файла)...
			if( file_exists($RTypesTxt) ) {
				return BaseUtility::txtRTypes($RTypesTxt);
	 */
	public static function txtRTypes($pathFile = '')
	{
		$divCounter = 10;
		$items = [];
		$RTypes = file_get_contents($pathFile);
		$RTypes = explode(chr(10),$RTypes);
		foreach($RTypes as $k => $v){
			if(preg_match('/^\#/is',$v)){
				continue;
			}
			$temp = explode(';',$v);
			$item_key = trim($temp[0]);
			$item_value = trim($temp[1]);
			if(strstr($item_key,'--div--')){
				$items[str_replace('--div','--div'.$divCounter,$item_key)] = $item_value;
				$divCounter += 10;
			} else {
				$items[$item_key] = $item_value;
			}
		}
		return $items;
	}
	
	/**
	 * @return array
			// Проверяем сущестование файла Tabs.php
			$classPath = $GLOBALS['Litovchenko.AirTable.VarCache.getLoaderClassesPath2'][$class];
			$TabsTxt = preg_replace('/.php$/is','.Tabs.txt',$classPath);
			
			// Пользовательские табы (из файла)...
			if( file_exists($TabsTxt) ) {
				$tabs += BaseUtility::txtTabs($TabsTxt);
	 */
	public static function txtTabs($pathFile = '')
	{
		$items = [];
		$tabs = file_get_contents($pathFile);
		$tabs = explode(chr(10),$tabs);
		foreach($tabs as $k => $v){
			if(preg_match('/^\#/is',$v)){
				continue;
			}
			$temp = explode(';',$v);
			$item_key = trim($temp[0]);
			$item_value = trim($temp[1]);
			$items[$item_key] = $item_value;
		}
		return $items;
	}
	

	
    /**
     * @param mixed $extensionKey The extension key which registered this FlexForm
     * @param mixed $controllerName
     * @param mixed $tableName
     * @param mixed $pluginSignature The plugin signature this FlexForm belongs to
     * @param mixed $variables Optional array of variables to pass to Fluid template
     * @return ProviderInterface
     */
    public static function registerFluidFlexForm(
        $extensionKey,
        $controllerName,
        $tableName,
        $pluginSignature,
        $variables = [] // -> для чего???
    ) {
		if(!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('flux')){
			return '';
		}
		
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
		*/
		
		/*
			Делаем "unregisterConfigurationProvider" двух провайдеров (для страниц, и для контента)
			После регистрируем свои два провайдера - так сделали обработку как приоритета наших ключей
		*/
		
        /** @var ObjectManagerInterface $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var $provider ProviderInterface */
		switch($tableName){
			case 'pages':
				\FluidTYPO3\Flux\Core::unregisterConfigurationProvider(\FluidTYPO3\Flux\Provider\PageProvider::class); // Так поменяли приоритет
				\FluidTYPO3\Flux\Core::unregisterConfigurationProvider(\FluidTYPO3\Flux\Provider\SubPageProvider::class);
				$provider = $objectManager->get(\Litovchenko\AirTable\Provider\PagesConfigurationProvider::class);
			break;
			case 'tt_content':
				\FluidTYPO3\Flux\Core::unregisterConfigurationProvider(\FluidTYPO3\Flux\Content\ContentTypeProvider::class); // Так поменяли приоритет
				$provider = $objectManager->get(\Litovchenko\AirTable\Provider\TtContentConfigurationProvider::class);
			break;
		}
		
		$provider->setFullControllerName($controllerName);
        $provider->setExtensionKey($extensionKey);
        $provider->setTableName($tableName);
		
		$controllerName = end(explode('\Controller\\',$controllerName));
		$controllerName = preg_replace('/Controller$/is','',$controllerName);
		
		switch($tableName){
			case 'pages':
				$provider->setFieldName('tx_fed_page_flexform');
				$provider->setControllerName($controllerName);
				$provider->setControllerAction('index');
				$provider->setPageSignature($pluginSignature);
			break; 
			case 'tt_content':
				$provider->setControllerName($controllerName); // setControllerAction
				$provider->setFieldName('pi_flexform');
				if(strstr($pluginSignature,'_elements_')) {
					$provider->setContentObjectType($pluginSignature);
					$provider->setControllerAction('index');
					
				} elseif(strstr($pluginSignature,'_gridelements_')) {
					$provider->setContentObjectType($pluginSignature);
					$provider->setControllerAction('index');
					
				} elseif(strstr($pluginSignature,'_plugins_')) {
					$provider->setContentObjectType('list');
					$provider->setListType($pluginSignature);
					$provider->setControllerAction('index');
				}
			break; 
			default: 
				$provider->setFieldName('prop_flexform');
			break;
		}
        // $provider->setTemplateVariables($variables); // -> для чего???
        \FluidTYPO3\Flux\Core::registerConfigurationProvider($provider);
		// \FluidTYPO3\Flux\Core::registerConfigurationProvider(\FluidTYPO3\Flux\Provider\PageProvider::class); // Так поменяли приоритет
		// \FluidTYPO3\Flux\Core::registerConfigurationProvider(\FluidTYPO3\Flux\Content\ContentTypeProvider::class); // Так поменяли приоритет
        return $provider;
    }
	


	// Бесконечные
	// Разрешенные типы для вложенных?
	public static function readFluxGrids($grid, $fluxGrids = [])
	{
		// $values = $this->getFlexFormValues($row);
		foreach($fluxGrids as $kRow => $vRow){
			$arexRow = explode('|',$kRow);
			if($arexRow[0] == 'Row'){
				$gridRow = $grid->createContainer('Row', $arexRow[1]);
				foreach($vRow as $kCol => $vCol){
					$arexCol = explode('|',$vCol);
					if($arexCol[2] == 'Auto'){
						$colLabel = '#'.$arexCol[1];
					} else {
						$colLabel = $arexCol[2];
					}
					$gridColumn = $gridRow->createContainer('Column', $arexCol[1], $colLabel);
					$gridColumn->setColumnPosition($arexCol[1]);
					
					$params = $arexCol;
					unset($params[0]);
					unset($params[1]);
					unset($params[2]);
					if(count($params)>0){
						foreach($params as $kParam => $vParam){
							$temp = explode(':',$vParam);
							$func = 'set'.$temp[0];
							$arg = $temp[1];
							$gridColumn->{$func}($arg); // $gridColumn->setColSpan(1);
							// $gridColumn->setStyle('padding: 10px;'); /// ???
							// $gridColumn->setVariables(['allowedContentTypes'=>'text,shortcut']); /// ???
							//<flux:form.variable name="allowedContentTypes" value="text,shortcut"/>
							
						}
					}
				}
			}
		}
		return $grid;
	}
	
	public static function readFluxFields($form, $fluxFields = [])
	{
		foreach($fluxFields as $k => $v){
			$arexK = explode('|',$k);
			$arexV = explode('|',$v);
			if($arexK[0] == 'Sheet'){
				$sheet = $form->createContainer('Sheet', $arexK[1], $arexK[2]);
				if(!empty($arexK[3])){
					$sheet->setDescription($arexK[3]);
					$sheet->setShortDescription($arexK[3]);
				}
				$form->add(self::readFluxFields($sheet, $v));
				
			/*
			<flux:form.section name="settings.sectionObjectAsClass2" label="Telephone numbers 2">
				<flux:form.object name="custom">
					<flux:field.input name="propertyFoo" default="Foo" label="Property value: Foo" />
					<flux:field.input name="propertyBar" default="Bar" label="Property value: Bar" />
					<flux:field.input name="propertyBar2" default="Bar2" label="Property value: Bar" />
				</flux:form.object>
				<flux:form.object name="mobile" label="Mobile">
					<flux:field.input name="number"/>
				</flux:form.object>
				<flux:form.object name="landline" label="Landline">
					<flux:field.input name="number"/>
				</flux:form.object>
			</flux:form.section>
			*/
			
			}elseif($arexK[0] == 'Section'){
				$section = $form->createContainer('Section', $arexK[1], $arexK[2]);
				$form->add(self::readFluxFields($section, $v));
			
			}elseif($arexK[0] == 'SectionObject'){
				$sectionObject = $form->createContainer('SectionObject', $arexK[1], $arexK[2]);
				$form->add(self::readFluxFields($sectionObject, $v));
				
			} elseif($arexV[0] == 'Input'){
				$form->createField('Input', $arexV[1], $arexV[2]); // ->setDefault('My default value')
				
			}
		}
		return $form;
	}
	
	// https://gist.github.com/wgrafael/8a9bb1a963042bc88dac
	public static function convertDotToArray($array) 
	{
		$newArray = array();
		foreach($array as $key => $value) {
			$dots = explode(".", $key);
			if(count($dots) > 1) {
				$last = &$newArray[ $dots[0] ];
				foreach($dots as $k => $dot) {
					if($k == 0) continue;
					$last = &$last[str_replace('#','.',$dot)];
				}
				$last = $value;
			} else {
				$newArray[str_replace('#','.',$key)] = $value;
			}
		}

		return $newArray;
	}
	
	public static function convertArrayToDot($arr, $narr = array(), $nkey = '') 
	{
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				if(strstr($key,'.')){
					$newkey = str_replace('.','#',$key);
				} else {
					$newkey = $key;
				}
				$narr = array_merge($narr, self::convertArrayToDot($value, $narr, $nkey . $newkey . '.'));
			} else {
				$narr[$nkey . $key] = $value;
			}
		}
		return $narr;
	}

}
?>
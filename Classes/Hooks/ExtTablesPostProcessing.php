<?php
namespace Litovchenko\AirTable\Hooks;

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Database\TableConfigurationPostProcessingHookInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Reflection\ClassSchema;
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Utility\BaseUtility;

class ExtTablesPostProcessing implements TableConfigurationPostProcessingHookInterface
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Генерация TCA',
		'description' => '',
		'onlyBackend' => [
			// https://github.com/TYPO3-extensions/gridelements/blob/master/ext_localconf.php
			// не подошло!, у нас много в функция создаются иконки и прочее...
			// $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
			// $signalSlotDispatcher->connect(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::class, 'tcaIsBeingBuilt', \Litovchenko\AirTable\Hooks\TableConfigurationPostProcessor::class, 'processData');
			'TYPO3_CONF_VARS|SC_OPTIONS|GLOBAL|extTablesInclusion-PostProcessing',
		],
		'onlyFrontend' => [
			// -> запускалось 11 раз! // как вариант можно сделать проверку на 1 запуск фукнции!
			// TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_fe.php|configArrayPostProc -- Второй вариант, что можно использовать!
			'TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tstemplate.php|includeStaticTypoScriptSourcesAtEnd::processData',
		]
	];
	
    public function processData()
    {
		// Запускаем функцию только 1 раз!
		if($GLOBALS['Litovchenko.AirTable.VarCache.Counter'] == 0){
			$GLOBALS['Litovchenko.AirTable.VarCache.Counter'] = 1;
			
			// Правка для просмотра записей в корне дерева страница сайта (очень долго загружается)
			// Через модуль "Список" - иначе идет зависание админки, т.к. все записи показываются
			$id = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
			if($id == 0){
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
					mod.web_list.itemsLimitPerTable = 3
				');
			}
			
			#$start = microtime(true);
			// if (TYPO3_REQUESTTYPE_INSTALL !== (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
				
				// Генерируем TCA
				// AbstractModelCrud && AbstractModelCrudOverride
				// $models = BaseUtility::getLoaderClasses('Classes/Domain/');
				// foreach($models as $class) {
				// 	$class_parents = class_parents($class);
					
					// Регистрация расширений моделей
					// 'Illuminate\Database\Eloquent\Model'
					
					// [Отказался - нет даже модели Pagesъ
					// Регистрация моделей на основе существующей таблицы TYPO3
					// 'TYPO3\CMS\Extbase\DomainObject\AbstractEntity'
					// 'TYPO3\CMS\Extbase\DomainObject\AbstractValueObject'
					// Создаем файлы AbstractModelCrudOverride
					#if(in_array('TYPO3\CMS\Extbase\DomainObject\AbstractEntity',$class_parents)
					#	|| in_array('TYPO3\CMS\Extbase\DomainObject\AbstractValueObject',$class_parents)){
					#	$r = new \ReflectionClass($class);
					#	if(!$r->isAbstract()){
					#		// table name
					#		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
					#		$dataMapper = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper::class);
					#		$table = $dataMapper->getDataMap($class)->getTableName();
					#		
					#		// file AbstractModelCrudOverride
					#		if(isset($GLOBALS['TCA'][$table])){
					#			$path_Typo3ExtAirTable = \TYPO3\CMS\Core\Core\Environment::getExtensionsPath().'/air_table/Resources/Private/BaseCrudEntity.txt';
					#			$content = file_get_contents($path_Typo3ExtAirTable);
					#			$content = str_replace(
					#				[
					#					'###NAMESPACE-NAME###',
					#					'###MODEL-NAME###',
					#					'###TABLE-NAME###',
					#					'###TABLE-KEY###',
					#					''
					#				],
					#				[
					#					$r->getNamespaceName(),
					#					'_Laravel'.$r->getShortName(),
					#					'--auto--',
					#					$table
					#				],
					#				$content
					#			);
					#			
					#			$classDirPath = BaseUtility::getClassDirPath($class);
					#			BaseUtility::fileReWrite($classDirPath.'_Laravel'.$r->getShortName().'.php',$content);
					#		}
					#	}
					#}
					
				// Все модели
				$allClasses = BaseUtility::getLoaderClasses2();
				$models = array_merge((array)$allClasses['BackendModelCrud'],(array)$allClasses['BackendModelCrudOverride'],(array)$allClasses['BackendModelExtending']);
				
				// Регистрация новых пользовательских моделей
				foreach($models as $class) {
					if($class::$TYPO3['thisIs'] == 'BackendModelCrud'){
						$table = BaseUtility::getTableNameFromClass($class);
						$this->generateTableConfiguration($class, $table);
					}
				}
				
				// Регистрация моделей на основе существующей таблицы TYPO3
				foreach($models as $class) {
					if($class::$TYPO3['thisIs'] == 'BackendModelCrudOverride'){
						$table = BaseUtility::getTableNameFromClass($class);
						$this->registrationTableConfiguration($class, $table);
					}
				}
				
				// Ex
				foreach($models as $class) {
					if($class::$TYPO3['thisIs'] == 'BackendModelExtending'){
						$table = BaseUtility::getTableNameFromClass($class);
						$this->registrationExTableConfiguration($class, $table);
					}
				}
				
				// Вызываем событие после обработки моделей (для колонок)
				foreach($models as $class) {
					// $class_parents = class_parents($class);
					// if(in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents)){
						$table = BaseUtility::getTableNameFromClass($class);
						$userFields = BaseUtility::getClassAllFieldsNew($class);
						foreach($userFields as $kField => $vField){
							$annotationField = BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field');
							$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
							if(class_exists($fieldClass) && method_exists($fieldClass,'postBuildConfiguration')) {
								$fieldClass::postBuildConfiguration($class,$table,$vField);
							}
						}
					// }
				}
				
				// Вызываем событие после обработки моделей
				foreach($models as $class) {
					// $class_parents = class_parents($class);
					// if(in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents)){
						if (method_exists($class,'postBuildConfiguration')){
							$table = BaseUtility::getTableNameFromClass($class);
							$init = $class::postBuildConfiguration($GLOBALS['TCA'][$table]);
						}
					// }
				}
				
				// Перекидка вкладок - очень хотелось!
				// Блокируем кнопки, т.к. они не несут смысловой нагрузки
				if(current(current($GLOBALS['_GET']['edit'])) == 'new' || current(current($GLOBALS['_GET']['edit'])) == 'edit'){
				foreach($GLOBALS['TCA'] as $table => $v){
					#foreach($GLOBALS['TCA'][$table]['types'] as $kType => $vType){
					#	// Перекидываем вкладку "категории" в конец...
					#	if(preg_match("/tab_cat_start(.*)tab_cat_end/is",$GLOBALS['TCA'][$table]['types'][$kType]['showitem'], $match)){
					#		$GLOBALS['TCA'][$table]['types'][$kType]['showitem'] = preg_replace("/tab_cat_start(.*)tab_cat_end/is","",$GLOBALS['TCA'][$table]['types'][$kType]['showitem']).",\n".$match[0];
					#	}
					#	// Перекидываем вкладку "расширенное" в конец...
					#	if(preg_match("/tab_extended_start(.*)tab_extended_end/is",$GLOBALS['TCA'][$table]['types'][$kType]['showitem'], $match)){
					#		$GLOBALS['TCA'][$table]['types'][$kType]['showitem'] = preg_replace("/tab_extended_start(.*)tab_extended_end/is","",$GLOBALS['TCA'][$table]['types'][$kType]['showitem']).",\n".$match[0];
					#	}
					#}
					
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
						options.saveDocNew.'.$table.' = 0
						options.saveDocView.'.$table.' = 0
						# options.disableDelete.'.$table.' = 1
					');
				}
				}
				
				// Конвертируем массивы "foreignWhere" в строку "foreign_table_where"
				// В 
				// TcaCheckboxItems.php,
				// TcaRadioItems.php,
				// TcaSelectItems.php,
				// TcaSelectTreeItems.php
				// почему-то в новой версии не заработал Xclass для функци "protected function processForeignTableClause()"
				if(TYPO3_MODE === 'BE'){
					foreach($GLOBALS['TCA'] as $table => $v){
						foreach($GLOBALS['TCA'][$table]['columns'] as $kType => $vType){
							$foreign_table_where_ar = $vType['config']['foreign_table_where'];
							if(is_array($foreign_table_where_ar)){
								$tempString = $this->foreignWhereArrayConver($foreign_table_where_ar);
								$GLOBALS['TCA'][$table]['columns'][$kType]['config']['foreign_table_where'] = $tempString;
							}
							foreach($GLOBALS['TCA'][$table]['columns'][$kType]['config']['overrideChildTca']['columns'] as $kTypeOver => $vTypeOver){
								$foreign_table_where_ar = $vTypeOver['config']['foreign_table_where'];
								if(is_array($foreign_table_where_ar)){
									$tempString = $this->foreignWhereArrayConver($foreign_table_where_ar);
									$GLOBALS['TCA'][$table]['columns'][$kType]['config']['overrideChildTca']['columns'][$kTypeOver]['config']['foreign_table_where'] = $tempString;
								}
							}
						}
					}
				}
				
				// Уничтожаем, т.к. это пустые классы
				// unset($GLOBALS['TCA']['tx_air_table_root_basecrud']);
				// unset($GLOBALS['TCA']['tx_air_table_root_basecrudoverride']);
			// }
			#echo '<div style="background: red; position: fixed; bottom: 0; right: 0;">Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.</div>';
			#exit();
			
			// (ext_tables.sql) в TYPO3 v11 убрали Signal slots
			// if(TYPO3_MODE === 'BE'){
			// 	\Litovchenko\AirTable\Hooks\DatabaseSchemaService::addMysqlTableAndFields([]);
			// }
		}
		
		/*
		$a = [
			'tx_data',
			'tx_data_category',
			'tx_data_type',
			'tx_data_type_sub',
			'tx_data_type_group',
			'sys_attribute',
			'sys_attribute_option',
			'sys_value',
			'pages',
			'tt_content',
			'sys_file',
			'sys_filemounts',
			'sys_file_metadata',
			'sys_file_reference',
			'sys_file_storage',
			'be_users',
			'be_groups',
			'fe_users',
			'fe_groups',
			'sys_mm',
			'sys_note',
			'sys_redirect',
			'tx_projiv_experience',
			'tx_projiv_service',
			'tx_projiv_technology',
			'tx_projiv_technology_category',
			'tx_air_table_examples_exampletable',
			'tx_air_table_examples_exampletable1',
			'tx_air_table_examples_exampletable2',
			'tx_air_table_examples_exampletable3',
			'tx_air_table_examples_exampletable4',
			'tx_air_table_examples_exampletable5',
			'tx_air_table_examples_exampletable6',
			'tx_air_table_examples_exampletable7',
			'tx_air_table_examples_exampletable_category',
			'tx_air_table_examples_testmodel1',
			'tx_air_table_examples_testmodel4',
			'tx_air_table_examples_testmodel4_category',
			'tx_air_table_examples_testmodel5',
			'tx_air_table_examples_testmodel5_category'
		];
		foreach($a as $k => $v){
			BaseUtility::fileReWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/_TCA-OLD/'.$v.'.'.md5(serialize($GLOBALS['TCA'][$v])).'.NEW.txt',print_r($GLOBALS['TCA'][$v],true));
		}
		exit();
		*/
    }
	
	protected function foreignWhereArrayConver($foreign_table_where_ar)
	{
		$tempString = '';
		$temp = $foreign_table_where_ar;
		if(isset($temp['where'])){
			$tempString .= ' AND ' . implode(' AND ', $temp['where']) . ' ';
		}
		if(isset($temp['groupBy'])){
			//
		}
		if(isset($temp['orderBy'])){
			$tempString .= ' ORDER BY ' . implode(' AND ', $temp['orderBy']) . ' ';
		}
		if(isset($temp['limit'])){
			//
		}
		return $tempString;
	}
	
	protected function registrationExTableConfiguration($class, $table)
    {
		// Если таблица не генерировалась через наш генератор
		if(empty($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'])){
			return false;
		}
		
		// TCA, помеченный как AirTable Ex
		$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.Class'][] = $class;
		$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.Ext'][] = BaseUtility::getExtNameFromClassPath($class);
		$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.SubDomain'][] = BaseUtility::getSubDomainNameFromClassPath($class);
		
		// Если мультиструктуры
		$class_parents = class_parents($class);
		if(BaseUtility::hasSpecialField(current($class_parents),'RType') == true) {
			$this->registrationRTypeConfiguration($class, $table, 0, 1); // Базовая структура
			$userRTypes = $class::baseRTypes();
			foreach($userRTypes as $k => $v){
				$this->registrationRTypeConfiguration($class, $table, $k, 1); // Пользовательская структура
			}
		
		// Без мультиструктур
		} else {
			if(isset($GLOBALS['TCA'][$table]['ctrl']['type'])){
				foreach($GLOBALS['TCA'][$table]['types'] as $kT => $vT){
					$this->registrationRTypeConfiguration($class, $table, $kT, 1); // Базовая структура
				}
			} else {
				$this->registrationRTypeConfiguration($class, $table, 0, 1); // Базовая структура
			}
		}
		
		// Генерируем пользовательские колонки
		$userFields = BaseUtility::getClassAllFieldsNew($class);
		foreach($userFields as $kField => $vField){
			$annotationField 		= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field');
			$annotationFieldSub 	= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field',1);
			$annotationLabel 		= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field\Label');
			$annotationDoNotCheck 	= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field\DoNotCheck');
		
			if(empty($GLOBALS['TCA'][$table]['columns'][$vField])){
				// Проверка на наличие префикса
				$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
				$requiredPrefix = $fieldClass::REQPREFIXCURRENTFIELD.'tx_'.str_replace('_','',BaseUtility::getExtNameFromClassPath($class)).'_';
				if(!preg_match('/^'.$requiredPrefix.'/is',$vField,$match) && $annotationDoNotCheck != 1){
					$GLOBALS['TCA'][$table]['columns'][$vField]['label'] = $annotationLabel;
					$GLOBALS['TCA'][$table]['columns'][$vField]['config'] = [
						'type' => 'user',
						'renderType' => 'fieldErrorDisplay',
						'parameters' => [
							'field' => $vField,
							'message' => [0=>'<li>Отсутствует обязательный префикс 
							<code>"' . $requiredPrefix . '"</code> в названии колонки 
							указывающий на принадлежность к расширению</li>']
						]
					];
					$this->registrationFieldInForm($class, $table, $vField);
				} else {
					$this->registrationFieldConfiguration($class, $table, $vField);
					$this->registrationFieldInForm($class, $table, $vField);
				}
			} else {
				$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
				$fieldClassSub = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationFieldSub;
				$databaseDefinitionsAr = $fieldClass::databaseDefinitions($class,$table,$vField);
				$GLOBALS['TCA'][$table]['columns'][$vField]['AirTable.Class'] = $fieldClass;
				$GLOBALS['TCA'][$table]['columns'][$vField]['AirTable.ClassWithSub'] = $fieldClassSub;
				#$GLOBALS['TCA'][$table]['columns'][$vField]['AirTable.MySql'] = $databaseDefinitionsAr[$table][$vField];
				if(!empty($annotationLabel)){
					$GLOBALS['TCA'][$table]['columns'][$vField]['label'] = $annotationLabel;
				}
			}
		}	
		
		// Вставляем кол-во свойств на вкладку "Свойства (###COUNT###)"
		foreach($GLOBALS['TCA'][$table]['types'] as $kType => $vType){
			$fieldsInType = BaseUtility::getAllColumnsFromType($table,$kType);
			$temp = explode("\n",$GLOBALS['TCA'][$table]['types'][$kType]['showitem']);
			foreach($temp as $k => $v){
				$fieldCounter = substr_count(preg_replace('/,$/is','',$v),',')-2;
				$temp[$k] = str_replace('###COUNT###',$fieldCounter,$temp[$k]);
			}
			$GLOBALS['TCA'][$table]['types'][$kType]['showitem'] = implode("\n",$temp);
		}
		
		#$classNameParentModel = current(explode('_Ex_',$className));
		#$fromExtension = end(explode('_Ex_',$className));
		#$class_vars = BaseFunction::get_class_TYPO3_vars($className);
		#$this->reportCompilation[] = 'Расширение сущесвтующей таблицы - ' . $className;
		
		// Проверка загруженности расширения
		//if(!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($fromExtension)){
		//	continue;
		//}
		/*
			// проверяем сущестование таблицы
			if(!class_exists($classNameParentModel)){
				BaseFunction::ServiceUnavailableException($className, "
					Для класса с расширения полей (EX) не существует базовой модели для расширения (\"".$classNameParentModel."\")
				");
			}elseif($fromExtension != $class_vars['ext']){
				BaseFunction::ServiceUnavailableException($className, "
					В названии класса (\"".$classNameParentModel."\") с расширения полей (EX) не задан постфикс расширения (\"".$class_vars['ext']."\")
				");
			} else {
				// если это не новая таблица - сообщаем, что таблица прошла модификацию (добавление полей)
				// и ее нужно выгрузить в ext_tables.sql
				// t3lib_div::loadTCA($class_vars['table_without_prefix_and_ex']);
				$GLOBALS['TCA'][$class_vars['table_without_prefix_and_ex']]['ctrl']['AirTableEx'] = 1;
				$GLOBALS['TCA'][$class_vars['table_without_prefix_and_ex']]['ctrl']['AirTableModelEx'][] = $className;
				$GLOBALS['TCA'][$class_vars['table_without_prefix_and_ex']]['ctrl']['AirTableModelEx_path'][] = $class_vars['file_path'];
			}
		*/
		
		#BaseFunction::ServiceUnavailableException($className, "Добавляемая в структуру ($k_addFieldsToTCAtypes) колонка ($v) не существует");
		#BaseFunction::ServiceUnavailableException($className, "Указанная структура ($k_addFieldsToTCAtypes) в \"TYPO3_addFieldsToTCAtypes\" не существует");
		#BaseFunction::ServiceUnavailableException($className, "Добавляемая в группу ($k_addFieldsToTCApalettes) колонка ($v) не существует");
		#BaseFunction::ServiceUnavailableException($className, "Указанная группа ($k_addFieldsToTCApalettes) в \"TYPO3_addFieldsToTCApalettes\" не существует");
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array(1=>432));
	}
	
	protected function registrationTableConfiguration($class, $table)
    {
		// Если таблица не существует - пропускаем
		if(empty($GLOBALS['TCA'][$table])){
			return false;
		}
		
		// Если создается не из расширения EXT:air_table
		#if(BaseUtility::getExtNameFromClassPath($class) != 'air_table'){
		#	return false;
		#}
		
		// TCA, помеченный как AirTable Ex
		#if(preg_match('/Ex$/is',$class)){
		#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.Class'][] = $class;
		#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.Ext'][] = BaseUtility::getExtNameFromClassPath($class);
		#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.SubDomain'][] = BaseUtility::getSubDomainNameFromClassPath($class);
		#
		#} else {
			$GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] = $class;
			$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ext'] = BaseUtility::getExtNameFromClassPath($class);
			$GLOBALS['TCA'][$table]['ctrl']['AirTable.SubDomain'] = BaseUtility::getSubDomainNameFromClassPath($class);
			// $GLOBALS['TCA'][$table]['ctrl']['rootLevel'] = -1;
		#}
		
		// Пробигаемся по всем структурам и добавляем [tab_end]
		/*
		Array
		(
			[showitem] => --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
							--palette--;;general,
							--palette--;;headers,
							bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
						--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
							--palette--;;frames,
							--palette--;;appearanceLinks,
						--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
							--palette--;;language,
						--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
							--palette--;;hidden,
							--palette--;;access,
						--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
							--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category, categories,
						--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
							rowDescription,
						--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended, --div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns, eav_rows, uid, importprocess, importolduid
			[columnsOverrides] => Array
				(
					[bodytext] => Array
						(
							[config] => Array
								(
									[enableRichtext] => 1
								)

						)

				)

		)
		*/
		foreach($GLOBALS['TCA'][$table]['types'] as $k => $v){
			$rewrite = []; 
			$temp = explode(",",$GLOBALS['TCA'][$table]['types'][$k]['showitem']);
			foreach($temp as $kI => $vI){
				//--div--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category
				//--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended
				if(preg_match("/.xlf:/is",$vI)){
					$t1 = end(explode(":",$vI));
					$t2 = end(explode(".",$t1));
					$rewrite[] = $vI;
					$rewrite[] = 'tab_'.$t2.'_end';
				} else {
					$rewrite[] = $vI;
				}
			}
			$GLOBALS['TCA'][$table]['types'][$k]['showitem'] = implode(",\n",$rewrite);
		}
		
		// Генерируем пользовательские колонки
		$userFields = BaseUtility::getClassAllFieldsNew($class);
		foreach($userFields as $kField => $vField){
			$annotationField 		= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field');
			$annotationFieldSub 	= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field',1);
			$annotationLabel 		= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field\Label');
			$annotationDoNotCheck 	= BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field\DoNotCheck');
		
			if(empty($GLOBALS['TCA'][$table]['columns'][$vField])){
				// Проверка на наличие префикса
				$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
				$requiredPrefix = $fieldClass::REQPREFIXCURRENTFIELD.'tx_'.str_replace('_','',BaseUtility::getExtNameFromClassPath($class)).'_';
				if(!preg_match('/^'.$requiredPrefix.'/is',$vField,$match) && $annotationDoNotCheck != 1){
					$GLOBALS['TCA'][$table]['columns'][$vField]['label'] = $annotationLabel;
					$GLOBALS['TCA'][$table]['columns'][$vField]['config'] = [
						'type' => 'user',
						'renderType' => 'fieldErrorDisplay',
						'parameters' => [
							'field' => $vField,
							'message' => [0=>'<li>Отсутствует обязательный префикс 
							<code>"' . $requiredPrefix . '"</code> в названии колонки 
							указывающий на принадлежность к расширению</li>']
						]
					];
					$this->registrationFieldInForm($class, $table, $vField);
				} else {
					$this->registrationFieldConfiguration($class, $table, $vField);
					$this->registrationFieldInForm($class, $table, $vField);
				}
			} else {
				$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
				$fieldClassSub = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationFieldSub;
				$databaseDefinitionsAr = $fieldClass::databaseDefinitions($class,$table,$vField);
				$GLOBALS['TCA'][$table]['columns'][$vField]['AirTable.Class'] = $fieldClass;
				$GLOBALS['TCA'][$table]['columns'][$vField]['AirTable.ClassWithSub'] = $fieldClassSub;
				#$GLOBALS['TCA'][$table]['columns'][$vField]['AirTable.MySql'] = $databaseDefinitionsAr[$table][$vField];
				if(!empty($annotationLabel)){
					$GLOBALS['TCA'][$table]['columns'][$vField]['label'] = $annotationLabel;
				}
			}
		}
		
		// Перемещаем uid, pid в начало столбцов (решено сделать в ручную!)
		if(isset($GLOBALS['TCA'][$table]['columns']['pid'])){
			$tempPid = $GLOBALS['TCA'][$table]['columns']['pid'];
			unset($GLOBALS['TCA'][$table]['columns']['pid']);
			$GLOBALS['TCA'][$table]['columns'] = ['pid'=>$tempPid] + $GLOBALS['TCA'][$table]['columns'];
		}
		if(isset($GLOBALS['TCA'][$table]['columns']['uid'])){
			$tempUid = $GLOBALS['TCA'][$table]['columns']['uid'];
			unset($GLOBALS['TCA'][$table]['columns']['uid']);
			$GLOBALS['TCA'][$table]['columns'] = ['uid'=>$tempUid] + $GLOBALS['TCA'][$table]['columns'];
		}
	}
	
    protected function generateTableConfiguration($class, $table)
    {
		// Создание массива TCA, помеченного как AirTable
		// TCA, помеченный как AirTable Ex
		#if(preg_match('/Ex$/is',$class)){
		#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.Class'][] = $class;
		#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.Ext'][] = BaseUtility::getExtNameFromClassPath($class);
		#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ex.SubDomain'][] = BaseUtility::getSubDomainNameFromClassPath($class);
		#
		#} else {
			$GLOBALS['TCA'][$table] = [];
			$GLOBALS['TCA'][$table]['columns'] = [];
			$GLOBALS['TCA'][$table]['ctrl'] = [];
			$GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] = $class;
			$GLOBALS['TCA'][$table]['ctrl']['AirTable.Ext'] = BaseUtility::getExtNameFromClassPath($class);
			$GLOBALS['TCA'][$table]['ctrl']['AirTable.SubDomain'] = BaseUtility::getSubDomainNameFromClassPath($class);
			// $GLOBALS['TCA'][$table]['ctrl']['security']['ignoreRootLevelRestriction'] = true; // Access
			// $GLOBALS['TCA'][$table]['ctrl']['security']['ignoreWebMountRestriction'] = true; // Access
			$GLOBALS['TCA'][$table]['ctrl']['label'] = "uid"; // Поля заголовка (для списка)
			$GLOBALS['TCA'][$table]['ctrl']['title'] = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label'); // название записываем в xml, что бы при выборе через модуль список правильно проходила группировка в "/typo3/db_new.php"
		
			if($table == 'sys_mm'){
				$GLOBALS['TCA'][$table]['ctrl']['groupName'] = 'system';
			} else {
				$GLOBALS['TCA'][$table]['ctrl']['groupName'] = 'system';
			}

			// Поиск в админке по хештегу "#"
			#$LiveSearchHashTagKey = BaseUtility::getClassAnnotationValueNew($class,'AirTable\LiveSearchHashTagKey');
			#if($LiveSearchHashTagKey != '' && empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch'][$LiveSearchHashTagKey])){
			#	$livekey = BaseUtility::getExtNameFromClassPath($class);
			#	$livekey .= $LiveSearchHashTagKey;
			#	$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch'][$livekey] = $table;
			#}
		
			// Возможность размещять записи в любом месте дерева страниц
			#if(BaseUtility::getClassAnnotationValueNew($class,'AirTable\PidAny') == 1){
			#	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
			#	$GLOBALS['TCA'][$table]['ctrl']['hideTable'] = 0;
			#	$GLOBALS['TCA'][$table]['ctrl']['rootLevel'] = -1;
			#} else {
				$GLOBALS['TCA'][$table]['ctrl']['hideTable'] = 0;
				$GLOBALS['TCA'][$table]['ctrl']['rootLevel'] = 1;
			#}
		
			// Иконка
			if(BaseUtility::isModelCategoryForAnotherModel($class)){
				$GLOBALS['TCA'][$table]['ctrl']['typeicon_classes']['default'] = 'extensions-tx-airtable-resources-public-icons-RecordCategory';
			} else {
				$GLOBALS['TCA'][$table]['ctrl']['typeicon_classes']['default'] = 'extensions-tx-airtable-resources-public-icons-Record';	
			}
		#}
		
		if(BaseUtility::hasSpecialField($class,'propref_beauthor') == true) {
			$GLOBALS['TCA'][$table]['ctrl']['cruser_id'] = 'propref_beauthor';
		}
		
		// Если мультиструктуры
		if(BaseUtility::hasSpecialField($class,'RType') == true) {
			$this->registrationRTypeConfiguration($class, $table, 0); // Базовая структура
			$userRTypes = $class::baseRTypes();
			foreach($userRTypes as $k => $v){
				$this->registrationRTypeConfiguration($class, $table, $k); // Пользовательская структура
			}
		
		// Без мультиструктур
		} else {
			$this->registrationRTypeConfiguration($class, $table, 0); // Базовая структура
		}
		
		// Генерируем пользовательские колонки
		$userFields = BaseUtility::getClassAllFieldsNew($class);
		foreach($userFields as $kfield => $field){
			$this->registrationFieldConfiguration($class, $table, $field);
			$this->registrationFieldInForm($class, $table, $field);
		}
		
		// Вставляем кол-во свойств на вкладку "Свойства (###COUNT###)"
		foreach($GLOBALS['TCA'][$table]['types'] as $kType => $vType){
			$fieldsInType = BaseUtility::getAllColumnsFromType($table,$kType);
			$temp = explode("\n",$GLOBALS['TCA'][$table]['types'][$kType]['showitem']);
			foreach($temp as $k => $v){
				$fieldCounter = substr_count(preg_replace('/,$/is','',$v),',')-2;
				$temp[$k] = str_replace('###COUNT###',$fieldCounter,$temp[$k]);
			}
			$GLOBALS['TCA'][$table]['types'][$kType]['showitem'] = implode("\n",$temp);
		}
		
		// Палетты (на данном этапе пока решил отказаться от них!)
		#$GLOBALS['TCA'][$table]['palettes'] = [];
		#$GLOBALS['TCA'][$table]['palettes']['index']['showitem'] = 'title, RType, uid, --linebreak--, alttitle';
		#$GLOBALS['TCA'][$table]['palettes']['index']['canNotCollapse'] = 0;
		#$GLOBALS['TCA'][$table]['palettes']['metatags']['showitem'] = 'vp_path_segment, --linebreak--, vp_keywords, vp_description';
		#$GLOBALS['TCA'][$table]['palettes']['metatags']['canNotCollapse'] = 0;
		#$GLOBALS['TCA'][$table]['palettes']['time']['showitem'] = 'date_create, date_update, date_start, date_end';
		#$GLOBALS['TCA'][$table]['palettes']['time']['canNotCollapse'] = 0;
		
		// Добавляем доступы на чтение (// todo)
		// Define custom permission options
		// USAGE: Core APIs > TYPO3 API overview > Various examples > Custom permission
		// $catKey = '';
		// $GLOBALS['BE_USER']->check('custom_options', $catKey . ':' . $itemKey);
		if(TYPO3_MODE === 'BE') {
			$l = BaseUtility::getClassAnnotationValueNew($class,'AirTable\Label');
			$GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']['tx_air_table_table_access']['header'] = 'Table access (D+) // Todo';
			$GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']['tx_air_table_table_access']['items'][$table.'.r'] = ['READ - '.$table,'overlay-news'];
			$GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']['tx_air_table_table_access']['items'][$table.'.c'] = ['CREATE - '.$table,'overlay-new'];
			$GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']['tx_air_table_table_access']['items'][$table.'.u'] = ['UPDATE - '.$table,'overlay-edit'];
			$GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']['tx_air_table_table_access']['items'][$table.'.d'] = ['DELETE - '.$table,'overlay-deleted'];
		}
    }
	
	protected function registrationRTypeConfiguration($class, $table, $type = 0, $add = false)
    {
		// Данные из класса
		$userRTypes = $class::baseRTypes();
		$userTabs = $class::baseTabs();
		
		// Табы для структуры по умолчанию
		$tabs = [];
		foreach($userTabs as $k => $tab){
			$tabLabel = $tab;
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$tabLabel .= ' ['.$k.']';
			}
			if($GLOBALS['TCA'][$table]['types'][$type]['tabs'][$k] == null){
				$tabs[$k] = 'tab_'.$k.'_start,--div--;'.$tabLabel.',tab_'.$k.'_end';
				$GLOBALS['TCA'][$table]['types'][$type]['tabs'][$k] = true;
			}
		}
		
		// Записываем структуру
		if($add == true){
			$GLOBALS['TCA'][$table]['types'][$type]['showitem'] .= ",\n".implode(",\n",$tabs);
		} else {
			$GLOBALS['TCA'][$table]['types'][$type]['showitem'] = implode(",\n",$tabs);
		}
	}
	
	protected function registrationFieldConfiguration($class, $table, $field)
    {
		$annotationField 		= BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field');
		$annotationLabel		= BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\Label');
		$annotationDoNotCheck 	= BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\DoNotCheck');
		
		// Eav
		if($class == 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute' && strstr($field,'@')){
		}
		
		// Генерируем колонку и ее настройки
		$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
		if(class_exists($fieldClass)) { // && method_exists($fieldClass,'buildConfiguration')
			$arError = $fieldClass::buildConfigurationCheck($class,$table,$field);
			if(count($arError)>0 && $annotationDoNotCheck != 1){
				$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $annotationLabel;
				$GLOBALS['TCA'][$table]['columns'][$field]['config'] = [
					'type' => 'user',
					'renderType' => 'fieldErrorDisplay',
					'parameters' => [
						'field' => $field,
						'message' => $arError
					]
				];
			} else {
				$fieldClass::buildConfiguration($class,$table,$field);
			}
		} else {
			$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $annotationLabel;
			$GLOBALS['TCA'][$table]['columns'][$field]['config'] = [
				'type' => 'user',
				'renderType' => 'fieldErrorDisplay',
				'parameters' => [
					'field' => $field,
					'message' => [0=>'<li>Для поля задан не существующий в системе тип конфигурации <code>' . $fieldClass . '</code></li>']
				]
			];
		}
		
	}
	
	protected function registrationFieldInForm($class, $table, $field)
    {
		$annotationField 		= BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field');
		$annotationPosition 	= BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\Position');
		
		// Eav
		if($class == 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute' && strstr($field,'@')){
		}
		
		// Отказался!
		// $annotationTab		= BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\Tab'); // ???
		
		if(is_array($annotationPosition)){
			foreach($annotationPosition as $kA => $vA){
				$tab = current(explode(',',$vA));
				$userTabs = $class::baseTabs();
				if($field == 'propref_attributes' && $table == 'pages'){
					$tab = 'after:subtitle';
				}elseif($field == 'propref_attributes' && $table == 'tt_content'){
					$tab = 'after:subheader';
				}elseif(array_key_exists($tab,$userTabs) == false){
					$tab = 'before:tab_extended_end';
				} else {
					$tab = 'before:tab_'.$tab.'_end';
				}
				$num = end(explode(',',$vA));
				if($kA == '*' || !isset($GLOBALS['TCA'][$table]['types'][$kA])){
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
						$table, 
						$field, // implode("," , $vColumn), // что 
						'',  // структура
						$tab // позиция after:extended
					);
				} else {
					\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
						$table, 
						$field, // implode("," , $vColumn), // что 
						$kA,  // структура
						$tab // позиция after:extended
					);
				}	
			}
		} else {
			$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
			if(class_exists($fieldClass) && $fieldClass::TABDEFAULT == 8) {
				$tab = 'media';
			} elseif(class_exists($fieldClass) && $fieldClass::TABDEFAULT == 9) {
				$tab = 'rels';
			} else {
				$tab = 'props';
			}
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
				$table, 
				$field, // implode("," , $vColumn), // что 
				'',  // структура
				'before:tab_'.$tab.'_end' // позиция after:extended
			);
		}
	}
	
}
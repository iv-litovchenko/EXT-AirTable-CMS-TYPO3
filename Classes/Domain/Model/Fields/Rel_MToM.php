<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelation;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_MToM extends AbstractFieldRelelation
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'M:M Связь многие ко многим',
		'description' 	=> 'Информация о связи пишется в промежуточную таблицу "sys_mm". Доступно три варианта конфигурации: default, db, tree',
		'incEav' 		=> 0,
	];
	
	const IMPORT = true;
    const REQPOSTFIXCURRENTFIELD = ''; // _rows
    const REQPOSTFIXFOREIGNFIELD = ''; // _rows
	
    /**
     * @var string
     */
    protected $RenderType = 'select'; // select || db || tree
	
    /**
     * @var string
     */
    protected $ParentKey = 'pid';

    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		$sql = []; // Имеем в виду, что может быть связь "На себя"
		$sql[$table][$field] = 'int(11) DEFAULT \'0\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)][parent::getForeignFieldName($model,$field)] = 'int(11) DEFAULT \'0\' NOT NULL';
		return $sql;
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $GLOBALS['TCA'][$table]['columns'][$field]['label'].' // M-M';
		#if(self::isDoubleRelation($model, $field)){
		#	$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= '↔';
		#}
		
		// Fix Typo3 bug (про создании записей для "renderType->selectTree" не работает defVals)
		if(current(current($GLOBALS['_GET']['edit'])) == 'new' && $field == 'propref_categories'){
			$RenderType = 'small';
		
		// Без бага
		} else {
			$RenderType = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field',true);
		}
		
		switch(strtolower($RenderType)){
			default:
			case 'small':
				self::buildConfigurationRelationSelect($model, $table, $field);
			break;
			case 'large':
				self::buildConfigurationRelationDb($model, $table, $field);
			break;
			case 'tree':
				self::buildConfigurationRelationTree($model, $table, $field);
			break;
		}
		
		// Eav tx_data (ограничения на тип записи - выбираем только записи своего типа)
		if($table == 'tx_data' && $field == 'propref_categories'){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where']['where'][] = ' tx_data_category.RType=###REC_FIELD_RType### ';
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where']['orderBy'][] = ' tx_data_category.RType ASC ';
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where']['orderBy'][] = ' tx_data_category.title ASC ';
		}
	}
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		// Добавляем информацию в таблицу "sys_mm"
		// $tableLabel = BaseUtility::getClassAnnotationValueNew($model,'AirTable\Label');
		// $GLOBALS['TCA']['sys_mm']['columns']['tablename']['config']['items'][$model] = [0=>$tableLabel,1=>$model];
	}
	
    /**
     * @return array
     */
    public static function buildConfigurationRelationSelect($model, $table, $field)
    {
		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		if(BaseUtility::hasSpecialField($_foreignModelName,'RType') == true) {
			if(BaseUtility::hasSpecialField($_foreignModelName,'title') == 1){
				$sort = ' '.$_foreignTableName.'.RType ASC, '.$_foreignTableName.'.title ASC ';
			} else {
				$sort = ' '.$_foreignTableName.'.RType ASC, '.$_foreignTableName.'.uid ASC ';
			}
		} elseif(BaseUtility::hasSpecialField($_foreignModelName,'title') == true) {
			$sort = ' '.$_foreignTableName.'.title ASC ';
		} else {
			$sort = ' '.$_foreignTableName.'.uid ASC ';
		}
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'select';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'selectMultipleSideBySide';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['enableMultiSelectFilterTextfield'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 0;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 100;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = 10;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'] = $_foreignTableName; // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where']['orderBy'][] = $sort; // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM'] = 'sys_mm';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_hasUidField'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_match_fields'] = [
			'tablename' => $table, // -> Устанавливать автоматически
			'fieldname' => $field, // -> Устанавливать автоматически
			// 'indent' => '' // произвольные данные
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldControl'] = [
			'addRecord' => [
				'disabled' => 0,
				'renderType' => 'addRecord',
				'options' => [
					#'pid' => 0, // -> Устанавливать автоматически
					'table' => $_foreignTableName, // -> Устанавливать автоматически
				]
			],
			'editPopup' => [
				'disabled' => 1,
			],
			'listModule' => [
				'disabled' => 1,
			],
			'elementBrowser' => [
				'disabled' => 1
			]
		];
    }
	
    /**
     * @return array
     */
    public static function buildConfigurationRelationDb($model, $table, $field)
    {
		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'group';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['internal_type'] = 'db';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['allowed'] = $_foreignTableName; // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM'] = 'sys_mm';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_hasUidField'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_match_fields'] = [
			'tablename' => $table, // -> Устанавливать автоматически
			'fieldname' => $field, // -> Устанавливать автоматически
			// 'indent' => '' // произвольные данные
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 0;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 100;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = 10;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['hideSuggest'] = 1;
		#'suggestOptions' => [
		#	'default' => [
		#		'pidList' => 0,
		#		'pidDepth' => 0,
		#		'maxItemsInResultList' => 100,
		#		'searchWholePhrase' => 1,
		#	]
		#],
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldWizard']['recordsOverview']['disabled'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldControl'] = [
			'addRecord' => [
				'disabled' => 1,
				'renderType' => 'addRecord',
				'options' => [
					#'pid' => 0, // -> Устанавливать автоматически
					'table' => $_foreignTableName, // -> Устанавливать автоматически
				]
			],
			'editPopup' => [
				'disabled' => 1,
			],
			'listModule' => [
				'disabled' => 1,
			],
			'elementBrowser' => [
				'disabled' => 0
			]
		];
    }
	
    /**
     * @return array
     */
    public static function buildConfigurationRelationTree($model, $table, $field)
    {
		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		$parentKey = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignParentKey');
		if(BaseUtility::hasSpecialField($_foreignModelName,'RType') == true) {
			if(BaseUtility::hasSpecialField($_foreignModelName,'title') == 1){
				$sort = ' '.$_foreignTableName.'.RType ASC, '.$_foreignTableName.'.title ASC ';
			} else {
				$sort = ' '.$_foreignTableName.'.RType ASC, '.$_foreignTableName.'.uid ASC ';
			}
		} elseif(BaseUtility::hasSpecialField($_foreignModelName,'title') == true) {
			$sort = ' '.$_foreignTableName.'.title ASC ';
		} else {
			$sort = ' '.$_foreignTableName.'.uid ASC ';
		}
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'select';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'selectTree';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['enableMultiSelectFilterTextfield'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 0;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 100;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'] = $_foreignTableName; // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where']['orderBy'][] = $sort; // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM'] = 'sys_mm';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_hasUidField'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_match_fields'] = [
			'tablename' => $table, // -> Устанавливать автоматически
			'fieldname' => $field, // -> Устанавливать автоматически
			// 'indent' => '' // произвольные данные
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['treeConfig'] = [
			'parentField' =>$parentKey!=''?$parentKey:'propref_parent', // -> Устанавливать автоматически
			'appearance' => [
				'expandAll' => 0,
				'showHeader' => 1
			]
		];
    }
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $obj->belongsToMany($annotationForeignModel, 'sys_mm', 'uid_local', 'uid_foreign')
			->where('sys_mm.tablename', '=', BaseUtility::getTableNameFromClass($class))
				->where('sys_mm.fieldname', '=', $field)
					->orderBy('sys_mm.sorting', 'asc');
	}
	
	// Создание связей
	public function refAttach($obj, $class, $field, $idTwo = null, $data = []){
		// belongsToMany
		$arSync = [];
		$indentArray = [
			'tablename' => BaseUtility::getTableNameFromClass(get_class($obj)), 
			'fieldname' => $field
		];
		#if(is_array($idTwo)){
		#	foreach($idTwo as $k => $id){
		#		$arSync[$id] = $indentArray;
		#		$arSync[$id]['sorting'] = $k;
		#	}
		#} else {
			$arSync[$idTwo] = $indentArray;
			$arSync[$idTwo]['sorting'] = 1;
		#}
		$obj->{$field}('withoutGlobalScopes')->syncWithoutDetaching($arSync);
		return $obj->save();
	}
	
	// Удаление связей
	public function refDetach($obj, $class, $field, $idTwo = null, $data = []){
		// belongsToMany
		$obj->{$field}('withoutGlobalScopes')
			->wherePivot('tablename', '=', BaseUtility::getTableNameFromClass(get_class($obj)))
				->wherePivot('fieldname', '=', $field)
					->detach($idTwo); // Если = "0" - удаляеться все связи
		return $obj->save();
	}

}
<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\SwitchInt;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialRType extends Switcher
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: тип записи',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
			'itemsProcFunc' => 'string',
			'itemsModel' => 'string',
			'itemsWhere' => 'array',
		],
		'_fields' => [
			'RType' => [
				'type' => 'SpecialRType',
				'name' => 'Тип записи',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|main|1'
			]
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		#$sql = BaseUtility::getClassAnnotationValueNew($model,'AirTable\RType\MySql');
		$userRTypes = $model::baseRTypes();
		foreach($userRTypes as $k => $v){
			if(!ctype_digit($k)){
				return [$table=>[
					$field => 'varchar(255) DEFAULT \'\' NOT NULL'
				]];
			}
		}
		return [$table=>[
			$field => 'int(11) DEFAULT \'0\' NOT NULL'
		]];
		/*
		if(strtolower($sql) == 'varchar'){
			return [$table=>[
				$field => 'varchar(255) DEFAULT \'\' NOT NULL'
			]];
		} elseif(strtolower($sql) == 'int') {
			return [$table=>[
				$field => 'smallint UNSIGNED DEFAULT \'0\' NOT NULL'
			]];
		} else {
		}
		*/
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns']['RType']['config']['type'] = 'select';
		$GLOBALS['TCA'][$table]['columns']['RType']['config']['renderType'] = 'selectSingle';
		$GLOBALS['TCA'][$table]['columns']['RType']['config']['items'] = [];
		$GLOBALS['TCA'][$table]['columns']['RType']['onChange'] = 'reload';
		unset($GLOBALS['TCA'][$table]['columns']['RType']['config']['default']);
		
		// tx_data (настройка доступов для типа)
		if($table == 'tx_data' || $table == 'tx_data_category'){
			$GLOBALS['TCA'][$table]['columns']['RType']['config']['authMode'] = 'explicitAllow';
			$GLOBALS['TCA'][$table]['columns']['RType']['config']['authMode_enforce'] = 'strict';
		}
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		// Добавляем в название тип элемента (обычно очень актуально при выборе связей в админке)
		$GLOBALS['TCA'][$table]['ctrl']['type'] = 'RType'; // Поле структур
		if(BaseUtility::hasSpecialField($model,'title') == true){
			$GLOBALS['TCA'][$table]['ctrl']['label_alt'] = 'RType,uid,title';
		} else {
			$GLOBALS['TCA'][$table]['ctrl']['label_alt'] = 'RType,uid';
		}
		$GLOBALS['TCA'][$table]['ctrl']['label_alt_force'] = 1;
		$GLOBALS['TCA'][$table]['ctrl']['label_userFunc'] = \Litovchenko\AirTable\Domain\Model\Fields\SpecialRType::class.'->labelSpecialRTypeUserFunction';

		// Добавляем типы
		// $itemDefault = BaseUtility::getClassAnnotationValueNew($model,'AirTable\RType\Default');
		// $items = BaseUtility::getClassAnnotationValueNew($model,'AirTable\RType\Items');
		$icon = $GLOBALS['TCA'][$table]['ctrl']['typeicon_classes']['default']; // EXT:air_table/Resources/Public/Icons/record.svg
		$userRTypes = $model::baseRTypes();
		
		$items = [];
		$defaultItem = '';
		foreach($userRTypes as $k => $v){
			if(preg_match('/--div(.*)--/is',$k)){
				$items[$k] = [
					0 => $v,
					1 => '--div--'
				];
			} else {
				if($defaultItem == ''){
					$defaultItem = $k;
				}
				$items[$k] = [
					0 => $v,
					1 => $k,
					2 => $icon
				];
			}
		}
		
		// $GLOBALS['TCA'][$table]['columns']['RType']['config']['default'] = $itemDefault;
		$GLOBALS['TCA'][$table]['columns']['RType']['config']['items'] = $items;
		$GLOBALS['TCA'][$table]['columns']['RType']['config']['default'] = $defaultItem;
	}
	
	// Проверка полей связи
	public static function buildConfigurationCheck($model, $table, $field)
	{
		// Список ошибок
		$arError = parent::buildConfigurationCheck($model, $table, $field);
		
		#$sql = BaseUtility::getClassAnnotationValueNew($model,'AirTable\RType\MySql');
		#if($sql == ''){
		#	$arError[] = '<li>Не определен обязательный параметр <code>"@AirTable\RType\MySql:"</code>.
		#	Допустипые значений <code>int||varchar</code></li>';
		#}
		
		#$itemDefault = BaseUtility::getClassAnnotationValueNew($model,'AirTable\RType\Default');
		#if($itemDefault == ''){
		#	$arError[] = '<li>Не определен обязательный параметр <code>"@AirTable\RType\Default:"</code></li>';
		#}
		
		#$items = BaseUtility::getClassAnnotationValueNew($model,'AirTable\RType\Items');
		#if(empty($items)){
		#	$arError[] = '<li>Обязательный параметр <code>"@AirTable\RType\Items:"</code>
		#	не содержит определений о типах записей данной таблицы</li>';
		#}
		
		return $arError;
	}
	
	// Функция выводить альтернативное название для записей с типом поля RType
	public static function labelSpecialRTypeUserFunction(&$parameters)
	{
		if($parameters['table'] != 'tx_data' && $parameters['table'] != 'tx_data_category'){
			if(is_array($parameters['row']['RType'])){
				$temp = BaseUtility::getTcaFieldItem($parameters['table'],'RType',$parameters['row']['RType'][0]);
				// $newTitle = '[Тип: '.$temp[0].'] ';
				$newTitle = '['.(($temp[0]!='')?$temp[0]:'?').'] ';
			} else {
				$temp = BaseUtility::getTcaFieldItem($parameters['table'],'RType',$parameters['row']['RType']);
				// $newTitle = '[Тип: '.$temp[0].'] ';
				$newTitle = '['.(($temp[0]!='')?$temp[0]:'?').'] ';
			}
		}
		$fieldLabel = $GLOBALS['TCA'][$parameters['table']]['ctrl']['label'];
		if($parameters['row'][$fieldLabel] != ''){
			$newTitle .= $parameters['row'][$fieldLabel];
		} else {
			$newTitle .= $parameters['row']['uid'];
		}
		$parameters['title'] = $newTitle;
	}
}
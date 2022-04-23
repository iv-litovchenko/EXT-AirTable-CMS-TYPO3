<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_Eav extends Rel_MToM
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Атрибуты (характеристики)'
	];
	
	const EXPORT = false; // Todo
	
	const IMPORT = false; // Todo
	
    const REQPOSTFIXCURRENTFIELD = ''; // _rows

    const REQPOSTFIXFOREIGNFIELD = ''; // _rows
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		return [$table=>[
			$field => 'text' // XML Flexform
		]];
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		// При создании новой записи будет ошибка сохранения Eav, т.к. uid-еще не существует NEW601fee50e8c80091179941...
		#if(isset($GLOBALS['TCA'][$table]['columns'][$field]['displayCond'])){
		#	$GLOBALS['TCA'][$table]['columns'][$field]['displayCond']['AND'] = [
		#		$GLOBALS['TCA'][$table]['columns'][$field]['displayCond'],
		#		'FIELD:uid:>:0'
		#	];
		#} else {
		#	$GLOBALS['TCA'][$table]['columns'][$field]['displayCond'] = 'FIELD:uid:>:0';
		#}
		
		#$typo3temp_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/SysEavFlexForm.xml'; // typo3conf/ext path
		#if(file_exists($typo3temp_path)){
		#	$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'flex';
		#	$GLOBALS['TCA'][$table]['columns'][$field]['config']['ds']['default'] = 'FILE:typo3temp/SysEavFlexForm.xml';
		#} else {
		#	$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'flex';
		#	$GLOBALS['TCA'][$table]['columns'][$field]['config']['ds']['default'] = 'FILE:EXT:air_table/Configuration/FlexForms/Default.xml';
		#}
		
		// // // // // // // // // // // // // // // // // // // // // // 
		// Последний  вариант (START)
		// // // // // // // // // // // // // // // // // // // // // // 
		
		# $DSPF = $GLOBALS['ENTITY_TYPES'][$table]['field']; // RType
		# $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'flex';
		# $GLOBALS['TCA'][$table]['columns'][$field]['config']['ds']['default'] = 'FILE:EXT:air_table/Configuration/FlexForms/Default.xml';
		# $GLOBALS['TCA'][$table]['columns'][$field]['config']['ds_pointerField'] = $DSPF;
		
		# $typo3temp_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/SysEavFlexForm/';
		# foreach (glob($typo3temp_path."*") as $filename) {
		# 	$arex = explode('___',basename($filename));
		#  	if($arex[0] == $table){
		#  		$dsKey = $arex[1];
		#  		$dsKey = preg_replace('/.xml$/is','',$dsKey);
		#  		$GLOBALS['TCA'][$table]['columns'][$field]['config']['ds'][$dsKey] = 'FILE:typo3temp/SysEavFlexForm/'.basename($filename);
		#  	}
		# }
		
		// // // // // // // // // // // // // // // // // // // // // // 
		// Последний  вариант (END)
		// // // // // // // // // // // // // // // // // // // // // // 
		
		// // // // // // // // // // // // // // // // // // // // // // 
		// Остановился на Inline...
		// // // // // // // // // // // // // // // // // // // // // // 
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config'] = [
			'type' => 'inline',
			'foreign_table' => 'sys_value',
			'foreign_field' => 'prop_entity',
			'foreign_sortby' => 'sorting',
			'foreign_label' => 'propref_attribute',
			'foreign_unique' => 'propref_attribute',
			'foreign_selector' => 'propref_attribute',
			// 'foreign_table_field' => 'tablename',
			// 'foreign_match_fields' => [
			// 	'tablename' => $table, // -> Устанавливать автоматически
			// 	'fieldname' => $field, // -> Устанавливать автоматически
			// 	// 'indent' => '' // произвольные данные
			// ],
			'inline' => [
				'inlineNewRelationButtonStyle' => 'display: none;'
			],
			#'filter' => [
			#	[
			#		'userFunc'=>'Litovchenko\AirTable\Domain\Model\SysEav->doFilterEav',
			#	]
			#],
			'appearance' => [
				'showSynchronizationLink' => 1,
				'showAllLocalizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'showRemovedLocalizationRecords' => 1,
				// 'collapseAll' => true,
				// 'expandSingle' => false,
				'enabledControls' => [
					'info' => true,
					'new' => false,
					'dragdrop' => true,
					'sort' => true,
					'hide' => true,
					'delete' => true,
					'localize' => false,
				]
			],
			'overrideChildTca' => [
				'columns' => [
					'propref_attribute' => [
						'config' => [
							// 'foreign_table_where' => [
							// 	'where' => [
							// 		0 => ' ### '
							// 	]
							// ]
							// 'readOnly' => 1
						]
					]
				],
				'types' => [
					0 => [
						// 'showitem' => 'prop_value,propref_attribute,prop_entity'
					]
				]
			]
		];
	}
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = 'Характеристики (атрибуты) // M-M // Eav';
	}
	
	// Проверка полей связи
	public static function buildConfigurationCheck($model, $table, $field)
	{
		// Список ошибок
		$arError = parent::buildConfigurationCheck($model, $table, $field);
		
		if($field != 'propref_attributes'){
			$arError[] = '<li>Название поля с характеристиками (атрибутами) имеет навание отличное от 
			<code>"propref_attributes"</code></li>';
		}
		
		return $arError;
	}
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $obj->belongsToMany($annotationForeignModel, 'sys_value', 'prop_entity', 'propref_attribute')
			// ->where('sys_attribute.entity_type', 'like', BaseUtility::getTableNameFromClass($class).'___%')
			->withPivot('prop_entity', 'prop_value', 'sorting', 'propref_attribute') // 'duplicate_entity_type', 'duplicate_attr_key', 
			->with('proprefinv_values')
			->orderBy('sys_value.sorting', 'asc');
			// ->keyBy('uid');
	}
}

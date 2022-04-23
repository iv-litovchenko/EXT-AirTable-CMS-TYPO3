<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Input;
use Litovchenko\AirTable\Utility\BaseUtility;

class FlexForm extends Text
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Гибкиая форма (FlexForm)',
		'incEav' 		=> 0,
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string',
			'format' => 'string',
			'preset' => 'string',
			'dsDefault' => 'string'
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [$table=>[
			$field => 'text'
		]];
    }
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		
		$ds = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\DsDefault');
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'flex';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['ds']['default'] = 'FILE:EXT:air_table/Configuration/FlexForms/Default.xml';
		#'prop_attr_config' => [
		#		'type' => 'FlexForm',
		#		'name' => 'Настройки атрибута (УДАЛИТЬ)',
		#		'dsDefault' => 'FILE:EXT:air_table/Configuration/FlexForms/AirTableFieldConfig.xml',
		#		'position' => [
		#			'*' => 'main,500'
		#		]
		#]
	}
}

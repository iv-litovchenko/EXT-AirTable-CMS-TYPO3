<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\InputNumber;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialSorting extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: сортировка',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
		],
		'_fields' => [
			'sorting' => [
				'type' => 'SpecialSorting',
				'name' => 'Сортировка',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|11'
			]
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		return [$table=>[
			$field => 'tinyint(4) unsigned DEFAULT \'0\' NOT NULL'
		]];
	}
				
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = "int";
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = 8;
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['sortby'] = "sorting";
		$GLOBALS['TCA'][$table]['ctrl']['default_sortby'] = " ORDER BY sorting ";
	}
}
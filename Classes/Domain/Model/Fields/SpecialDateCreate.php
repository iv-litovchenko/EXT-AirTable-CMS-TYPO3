<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\DateTime;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialDateCreate extends Date
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: дата создания записи',
		'_fields' => [
			# const CREATED_AT = 'date_create';
			'date_create' => [
				'type' => 'SpecialDateCreate',
				'name' => 'Дата создания записи',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|5'
			]
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'datetime';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = time();
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['crdate'] = 'date_create';
	}
}
<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\DateTime;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialDateUpdate extends Date
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: дата изменения записи',
		'_fields' => [
			# const UPDATED_AT = 'date_update';	
			'date_update' => [
				'type' => 'SpecialDateUpdate',
				'name' => 'Дата изменения записи',
				'readOnly' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|6'
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
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['tstamp'] = 'date_update';
	}
}
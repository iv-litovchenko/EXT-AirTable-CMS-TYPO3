<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Text;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialServiceNote extends Text
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: служебная заметка',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string',
			'format' => 'string',
			'preset' => 'string'
		],
		'_fields' => [
			'service_note' => [
				'type' => 'SpecialServiceNote',
				'name' => 'Служебная заметка',
				'doNotCheck' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|9'
			]
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['descriptionColumn'] = 'service_note';
	}
}
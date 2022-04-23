<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Input;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialAltTitle extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: альтернативное название',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
		],
		'_fields' => [
			'alt_title' => [
				'type' => 'SpecialAltTitle',
				'name' => 'Альтернативное название',
				'liveSearch' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|3'
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
	}
}
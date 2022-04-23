<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Input;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialSlug extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: сегмент URL',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
		],
		'_fields' => [
			'slug' => [
				'type' => 'SpecialSlug.Slug',
				'name' => 'Сегмент URL',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|4'
			]
		]
	];
}
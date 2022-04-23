<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Text;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialKeywords extends Text
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: ключевые слова',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string',
			'format' => 'string',
			'preset' => 'string'
		],
		'_fields' => [
			'keywords' => [
				'type' => 'SpecialKeywords',
				'name' => 'Ключевые слова',
				'doNotCheck' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|detail|3'
			]
		]
	];
}
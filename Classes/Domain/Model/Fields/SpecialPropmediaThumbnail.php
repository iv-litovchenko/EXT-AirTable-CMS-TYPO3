<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialPropmediaThumbnail
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: миниатюра',
		'_propertyAnnotations' => [
			'minItems' => 'int',
			'maxItems' => 'int'
		],
		'_fields' => [
			// $GLOBALS['TCA'][$table]['ctrl']['thumbnail'] = "thumbnail";
			'propmedia_thumbnail' => [
				'type' => 'Media_1.Image',
				'name' => 'Миниатюра',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|files|2'
			]
		]
	];
}
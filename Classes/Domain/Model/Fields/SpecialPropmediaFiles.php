<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialPropmediaFiles
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
			'propmedia_files' => [
				'type' => 'Media_M.Mix',
				'name' => 'Файлы',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|files|1'
			]
		]
	];
}
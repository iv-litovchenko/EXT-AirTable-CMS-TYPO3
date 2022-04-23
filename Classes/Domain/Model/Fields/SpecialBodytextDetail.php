<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Text;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialBodytextDetail extends Text
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: детальное описание',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string',
			'format' => 'string',
			'preset' => 'string'
		],
		'_fields' => [
			'bodytext_detail' => [
				'type' => 'SpecialBodytextDetail.Rte',
				'name' => 'Детальное описание',
				'doNotCheck' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|detail|1'
			]
		]
	];
}
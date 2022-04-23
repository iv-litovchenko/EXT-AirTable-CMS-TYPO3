<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Text;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialBodytextPreview extends Text
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: описание для анонса',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string',
			'format' => 'string',
			'preset' => 'string'
		],
		'_fields' => [
			'bodytext_preview' => [
				'type' => 'SpecialBodytextPreview.Rte',
				'name' => 'Описание для анонса',
				'doNotCheck' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|preview|1'
			]
		]
	];
}
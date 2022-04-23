<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialPropmediaPicPreview
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: картинка для анонса',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'propmedia_pic_preview' => [
				'type' => 'Media.Image',
				'name' => 'Картинка для анонса',
				'doNotCheck' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|preview|2'
			]
		]
	];
}
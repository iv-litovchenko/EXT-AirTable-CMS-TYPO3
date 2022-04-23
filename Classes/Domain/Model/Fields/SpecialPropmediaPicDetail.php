<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialPropmediaPicDetail
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: детальная картинка',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'propmedia_pic_detail' => [
				'type' => 'Media.Image',
				'name' => 'Детальная картинка',
				'doNotCheck' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|detail|2'
			]
		]
	];
}
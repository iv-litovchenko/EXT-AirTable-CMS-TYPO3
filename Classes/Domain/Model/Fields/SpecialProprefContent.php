<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialProprefContent
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: содержимое из элементов контента',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'propref_content' => [
				'type' => 'Rel_Poly_1ToM',
				'name' => 'Содержимое из элементов контента',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Content\TtContent',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data',
				'position' => '*|content|1'
			]
		]
	];
}
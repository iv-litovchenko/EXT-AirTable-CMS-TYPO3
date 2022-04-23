<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialProprefCategories
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: категории',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'propref_categories' => [
				'type' => 'Rel_MToM.Tree',
				'name' => 'Категории (возможно несколько)',
				'foreignModel' => 'category',
				'foreignParentKey' => 'propref_parent',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data',
				'position' => '*|cat|1'
			]
		]
	];
}
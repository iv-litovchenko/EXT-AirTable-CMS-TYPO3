<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialProprefCategory
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: категория',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'propref_category' => [
				'type' => 'Rel_MTo1.Tree',
				'name' => 'Категория',
				'foreignModel' => 'category',
				'foreignParentKey' => 'propref_parent',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|cat|2'
			]
		]
	];
}
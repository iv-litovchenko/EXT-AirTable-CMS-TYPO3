<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialForeignField
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: Poly: 2 Колонка',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'foreign_field' => [
				'type' => 'Input',
				'name' => 'Poly: 2 Колонка',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|rels|-3'
			]
		]
	];
}
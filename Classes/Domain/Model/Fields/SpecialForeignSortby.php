<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialForeignSortby
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: Poly: 4 Сортировка',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'foreign_sortby' => [
				'type' => 'Input.Number',
				'name' => 'Poly: 4 Сортировка',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|rels|-1'
			]
		]
	];
}
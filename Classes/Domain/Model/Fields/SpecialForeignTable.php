<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialForeignTable
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: Poly: 1 Таблица',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'foreign_table' => [
				'type' => 'Input',
				'name' => 'Poly: 1 Таблица',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|rels|-4'
			]
		]
	];
}
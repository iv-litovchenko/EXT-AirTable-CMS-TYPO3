<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialForeignUid
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: Poly: 3 Запись (ID)',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'foreign_uid' => [
				'type' => 'Input.Number',
				'name' => 'Poly: 3 Запись (ID)',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|rels|-2'
			]
		]
	];
}
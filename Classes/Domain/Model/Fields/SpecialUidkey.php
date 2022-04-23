<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Input;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialUidkey extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: IDKey',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
		],
		'_fields' => [
			'uidkey' => [
				'type' => 'SpecialUidkey',
				'name' => 'Специальное: IDKey',
				'liveSearch' => 1,
				'required' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'show' => 1,
				'position' => '*|main|0'
			]
		]
	];
}
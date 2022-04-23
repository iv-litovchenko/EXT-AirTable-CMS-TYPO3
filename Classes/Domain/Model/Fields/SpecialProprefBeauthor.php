<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialProprefBeauthor
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: автор записи',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			'propref_beauthor' => [
				'type' => 'Rel_MTo1',
				'name' => 'Автор записи',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Users\BeUsers',
				'readOnly' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|main|10'
			]
		]
	];
}
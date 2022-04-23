<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialProprefAttributes
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
			# public function builderRefCustomAttributes(){
			# 	return $this->refProvider('propref_attributes');
			# }
			'propref_attributes' => [
				'type' => 'Rel_Eav',
				'name' => 'Характеристики (атрибуты)',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Eav\SysAttribute',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|attrs|0'
			],
			#'entity_id' => [
			#	'type' => 'Input.Int',
			#	'name' => 'entity_id',
			#	'show' => 1,
			#	'doNotCheck' => 1,
			#	'position' => [
			#		'*'	=> 'attrs,0'
			#	]
			#]
		]
	];
}
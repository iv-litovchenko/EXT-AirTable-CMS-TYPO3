<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialProprefParent
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: родительская запись',
		'_propertyAnnotations' => [
		],
		'_fields' => [
			// Получить всех родителей (root-line)
			# public function parentrootline(){
			# 	return $this->hasOne(get_class($this),'uid','propref_parent')->with('parentrootline');
			# }
			
			// Получить всех дочек
			# public function childrens(){
			# 	return $this->hasMany(get_class($this),'propref_parent','uid')->with('childrens');
			# }
			'propref_parent' => [
				'type' => 'Rel_MTo1.Tree',
				'name' => 'Родительская запись',
				'foreignModel' => 'current',
				'foreignParentKey' => 'propref_parent',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
				'position' => '*|cat|3'
			]
		]
	];
}
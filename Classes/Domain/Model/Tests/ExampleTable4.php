<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable4 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица 4 (M:M)',
		'baseFields' => [
			'RType',
			'title',
			'disabled',
			'deleted',
			'sorting'
		],
		'dataFields' => [],
		'relationalFields' => [
			'proprefinv_exampletable' => [
				'type' => 'Rel_MToM_Inverse',
				'name' => 'Основная таблица',
				'position' => '*|rels|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
				'foreignKey' => 'propref_exampletable4',
			]
		]
	];
}
?>
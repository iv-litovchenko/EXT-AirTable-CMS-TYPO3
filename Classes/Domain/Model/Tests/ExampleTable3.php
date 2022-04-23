<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable3 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица 4 (M:1)',
		'baseFields' => [
			'RType' => [
				'items' => [
					1 => 'Тип 1',
					2 => 'Тип 2',
					3 => 'Тип 3'
				]
			],
			'title',
			'disabled',
			'deleted',
			'sorting'
		],
		'dataFields' => [],
		'relationalFields' => [
			'proprefinv_exampletable' => [
				'type' => 'Rel_MTo1_Inverse',
				'name' => 'Основная таблица',
				'position' => '*|rels|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
				'foreignKey' => 'propref_exampletable3',
			]
		]
	];
}
?>
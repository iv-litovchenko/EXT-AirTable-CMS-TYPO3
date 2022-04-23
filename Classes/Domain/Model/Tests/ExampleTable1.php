<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable1 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица 1 (1:1)',
		'baseFields' => [
			'title',
			'disabled',
			'deleted',
			'sorting'
		],
		'dataFields' => [],
		'relationalFields' => [
			'proprefinv_exampletable' => [
				'type' => 'Rel_1To1_Inverse',
				'name' => 'Основная таблица',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
				'foreignKey' => 'propref_exampletable1',
			]
		]
	];
}
?>
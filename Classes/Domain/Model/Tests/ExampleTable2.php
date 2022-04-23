<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable2 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица 2 (1:M)',
		'baseFields' => [
			'title',
			'disabled',
			'deleted',
			'sorting'
		],
		'dataFields' => [],
		'relationalFields' => [
			'proprefinv_exampletable' => [
				'type' => 'Rel_1ToM_Inverse',
				'name' => 'Основная таблица',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
				'foreignKey' => 'propref_exampletable2',
			]
		]
	];
}
?>